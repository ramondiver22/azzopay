<?php

namespace App\Http\Controllers\Reports\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Currency;

class ProductsReportController  extends Controller {

    public function index(Request $request) {

        $data['lang'] = parent::getLanguageVars("user_reports_page");
        $data['title']=  "Relatório de Produtos";


        $startDate = convert_brl_date_time($request->start_date . " 00:00:00");
        $endDate = convert_brl_date_time($request->end_date . " 23:59:59");
        $userId = Auth::guard('user')->user()->id;

        if ($startDate == null && $endDate == null) {
            $startDate = new \DateTime(date("Y-m-d H:i:s"));
            $startDate->sub(new \DateInterval("P30D"));
            $endDate = new \DateTime(date("Y-m-d H:i:s"));
        }

        $data['products'] = Product::from("orders", "o")
                  ->join("products AS p", "p.id", "o.product_id")
                  ->where("o.seller_id", $userId)
                  ->where("o.status", 1)
                  ->where("o.created_at", ">=", $startDate->format("Y-m-d H:i:s"))
                  ->where("o.created_at", "<=", $endDate->format("Y-m-d H:i:s"))
                  ->select(
                    "o.ref_id", 
                    "o.first_name", 
                    "o.last_name", 
                    "o.created_at", 
                    "o.amount",
                    "o.charge",
                    "o.total",
                    "o.shipping_fee",
                    "p.name",
                    "o.quantity"
                  )
                  ->orderBy("o.id")
                  ->get();

        $currency=Currency::whereStatus(1)->first(); 
        $data['currency']=  $currency;
        $data['startDate']=  $startDate->format("d/m/Y");
        $data['endDate']=  $endDate->format("d/m/Y");

        return view('user.reports.products', $data);
    }
    
}