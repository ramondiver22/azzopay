<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\Settings;
use App\Models\Logo;
use App\Models\Paymentlink;
use App\Models\Transactions;
use App\Models\Currency;
use App\Models\User;
use App\Models\Plans;
use App\Models\Subscribers;
use App\Models\Invoice;
use App\Models\Exttransfer;
use App\Models\Merchant;
use App\Models\Requests;
use App\Models\Transfer;
use App\Models\Withdraw;
use App\Models\Bank;
use App\Models\Product;
use App\Models\Order;
use App\Models\Subaccounts;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Curl\Curl;
use Illuminate\Support\Facades\Mail;


function send_email($to, $name, $subject, $message) {
    $set=Settings::first();
    $mlogo=Logo::first();
    $from=env('MAIL_USERNAME');
    $site=$set->site_name;
    $phone=$set->phone;
    $details=$set->site_desc;
    $email=env('MAIL_USERNAME');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $data=array('name'=>$name,'subject'=>$subject,'content'=>$message,'website'=>$set->site_name,'phone'=>$phone,'details'=>$details,'email'=>$email,'logo'=>$logo);
    Mail::send(['html' => 'emails/mail'], $data, function($message) use($name, $to, $subject, $from, $site) {
    $message->to($to, $name);
    $message->subject($subject);
    $message->from($from, $site);
    });
}

 function send_paymentlinkreceipt($ref, $type, $token) {
    $link=Paymentlink::whereref_id($ref)->first();
    $dd=Transactions::whereref_id($token)->first();
    $receiver=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    if($dd->sender_id!=null){
        $bb=User::whereid($dd->sender_id)->first();
        $sender_email=$bb->email;
    }else{
        $sender_email=$dd->email;
    }
    $site=$set->site_name;
    $details=$set->site_desc;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$dd->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';

    if($dd->sender_id==null){
        $sender_name=$dd->first_name.' '.$dd->last_name;
        $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    }else{
        $xx=User::whereid($dd->sender_id)->first();
        $sender_name=$xx->first_name.' '.$xx->last_name;
        $receiver_text='A payment from '.$sender_name.' was successfully received';
    }

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'details'=>$details,
        'amount'=>$amount,
        'charges'=>$charge,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/payment_links/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/payment_links/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 } 
 
 function send_productlinkreceipt($ref, $type, $token) {
    $link=Product::whereref_id($ref)->first();
    $dd=Order::whereref_id($token)->first();
    $receiver=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    $sender_email=$dd->email;
    $site=$set->site_name;
    $details=$set->site_desc;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $quantity=$dd->quantity;
    $address=$dd->address;
    $country=$dd->country;
    $state=$dd->state;
    $town=$dd->town;
    $phone=$dd->phone;
    $email=$dd->email;
    if($dd->shipping_fee==null){
        $shipping_fee=' - ';
    }else{
        $shipping_fee=$currency->name.' '.number_format((float)$dd->shipping_fee,2, '.', '');
    }

    $total=$currency->name.' '.number_format((float)$dd->total,2, '.', '');
    $amount=$currency->name.' '.number_format((float)$dd->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';
    $sender_name=$dd->first_name.' '.$dd->last_name;
    $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'details'=>$details,
        'amount'=>$amount,
        'charges'=>$charge,
        'total'=>$total,
        'quantity'=>$quantity,
        'address'=>$address,
        'country'=>$country,
        'state'=>$state,
        'town'=>$town,
        'phone'=>$phone,
        'email'=>$email,
        'shipping_fee'=>$shipping_fee,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/product/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/product/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }  

 function send_requestreceipt($ref) {
    $link=Requests::whereref_id($ref)->first();
    $receiver=User::whereid($link->user_id)->first();
    $dd=User::whereemail($link->email)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    $sender_email=$dd->email;
    $site=$set->site_name;
    $reference=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';

    if($dd->sender_id==null){
        $sender_name=$dd->first_name.' '.$dd->last_name;
        $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    }else{
        $xx=User::whereid($dd->sender_id)->first();
        $sender_name=$xx->first_name.' '.$xx->last_name;
        $receiver_text='A payment from '.$sender_name.' was successfully received';
    }

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'amount'=>$amount,
        'charges'=>$charge,
        'reference'=>$reference,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/request/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/request/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }  

 function send_transferreceipt($ref) {
    $link=Transfer::whereref_id($ref)->first();
    $receiver=User::whereid($link->receiver_id)->first();
    $dd=User::whereid($link->sender_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    $sender_email=$dd->email;
    $site=$set->site_name;
    $reference=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';

    if($dd->sender_id==null){
        $sender_name=$dd->first_name.' '.$dd->last_name;
        $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    }else{
        $xx=User::whereid($dd->sender_id)->first();
        $sender_name=$xx->first_name.' '.$xx->last_name;
        $receiver_text='A payment from '.$sender_name.' was successfully received';
    }

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'amount'=>$amount,
        'charges'=>$charge,
        'reference'=>$reference,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/transfer/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/transfer/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 } 

 function send_ntransferreceipt($ref) {
    $link=Transfer::whereref_id($ref)->first();
    $dd=User::whereid($link->sender_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $receiver_email=$link->temp;
    $sender_email=$dd->email;
    $site=$set->site_name;
    $reference=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='Confirm transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver_email.' was successful, since '.$link->temp.' is not registered, user will have to register with that email address to claim funds, funds will be returned within 5 days if money is not confirmed by recipient';
    $sender_name=$dd->first_name.' '.$dd->last_name;
    $receiver_text='You are receiving this email because '.$sender_name.', sent '.$amount.' to this email, but no account was found with this email, click button link to register with this email and confirm transaction, <a href="'.route('register').'">Register</a>';

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'amount'=>$amount,
        'charges'=>$charge,
        'reference'=>$reference,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/transfer/nrpmail'], $data, function($r_message) use($receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/transfer/nspmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 } 
 
 function send_invoicereceipt($ref, $type, $token) {
    $link=Invoice::whereref_id($ref)->first();
    $dd=Transactions::whereref_id($token)->first();
    $receiver=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    if($dd->sender_id!=null){
        $bb=User::whereid($dd->sender_id)->first();
        $sender_email=$bb->email;
    }else{
        $sender_email=$dd->email;
    }
    $site=$set->site_name;
    $details=$set->site_desc;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$dd->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';

    if($dd->sender_id==null){
        $sender_name=$dd->first_name.' '.$dd->last_name;
        $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    }else{
        $xx=User::whereid($dd->sender_id)->first();
        $sender_name=$xx->first_name.' '.$xx->last_name;
        $receiver_text='A payment from '.$sender_name.' was successfully received';
    }

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'details'=>$details,
        'amount'=>$amount,
        'charges'=>$charge,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/invoice/receiver/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/invoice/sender/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }

 function send_merchantreceipt($ref, $type, $token) {
    $link=Merchant::wheremerchant_key($ref)->first();
    $dd=Exttransfer::wherereference($token)->first();
    $receiver=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    if($link->email!=null){
        $receiver_email=$link->email;
    }else{
        $receiver_email=$receiver->email;
    }
    if($dd->sender_id!=null){
        $bb=User::whereid($dd->user_id)->first();
        $sender_email=$bb->email;
    }else{
        $sender_email=$dd->email;
    }
    $site=$set->site_name;
    $details=$set->site_desc;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$dd->amount*$dd->quantity,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';

    if($dd->sender_id==null){
        $sender_name=$dd->first_name.' '.$dd->last_name;
        $receiver_text='A payment from '.$dd->first_name.' '.$dd->last_name.' was successfully received';
    }else{
        $xx=User::whereid($dd->user_id)->first();
        $sender_name=$xx->first_name.' '.$xx->last_name;
        $receiver_text='A payment from '.$sender_name.' was successfully received';
    }

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'details'=>$details,
        'amount'=>$amount,
        'charges'=>$charge,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/merchant/receiver/rpmail'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/merchant/sender/spmail'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 } 

function sub_check() {
    $set=Settings::first();
    if($set->xperiod==null){

    }elseif($set->xperiod==1){
        echo redirect()->route('ipn.boompay'); 
    }
}

function file_write() {
    $path = url('/').'/asset/dashboard/js/check.json';
    $data = str_random(4).'-'.str_random(4).'-'.str_random(4).'-'.str_random(4);
    file_put_contents($path, $data);
    $set=Settings::first();
    $set->lock_code=$data;
    $set->save();
}

 function new_subscription($ref, $type, $token) {
    $link=Plans::whereref_id($ref)->first();
    $dd=Subscribers::whereref_id($token)->first();
    $bb=User::whereid($dd->user_id)->first();
    $receiver=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $sender_name=$bb->first_name.' '.$bb->last_name;
    $receiver_name=$receiver->first_name.' '.$receiver->last_name;
    $from=env('MAIL_USERNAME');
    $receiver_email=$receiver->email;
    $sender_email=$bb->email;
    $site=$set->site_name;
    $details=$set->site_desc;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$dd->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $next=$dd->expiring_date;
    $plan_name=$link->name;

    if($dd->times>0 && $dd->status==1){
        $renewal='Yes';
    }else{
        $renewal='No';
    }

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='New successful transaction';
    $sender_subject='Payment was successful';   
    $sender_text='Payment to '.$receiver->business_name.' was successful';
    $receiver_text='A payment from '.$bb->first_name.' '.$bb->last_name.' was successfully received';

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'receiver_text'=>$receiver_text,
        'details'=>$details,
        'amount'=>$amount,
        'charges'=>$charge,
        'next'=>$next,
        'plan_name'=>$plan_name,
        'renewal'=>$renewal,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );
    Mail::send(['html' => 'emails/subscription/receiver/new'], $data, function($r_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
        $r_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});    
    Mail::send(['html' => 'emails/subscription/sender/new'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }
 
 function send_invoice($ref) {
    $link=Invoice::whereref_id($ref)->first();
    $link->sent = 1;
    $link->save();
    $user=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $sender_email=$link->email;
    $site=$set->site_name;
    $payment_link=$link->ref_id;
    $quantity=$link->quantity;
    $r_d=$link->discount;
    $r_t=$link->tax;
    $total=$currency->name.' '.number_format((float)$link->total,2, '.', '');
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $discount=$currency->name.' '.number_format((float)$link->amount*$link->quantity*$r_d/100, 2, '.', '');
    $tax=$currency->name.' '.number_format((float)$link->amount*$link->quantity*$r_t/100, 2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Payment for Invoice #'.$link->invoice_no;   
    $sender_text='Invoice for '.$link->item.' will be due by '.date("h:i:A j, M Y", strtotime($link->due_date));

    $data=array(
        'created'=>$link->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$site,
        'receiver_name'=>$user->first_name.' '.$user->last_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'payment_link'=>$payment_link,
        'quantity'=>$quantity,
        'r_d'=>$r_d,
        'r_t'=>$r_t,
        'total'=>$total,
        'amount'=>$amount,
        'discount'=>$discount,
        'tax'=>$tax,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/invoice/sender/invoice'], $data, function($s_message) use($sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email)->subject($sender_subject)->from($from, $site);});
 }

 function send_transferrefund($ref) {
    $link=Transfer::whereref_id($ref)->first();
    $user=User::whereid($link->sender_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $sender_email=$user->email;
    $sender_name=$user->first_name.' '.$user->last_name;
    $site=$set->site_name;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Refund for #'.$link->ref_id;   
    $sender_text='Account has been credited with '.$amount.'.  '.$link->temp.' failed to claim transfer request within the last 5 days.';

    $data=array(
        'created'=>$link->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$user->first_name.' '.$user->last_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'reference'=>$payment_link,
        'amount'=>$amount,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/transfer/refund'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }  
 
 function new_withdraw($ref) {
    $link=Withdraw::wherereference($ref)->first();
    $bank=Bank::whereid($link->bank_id)->first();
    $user=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $sender_email=$user->email;
    $sender_name=$user->first_name.' '.$user->last_name;
    $site=$set->site_name;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Withdraw Request is currently been Processed';   
    $sender_text='Withdraw Request has been booked. Thanks for working with us.';

    $data=array(
        'created'=>$link->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'reference'=>$ref,
        'amount'=>$amount,
        'charge'=>$charge,
        'bank'=>$bank->name,
        'acct_name'=>$bank->acct_name,
        'acct_no'=>$bank->acct_no,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/withdraw/new'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }
 
 function new_subwithdraw($ref) {
    $link=Withdraw::wheresecret($ref)->first();
    $bank=Subaccounts::whereid($link->sub_id)->first();
    $real=Banksupported::whereid($bank->bank_id)->first();
    $user=Subaccounts::whereid($link->sub_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $sender_email=$user->email;
    $sender_name=$user->name;
    $site=$set->site_name;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Payout is currently been Processed';   
    $sender_text='You have been added to a sub account payout. Thanks for working with us.';

    $data=array(
        'created'=>$link->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'reference'=>$ref,
        'amount'=>$amount,
        'bank'=>$real->name,
        'acct_name'=>$bank->acct_name,
        'acct_no'=>$bank->acct_no,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/withdraw/newsub'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }
 function send_withdraw($ref, $status) {
    $link=Withdraw::whereid($ref)->first();
    $bank=Bank::whereid($link->bank_id)->first();
    $user=User::whereid($link->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    if($status=='approved'){
        $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');
    }else{
        $charge=' - ';
    }
    $from=env('MAIL_USERNAME');
    $receiver_email=$user->email;
    $receiver_name=$user->first_name.' '.$user->last_name;
    $site=$set->site_name;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $receiver_subject='We sent you money';   
    $receiver_text='Withdrawal request of '.$amount.' has been paid out. Thanks for working with us.';

    $data=array(
        'created'=>$link->created_at,
        'receiver_subject'=>$receiver_subject,
        'receiver_name'=>$receiver_name,
        'website'=>$set->site_name,
        'receiver_text'=>$receiver_text,
        'reference'=>$ref,
        'amount'=>$amount,
        'charge'=>$charge,
        'bank'=>$bank->name,
        'acct_name'=>$bank->acct_name,
        'acct_no'=>$bank->acct_no,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/withdraw/send'], $data, function($s_message) use($receiver_name, $receiver_email, $receiver_subject, $from, $site) {
    $s_message->to($receiver_email, $receiver_name)->subject($receiver_subject)->from($from, $site);});
 } 
 
 function send_request($ref) {
    $link=Requests::whereref_id($ref)->first();
    $user=User::whereid($link->user_id)->first();
    $to=User::whereemail($link->email)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $from=env('MAIL_USERNAME');
    $sender_email=$user->email;
    $receiver_email=$to->email;
    $site=$set->site_name;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$link->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$link->charge,2, '.', '');

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Money request #'.$ref;   
    $sender_text=$user->first_name.' '.$user->last_name.' just sent a Money request';

    $data=array(
        'created'=>$link->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$user->first_name.' '.$user->last_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'sender_email'=>$sender_email,
        'receiver_email'=>$receiver_email,
        'payment_link'=>$payment_link,
        'amount'=>$amount,
        'charge'=>$charge,
        'reference'=>$ref,
        'confirm'=>$link->confirm,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/request/new'], $data, function($s_message) use($receiver_email, $sender_subject, $from, $site) {
    $s_message->to($receiver_email)->subject($sender_subject)->from($from, $site);});
 } 
 
 function insufficient_balance($ref, $type, $token) {
    $link=Plans::whereref_id($ref)->first();
    $dd=Subscribers::whereref_id($token)->first();
    $dd->notify=0;
    $dd->save();
    $bb=User::whereid($dd->user_id)->first();
    $currency=Currency::whereStatus(1)->first();
    $set=Settings::first();
    $mlogo=Logo::first();

    $sender_name=$bb->first_name.' '.$bb->last_name;
    $from=env('MAIL_USERNAME');
    $sender_email=$bb->email;
    $site=$set->site_name;
    $method=$type;
    $reference=$token;
    $payment_link=$link->ref_id;
    $amount=$currency->name.' '.number_format((float)$dd->amount,2, '.', '');
    $charge=$currency->name.' '.number_format((float)$dd->charge,2, '.', '');
    $plan_name=$link->name;

    if($dd->times>0 && $dd->status==1){
        $renewal='Yes';
    }else{
        $renewal='No';
    }

    $logo=url('/').'/asset/'.$mlogo->image_link;
    $sender_subject='Could not renew subscription';   
    $sender_text='Payment for '.$plan_name.' was unsuccessful due to insufficient balance';

    $data=array(
        'created'=>$dd->created_at,
        'sender_subject'=>$sender_subject,
        'sender_name'=>$sender_name,
        'website'=>$set->site_name,
        'sender_text'=>$sender_text,
        'amount'=>$amount,
        'plan_name'=>$plan_name,
        'renewal'=>$renewal,
        'method'=>$method,
        'reference'=>$reference,
        'payment_link'=>$payment_link,
        'logo'=>$logo
        );  
    Mail::send(['html' => 'emails/subscription/sender/failed'], $data, function($s_message) use($sender_name, $sender_email, $sender_subject, $from, $site) {
    $s_message->to($sender_email, $sender_name)->subject($sender_subject)->from($from, $site);});
 }

if (! function_exists('user_ip')) {
    function user_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}


if (!function_exists('castrotime')) {

    function castrotime($timestamp)
    {
        $datetime1=new DateTime("now");
        $datetime2=date_create($timestamp);
        $diff=date_diff($datetime1, $datetime2);
        $timemsg='';
        if($diff->y > 0){
            $timemsg = $diff->y * 12;
        }
        else if($diff->m > 0){
            $timemsg = $diff->m *30;
        }
        else if($diff->d > 0){
            $timemsg = $diff->d *1;
        }    
        if($timemsg == "")
            $timemsg = 0;
        else
            $timemsg = $timemsg;
    
        return $timemsg;
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($timestamp){
        //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
        $datetime1=new DateTime("now");
        $datetime2=date_create($timestamp);
        $diff=date_diff($datetime1, $datetime2);
        $timemsg='';
        if($diff->y > 0){
            $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');
    
        }
        else if($diff->m > 0){
            $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
        }
        else if($diff->d > 0){
            $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
        }
        else if($diff->h > 0){
            $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
        }
        else if($diff->i > 0){
            $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
        }
        else if($diff->s > 0){
            $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
        }
        if($timemsg == "")
            $timemsg = "Just now";
        else
            $timemsg = $timemsg.' ago';
    
        return $timemsg;
    }
}


function convertFloat($floatAsString)
{
    $norm = strval(floatval($floatAsString));

    if (($e = strrchr($norm, 'E')) === false) {
        return $norm;
    }

    return number_format($norm, -intval(substr($e, 1)));
}


if (! function_exists('convertCurrency'))
{

    function convertCurrency($amount,$from_currency,$to_currency){
        $gnl = Settings::first();
        $apikey = $gnl->api;
        $from_Currency = urlencode($from_currency);
        $to_Currency = urlencode($to_currency);
        $query =  "{$from_Currency}_{$to_Currency}";
        // change to the free URL if you're using the free version
        $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $obj = json_decode($json, true);
        $val = floatval($obj["$query"]);
        $total = $val * $amount;
        return $total;
    }
}


if (! function_exists('boomtime'))
{
    function boomtime($timestamp){
        //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
        $datetime1=new DateTime("now");
        $datetime2=date_create($timestamp);
        $diff=date_diff($datetime1, $datetime2);
        $timemsg='';
        if($diff->h > 0){
            $timemsg = $diff->h * 1;
        }    
        if($timemsg == "")
            $timemsg = 0;
        else
            $timemsg = $timemsg;

        return $timemsg;
    }
}


if (! function_exists('find_address_by_cep'))
{
    function find_address_by_cep($cep) {
        try {
            $cep = str_replace(Array("-", "_", "."), "", $cep);

            $content = file_get_contents("https://viacep.com.br/ws/{$cep}/json");
            $json = json_decode($content);

            if (json_last_error()) {
                throw new \Exception(json_last_error() . " - " . json_last_error_msg());
            }

            if (isset($json->erro) && $json->erro) {
                throw new \Exception("CEP não encontrado.");
            }

            return Array(
                "cep" => $json->cep,
                "logradouro" => $json->logradouro,
                "complemento" => $json->complemento,
                "bairro" => $json->bairro,
                "localidade" => $json->localidade,
                "uf" => $json->uf,
                "ibge" => $json->ibge
            );
        } catch (\Exception $ex) {
            
            return Array(
                "cep" => "",
                "logradouro" => "",
                "complemento" => "",
                "bairro" => "",
                "localidade" => "",
                "uf" => "",
                "ibge" => ""
            );
        }
    }
}

if (! function_exists('convert_brl_date')) {
    function convert_brl_date($brlDate) {
        if (strlen($brlDate) == 10) {
            
            $date = DateTime::createFromFormat("d/m/Y H:i:s", $brlDate . " 00:00:00");
            return $date;
        }
        return null;
    }
}

if (! function_exists('convert_brl_date_time')) {
    function convert_brl_date_time($brlDate) {
        if (strlen($brlDate) == 19) {
            $date = DateTime::createFromFormat("d/m/Y H:i:s", $brlDate);
            return $date;
        }
        return null;
    }
}




if (! function_exists('validate_document')) {
    function validate_document($document) {
        if (empty($document))	return FALSE;

        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) == 11) {
            return check_cpf($document);
        } else if (strlen($document) == 14) {
            return check_cnpj($document);
        }

        return false;
    }
}

if (! function_exists('check_cpf')) {
    function check_cpf($cpf = FALSE)
    {
        if (empty($cpf))	return FALSE;

        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        if (strlen($cpf) != 11)	return FALSE;
        elseif (
            $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444'
            || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'
        )	return FALSE;
        else
        {
            for ($t = 9; $t < 11; $t++)
            {
                for ($d = 0, $c = 0; $c < $t; $c++)
                    $d += $cpf[$c] * (($t + 1) - $c);

                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d)	return FALSE;
            }
            return TRUE;
        }
    }
}


if (! function_exists('check_cnpj')) {
    function check_cnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;	

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}


if (! function_exists('calc_age'))
{
    function calc_age($birthday = FALSE)
    {
        if (!$birthday)	return FALSE;

        list($day, $month, $year) = explode('/', $birthday);
        $birthday	= mktime(0, 0, 0, $month, $day, $year);
        $today		= mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        return floor((((($today - $birthday) / 60) / 60) / 24) / 365.25);
    }
}


if (! function_exists('formatCnpjCpf')) {
    function formatCnpjCpf($value)
    {
    $cnpj_cpf = preg_replace("/\D/", '', $value);
    
    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 
    
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
}


if (! function_exists('indentify_pixkey_type')) {
    function indentify_pixkey_type($pixkey) {


        if (preg_match("/^[a-z0-9.!#$&'*+\/=?^_{}~-]+@[a-z0-9?(?:.a-z0-9?)]*$`/", $pixkey) == 1) {
            return "email";
        }

        
        if (preg_match("/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/", $pixkey) == 1) {
            return "evp";
        }


        $pixkey = str_replace(array("-", ".", "/", "(", ")"), "", $pixkey);
        if (strlen($pixkey) == 11 && check_cpf($pixkey)) {
            return "cpf";
        }

        if (strlen($pixkey) == 14 && check_cnpj($pixkey)) {
            return "cnpj";
        }

        $a = $pixkey;
        if (strlen($a) == 11) {
            $a = "+55{$a}";
        } else if (strlen($a) == 13) {
            $a = "+{$a}";
        }

        if (preg_match("/\+[1-9][0-9]\d{1,14}/", $a) == 1) {
            return "phone";
        }
        
        return null;
    } 

}


if (! function_exists('grava_money')) {


    function grava_money($value, $decimal = 2) { //TRATA PARA GRAVAR NA DB.

        if (!empty($value)) {
            $virgula = strpos($value, ",");
            $ponto = strpos($value, ".");
            
            if ($virgula >= 0 && $ponto >= 0) {
                
                if ($virgula > $ponto) {
                    $value = str_replace("." , "" , $value); // tira ponto
                    $value = str_replace("," , "." , $value); // virgula por ponto
                } else {
                    $value = str_replace("," , "" , $value); // tira virgula
                }
                
            } else if ($virgula >= 0) {
                $value = str_replace("," , "." , $value); // virgula por ponto
            }
        } else {
            $value = 0;
        }
        $value = number_format($value, $decimal, '.', '');
        return $value;
    }

}


if (! function_exists('gerarHtmlPaginacao')) {
    function gerarHtmlPaginacao($totalRegistros, $paginaAtual, $porPagina) {
            
        $totalPaginas = ceil($totalRegistros / $porPagina);

        $primeiraPagina = 1;
        $ultimaPagina = 15;
        
        if ($totalPaginas > 15 && $paginaAtual > 7) {
            $ultimaPagina = ($paginaAtual + 8);
            if ($ultimaPagina > $totalPaginas) {
                $ultimaPagina = $totalPaginas;
                $primeiraPagina = ($totalPaginas - 14);
            } else {
                $primeiraPagina = ($paginaAtual - 6);
            }
        } else if ($totalPaginas > 0 && $totalPaginas <= 15) {
            $ultimaPagina = $totalPaginas;
        }
        
        $disableFirst = (($primeiraPagina == 1) ? "disabled" : "");
        $disableLast = (($ultimaPagina == $totalPaginas) ? "disabled" : "");
        $disabledAnterior = (($paginaAtual == 1) ? "disabled" : "");
        $disabledProxima = (($paginaAtual == $ultimaPagina) ? "disabled" : "");
        
        ob_start();
        ?>

        <ul class="pagination round-pagination" style="width: fit-content; margin: 0 auto;">
            <li class="page-item <?php echo $disableFirst ?>">
                <a class="page-link " href="javascript:irParaPagina(1);" ><i class="fad fa-angle-double-left"></i></a>
            </li>
            <li class="page-item <?php echo $disabledAnterior ?>">
                <a class="page-link " href="javascript:paginaAnterior();" ><i class="fad fa-angle-left"></i></a>
            </li>
            <?php
            if ($totalPaginas > 0) {
                for ($i = $primeiraPagina; $i <= $ultimaPagina; $i++) {
                    //exit("{$i} == {$paginaAtual}");
                    $active = (($i == intval($paginaAtual)) ? "active" : "");
                    $aria = (($i == intval($paginaAtual)) ? "aria-current='page'" : "");;
            ?>
                <li class="page-item <?php echo $active ?>"  <?php echo $aria ?>>
                    <a class="page-link" href="javascript:irParaPagina(<?php echo $i ?>);"><?php echo $i ?></a>
                </li>
            <?php
                }
            }
            ?>

            <li class="page-item <?php echo $disabledProxima ?>">
                <a class="page-link " href="javascript:proximaPagina();" ><i class="fad fa-angle-right"></i></a>
            </li>
            <li class="page-item <?php echo $disableLast ?>">
                <a class="page-link " href="javascript:irParaPagina(<?php echo $totalPaginas ?>);"><i class="fad fa-angle-double-right"></i></a>
            </li>
        </ul>

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }
}

