<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\SafeSubmit\SafeSubmit;

class TransactionsController extends Controller
{
    public function storeMoneyIn(Request $request, SafeSubmit $safeSubmit)
    {
        $is_money_out = $request->input('is-money-out');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $user_id = Auth()->user()->getAuthIdentifier();

        $this->storeTransaction($user_id, $note, $amount, null, $is_money_out);
        return $safeSubmit->intended(route('dashboard'));
    }

    public function storeMoneyOut(Request $request, SafeSubmit $safeSubmit)
    {
        $user_id = Auth()->user()->getAuthIdentifier();
        $is_money_out = $request->input('is-money-out');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $category = $request->input('category');

        $this->storeTransaction($user_id, $note, $amount, $category, $is_money_out);
        return $safeSubmit->intended(route('dashboard'));
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
}
