@extends('userlayout')
@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$_data["bill_eletricity"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#single-charge" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$_data["bill_pay_light_bill"]}}</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="single-charge" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-body p-0">
                <div class="card bg-white border-0 mb-0">
                  <div class="card-header">
                    <h3 class="mb-0 font-weight-bolder">{{$_data["bill_eletricity"]}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="form-text text-xs">{{$_data["bill_transaction_charge_is"]}} {{$userTax->bill_charge}}% {{$_data["bill_per_transaction"]}}</span>
                  </div>
                  <div class="card-body">
                    <form action="{{route('user.submit-bill')}}" method="post" id="modal-details">
                      @csrf
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="input-group">
                              <span class="input-group-prepend">
                                <span class="input-group-text">{{$currency->symbol}}</span>
                              </span>
                              <input type="number" step="any" class="form-control" name="amount" id="amounttransfer" placeholder="0.00" onkeyup="transfercharge()" required>
                              <input type="hidden" value="{{$userTax->bill_charge}}" id="chargetransfer">
                              <input type="hidden" value="4" name="type">
                            </div>
                          </div>
                        </div> 
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-control select" name="biller" required>
                                    <option value="">{{$_data["bill_service"]}}</option>
                                    @foreach($log['data'] as $k=>$v)
                                        @if($v['biller_code']=='BIL112' || $v['biller_code']=='BIL113' || $v['biller_code']=='BIL114' || $v['biller_code']=='BIL115' || $v['biller_code']=='BIL116' || $v['biller_code']=='BIL117' || $v['biller_code']=='BIL118' || $v['biller_code']=='BIL120')
                                            <option value="{{$v['biller_name']}}">{{$v['short_name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-12">{{$_data["bill_meter_number"]}}</label>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </span>
                                    <input type="number" class="form-control" name="track" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$_data["bill_pay"]}} <span id="resulttransfer"></span></button>
                        </div>         
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$_data["bill_transactions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$_data["bill_s_n"]}}</th>
              <th>{{$_data["bill_network"]}}</th>
              <th>{{$_data["bill_amount"]}}</th>
              <th>{{$_data["bill_charge"]}}</th>
              <th>{{$_data["bill_mobile"]}}</th>
              <th>{{$_data["bill_reference"]}}</th>
              <th>{{$_data["bill_date"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($trans as $k=>$val)
                
                <tr>
                    <td>{{++$k}}.</td>
                    <td>{{$val->biller}}</td>
                    <td>{{$currency->symbol.$val->amount}}</td>
                    <td>{{$currency->symbol.$val->charge}}</td>
                    <td>{{$val->track}}</td>
                    <td>{{$val->ref}}</td>
                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td> 
                </tr>
                
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
@stop