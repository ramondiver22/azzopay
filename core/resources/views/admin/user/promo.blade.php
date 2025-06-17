@extends('master')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">{{$lang['admin_users_promo_send_mail_to_all_users']}}</h3>
        </div>
        <div class="card-body">
            <form action="{{route('user.promo.send')}}" method="post">
            @csrf                                             
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang['admin_users_promo_subject']}}:</label>
                    <div class="col-lg-10">
                        <input type="text" name="subject" maxlength="200" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2">{{$lang['admin_users_promo_message']}}:</label>
                    <div class="col-lg-10">
                        <textarea type="text" name="message" rows="8" class="form-control tinymce"></textarea>
                    </div>
                </div>          
                <div class="text-right">
                    <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_users_promo_send']}}</button>
                </div>
            </form>
        </div>
    </div>
@stop