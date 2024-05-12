<?php

namespace App\Http\Controllers;

use App\Models\BudgetPortions;
use App\Models\Transactions;
use App\Models\UserBudget;
use App\Models\BudgetType;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{

    public function index()
    {
        $money_in = 0;
        $money_out = 1;
        $user_budget = $this->getUserBudget();
        $money_in_sum = $this->getSum($money_in, $user_budget);
        $money_out_sum = $this->getSum($money_out, $user_budget);
        $largest_spent = $this->getLargestSpent($user_budget);

        $data = [
            'budget_portions' => $this->getBudgetPortions($user_budget),
            'alloc_budget' => $this->getAllocBudget($user_budget),
            'sum_money_in' => $money_in_sum,
            'sum_money_out' => $money_out_sum,
            'sum_savings' => $this->getSavingsSum($user_budget),
            'largest_spent' => $largest_spent,
            'money_out_data' => $this->getAllChartData($money_out),
            'money_in_data' => $this->getAllChartData($money_in),
            'w_money_out_data' => $this->getWeeklyAmounts($money_out),
            'w_money_in_data' => $this->getWeeklyAmounts($money_in),
            'm_money_out_data' => $this->getMonthlyAmounts($money_out),
            'm_money_in_data' => $this->getMonthlyAmounts($money_in),
            'largest_spent_cat_name' => $this->getLargestSpentCategoryName($largest_spent),
            'budget_type_name' => $this->getTypeName($user_budget->type),
            'categories' => $this->getAllCategories(),
            'user_portion_categories' => $this->getUserPortionCategories()
        ];

        return view('dashboard', $data);
    }

    public function getBudgetPortions($user_budget)
    {
        $portions = BudgetPortions::with('category')
            ->where('budget_id', $user_budget->budget_id)
            ->get();

        return $portions;
    }

    public function getAllocBudget($user_budget)
    {
        return $user_budget->alloc_budget;
    }
    public function userId()
    {
        return Auth()->user()->getAuthIdentifier();
    }

    public function getAllCategories()
    {
        return ExpenseCategory::all();
    }

    public function getTypeId($name)
    {
        $budget_type = BudgetType::where('name', $name)->first();
        return $budget_type->id;
    }

    public function getUserBudget()
    {
        return UserBudget::where('user_id', $this->userId())->first();
    }

    public function getUserPortionCategories()
    {

        $budget = $this->getUserBudget();
        $budget_id = $budget->budget_id;

        $portion_categories = BudgetPortions::with('category')
            ->where('budget_id', $budget_id)
            ->get();

        return $portion_categories;
    }

    public function getSum($is_money_out, $user_budget)
    {
        $transactions = $this->getTransactions();
        $daily_id = $this->getTypeId('daily');
        $weekly_id = $this->getTypeId('weekly');
        $monthly_id = $this->getTypeId('monthly');

        // -1 indicates there's somethings wrong in the switch statement
        $sum = -1;

        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $sum = $transactions->whereDate('created_at', $currentDate->toDateString())
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $sum = $transactions->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $sum = $transactions->whereBetween('created_at', [$currentMonthStart, $endDate])
                    ->where('is_money_out', $is_money_out)
                    ->sum('amount');
                break;
        }

        return $sum;
    }

    public function getTypeName($type_id)
    {
        $type = BudgetType::where('id', $type_id)->first();
        return $type->name;
    }

    public function getSavingsSum($user_budget)
    {
        $savings_category = ExpenseCategory::where('name', 'Savings')->first()->id;
        $transactions = $this->getTransactions();
        $daily_id = $this->getTypeId('daily');
        $weekly_id = $this->getTypeId('weekly');
        $monthly_id = $this->getTypeId('monthly');

        // -1 indicates there's somethings wrong in the switch statement
        $sum = -1;

        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $sum = $transactions->whereDate('created_at', $currentDate->toDateString())
                    ->where('category_id', $savings_category)
                    ->sum('amount');
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $sum = $transactions->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->where('category', $savings_category)
                    ->sum('amount');
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $sum = $transactions->whereBetween('created_at', [$currentMonthStart, $endDate])
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

    public function getLargestSpent($user_budget)
    {
        $daily_id = $this->getTypeId('daily');
        $weekly_id = $this->getTypeId('weekly');
        $monthly_id = $this->getTypeId('monthly');
        $largestSpent = 0;

        $largestSpentTransaction = Transactions::where('user_id', $this->userId())
            ->where('is_money_out', 1)
            ->orderBy('amount', 'desc');
        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $largestSpent = $largestSpentTransaction->whereDate('created_at', $currentDate->toDateString())
                    ->first();
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $largestSpent = $largestSpentTransaction->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->first();
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $largestSpent = $largestSpentTransaction->whereBetween('created_at', [$currentMonthStart, $endDate])
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
}
