<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBudget;
use Illuminate\Support\Facades\DB;

class NewUserController extends Controller
{
    public function index(){

        $user = DB::table('user_budgets')->where('user_id', Auth()->user()->getAuthIdentifier())->first();

        if($user == null){
            return view('auth.new-user-setup');
        }
        return redirect('/dashboard');

    }

    public function newUserSetup(Request $request){
        $userId = auth()->user()->getAuthIdentifier();
        $userBudget = new UserBudget();
        $userBudget->user_id = $userId;
        $userBudget->type = $request->input('budget_type');
        $userBudget->alloc_budget = $request->input('alloc_budget');
        $userBudget->save();

        return redirect('/dashboard');
    }


}
