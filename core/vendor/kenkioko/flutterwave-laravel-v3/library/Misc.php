<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\Facade\Rave;

class Misc
{
    public function getBalances()
    {
        return Rave::setEndPoint("v3/balances")
                ->getTransferBalance($array);
    }

    public function getBalance($array)
    {
        if (!isset($array['currency'])) {
            $array['currency'] = 'NGN';
        }

        return Rave::setEndPoint("v3/balances/".$array['currency'])
                ->getTransferBalance($array);
    }

    public function verifyAccount($array)
    {
        return Rave::setEndPoint("v3/accounts/resolve")
                ->verifyAccount($array);
    }
}
