<?php

namespace App\Http\Controllers\Reports\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Currency;
use DB;

class OrdersReportController  extends Controller {

    public function index(Request $request) {

        $data['lang'] = parent::getLanguageVars("user_reports_page");
        $data['title']=  "Relatório de Vendas";


        $startDate = convert_brl_date_time($request->start_date . " 00:00:00");
        $endDate = convert_brl_date_time($request->end_date . " 23:59:59");
        $userId = Auth::guard('user')->user()->id;

        if ($startDate == null && $endDate == null) {
            $startDate = new \DateTime(date("Y-m-d H:i:s"));
            $startDate->sub(new \DateInterval("P30D"));
            $endDate = new \DateTime(date("Y-m-d H:i:s"));
        }

        $data['orders'] = Order::where("seller_id", $userId)
                  ->where("status", 1)
                  ->where("created_at", ">=", $startDate->format("Y-m-d H:i:s"))
                  ->where("created_at", "<=", $endDate->format("Y-m-d H:i:s"))
                  ->orderBy("created_at")
                  ->select(
                      "ref_id", 
                      "first_name", 
                      "last_name", 
                      "created_at", 
                      DB::raw("SUM(amount) AS amount"),
                      DB::raw("SUM(charge) AS charge"),
                      DB::raw("SUM(total) AS total"),
                      DB::raw("SUM(shipping_fee) AS shipping_fee")
                  )
                  ->groupBy("ref_id", "first_name", "last_name", "created_at")
                  ->get();

        $currency=Currency::whereStatus(1)->first(); 
        $data['currency']=  $currency;
        $data['startDate']=  $startDate->format("d/m/Y");
        $data['endDate']=  $endDate->format("d/m/Y");

        return view('user.reports.orders', $data);
    }
    
}