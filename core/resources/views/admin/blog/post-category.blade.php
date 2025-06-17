@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 h3 font-weight-bolder">{{$lang["admin_blog_post_create_category"]}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/createcategory')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_blog_post_category"]}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>               
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_blog_post_save"]}}</button>
                            </div>
                        </form>
                    </div>
                </div> 
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 h3 font-weight-bolder">{{$lang["admin_blog_post_category"]}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang["admin_blog_post_sn"]}}</th>
                                    <th>{{$lang["admin_blog_post_name"]}}</th>
                                    <th class="text-center">{{$lang["admin_blog_post_action"]}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cat as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->categories}}</td>
                                    <td class="text-center">
                                        <div class="text-right">
                                            <div class="dropdown">
                                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang["admin_blog_post_delete"]}}</a>
                                                    <a data-toggle="modal" data-target="#update{{$val->id}}" href="" class="dropdown-item">{{$lang["admin_blog_post_edit"]}}</a>
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
                @foreach($cat as $k=>$val)
                    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0">{{$lang["admin_blog_post_are_you_sure_you_want"]}}</h3>
                                        </div>
                                        <div class="card-body px-lg-5 py-lg-5 text-right">
                                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_blog_post_close"]}}</button>
                                            <a  href="{{route('blog.delcategory', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang["admin_blog_post_proceed"]}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                            
                    <div id="update{{$val->id}}" class="modal fade" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">   
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{url('admin/updatecategory')}}" method="post">
                                @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-form-label col-lg-4">{{$lang["admin_blog_post_category"]}}</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="name" class="form-control" value="{{$val->categories}}">
                                                <input type="hidden" name="id" value="{{$val->id}}">
                                            </div>
                                        </div>               
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_blog_post_close"]}}</button>
                                        <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_blog_post_save"]}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop