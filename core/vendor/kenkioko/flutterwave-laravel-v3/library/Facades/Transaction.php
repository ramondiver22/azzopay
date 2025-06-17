<?php

namespace Laravel\Flutterwave\Facades;

use Illuminate\Support\Facades\Facade;

class Transaction extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flutterwavetransaction';
    }
}
