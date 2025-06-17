@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_transfer_orders_title']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_transfer_orders_sn']}}</th>
                                    <th>{{$lang['admin_transfer_orders_ref']}}</th>
                                    <th>{{$lang['admin_transfer_orders_vendor']}}</th>
                                    <th>{{$lang['admin_transfer_orders_buyer']}}</th>
                                    <th>{{$lang['admin_transfer_orders_product_name']}}</th>
                                    <th>{{$lang['admin_transfer_orders_amount']}}</th>
                                    <th>{{$lang['admin_transfer_orders_total']}}</th>
                                    <th>{{$lang['admin_transfer_orders_charge']}}</th>
                                    <th>{{$lang['admin_transfer_orders_quantity']}}</th>
                                    <th>{{$lang['admin_transfer_orders_region']}}</th>
                                    <th>{{$lang['admin_transfer_orders_shipping_fee']}}</th>
                                    <th>{{$lang['admin_transfer_orders_address']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_country']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_state']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_town']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_name']}}</th>                                                                                                                             
                                    <th>{{$lang['admin_transfer_orders_email']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_phone']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_type']}}</th>                                                               
                                    <th>{{$lang['admin_transfer_orders_created']}}</th>
                                    <th>{{$lang['admin_transfer_orders_updated']}}</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>#{{$val->ref_id}}</td>
                                    <td>{{$val->seller->business_name}}</td>
                                    @if($val->user_id!=null)
                                    <td>{{$val->buyer->first_name.' '.$val->buyer->last_name}}</td>
                                    @else
                                    <td>{{$val->first_name.' '.$val->last_name}}</td>
                                    @endif
                                    <td>{{$val->lala->name}}</td>
                                    <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->total, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                                    <td>{{$val->quantity}}</td>
                                    <td>{{$lang['admin_transfer_orders_region']}}: {{$val->ship['region']}}</td>
                                    <td>{{$lang['admin_transfer_orders_shipping_fee']}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</td>
                                    <td>{{$val->address}}</td>                                    
                                    <td>{{$val->country}}</td>                                    
                                    <td>{{$val->state}}</td>                                    
                                    <td>{{$val->town}}</td>   
                                    @if($val->user_id!=null)
                                        <td>{{$lang['admin_transfer_orders_name_2']}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</td>
                                        <td>{{$lang['admin_transfer_orders_email_2']}}: {{$val->buyer->email}}</td>
                                        <td>{{$lang['admin_transfer_orders_phone_2']}}: {{$val->buyer->phone}}</td>
                                    @else
                                        <td>{{$lang['admin_transfer_orders_name_2']}}: {{$val->first_name}} {{$val->last_name}}</td>
                                        <td>{{$lang['admin_transfer_orders_email_2']}}: {{$val->email}}</td>
                                        <td>{{$lang['admin_transfer_orders_phone_2']}}: {{$val->phone}}</td>
                                    @endif
                                    @if($val->store_id==null)
                                        <td>{{$lang['admin_transfer_orders_type_single_purchase']}}</td>
                                    @elseif($val->store_id!=null)
                                        <td>{{$lang['admin_transfer_orders_type_store_purchase']}}</td>
                                    @endif                                                                   
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