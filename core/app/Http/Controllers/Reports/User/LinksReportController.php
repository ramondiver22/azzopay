<?php

namespace App\Http\Controllers\Reports\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paymentlink;
use App\Models\Transactions;
use App\Models\Currency;

class LinksReportController  extends Controller {

    public function index(Request $request) {

        $data['lang'] = parent::getLanguageVars("user_reports_page");
        $data['title']=  "Relatório de Links de Pagamento";


        $startDate = convert_brl_date_time($request->start_date . " 00:00:00");
        $endDate = convert_brl_date_time($request->end_date . " 23:59:59");
        $userId = Auth::guard('user')->user()->id;

        if ($startDate == null && $endDate == null) {
            $startDate = new \DateTime(date("Y-m-d H:i:s"));
            $startDate->sub(new \DateInterval("P30D"));
            $endDate = new \DateTime(date("Y-m-d H:i:s"));
        }

        $data['links'] = Paymentlink::from("payment_link ", "p")
                  ->where("p.user_id", $userId)
                  ->join("transactions AS t", "p.id", "t.payment_link")
                  ->where("t.status", 1)
                  ->where("t.type", 1)
                  ->where("t.created_at", ">=", $startDate->format("Y-m-d H:i:s"))
                  ->where("t.created_at", "<=", $endDate->format("Y-m-d H:i:s"))
                  ->orderBy("p.id")
                  ->select("p.ref_id", "p.name", "t.amount", "t.charge", "t.created_at")
                  ->get();

        $currency=Currency::whereStatus(1)->first(); 
        $data['currency']=  $currency;
        $data['startDate']=  $startDate->format("d/m/Y");
        $data['endDate']=  $endDate->format("d/m/Y");

        return view('user.reports.links', $data);
    }
    
}