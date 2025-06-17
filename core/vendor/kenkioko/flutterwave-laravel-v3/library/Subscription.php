<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Subscription extends RaveImplementAbstract
{
    public function activateSubscription($id)
    {
        $endPoint = 'v3/subscriptions/'.$id.'/activate';

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint($endPoint);
        //returns the value from the results
        return $this->rave->activateSubscription();
    }

    public function getAllSubscription()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/subscriptions");
        //returns the value from the results
        return $this->rave->getAllSubscription();
    }

    public function cancelSubscription($id)
    {
        $endPoint = 'v3/subscriptions/'.$id.'/cancel';

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint($endPoint);
        //returns the value from the results
        return $this->rave->cancelSubscription();
    }
}
