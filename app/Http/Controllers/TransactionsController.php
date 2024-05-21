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

    public function index()
    {
        $trans_controller = new TransactionsController();

        $money_in = 0;
        $money_out = 1;

        $money_in_sum = $trans_controller->getSum($money_in);
        $money_out_sum = $trans_controller->getSum($money_out);

        $search = session('search');
        $searched_trans = session('searched_trans');

        $start_date = session('start_date');
        $end_date = session('end_date');
        $searched_trans_by_date = session('searched_trans_by_date');

        $datas = [
            'user_budget' => BudgetController::getUserBudget(),
            'sum_money_in' => $money_in_sum,
            'sum_money_out' => $money_out_sum,
            'sum_savings' => $trans_controller->getSavingsSum(),
            'trans_based_of_type' => $trans_controller->getTransactionsBasedOfBudgetType(),
            'search' => $search,
            'searched_trans' => $searched_trans,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'searched_trans_by_date' => $searched_trans_by_date,
            'money_out_data' => $trans_controller->getAllChartData($money_out),
            'money_in_data' => $trans_controller->getAllChartData($money_in),
            'w_money_out_data' => $trans_controller->getWeeklyAmounts($money_out),
            'w_money_in_data' => $trans_controller->getWeeklyAmounts($money_in),
            'm_money_out_data' => $trans_controller->getMonthlyAmounts($money_out),
            'm_money_in_data' => $trans_controller->getMonthlyAmounts($money_in),
            'budget_portions' => BudgetPortionsController::getBudgetPortions(),
            'd_savings' => $trans_controller->getDailySavings(),
            'w_savings' => $trans_controller->getWeeklySavings(),
            'm_savings' => $trans_controller->getMonthlySavings(),
        ];

        return view('transactions', $datas);
    }

    public function filterByDate(Request $request, SafeSubmit $safeSubmit)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $transactions = Transactions::with('category')
            ->where('user_id', $this->userId())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $safeSubmit->intended(route('transactions'))->with([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'searched_trans_by_date' => $transactions
        ]);
    }

    public function search(Request $request, SafeSubmit $safeSubmit)
    {
        $search = $request->input('search');

        $transactions = Transactions::where('user_id', $this->userId())
            ->where(function ($query) use ($search) {
                $query->where('note', 'like', "%$search%")
                    ->orWhere('created_at', 'like', "%$search%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            })
            ->get();

        Log::debug('searched: ' . $transactions);

        return $safeSubmit->intended(route('transactions'))->with([
            'search' => $search,
            'searched_trans' => $transactions,
        ]);
    }

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

    public function goToDashboardMoneyIn(Request $request,  SafeSubmit $safeSubmit)
    {
        $this->storeMoneyIn($request);
        return $safeSubmit->intended(route('dashboard'));
    }

    public function goToTransactionsMoneyIn(Request $request,  SafeSubmit $safeSubmit)
    {
        $this->storeMoneyIn($request);
        return $safeSubmit->intended(route('transactions'));
    }

    public function goToTransactionsMoneyOut(Request $request,  SafeSubmit $safeSubmit)
    {
        $this->storeMoneyOut($request);
        return $safeSubmit->intended(route('transactions'));
    }

    public function storeMoneyIn(Request $request)
    {
        $is_money_out = $request->input('is-money-out');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $user_id = Auth()->user()->getAuthIdentifier();

        $this->storeTransaction($user_id, $note, $amount, null, $is_money_out);
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
        $userBudget = BudgetController::getUserBudget();
        $budgetPortions = BudgetPortionsController::getBudgetPortions();
        $budgetTypeIds = [
            'daily' => BudgetController::getTypeId('daily'),
            'weekly' => BudgetController::getTypeId('weekly'),
            'monthly' => BudgetController::getTypeId('monthly'),
        ];

        $dateRanges = [
            $budgetTypeIds['daily'] => [
                Carbon::now()->toDateString(),
                Carbon::now()->toDateString(),
            ],
            $budgetTypeIds['weekly'] => [
                Carbon::now()->startOfWeek(),
                Carbon::now(),
            ],
            $budgetTypeIds['monthly'] => [
                Carbon::now()->startOfMonth(),
                Carbon::now(),
            ],
        ];

        $transactionsArr = [];
        foreach ($budgetPortions as $budgetPortion) {
            $categoryId = $budgetPortion->category->id;
            $categoryName = $budgetPortion->category->name;

            if (isset($dateRanges[$userBudget->type])) {
                [$startDate, $endDate] = $dateRanges[$userBudget->type];

                $transactions = Transactions::where('user_id', $this->userId())
                    ->where('category_id', $categoryId)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();

                $transactionsArr[$categoryName] = $transactions;
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

                if ($is_money_out == 1) {
                    $sum = Transactions::with('category')
                        ->where('user_id', $this->userId())->whereDate('created_at', $currentDate->toDateString())
                        ->where('is_money_out', $is_money_out)
                        ->whereHas('category', function ($query) {
                            $query->where('name', '!=', 'Savings');
                        })
                        ->sum('amount');
                } else {
                    $sum = Transactions::where('user_id', $this->userId())->whereDate('created_at', $currentDate->toDateString())
                        ->where('is_money_out', $is_money_out)
                        ->sum('amount');
                }

                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                if ($is_money_out == 1) {
                    $sum =  Transactions::with('category')
                        ->where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentWeekStart, $endDate])
                        ->where('is_money_out', $is_money_out)
                        ->whereHas('category', function ($query) {
                            $query->where('name', '!=', 'Savings');
                        })
                        ->sum('amount');
                } else {
                    $sum =  Transactions::where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentWeekStart, $endDate])
                        ->where('is_money_out', $is_money_out)
                        ->sum('amount');
                }

                Log::debug('weekly sum: ' . $sum);
                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                if ($is_money_out == 1) {
                    $sum =  Transactions::with('category')
                        ->where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentMonthStart, $endDate])
                        ->where('is_money_out', $is_money_out)
                        ->sum('amount');
                } else {
                    $sum =  Transactions::where('user_id', $this->userId())
                        ->whereBetween('created_at', [$currentMonthStart, $endDate])
                        ->where('is_money_out', $is_money_out)
                        ->sum('amount');
                }

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

    public function getTransactionsBasedOfBudgetType()
    {
        $daily_id = BudgetController::getTypeId('daily');
        $weekly_id = BudgetController::getTypeId('weekly');
        $monthly_id = BudgetController::getTypeId('monthly');
        $user_budget = BudgetController::getUserBudget();

        $transactions = '';
        switch ($user_budget->type) {
            case $daily_id:
                $currentDate = Carbon::now();
                $transactions = Transactions::with('category')
                    ->where('user_id', $this->userId())
                    ->whereDate('created_at', $currentDate->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
            case $weekly_id:
                $currentWeekStart = Carbon::now()->startOfWeek();
                $endDate = Carbon::now();
                $transactions =  Transactions::with('category')
                    ->where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentWeekStart, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();

                break;
            case $monthly_id:
                $currentMonthStart = Carbon::now()->startOfMonth();
                $endDate = Carbon::now();
                $transactions =  Transactions::with('category')
                    ->where('user_id', $this->userId())
                    ->whereBetween('created_at', [$currentMonthStart, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }
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

        if ($is_money_out == 1) {
            $results = Transactions::with('category')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as total_amount')
                )
                ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
                ->where('is_money_out', $is_money_out) // Assuming $is_money_out is a variable indicating whether it's a money out transaction
                ->whereBetween('created_at', [$startDate, now()]) // Filter transactions within the last 30 days up to the present time
                ->whereHas('category', function ($query) {
                    $query->where('name', '!=', 'Savings');
                })
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get();
        } else {
            $results = Transactions::with('category')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as total_amount')
                )
                ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
                ->where('is_money_out', $is_money_out) // Assuming $is_money_out is a variable indicating whether it's a money out transaction
                ->whereBetween('created_at', [$startDate, now()]) // Filter transactions within the last 30 days up to the present time
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get();
        }



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
        if ($is_money_out == 1) {
            $results = Transactions::select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at), 2, '0')) as week_number"), DB::raw('SUM(amount) as total_amount'))
                ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
                ->where('is_money_out', $is_money_out)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('category', function ($query) {
                    $query->where('name', '!=', 'Savings');
                })
                ->groupBy('week_number')
                ->orderBy('week_number')
                ->get();
        } else {
            $results = Transactions::select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at), 2, '0')) as week_number"), DB::raw('SUM(amount) as total_amount'))
                ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
                ->where('is_money_out', $is_money_out)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('week_number')
                ->orderBy('week_number')
                ->get();
        }
        // Query to fetch total amount per week for the past 3 months


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

        if ($is_money_out == 1) {
            $results = Transactions::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
                ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
                ->where('is_money_out', $is_money_out)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('category', function ($query) {
                    $query->where('name', '!=', 'Savings');
                })
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } else {
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
        }
        // Query to fetch total amount per month for the past 12 months


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

    public function getDailySavings()
    {

        $startDate = Carbon::now()->subDays(30)->startOfDay();

        $results = Transactions::with('category')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->whereBetween('created_at', [$startDate, now()]) // Filter transactions within the last 30 days up to the present time
            ->whereHas('category', function ($query) {
                $query->where('name', 'Savings');
            })
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

    public function getWeeklySavings()
    {
        $startDate = now()->subMonths(3); // Get the date 3 months ago from today
        $endDate = now(); // Today's date
        $results = Transactions::select(DB::raw("CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at), 2, '0')) as week_number"), DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Savings');
            })
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

    public function getMonthlySavings()
    {
        // Get the start date (12 months ago from today)
        $startDate = Carbon::now()->subMonths(12)->startOfMonth();

        // Get today's date
        $endDate = Carbon::now();

        $results = Transactions::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('user_id', $this->userId()) // Assuming $this->userId() retrieves the current user's ID
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('category', function ($query) {
                $query->where('name', 'Savings');
            })
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
