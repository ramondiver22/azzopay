<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Settlement extends RaveImplementAbstract
{
    public function fetchSettlement($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing id Parameter in the payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/settlements/".$array['id']);
        //returns the value from the results
        return $this->rave->fetchASettlement();
    }

    public function listAllSettlements()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/settlements");
        //returns the value from the results
        return $this->rave->getAllSettlements();
    }
}
