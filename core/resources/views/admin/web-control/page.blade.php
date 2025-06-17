@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                <div class="card-body">
                <a data-toggle="modal" data-target="#create" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang['admin_web_page_create_page']}}</a>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0">{{$lang['admin_web_page_title_page']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_web_page_sn']}}</th>
                                    <th>{{$lang['admin_web_page_title']}}</th>
                                    <th>{{$lang['admin_web_page_status']}}</th>
                                    <th>{{$lang['admin_web_page_created']}}</th>
                                    <th>{{$lang['admin_web_page_updated']}}</th>
                                    <th class="scope"></th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($page as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->title}}</td>
                                    <td>
                                        @if($val->status==1)
                                            <span class="badge badge-success">{{$lang['admin_web_page_published']}}</span>
                                        @else
                                            <span class="badge badge-danger">{{$lang['admin_web_page_pending']}}</span>
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
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_web_page_delete']}}</a>
                                                    @if($val->status==1)
                                                        <a class='dropdown-item' href="{{route('page.unpublish', ['id' => $val->id])}}">{{$lang['admin_web_page_unpublish']}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('page.publish', ['id' => $val->id])}}">{{$lang['admin_web_page_publish']}}</a>
                                                    @endif
                                                    <a data-toggle="modal" data-target="#update{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_web_page_edit']}}</a>
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
                @foreach($page as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="card bg-white border-0 mb-0">
                                                    <div class="card-header">
                                                        <h3 class="mb-0">{{$lang['admin_web_page_are_you_sure_you_want_to_delete']}}</h3>
                                                    </div>
                                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_web_page_close']}}</button>
                                                        <a  href="{{route('page.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_web_page_proceed']}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="update{{$val->id}}" class="modal fade" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">   
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{route('page.update')}}" method="post">
                                            @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-2">{{$lang['admin_web_page_title_2']}}:</label>
                                                        <div class="col-lg-10">
                                                            <input type="text" name="title" class="form-control" value="{{$val->title}}">
                                                            <input type="hidden" name="id" value="{{$val->id}}">
                                                        </div>
                                                    </div>  
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-2">{{$lang['admin_web_page_details']}}:</label>
                                                        <div class="col-lg-10">
                                                            <textarea type="text" name="content" class="form-control tinymce">{{$val->content}}</textarea>
                                                        </div>
                                                    </div>               
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_page_save']}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                @endforeach
            </div>
        </div>
    <div id="create" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">   
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{url('admin/createpage')}}" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang['admin_web_page_title_2']}}:</label>
                            <div class="col-lg-10">
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang['admin_web_page_details']}}:</label>
                            <div class="col-lg-10">
                                <textarea type="text" name="content" class="form-control tinymce"></textarea>
                            </div>
                        </div>               
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_page_save']}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop