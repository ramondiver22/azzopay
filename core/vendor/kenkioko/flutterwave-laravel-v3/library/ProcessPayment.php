<?php

namespace Laravel\Flutterwave;

use Laravel\Flutterwave\RaveImplementAbstract;
use Illuminate\Support\Facades\Log;

/**
 * FlutterWave process payment implementation
 */
class ProcessPayment extends RaveImplementAbstract
{
    protected $response_options = [
        'echo_response' => false,
        'json_response' => false,
    ];

    /**
     * Sets Rave's mets data.
     *
     * @param   array $meta_data
     * @return  object
     */
    public function setMetaData($meta_data)
    {
        $this->rave->setMetaData($meta_data);
        return $this;
    }

    /**
     * Sets Rave's single sub account.
     *
     * @param   array $subaccount
     * @return  object
     */
    public function setSingleSubaccount($subaccount)
    {
        $this->rave->setSingleSubaccount($subaccount);
        return $this;
    }

    /**
     * Set the response options.
     *
     * @param   array $echo_response
     * @return  object
     */
    public function responseOptions(array $response_options)
    {
        $this->response_options = $response_options;
        return $this;
    }

    /**
     * Echo the response.
     *
     * @param   bool $echo_response
     * @return  object
     */
    public function echoResponse(bool $echo_response)
    {
        $this->response_options['echo_response'] = $echo_response;
        return $this;
    }

    /**
     * Json response.
     *
     * @param   bool $json_response
     * @return  object
     */
    public function jsonResponse(bool $json_response)
    {
        $this->response_options['json_response'] = $json_response;
        return $this;
    }

    /**
     * Process the payment.
     *
     * @param   array $data
     * @return  void
     */
    public function process(array $data)
    {
        if (isset($data['amount'])) {
            // Make payment (required)
            $this->rave->eventHandler($this->getEventHandler())
                 ->setAmount($data['amount'])
                 ->setDescription($data['description'])
                 ->setCountry($data['country'])
                 ->setCurrency($data['currency'])
                 ->setEmail($data['email'])
                 ->setRedirectUrl($data['redirect_url']);

            // optional
            if (isset($data['tx_ref'])) {
                $this->rave->setTxRef($data['tx_ref']);
            }

            if (isset($data['payment_options'])) {
                $this->rave->setPaymentOptions($data['payment_options']);
            }

            if (isset($data['logo_url'])) {
                $this->rave->setPaymensetLogotOptions($data['logo_url']);
            }

            if (isset($data['payment_options'])) {
                $this->rave->setTitle($data['title']);
            }

            if (isset($data['firstname'])) {
                $this->rave->setFirstname($data['firstname']);
            }

            if (isset($data['lastname'])) {
                $this->rave->setLastname($data['lastname']);
            }

            if (isset($data['phonenumber'])) {
                $this->rave->setPhoneNumber($data['phonenumber']);
            }

            if (isset($data['pay_button_text'])) {
                $this->rave->setPayButtonText($data['pay_button_text']);
            }

            if (isset($data['meta_data'])) {
                $this->rave->setMetaData($data['meta_data']);
            }

            if (isset($data['sub_accounts'])) {
                $this->rave->setSubaccounts($data['sub_accounts']);
            }

            // initialize and return html response
            return $this->rave->initialize($this->response_options);
        } else {
            if (isset($data['cancelled'])) {
                // Handle canceled payments
                $this->rave->eventHandler($this->getEventHandler())
                     ->paymentCanceled($data['cancelled']);
            } elseif (isset($data['tx_ref'])) {
                // Handle completed payments
                log::notice('Payment completed. Now requerying payment.');
                $this->rave->eventHandler($this->getEventHandler())
                     ->requeryTransaction($data['transaction_id']);
            } else {
                $warning_msg = "Stop!!! Please pass the txref parameter!";
                log::warning($warning_msg);

                throw new \Exception($warning_msg, 1);
            }
        }
    }
}
