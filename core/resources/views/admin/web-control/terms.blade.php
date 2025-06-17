@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h3 class="mb-0">{{$lang["admin_web_terms_terms_conditions"]}}</h3>
            </div>
            <div class="card-body">
                <form action="{{route('terms.update')}}" method="post">
                @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                        <textarea type="text" name="details" class="tinymce form-control">{{$value->terms}}</textarea>
                        </div>
                    </div>                
                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_web_terms_save"]}}</button>
                    </div>
                </form>
            </div>
        </div> 
@stop