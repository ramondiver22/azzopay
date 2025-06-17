@extends('master')
    @section('content')
    <div class="container-fluid mt--6">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-header header-elements-inline">
                            <h3 class="mb-0">{{$lang["admin_settings_configure_website"]}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.settings.update')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_website_name"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="site_name" maxlength="200" value="{{$set->site_name}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_company_email"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="email" name="email" value="{{$set->email}}" class="form-control" required>
                                    </div>
                                </div>                                
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_support_email"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="email" name="support_email" value="{{$set->support_email}}" class="form-control" required>
                                    </div>
                                </div>
                                
                                      
                                                            
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_company_name"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="company_name" maxlength="255" value="{{$set->company_name}}" class="form-control" required>
                                    </div>
                                </div>                         
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_company_documents"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="company_document" maxlength="18" value="{{$set->company_document}}" class="form-control" required>
                                    </div>
                                </div>                            
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_company_address"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="company_address" maxlength="255" value="{{$set->company_address}}" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_mobile"]}}</label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <input type="text" name="mobile" max-length="14" value="{{$set->mobile}}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_website_title"]}}</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="title" max-length="200" value="{{$set->title}}" class="form-control" required>
                                    </div>
                                </div>                                  
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_short_description"]}}</label>
                                    <div class="col-lg-10">
                                        <textarea type="text" name="site_desc" rows="4" class="form-control" required>{{$set->site_desc}}</textarea>
                                    </div>
                                </div>                                
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_welcome_message"]}}</label>
                                    <div class="col-lg-10">
                                        <textarea type="text" name="welcome_message" rows="7" class="form-control" required>{{$set->welcome_message}}</textarea>
                                    </div>
                                </div>                           
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_livechat_code"]}}</label>
                                    <div class="col-lg-10">
                                        <textarea type="text" name="livechat" class="form-control">{{$set->livechat}}</textarea>
                                    </div>
                                </div>           
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_settings_save_changes"]}}</button>
                                    </div>
                            </form>
                        </div>
                    </div>    

                    


                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Habilitação de Formas de Pagamento</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                
                                <div class="col-lg-3">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        
                                        <input type="checkbox" name="enable_account_payment" id="enable_account_payment" @if($set->enable_account_payment > 0) checked  @endif class="custom-control-input" value="1" >
                                        
                                        <label class="custom-control-label" for="enable_account_payment">
                                            <span class="text-muted">Pagamento com sado da conta</span>     
                                        </label>
                                    </div> 
                                </div>
                                

                                <div class="col-lg-3">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        
                                        <input type="checkbox" name="enable_boleto_payment" id="enable_boleto_payment" @if($set->enable_boleto_payment > 0) checked  @endif class="custom-control-input" value="1" >
                                        
                                        <label class="custom-control-label" for="enable_boleto_payment">
                                            <span class="text-muted">Pagamento com Boleto</span>     
                                        </label>
                                    </div> 
                                </div>


                                <div class="col-lg-3">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        
                                        <input type="checkbox" name="enable_creditcard_payment" id="enable_creditcard_payment" @if($set->enable_creditcard_payment > 0) checked  @endif class="custom-control-input" value="1" >
                                        
                                        <label class="custom-control-label" for="enable_creditcard_payment">
                                            <span class="text-muted">Pagamento com Cartão de Crédito</span>     
                                        </label>
                                    </div> 
                                </div>

                                <div class="col-lg-3">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        
                                        <input type="checkbox" name="enable_pix_payment" id="enable_pix_payment" @if($set->enable_pix_payment > 0) checked  @endif class="custom-control-input" value="1" >
                                        
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


                    <div class="card">

                        <div class="row">
                            <div class="col col-lg-6 col-md-6 col-xs-12">
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
                                                        @foreach($gatewaysBoleto as $gateway) {
                                                        <option value="{{$gateway->id}}" {{($gatewayBoleto->gateway_id == $gateway->id ? 'selected' : '')}}>{{$gateway->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>      
                                            </div>    
                                        </div>
                                    </div>
                                
                                    
                                </div>
                            </div>


                            <div class="col col-lg-6 col-md-6 col-xs-12">
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
                                                        @foreach($gatewaysCreditcard as $gateway) {
                                                        <option value="{{$gateway->id}}" {{($gatewayCreditcard->gateway_id == $gateway->id ? 'selected' : '')}}>{{$gateway->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>      
                                            </div>    
                                        </div>
                                    </div>
                                
                                    
                                </div>
                            </div>
                        </div>


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
                                                @foreach($gatewaysIn as $gateway) {
                                                <option value="{{$gateway->id}}" {{($gatewayCashIn->gateway_id == $gateway->id ? 'selected' : '')}}>{{$gateway->name}}</option>
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
                                                @foreach($gatewaysOut as $gateway) {
                                                <option value="{{$gateway->id}}" {{($gatewayCashOut->gateway_id == $gateway->id ? 'selected' : '')}} >{{$gateway->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>      
                                    </div>    
                                </div>
                            </div>
                        
                             
                        </div>
                        
                        <div class="card-footer">
   
                            <div class="text-right">
                                <button type="button" class="btn btn-success btn-sm" id="btn-gateway-pix" onclick="saveGatewayConfig();">Salvar Configurações de Gateway</button>
                            </div>
                        </div>
                    </div>    




                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_settings_settlement"]}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.settlement.update')}}" method="post">
                                
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_withdraw_charge"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <input type="text" name="withdraw_charge" value="{{number_format($set->withdraw_charge, 2, ',', '.')}}" class="form-control money-mask" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>      
                                </div>    
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_withdraw_charge_p"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <input type="text" name="withdraw_chargep" value="{{number_format($set->withdraw_chargep, 2, ',', '.')}}" class="form-control money-mask" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">R$</span>
                                            </span>
                                        </div>
                                    </div>      
                                </div>                                 
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_withdraw_limit"]}} (Unverified) <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="text" name="withdraw_limit" value="{{number_format($set->withdraw_limit, 2, ',', '.')}}" class="form-control money-mask" required>
                                        </div>
                                        <span class="text-gray text-xs">Perfis não verificados</span>
                                    </div>      
                                </div>                                
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_withdraw_limit"]}} (Personal) <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="text" name="starter_limit" value="{{number_format($set->starter_limit, 2, ',', '.')}}" class="form-control money-mask" required>
                                        </div>
                                        <span class="text-gray text-xs">Perfis com verificação pessoal</span>
                                    </div>      
                                </div>                                  
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_withdraw_limit"]}} (Business) <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="text" name="business_limit" value="{{number_format($set->business_limit, 2, ',', '.')}}" class="form-control money-mask" required>
                                        </div>
                                        <span class="text-gray text-xs">Perfis com verficação Business</span>
                                    </div>      
                                </div>      


                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Valor máximo para aprovação automática de saques:<span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="text" name="max_automatic_withdraw_value" value="{{number_format($set->max_automatic_withdraw_value, 2, ',', '.')}}" class="form-control money-mask" required>
                                        </div>
                                        <span class="text-gray text-xs">Saques acima desse valor ficarão pendentes e deverão ser feitos manualmente.</span>
                                    </div>      
                                </div>    


                                
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_settings_save"]}}</button>
                                </div>
                            </form>
                        </div>
                    </div>    
                    <!--                      
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{__('Cryptocurrency')}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.crypto.update')}}" method="post">
                                @csrf                    
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{__('Btc wallet address')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">#</span>
                                            </span>
                                            <input type="text" name="btc_wallet" value="{{$set->btc_wallet}}" class="form-control">
                                        </div>
                                    </div> 
                                </div> 
                                <div class="form-group row">                                                                                  
                                    <label class="col-form-label col-lg-2">{{__('Eth wallet address')}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">#</span>
                                            </span>
                                            <input type="text" name="eth_wallet" value="{{$set->eth_wallet}}" class="form-control">
                                        </div>
                                    </div>           
                                </div> 
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{__('Btc sell rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="btc_sell" step="any" max-length="10" value="{{convertFloat($set->btc_sell)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Btc buy rate ')}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="btc_buy" step="any" max-length="10" value="{{convertFloat($set->btc_buy)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Eth sell rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="eth_sell" step="any" max-length="10" value="{{convertFloat($set->eth_sell)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Eth buy rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="eth_buy" step="any" max-length="10" value="{{convertFloat($set->eth_buy)}}" class="form-control">
                                        </div>
                                    </div>                                                                                                                                                                     
                                    <label class="col-form-label col-lg-2">{{__('Minimum bitcoin sell rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="min_btcsell" step="any" max-length="10" value="{{convertFloat($set->min_btcsell)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Minimum bitcoin buy rate ')}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="min_btcbuy" step="any" max-length="10" value="{{convertFloat($set->min_btcbuy)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Minimum ethereum sell rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="min_ethsell" step="any" max-length="10" value="{{convertFloat($set->min_ethsell)}}" class="form-control">
                                        </div>
                                    </div>                                                                                   
                                    <label class="col-form-label col-lg-2">{{__('Minimum ethereum buy rate')}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="min_ethbuy" step="any" max-length="10" value="{{convertFloat($set->min_ethbuy)}}" class="form-control">
                                        </div>
                                    </div>   
                                </div>        
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success btn-sm">{{__('Save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div> 
                    -->                  
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_settings_features"]}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.features.update')}}" method="post">
                                @csrf   
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->email_verification==1)
                                                <input type="checkbox" name="email_activation" id="customCheckLogin2" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="email_activation"id="customCheckLogin2"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin2">
                                            <span class="text-muted">{{$lang["admin_settings_email_verification"]}}</span>     
                                            </label>
                                        </div>                       
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->email_notify==1)
                                                <input type="checkbox" name="email_notify" id="customCheckLogin3" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="email_notify"id="customCheckLogin3"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin3">
                                            <span class="text-muted">{{$lang["admin_settings_email_notify"]}}</span>     
                                            </label>
                                        </div>  
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->registration==1)
                                                <input type="checkbox" name="registration" id="customCheckLogin4" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="registration"id="customCheckLogin4"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin4">
                                            <span class="text-muted">{{$lang["admin_settings_registration"]}}</span>     
                                            </label>
                                        </div>  

                                        <?php /*                             
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->subscription==1)
                                                <input type="checkbox" name="subscription" id="customCheckLogin13" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="subscription"id="customCheckLogin13"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin13">
                                            <span class="text-muted">{{$lang["admin_settings_subscription"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->stripe_connect==1)
                                                <input type="checkbox" name="stripe_connect" id="customCheckLogin130" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="stripe_connect" id="customCheckLogin130"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin130">
                                            <span class="text-muted">{{$lang["admin_settings_stripe_connect"]}}</span>     
                                            </label>
                                        </div>   
                                        */ ?>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->kyc_restriction==1)
                                                <input type="checkbox" name="kyc_restriction" id="customCheckLogin117" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="kyc_restriction" id="customCheckLogin117"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin117">
                                            <span class="text-muted">{{$lang["admin_settings_compliance_restriction"]}}</span>     
                                            </label>
                                        </div>                                                                                                                                                                                        
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->recaptcha==1)
                                                <input type="checkbox" name="recaptcha" id="customCheckLogin6" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="recaptcha"id="customCheckLogin6"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin6">
                                            <span class="text-muted">{{$lang["admin_settings_recaptcha"]}}</span>     
                                            </label>
                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->merchant==1)
                                                <input type="checkbox" name="merchant" id="customCheckLogin7" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="merchant" id="customCheckLogin7"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin7">
                                            <span class="text-muted">{{$lang["admin_settings_merchant"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->transfer==1)
                                                <input type="checkbox" name="transfer" id="customCheckLogin8" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="transfer" id="customCheckLogin8"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin8">
                                            <span class="text-muted">{{$lang["admin_settings_transfer"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->request_money==1)
                                                <input type="checkbox" name="request_money" id="customCheckLogin9" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="request_money" id="customCheckLogin9"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin9">
                                            <span class="text-muted">{{$lang["admin_settings_request_money"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->country_restriction==1)
                                                <input type="checkbox" name="country_restriction" id="customCheckLogin459" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="country_restriction" id="customCheckLogin459"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin459">
                                            <span class="text-muted">{{$lang["admin_settings_country_restrinction"]}}</span>     
                                            </label>
                                        </div>
                                        <!--
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->bitcoin==1)
                                                <input type="checkbox" name="bitcoin" id="customCheckLogin22" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="bitcoin" id="customCheckLogin22"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin22">
                                            <span class="text-muted">{{__('Bitcoin')}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->ethereum==1)
                                                <input type="checkbox" name="ethereum" id="customCheckLogin23" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="ethereum" id="customCheckLogin23"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin23">
                                            <span class="text-muted">{{__('Ethereum')}}</span>     
                                            </label>
                                        </div>
                                        -->
                                    </div>                                    
                                    <div class="col-lg-4">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->invoice==1)
                                                <input type="checkbox" name="invoice" id="customCheckLogin10" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="invoice" id="customCheckLogin10"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin10">
                                            <span class="text-muted">{{$lang["admin_settings_invoice"]}}</span>     
                                            </label>
                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->store==1)
                                                <input type="checkbox" name="store" id="customCheckLogin10z" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="store" id="customCheckLogin10z"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin10z">
                                            <span class="text-muted">{{$lang["admin_settings_store"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->donation==1)
                                                <input type="checkbox" name="donation" id="customCheckLogin11" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="donation" id="customCheckLogin11"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin11">
                                            <span class="text-muted">{{$lang["admin_settings_donation"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->single==1)
                                                <input type="checkbox" name="single" id="customCheckLogin12" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="single" id="customCheckLogin12"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin12">
                                            <span class="text-muted">{{$lang["admin_settings_single_charge"]}}</span>     
                                            </label>
                                        </div>                                        
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->bill==1)
                                                <input type="checkbox" name="bill" id="customCheckLogin20" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="bill" id="customCheckLogin20"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin20">
                                            <span class="text-muted">{{$lang["admin_settings_bill_payment"]}}</span>     
                                            </label>
                                        </div>    
                                        
                                        <?php /*
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            @if($set->vcard==1)
                                                <input type="checkbox" name="vcard" id="customCheckLogin21" class="custom-control-input" value="1" checked>
                                            @else
                                                <input type="checkbox" name="vcard" id="customCheckLogin21"  class="custom-control-input" value="1">
                                            @endif
                                            <label class="custom-control-label" for="customCheckLogin21">
                                            <span class="text-muted">{{$lang["admin_settings_virtual_card"]}}</span>     
                                            </label>
                                        </div>    
                                        
                                        */ ?>
                                    </div>
                                </div>         
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_settings_save_changes"]}}</button>
                                </div>
                            </form>
                        </div>
                    </div>                      
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_settings_charges"]}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.charges.update')}}" method="post">
                                @csrf
                                <p>{{$lang["admin_settings_config_fees_transfer_request"]}}</p>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any"  name="transfer_charge" value="{{$set->transfer_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>                                    
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any"  name="transfer_chargep" value="{{$set->transfer_chargep}}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <p>{{$lang["admin_settings_config_fees_merchant"]}}</p>
                                <div class="form-group row">                                                                                                                                                                                                                       
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number"step="any"  name="merchant_charge" value="{{$set->merchant_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div> 
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="merchant_chargep" value="{{$set->merchant_chargep}}" class="form-control" required>
                                        </div>
                                    </div>
                                </div> 
                                <p>{{$lang["admin_settings_config_fees_invoice"]}}</p>
                                <div class="form-group row">                               
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any"  name="invoice_charge" value="{{$set->invoice_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="invoice_chargep" value="{{$set->invoice_chargep}}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <p>{{$lang["admin_settings_config_fees_product"]}}</p>
                                <div class="form-group row">                             
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any" name="product_charge" max-length="10" value="{{$set->product_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div> 
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="product_chargep" value="{{$set->product_chargep}}" class="form-control" required>
                                        </div>
                                    </div> 
                                </div>
                                <p>{{$lang["admin_settings_config_fees_single_charge"]}}</p>
                                <div class="form-group row">                                   
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any"  name="single_charge" max-length="10" value="{{$set->single_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div> 
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="single_chargep" value="{{$set->single_chargep}}" class="form-control" required>
                                        </div>
                                    </div> 
                                </div>
                                <p>{{$lang["admin_settings_config_fees_donation"]}}</p>
                                <div class="form-group row">                                    
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number"step="any"  name="donation_charge" max-length="10" value="{{$set->donation_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div> 
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="donation_chargep" value="{{$set->donation_chargep}}" class="form-control" required>
                                        </div>
                                    </div>  
                                </div>

                                <?php /* 
                                <p>Subscription</p>
                                <div class="form-group row">                                     
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any" name="subscription_charge" max-length="10" value="{{$set->subscription_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>  
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}} <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="subscription_chargep" value="{{$set->subscription_chargep}}" class="form-control" required>
                                        </div>
                                    </div> 
                                </div>
                                <p>Virtual Card Creation Charge</p>
                                <div class="form-group row">   
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any" name="virtual_createcharge" value="{{$set->virtual_createcharge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="virtual_createchargep" value="{{$set->virtual_createchargep}}" class="form-control" required>
                                        </div>
                                    </div>  
                                </div> 
                                <p>Virtual Card Charge</p>
                                <div class="form-group row">                                     
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_percent"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any" name="virtual_charge" value="{{$set->virtual_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="virtual_chargep" value="{{$set->virtual_chargep}}" class="form-control" required>
                                        </div>
                                    </div>  
                                </div> 
                                */ ?>

                                <p>{{$lang["admin_settings_config_fees_bill_payment"]}}</p>
                                <div class="form-group row">  
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_bill"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="number" step="any" name="bill_charge" value="{{$set->bill_charge}}" class="form-control" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-form-label col-lg-3">{{$lang["admin_settings_fiat"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" step="any" name="bill_chargep" value="{{$set->bill_chargep}}" class="form-control" required>
                                        </div>
                                    </div>  
                                </div> 
                                <hr>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_balance_on_signup"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="bal" value="{{$set->balance_reg}}" class="form-control" required>
                                        </div>
                                    </div>                                    
                                    <label class="col-form-label col-lg-2">{{$lang["admin_settings_minimum_transfer"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="min_transfer" value="{{$set->min_transfer}}" class="form-control" required>
                                        </div>
                                    </div>                                                                                                                                                                                                                                                                                                                               
                                </div>  

                                
                                <hr>
                                <div class="form-group row">  
                                    <label class="col-form-label col-lg-6">Quantidade de dias para liberação dos valores de cartão de crédito.<span class="text-danger">*</span></label>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <input type="number" name="days_creditcard_liquidation" value="{{$set->days_creditcard_liquidation}}" class="form-control numeric-input" required>
                                            <span class="input-group-append">
                                                <span class="input-group-text">Dias</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>  

                                <?php /*                                
                                <hr>
                                <div class="form-group row">                                                                        
                                    <label class="col-form-label col-lg-4">{{$lang["admin_settings_vc_minimum"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="vc_min" value="{{$set->vc_min}}" class="form-control" required>
                                        </div>
                                    </div>                                     
                                    <label class="col-form-label col-lg-4">{{$lang["admin_settings_vc_maximum"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">{{$currency->symbol}}</span>
                                            </span>
                                            <input type="number" name="vc_max" value="{{$set->vc_max}}" class="form-control" required>
                                        </div>
                                    </div>                                     
                                    <label class="col-form-label col-lg-4">{{$lang["admin_settings_vc_debit_currency"]}}<span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control select" name="debit_currency" required>    
                                            <option value="BRL" @if($set->debit_currency=='BRL'){{__('selected')}}@endif>{{__('BRL')}}</option> 
                                            <option value="NGN" @if($set->debit_currency=='NGN'){{__('selected')}}@endif>{{__('NGN')}}</option>                                                                               
                                            <option value="USD" @if($set->debit_currency=='USD'){{__('selected')}}@endif>{{__('USD')}}</option>                                                                               
                                            <option value="GNF" @if($set->debit_currency=='GNF'){{__('selected')}}@endif>{{__('GNF')}}</option>                                                                               
                                            <option value="KES" @if($set->debit_currency=='KES'){{__('selected')}}@endif>{{__('KES')}}</option>                                                                               
                                            <option value="LRD" @if($set->debit_currency=='LRD'){{__('selected')}}@endif>{{__('LRD')}}</option>                                                                               
                                            <option value="MWK" @if($set->debit_currency=='MWK'){{__('selected')}}@endif>{{__('MWK')}}</option>                                                                               
                                            <option value="MZN" @if($set->debit_currency=='MZN'){{__('selected')}}@endif>{{__('MZN')}}</option>                                                                               
                                            <option value="RWF" @if($set->debit_currency=='RWF'){{__('selected')}}@endif>{{__('RWF')}}</option>                                                                               
                                            <option value="SLL" @if($set->debit_currency=='SLL'){{__('selected')}}@endif>{{__('SLL')}}</option>                                                                               
                                            <option value="BIF" @if($set->debit_currency=='BIF'){{__('selected')}}@endif>{{__('BIF')}}</option>                                                                               
                                            <option value="CAD" @if($set->debit_currency=='CAD'){{__('selected')}}@endif>{{__('CAD')}}</option>                                                                               
                                            <option value="CDF" @if($set->debit_currency=='CDF'){{__('selected')}}@endif>{{__('CDF')}}</option>                                                                               
                                            <option value="CVE" @if($set->debit_currency=='CVE'){{__('selected')}}@endif>{{__('CVE')}}</option>                                                                               
                                            <option value="EUR" @if($set->debit_currency=='EUR'){{__('selected')}}@endif>{{__('EUR')}}</option>                                                                               
                                            <option value="GBP" @if($set->debit_currency=='GBP'){{__('selected')}}@endif>{{__('GBP')}}</option>                                                                               
                                            <option value="GHS" @if($set->debit_currency=='GHS'){{__('selected')}}@endif>{{__('GHS')}}</option>                                                                               
                                            <option value="GMD" @if($set->debit_currency=='GMD'){{__('selected')}}@endif>{{__('GMD')}}</option>                                                                               
                                            <option value="STD" @if($set->debit_currency=='STD'){{__('selected')}}@endif>{{__('STD')}}</option>                                                                               
                                            <option value="TZS" @if($set->debit_currency=='TZS'){{__('selected')}}@endif>{{__('TZS')}}</option>                                                                               
                                            <option value="UGX" @if($set->debit_currency=='UGX'){{__('selected')}}@endif>{{__('UGX')}}</option>                                                                               
                                            <option value="XAF" @if($set->debit_currency=='XAF'){{__('selected')}}@endif>{{__('XAF')}}</option>                                                                               
                                            <option value="XOF" @if($set->debit_currency=='XOF'){{__('selected')}}@endif>{{__('XOF')}}</option>                                                                               
                                            <option value="ZMK" @if($set->debit_currency=='ZMK'){{__('selected')}}@endif>{{__('ZMK')}}</option>                                                                               
                                            <option value="ZMW" @if($set->debit_currency=='ZMW'){{__('selected')}}@endif>{{__('ZMW')}}</option>                                                                               
                                            <option value="ZWD" @if($set->debit_currency=='ZWD'){{__('selected')}}@endif>{{__('ZWD')}}</option>                                                                               
                                        </select>
                                    </div>                                                                                                                                                                                                                                                     
                                </div>                          
                                */ ?>                   
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_settings_save_changes"]}}</button>
                                    </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Comissões para Patrocinadores</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.comissions.update')}}" method="post">
                                @csrf
                                
                                <div class="row">
                                    <div class="col col-md-6">
                                        <div class="form-group m-2">
                                            <label class="form-label">
                                                Comissão sobre taxa de saque
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" name="withdraw-comission" id="withdraw-comission" value="{{number_format($set->withdraw_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                                                <input type="text" name="deposit-comission" id="deposit-comission" value="{{number_format($set->deposit_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                                                <input type="text" name="invoice-comission" id="invoice-comission" value="{{number_format($set->invoice_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                                                <input type="text" name="payment-link-comission" id="payment-link-comission" value="{{number_format($set->payment_link_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                                                <input type="text" name="donation-comission" id="donation-comission" value="{{number_format($set->donation_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                                                <input type="text" name="store-comission" id="store-comission" value="{{number_format($set->store_comission, 2, '.', '')}}" class="form-control money-mask" required>
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
                            </form>
                        </div>
                    </div>


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


                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_settings_security"]}}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.account.update')}}" method="post">
                                @csrf
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">{{$lang["admin_settings_username"]}}</label>
                                        <div class="col-lg-10">
                                            <input type="text" name="username" value="{{$val->username}}" class="form-control">
                                        </div>
                                    </div>                         
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-2">{{$lang["admin_settings_password"]}}</label>
                                        <div class="col-lg-10">
                                            <input type="password" name="password"  class="form-control" required>
                                        </div>
                                    </div>          
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_settings_save"]}}</button>
                                </div>
                            </form>
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

    function updateComissionTaxes() {

        let data = {
            _token: "{{ csrf_token() }}",
            withdraw_comission: $("#withdraw-comission").val(),
            deposit_comission: $("#deposit-comission").val(),
            invoice_comission: $("#invoice-comission").val(),
            payment_link_comission: $("#payment-link-comission").val(),
            donation_comission: $("#donation-comission").val(),
            store_comission: $("#store-comission").val()
        }

        $("#btn-save-comissions").prop("disabled", true);
        $.post("{{route('admin.comissions.update')}}", data, function (json) {
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
            _token: "{{ csrf_token() }}"
        }

        
        $.post("{{route('admin.creditcard.taxes.list')}}", data, function (json) {
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
            taxp: $("#installments_taxp").val()
        }

        $("#installments_btn").prop("disabled", true);
        $.post("{{route('admin.creditcard.taxes.save')}}", data, function (json) {
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
        $.post("{{route('admin.creditcard.taxes.delete')}}", data, function (json) {
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

    
    function saveGatewayConfig() {

        let data = {
            _token: "{{ csrf_token() }}",
            gateway_cashin: $("#gateway_cashin").val(),
            gateway_cashout: $("#gateway_cashout").val(),
            gateway_creditcard: $("#gateway_creditcard").val(),
            gateway_boleto: $("#gateway_boleto").val()
        } 

        $("#btn-gateway-pix").prop("disabled", true);
        $.post("{{route('admin.gateway.pix.update')}}", data, function (json) {
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
            enable_account_payment: ($("#enable_account_payment").is(":checked") ? 1 : 0),
            enable_boleto_payment: ($("#enable_boleto_payment").is(":checked") ? 1 : 0),
            enable_creditcard_payment: ($("#enable_creditcard_payment").is(":checked") ? 1 : 0),
            enable_pix_payment: ($("#enable_pix_payment").is(":checked") ? 1 : 0)
        }

        $("#btn-payments-methods-update").prop("disabled", true);
        $.post("{{route('admin.payment.forms.update')}}", data, function (json) {
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