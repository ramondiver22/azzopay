@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">

        <div class="row">
            <div class="col col-md-12">
                <div id="card-contact-component" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="card-contact-component-tab">
                    <div class="card">

                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar avatar-xl rounded-circle">
                                        <img alt="Image placeholder" src="{{url('/')}}/asset/profile/{{$client->image}}">
                                    </a>
                                </div>
                                <div class="col ml--2">
                                    <h4 class="mb-0">
                                        <a href="#!">{{$client->first_name}} {{$client->last_name}}</a>
                                    </h4>
                                    <p class="text-sm text-muted mb-0">{{$lang['admin_users_fees_email']}}: {{$client->email}}</p>
                                    <p class="text-sm text-muted mb-0">{{$lang['admin_users_fees_mobile']}}: {{$client->phone}}</p>
                                    <p class="text-sm text-muted mb-0">{{$lang['admin_users_fees_balance']}}: {{number_format($client->balance, 2, ",", ".")}}</p>
                                    
                                </div>
                                <div class="col-auto">
                                    <a href="{{route('user.manage', ['id' => $client->id])}}" class="btn btn-sm btn-primary">{{$lang['admin_users_fees_manage_user']}}</a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_users_fees_title']}}</h3>
                    </div>
                    <div class="card-body">

                        <form action="{{url('admin/profile-update')}}" method="post" id="form-user-taxes">
                            @csrf     
                            <input type="hidden" id="user_id" name="user_id" value="{{$client->id}}"/> 
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                @if($tax != null && $tax->use_taxes==1)
                                    <input type="checkbox" name="use_taxes" id="useIndividualSettings" class="custom-control-input" value="1" checked>
                                @else
                                    <input type="checkbox" name="use_taxes" id="useIndividualSettings"  class="custom-control-input" value="1">
                                @endif
                                <label class="custom-control-label" for="useIndividualSettings">
                                    <span class="text-muted">{{$lang["admin_users_fees_use_individual_taxes"]}}</span>     
                                </label>
                            </div>  

                            <hr>

                            <p>{{$lang["admin_users_fees_withdraw"]}}</p>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any"  name="withdraw_charge" id="withdraw_charge" value="{{($tax != null ? $tax->withdraw_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->withdraw_charge}}%</p>
                                </div>                                    
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any"  name="withdraw_chargep"  id="withdraw_chargep" value="{{($tax != null ? $tax->withdraw_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->withdraw_chargep}}</p>
                                </div>
                            </div>

                            <p>{{$lang["admin_users_fees_transfer_request"]}}</p>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any"  name="transfer_charge" id="transfer_charge" value="{{($tax != null ? $tax->transfer_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->transfer_charge}} %</p>
                                </div>                                    
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any"  name="transfer_chargep" id="transfer_chargep"  value="{{($tax != null ? $tax->transfer_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->transfer_chargep}}</p>
                                </div>
                            </div>

                            <p>{{$lang["admin_users_fees_merchant"]}}</p>
                            <div class="form-group row">                                                                                                                                                                                                                       
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number"step="any"  name="merchant_charge" id="merchant_charge" value="{{($tax != null ? $tax->merchant_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->merchant_charge}}%</p>
                                </div> 
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="merchant_chargep" id="merchant_chargep" value="{{($tax != null ? $tax->merchant_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->merchant_chargep}}</p>
                                </div>
                            </div> 

                            <p>{{$lang["admin_users_fees_invoice"]}}</p>
                            <div class="form-group row">                               
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any"  name="invoice_charge" id="invoice_charge" value="{{($tax != null ? $tax->invoice_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->invoice_charge}}%</p>
                                </div>
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="invoice_chargep" id="invoice_chargep" value="{{($tax != null ? $tax->invoice_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->invoice_chargep}}</p>
                                </div>
                            </div>


                            <p>{{$lang["admin_users_fees_product"]}}</p>
                            <div class="form-group row">                             
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any" name="product_charge" id="product_charge" max-length="10" value="{{($tax != null ? $tax->product_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->product_charge}}%</p>
                                </div> 
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="product_chargep" id="product_chargep" value="{{($tax != null ? $tax->product_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->product_chargep}}</p>
                                </div> 
                            </div>


                            <p>{{$lang["admin_users_fees_single_charge"]}}</p>
                            <div class="form-group row">                                   
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any"  name="single_charge" id="single_charge" max-length="10" value="{{($tax != null ? $tax->single_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->single_charge}}%</p>
                                </div> 
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="single_chargep" id="single_chargep"  value="{{($tax != null ? $tax->single_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->single_chargep}}</p>
                                </div> 
                            </div>


                            <p>{{$lang["admin_users_fees_donation"]}}</p>
                            <div class="form-group row">                                    
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number"step="any"  name="donation_charge" id="donation_charge" max-length="10" value="{{($tax != null ? $tax->donation_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->donation_charge}}%</p>
                                </div> 
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}} <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="donation_chargep" id="donation_chargep" value="{{($tax != null ? $tax->donation_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->donation_chargep}}</p>
                                </div>  
                            </div>

                            <p>{{$lang["admin_users_fees_bill_payment"]}}</p>
                            <div class="form-group row">  
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_percent"]}}<span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" step="any" name="bill_charge" id="bill_charge" value="{{($tax != null ? $tax->bill_charge : '')}}" class="form-control" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$set->bill_charge}}%</p>
                                </div>
                                <label class="col-form-label col-lg-3">{{$lang["admin_users_fees_fiat"]}}<span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" name="bill_chargep" id="bill_chargep" value="{{($tax != null ? $tax->bill_chargep : '')}}" class="form-control" required>
                                    </div>
                                    <p>{{$lang['admin_users_fees_default_fee']}} {{$currency->symbol}} {{$set->bill_chargep}}</p>
                                </div>  
                            </div> 
                            <hr>

                            <div class="form-group row">  
                                <label class="col-form-label col-lg-6">Quantidade de dias para liberação dos valores de cartão de crédito.<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <input type="number" name="days_creditcard_liquidation" value="{{$tax->days_creditcard_liquidation??''}}" class="form-control numeric-input" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">Dias</span>
                                        </span>
                                    </div>
                                </div>
                            </div>  
                            
                            <div class="text-right">
                                <button type="button" onclick="updateUserFees();" class="btn btn-success btn-sm" id="btn-save">{{$lang['admin_users_fees_save']}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Comissões sobre taxas de indicados</h3>
                    </div>
                    <div class="card-body">

                        <div class="custom-control custom-control-alternative custom-checkbox">
                            @if($tax != null && $tax->use_comissions==1)
                                <input type="checkbox" name="use_comissions" id="useIndividualComissions" class="custom-control-input" value="1" checked>
                            @else
                                <input type="checkbox" name="use_comissions" id="useIndividualComissions"  class="custom-control-input" value="1">
                            @endif
                            <label class="custom-control-label" for="useIndividualComissions">
                                <span class="text-muted">Utilizar as comissões abaixo para o cliente</span>     
                            </label>
                        </div>  


                        <div class="row">
                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de saque
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="withdraw-comission" id="withdraw-comission" value="{{number_format(($tax->withdraw_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de depósito
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="deposit-comission" id="deposit-comission" value="{{number_format(($tax->deposit_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de Invoice
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="invoice-comission" id="invoice-comission" value="{{number_format(($tax->invoice_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de Link de Pagamento
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="payment-link-comission" id="payment-link-comission" value="{{number_format(($tax->payment_link_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de Doação
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="donation-comission" id="donation-comission" value="{{number_format(($tax->donation_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="col col-md-6">
                                <div class="form-group m-2">
                                    <label class="form-label">
                                        Comissão sobre taxa de venda de produtos da vitrine
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="store-comission" id="store-comission" value="{{number_format(($tax->store_comission ?? 0), 2, '.', '')}}" class="form-control money-mask" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <div class="text-right">
                            <button type="button" class="btn btn-success btn-sm" onclick="updateComissionTaxes();" id="btn-save-comissions">Atualizar Comissões</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>



        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Habilitação de Formas de Pagamento</h3>
                    </div>
                    <div class="card-body">

                        <div class="row mt-2">
                            <div class="col-lg-3">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    
                                    <input type="checkbox" name="use_payment_configs" id="use_payment_configs" @if(($tax->use_payment_configs ?? 0) > 0) checked  @endif class="custom-control-input" value="1" >
                                    
                                    <label class="custom-control-label" for="use_payment_configs">
                                        <span class="text-muted">Utilizar configurações abaixo para este cliente:</span>     
                                    </label>
                                </div> 
                            </div>
                        </div>

                        <div class="row mt-2">
                            
                            <div class="col-lg-3">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    
                                    <input type="checkbox" name="enable_account_payment" id="enable_account_payment" @if(($tax->enable_account_payment ?? 0) > 0) checked  @endif class="custom-control-input" value="1" >
                                    
                                    <label class="custom-control-label" for="enable_account_payment">
                                        <span class="text-muted">Pagamento com sado da conta</span>     
                                    </label>
                                </div> 
                            </div>
                            

                            <div class="col-lg-3">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    
                                    <input type="checkbox" name="enable_boleto_payment" id="enable_boleto_payment" @if(($tax->enable_boleto_payment ?? 0) > 0) checked  @endif class="custom-control-input" value="1" >
                                    
                                    <label class="custom-control-label" for="enable_boleto_payment">
                                        <span class="text-muted">Pagamento com Boleto</span>     
                                    </label>
                                </div> 
                            </div>


                            <div class="col-lg-3">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    
                                    <input type="checkbox" name="enable_creditcard_payment" id="enable_creditcard_payment" @if(($tax->enable_creditcard_payment ?? 0) > 0) checked  @endif class="custom-control-input" value="1" >
                                    
                                    <label class="custom-control-label" for="enable_creditcard_payment">
                                        <span class="text-muted">Pagamento com Cartão de Crédito</span>     
                                    </label>
                                </div> 
                            </div>

                            <div class="col-lg-3">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    
                                    <input type="checkbox" name="enable_pix_payment" id="enable_pix_payment" @if(($tax->enable_pix_payment ?? 0) > 0) checked  @endif class="custom-control-input" value="1" >
                                    
                                    <label class="custom-control-label" for="enable_pix_payment">
                                        <span class="text-muted">Pagamento com Pix</span>     
                                    </label>
                                </div> 
                            </div>
                        </div>
                    
                            
                    </div>
                    
                    <div class="card-footer">

                        <div class="text-right">
                            <button type="button" class="btn btn-success btn-sm" id="btn-payments-methods-update" onclick="savePaymentsFormConfig();">Salvar Configurações de Gateway</button>
                        </div>
                    </div>
                </div>   

            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Configurar Gateway para Boletos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">Gateway para cobranças via Boleto<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <select class="form-control select" name="gateway_boleto" id="gateway_boleto" required="">
                                            <option value="0">Utilizar o Gateway Padrão</option>
                                            @foreach($gatewaysBoleto as $gateway) {
                                            <option value="{{$gateway->id}}" {{(($gatewayBoleto && $gatewayBoleto->gateway_id == $gateway->id) ? 'selected' : '')}}>{{$gateway->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>      
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Configurar Gateway para Cartão de Crédito</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">Gateway para cobranças via Cartão de Crédito<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <select class="form-control select" name="gateway_creditcard" id="gateway_creditcard" required="">
                                            <option value="0">Utilizar o Gateway Padrão</option>
                                            @foreach($gatewaysCreditcard as $gateway) {
                                            <option value="{{$gateway->id}}" {{(($gatewayCreditcard && $gatewayCreditcard->gateway_id == $gateway->id) ? 'selected' : '')}}>{{$gateway->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>      
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Configurar Gateway de Payin e Payout para PIX</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">Gateway para Cash In<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <select class="form-control select" name="gateway_cashin" id="gateway_cashin" required="">
                                            <option value="0">Utilizar o Gateway Padrão</option>
                                            @foreach($gatewaysIn as $gateway) {
                                            <option value="{{$gateway->id}}" {{(($gatewayCashIn && $gatewayCashIn->gateway_id == $gateway->id) ? 'selected' : '')}}>{{$gateway->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>      
                                </div>    
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">Gateway para Cash Out<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <select class="form-control select" name="gateway_cashout" id="gateway_cashout" required="">
                                            <option value="0">Utilizar o Gateway Padrão</option>
                                            @foreach($gatewaysOut as $gateway) {
                                            <option value="{{$gateway->id}}" {{(($gatewayCashOut && $gatewayCashOut->gateway_id == $gateway->id) ? 'selected' : '')}} >{{$gateway->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>      
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-footer">
                        <div class="text-right mt-2 mb-2">
                            <button type="button" class="btn btn-success btn-sm" id="btn-gateway-pix" onclick="saveGatewayConfig();">Salvar Configurações de Gateway</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Taxas Sobre Parcelamento Com Cartão de Crédito</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col col-md-3 col-xs-12">

                                <div class="form-group row"> 
                                    <label class="col-form-label col-lg-12">Parcelas<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text"  name="installments_qtd" id="installments_qtd" value="" class="form-control numeric-input" required>
                                        </div>
                                    </div>  
                                </div> 

                            </div>

                            <div class="col col-md-3 col-xs-12">
                                <div class="form-group row">  
                                    <label class="col-form-label col-lg-12">Taxa Fixa<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <span class="input-group-append">
                                                <span class="input-group-text">R$</span>
                                            </span>
                                            <input type="text"  name="installments_tax" id="installments_tax" value="" class="form-control money-mask" required>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="col col-md-3 col-xs-12">
                                <div class="form-group row">  
                                    <label class="col-form-label col-lg-12">Taxa Percentual<span class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text"  name="installments_taxp"  id="installments_taxp" value="" class="form-control money-mask" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-3 col-xs-12 mb-5">
                                <button type="button" class="btn btn-primary w-100 mt-5" id="installments_btn" onclick="saveInstallmentFee();">
                                    Salvar Taxa
                                </button>
                            </div>

                        </div>

                        <div class="row mt-2 mb-2">
                            <div class="col col-xs-12 text-right">
                                <button type="button" class="btn btn-success mt-5" id="installments_btn_activate"  onclick="activateTale();" @if($tableActivated > 0) style="display: none" @endif >
                                    Ativar Tabela
                                </button>
                                <button type="button" class="btn btn-danger mt-5" id="installments_btn_disabled" onclick="disableTable();" @if($tableActivated < 1) style="display: none" @endif>
                                    Desativar Tabela
                                </button>
                            </div>
                        </div>
                        <table class="table table-flush" >
                            <thead>
                                <tr>
                                    <th class="text-center">Parcelamento</th>
                                    <th class="text-center">Taxa Fixa</th>
                                    <th class="text-center">Taxa Percentual</th>
                                    <th class="text-center">Excluir</th>   
                                </tr>
                            </thead>
                            <tbody id="tbody-installments">
                            
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


    <div class="modal fade" id="modal-delete-creditcard-tax" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">Excluir Taxa de Cartão</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <input type="hidden" id="modal-delete-creditcard-tax-id" name="modal-delete-creditcard-tax-id" />

                            <h4 class="mt-5">Deseja realmente excluir a faixa de taxa escolhida?</h4>
                         </div>
                        <div class="card-footer px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-delete-creditcard-tax-cancel">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-delete-creditcard-tax-save" onclick="deleteInstallmentTax();">Alterar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@push('scripts')
<script>


    $(document).ready(function () {
        listCardTaxes();
    });

    function updateUserFees() {
        let ajaxcointableform = JSON.stringify($('#form-user-taxes').serializeArray());
        let data  = $.parseJSON(ajaxcointableform);
      
        $("#btn-save").prop("disabled", true);
        $.post("{{route('user.taxes.update')}}", data, function (json) {
            try {
                if (json.success) {

                    toastr.success(json.message);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, 'json').always(function () {
            $("#btn-save").prop("disabled", false);
        });
    }

    function updateComissionTaxes() {

        let data = {
            _token: "{{ csrf_token() }}",
            user_id: {{$client->id}},
            use_comissions: ($("#use_comissions").is(":checked") ? 1 : 0),
            withdraw_comission: $("#withdraw-comission").val(),
            deposit_comission: $("#deposit-comission").val(),
            invoice_comission: $("#invoice-comission").val(),
            payment_link_comission: $("#payment-link-comission").val(),
            donation_comission: $("#donation-comission").val(),
            store_comission: $("#store-comission").val()
        }

        $("#btn-save-comissions").prop("disabled", true);
        $.post("{{route('user.comissions.update')}}", data, function (json) {
            try {
                if (json.success) {
                    toastr.success(json.message);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#btn-save-comissions").prop("disabled", false);
        });
    }

    function listCardTaxes() {

        let data = {
            _token: "{{ csrf_token() }}",
            user: {{$client->id}}
        }


        $.post("{{route('admin.user.creditcard.taxes.list')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#tbody-installments").html(json.html);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            
        });
    }
    
    function saveInstallmentFee() {

        let data = {
            _token: "{{ csrf_token() }}",
            installment: $("#installments_qtd").val(),
            tax: $("#installments_tax").val(),
            taxp: $("#installments_taxp").val(),
            user: {{$client->id}}
        }

        $("#installments_btn").prop("disabled", true);
        $.post("{{route('admin.user.creditcard.taxes.save')}}", data, function (json) {
            try {
                if (json.success) {
                    toastr.success(json.message);
                    listCardTaxes();
                    $("#installments_qtd").val("");
                    $("#installments_tax").val("");
                    $("#installments_taxp").val("");
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#installments_btn").prop("disabled", false);
        });
    }


    function modalDeleteInstallment(code) {
        var modalDeleteTax = new bootstrap.Modal(document.getElementById('modal-delete-creditcard-tax'), { keyboard: false });
        modalDeleteTax.show();

        $("#modal-delete-creditcard-tax-id").val(code);
        $("#modal-delete-creditcard-tax-cancel, #modal-delete-creditcard-tax-save").prop("disabled", false);
    }


    function deleteInstallmentTax() {

        let data = {
            _token: "{{ csrf_token() }}",
            code: $("#modal-delete-creditcard-tax-id").val()
        }

        $("#modal-delete-creditcard-tax-cancel, #modal-delete-creditcard-tax-save").prop("disabled", true);
        $.post("{{route('admin.user.creditcard.taxes.delete')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-delete-creditcard-tax-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    listCardTaxes();
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-delete-creditcard-tax-cancel, #modal-delete-creditcard-tax-save").prop("disabled", false);
        });
    }

    function activateTale() {

        let data = {
            _token: "{{ csrf_token() }}",
            user: {{$client->id}}
        }

        $("#installments_btn_activate").prop("disabled", true);
        $.post("{{route('admin.user.creditcard.taxes.enable')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#installments_btn_activate").hide();
                    $("#installments_btn_disabled").show();

                    toastr.success(json.message);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#installments_btn_activate").prop("disabled", false);
        });
    }
    
    function disableTable() {

        let data = {
            _token: "{{ csrf_token() }}",
            user: {{$client->id}}
        }

        $("#installments_btn_disabled").prop("disabled", true);
        $.post("{{route('admin.user.creditcard.taxes.disable')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#installments_btn_activate").show();
                    $("#installments_btn_disabled").hide();
                    toastr.success(json.message);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#installments_btn_disabled").prop("disabled", false);
        });
    }



    
    function saveGatewayConfig() {

        let data = {
            _token: "{{ csrf_token() }}",
            gateway_cashin: $("#gateway_cashin").val(),
            gateway_cashout: $("#gateway_cashout").val(),
            gateway_creditcard: $("#gateway_creditcard").val(),
            gateway_boleto: $("#gateway_boleto").val(),
            user: {{$client->id}}
        } 

        $("#btn-gateway-pix").prop("disabled", true);
        $.post("{{route('admin.user.gateway.pix.update')}}", data, function (json) {
            try {
                if (json.success) {
                    
                    toastr.success(json.message);
                    
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#btn-gateway-pix").prop("disabled", false);
        });
    }


    function savePaymentsFormConfig() {

        let data = {
            _token: "{{ csrf_token() }}",
            use_payment_configs: ($("#use_payment_configs").is(":checked") ? 1 : 0),
            enable_account_payment: ($("#enable_account_payment").is(":checked") ? 1 : 0),
            enable_boleto_payment: ($("#enable_boleto_payment").is(":checked") ? 1 : 0),
            enable_creditcard_payment: ($("#enable_creditcard_payment").is(":checked") ? 1 : 0),
            enable_pix_payment: ($("#enable_pix_payment").is(":checked") ? 1 : 0),
            user: {{$client->id}}
        }

        $("#btn-payments-methods-update").prop("disabled", true);
        $.post("{{route('admin.user.payment.forms.update')}}", data, function (json) {
            try {
                if (json.success) {
                    
                    toastr.success(json.message);
                    
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#btn-payments-methods-update").prop("disabled", false);
        });
    }


       
</script>
@endpush