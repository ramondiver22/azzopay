<?php
namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Subaccount extends RaveImplementAbstract
{
    public function createSubaccount($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subaccounts");
        //returns the value from the results`
        return $this->rave->createSubaccount($array);
    }

    public function getSubaccounts()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subaccounts");
        //returns the value from the results
        return $this->rave->getSubaccounts();
    }

    public function fetchSubaccount($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing id Parameter in the payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subaccounts/".$array['id']);
        //returns the value from the results
        return $this->rave->fetchSubaccount();
    }

    public function updateSubaccount($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing id Parameter in the payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subaccounts/".$array['id']);
        //returns the value from the results
        return $this->rave->updateSubaccount($array);
    }

    public function deleteSubaccount($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing id Parameter in the payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subaccounts/".$array['id']);
        //returns the value from the results
        return $this->rave->deleteSubaccount();
    }
}
