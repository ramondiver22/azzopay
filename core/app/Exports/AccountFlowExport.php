<?php
namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromArray;

class AccountFlowExport implements FromArray {

    private $start;
    private $end;
    private $text;
    private $type;
    private $entityType;

    
    public function __construct($start, $end, $text, $type, $entityType) {
        $this->start = $start;
        $this->end = $end;
        $this->text = $text;
        $this->type = $type;
        $this->entityType = $entityType;
    }

    public function array(): array {
        
        $info = History::listHistoric(0, $this->start, $this->end, $this->text, $this->type, $this->entityType, 1, 0);
        
        $collection = Array();
        $collection[] = Array("", "", "", "", "", "");
        $collection[] = Array("", "", "", "", "", "");
        $collection[] = Array("", "", "", "", "", "");
        
        $collection[] = Array("", "", "", "", "", "");
        $collection[] = Array("", "", "", "", "", "");
        $collection = Array(
            Array(
                "Data",
                "Cliente",
                "Movimento",
                "Referencia",
                "Tipo",
                "Valor"
            )
        );

        
        $totalCredit = 0;
        $totalDebit = 0;
        $totalAmount = 0;
        foreach ($info["historic"] as $history) {
            if ($history->type == 1) {
                $totalCredit += $history->amount;
                $totalAmount += $history->amount;
            } else {
                $totalDebit += $history->amount;
                $totalAmount -= $history->amount;
            }
            
            $collection[] = Array(
                date("d/m/Y H:i", strtotime($history->created_at)),
                "{$history->first_name} {$history->last_name}",
                History::getStringType($history->type),
                $history->ref,
                History::getStringEntity($history->entity_type),
                number_format($history->amount, 2, ",", ".")
            );
        }

        $usersBalances = User::sum("balance");

        $collection[0] = Array(
            "Crédito",
            "",
            "",
            "",
            "",
            number_format($totalCredit, 2, ",", ".")
        );

        $collection[1] = Array(
            "Débito",
            "",
            "",
            "",
            "",
            number_format($totalDebit, 2, ",", ".")
        );

        $collection[1] = Array(
            "Saldos de Clientes",
            "",
            "",
            "",
            "",
            number_format($usersBalances, 2, ",", ".")
        );

        $collection[2] = Array(
            "Saldo de Caixa",
            "",
            "",
            "",
            "",
            number_format(($totalAmount - $usersBalances), 2, ",", ".")
        );

        return $collection;
    }
}