<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class VirtualAccount extends RaveImplementAbstract
{
    /**
     * Creating the VirtualAccount
     */
    public function createvirtualAccount($userdata)
    {
        if (!isset($userdata['email'])) {
            throw new \Exception("The following body params are required: email", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-account-numbers");

        //returns the value of the result.
        return $this->rave->createVirtualAccount($userdata);
    }

    public function createBulkAccounts($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/bulk-virtual-account-numbers");

        //returns the value of the result.
        return $this->rave->createBulkAccounts($array);
    }

    public function getBulkAccounts($array)
    {
        if (!isset($array['batch_id'])) {
            throw new \Exception("The following body params are required: batch_id", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/bulk-virtual-account-numbers/". $array['batch_id']);

        //returns the value of the result.
        return $this->rave->getBulkAccounts($array);
    }

    public function getAccountNumber($array)
    {
        if (!isset($array['order_ref'])) {
            throw new \Exception("The following body params are required: order_ref", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-account-numbers/". $array['order_ref']);

        //returns the value of the result.
        return $this->rave->getvAccountsNum();
    }
}
