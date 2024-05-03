<?php

namespace App\Http\Controllers;

use App\Models\BudgetPortions;
use Illuminate\Http\Request;
use App\Models\UserBudget;
use Illuminate\Support\Facades\DB;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Log;

class BudgetPortionsController extends Controller
{
    public function setDefaultPortion($user_budget_alloc)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        $user_budget = UserBudget::where('user_id', Auth()->user()->getAuthIdentifier())->first();

        $portion_percentage = [
            'food' => [
                'id' => 1, 'portion' => 0.20
            ],
            'bills' => [
                'id' => 2, 'portion' => 0.20
            ],
            'internet' => [
                'id' => 3, 'portion' => 0.10
            ],
            'entertainment' => [
                'id' => 4, 'portion' => 0.10
            ],
            'shopping' => [
                'id' => 5, 'portion' => 0.20
            ],
            'savings' => [
                'id' => 6, 'portion' => 0.20
            ],
        ];

        foreach ($portion_percentage as $portions) {
            $budget_portion = new BudgetPortions();
            $budget_portion->budget_id = $user_budget->budget_id;
            $budget_portion->category = $portions['id'];
            $budget_portion->portion = $user_budget_alloc * $portions['portion'];
            $budget_portion->save();
        }
    }

    public function newUserPortion()
    {
        $user_id = Auth()->user()->getAuthIdentifier();
    }

    public function editPortion(Request $request)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        $portion_id = $request->input('portion_id');
        $portion = $request->input('portion');
        $budget_table = DB::table('user_budgets')->where('user_id', $user_id);
        $budget = $budget_table->first();
        $budget_portion_table = DB::table('budget_portions')->where('budget_id', $budget->budget_id);



        if ($request->input('action') == 'save') {

            BudgetPortions::whereIn('portion_id', [$portion_id])
                ->update([
                    'portion' => $portion
                ]);

            $sum_of_portions = $budget_portion_table->sum('portion');
            UserBudget::whereIn('budget_id', [$budget->budget_id])
                ->update([
                    'alloc_budget' => $sum_of_portions
                ]);
        } else {
            BudgetPortions::whereIn('portion_id', [$portion_id])->delete();

            $sum_of_portions = $budget_portion_table->sum('portion');
            UserBudget::whereIn('budget_id', [$budget->budget_id])
                ->update([
                    'alloc_budget' => $sum_of_portions
                ]);
        }

        $budget = $budget_table->first();
        $categories = ExpenseCategory::all();
        $budget_portions = $budget_portion_table->get();

        return view('auth.portion-budget', [
            'budget' => $budget->alloc_budget,
            'budget_portions' => $budget_portions,
            'categories' => $categories
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetPortions $budgetPortions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetPortions $budgetPortions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BudgetPortions $budgetPortions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetPortions $budgetPortions)
    {
        //
    }
}
