<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Transfer extends RaveImplementAbstract
{
    /**
     * initiating a single transfer
     * @return object
     * */
    public function singleTransfer($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/transfers");
        //returns the value from the results
        return $this->rave->transferSingle($array);
    }

    /**
     * initiating a bulk transfer
     * @return object
     * */
    public function bulkTransfer($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/bulk-transfers");
        //returns the value from the results
        return $this->rave->transferBulk($array);
    }

    public function listTransfers($array = array())
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/transfers");

        return $this->rave->listTransfers($array);
    }

    public function bulkTransferStatus($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/bulk-transfers");

        return $this->rave->bulkTransferStatus($array);
    }

    public function getTransferFee($array)
    {
        if (!isset($array['amount'])) {
            throw new \Exception("The following query param  is required amount", 1);
        }

        if (gettype($array['amount']) !== "string") {
            $array['amount'] = (string) $array['amount'];
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/transfers/fee");

        return $this->rave->applicableFees($array);
    }
}
