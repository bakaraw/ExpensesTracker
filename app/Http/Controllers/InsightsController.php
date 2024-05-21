<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InsightsController extends Controller
{
    public function index()
    {
        $trans_controller = new TransactionsController();

        $money_in = 0;
        $money_out = 1;

        $datas = [
            'money_out_data' => $trans_controller->getAllChartData($money_out),
            'money_in_data' => $trans_controller->getAllChartData($money_in),
            'w_money_out_data' => $trans_controller->getWeeklyAmounts($money_out),
            'w_money_in_data' => $trans_controller->getWeeklyAmounts($money_in),
            'm_money_out_data' => $trans_controller->getMonthlyAmounts($money_out),
            'm_money_in_data' => $trans_controller->getMonthlyAmounts($money_in),
            'd_savings' => $trans_controller->getDailySavings(),
            'w_savings' => $trans_controller->getWeeklySavings(),
            'm_savings' => $trans_controller->getMonthlySavings(),
        ];

        return view('insights', $datas);
    }



    public function userId()
    {
        return Auth()->user()->getAuthIdentifier();
    }
}
