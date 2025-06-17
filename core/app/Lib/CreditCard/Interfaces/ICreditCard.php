<?php

namespace App\Lib\CreditCard\Interfaces;


interface ICreditCard {

    /**
     * Executa a cobrança no cartão de crédito
     */
    public function createTransaction($transactionTotal, $refId, $cardData);

    /**
     * Retorna um array com o enum do status e a descrição do status de acordo com o gateway
     */
    public function getTransactionStatus($statusCode);

    /**
     * Retorna a conversão do status do gateway para o código de status da transação no sistema
     */
    public function toTransactionStatus($statusCode);
}