@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang["admin_merchant_logs_logs"]}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                <th>{{$lang["admin_merchant_logs_sn"]}}</th>
                                <th>{{$lang["admin_merchant_logs_name"]}}</th>
                                <th>{{$lang["admin_merchant_logs_from"]}}</th>
                                <th>{{$lang["admin_merchant_logs_ip_address"]}}</th>
                                <th>{{$lang["admin_merchant_logs_type"]}}</th>
                                <th>{{$lang["admin_merchant_logs_status"]}}</th>
                                <th>{{$lang["admin_merchant_logs_amount"]}}</th>
                                <th>{{$lang["admin_merchant_logs_charge"]}}</th>
                                <th>{{$lang["admin_merchant_logs_reference_id"]}}</th>
                                <th>{{$lang["admin_merchant_logs_payment_type"]}}</th>
                                <th>{{$lang["admin_merchant_logs_created"]}}</th>
                                <th>{{$lang["admin_merchant_logs_updated"]}}</th>
                                </tr>
                            </thead>
                            <tbody>  
                                @foreach($transfer as $k=>$val)
                                @php
                                    $fff=App\Models\Merchant::wheremerchant_key($val->merchant_key)->first();
                                @endphp
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$fff->name}}</td>
                                    <td>@if($val->user_id!=null) {{$val->sender['first_name'].' '.$val->sender['last_name']}} [{{$val->sender['email']}}] @else {{$val->first_name.' '.$val->last_name}} [{{$val->email}}] @endif</td>
                                    <td>{{$val->ip_address}}</td>
                                    <td>@if($val->sender_id==$user->id) {{$lang["admin_merchant_logs_paid"]}} @else {{$lang["admin_merchant_logs_received"]}} @endif</td>
                                    <td>@if($val->status==0) <span class="badge badge-pill badge-danger">{{$lang["admin_merchant_logs_failed"]}}</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">{{$lang["admin_merchant_logs_successful"]}}</span> @elseif($val->status==2) {{$lang["admin_merchant_logs_refunded"]}} @endif</td>
                                    <td>{{$currency->symbol.$val->amount}}</td>
                                    <td>@if($val->charge==null) - @else {{$currency->symbol.$val->charge}} @endif</td>
                                    <td>{{$val->reference}}</td>
                                    <td>{{$val->payment_type}} @if($val->payment_type=='card') - XXXX XXXX XXXX {{substr($val->card_number, 12)}} @endif</td>
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>                  
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop