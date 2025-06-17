<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class VirtualCard extends RaveImplementAbstract
{
    //create card function
    public function createCard($array)
    {
        //set the endpoint for the api call
        if (!isset($array['currency']) || !isset($array['amount']) || !isset($array['billing_name'])) {
            throw new \Exception("Please pass the required values for currency, duration and amount", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards");

        return $this->rave->vcPostRequest($array);
    }

    //get the detials of a card using the card id
    public function getCard($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Please pass the required value for id", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']);

        return $this->rave->vcGetRequest();
    }

    //list all the virtual cards on your profile
    public function listCards()
    {
        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/");

        return $this->rave->vcGetRequest();
    }

    //terminate a virtual card on your profile
    public function terminateCard($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Please pass the required value for id", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']."/terminate");

        return $this->rave->vcPutRequest();
    }

    //fund a virtual card
    public function fundCard($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing value for id in your payload", 1);
        }

        if (gettype($array['amount']) !== 'integer') {
            $array['amount'] = (int) $array['amount'];
        }

        if (!isset($array['currency'])) {
            $array['currency'] = 'NGN';
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']."/fund");

        $data = array(
            "amount"=> $array['amount'],
            "debit_currency"=> $array['currency']
        );

        return $this->rave->vcPostRequest($data);
    }

    // list card transactions
    public function cardTransactions($array)
    {
        if (!isset($array['id'])) {
            throw new \Exception("Missing value for id in your payload", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']."/transactions");

        return $this->rave->vcGetRequest($array);
    }

    //withdraw funds from card
    public function cardWithdrawal($array)
    {
        //set the endpoint for the api call
        if (!isset($array['amount'])) {
            throw new \Exception("Please pass the required value for amount", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']."/withdraw");

        return  $this->rave->vcPostRequest($array);
    }

    public function changeCardBlockStatus($array)
    {
        if (!isset($array['id']) || !isset($array['status_action'])) {
            throw new \Exception("Please pass the required value for id and status_action", 1);
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/virtual-cards/".$array['id']."/"."status/".$array['status_action']);

        return $this->rave->vcPutRequest();
    }
}
