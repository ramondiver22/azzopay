@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_transfer_orders_products_products']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_transfer_orders_products_sn']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_ref']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_vendor']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_received_amount_charges']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_product_name']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_amount']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_quantity']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_shipping_fee']}}</th>                                                             
                                    <th>{{$lang['admin_transfer_orders_products_status']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_suspended']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_created']}}</th>
                                    <th>{{$lang['admin_transfer_orders_products_updated']}}</th>
                                    <th class="text-center">{{$lang['admin_transfer_orders_products_action']}}</th>    
                                </tr>
                            </thead>
                            
                            <tbody>
                            @foreach($product as $k=>$val)
                                @php
                                    $amount=App\Models\Order::whereproduct_id($val->id)->sum('total');
                                    $charge=App\Models\Order::whereproduct_id($val->id)->sum('charge');
                                @endphp
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>#{{$val->ref_id}}</td>
                                    <td>{{$val->user->business_name}}</td>
                                    <td>{{$currency->symbol.number_format($amount, 2, '.', '')}} / {{$currency->symbol.number_format($charge, 2, '.', '')}}</td>
                                    <td>{{$val->name}}</td>
                                    <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                                    <td>{{$val->quantity}}</td>
                                    <td>{{$currency->symbol.number_format($val->shipping_fee)}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_products_disabled']}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_products_active']}}</span>                                        
                                        @endif
                                    </td> 
                                    <td>
                                        @if($val->active==0)
                                            <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_products_yes']}}</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_products_no']}}</span>
                                        @endif
                                    </td>                                      
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-center">
                                        <div class="">
                                            <div class="dropdown">
                                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    @if($val->active==0)
                                                        <a class='dropdown-item' href="{{route('product.unpublish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_products_unsuspend']}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('product.publish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_products_suspend']}}</a>
                                                    @endif
                                                    <a class="dropdown-item" href="{{route('admin.orders', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_products_orders']}}</a>
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_orders_products_delete']}}</a>
                                                </div>
                                            </div>
                                        </div> 
                                    </td>                  
                                </tr>
                                @endforeach               
                            </tbody>                    
                        </table>
                    </div>
                </div>
                @foreach($product as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{$lang['admin_transfer_orders_products_are_you_sure_you_want_to_delete']}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_orders_products_close']}}</button>
                                        <a  href="{{route('product.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_transfer_orders_products_proceed']}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop