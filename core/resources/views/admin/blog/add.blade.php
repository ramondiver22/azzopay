@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0 h3 font-weight-bolder">{{$lang["admin_blog_add_compose_article"]}}</h3>
          </div>
          <div class="card-body">
            <p class="text-danger"></p>
            <form action="{{route('blog.create')}}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang["admin_blog_add_title"]}}</label>
                    <div class="col-lg-10">
                        <input type="text" name="title" class="form-control" reqiured>
                    </div>
                    @if ($errors->has('title'))
                        <div class="error">{{ $errors->first('title') }}</div>
                    @endif
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang["admin_blog_add_category"]}}</label>
                    <div class="col-lg-10">
                        <select class="form-control select" name="cat_id" data-dropdown-css-class="bg-info-800" data-fouc required> 
                        @foreach($cat as $val)
                            <option value='{{$val->id}}'>{{$val->categories}}</option>     
                        @endforeach             
                        </select>
                        @if ($errors->has('cat_id'))
                            <div class="error">{{ $errors->first('cat_id') }}</div>
                        @endif
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang["admin_blog_add_thumbnail"]}}:</label>
                    <div class="col-lg-10">
                        <input type="file" class="custom-file-input" id="customFileLang" name="image" lang="en">
                        <label class="custom-file-label" for="customFileLang" style="border-color: {{$set->s_c}};">{{$lang["admin_blog_add_choose_media"]}}</label>
                    </div>
                </div>                
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang["admin_blog_add_content"]}}</label>
                    <div class="col-lg-10">
                        <textarea type="text" name="details"  class="tinymce form-control"></textarea>
                    </div>
                </div>           
                <div class="text-right">
                    <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_blog_add_save"]}}</button>
                </div>
            </form>
        </div>
    </div> 
</div> 

@stop