<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBudget;
use Illuminate\Support\Facades\DB;

class NewUserController extends Controller
{
    public function index(Request $request)
    {

        $user = DB::table('user_budgets')->where('user_id', Auth()->user()->getAuthIdentifier())->first();
        $user_name = $request->user();
        if ($user == null) {
            return view('auth.welcome-user', [
                'user' => $user_name
            ]);
        }
        return redirect('/dashboard');
    }

    public function newUserSetup(Request $request)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        $user = DB::table('user_budgets')->where('user_id', $user_id)->first();

        if ($user == null) {
            $userId = auth()->user()->getAuthIdentifier();
            $userBudget = new UserBudget();
            $userBudget->user_id = $userId;
            $userBudget->type = $request->input('budget_type');
            $userBudget->alloc_budget = $request->input('alloc_budget');
            $userBudget->save();

            app(BudgetPortionsController::class)->setDefaultPortion($userBudget->alloc_budget);

        } else {
            UserBudget::whereIn('user_id', [$user_id])
                ->update([
                    'type' => $request->input('budget_type'),
                    'alloc_budget' => $request->input('alloc_budget')
                ]);
        }

        return redirect('/portion_budget');
    }
}
