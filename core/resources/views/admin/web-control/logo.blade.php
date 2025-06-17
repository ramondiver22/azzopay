@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_web_logo_light_mode']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('light.logo')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="logo" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang" style="border-color: {{$set->s_c}};">{{$lang['admin_web_logo_choose_media']}}</label>
                                </div>
                            </div>              
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_logo_upload']}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/{{$logo->image_link}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>         
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_web_logo_dark_mode']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('dark.logo')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang2" name="logo" lang="en" required>
                                    <label class="custom-file-label" for="customFileLang2">{{$lang['admin_web_logo_choose_media']}}</label>
                                </div>
                            </div>              
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_logo_upload']}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/{{$logo->dark}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang['admin_web_logo_favicon']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/updatefavicon')}}" enctype="multipart/form-data" method="post">
                        @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang1" name="favicon" lang="en" required>
                                    <label class="custom-file-label sdsd" for="customFileLang1">{{$lang['admin_web_logo_choose_media']}}</label>
                                </div>
                            </div>              
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_web_logo_upload']}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid" src="{{url('/')}}/asset/{{$logo->image_link2}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>  
@stop