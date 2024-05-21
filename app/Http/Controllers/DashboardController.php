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
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function index()
    {
        $trans_controller = new TransactionsController();

        $money_in = 0;
        $money_out = 1;

        $money_in_sum = $trans_controller->getSum($money_in);
        $money_out_sum = $trans_controller->getSum($money_out);
        $largest_spent = $trans_controller->getLargestSpent();

        $data = [
            'budget_portions' => BudgetPortionsController::getBudgetPortions(),
            'user_portion_categories' => BudgetPortionsController::getUserPortionCategories(),
            'alloc_budget' => BudgetController::getBudgetAlloc(),
            'budget_type_name' => BudgetController::getTypeName(),
            'trans_with_category' => $trans_controller->getTransactionsWithCategory($money_out),
            'sum_money_in' => $money_in_sum,
            'sum_money_out' => $money_out_sum,
            'sum_savings' => $trans_controller->getSavingsSum(),
            'largest_spent' => $largest_spent,
            'money_out_data' => $trans_controller->getAllChartData($money_out),
            'money_in_data' => $trans_controller->getAllChartData($money_in),
            'w_money_out_data' => $trans_controller->getWeeklyAmounts($money_out),
            'w_money_in_data' => $trans_controller->getWeeklyAmounts($money_in),
            'm_money_out_data' => $trans_controller->getMonthlyAmounts($money_out),
            'm_money_in_data' => $trans_controller->getMonthlyAmounts($money_in),
            'largest_spent_cat_name' => $trans_controller->getLargestSpentCategoryName($largest_spent),
            'categories' => $trans_controller->getAllCategories(),
            'd_savings' => $trans_controller->getDailySavings(),
            'w_savings' => $trans_controller->getWeeklySavings(),
            'm_savings' => $trans_controller->getMonthlySavings()
        ];

        return view('dashboard', $data);
    }
}
