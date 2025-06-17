@extends('master')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">{{$lang['admin_users_messages_title']}}</h3>
        </div>
        <div class="table-responsive py-4">
            <table class="table table-flush" id="datatable-buttons">
                <thead>
                    <tr>
                        <th>{{$lang['admin_users_messages_sn']}}</th>
                        <th>{{$lang['admin_users_messages_name']}}</th>
                        <th>{{$lang['admin_users_messages_mobile']}}</th>
                        <th>{{$lang['admin_users_messages_email']}}</th>                                                                      
                        <th>{{$lang['admin_users_messages_message']}}</th>                                                                      
                        <th>{{$lang['admin_users_messages_created']}}</th>
                        <th>{{$lang['admin_users_messages_updated']}}</th>
                        <th class="scope"></th>    
                    </tr>
                </thead>
                <tbody>
                @foreach($message as $k=>$val)
                    <tr>
                        <td>{{++$k}}.</td>
                        <td>{{$val->full_name}}</td>
                        <td>{{$val->mobile}}</td>
                        <td>{{$val->email}}</td>
                        <td>{{$val->message}}</td>
                        <td>{{date("Y/m/d", strtotime($val->created_at))}}</td>  
                        <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a href="{{route('admin.email', ['email' => $val->email, 'name' => $val->full_name])}}" class="dropdown-item">{{$lang['admin_users_messages_send_email']}}</a>
                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_users_messages_delete']}}</a>
                                </div>
                            </div>
                        </td>                  
                    </tr>
                    @endforeach               
                </tbody>                    
            </table>
        </div>
    </div>
    @foreach($message as $k=>$val)
    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang['admin_users_messages_are_you_sure_you_want_to_delete']}}</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_users_messages_close']}}</button>
                            <a  href="{{route('message.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_users_messages_proceed']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@stop