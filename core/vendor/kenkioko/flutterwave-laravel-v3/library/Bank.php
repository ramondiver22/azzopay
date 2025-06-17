<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Bank extends RaveImplementAbstract
{
    public function getBanksForTransfer($data)
    {
        if (!isset($data['country'])) {
            throw new \Exception("Missing value for country in your payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/banks/".$data['country']."/");

        return $this->rave->getBanksForTransfer();
    }

    public function getBankBranchesForTransfer($data)
    {
        if (!isset($data['id'])) {
            throw new \Exception("Missing value for id in your payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/banks/".$data['id']."/branches");

        return $this->rave->getBankBranchesForTransfer();
    }
}
