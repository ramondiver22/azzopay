
@extends('paymentlayout')

@section('content')
<div class="container-fluid mt--6">
      <div class="content-wrapper">
            <div class="header py-7 py-lg-6 pt-lg-1">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="container mt--8 pb-5 mb-0">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-7">
                        <div class="accordion" id="accordionExample">
                            <div class="card bg-white border-0 mb-0">

                                @if($userTax->enable_pix_payment  > 0)
                                <div class="card-header" id="headingPayWithPix">
                                  <div class="text-left" data-toggle="collapse" data-target="#collapsePayWithPix" aria-expanded="true" aria-controls="collapsePayWithPix">
                                    <h4 class="mb-0 font-weight-bolder">{{$lang["fund_pix"]}}</h4>
                                  </div>
                                </div>
              
                                <div id="collapsePayWithPix" class="collapse show" aria-labelledby="headingPayWithPix" data-parent="#accordionExample">
                                    <div class="card-body">
                                      <div class="row">                           
                                        <div class="col-12">
                                          <form action="{{ route('recharge.pix')}}" method="post" id="payment-form-pix">
                                                <input type="hidden" value="pix" id="type" name="type"> 


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


                                              @csrf
                                              <div class="form-group row data-pix-form">
                                                  <div class="col-md-12">
                                                      <div class="input-group">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text">{{$currency->symbol}}</span>
                                                          </div>
                                                          <input type="number" step="any" class="form-control" name="amount" id="pixamount" placeholder="0.00" autocomplete="off" required> 
                                                          
                                                      </div>
                                                  </div>
                                              </div>  

                                              <div id="card-errors" role="alert" class="data-pix-form"></div> 
                                              <div class="text-center mt-5 data-pix-form" id="pix-btn-container">
                                                  <button type="button" class="btn btn-neutral btn-block" onclick="payFundWithPix();" id="btn-pay-with-pix">{{$lang["fund_pay_with_pix"]}} <span id="pixresult"></span></button>
                                              </div>

                                              <div class="row justify-content-center" id="pix-container-payment-data"  style="display: none;">
                                                  <div class="col col-md-6 col-sm-6 col-lg-6 col-xs-12">

                                                  <br>
                                                    <div class="row mt-20">
                                                        <div class="col col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
                                                            {{$lang["fund_pay_with_pix_instrunctions"]}}
                                                        </div>
                                                    </div>
                                                    
                                                  <br><br>

                                                  <div class="row">
                                                      <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                          <ul class="list-group">
                                                              <li class="list-group-item text-center">
                                                                  Valor da recarga: <strong id="pix-amount"></strong>
                                                              </li>
                                                              <li class="list-group-item text-center" >
                                                                  Valor da taxa:  <strong id="pix-charge"></strong>
                                                              </li>
                                                              <li class="list-group-item text-center">
                                                                  Valor total:  <strong id="pix-total"></strong>
                                                              </li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                                    <br><br>
                                                    <div class="row mt-20">
                                                        <div class="col col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
                                                            <img src="" width='200' id="pix-img-qrcode"/>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group-row mt-20">
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

                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>  
                                  <hr>
                                  @endif



                                @if($userTax->enable_creditcard_payment  > 0)
                                  <div class="card-header" id="headingOne">
                                    <div class="text-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                      <h4 class="mb-0 font-weight-bolder">{{$lang["fund_card"]}}</h4>
                                    </div>
                                  </div>
                                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                      <div class="row">                           
                                        <div class="col-12">
                                          <form action="{{ route('card')}}" method="post" id="payment-form-creditcard">
                                                <input type="hidden" value="creditcard" id="type" name="type"> 
                                              @csrf
                                              <div class="form-group row">
                                                  <div class="col-md-12">
                                                      <div class="input-group">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text">{{$currency->symbol}}</span>
                                                          </div>
                                                          <input type="number" step="any" class="form-control" name="amount" id="cardamount" placeholder="0.00" autocomplete="off" required> 
                                                          
                                                      </div>
                                                  </div>
                                              </div>  
                                              
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


                                                <br><br>
                                                <div class="text-center mt-5  payment-form-data">
                                                    <button type="button" class="btn btn-neutral btn-block my-4" id="btn-pay-credit-card" onclick="payInvoiceWithCreditCard();"  > {{$lang["fund_pay"]}}</button>
                                                </div>


                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>  
                                  <hr> 
                                  @endif

                                  @if($userTax->enable_boleto_payment  > 0)
                                  <div class="card-header" id="headingTwo">
                                    <div class="text-left collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="fadse" aria-controls="collapseTwo">
                                      <h4 class="mb-0 font-weight-bolder">{{$lang["fund_boleto"]}}</h4>
                                    </div>
                                  </div>
                                  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body text-center">
                                      
                                      <form method="post" action="{{route('recharge.boleto')}}" id="payment-form-boleto">
                                        <input type="hidden" value="boleto" id="type" name="type">
                                          @csrf
                                          

                                          <div class="form-group row  pay-with-boleto-data">
                                              <div class="col-lg-8 offset-lg-2">
                                                  <div class="input-group">
                                                      <span class="input-group-prepend">
                                                          <span class="input-group-text">{{$currency->symbol}}</span>
                                                      </span>
                                                      <input type="number" step="any" name="amount" max-length="10" class="form-control" required>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="text-center pay-with-boleto-data">
                                              <button type="button" onclick="payInvoiceWithBoleto();" id="btn-pay-with-boleto" class="btn btn-neutral btn-block">{{$lang["fund_pay_with_boleto"]}}</button>
                                          </div>

                                          <div  class="row pay-with-boleto-info" style="display:none;">

                                              <div class="col col-xs-12 col-md-12 col-lg-12 col-sm-12">

                                                  <br><br>
                                                  <div class="row">
                                                      <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                                          <h4>{{$lang["fund_pay_with_boleto_instructions"]}}</h4>
                                                      </div>
                                                  </div>

                                                  <br><br>

                                                  <div class="row">
                                                      <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                          <ul class="list-group">
                                                              <li class="list-group-item">
                                                                  Valor da recarga: <strong id="boleto-amount"></strong>
                                                              </li>
                                                              <li class="list-group-item" >
                                                                  Valor da taxa:  <strong id="boleto-charge"></strong>
                                                              </li>
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
                                      </form>
                                    </div>
                                  </div>      
                                  <hr>  
                                  @endif

                                  <div class="row">
                                      <div class="col col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center">

                                          <br><br>
                                          <a href="{{route('user.dashboard')}}"  class="btn btn-info">
                                              Voltar para a Dashboard
                                          </a>
                                          <br><br>
                                      </div>
                                  </div>

                                </div>
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
    });

  function payFundWithPix() {
        let ajaxcointableform = JSON.stringify($('#payment-form-pix').serializeArray());
        let formdata  = $.parseJSON(ajaxcointableform);
      
        $("#btn-pay-with-pix").prop("disabled", true);
        $.post("{{ route('fund.proccess.payment')}}", formdata, function (json) {
            try {
                if (json.success) {
                    reference = json.reference;
                    $("#pix-amount").html(json.recharge);
                    $("#pix-charge").html(json.charge);
                    $("#pix-total").html("R$ " + json.total);
                    
                    $(".data-pix-form").hide();
                    $("#pix-container-payment-data").show();
                    $("#pix-img-qrcode").attr("src", json.qrcode);
                    $("#pix-copypaste").val(json.copy);
                        
                } else {
                    toastr.error(json.message);
                }
                
            } catch (e) {
                toastr.error(e);
            }
        }, 'json').always(() => {
            $("#btn-pay-with-pix").prop("disabled", false);
        });
  }


  function payInvoiceWithCreditCard() {
      let ajaxcointableform = JSON.stringify($('#payment-form-creditcard').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-credit-card").prop("disabled", true);
      $.post("{{ route('fund.proccess.payment')}}", formdata, function (json) {
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
          $("#btn-pay-credit-card").prop("disabled", false);
      });
  }
    

   
    

  function payInvoiceWithBoleto() {
      let ajaxcointableform = JSON.stringify($('#payment-form-boleto').serializeArray());
      let formdata  = $.parseJSON(ajaxcointableform);
      

      $("#btn-pay-with-boleto").prop("disabled", true);
      $.post("{{ route('fund.proccess.payment')}}", formdata, function (json) {
          try {
              if (json.success) {
                reference = json.reference;
                $(".pay-with-boleto-data").hide();
                $(".pay-with-boleto-info").show();
                JsBarcode('#codBarras', json.barcode);                         
                $("#btn-print-boleto").attr("href", json.boleto);                     
                                     
                $("#boleto-amount").html(json.recharge);
                $("#boleto-charge").html(json.charge);
                $("#boleto-total").html(json.total);

              } else {
                  toastr.error(json.message);
              }
              
          } catch (e) {
              toastr.error(e);
          }
      }, 'json').always(function () {
          $("#btn-pay-with-boleto").prop("disabled", false);
      });
  }

  var reference = null;
  function checkPayment() {
      let data = {
        reference: reference
      };
      $.post("{{ route('fund.verify.payment')}}", data, function (json) {
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