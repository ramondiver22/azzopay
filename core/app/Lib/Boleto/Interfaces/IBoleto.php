<?php

namespace App\Lib\Boleto\Interfaces;


interface IBoleto {

    public function createTransaction($user, $transactionTotal, $entityId, $refId, $dueDate, $customer, $instructions =null);
}