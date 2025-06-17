@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-body">
                    <a href="{{route('new.staff')}}" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang['admin_users_staff_new_staff']}}</a>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0">{{$lang['admin_users_staff_staff']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_users_staff_sn']}}</th>
                                    <th>{{$lang['admin_users_staff_name']}}</th>
                                    <th>{{$lang['admin_users_staff_username']}}</th>                                                                      
                                    <th>{{$lang['admin_users_staff_status']}}</th>
                                    <th>{{$lang['admin_users_staff_created']}}</th>
                                    <th>{{$lang['admin_users_staff_updated']}}</th>
                                    <th class="scope"></th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->first_name.' '.$val->last_name}}</td>
                                    <td>{{$val->username}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-pill badge-info">{{$lang['admin_users_staff_active']}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-pill badge-danger">{{$lang['admin_users_staff_blocked']}}</span> 
                                        @endif
                                    </td>   
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-right">
                                    <div class="dropdown">
                                            <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a href="{{route('staff.manage', ['id' => $val->id])}}" class="dropdown-item">{{$lang['admin_users_staff_edit_permissions']}}</a>
                                                @if($val->status==0)
                                                    <a class='dropdown-item' href="{{route('staff.block', ['id' => $val->id])}}">{{$lang['admin_users_staff_block']}}</a>
                                                @else
                                                    <a class='dropdown-item' href="{{route('staff.unblock', ['id' => $val->id])}}">{{$lang['admin_users_staff_unblock']}}</a>
                                                @endif
                                                <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_users_staff_delete']}}</a>
                                            </div>
                                        </div>
                                    </td>                   
                                </tr>
                                @endforeach               
                            </tbody>                    
                        </table>
                    </div>
                </div>
                @foreach($users as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{$lang['admin_users_staff_are_you_sure_you_want_to_delete']}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_users_staff_close']}}</button>
                                        <a  href="{{route('staff.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_users_staff_proceed']}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
@stop