@extends('userlayout')
@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["bill_data_bundle"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#single-charge" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["bill_buy_data"]}}</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="single-charge" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["bill_data_bundle"]}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
              <div class="modal-body">
                <form action="{{route('user.submit-bill')}}" method="post" id="modal-details">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="biller" id="biller" required>
                                <option value="">{{$lang["bill_data_plan"]}}</option>
                                @foreach($log['data'] as $k=>$v)
                                    @if($v['biller_code']=='BIL108' || $v['biller_code']=='BIL109' || $v['biller_code']=='BIL110' || $v['biller_code']=='BIL111' || $v['biller_code']=='BIL124')
                                        <option value="{{$v['name']}}">{{$v['biller_name']}} - {{$v['amount']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">+234</span>
                                </span>
                                <input type="text" pattern="\d*" class="form-control" name="track" placeholder="{{$lang["bill_phone_number"]}}" minlength="11" required>
                                <input type="hidden" value="2" name="type">
                                <input type="hidden" value="{{$userTax->bill_charge}}" id="chargetransfer">
                                <input type="hidden" name="amount" id="real">
                            </div>
                            <span class="form-text text-xs">{{$lang["bill_transaction_charge_is"]}} {{$userTax->bill_charge}}% {{$lang["bill_per_transaction"]}}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["bill_pay"]}} <span id="resulttransfer"></span></button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["bill_transactions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["bill_s_n"]}}</th>
              <th>{{$lang["bill_network"]}}</th>
              <th>{{$lang["bill_amount"]}}</th>
              <th>{{$lang["bill_charge"]}}</th>
              <th>{{$lang["bill_mobile"]}}</th>
              <th>{{$lang["bill_reference"]}}</th>
              <th>{{$lang["bill_date"]}}</th>
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