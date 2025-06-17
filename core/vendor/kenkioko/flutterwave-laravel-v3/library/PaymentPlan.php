<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class PaymentPlan extends RaveImplementAbstract
{
    public function createPlan($array)
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/payment-plans");

        if (empty($array['amount']) || !array_key_exists('amount', $array) ||
            empty($array['name']) || !array_key_exists('name', $array) ||
            empty($array['interval']) || !array_key_exists('interval', $array) ||
            empty($array['duration']) || !array_key_exists('duration', $array)) {
                throw new \Exception("Missing values for the following parameters amount, name , interval, or duration", 1);
        }

        //returns the value from the results
        return $this->rave->createPlan($array);
    }

    public function updatePlan($array)
    {
        if (!isset($array['id']) || !isset($array['name']) || !isset($array['status'])) {
            throw new \Exception("Missing values for a parametter: id, name, or status", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/payment-plans/".$array['id']);


        return $this->rave->updatePlan($array);
    }

    public function cancelPlan($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing values for a parametter: id", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/payment-plans/".$array['id']."/cancel");

        return $this->rave->cancelPlan($array);
    }

    public function getPlans()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/payment-plans");

        return $this->rave->getPlans();
    }

    public function getSinglePlan($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing id Parameter in the payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/payment-plans/".$array['id']);

        return $this->rave->getSinglePlan();
    }
}
