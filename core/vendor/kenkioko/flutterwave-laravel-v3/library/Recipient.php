<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Recipient extends RaveImplementAbstract
{
    public function createRecipient($array)
    {
        if (!isset($array['account_number']) || !isset($array['account_bank'])) {
            throw new \Exception("The following body params are required account_number and account_bank", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/beneficiaries");
        //returns the value from the results
        return $this->rave->createBeneficiary($array);
    }

    public function listRecipients()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/beneficiaries");
        //returns the value from the results
        return $this->rave->getBeneficiaries();
    }

    public function fetchBeneficiary($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("The following PATH param is required : id", 1);
        }

        if (gettype($array['id']) !== 'string') {
            $array['id'] = (string) $array['id'];
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/beneficiaries/". $array['id']);
        //returns the value from the results
        return $this->rave->getBeneficiaries();
    }

    public function deleteBeneficiary($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("The following PATH param is required : id", 1);
        }

        if (gettype($array['id']) !== 'string') {
            $array['id'] = (string) $array['id'];
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/beneficiaries/". $array['id']);
        //returns the value from the results
        return $this->rave->deleteBeneficiary();
    }
}
