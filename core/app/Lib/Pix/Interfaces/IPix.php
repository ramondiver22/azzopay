<?php

namespace App\Lib\Pix\Interfaces;


interface IPix {

    public function criarCobranca($user, $value, $key = null, $description = null);

    /**
     * 
     * @return
     * Array(
     *      "qrcode" => "QRCode content",
     *      "txid" => "Transaction TXID",
     *      "copy" => "Copy Paste content"
     * )
     */
    public function criarCobrancaQrCode(array $user, $value, $key = null, $description = null);
    public function consultarCobranca($txid);
    public function setSandbox($isSandbox);

    public function sendPayment($userData, $pixkey, $pixkeyType, $amount, $description);
    public function consultarPagamento($txid);

    public function listarCobrancas($page, $itensPage, $statusFilter, $dateFrom, $dateTo);
    public function listarPagamentos($page, $statusFilter, $dateFrom, $dateTo);

    
     
}
