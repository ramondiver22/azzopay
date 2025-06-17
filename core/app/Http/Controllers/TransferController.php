<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Transfer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transactions;
use App\Models\Productimage;
use App\Models\Requests;
use App\Models\Charges;
use App\Models\Donations;
use App\Models\Paymentlink;
use App\Models\Plans;
use App\Models\Subscribers;
use Carbon\Carbon;


class TransferController extends Controller
{

    public function sclinks()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title'] = "Single Charge";
        $data['links']=Paymentlink::wheretype(1)->latest()->paginate(6);
        return view('admin.transfer.sc', $data);
    } 
    public function dplinks()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title'] = "Donation Page";
        $data['links']=Paymentlink::wheretype(2)->latest()->paginate(6);
        return view('admin.transfer.dp', $data);
    }
    public function Ownbank()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Transfer';
        $data['transfer']=Transfer::latest()->get();
        return view('admin.transfer.own-bank', $data);
    }     
    public function Requestmoney()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Request Money';
        $data['request']=Requests::latest()->get();
        return view('admin.transfer.request', $data);
    }      


    public function Invoice() {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Invoice';
        $data['invoice']=Invoice::latest()->get();
        return view('admin.transfer.invoice', $data);
    }      


    public function listInvoices() {
        
        try {
            
            $status = request("status");
            $search = request("search");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $page = request("page");
                        
            $info = Invoice::listInvoices(0, $status, $search, $start, $end, $page, 50);
            
            $invoices = $info["invoices"];
            
            
            ob_start();
            if (sizeof($invoices) > 0) {
                foreach ($invoices as $invoice) {
                    ?>
                    <tr>
                        <td>
                            <h6>
                                <strong class="mr-2 h3">No. <?php echo $invoice->id ?></strong>
                                <?php if ($invoice->status == 0) { ?>
                                    <span class="badge badge-info ml-2">Aguardando Pagamento</span>
                                <?php } else if ($invoice->status == 1) { ?>
                                    <span class="badge badge-success ml-2">Pago</span>
                                <?php } else if ($invoice->status == 2) { ?>
                                    <span class="badge badge-danger ml-2">Cancelada</span>
                                <?php } else if ($invoice->status == 3) { ?>
                                    <span class="badge badge-primary ml-2">Reembolsada</span>
                                <?php }  ?>
                            </h6>
                            <h6><small>Data: <?php echo ($invoice->created_at != null ? date("d/m/Y H:i", strtotime($invoice->created_at)) : "") ?></h6>
                            <h6><small>Venc: <?php echo ($invoice->due_date != null ? date("d/m/Y H:i", strtotime($invoice->due_date)) : "") ?></h6>
                            <h6><small>Ref: <?php echo $invoice->ref_id ?></h6>
                            <h6><small>Ref Cliente: <?php echo $invoice->invoice_no ?></small></h6>
                        </td>
                        <td>
                            <h6><?php echo $invoice->first_name ?> <?php echo $invoice->last_name ?></h6>
                            <h6><small>E-mail: <?php echo $invoice->email ?></small></h6>
                            <h6><small>Item: <?php echo $invoice->item ?></small></h6>
                            <small>Obs: <?php echo $invoice->notes ?></small>
                        </td>
                        <td>
                            <h6>Total: <?php echo number_format($invoice->amount, 2, ',', '.') ?></h6>
                            <h6><small>Taxas: <?php echo number_format($invoice->charge, 2, ',', '.')  ?></h6>
                            <h6><small>Líquido: <?php echo number_format($invoice->amount, 2, ',', '.')  ?></h6>
                            <h6><small>Ref: <?php echo $invoice->ref_id ?></h6>
                            <h6><small>Ref Cliente: <?php echo $invoice->invoice_no ?></small></h6>
                        </td>
           
                    </tr>
                    <?php
                }
                
            } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="3">
                            Nenhuma Invoice Encontrada.
                        </td>
                    </tr>
                <?php
            }
            $html = ob_get_contents();
            ob_end_clean();
            
            $json["pagination"] = gerarHtmlPaginacao($info["total"], $page, 50);
            
            $json["html"] = $html;
            $json["success"] = true;
        } catch (\Exception $ex) {
            $json["success"] = false;
            $json["message"] = $ex->getMessage();
        }
        exit(json_encode($json));
    }





    public function Product()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Product';
        $data['product']=Product::latest()->get();
        return view('admin.transfer.product', $data);
    }    
    public function charges()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Charges';
        $data['charges']=Charges::latest()->get();
        return view('admin.transfer.charges', $data);
    }
    public function plans()
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Plans';
        $data['plans']=Plans::latest()->get();
        return view('admin.transfer.plans', $data);
    }
    public function unplan($id)
    {
        $page=Plans::find($id);
        $page->status=0;
        $page->save();
        return back()->with('success', 'Plan has been disabled.');
    } 
    public function plansub($id)
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['plan']=$plan=Plans::whereref_id($id)->first();
        $data['sub']=Subscribers::whereplan_id($plan->id)->latest()->get();
        $data['title']=$plan->ref_id;
        return view('admin.transfer.subscribers', $data);
    }
    public function pplan($id)
    {
        $page=Plans::find($id);
        $page->status=1;
        $page->save();
        return back()->with('success', 'Plan link has been activated.');
    }
    public function Orders($id)
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title']='Orders';
        $data['orders']=Order::whereproduct_id($id)->latest()->get();
        return view('admin.transfer.orders', $data);
    }  
    public function linkstrans($id)
    {
        $data['lang'] = parent::getLanguageVars("admin_transfers_page");
        $data['title'] = "Transactions";
        $data['links']=Transactions::wherepayment_link($id)->latest()->get();
        return view('admin.transfer.trans', $data);
    }
    public function unlinks($id)
    {
        $page=Paymentlink::find($id);
        $page->status=0;
        $page->save();
        return back()->with('success', 'Payment link has been unsuspended.');
    } 
    public function plinks($id)
    {
        $page=Paymentlink::find($id);
        $page->active=1;
        $page->save();
        return back()->with('success', 'Product has been suspended.');
    }    
    public function unproduct($id)
    {
        $page=Product::find($id);
        $page->active=1;
        $page->save();
        return back()->with('success', 'Product has been unsuspended.');
    } 
    public function pproduct($id)
    {
        $page=Product::find($id);
        $page->active=0;
        $page->save();
        return back()->with('success', 'Payment link has been suspended.');
    }
    public function Destroylink($id)
    {
        $link=Paymentlink::whereid($id)->first();
        $history=Transactions::wherepayment_link($id)->delete();
        if($link->type==2){
            $donation=Donations::wheredonation_id($id)->delete();
        }
        $data=$link->delete();
        if ($data) {
            return back()->with('success', 'Payment link was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Payment link');
        }
    }   
    public function Destroyownbank($id)
    {
        $data = Transfer::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }    
    public function Destroyrequest($id)
    {
        $data = Requests::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }      
    public function Destroyinvoice($id)
    {
        $link=Invoice::whereid($id)->first();
        $history=Transactions::wherepayment_link($id)->delete();
        $res=$link->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }     
    public function Destroyproduct($id)
    {
        $data = Product::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }     
    public function Destroyorders($id)
    {
        $data = Order::findOrFail($id);
        $res =  $data->delete();
        if ($res) {
            return back()->with('success', 'Request was Successfully deleted!');
        } else {
            return back()->with('alert', 'Problem With Deleting Request');
        }
    }       
    
}
