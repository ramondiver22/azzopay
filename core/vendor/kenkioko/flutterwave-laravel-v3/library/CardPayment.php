<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Card extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->rave->setType('card');
    }

    public function cardCharge($array)
    {
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        } else {
            $this->rave->setTxRef($array['tx_ref']);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/charges?type=".$this->rave->getType());

        //returns the value from the results
        return $this->rave->chargePayment($array);
    }
}
