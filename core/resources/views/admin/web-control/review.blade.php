@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                <div class="card-body">
                <a data-toggle="modal" data-target="#create" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang['admin_web_review_create_review']}}</a>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0">{{$lang['admin_web_review_review']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang['admin_web_review_sn']}}</th>
                                    <th>{{$lang['admin_web_vname']}}</th>
                                    <th>{{$lang['admin_web_review_occupation']}}</th>
                                    <th>{{$lang['admin_web_review_status']}}</th>
                                    <th>{{$lang['admin_web_review_created']}}</th>
                                    <th>{{$lang['admin_web_review_updated']}}</th>
                                    <th class="scope"></th>    
                                </tr>
                            </thead>
                            
                            <tbody>
                            @foreach($review as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->name}}</td>
                                    <td>{{$val->occupation}}</td>
                                    <td>
                                        @if($val->status==1)
                                            <span class="badge badge-success">{{$lang['admin_web_review_published']}}</span>
                                        @else
                                            <span class="badge badge-danger">{{$lang['admin_web_review_pending']}}</span>
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
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_web_review_delete']}}</a>
                                                    @if($val->status==1)
                                                        <a class='dropdown-item' href="{{route('review.unpublish', ['id' => $val->id])}}">{{$lang['admin_web_review_unpublish']}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('review.publish', ['id' => $val->id])}}">{{$lang['admin_web_review_pubish']}}</a>
                                                    @endif
                                                    <a href="{{route('review.edit', ['id' => $val->id])}}" class="dropdown-item">{{$lang['admin_web_review_edit']}}</a>
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
                @foreach($review as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <div class="card bg-white border-0 mb-0">
                                                    <div class="card-header">
                                                        <h3 class="mb-0">{{$lang['admin_web_review_are_you_sure_you_want_to_delete']}}</h3>
                                                    </div>
                                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_web_review_close']}}</button>
                                                        <a  href="{{route('review.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_web_review_proceed']}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                @endforeach
            </div>
        </div>
    <div id="create" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">   
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{url('admin/createreview')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="text" name="name" class="form-control" placeholder="{{$lang['admin_web_review_name_2']}}" required>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="text" name="occupation" class="form-control" placeholder="{{$lang['admin_web_review_occupation_2']}}" required>
                            </div>
                        </div>                         
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <textarea type="text" name="review" class="form-control" placeholder="{{$lang['admin_web_review_review_2']}}" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="file" class="custom-file-input" id="customFileLang" name="image" lang="en" required>
                                <label class="custom-file-label" for="customFileLang">{{$lang['admin_web_vchoose_image']}}</label>
                            </div>
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_review_save']}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop