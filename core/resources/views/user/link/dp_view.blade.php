@extends('paymentlayout')

@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-6 pt-lg-1">
        <div class="container">
            <div class="header-body text-center mb-7">

            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
        <div class="row justify-content-center">
            <div class="col-md-7 col-xs-12">
                <div class="card">
                    <img class="card-img-top" src="{{url('/')}}/asset/profile/{{$link->image}}" alt="Image placeholder">
                    <div class="card-body">
                        <h5 class="h3 font-weight-bolder mb-0">{{$lang["link_fundraiser_for"]}} {{$link->name}}</h5>
                        <small class="text-muted">{{$lang["link_by"]}} {{$merchant->business_name}} {{$lang["link_on"]}} {{date("h:i:A j, M Y", strtotime($link->created_at))}}</small>
                        <p class="mb-3 text-sm">{{$link->description}}</p>
                    
                        <form action="{{route('send.donation')}}" method="post" id="payment-form">
                            <input type="hidden" value="{{Session::get('pay-type')}}" name="type" id="type"> 
                            @csrf
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" step="any" class="form-control" name="amount" id="amount" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <select class="form-control select" name="status" required>
                                        <option value="">{{$lang["link_donate_as"]}}</option>
                                        <option value="1">{{$lang["link_anonymous"]}}</option>
                                        <option value="0">{{$lang["link_display_name"]}}</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="{{$link->ref_id}}" name="link"> 
                            @if($donated<$link->amount) 

                                @if(Session::get('pay-type')=='account')   
                                    <div class="text-center">
                                    @if (Auth::guard('user')->check())
                                        @csrf
                                            <h4 class="mb-1">{{$lang["link_account_balance"]}}</h4>
                                            <h1 class="mb-3 text-muted font-weight-bolder">{{$currency->symbol.number_format($user->balance)}}</h1>
                                            
                                            
                                            <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-link-account" onclick="payLinkWithAccount();" >
                                                <i class="fad fa-external-link"></i> {{$lang["link_pay_now"]}}
                                            </button>
                                            
                                    @else
                                        @php Session::put('oldLink', url()->current()); @endphp
                                        <h3 class="mb-3 text-muted font-weight-bolder">{{$lang["link_login_to_make_payment"]}}</h3>
                                        <a href="{{route('login')}}" class="btn btn-neutral btn-block my-4"><i class="fad fa-sign-in"></i> {{$lang["link_login"]}}</a>
                                    @endif 
                                    </div>
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
                                        <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-link-creditcard" onclick="payLinkWithCreditCard();"  ><i class="fad fa-external-link"></i> {{$lang["link_pay"]}}</button>
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
                                    <div class="text-center mt-5  payment-form-data">
                                        <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-link-boleto" onclick="payLinkWithBoleto();"  ><i class="fad fa-external-link"></i> Gerar Boleto </button>
                                    </div>

                                    <div  class="row pay-with-boleto-info" style="display: none;">

                                        <div class="col col-xs-12 col-md-12 col-lg-12 col-sm-12">

                                            <br><br>
                                            <div class="row">
                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                    <h4>{{$lang["link_pay_with_boleto_instructions"]}}</h4>
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
                                                <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-link-pix" onclick="payInvoiceWithPix();"  ><i class="fad fa-external-link"></i> Gerar Cobrança Pix </button>
                                            </div>
                                            <br>
                                            <div class="row mt-20 row-pix-with-pix" style="display: none;">
                                                <div class="col col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
                                                    {{$lang["link_pay_with_pix_instrunctions"]}}
                                                </div>
                                            </div>

                                            <br><br>

                                            <div class="row row-pix-with-pix" style="display: none;">
                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <ul class="list-group">
                                                        <li class="list-group-item text-center">
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
                            @endif   
                        </form>
                        <div class="text-center mb-3">
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

            <div class="col-md-5 col-xs-12">
                <div class="card card-profile bg-white border-0 mb-5">
                    <div class="card-body">
                        <div class="row justify-content-between align-items-center mb-3">
                            <div class="col-12">
                                <span class="form-text text-xl font-weight-bolder">{{$currency->symbol}} {{number_format($link->amount, 2, '.', '')}} {{$lang["link_goal"]}} </span>
                            </div>
                        </div>                
                        <div class="row justify-content-between align-items-center mb-3">
                            <div class="col">
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{($donated*100)/$link->amount}}%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between align-items-center mb-5">
                            <div class="col-12">
                                <h4 class="h3 font-weight-bolder mb-0">{{$currency->symbol}} {{number_format($donated, 2, '.', '')}} {{$lang["link_raised_donations"]}} ({{count($dd)}})</h4>
                            </div>
                        </div>  
                        <ul class="list-group list-group-flush list my--3">
                            @foreach($paid as $k=>$val)
                                <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon icon-shape text-success rounded-circle bg-white">
                                            <i class="fad fa-gift"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                    <h4 class="mb-0">
                                        @if($val->anonymous==0) 
                                            @if($val->user_id==null)
                                                @php
                                                    $fff=App\Models\Transactions::whereref_id($val->ref_id)->first();
                                                @endphp
                                                {{$fff['first_name'].' '.$fff['last_name']}}
                                            @endif
                                            {{$val->user['first_name'].' '.$val->user['last_name']}} 
                                        @else 
                                            {{$lang["link_anonymous"]}} 
                                        @endif
                                    </h4>
                                    <small>{{$currency->symbol.number_format($val->amount, 2, '.', '')}} @ {{date("h:i:A j, M Y", strtotime($val->created_at))}}</small>
                                    </div>
                                </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row mt-5">
                            <div class="col-md-12">
                            {{ $paid->links('pagination::bootstrap-4') }}
                            </div>
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
        
        setInterval(function () {
            checkPayment();
        }, 10000);


        $("#amount").blur(function () { 
            calcInstallments("amount", {{$link->user_id}}, "creditcard_installments");
        });
    });


  function payLinkWithAccount() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-link-account").prop("disabled", true);
      $.post("{{ route('donation.proccess.payment')}}", formdata, function (json) {
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
          $("#btn-pay-link-account").prop("disabled", false);
      });
  }


  function payLinkWithCreditCard() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-link-creditcard").prop("disabled", true);
      $.post("{{ route('donation.proccess.payment')}}", formdata, function (json) {
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
          $("#btn-pay-link-creditcard").prop("disabled", false);
      });
  }


  function payLinkWithBoleto() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-print-boleto, #btn-pay-invoice-boleto").prop("disabled", true);
      $.post("{{ route('donation.proccess.payment')}}", formdata, function (json) {
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
          $("#btn-print-boleto, #btn-pay-invoice-boleto").prop("disabled", false);
      });
  }



  function payInvoiceWithPix() {
      let ajaxcointableform = JSON.stringify($('#payment-form').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      
      $("#btn-pay-link-pix").prop("disabled", true);
      $.post("{{ route('donation.proccess.payment')}}", formdata, function (json) {
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
        $("#btn-pay-link-pix").prop("disabled", false);
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
      $.post("{{ route('donation.verify.payment')}}", data, function (json) {
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