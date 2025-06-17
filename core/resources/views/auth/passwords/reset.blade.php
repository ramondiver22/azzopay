@extends('loginlayout')

@section('content')
<div class="main-content">
    <div class="header py-6 pt-7">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card border-0 mb-0">
            <div class="card-header bg-transparent pb-3">
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-dark mb-5">
                <small>{{$lang["reset_recoer_your_account"]}}</small>
              </div>
              <form role="form" action="{{route('user.password.request')}}" method="post">
                @csrf
                <div class="form-group mb-3">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                    </div>
                    <input class="form-control" placeholder="{{$lang["reset_email"]}}" type="email" name="email" required>
                  </div>
                  @if ($errors->has('email'))
                      <span class="error form-error-msg ">
                        <strong>{{ $errors->first('email') }}</strong>
                      </span>
                    @endif
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-unlock"></i></span>
                    </div>
                    <input class="form-control" placeholder="{{$lang["reset_password"]}}" type="password" name="password" required>
                  </div>
                  @if ($errors->has('password'))
                    <span class="error form-error-msg ">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="text-center">
                  <button type="submit" class="btn btn-neutral btn-block my-4 text-uppercase">{{$lang["reset_continue"]}}</button>
                  <div class="loginSignUpSeparator"><span class="textInSeparator">{{$lang["reset_or"]}}</span></div>
                  <a href="{{route('login')}}" class="btn btn-primary btn-block my-0 text-uppercase">{{$lang["reset_sign_in"]}}</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop