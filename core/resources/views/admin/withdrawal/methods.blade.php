@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                <div class="card-body">
                <a data-toggle="modal" data-target="#create" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang['admin_method_create_type']}}</a>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang['admin_method_withdraw_methods']}}</h6>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_method_sn']__('S/N')}}</th>
                                    <th>{{$lang['admin_method_name']__('Name')}}</th>
                                    <th>{{$lang['admin_method_status']__('Status')}}</th>
                                    <th>{{$lang['admin_method_created']__('Created')}}</th>
                                    <th>{{$lang['admin_method_updated']__('Updated')}}</th>
                                    <th class="text-center">{{$lang['admin_method_action']__('Action')}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($method as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->method}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger">{{$lang['admin_method_disabled']}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success">{{$lang['admin_method_active']}}</span> 
                                        @endif
                                    </td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-center">
                                        <div class="text-right">
                                            <div class="dropdown">
                                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    @if($val->status==1)
                                                        <a class='dropdown-item' href="{{route('withdraw.declinedm', ['id' => $val->id])}}">{{$lang['admin_method_disable']}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('withdraw.approvem', ['id' => $val->id])}}">{{$lang['admin_method_activate']}}</a>   
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
            </div>
        </div>
    </div>
</div>
    <div id="create" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">   
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('admin.withdraw.store')}}" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang['admin_method_name_2']}}</label>
                            <div class="col-lg-10">
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">{{$lang['admin_method_close']}}</button>
                        <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_method_save']}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop