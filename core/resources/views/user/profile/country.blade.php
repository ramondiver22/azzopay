@extends('loginlayout')

@section('content')
  <div class="main-content">
    <div class="header py-7 py-lg-3 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">

          </div>
        </div>
      </div>
    </div>
    <div class="container mt--8 pb-2">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent pb-3">
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{$lang["profile_country_update_country"]}}</h3>
                <small>{{$lang["profile_country_this_cant_be_edited_once"]}}</small>
              </div>
              <form role="form" action="{{route('submit.country')}}" method="post">
                @csrf
                <div class="form-group mb-0">
                    <select class="form-control select" name="country" required>
                        @foreach($country as $val)
                            <option value="{{$val->country_id}}">{{$val->real['nicename']}}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="text-center">
                    <button type="submit" class="btn btn-neutral btn-block my-4 text-uppercase">{{$lang["profile_country_update"]}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop