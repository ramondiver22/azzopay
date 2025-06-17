@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_web_brand_edit_title']}}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-danger"></p>
                        <form action="{{route('brand.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <input type="text" name="title" class="form-control" placeholder="{{$lang['admin_web_brand_edit_title_2']}}" value="{{$val->title}}">
                                    <input type="hidden" name="id" value="{{$val->id}}">
                                </div>
                            </div>  
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang2" name="image" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang2">{{$lang['admin_web_brand_edit_choose_media']}}</label>
                                </div>
                            </div>         
                            <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_brand_edit_save']}}</button>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
            <div class="col-md-4">
                <div class="card-body text-center">
                    <div class="card-img-actions d-inline-block mb-3">
                        <img class="img-fluid" src="{{url('/')}}/asset/brands/{{$val->image}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop