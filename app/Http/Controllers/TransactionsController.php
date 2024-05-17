<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\SafeSubmit\SafeSubmit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{

    public function goToBudgeting(Request $request,  SafeSubmit $safeSubmit)
    {
        $this->storeMoneyOut($request);
        return $safeSubmit->intended(route('budgeting'));
    }

    public function goToDashboard(Request $request,  SafeSubmit $safeSubmit)
    {
        $this->storeMoneyOut($request);
        return $safeSubmit->intended(route('dashboard'));
    }

    public function storeMoneyIn(Request $request, SafeSubmit $safeSubmit)
    {
        $is_money_out = $request->input('is-money-out');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $user_id = Auth()->user()->getAuthIdentifier();

        $this->storeTransaction($user_id, $note, $amount, null, $is_money_out);
        return $safeSubmit->intended(route('dashboard'));
    }

    public function storeMoneyOut(Request $request)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        $is_money_out = $request->input('is-money-out');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $category = $request->input('category');

        $this->storeTransaction($user_id, $note, $amount, $category, $is_money_out);
    }

    public function storeSavings(Request $request, SafeSubmit $safeSubmit)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        // money_out = 1 indicates that we are refering to a money out
        $is_money_out = 1;
        $amount = $request->input('amount');
        $savings_category = ExpenseCategory::where('name', 'Savings')->first()->id;

        $this->storeTransaction($user_id, null, $amount, $savings_category, $is_money_out);
        return $safeSubmit->intended(route('dashboard'));
    }

    public function storeTransaction($user_id, $note, $amount, $category, $is_money_out)
    {
        $transactions = new Transactions();
        $transactions->user_id = $user_id;
        $transactions->note = $note;
        $transactions->amount = $amount;
        $transactions->category_id = $category;
        $transactions->is_money_out = $is_money_out;

        $transactions->save();
    }

    public function getCategoryTransactions()
    {
        $user_budget = BudgetController::getUserBudget();
        $budget_portions = BudgetPortionsController::getBudgetPortions();
        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');

        $transactionsArr = [];
        foreach ($budget_portions as $budget_portion) {
            switch ($user_budget->type) {
                case $daily_id:
                    $currentDate = Carbon::now();

                    $transactions = Transactions::where('user_id', $this->userId())
                        ->whereDate('created_at', $currentDate->toDateString())
                        ->where('category_id', $budget_portion->category->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $transactionsArr[$budget_portion->category->name] = $transactions;

                    break;
                case $weekly_id:
                    $currentWeekStart = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now();

                    $transactions = Transactions::where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentWeekStart, $endDate])
                        ->where('category_id', $budget_portion->category->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $transactionsArr[$budget_portion->category->name] = $transactions;
                    break;
                case $monthly_id:
                    $currentMonthStart = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now();
                    $transactions = Transactions::where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentMonthStart, $endDate])
                        ->where('category_id', $budget_portion->category->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $transactionsArr[$budget_portion->category->name] = $transactions;
                    break;
            }
        }

        return $transactionsArr;
    }

    public function getTransactionsWithCategory($is_money_out)
    {
        $user_budget = BudgetController::getUserBudget();
        $transactions = Transactions::with('category')
            ->where('user_id', $this->userId());
        $budget_portions = BudgetPortionsController::getBudgetPortions();

        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');

        $sum = -1;

        $totalTransactionArr = [];
        foreach ($budget_portions as $budget_portion) {
            switch ($user_budget->type) {
                case $daily_id:
                    $currentDate = Carbon::now();
                    $sum = Transactions::with('category')
                        ->where('user_id', $this->userId())
                        ->whereDate('created_at', $currentDate->toDateString())
                        ->where('is_money_out', $is_money_out)
                        ->where('category_id', $budget_portion->category_id)
                        ->sum('amount');
                    $totalTransactionArr[$budget_portion->category->name] = $sum;
                    break;
                case $weekly_id:
                    $currentWeekStart = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now();
                    $sum = Transactions::with('category')
                        ->where('user_id', $this->userId())
                        ->where('is_money_out', $is_money_out)
                        ->whereBetween('created_at', [$currentWeekStart, $endDate])
                        ->where('category_id', $budget_portion->category->id)
                        ->sum('amount');
                    $totalTransactionArr[$budget_portion->category->name] = $sum;
                    Log::debug('weekly: ' . $budget_portion->category->name . ' Sum: ' . $sum);
                    break;
                case $monthly_id:
                    $currentMonthStart = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now();
                    $sum = Transactions::with('category')
                        ->where('user_id', $this->userId())
                        ->where('is_money_out', $is_money_out)
                        ->whereBetween('created_at', [$currentMonthStart, $endDate])
                        ->where('category_id', $budget_portion->category_id)
                        ->sum('amount');
                    $totalTransactionArr[$budget_portion->category->name] = $sum;
                    Log::debug("type: " . $user_budget->type);
                    break;
            }
        }


        return $totalTransactionArr;
    }

    public function getSum($is_money_out)
    {
        $user_budget = BudgetController::getUserBudget();
        $transactions = $this->getTransactions();
        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');

        // -1 indicates there's somethings wrong in the switch statement
        $sum = -1;

        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();

                $sum = Transactions::where('user_id', $this->userId())->whereDate('created_at', $currentDate->toDateString())
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $sum =  Transactions::where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');

                Log::debug('weekly sum: ' . $sum);
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $sum =  Transactions::where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentMonthStart, $endDate])
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');
                break;
        }

        return $sum;
    }
    public function getSavingsSum()
    {
        $user_budget = BudgetController::getUserBudget();
        $savings_category = ExpenseCategory::where('name', 'Savings')->first()->id;
        $transactions = $this->getTransactions();
        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');

        // -1 indicates there's somethings wrong in the switch statement
        $sum = -1;

        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $sum = Transactions::where('user_id', $this->userId())->whereDate('created_at', $currentDate->toDateString())
                    ->where('is_money_out', 1)
                    ->sum('amount');
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $sum =  Transactions::where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->where('category_id', $savings_category)
                    ->sum('amount');

                Log::debug('savings sum: ' . $user_budget->type);
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $sum =  Transactions::where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentMonthStart, $endDate])
                    ->where('category_id', $savings_category)
                    ->sum('amount');
                break;
        }

        return $sum;
    }

    public function getTransactions()
    {
        $transactions = Transactions::where('user_id', $this->userId())->get();
        return $transactions;
    }

    public function getLargestSpent()
    {
        $user_budget = BudgetController::getUserBudget();
        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');
        $largestSpent = 0;

        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $largestSpent = Transactions::where('user_id', $this->userId())
                    ->where('is_money_out', 1)
                    ->whereDate('created_at', $currentDate->toDateString())
                    ->orderBy('amount', 'desc')
                    ->first();
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $largestSpent = Transactions::where('user_id', $this->userId())
                    ->where('is_money_out', 1)
                    ->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->orderBy('amount', 'desc')
                    ->first();
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $largestSpent = Transactions::where('user_id', $this->userId())
                    ->where('is_money_out', 1)
                    ->whereBetween('created_at', [$currentMonthStart, $endDate])
                    ->orderBy('amount', 'desc')
                    ->first();
                break;
        }

        return $largestSpent;
    }

    public function getLargestSpentCategoryName($largestSpentTransaction)
    {
        if (!isset($largestSpentTransaction->category_id)) {
            return null;
        }

        $categories = $this->getAllCategories();
        foreach ($categories as $category) {
            if ($category->id == $largestSpentTransaction->category_id) {
                return $category->name;
            }
        }
    }

    public function getAllChartData($is_money_out)
    {

        $startDate = Carbon::now()->subDays(30)->startOfDay();

        $results = Transactions::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->where('is_money_out', $is_money_out) // Assuming $is_money_out is a variable indicating whether it's a money out transaction
            ->whereBetween('created_at', [$startDate, now()]) // Filter transactions within the last 30 days up to the present time
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();


        $data = []; // Initialize an empty array to hold data points

        foreach ($results as $result) {
            $data[] = [
                'x' => $result->date,
                'y' => $result->total_amount
            ];
        }

        // If no results were found, return a default data point
        if (empty($data)) {
            $data[] = [
                'x' => date('M d'), // Format current month and day
                'y' => 0
            ];
        }

        // Encode the data array to JSON format for the final return
        return json_encode($data);
    }

    public function getWeeklyAmounts($is_money_out)
    {
        $startDate = now()->subMonths(3); // Get the date 3 months ago from today
        $endDate = now(); // Today's date

        // Query to fetch total amount per week for the past 3 months
        $results = Transactions::select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at), 2, '0')) as week_number"), DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->where('is_money_out', $is_money_out)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();

        $data = []; // Initialize an empty array to hold data points

        foreach ($results as $result) {
            $data[] = [
                'x' => $result->week_number,
                'y' => $result->total_amount
            ];
        }

        // If no results were found, return a default data point
        if (empty($data)) {
            $data[] = [
                'x' => 'no data yet', // Format current month and day
                'y' => 0
            ];
        }

        // Encode the data array to JSON format for the final return
        return json_encode($data);
    }

    public function getMonthlyAmounts($is_money_out)
    {
        // Get the start date (12 months ago from today)
        $startDate = Carbon::now()->subMonths(12)->startOfMonth();

        // Get today's date
        $endDate = Carbon::now();

        // Query to fetch total amount per month for the past 12 months
        $results = Transactions::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->where('is_money_out', $is_money_out)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $data = []; // Initialize an empty array to hold data points

        foreach ($results as $result) {
            $data[] = [
                'x' => $result->year . "-" . $result->month,
                'y' => $result->total_amount
            ];
        }

        // If no results were found, return a default data point
        if (empty($data)) {
            $data[] = [
                'x' => 'no data yet', // Format current month and day
                'y' => 0
            ];
        }

        // Encode the data array to JSON format for the final return
        return json_encode($data);
    }

    public function userId()
    {
        return Auth()->user()->getAuthIdentifier();
    }

    public function getAllCategories()
    {
        return ExpenseCategory::all();
    }
}
