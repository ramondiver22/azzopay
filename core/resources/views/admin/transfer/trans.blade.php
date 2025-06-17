
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0">{{$lang['admin_transfer_trans_transactions']}}</h3>
        </div>
        <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
            <thead>
            <tr>
                <th>{{$lang['admin_transfer_trans_sn']}}</th>
                <th>{{$lang['admin_transfer_trans_name']}}</th>
                <th>{{$lang['admin_transfer_trans_from']}}</th>
                <th>{{$lang['admin_transfer_trans_ip_address']}}</th>
                <th>{{$lang['admin_transfer_trans_status']}}</th>
                <th>{{$lang['admin_transfer_trans_amount']}}</th>
                <th>{{$lang['admin_transfer_trans_charge']}}</th>
                <th>{{$lang['admin_transfer_trans_reference_id']}}</th>
                <th>{{$lang['admin_transfer_trans_payment_type']}}</th>
                <th>{{$lang['admin_transfer_trans_created']}}</th>
                <th>{{$lang['admin_transfer_trans_updated']}}</th>
            </tr>
            </thead>
            
            <tbody>  
            @foreach($links as $k=>$xval)
                <tr>
                <td>{{++$k}}.</td>
                <td>{{$xval->ddlink['name']}}</td>
                <td>@if($xval->sender_id!=null) {{$xval->sender['first_name'].' '.$xval->sender['last_name']}} [{{$xval->sender['email']}}] @else {{$xval->first_name.' '.$xval->last_name}} [{{$xval->email}}] @endif</td>
                <td>{{$xval->ip_address}}</td>
                <td>@if($xval->status==0) <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_trans_failed']}}</span> @elseif($xval->status==1) <span class="badge badge-pill badge-success">{{$lang['admin_transfer_trans_successful']}}</span> @elseif($xval->status==2) {{$lang['admin_transfer_trans_refunded']}} @endif</td>
                <td>{{$currency->symbol.number_format($xval->amount, 2, '.', '')}}</td>
                <td>@if($xval->charge==null) - @else {{$currency->symbol.number_format($xval->charge, 2, '.', '')}} @endif</td>
                <td>{{$xval->ref_id}}</td>
                <td>{{$xval->payment_type}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($xval->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($xval->updated_at))}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

@stop