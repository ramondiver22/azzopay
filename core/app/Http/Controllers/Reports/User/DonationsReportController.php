<?php

namespace App\Http\Controllers\Reports\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donations;
use App\Models\Currency;

class DonationsReportController  extends Controller {

    public function index(Request $request) {

        $data['lang'] = parent::getLanguageVars("user_reports_page");
        $data['title']=  "Relatório de Doações";

        $startDate = convert_brl_date_time($request->start_date . " 00:00:00");
        $endDate = convert_brl_date_time($request->end_date . " 23:59:59");
        $userId = Auth::guard('user')->user()->id;

        if ($startDate == null && $endDate == null) {
            $startDate = new \DateTime(date("Y-m-d H:i:s"));
            $startDate->sub(new \DateInterval("P30D"));
            $endDate = new \DateTime(date("Y-m-d H:i:s"));
        }

        $data['donations'] = Donations::where("payment_link.user_id", $userId)
                  ->join("payment_link", "donations.donation_id", "payment_link.id")
                  ->join("transactions", "donations.id", "transactions.donation_id")
                  ->leftJoin("users", "users.id", "donations.user_id")
                  ->where("donations.status", 1)
                  ->where("donations.created_at", ">=", $startDate->format("Y-m-d H:i:s"))
                  ->where("donations.created_at", "<=", $endDate->format("Y-m-d H:i:s"))
                  ->select("transactions.amount", "transactions.charge", "donations.anonymous", "donations.ref_id", "donations.created_at", "payment_link.name AS campaign", "users.first_name", "users.last_name")
                  ->orderBy("donations.id")
                  ->get();

        $currency=Currency::whereStatus(1)->first(); 
        $data['currency']=  $currency;
        $data['startDate']=  $startDate->format("d/m/Y");
        $data['endDate']=  $endDate->format("d/m/Y");

        return view('user.reports.donations', $data);
    }
    
}