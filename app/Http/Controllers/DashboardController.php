<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function index(){
        return view('dashboard');
    }

    public function getMoneyIn(){

    }

    public function getTransactions(){

        $user_id = Auth()->user()->getAuthIdentifier();
        $transactions = Transactions::where('user_id', $user_id)->get();
        return $transactions;

    }
}
