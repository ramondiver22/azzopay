<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'ext_transfer',
        'treeal/callbacks',
        'bancobrasil/callbacks',
        'bancooriginal/callbacks',
        'cielo/callbacks',
        'cron/originalhub/token',
        'api/*',
        'user/invoice/verify/payment',
        'user/product/verify/payment',
        'product/verify/payment',
        'user/checkout/verify/payment',
        'user/fund/verify/payment',
        'user/link/verify/payment',
        'user/donation/verify/payment',
        'fitbank/callbacks'
    ];
}
