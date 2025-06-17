@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["transfer_mobile_mobile_money"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fal fa-plus"></i> {{$lang["transfer_mobile_send_money"]}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 h4 font-weight-bolder">{{$lang["transfer_mobile_transfer_money"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.transfer')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="biller" required>
                                <option value="">{{$lang["transfer_mobile_type"]}}</option>
                                <option value="GHS">{{$lang["transfer_mobile_ghana_mobile_money"]}}</option>
                                <option value="RWF">{{$lang["transfer_mobile_rwanda_mobile_money"]}}</option>
                                <option value="UGX">{{$lang["transfer_mobile_uganda_mobile_money"]}}</option>
                                <option value="ZMW">{{$lang["transfer_mobile_zambia_mobile_money"]}}</option>
                            </select>
                            <span class="form-text text-xs">{{$lang["transfer_mobile_transfer_charge_is"]}} {{$userTax->transfer_charge}}% {{$lang["transfer_mobile_per_transaction"]}}</span>
                        </div>
                    </div>
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{$lang["transfer_mobile_amount"]}}</label>
                    <div class="col-lg-12">
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">{{$currency->symbol}}</span>
                        </span>
                        <input type="number" class="form-control" name="amount" id="amounttransfer" min="{{$set->min_transfer}}"  onkeyup="transfercharge()" required>
                        <input type="hidden" value="{{$userTax->transfer_charge}}" id="chargetransfer">
                        <span class="input-group-append">
                          <span class="input-group-text">.00</span>
                        </span>
                      </div>
                    </div>
                  </div>                   
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["transfer_mobile_transfer"]}} <span id="resulttransfer"></span></button>
                  </div>         
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          @if(count($transfer)>0)  
            @foreach($transfer as $k=>$val)
              <div class="col-md-6">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <!-- Title -->
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->ref_id}}</h5>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col">
                          @if($val->receiver['id']==$user->id)
                          <p>{{$lang["transfer_mobile_received"]}}: {{$currency->symbol.number_format($val->amount)}}</p>
                          <p>{{$lang["transfer_mobile_from"]}}: {{$val->sender['email']}}</p>
                          @elseif($val->sender['id']==$user->id)
                          <p>{{$lang["transfer_mobile_sent"]}}: {{$currency->symbol.number_format($val->amount)}}</p>
                            @if($val->receiver['id']==null)
                            <p>{{$lang["transfer_mobile_to"]}}: {{$val->temp}}</p>
                            @else
                            <p>{{$lang["transfer_mobile_to"]}}: {{$val->receiver['email']}}</p>
                            @endif
                          @endif
                          <p class="text-sm mb-2">{{$lang["transfer_mobile_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                          @if($val->sender['id']==$user->id) 
                          <span class="badge badge-pill badge-primary">{{$lang["transfer_mobile_charge"]}}: {{$currency->symbol.number_format($val->charge)}} </span>
                          @endif
                          @if($val->status==1)
                            <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{$lang["transfer_mobile_confirmed"]}}</span>
                          @elseif($val->status==0)
                            <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i> {{$lang["transfer_mobile_pending"]}}</span>                        
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-info"><i class="fa fa-check"></i> {{$lang["transfer_mobile_returned"]}}</span>
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
                <h3 class="text-dark">{{$lang["transfer_mobile_no_transfer_request"]}}</h3>
                <p class="text-dark text-sm card-text">{{$lang["transfer_mobile_we_couldnt_find_any_mobile_money"]}}</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $transfer->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
@stop