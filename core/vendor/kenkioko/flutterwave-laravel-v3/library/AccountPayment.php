<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Account extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->rave->setType('account');
        $this->type = array('debit_uk_account','debit_ng_account');
    }

    public function accountCharge($array)
    {
        // add tx_ref to the paylaod
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        } else {
            $this->rave->setTxRef($array['tx_ref']);
        }

        if (!in_array($array['type'], $this->type)) {
            throw new \Exception("The Type specified in the payload  is not {$this->type[0]} or {$this->type[1]}", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        ->setEndPoint("v3/charges?type=".$array['type']);

        //returns the value from the results
        return $this->rave->chargePayment($array);
    }
}
