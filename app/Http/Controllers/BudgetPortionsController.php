<?php

namespace App\Http\Controllers;

use App\Models\BudgetPortions;
use Illuminate\Http\Request;
use App\Models\UserBudget;
use Illuminate\Support\Facades\DB;
use App\Models\ExpenseCategory;
use App\SafeSubmit\SafeSubmit;
use App\Http\Controllers\BudgetController;

class BudgetPortionsController extends Controller
{

    public function index()
    {

        return view('budgeting', [
            'alloc_budget' => BudgetController::getBudgetAlloc(),
            'budget_portions' => BudgetPortionsController::getBudgetPortions(),
        ]);
    }

    public function userId(){
        return Auth()->user()->getAuthIdentifier();
    }

    public function setDefaultPortion($user_budget_alloc)
    {
        $user_budget = BudgetController::getUserBudget();

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
            $budget_portion->category_id = $portions['id'];
            $budget_portion->portion = $user_budget_alloc * $portions['portion'];
            $budget_portion->save();
        }
    }


    public function editPortion(Request $request, SafeSubmit $safeSubmit)
    {

        $user_id = Auth()->user()->getAuthIdentifier();
        $portion_id = $request->input('portion_id');
        $portion = $request->input('portion');
        $budget_table = DB::table('user_budgets')->where('user_id', $user_id);
        $budget = $budget_table->first();

        if ($request->input('action') === 'save') {
            $this->updatePortion($portion_id, $portion);
        } else {
            $this->deletePortion($portion_id);
        }
        $this->updateAllocatedBudget($budget->budget_id);

        return $safeSubmit->intended(route('show.portion'));
    }

    public function addPortion(Request $request, SafeSubmit $safeSubmit)
    {


        $user_id = $this->userId();
        $budget_table = DB::table('user_budgets')->where('user_id', $user_id);
        $budget = $budget_table->first();

        $this->store($request, $budget->budget_id);
        $this->updateAllocatedBudget($budget->budget_id);

        return $safeSubmit->intended(route('show.portion'));
    }

    public function showPortion()
    {

        $user_id = $this->userId();
        $budget_table = DB::table('user_budgets')->where('user_id', $user_id);
        $budget = $budget_table->first();
        $budget_portion_table = DB::table('budget_portions')->where('budget_id', $budget->budget_id);
        $budget = UserBudget::where('user_id', $user_id)->first();
        $categories = ExpenseCategory::all();
        $budget_portions = $budget_portion_table->get();

        return view('auth.portion-budget', [
            'budget' => $budget->alloc_budget,
            'budget_portions' => $budget_portions,
            'categories' => $categories
        ]);
    }

    private function updatePortion($portion_id, $portion)
    {
        BudgetPortions::where('portion_id', $portion_id)->update(['portion' => $portion]);
    }

    private function deletePortion($portion_id)
    {
        BudgetPortions::where('portion_id', $portion_id)->delete();
    }

    private function updateAllocatedBudget($budget_id)
    {
        $sum_of_portions = DB::table('budget_portions')->where('budget_id', $budget_id)->sum('portion');

        UserBudget::where('budget_id', $budget_id)->update([
            'alloc_budget' => $sum_of_portions
        ]);
    }

    public static function getBudgetPortions()
    {
        $user_budget = BudgetController::getUserBudget();
        $portions = BudgetPortions::with('category')
            ->where('budget_id', $user_budget->budget_id)
            ->get();

        return $portions;
    }

    public static function getUserPortionCategories()
    {

        $budget = BudgetController::getUserBudget();
        $budget_id = $budget->budget_id;

        $portion_categories = BudgetPortions::with('category')
            ->where('budget_id', $budget_id)
            ->get();

        return $portion_categories;
    }


    /**
     * Display a listing of the resource.
     */


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
    public function store(Request $request, $budget_id)
    {
        $budgetPortions = new BudgetPortions();
        $budgetPortions->budget_id = $budget_id;
        $budgetPortions->category_id = $request->input('category');
        $budgetPortions->portion = $request->input('portion');
        $budgetPortions->save();

        return;
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
    public function update($column, $value, $updateArray)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetPortions $budgetPortions)
    {
        //
    }
}
