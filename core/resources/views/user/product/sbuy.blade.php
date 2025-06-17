@extends('paymentlayout')

@section('content')

<div class="main-content">
    <div class="header py-7 py-lg-6 pt-lg-1">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <div class="card-profile-image mb-5">
                            <img src="{{url('/')}}/asset/profile/{{$merchant->image}}" class="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mt--8 pb-5 mb-0">
        <div class="row justify-content-center">     
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <span class="form-text text-xl">{{$currency->symbol}} {{number_format($total)}}.00</span>
                                <span class="form-text text-sm text-default">{{$product->name}} by {{$merchant->business_name}}</span>
                            </div>
                        </div>
                        <form action="{{route('check.product')}}" method="post" id="payment-form">
                            <input type="hidden" value="{{Session::get('pay-type')}}" name="type" id="type">
                            @csrf
                            <input type="hidden" id="amount3" value="{{$total}}" name="amount">
                            <input type="hidden" name="product_id" value="{{$product->uniqueid}}">
                                
                            @if (!Auth::guard('user')->check())
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="first_name" class="form-control" placeholder="{{$lang["product_sbuy_first_name"]}}" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="text" name="last_name" class="form-control" placeholder="{{$lang["product_sbuy_last_name"]}}" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div>                         
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="email" name="email" class="form-control" placeholder="{{$lang["product_sbuy_your_email_address"]}}" required>
                                            </div>
                                        </div>                 
                                    </div>    
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <input type="number" inputmode="numeric" name="phone" class="form-control" placeholder="{{$lang["product_sbuy_mobile_number"]}}" required>
                                            </div>
                                        </div>                 
                                    </div>  
                                </div> 
                            @endif

                            @if($store->shipping_status==1)

                                @if($store->note_status!=0)
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <textarea type="text" name="note" class="form-control" rows="" placeholder="{{$lang["product_sbuy_delivery_note"]}} @if($store->note_status==1)({{$lang["product_sbuy_opational"]}}) @endif" @if($store->note_status==2)required="" @endif></textarea>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group row">                           
                                    <div class="col-lg-6">
                                        <select class="form-control custom-select" name="country" id="country" required>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <select class="form-control custom-select" name="state" id="state" required>
                                        </select>
                                    </div>
                                </div>     
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <input type="text" name="address" class="form-control" placeholder="{{$lang["product_sbuy_your_address"]}}" required>
                                    </div> 
                                    <div class="col-lg-6">
                                        <input type="text" name="town" class="form-control" placeholder="{{$lang["product_sbuy_town_city"]}}" required>
                                    </div>
                                </div> 
                                <div class="form-group row">    
                                    <label class="col-form-label col-lg-12">{{$lang["product_sbuy_shipping_fee"]}}</label>                       
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="shipping" id="ship_fee" required>
                                            @foreach($ship as $fval)
                                            <option value="{{$fval->id}}-{{number_format($fval->amount, 2, '.', '')}}">
                                                {{$fval->region}} {{$currency->symbol. number_format($fval->amount, 2, '.', '')}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                <input type="hidden" id="xship" name="xship"> 
                                <input type="hidden" id="xship_fee" name="shipping_fee"> 
                                <input type="hidden" id="inputGrandTotal" name="inputGrandTotal">
                                
                                <hr>

                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <span class="text-sm text-default">{{$lang["product_sbuy_subtotal"]}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{$currency->symbol}}<span id="subtotal3">{{number_format($subtotal, 2, '.', '') }}</span></span>
                                    </div>
                                </div>

                                <hr>  
                                <input type="hidden" id="qtdProducts" name="qtdProducts" value="<?php echo $products?>"/>
                                @if($store->shipping_status==1)                
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <span class="text-sm text-default">{{$lang["product_sbuy_shipping"]}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{$lang["product_sbuy_flat_rate"]}}: {{$currency->symbol}}<span id="flat"></span></span>
                                    </div>
                                </div>
                                <hr>
                                @endif


                                <div class="row justify-content-between align-items-center mb-5">
                                    <div class="col">
                                        <span class="text-sm text-default">{{$lang["product_sbuy_total"]}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-sm text-default">{{$currency->symbol}}<span id="total3">{{number_format($total, 2, '.', '')}}</span></span>
                                    </div>
                                </div>   

                            @endif


                            @if(Session::get('pay-type')=='account')
                                @if (Auth::guard('user')->check())
                                    <div class="text-center"> 
                                        <h4 class="mb-0">{{$lang["product_sbuy_account_balance"]}}</h4>
                                        <h1 class="mb-1 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance, 2, ',', '.')}}</h1>
                                        

                                        <div class="text-center">
                                            <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-order-account" onclick="payOrderWithAccount();" >
                                                <i class="fad fa-external-link"></i> {{$lang["product_sbuy_pay"]}}
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    @php Session::put('oldLink', url()->current()); @endphp
                                    <h3 class="mb-3 text-muted font-weight-bolder">{{$lang["product_sbuy_login_to_make_payment"]}}</h3>
                                    <a href="{{route('login')}}" class="btn btn-neutral btn-block">{{$lang["product_sbuy_login"]}}</a>
                                @endif


                            @elseif(Session::get('pay-type')=='card') 
                                
                                <div class="row">
                                    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="creditcard_holder">Nome conforme o cartão</label>
                                            <input type="text" id="creditcard_holder" name="creditcard_holder" class="form-control" autocomplete="off"  required/>
                                        </div>
                                    </div>
                                    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="creditcard_document">CPF do titular</label>
                                            <input type="text" id="creditcard_document" name="creditcard_document" class="form-control" autocomplete="off"  required/>
                                        </div>
                                    </div>
                                </div>

                                    
                                <div class="row">
                                    <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="creditcard_brand">Bandeira do Cartão</label>
                                            <select id="creditcard_brand" name="creditcard_brand" class="form-control">
                                                <option value="Visa">Visa</option>
                                                <option value="Master">Master Card</option>
                                                <option value="Elo">Elo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col col-lg-6 col-md-6 col-sm-5 col-xs-12">
                                        <div class="form-group">
                                            <label for="creditcard_number">Número do Cartão</label>
                                            <input type="text" id="creditcard_number" name="creditcard_number" class="form-control" autocomplete="off"  required/>
                                        </div>
                                    </div>
                                    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                        <div class="form-group">
                                            <label for="creditcard_month">Mês</label>
                                            <select id="creditcard_month" name="creditcard_month"  class="form-control" required>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-lg-2 col-md-2 col-sm-2 col-xs-4">

                                        <div class="form-group">
                                            <label for="creditcard_year">Ano</label>
                                            <select id="creditcard_year" name="creditcard_year"  class="form-control" required>
                                            @for ($i = intval(date('Y')); $i < intval(date('Y')) + 20; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                        <div class="form-group">
                                            <label for="creditcard_cvv">CVV</label>
                                            <input type="number" id="creditcard_cvv" name="creditcard_cvv" maxlength="3" class="form-control" autocomplete="off"  required/>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="creditcard_installments">
                                                <div class="custom-spinner-loader m1" id="installment-loader" style="display: none;"></div>
                                                Quantidade de Parcelas
                                            </label>
                                            <select id="creditcard_installments" name="creditcard_installments"  class="form-control" required>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="text-center mt-5  payment-form-data">
                                    <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-order-creditcard" onclick="payOrderWithCreditCard();"  ><i class="fad fa-external-link"></i> {{$lang["product_sbuy_pay"]}}</button>
                                </div>



                            @elseif(Session::get('pay-type')=='boleto') 


                                @if (!Auth::guard('user')->check()) 
                                    <div class="row boleto-customer-data">
                                        <div class="col col-md-8 col-lg-8 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="boleto_name">Nome Completo</label>
                                                <input type="text" id="boleto_name" name="boleto_name" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-4 col-lg-4 col-sm-6 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_document">Documento</label>
                                                <input type="text" id="boleto_document" name="boleto_document" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row boleto-customer-data">
                                        <div class="col col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="boleto_email">Email</label>
                                                <input type="text" id="boleto_email" name="boleto_email" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-4 col-lg-4 col-sm-12 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_telefone">Telefone</label>
                                                <input type="text" id="boleto_telefone" name="boleto_telefone" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-4 col-lg-4 col-sm-12 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_celular">Celular</label>
                                                <input type="text" id="boleto_celular" name="boleto_celular" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row boleto-customer-data">
                                        <div class="col col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="boleto_zip">Código Postal</label>
                                                <div class="input-group input-group-alternative input-group-merge">
                                                    <input class="form-control"  id="boleto_zip" name="boleto_zip" type="text" autocomplete="off"  required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"  onclick="searchPostalCode();" style="cursor: pointer;"><i class="fad fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        
                                    </div>

                                    <div class="row boleto-customer-data">
                                        <div class="col col-md-8 col-lg-8 col-sm-8 col-xs-12">
                                            <div class="form-group">
                                                <label for="boleto_address">Endereço</label>
                                                <input type="text" id="boleto_address" name="boleto_address" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-4 col-lg-4 col-sm-4 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_number">Número</label>
                                                <input type="text" id="boleto_number" name="boleto_number" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row boleto-customer-data">
                                        <div class="col col-md-4 col-lg-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="boleto_neighborhood">Bairro</label>
                                                <input type="text" id="boleto_neighborhood" name="boleto_neighborhood" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-5 col-lg-5 col-sm-5 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_city">Cidade</label>
                                                <input type="text" id="boleto_city" name="boleto_city" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                        <div class="col col-md-3 col-lg-3 col-sm-3 col-xs-12 ">
                                            <div class="form-group">
                                                <label for="boleto_state">Estado</label>
                                                <input type="text" id="boleto_state" name="boleto_state" class="form-control" autocomplete="off"  required/>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                                <br><br>
                                <div class="text-center mt-5  payment-form-data boleto-customer-data">
                                    <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-order-boleto" onclick="payOrderWithBoleto();"  ><i class="fad fa-external-link"></i> Gerar Boleto </button>
                                </div>

                                <div  class="row pay-with-boleto-info" style="display: none;">

                                    <div class="col col-xs-12 col-md-12 col-lg-12 col-sm-12">

                                        <br><br>
                                        <div class="row">
                                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                <h4>{{$lang["product_sask_pay_with_boleto_instructions"]}}</h4>
                                            </div>
                                        </div>

                                        <br><br>

                                        <div class="row">
                                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        Valor total:  <strong id="boleto-total"></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center " >
                                            <div class="col col-xs-12 col-lg-12 col-md-12 col-sm-12 text-center">
                                                <svg id="codBarras"></svg>
                                                
                                                <br><br>
                                                <a class="btn btn-info btn-block" href="" id="btn-print-boleto" target="_BLANK"> 
                                                    <i class="fa fa-barcode"></i> Imprimir Boleto
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            @elseif(Session::get('pay-type')=='pix') 

                                <div class="row justify-content-center" id="pix-container-payment-data">
                                    <div class="col col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                    <br>

                            
                                        <div class="row">
                                            <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="pix_client_name">Informe o seu nome</label>
                                                    <input type="text" id="pix_client_name" name="pix_client_name" class="form-control" autocomplete="off"  required/>
                                                </div>
                                            </div>
                                            <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="pix_client_document">CPF do titular</label>
                                                    <input type="text" id="pix_client_document" name="pix_client_document" class="form-control" autocomplete="off"  required/>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                            <div class="text-center mt-5  payment-form-data">
                                                <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-order-pix" onclick="payOrderWithPix();"  ><i class="fad fa-external-link"></i> Gerar Cobrança Pix </button>
                                            </div>
                                        <br>
                                            <div class="row mt-20 row-pix-with-pix" style="display: none;">
                                                <div class="col col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
                                                    {{$lang["product_sask_pay_with_pix_instrunctions"]}}
                                                </div>
                                            </div>

                                            
                                        <br><br>

                                        <div class="row row-pix-with-pix" style="display: none;">
                                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item text-center" style="text-align: center;">
                                                        Valor total:  <strong id="pix-total"></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="row mt-20 row-pix-with-pix" style="display: none;">
                                            <div class="col col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
                                                <img src="" width='200' id="pix-img-qrcode"/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group-row mt-20 row-pix-with-pix" style="display: none;">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <input type="text" step="any" class="form-control"  id="pix-copypaste" readonly> 
                                                    
                                                    <div class="input-group-append" onclick="copyToClipboard('pix-copypaste');">
                                                        <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="text-center">
                    @if($merchant->facebook!=null)
                        <a href="{{$merchant->facebook}}"><i class="sn fab fa-facebook"></i></a>   
                    @endif 
                    @if($merchant->twitter!=null)                      
                        <a href="{{$merchant->twitter}}"><i class="sn fab fa-twitter"></i></a>
                    @endif      
                    @if($merchant->linkedin!=null)                     
                        <a href="{{$merchant->linkedin}}"><i class="sn fab fa-linkedin"></i></a> 
                    @endif     
                    @if($merchant->instagram!=null)                        
                        <a href="{{$merchant->instagram}}"><i class="sn fab fa-instagram"></i></a>   
                    @endif 
                    @if($merchant->youtube!=null)                          
                        <a href="{{$merchant->youtube}}"><i class="sn fab fa-youtube"></i></a>  
                    @endif                          
                </div>
            </div>
        </div>
    </div>
@stop



@push('scripts')
<script>

    $(document).ready(function () {
        
        setInterval(function () {
            checkPayment();
        }, 10000);
        
    });

    function recalcInstallment() {
        
    }

  function payOrderWithAccount() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-order-account").prop("disabled", true);
      $.post("{{ route('checkout.proccess.payment')}}", formdata, function (json) {
          try {
              if (json.success) {
                  toastr.success(json.message);

                  if (json.redirect != null) {
                      setTimeout(() => {
                          location = json.redirect;
                      }, 2000);
                  }
              } else {
                  toastr.error(json.message);
              }
              
          } catch (e) {
              toastr.error(e);
          }
      }, 'json').always(function () {
          $("#btn-pay-order-account").prop("disabled", false);
      });
  }


  function payOrderWithCreditCard() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-order-creditcard").prop("disabled", true);
      $.post("{{ route('checkout.proccess.payment')}}", formdata, function (json) {
          try {
              if (json.success) {
                  toastr.success(json.message);

                  if (json.redirect != null) {
                      setTimeout(() => {
                          location = json.redirect;
                      }, 2000);
                  }
              } else {
                  toastr.error(json.message);
              }
              
          } catch (e) {
              toastr.error(e);
          }
      }, 'json').always(function () {
          $("#btn-pay-order-creditcard").prop("disabled", false);
      });
  }


  function payOrderWithBoleto() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-print-boleto, #btn-pay-order-boleto").prop("disabled", true);
      $.post("{{ route('checkout.proccess.payment')}}", formdata, function (json) {
          try {
              if (json.success) {
                reference = json.reference;
                
                $(".boleto-customer-data").hide();
                $(".pay-with-boleto-info").show();
                
                JsBarcode('#codBarras', json.barcode);                         
                $("#btn-print-boleto").attr("href", json.boleto); 
                $("#boleto-total").html(json.total);

              } else {
                  toastr.error(json.message);
              }
              
          } catch (e) {
              toastr.error(e);
          }
      }, 'json').always(function () {
          $("#btn-print-boleto, #btn-pay-order-boleto").prop("disabled", false);
      });
  }



  function payOrderWithPix() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      
      $("#btn-pay-order-pix").prop("disabled", true);
      $.post("{{ route('checkout.proccess.payment')}}", formdata, function (json) {
          try {
              if (json.success) {
                reference = json.reference;
                  
                $(".row-pix-with-pix").show();
                $("#pix-total").html("R$ " + json.total);
                $("#pix-img-qrcode").attr("src", json.qrcode);
                $("#pix-copypaste").val(json.copy);
                    
              } else {
                  toastr.error(json.message);
              }
              
          } catch (e) {
              toastr.error(e);
          }
      }, 'json').always(function () {
        $("#btn-pay-order-pix").prop("disabled", false);
      });
  }

       
                   

  function searchPostalCode() {
    
    let cep = $("#boleto_zip").val().replace("-", "");
    
    $.get("<?php echo url("api/cep/search")?>/"+cep, {}, function (json) {
        
        try {
            
            if (json.success) {
                $("#boleto_address").val(json.address.logradouro);
                $("#boleto_number").val("");
                $("#boleto_neighborhood").val(json.address.bairro);
                $("#boleto_city").val(json.address.localidade);
                $("#boleto_state").val(json.address.uf);
            } else {
                console.log(json.message);
            }
        } catch (e) {
            console.log(e);
        }
        
    }, "json");

}


var reference = null;
  function checkPayment() {
      let data = {
        _token: "{{ csrf_token() }}",
        reference: reference
      };
      $.post("{{ route('checkout.verify.payment')}}", data, function (json) {
            try {
                if (json.paid) {
                    toastr.success(json.message);

                    
                  if (json.redirect != null) {
                      setTimeout(() => {
                          location = json.redirect;
                      }, 2000);
                  }
                }
            } catch (e) {

            }
      }, 'json').always(function () {

      });
  }

</script>
@endpush
