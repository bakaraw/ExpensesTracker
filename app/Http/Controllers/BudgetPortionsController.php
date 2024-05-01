<?php

namespace App\Http\Controllers;

use App\Models\BudgetPortions;
use Illuminate\Http\Request;
use App\Models\UserBudget;

class BudgetPortionsController extends Controller
{
    public function setDefaultPortion($user_budget_alloc)
    {
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

        // i is iterator

        foreach ($portion_percentage as $portions) {
            $budget_portion = new BudgetPortions();
            $budget_portion->budget_id = $user_budget->budget_id;
            $budget_portion->category = $portions['id'];
            $budget_portion->portion = $user_budget_alloc * $portions['portion'];
            $budget_portion->save();
        }
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
