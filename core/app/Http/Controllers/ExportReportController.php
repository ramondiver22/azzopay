<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Models\Transactions;
use App\Exports\TaxesReportExport;
use Excel;
use Mpdf\Mpdf;

class ExportReportController extends Controller {

    public function index() {

        $invoicesId = Transactions::where("receiver_id", 227)
                    ->where("status", 1)
                    ->select("invoice_id")
                    ->groupBy("amount", "creditcard_transaction_id", "creditcard_authorization_code", "invoice_id")
                    ->havingRaw('COUNT(*) > ?', [1])
                    ->get();

        $whereInvoicesId = Array();
        foreach($invoicesId as $t) {
            $whereInvoicesId[$t->invoice_id] = $t->invoice_id;
        }


        $result = Transactions::where("receiver_id", 227)
                ->where("status", 1)
                ->orderBy("id")
                ->get();

        

        $invalids = Array();

        $totalAmount = 0;
        $totalInvalidAmount = 0;
        $totalInvalidInvoice = 0;


        $mpdf = new Mpdf();
        $mpdf->WriteHTML("<table style='border: 1px solid #000000;'>");
        $mpdf->WriteHTML("<thead>");
        $mpdf->WriteHTML("<tr>");
        $mpdf->WriteHTML("<th>Fatura</th>");
        $mpdf->WriteHTML("<th>Referencia</th>");
        $mpdf->WriteHTML("<th>Transação</th>");
        $mpdf->WriteHTML("<th>Data e Hora</th>");
        $mpdf->WriteHTML("<th>Valor</th>");
        $mpdf->WriteHTML("<th>Espécie</th>");
        $mpdf->WriteHTML("<th>Id Pagamento</th>");
        $mpdf->WriteHTML("</tr>");
        $mpdf->WriteHTML("</thead>");
        $mpdf->WriteHTML("<tbody>");
        
        foreach($result as $r) {
            ob_start();
            $color = "";
            if (isset($whereInvoicesId[$r->invoice_id])) {
                $color= "color: red; font-weight: bold;";

                if (isset($invalids[$r->invoice_id])) {
                    
                    $totalInvalidAmount += $r->amount;
                } else {
                    $invalids[$r->invoice_id] = $r->invoice_id;
                    $totalInvalidInvoice += $r->amount;
                }
                
            }

            $totalAmount += $r->amount;
            ?>
            <tr>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo $r->invoice_id ?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo $r->ref_id ?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo $r->id ?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo date("d/m/Y H:i:s", strtotime($r->created_at))?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo number_format($r->amount, 2, ",", ".") ?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>"><?php echo ucfirst($r->payment_type); ?></td>
                <td style="border: 1px solid #000000;<?php echo $color; ?>">
                    <?php echo $r->creditcard_transaction_id ?>
                </td>
            </tr>
            <?php

            $html = ob_get_contents();
            ob_end_clean();
            $mpdf->WriteHTML($html);
        }
        $mpdf->WriteHTML("</tbody>");
        $mpdf->WriteHTML("</table>");
                
        ob_start();
        ?>
        <table>
            <tbody>
                <tr>
                    <td><h2>Valor total recebido: </h2></td>
                    <td><h2><?php echo number_format($totalAmount, 2, ",", "."); ?> </h2></td>
                </tr>
                <tr>
                    <td><h2>Valor recebido a mais (não somando o primeiro recebimento de cada invoice duplicada): </h2></td>
                    <td><h2><?php echo number_format($totalInvalidAmount, 2, ",", "."); ?> </h2></td>
                </tr>
                <tr>
                    <td><h2>Valor das invoices com problema (apenas o primeiro recebimento de cada invoice duplicada): </h2></td>
                    <td><h2><?php echo number_format($totalInvalidInvoice, 2, ",", "."); ?> </h2></td>
                </tr>
            </tbody>
        </table>
        <br><br><br>
        <?php

        $header = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML($header);
        $mpdf->Output();

    } 
    
    
    
    
}