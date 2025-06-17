@extends('userlayout')
@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["bill_bill_payment_transactions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["bill_network"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["donation_charge"]}}</th>
              <th>{{$lang["bill_mobile_iuc_meter_no"]}}</th>
              <th>{{$lang["donation_reference"]}}</th>
              <th>{{$lang["donation_date"]}}</th>
            </tr>
          </thead>
          <tbody>  
                <tr>
                    <td>1.</td>
                    <td>{{$val->biller}}</td>
                    <td>{{$currency->symbol.$val->amount}}</td>
                    <td>{{$currency->symbol.$val->charge}}</td>
                    <td>{{$val->track}}</td>
                    <td>{{$val->ref}}</td>
                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td> 
                </tr>
          </tbody>
        </table>
      </div>
    </div>
@stop