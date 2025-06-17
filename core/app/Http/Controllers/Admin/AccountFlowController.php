<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Image;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Models\History;
use App\Exports\AccountFlowExport;
use Excel;

class AccountFlowController extends Controller {

        
    public function __construct() {		
        
    }
    
    public function index(){

        $data['lang'] = parent::getLanguageVars("");
        $data['title']= "Fluxo de Caixa - Entradas e Saídas";
        
        

        return view('admin.financial.account_flow', $data);
    }    
    
    public function listAccounts(Request $request) {
        
        try {
            
            $entity = request("entity");
            $type = request("type");
            $text = request("text");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $page = request("page");
            
            switch($entity) {
                case "WT": $entity = 'WITHDRAW'; break;
                case "FD": $entity = 'FUND'; break;
                case "DN": $entity = 'DONATION'; break;
                case "PL": $entity = 'PAYMENT_LINK'; break;
                case "CM": $entity = 'COMISSION'; break;
                case "OD": $entity = 'ORDER'; break;
                case "IV": $entity = 'INVOICE'; break;
                default: 
                    $entity = null;
            }

            switch($type) {
                case "CREDIT": $type = 1; break;
                case "DEBIT": $type = 2; break;
                default: 
                    $type = null;
            }

            $info = History::listHistoric(0, $start, $end, $text, $type, $entity, $page, 50);
            
            $historic = $info["historic"];
            
            
            ob_start();
            if (sizeof($historic) > 0) {
                foreach ($historic as $history) {
                    ?>
                    <tr>
                        <td class="text-center">
                            <?=date("d/m/Y H:i", strtotime($history->created_at));?>
                        </td>

                        <td>
                            <?php echo $history->first_name ?> <?php echo $history->last_name ?>
                        </td>
                        
                        <td class="text-center">
                            <span class="badge bg-<?php echo History::getLabelType($history->type) ?> text-white">
                                <?php echo History::getStringType($history->type) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php echo $history->ref ?>
                        </td>
                        <td class="text-center">
                            <?php echo History::getStringEntity($history->entity_type) ?>
                        </td>

                        <td class="text-center">
                            R$ <?=number_format($history->amount, 2, ",", ".");?>
                        </td>
                    </tr>
                    <?php
                }
                
            } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="6">
                            Não foram encontrados registros para os dados informados.
                        </td>
                    </tr>
                <?php
            }
            $html = ob_get_contents();
            ob_end_clean();
            
            $usersBalances = User::sum("balance");
            
            $json["pagination"] = gerarHtmlPaginacao($info["total"], $page, 50);
            $json["totalAmount"] = number_format(($info["totalAmount"] - $usersBalances), 2, ",", ".");
            $json["usersBalances"] = number_format($usersBalances, 2, ",", ".");
            $json["totalCredits"] = number_format($info["totalCredits"], 2, ",", ".");
            $json["totalDebits"] = number_format($info["totalDebits"], 2, ",", ".");
            
            $json["html"] = $html;
            $json["success"] = true;
        } catch (\Exception $ex) {
            $json["success"] = false;
            $json["message"] = $ex->getMessage();
        }
        exit(json_encode($json));
    }
	

    public function exportReport(Request $request) {

        try {
            $entity = request("entity");
            $type = request("type");
            $text = request("text");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $fileType = request("fileType");

            switch($entity) {
                case "WT": $entity = 'WITHDRAW'; break;
                case "FD": $entity = 'FUND'; break;
                case "DN": $entity = 'DONATION'; break;
                case "PL": $entity = 'PAYMENT_LINK'; break;
                case "CM": $entity = 'COMISSION'; break;
                case "OD": $entity = 'ORDER'; break;
                case "IV": $entity = 'INVOICE'; break;
                default: 
                    $entity = null;
            }

            switch($type) {
                case "CREDIT": $type = 1; break;
                case "DEBIT": $type = 2; break;
                default: 
                    $type = null;
            }

            if (!in_array($fileType, Array("XLS", "CSV"))) {
                throw new \Exception("Formato de exportação inválido.");
            }

            $typeObject = null;
            switch($fileType) {
                case "XLS": $typeObject = \Maatwebsite\Excel\Excel::XLS; break;
                case "CSV": $typeObject = \Maatwebsite\Excel\Excel::CSV; break;
            }

            return Excel::download(new AccountFlowExport($start, $end, $text, $type, $entity), 'taxes_report.' . strtolower($fileType), $typeObject);
            
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], 500);
        }
            
    }

}