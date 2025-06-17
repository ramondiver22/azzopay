<?php

namespace Laravel\Flutterwave\Facades;

use Illuminate\Support\Facades\Facade;

class Bvn extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flutterwavebvn';
    }
}
