<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Order extends Model {
    protected $table = "orders";
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }      
    public function seller()
    {
        return $this->belongsTo('App\Models\User','seller_id');
    }     
    public function buyer()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }    
    public function lala()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }    
    public function ship()
    {
        return $this->belongsTo('App\Models\Shipping','ship_id');
    }

    
    public static function saveOrderList($client, $seller, $cart, $shippingData = null, $addressData = null) {
        $orders = Array();
        $tokenx='ORD-'.str_random(6);

        $currency=Currency::whereStatus(1)->first();
        $data = Array();
        foreach($cart as $checkout) {
            $product = Product::where("id", $checkout->product)->first();

            if ($product->add_status > 0) {
            
                if ($shippingData == null) {
                    throw new \Exception("Você precisa informar o endereço de entrega.");
                }
    
                if (!isset($shippingData->ship_id) || empty($shippingData->ship_id)) {
                    throw new \Exception("Você precisa selecionar uma região de entrega.");
                }
    
                if (!isset($addressData->address) || empty($addressData->address)) {
                    throw new \Exception("Você precisa informar o endereço de entrega.");
                }
                if (!isset($addressData->country) || empty($addressData->country)) {
                    throw new \Exception("Você precisa selecionar o país de entrega.");
                }
    
                if (!isset($addressData->state) || empty($addressData->state)) {
                    throw new \Exception("Você precisa selecionar o estado de entrega.");
                }
                if (!isset($addressData->town) || empty($addressData->town)) {
                    throw new \Exception("Você precisa selecionar a cidade de entrega.");
                }
    
            }
    
            if ($product->note_status > 0) {
                if (!isset($addressData->note) || empty($addressData->note)) {
                    throw new \Exception("O produto {$product->name} precisa que seja informado o campo observações de entrega.");
                }
            }


            $shippingValue = (isset($shippingData->shipping_fee) ? $shippingData->shipping_fee : 0);

            $subtotal = ($checkout->quantity * $product->amount);
            $total = ($subtotal + $shippingValue);
            $charge = 0;

            $data[] = Array(
                "user_id" => $client->id,
                "first_name" => $client->first_name,
                "last_name" => $client->last_name,
                "seller_id" => $seller->id,
                "email" => $client->email,
                "ip_address" => user_ip(),
                "card_number" => null,
                "payment_type" => null,
                "product_id" => $checkout->product,
                "quantity" => $checkout->quantity,
                "amount" => $subtotal,
                "rate" => null,
                "currency" => $currency->name,
                "total" => $total,
                "charge" => $charge,
                "shipping_fee" => $shippingValue,
                "ship_id" => (isset($shippingData->ship_id) ? $shippingData->ship_id : null),
                "address" => (isset($addressData->address) ? $addressData->address : null),
                "country" => (isset($addressData->country) ? $addressData->country : null),
                "state" => (isset($addressData->state) ? $addressData->state : null),
                "town" => (isset($addressData->town) ? $addressData->town : null),
                "ref_id" => $tokenx,
                "status" => 0,
                "phone" => $client->phone,
                "note" => (isset($addressData->note) ? $addressData->note : null),
                "store_id" => $checkout->store,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            );

            
        }

        $orders = Array();
        foreach($data as $d) {
            $orders[] = self::create($d);
        }

        return $orders;
    }

    public static function createOrder($client, $seller, $checkout, $shippingData = null, $addressData = null) {
        $set=Settings::first();
        $currency=Currency::whereStatus(1)->first();

        $tokenx='ORD-'.str_random(6);
        
        $product = Product::where("id", $checkout->product)->first();

        $subtotal = ($checkout->quantity * $product->amount);
        $shippingValue = (isset($shippingData->shipping_fee) ? $shippingData->shipping_fee : null);

        $charge = 0;

        $total = number_format((($subtotal + $shippingValue)-$charge), 2, ".", "");
        

        if ($product->add_status > 0) {
            
            if ($shippingData == null) {
                throw new \Exception("Você precisa informar o endereço de entrega.");
            }

            if (!isset($shippingData->ship_id) || empty($shippingData->ship_id)) {
                throw new \Exception("Você precisa selecionar uma região de entrega.");
            }

            if (!isset($addressData->address) || empty($addressData->address)) {
                throw new \Exception("Você precisa informar o endereço de entrega.");
            }
            if (!isset($addressData->country) || empty($addressData->country)) {
                throw new \Exception("Você precisa selecionar o país de entrega.");
            }

            if (!isset($addressData->state) || empty($addressData->state)) {
                throw new \Exception("Você precisa selecionar o estado de entrega.");
            }
            if (!isset($addressData->town) || empty($addressData->town)) {
                throw new \Exception("Você precisa selecionar a cidade de entrega.");
            }

        }

        if ($product->note_status > 0) {
            if (!isset($addressData->note) || empty($addressData->note)) {
                throw new \Exception("O produto {$product->name} precisa que seja informado o campo observações de entrega.");
            }
        }

        $data = Array(
            "user_id" => $client->id,
            "first_name" => $client->first_name,
            "last_name" => $client->last_name,
            "seller_id" => $seller->id,
            "email" => $client->email,
            "ip_address" => user_ip(),
            "card_number" => null,
            "payment_type" => null,
            "product_id" => $checkout->product,
            "quantity" => $checkout->quantity,
            "amount" => $subtotal,
            "rate" => null,
            "currency" => $currency->name,
            "total" => $total,
            "charge" => $charge,
            "shipping_fee" => $shippingValue,
            "ship_id" => (isset($shippingData->ship_id) ? $shippingData->ship_id : null),
            "address" => (isset($addressData->address) ? $addressData->address : null),
            "country" => (isset($addressData->country) ? $addressData->country : null),
            "state" => (isset($addressData->state) ? $addressData->state : null),
            "town" => (isset($addressData->town) ? $addressData->town : null),
            "ref_id" => $tokenx,
            "status" => 0,
            "phone" => $client->phone,
            "note" => (isset($addressData->note) ? $addressData->note : null),
            "store_id" => $checkout->store,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        );

        $order = self::create($data);
        

        
        return $order;

    }



    public function updatePixInfo($txId, $qrcode, $copy) {
        $this->pix_transaction_id = $txId;
        $this->pix_qrcode = $qrcode;
        $this->pix_copy_past = $copy;

        self::where("ref_id", $this->ref_id)
        ->update(Array(
            "pix_transaction_id" => $txId,
            "pix_qrcode" => $qrcode,
            "pix_copy_past" => $copy
        ));
    }


    public function updateBoletoInfo($paymentId, $boletoURL, $barcode, $digitableLine) {
        $this->boleto_transaction_id = $paymentId;
        $this->boleto_url = $boletoURL;
        $this->boleto_barcode = $barcode;
        $this->boleto_digitable_line = $digitableLine;


        self::where("ref_id", $this->ref_id)
            ->update(Array(
                "boleto_transaction_id" => $paymentId,
                "boleto_url" => $boletoURL,
                "boleto_barcode" => $barcode,
                "boleto_digitable_line" => $digitableLine
            ));
    }

    public function proccessPayment($paymentInfo, $paymentMethod, $userClient = null) {

        
        if ($this->status == 1) {
            throw new \Exception("Order already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Order already canceled.");
        }

        $set = Settings::first();

        $orders = self::where("ref_id", $this->ref_id)->get();

        if ($paymentInfo->status == 1) {

            $totalReceived = 0;

            $sellTotal = 0;
            $sellTotalShipping = 0;
            foreach($orders as $order) {
                $sellTotal += $order->total;
                $sellTotalShipping += $order->shipping_fee;

                $totalReceived = $order->total_received;
            }

            $sellTotalProducts = ($sellTotal - $sellTotalShipping);


            $liquidReceivedValue = 0;
            if ($paymentInfo->totalReceived < $sellTotal) {
                // retiro o valor do frete proporcional
                $percent = ($paymentInfo->totalReceived / $sellTotal);
                $proporcionalShipping = number_format(($percent * $sellTotalShipping), 2, ".", "");
                $liquidReceivedValue = ($paymentInfo->totalReceived - $proporcionalShipping);
            } else {
                $liquidReceivedValue = ($paymentInfo->totalReceived - $sellTotalShipping);
            }

            $totalReceived += $paymentInfo->totalReceived;

            $status = 0;
            
            //exit("{$totalReceived}  >= {$sellTotal}");
            if ($totalReceived >= $sellTotal) {
                $status = 1;
            }

            $userTax = UserTax::getUserTax($this->seller_id);

            $total = 0;
            $charge = 0;
            if (strtolower($paymentMethod) == "creditcard") {
                $charge = $paymentInfo->totalReceived - $sellTotal;
                $total = $sellTotal;
            } else {
                $charge = number_format(($liquidReceivedValue * $userTax->product_charge / 100 + ($userTax->product_chargep)), 2, ".", "");
                $total = number_format(($paymentInfo->totalReceived-$charge), 2, ".", "");
            }
            
        
            foreach($orders as $order) {

                if ($status == 1) {
                    if (strtolower($paymentMethod) == "creditcard") {
                        $order->charge = number_format(($charge / sizeof($orders)), 2, ".", "");
                    } else {
                        $order->charge = number_format((( ($order->total - $order->shipping_fee) * $userTax->product_charge / 100) + $userTax->product_chargep), 2, ".", "");
                    }
                }

                $order->total_received += $paymentInfo->totalReceived;
                $order->status = $status;
                $order->payment_type = strtolower($paymentMethod);

                $order->creditcard_payment_id = (isset($paymentInfo->paymentId) ? $paymentInfo->paymentId : null);
                $order->creditcard_brand = (isset($paymentInfo->brand) ? strtoupper($paymentInfo->brand) : null);
                $order->creditcard_installments = (isset($paymentInfo->installments) ? $paymentInfo->installments : null);
                $order->creditcard_callback = (isset($paymentInfo->json) ? $paymentInfo->json : null);
                $order->creditcard_status = (isset($paymentInfo->statusEnum) ? $paymentInfo->statusEnum : null);
                $order->creditcard_status_description = (isset($paymentInfo->statusDescription) ? $paymentInfo->statusDescription : null);
                $order->creditcard_authorization_code = (isset($paymentInfo->authorizationCode) ? $paymentInfo->authorizationCode : null);
                $order->creditcard_transaction_id = (isset($paymentInfo->transactionId) ? $paymentInfo->transactionId : null);
                $order->creditcard_proof_of_sale = (isset($paymentInfo->proofOfSale) ? $paymentInfo->proofOfSale : null);

                $order->client_name = (isset($paymentInfo->client_name) ? $paymentInfo->client_name : null);
                $order->client_document = (isset($paymentInfo->client_document) ? $paymentInfo->client_document : null);

                $order->save();


                if ($status == 1) {
                    $product = Product::whereid($order->product_id)->first();
                    $product->sold($order->quantity);
                }
            }


            $userSeller = User::whereid($this->seller_id)->first();
            if (strtolower($paymentInfo->paymentMethod) == "creditcard") { 
                PendingBalance::saveBalance($userSeller->id, $total, $charge, PendingBalance::STORE, $this->ref_id, "Venda {$this->id} ");
            } else {
                $userSeller->updateBalance($userSeller, $total, "credit");
                $chargeDescription = 'Received payment for order #' .$this->ref_id;
                Charges::registerCharge($userSeller->id, $this->ref_id, $charge, $chargeDescription);

                History::registerHistory($userSeller->id, $total, $this->ref_id, 1, 1, $charge);
            }
            
            if ($this->store_id > 0) {
                $merchant = Storefront::whereid($this->store_id)->first();
                $merchant->revenue = $merchant->revenue + $total;
                $merchant->save();
            }

            $logDescription = 'Received payment for order #' .$this->ref_id;
            Audit::registerLog($userSeller->id, $this->ref_id, $logDescription);

            if (!empty($userSeller->callback_url)) {
                $body = Array(
                    "entity" => "STORE",
                    "reference" => $orders[0]->ref_id,
                    "receivedValue" => $paymentInfo->totalReceived,
                    "receivedTotal" => $orders[0]->total_received
                );
                Callback::createCallback("STORE", $orders[0]->ref_id, $orders[0]->id, $body, $userSeller->callback_url);
            }
        }
    }



    public function proccessPaymentWithAccountBalance($userClient, $totalPaid) {

        if ($this->status == 1) {
            throw new \Exception("Payment Link already paid.");
        }
        
        if ($this->status == 2) {
            throw new \Exception("Payment Link already canceled.");
        }

        if (!($totalPaid > 0)) {
            throw new \Exception("O valor do pagamento precisa ser maior que zero");
        }

        if ($userClient->id == $this->seller_id) {
            throw new \Exception("Você não pode pagar um link gerado em sua própria conta.");
        }
        
        $set = Settings::first();

        $totalReceived = 0;

        $sellTotal = 0;
        $sellTotalShipping = 0;
        $orders = self::where("ref_id", $this->ref_id)->get();
        foreach($orders as $order) {
            $sellTotal += $order->total;
            $sellTotalShipping += $order->shipping_fee;

            $totalReceived = $order->total_received;
        }

        $sellTotalProducts = ($sellTotal - $sellTotalShipping);


        $liquidReceivedValue = 0;
        if ($totalPaid < $sellTotal) {
            // retiro o valor do frete proporcional
            $percent = ($totalPaid / $sellTotal);
            $proporcionalShipping = number_format(($percent * $sellTotalShipping), 2, ".", "");
            $liquidReceivedValue = ($totalPaid - $proporcionalShipping);
        } else {
            $liquidReceivedValue = ($totalPaid - $sellTotalShipping);
        }

        $totalReceived += $totalPaid;

        $status = 0;
        if ($totalReceived >= $sellTotal) {
            $status = 1;
        }

        $userTax = UserTax::getUserTax($this->seller_id);

        $charge = number_format(($liquidReceivedValue * $userTax->product_charge / 100 + ($userTax->product_chargep)), 2, ".", "");
        $total = number_format(($totalPaid-$charge), 2, ".", "");
    
        foreach($orders as $order) {

            if ($status == 1) {
                $order->charge = number_format((( ($order->total - $order->shipping_fee) * $userTax->product_charge / 100) + $userTax->product_chargep), 2, ".", "");
            }
            $order->total_received += $totalPaid;
            $order->status = $status;
            $order->payment_type = "account";

            $order->save();


            if ($status == 1) {
                $product = Product::whereid($order->product_id)->first();
                $product->sold($order->quantity);
            }
        }


        $userSeller = User::whereid($this->seller_id)->first();
        $userSeller->updateBalance($userSeller, $total, "credit");
        $userClient->updateBalance($userClient, $totalPaid, "debit");

        if ($this->store_id > 0) {
            $merchant = Storefront::whereid($this->store_id)->first();
            $merchant->revenue = $merchant->revenue + $total;
            $merchant->save();
        }

        $logDescription = 'Received payment for order #' .$this->ref_id;
        Audit::registerLog($userSeller->id, $this->ref_id, $logDescription);

        $chargeDescription = 'Received payment for order #' .$this->ref_id;
        Charges::registerCharge($userSeller->id, $this->ref_id, $charge, $chargeDescription);

        History::registerHistory($userSeller->id, $total, $this->ref_id, 1, 1, $charge);
        History::registerHistory($userClient->id, $totalPaid, $this->ref_id, 1, 2);
        
        if (!empty($userSeller->callback_url)) {
            $body = Array(
                "entity" => "STORE",
                "reference" => $orders[0]->ref_id,
                "receivedValue" => $paymentInfo->totalReceived,
                "receivedTotal" => $orders[0]->total_received
            );
            Callback::createCallback("STORE", $orders[0]->ref_id, $orders[0]->id, $body, $userSeller->callback_url);
        }
    }

}
