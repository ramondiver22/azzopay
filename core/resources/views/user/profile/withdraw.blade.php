@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
              <h6 class="h2 d-inline-block mb-0">{{$lang["profile_withdraw_payout"]}}</h6>
          </div>
          <div class="col-lg-6 col-5 text-right">
              <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral">
                  <i class="fad fa-plus"></i> {{$lang["profile_withdraw_withdraw_request"]}}
              </a>
          </div>
        </div>

      <div class="row">
          <div class="col-md-8">
              <div class="row">  
                  @if(count($withdraw)>0) 
                      @foreach($withdraw as $k=>$val)
                      <div class="col-md-6">
                          <div class="card bg-white">
                              <!-- Card body -->
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-6">
                                          <!-- Title -->
                                          <h5 class="h4 mb-1 font-weight-bolder">{{$val->reference}}</h5>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col">
                                        <p class="text-sm mb-0">Total: {{$currency->symbol.number_format(($val->amount + $val->charge), 2, '.', '')}}</p>
                                        
                                        <p class="text-sm mb-0">Taxas: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</p>
                                        
                                        <p class="text-sm mb-0">Valor a Receber: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                                        
                                        <p class="text-sm mb-2">{{$lang["profile_withdraw_date"]}}: {{date("d/m/Y H:i", strtotime($val->created_at))}}</p>
                                        @if($val->type==2)
                                          <span class="badge badge-pill badge-primary"><i class="fad fa-user"></i> {{$lang["profile_withdraw_sub_account"]}}</span>                        
                                        @elseif($val->type==1)
                                          <span class="badge badge-pill badge-primary"><i class="fad fa-user"></i> {{$lang["profile_withdraw_main"]}} </span>
                                        @endif  
                                        
                                        @if($val->status==1)
                                          <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_withdraw_paid_out"]}}</span>
                                        @elseif($val->status==0)
                                          <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i>  {{$lang["profile_withdraw_pending"]}}</span>                        
                                        @elseif($val->status==2)
                                          <span class="badge badge-pill badge-info"><i class="fad fa-ban"></i> {{$lang["profile_withdraw_declined"]}}</span> <br>
                                          <p class="text-danger m-2">{{$val->error_msg}}</p>
                                        @endif
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div> 
                      @endforeach
                  @else
                      <div class="col-md-12 mb-5">
                          <div class="text-center mt-8">
                              <div class="mb-3">
                                  <img src="{{url('/')}}/asset/images/empty.svg">
                              </div>
                              <h3 class="text-dark">{{$lang["profile_withdraw_no_payout"]}}</h3>
                              <p class="text-dark text-sm card-text">{{$lang["profile_withdraw_we_couldnt_find_any_payouts"]}}</p>
                          </div>
                      </div>
                  @endif
              </div> 



              <div class="row">
                  <div class="col-md-12">
                      {{ $withdraw->links('pagination::bootstrap-4') }}
                  </div>
              </div>
          </div> 

          <div class="col-md-4">
              

              <div class="card">
                  <div class="card-body">
              
                      <div class="row align-items-center">
                          <div class="col text-center">
                              <h4 class="mb-4 text-primary font-weight-bolder">
                                  {{$lang["profile_withdraw_statistics"]}}
                              </h4>

                              <span class="text-sm text-dark mb-0">
                                  <i class="fa fa-google-wallet"></i> Sacado Hoje
                              </span>

                              <br>
                              <span class="text-xl text-dark mb-0">
                                  {{$currency->name}} {{number_format($received, 2, ',', '.')}}
                              </span>
                              <br>
                              <hr>
                          </div>
                      </div>

                      <div class="row align-items-center">
                          <div class="col">
                              <div class="my-4">
                                  <span class="surtitle">Saques Pendentes: </span><br>
                                  <span class="surtitle">Limite de Saque Disponível: </span><br>
                                  <span class="surtitle ">Limite de Saque Total: </span>
                              </div>
                          </div> 
                          <div class="col-auto">
                              <div class="my-4">
                                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, ',', '.')}}</span><br>
                                  <span class="surtitle ">{{$currency->name}} {{number_format($withdrawLimits->available_limit, 2, ',', '.')}}</span><br>
                                  <span class="surtitle ">{{$currency->name}} {{number_format($withdrawLimits->total_limit, 2, ',', '.')}}</span>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
      </div>
  </div>

  
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h3 class="mb-0">{{$lang["profile_withdraw_create_paout_request"]}}</h3>
                </div>

                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        {{$currency->symbol}}
                                    </span>
                                </div>
                                <input type="text"  name="withdraw-amount" id="withdraw-amount" class="form-control money-mask" placeholder="0.00"  required>
                                <input type="hidden" value="{{$userTax->withdraw_charge}}" id="chargetransfer3">
                                <input type="hidden" value="{{$userTax->withdraw_chargep}}" id="chargetransferx">
                            </div>
                          
                            <span class="form-text text-xs">
                                {{$lang["profile_withdraw_wihdraw_charge_is"]}} {{number_format($userTax->withdraw_charge, 2, ",", ".")}}% + {{$currency->symbol . " " . number_format($userTax->withdraw_chargep, 2, ",", ".")}}, 
                                & {{$lang["profile_withdraw_maximum_withdraw_is"]}} {{$currency->symbol . " " . number_format($withdrawLimits->available_limit, 2, ",", ".")}} 
                            </span>
                            <span class="form-text text-xs">
                                Você receberá <strong id="withdrawal-receivable"></strong> 
                            </span>
                        </div>
                    </div>     
          
                    <div class="form-group row withdraw_pix_key" >
                        <div class="col-lg-12">
                            <input type="text" name="pix_key" id="pix_key" class="form-control" placeholder="{{$lang["profile_withdraw_label_pix_key"]}}">
                        </div>
                    </div>


                    <div class="form-group row withdraw_pix_key" >
                        <div class="col-lg-12">
                            <select class="form-control select" name="pix_key_type" id="pix_key_type">
                                <option value=''>{{$lang["profile_withdraw_label_pix_key_type"]}}</option>
                                <option value='CPF'>{{$lang["profile_withdraw_label_pix_key_type_cpf"]}}</option>
                                <option value='CNPJ'>{{$lang["profile_withdraw_label_pix_key_type_cnpj"]}}</option>
                                <option value='PHONE'>{{$lang["profile_withdraw_label_pix_key_type_phone"]}}</option>
                                <option value='EMAIL'>{{$lang["profile_withdraw_label_pix_key_type_email"]}}</option>
                                <option value='EVP'>{{$lang["profile_withdraw_label_pix_key_type_evp"]}}</option>
                            </select>
                        </div>
                    </div> 

                </div>

                <div class="card-footer px-lg-5 py-lg-5 text-right">
                    <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="btn-close-withdraw">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-send-withdraw" onclick="sendWithdraw();">
                        {{$lang["profile_withdraw_request_payout"]}}
                    </button>
                </div>

            </div>
        </div>
    </div>
    
    

