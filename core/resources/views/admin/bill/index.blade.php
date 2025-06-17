@extends('master')
@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 h3 font-weight-bolder">{{$lang["admin_bill_transactions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["admin_bill_sn"]}}</th>
              <th>{{$lang["admin_bill_username"]}}</th>
              <th>{{$lang["admin_bill_network"]}}</th>
              <th>{{$lang["admin_bill_amount"]}}</th>
              <th>{{$lang["admin_bill_charge"]}}</th>
              <th>{{$lang["admin_bill_recharge_id"]}}</th>
              <th>{{$lang["admin_bill_reference"]}}</th>
              <th>{{$lang["admin_bill_date"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($trans as $k=>$val)
                
                <tr>
                    <td>{{++$k}}.</td>
                    <td><a href="{{url('admin/manage-user')}}/{{$val->user['id']}}">{{$val->user['business_name']}}</a></td>
                    <td>{{$val->biller}}</td>
                    <td>{{$currency->symbol.$val->amount}}</td>
                    <td>{{$currency->symbol.$val->charge}}</td>
                    <td>{{$val->track}}</td>
                    <td>#{{$val->ref}}</td>
                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td> 
                </tr>
                
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
@stop