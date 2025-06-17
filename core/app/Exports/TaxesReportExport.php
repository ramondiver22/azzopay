<?php
namespace App\Exports;

use App\Models\Charges;
use Maatwebsite\Excel\Concerns\FromArray;

class TaxesReportExport implements FromArray {

    private $start;
    private $end;
    private $text;

    
    public function __construct($start, $end, $text) {
        $this->start = $start;
        $this->end = $end;
        $this->text = $text;
    }

    public function array(): array {
        
        $info = Charges::listCharges(0, $this->start, $this->end, $this->text, 1, 0);
        
        $collection = Array(
            Array(
                "Data",
                "Cliente",
                "Ref Id",
                "Descricao",
                "Valor"
            )
        );
        $totalAmount = 0;
        foreach ($info["charges"] as $charge) {
            $totalAmount += $charge->amount;
            $collection[] = Array(
                date("d/m/Y H:i", strtotime($charge->created_at)),
                "{$charge->first_name} {$charge->last_name}",
                $charge->ref_id,
                $charge->log,
                number_format($charge->amount, 2, ",", ".")
            );
        }

        $collection[] = Array(
            "Valor Total",
            "",
            "",
            "",
            number_format($totalAmount, 2, ",", ".")
        );


        return $collection;
    }
}