@push('scripts')
<script>
        
    let timeoutWitdrawalCalc = null;
    $(document).ready(function () {

        $("#withdraw-amount").keyup(function () {
            if (timeoutWitdrawalCalc != null) {
                clearTimeout(timeoutWitdrawalCalc);
            }

            timeoutWitdrawalCalc = setTimeout(() => {
                calcWithdrawalReceive();
            }, 300);
        });
        
        $("#withdraw-amount").blur(function () {
            if (timeoutWitdrawalCalc != null) {
                clearTimeout(timeoutWitdrawalCalc);
            }

            timeoutWitdrawalCalc = setTimeout(() => {
                calcWithdrawalReceive();
            }, 300);
        });
    });

    function calcWithdrawalReceive() {
        let withdrawalValue = $("#withdraw-amount").val().replaceAll(".", "").replace(",", ".");
        if (withdrawalValue.length > 0) {
            withdrawalValue = parseFloat(withdrawalValue);
        } else {
            withdrawalValue = 0;
        }

        let willReceive = (withdrawalValue - (withdrawalValue * {{number_format($userTax->withdraw_charge, 2, ".", "")}} / 100) - {{number_format($userTax->withdraw_chargep, 2, ".", "")}});
        if (willReceive < 0) {
          willReceive = 0;
        }
        $("#withdrawal-receivable").html("R$ " + willReceive.toFixed(2).replace(","));
    } 

    function sendWithdraw() {
         pix_key  
        let data = {
          _token: "{{csrf_token()}}",
          pix_key: $("#pix_key").val(),
          pix_key_type: $("#pix_key_type").val(),
          withdraw_amount: $("#withdraw-amount").val()
        };
      
        $("#btn-send-withdraw").prop("disabled", true);
        $.post("{{route('user.withdraw.send')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#pix_key").val("");
                    $("#pix_key_type").val("");
                    $("#withdraw-amount").val("");
                    $("#btn-close-withdraw").trigger("click");
                    toastr.success(json.message);

                    setTimeout(() => {
                      location = "{{route('user.withdraw')}}";
                    }, 2000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, 'json').always(function () {
            $("#btn-send-withdraw").prop("disabled", false);
        });
    }

</script>
@endpush
        
@stop
