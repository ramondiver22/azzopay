<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class InstantPayment extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->rave->setType('bank_transfer');
        $this->keys = array('amount', 'currency', 'email');
    }

    public function instantpay($array)
    {
        if (!isset($array['amount']) || !isset($array['currency']) || !isset($array['email'])) {
            throw new \Exception("Missing values for one of the following body params: {$this->keys[0]}, {$this->keys[1]} or {$this->keys[2]}", 1);
        }

        //add tx_ref to the paylaod
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/charges?type=".$this->rave->getType());
        //returns the value from the results
        return $this->rave->chargePayment($array);
    }
}
