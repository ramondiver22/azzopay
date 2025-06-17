
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="mb-0 font-weight-bolder">{{$lang["invoice_invoice_logs"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["donation_name"]}}</th>
              <th>{{$lang["donation_from"]}}</th>
              <th>{{$lang["donation_ip_address"]}}</th>
              <th>{{$lang["donation_type"]}}</th>
              <th>{{$lang["donation_status"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["donation_charge"]}}</th>
              <th>{{$lang["donation_reference_id"]}}</th>
              <th>{{$lang["donation_payment_type"]}}</th>
              <th>{{$lang["donation_created"]}}</th>
              <th>{{$lang["donation_updated"]}}</th>
            </tr>
          </thead>
          <tbody>  
              <tr>
                <td>1.</td>
                <td>{{$val->inplan['item']}}</td>
                <td>@if($val->sender_id!=null) {{$val->sender->first_name.' '.$val->sender->last_name}} [{{$val->sender->email}}] @else {{$val->first_name.' '.$val->last_name}} [{{$val->email}}] @endif</td>
                <td>{{$val->ip_address}}</td>
                <td>@if($val->sender_id==$user->id) {{$lang["invoice_paid"]}} @else {{$lang["donation_received"]}} @endif</td>
                <td>@if($val->status==0) <span class="badge badge-pill badge-danger">{{$lang["donation_failed"]}}</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">{{$lang["donation_successful"]}}</span> @elseif($val->status==2) {{$lang["donation_refunded"]}} @endif</td>
                <td>{{$currency->symbol.$val->amount}}</td>
                <td>@if($val->sender_id==$user->id || $val->charge==null) - @else {{$currency->symbol.$val->charge}} @endif</td>
                <td>{{$val->ref_id}}</td>
                <td>{{$val->payment_type}} @if($val->payment_type=='card') - XXXX XXXX XXXX {{substr($val->card_number, 12)}} @endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

@stop