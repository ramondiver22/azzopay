<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class TokinizedCharge extends RaveImplementAbstract
{
    public function tokenCharge($array)
    {

        //add tx_ref to the paylaod
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        }

        if (gettype($array['amount']) !== "integer") {
            throw new \Exception("Amount needs to be an integer", 1);
        }

        if (!isset($array['token']) || !isset($array['currency']) || !isset($array['country']) || !isset($array['amount']) || !isset($array['email'])) {
            throw new \Exception("Missing Param in the Payload. Please check you payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/tokenized-charges");
        //returns the value from the results
        //you can choose to store the returned value in a variable and validate within this function
        return $this->rave->tokenCharge($array);
    }


    public function updateEmailTiedToToken($data)
    {

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/gpx/tokens/embed_token/update_customer");
        //returns the value from the results
        //you can choose to store the returned value in a variable and validate within this function
        return $this->rave->postURL($data);
    }

    public function bulkCharge($data)
    {
        //https://api.ravepay.co/flwv3-pug/getpaidx/api/tokenized/charge_bulk
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("flwv3-pug/getpaidx/api/tokenized/charge_bulk");

        $this->rave->bulkCharges($data);
    }

    public function bulkChargeStatus($data)
    {
        //https://api.ravepay.co/flwv3-pug/getpaidx/api/tokenized/charge_bulk
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("flwv3-pug/getpaidx/api/tokenized/charge_bulk");

        $this->rave->bulkCharges($data);
    }
}
