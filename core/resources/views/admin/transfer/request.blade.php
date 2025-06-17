@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_transfer_orders_request_title']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_transfer_orders_request_sn']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_ref']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_sender']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_receiver']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_amount']}}</th>                                                                       
                                    <th>{{$lang['admin_transfer_orders_request_charge']}}</th>                                                                       
                                    <th>{{$lang['admin_transfer_orders_request_status']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_created']}}</th>
                                    <th>{{$lang['admin_transfer_orders_request_updated']}}</th>
                                    <th class="text-center">{{$lang['admin_transfer_orders_request_action']}}</th>    
                                </tr>
                            </thead>
                            
                            <tbody>
                            @foreach($request as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>#{{$val->ref_id}}</td>
                                    <td>{{$val->receiver['email']}}</td>
                                    <td>{{$val->email}}</td>
                                    <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                                    <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger">{{$lang['admin_transfer_orders_request_pending']}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success">{{$lang['admin_transfer_orders_request_successful']}}</span>                                        
                                        @elseif($val->status==2)
                                            <span class="badge badge-info">{{$lang['admin_transfer_orders_request_returned']}}</span> 
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
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_orders_request_delete']}}</a>
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
                @foreach($request as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{$lang['admin_transfer_orders_request_are_you_sure_you_want_to_delete']}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_orders_request_close']}}</button>
                                        <a  href="{{route('request.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_transfer_orders_request_proceed']}}</a>
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
</div>
@stop