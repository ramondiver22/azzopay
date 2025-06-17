
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="mb-0 font-weight-bolder">{{$lang["merchant_transactions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["merchant_s_n"]}}</th>
              <th>{{$lang["merchant_name"]}}</th>
              <th>{{$lang["merchant_from"]}}</th>
              <th>{{$lang["merchant_ip_address"]}}</th>
              <th>{{$lang["merchant_type"]}}</th>
              <th>{{$lang["merchant_status"]}}</th>
              <th>{{$lang["merchant_quantity"]}}</th>
              <th>{{$lang["merchant_amount"]}}</th>
              <th>{{$lang["merchant_total"]}}</th>
              <th>{{$lang["merchant_charge"]}}</th>
              <th>{{$lang["merchant_reference_id"]}}</th>
              <th>{{$lang["merchant_payment_type"]}}</th>
              <th>{{$lang["merchant_created"]}}</th>
              <th>{{$lang["merchant_updated"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($log as $k=>$val)
            @php
                $fff=App\Models\Merchant::wheremerchant_key($val->merchant_key)->first();
            @endphp
              <tr>
                <td>{{++$k}}.</td>
                <td>{{$fff->name}}</td>
                <td>@if($val->user_id!=null) {{$val->sender['first_name'].' '.$val->sender['last_name']}} [{{$val->sender['email']}}] @else {{$val->first_name.' '.$val->last_name}} [{{$val->email}}] @endif</td>
                <td>{{$val->ip_address}}</td>
                <td>@if($val->sender_id==$user->id) {{$lang["merchant_debit"]}} @else {{$lang["merchant_credit"]}} @endif</td>
                <td>@if($val->status==0) <span class="badge badge-pill badge-danger">{{$lang["merchant_failed"]}}</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">{{$lang["merchant_successful"]}}</span> @elseif($val->status==2) {{$lang["merchant_refunded"]}} @endif</td>
                <td>{{$val->quantity}}</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>{{$currency->symbol.number_format($val->total, 2, '.', '')}}</td>
                <td>@if($val->user_id==$user->id || $val->charge==null) - @else {{$currency->symbol.number_format($val->charge, 2, '.', '')}} @endif</td>
                <td>{{$val->reference}}</td>
                <td>{{$val->payment_type}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop