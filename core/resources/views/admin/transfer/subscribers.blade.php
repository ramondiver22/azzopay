
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{$plan->name}} - #{{$plan->ref_id}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang['admin_transfer_orders_subscribers_sn']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_amount']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_charge']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_subscriber']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_reference_id']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_expiring_date']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_renewal']}}</th>
              <th>{{$lang['admin_transfer_orders_subscribers_created']}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($sub as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                <td>{{$val->user['first_name'].' '.$val->user['last_name']}}</td>
                <td>#{{$val->ref_id}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->expiring_date))}}</td>
                <td>@if($val->times>0 && $val->status==1) {{$lang['admin_transfer_orders_subscribers_yes']}} @else {{$lang['admin_transfer_orders_subscribers_no']}} @endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop