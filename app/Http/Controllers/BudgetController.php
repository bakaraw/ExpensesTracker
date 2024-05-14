<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBudget;
use App\Models\BudgetType;

class BudgetController extends Controller
{
    public static function getUserBudget()
    {
        return UserBudget::where('user_id', BudgetController::userId())->first();
    }

    public static function userId(){
        return Auth()->user()->getAuthIdentifier();
    }

    public static function getBudgetAlloc(){
        $alloc_budget = BudgetController::getUserBudget()->alloc_budget;
        return $alloc_budget;
    }

    public static function getTypeId($name)
    {
        $budget_type = BudgetType::where('name', $name)->first();
        return $budget_type->id;
    }

    public static function getTypeName()
    {
        $type_id = BudgetController::getUserBudget()->type;
        $type = BudgetType::where('id', $type_id)->first();
        return $type->name;
    }


}
