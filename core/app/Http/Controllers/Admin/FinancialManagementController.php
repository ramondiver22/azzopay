<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Image;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Models\Charges;
use App\Exports\TaxesReportExport;
use Excel;

class FinancialManagementController extends Controller {

        
    public function __construct() {		
        
    }
    
    public function feesManagement(){

        $data['lang'] = parent::getLanguageVars("");
        $data['title']= "Gerenciamento de Taxas";
        
        

        return view('admin.financial.fees_managemet', $data);
    }    
    
    public function listFees(Request $request) {
        
        try {
            
            $text = request("text");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $page = request("page");
            
            $info = Charges::listCharges(0, $start, $end, $text, $page, 50);
            
            $charges = $info["charges"];
            
            
            ob_start();
            if (sizeof($charges) > 0) {
                foreach ($charges as $charge) {
                    ?>
                    <tr>
                        <td class="text-center">
                            <?=date("d/m/Y H:i", strtotime($charge->created_at));?>
                        </td>
                        <td>
                            <?php echo $charge->first_name ?> <?php echo $charge->last_name ?>
                        </td>

                        <td class="text-center">
                            <?php echo $charge->ref_id ?>
                        </td>
                        <td class="">
                            <?php echo $charge->log ?>
                        </td>

                        <td class="text-center">
                            R$ <?=number_format($charge->amount, 2, ",", ".");?>
                        </td>
                    </tr>
                    <?php
                }
                
            } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="5">
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
        } catch (\Exception $ex) {
            $json["success"] = false;
            $json["message"] = $ex->getMessage();
        }
        exit(json_encode($json));
    }
	

    public function exportReport(Request $request) {

        try {
            $text = request("text");
            $start = convert_brl_date_time(request("start") . " 00:00:00");
            $end = convert_brl_date_time(request("end") . " 23:59:59");
            $type = request("type");

            if (!in_array($type, Array("XLS", "CSV"))) {
                throw new \Exception("Formato de exportação inválido.");
            }

            $typeObject = null;
            switch($type) {
                case "XLS": $typeObject = \Maatwebsite\Excel\Excel::XLS; break;
                case "CSV": $typeObject = \Maatwebsite\Excel\Excel::CSV; break;
            }

            return Excel::download(new TaxesReportExport($start, $end, $text), 'taxes_report.' . strtolower($type), $typeObject);
            
        } catch (\Exception $ex) {
            return response()->json(['error'=> $ex->getMessage()], 500);
        }
            
    }

}