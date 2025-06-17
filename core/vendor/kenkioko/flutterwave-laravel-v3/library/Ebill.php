<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Ebill extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->keys = array('amount', 'phone_number','country', 'ip', 'email');
    }

    public function order($array)
    {
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        }

        if (!isset($array['amount']) || !isset($array['phone_number']) ||
        !isset($array['email']) || !isset($array['country']) || !isset($array['ip'])) {
            throw new \Exception("Missing values for one of the following body params: {$this->keys[0]}, {$this->keys[1]}, {$this->keys[2]}, {$this->keys[3]} and {$this->keys[4]}", 1);
        }


        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/ebills");
        //returns the value of the result.
        return $this->rave->createOrder($array);
    }

    public function updateOrder($data)
    {
        if (!isset($data['amount'])) {
            throw new \Exception("Missing values for one of the following body params: {$this->keys[0]} and reference", 1);
        }

        if (gettype($data['amount']) !== 'integer') {
            $data['amount'] = (int) $data['amount'];
        }

        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/ebills/".$data['reference']);
        //returns the value of the result.
        return $this->rave->updateOrder($data);
    }
}
