
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">  
            <div class="col-lg-12">
                @if($set->merchant==1)
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-12">
                                <h3 class="font-weight-bolder">{{$lang["merchant_integration_website_payment"]}}</h3>
                            </div>
                        </div>
                        <div class="align-item-sm-center flex-sm-nowrap text-left">
                            <p class="text-xs mb-1">
                            {{$lang["merchant_receiving_money_on_your_website"]}} {{$set->merchant_charge}}% {{$lang["merchant_per_transaction"]}}    
                            {{$lang["merchant_this_document_will_introduce"]}}</p>
                            <div class="row">
                                <div class="col">
                                    <pre class="">
                                        <code>
                                    &lt;form method="POST" action="{{url('/')}}/ext_transfer" &gt;
                                        &lt;input type="hidden" name="merchant_key" value="MERCHANT KEY" /&gt;
                                        &lt;input type="hidden" name="public_key" value="PUBLIC KEY" /&gt;
                                        &lt;input type="hidden" name="callback_url" value="mydomain.com/success.html" /&gt;
                                        &lt;input type="hidden" name="tx_ref" value="REF_123456" /&gt;
                                        &lt;input type="hidden" name="amount" value="10000" /&gt;
                                        &lt;input type="hidden" name="email" value="user@test.com" /&gt;
                                        &lt;input type="hidden" name="first_name" value="Finn" /&gt;
                                        &lt;input type="hidden" name="last_name" value="Marshal" /&gt;
                                        &lt;input type="hidden" name="title" value="Payment For Item" /&gt;
                                        &lt;input type="hidden" name="description" value="Payment For Item" /&gt;
                                        &lt;input type="hidden" name="quantity" value="10" /&gt;
                                        &lt;input type="hidden" name="currency" value="{{$currency->name}}" /&gt;
                                        &lt;input type="submit" value="submit" /&gt;
                                    &lt;/form&gt;
                                        </code>
                                    </pre>  

                                <p class="text-sm text-dark mb-0"><button type="button" class="btn-icon-clipboard" data-clipboard-text='
                                    <form method="POST" action="{{url("/")}}/ext_transfer" >
                                    <input type="hidden" name="merchant_key" value="MERCHANT KEY" />
                                    <input type="hidden" name="public_key" value="PUBLIC KEY" />
                                    <input type="hidden" name="success_url" value="//www.mydomain.com/success.html" />
                                    <input type="hidden" name="fail_url" value="//www.mydomain.com/failed.html" />
                                    <input type="hidden" name="amount" value="10000" />
                                    <input type="hidden" name="email" value="user@test.com" />
                                    <input type="hidden" name="first_name" value="Finn" />
                                    <input type="hidden" name="last_name" value="Marshal" />
                                    <input type="hidden" name="title" value="Payment For Item" />
                                    <input type="hidden" name="description" value="Payment For Item" />
                                    <input type="hidden" name="quantity" value="10" />
                                    <input type="hidden" name="currency" value="NGN" />
                                    <input type="submit" value="submit" />
                                    </form>' title="Copy code">{{$lang["merchant_copy_code"]}}</button></p>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["merchant_verifying_payment"]}}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-sm">{{$lang["merchant_depending_on_your_callback"]}}</p>
                        <pre>
                            <code>
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_URL, '{{url('/')}}/api/verify-payment/{txref}/{secretkey}');
                                $result = curl_exec($ch);
                                curl_close($ch);
                                $obj=json_decode($result, true);
                                //Verify Payment
                                if (array_key_exists("data", $obj)  && ($obj["status"] == "success")) {
                                    echo 'success';
                                }
                            </code>
                        </pre>
                        <p class="text-sm text-dark mb-3"><button type="button" class="btn-icon-clipboard" data-clipboard-text='
                        $ch = curl_init();
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, fadse);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_URL, "{{url("/")}}/api/verify-payment/{txref}/{secretkey}"");
                                $result = curl_exec($ch);
                                curl_close($ch);
                                $obj=json_decode($result, true);
                                //Verify Payment
                                if (array_key_exists("data", $obj) && ($obj["status"] == "success")) {
                                    echo "success";
                                }
                            ' title="Copy code">{{$lang["merchant_copy_code"]}}</button></p> 
                        <h3 class="mb-0 font-weight-bolder">{{$lang["merchant_successful_json_callback"]}}</h3>  
                        <pre>
                            <code>
                            {
                                "message":null,
                                "status":"success",
                                "data":{
                                    "id":6,
                                    "email":"a@b.com",
                                    "first_name":"qwert",
                                    "last_name":"trewq",
                                    "payment_type":account,
                                    "title":Rubik Cube,
                                    "description":Payment for Rubik Cube,
                                    "quantity":2,
                                    "reference":"Di9Wr1LuC7u4WEGu",
                                    "amount":10000,
                                    "charge":50,
                                    "merchant_key":"r1Kn6nzk1cE63rQE",
                                    "callback_url":"mydomain.com\/thank_you.html",
                                    "tx_ref":"deff",
                                    "status":"paid",
                                    "created_at":"2021-01-01T22:05:02.000000Z",
                                    "updated_at":"2020-05-15T12:05:29.000000Z"
                                }
                            }
                            </code>
                        </pre>
                    </div>
                </div>                
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["merchant_requirements"]}}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-flush">
                            <thead class="">
                                <tr>
                                <th>{{$lang["merchant_s_n"]}}</th>
                                <th>{{$lang["merchant_value"]}}</th>
                                <th>{{$lang["merchant_type"]}}</th>
                                <th>{{$lang["merchant_required"]}}</th>
                                <th>{{$lang["merchant_description"]}}</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <tr>
                                    <td>{{$lang["merchant_1"]}}</td>
                                    <td>{{$lang["merchant_merchant_key"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_used_to_authorize_transaction"]}}</td>
                                </tr>                                            
                                <tr>
                                    <td>{{$lang["merchant_2"]}}</td>
                                    <td>{{$lang["merchant_callback_url"]}}</td>
                                    <td>{{$lang["merchant_url"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_this_is_callback_endpoint"]}}</td>
                                </tr>                                            
                                <tr>
                                    <td>{{$lang["merchant_3"]}}</td>
                                    <td>{{$lang["merchant_tx_ref"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_this_is_the_merchant_reference"]}}</td>
                                </tr>                                                                                         
                                <tr>
                                    <td>{{$lang["merchant_5"]}}</td>
                                    <td>{{$lang["merchant_amount"]}}</td>
                                    <td>{{$lang["merchant_int_above_50_cents"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_cost_of_item_purchased"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_6"]}}</td>
                                    <td>{{$lang["merchant_mail"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_email_of_client_making_payment"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_7"]}}</td>
                                    <td>{{$lang["merchant_first_name"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_first_name_of_client_making_payment"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_8"]}}</td>
                                    <td>{{$lang["merchant_last_name"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_last_name_of_client_making_payment"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_9"]}}</td>
                                    <td>{{$lang["merchant_title"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_title_of_transaction"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_10"]}}</td>
                                    <td>{{$lang["merchant_description"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_description_of_what_transaction_is_for"]}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_11"]}}</td>
                                    <td>{{$lang["merchant_currency"]}}</td>
                                    <td>{{$lang["merchant_string"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_this_is_the_currency_the_transaction"]}} {{$currency->name}}</td>
                                </tr>                                
                                <tr>
                                    <td>{{$lang["merchant_12"]}}</td>
                                    <td>{{$lang["merchant_quantity"]}}</td>
                                    <td>{{$lang["merchant_int"]}}</td>
                                    <td>{{$lang["merchant_yes"]}}</td>
                                    <td>{{$lang["merchant_quantity_of_item_being_payed_for"]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@stop