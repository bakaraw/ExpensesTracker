<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBudget;
use App\Models\BudgetPortions;
use App\Models\BudgetType;
use App\SafeSubmit\SafeSubmit;
use Illuminate\Support\Facades\Log;

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

    public static function getAllType(){
        return BudgetType::all();
    }

    public function update(Request $request, SafeSubmit $safeSubmit){

        $old_budget_alloc = BudgetController::getBudgetAlloc();

        UserBudget::where('user_id', BudgetController::userId())->update([
            'type' => $request->input('budget-type'),
            'alloc_budget' => $request->input('alloc-budget'),
        ]);

        $new_budget_alloc = $request->input('alloc-budget');
        $diff = $new_budget_alloc - $old_budget_alloc;

        $budget_portions = BudgetPortionsController::getBudgetPortions();
        $budget_portions_count = count($budget_portions);
        $user_budget = BudgetController::getUserBudget();

        $diff /= $budget_portions_count;

        foreach($budget_portions as $budget_portion){
            BudgetPortions::where('budget_id', $user_budget->budget_id)
            ->where('category_id', $budget_portion->category->id)
            ->update([
                'portion' => $budget_portion->portion + $diff,
            ]);
        }


        return $safeSubmit->intended(route('budgeting'));
    }

}
