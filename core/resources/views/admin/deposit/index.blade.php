@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang["admin_deposits_index_deposit_logs"]}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang["admin_deposits_index_sn"]}}</th>
                                    <th>{{$lang["admin_deposits_index_name"]}}</th>
                                    <th>{{$lang["admin_deposits_index_amount"]}}</th>                                                                       
                                    <th>{{$lang["admin_deposits_index_method"]}}</th>
                                    <th>{{$lang["admin_deposits_index_ref"]}}</th>
                                    <th>{{$lang["admin_deposits_index_charge"]}}</th>
                                    <th>{{$lang["admin_deposits_index_status"]}}</th>
                                    <th>{{$lang["admin_deposits_index_created"]}}</th>
                                    <th>{{$lang["admin_deposits_index_updated"]}}</th>
                                    <th class="text-center">{{$lang["admin_deposits_index_action"]}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($deposit as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td><a href="{{url('admin/manage-user')}}/{{$val->user['id']}}">{{$val->user['first_name'].' '.$val->user['last_name']}}</a></td>
                                    <td>{{$currency->symbol.number_format($val->amount-$val->charge, '2', '.', '')}}</td>
                                    <td>{{(isset($val->gateway['name']) ? $val->gateway['name'] : "")}}</td>
                                    <td>{{$val->trx}}</td> 
                                    <td>{{$currency->symbol.number_format($val->charge, '2', '.', '')}}</td> 
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger badge-pill">{{$lang["admin_deposits_index_pending"]}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success badge-pill">{{$lang["admin_deposits_index_approved"]}}</span>  
                                        @elseif($val->status==2)
                                            <span class="badge badge-info badge-pill">{{$lang["admin_deposits_index_declined"]}}</span> 
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
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang["admin_deposits_index_delete"]}}</a>
                                                    @if($val->status==0)
                                                        <a class='dropdown-item' href="{{route('deposit.decline', ['id' => $val->id])}}">{{$lang["admin_deposits_index_decline"]}}</a>
                                                        <a class='dropdown-item' href="{{route('deposit.approve', ['id' => $val->id])}}">{{$lang["admin_deposits_index_approve"]}}</a>
                                                    @endif
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
                @foreach($deposit as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{$lang["admin_deposits_index_are_you_sure_you_want"]}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_deposits_index_close"]}}</button>
                                        <a  href="{{route('deposit.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang["admin_deposits_index_proceed"]}}</a>
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