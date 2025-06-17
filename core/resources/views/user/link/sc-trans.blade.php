
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0 font-weight-bolder">{{$lang["link_transactions"]}}</h3>
        </div>
        <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
            <thead>
            <tr>
                <th>{{$lang["link_s_n"]}}</th>
                <th>{{$lang["link_name"]}}</th>
                <th>{{$lang["link_from"]}}</th>
                <th>{{$lang["link_ip_address"]}}</th>
                <th>{{$lang["link_type"]}}</th>
                <th>{{$lang["link_status"]}}</th>
                <th>{{$lang["link_amount"]}}</th>
                <th>{{$lang["link_charge"]}}</th>
                <th>{{$lang["link_reference_id"]}}</th>
                <th>{{$lang["link_payment_type"]}}</th>
                <th>{{$lang["link_created"]}}</th>
                <th>{{$lang["link_updated"]}}</th>
            </tr>
            </thead>
            <tbody>  
            @foreach($links as $k=>$xval)
                <tr>
                <td>{{++$k}}.</td>
                <td>{{$xval->ddlink['name']}}</td>
                <td>@if($xval->sender_id!=null) {{$xval->sender['first_name'].' '.$xval->sender['last_name']}} [{{$xval->sender['email']}}] @else {{$xval->first_name.' '.$xval->last_name}} [{{$xval->email}}] @endif</td>
                <td>{{$xval->ip_address}}</td>
                <td>@if($xval->sender_id==$user->id) {{$lang["link_paid"]}} @else {{$lang["link_received"]}} @endif</td>
                <td>@if($xval->status==0) <span class="badge badge-pill badge-danger">{{$lang["link_failed"]}}</span> @elseif($xval->status==1) <span class="badge badge-pill badge-success">{{$lang["link_successful"]}}</span> @elseif($xval->status==2) {{$lang["link_refunded"]}} @endif</td>
                <td>@if($xval->sender_id==$user->id) {{$currency->symbol.number_format($xval->amount+$xval->charge, 2, '.', '')}} @else {{$currency->symbol.number_format($xval->amount, 2, '.', '')}} @endif</td>
                <td>@if($xval->sender_id==$user->id || $xval->charge==null) - @else {{$currency->symbol.number_format($xval->charge, 2, '.', '')}} @endif</td>
                <td>{{$xval->ref_id}}</td>
                <td>{{$xval->payment_type}}</td>
                <td>{{date("d/m/Y h:i:s", strtotime($xval->created_at))}}</td>
                <td>{{date("d/m/Y h:i:s", strtotime($xval->updated_at))}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

@stop