<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Withdraw;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Banksupported;
use App\Models\Subaccounts;
 

class WithdrawController extends Controller
{
   
    public function log()
    {
        $data['lang'] = parent::getLanguageVars("admin_withdraw_page");
        $data['title']='Settlements';
        
        return view('admin.withdrawal.index', $data);
    }  


    public function listWithdrawals() {

        try {
            $text = request("text");
            $status = request("status");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $page = request("page");
            


            $info = Withdraw::listWithdrawals(0, $start, $end, $status, $text, $page, 50);
            
            $withdrawals = $info["withdrawals"];
            
            
            ob_start();
            if (sizeof($withdrawals) > 0) {
                foreach ($withdrawals as $withdraw) {
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $withdraw->id ?></td>
                        <td><?php echo $withdraw->reference ?></td>
                        <td>
                            <a href="<?php echo url("'admin/manage-user'")?>/<?php echo $withdraw->user_id ?>">
                                <?php echo "{$withdraw->first_name} {$withdraw->last_name}" ?>
                            </a>
                        </td>
                        <td><?php echo $withdraw->email ?></td>
                        
                        
                        <td class="text-center">R$ <?php echo number_format($withdraw->amount, 2, ",", ".") ?></td>
                        <td class="text-center">R$ <?php echo number_format($withdraw->charge, 2, ",", ".") ?></td>
                        
                        <td class="text-center">
                            <?php if($withdraw->status==0) { ?>
                                <span class="badge badge-pill badge-danger">Pendente</span>
                            <?php } elseif($withdraw->status==1) { ?>
                                <span class="badge badge-pill badge-success">Pago</span> 
                            <?php } elseif($withdraw->status==2) { ?>
                                <span class="badge badge-pill badge-info">Cancelado</span>
                            <?php } ?>
                        </td> 


                        <td class="text-center"><?php echo ($withdraw->method=="BANK_TRANSFER" ? "Transferência" : "PIX") ?></td>

                        <?php 
                        if ($withdraw->method=="BANK_TRANSFER") { 
                            $account = null;
                            
                            if ($withdraw->type==1) {
                                $account = Bank::whereid($withdraw->bank_id)->first(); 
                            } else {
                                $account= Subaccounts::whereid($withdraw->sub_id)->first(); 
                            }
                            $bank = Banksupported::whereid($account->bank_id)->first(); 
                            ?>
                            <td><?php echo ($bank ? $bank->name : "") ?></td>
                            <td class="text-center"><?php echo ($account != null ? $account->agency_no : "") ?></td>
                            <td class="text-center"><?php echo ($account != null ? $account->acct_no : "") ?></td>
                            <td class="text-center"><?php echo ($account != null ? $account->acct_name : "") ?></td>   
                            <td class="text-center"><?php echo ($account != null ? $account->account_document : "") ?></td>  
                        <?php 
                        } else {
                        ?>
                        <td colspan="5"><?php echo $withdraw->pix_key_type ?>: <?php echo $withdraw->pix_key ?></td>
                        <?php 
                        } 
                        ?>
                        
                        <td class="text-center"><?php echo ($withdraw->created_at != null ? date("d/m/Y H:i:s", strtotime($withdraw->created_at)) : "") ?></td>
                        <td class="text-center"><?php echo ($withdraw->updated_at != null ? date("d/m/Y H:i:s", strtotime($withdraw->updated_at)) : "") ?></td>

                        <td class="text-center">
                            <?php if ($withdraw->status == 0) { ?>
                                <a class='btn btn-success' href="<?php echo route('withdraw.approve', ['id' => $withdraw->id]) ?>">Aprovar</a>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <?php if ($withdraw->status == 0) { ?>
                                <a class='btn btn-danger' href="<?php echo route('withdraw.decline', ['id' => $withdraw->id]) ?>">Cancelar</a>
                            <?php } ?>
                        </td>
                                       
                    </tr>  
                    <?php
                }
                
            } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="17">
                            Não foram encontrados registros para os dados informados.
                        </td>
                    </tr>
                <?php
            }
            $html = ob_get_contents();
            ob_end_clean();
            
            
            $json["pagination"] = gerarHtmlPaginacao($info["total"], $page, 50);
            $json["totalAmount"] = number_format($info["totalAmount"], 2, ",", ".");
            
            $json["html"] = $html;
            $json["success"] = true;
            return response()->json($json, 200 );
        } catch (\Exception $ex) {
            return response()->json(["success" => false, 'message'=> $ex->getMessage()], 200);
        }
    }

    public function delete($id)
    {
        $data = Withdraw::findOrFail($id);
        if($data->status==0){
            return back()->with('alert', 'You cannot delete an unpaid withdraw request');
        }else{
            $res =  $data->delete();
            if ($res) {
                return back()->with('success', 'Request was Successfully deleted!');
            } else {
                return back()->with('alert', 'Problem With Deleting Request');
            }
        }

    } 
    public function approve($id)
    {
        $data = Withdraw::findOrFail($id);

        if ($data->status == 1) {
            return back()->with('error', 'O saque selecionado já está aprovado!');
        }

        if ($data->status == 2) {
            return back()->with('error', 'O saque selecionado já está cancelado!');
        }

        $user=User::find($data->user_id);
        $user->balance=$user->balance-$data->charge;
        $user->save();
        $set=Settings::first();
        $data->status=1;
        $res=$data->save();
        if($set->email_notify==1){
            send_withdraw($id, 'approved');
        }
        if ($res) {
            return back()->with('success', 'Request was successfully approved!');
        } else {
            return back()->with('alert', 'Problem with approving request');
        }
    }    
    public function decline($id)
    {
        $set=Settings::first();
        $currency=Currency::whereStatus(1)->first();
        $data = Withdraw::findOrFail($id);


        if ($data->status == 1) {
            return back()->with('error', 'O saque selecionado já está aprovado!');
        }

        if ($data->status == 2) {
            return back()->with('error', 'O saque selecionado já está cancelado!');
        }


        $user=User::find($data->user_id);
        $user->balance=$user->balance+$data->amount+$data->charge;
        $user->save();
        $data->status=2;
        $res=$data->save();
        if($set->email_notify==1){
            send_withdraw($id, 'declined');
        }
        if ($res) {
            return back()->with('success', 'Request was successfully declined!');
        } else {
            return back()->with('alert', 'Problem with approving request');
        }
    }  
}
