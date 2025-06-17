<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Bvn extends RaveImplementAbstract
{
    public function verifyBVN($bvn)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        ->setEndPoint("v3/kyc/bvns");
        //returns the value from the results
        return $this->rave->bvn($bvn);
    }
}
