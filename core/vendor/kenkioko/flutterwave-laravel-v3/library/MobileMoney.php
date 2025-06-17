<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class MobileMoney extends RaveImplementAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->rave->setType('momo');
        $this->type = array("mobile_money_ghana","mobile_money_uganda","mobile_money_zambia","mobile_money_rwanda","mobile_money_franco");
    }

    public function mobilemoney($array)
    {
        //add tx_ref to the paylaod
        if (!isset($array['tx_ref']) || empty($array['tx_ref'])) {
            $array['tx_ref'] = $this->rave->getTxRef();
        }

        if (!in_array($array['type'], $this->type, true)) {
            throw new \Exception("The Type specified in the payload is not {$this->type[0]}, {$this->type[1]}, {$this->type[2]}, {$this->type[3]} or {$this->type[4]}", 1);
        }

        switch ($array['type']) {
            case 'mobile_money_ghana':
                //set type to gh_momo
                $this->type = 'mobile_money_ghana';
                break;

            case 'mobile_money_uganda':
                //set type to ugx_momo
                $this->type = 'mobile_money_uganda';
                break;

            case 'mobile_money_zambia':
                //set type to xar_momo
                $this->type = 'mobile_money_zambia';
                break;

            case 'mobile_money_franco':
                //set type to xar_momo
                $this->type = 'mobile_money_franco';
                break;

            default:
                //set type to momo
                $this->type = 'mobile_money_rwanda';
                break;
        }

        //set the payment handler
        $this->rave->eventHandler($this->getEventHandler())
        //set the endpoint for the api call
        ->setEndPoint("v3/charges?type=".$this->type);
        //returns the value from the results
        return $this->rave->chargePayment($array);
    }
}
