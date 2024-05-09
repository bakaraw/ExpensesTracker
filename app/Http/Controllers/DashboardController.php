<?php

namespace App\Http\Controllers;

use App\Models\BudgetPortions;
use App\Models\Transactions;
use App\Models\UserBudget;
use App\Models\BudgetType;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{

    public function index()
    {

        $money_in_sum = $this->getSum(0);
        $money_out_sum = $this->getSum(1);
        $largest_spent = $this->getLargestSpent();

        return view('dashboard', [
            'sum_money_in' => $money_in_sum,
            'sum_money_out' => $money_out_sum,
            'sum_savings' => $this->getSavingsSum(),
            'largest_spent' => $largest_spent,
            'largest_spent_cat_name' => $this->getLargestSpentCategoryName($largest_spent),
            'budget_type' => $this->getBudgetTypeId(),
            'categories' => $this->getAllCategories(),
            'user_portion_categories' => $this->getUserPortionCategories()
        ]);
    }

    public function userId()
    {
        return Auth()->user()->getAuthIdentifier();
    }

    public function getAllCategories()
    {
        return ExpenseCategory::all();
    }

    public function getBudgetTypeId()
    {

        $budget = $this->getUserBudget($this->userId());
        $budget_type = BudgetType::where('id', $budget->type)->first();
        return $budget_type->id;
    }

    public function getUserBudget($user_id)
    {
        return UserBudget::where('user_id', $this->userId())->first();
    }

    public function getUserPortionCategories()
    {

        $budget = $this->getUserBudget($this->userId());
        $budget_id = $budget->budget_id;
        $portion_categories = BudgetPortions::where('budget_id', $budget_id)->get();

        $categories = [];

        foreach ($portion_categories as $portion_category) {
            array_push($categories, $portion_category->category);
        }

        return $categories;
    }

    public function getSum($is_money_out)
    {
        $transactions = $this->getTransactions();
        $sum = $transactions->where('is_money_out', $is_money_out)->sum('amount');
        return $sum;
    }

    public function getSavingsSum()
    {
        $transactions = $this->getTransactions();
        $savings_category = ExpenseCategory::where('name', 'Savings')->first()->id;
        $sum = $transactions->where('category', $savings_category)->sum('amount');
        return $sum;
    }

    public function getTransactions()
    {

        $transactions = Transactions::where('user_id', $this->userId())->get();
        return $transactions;
    }

    public function getLargestSpent()
    {
        $largestSpentTransaction = Transactions::where('user_id', $this->userId())
            ->where('is_money_out', 1)
            ->orderBy('amount', 'desc')
            ->first();

        return $largestSpentTransaction;
    }

    public function getLargestSpentCategoryName($largestSpentTransaction)
    {
        if(!isset($largestSpentTransaction->category)){
            return null;
        }

        $categories = $this->getAllCategories();
        foreach ($categories as $category) {
            if ($category->id == $largestSpentTransaction->category) {
                return $category->name;
            }
        }

    }
}
