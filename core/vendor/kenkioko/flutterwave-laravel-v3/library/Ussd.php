<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Ussd extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->rave->setType('ussd');
    }

    public function ussd($array)
    {
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
