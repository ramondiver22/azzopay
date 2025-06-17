<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;

class Preauth extends RaveImplementAbstract
{
    public function accountCharge($array)
    {
        //set the payment handler
        $this->rave->eventHandler(new accountEventHandler)
        //set the endpoint for the api call
        ->setEndPoint("");
        //returns the value from the results
        //you can choose to store the returned value in a variable and validate within this function
        $this->rave->setAuthModel("AUTH");
        return $this->rave->chargePayment($array);
        /**you will need to validate and verify the charge
         * Validating the charge will require an otp
         * After validation then verify the charge with the txRef
         * You can write out your function to execute when the verification is successful in the onSuccessful function
         ***/
    }

    public function captureFunds($array)
    {
        //set the payment handler
        $this->rave->eventHandler(new preEventHandler)
        //set the endpoint for the api call
        ->setEndPoint("flwv3-pug/getpaidx/api/capture");
        //returns the value from the results
        return $this->rave->captureFunds($array);
    }

    public function refundOrVoid($array)
    {
        //set the payment handler
        $this->rave->eventHandler(new preEventHandler)
        //set the endpoint for the api call
        ->setEndPoint("flwv3-pug/getpaidx/api/refundorvoid");
        //returns the value from the results
        return $this->rave->refundOrVoid($array);
    }
}
