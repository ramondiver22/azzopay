@extends('loginlayout')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="header py-5 pt-7">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-7">
          <div class="card border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{$lang["default_bank_account"]}}</h3>
                <span class="text-gray text-xs">{{$lang["settlements_will_be_paid"]}}</span>
              </div>
              <form role="form" action="{{ route('add.bank')}}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-12">
                        <select class="form-control select" name="bank" required>
                            <option value="">{{$lang["select_bank"]}}</option> 
                                @foreach($bnk as $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                        </select>
                    </div>
                </div>                
                <div class="form-group row">
                    <div class="col-lg-12">
                        <select class="form-control select" name="account_type" required>
                            <option value="">{{$lang["account_type"]}}</option> 
                              <option value="individual">{{$lang["individual"]}}</option>
                              <option value="company">{{$lang["company"]}}</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                            </div>
                            <input class="form-control" placeholder="{{$lang["agency_number"]}}" type="number" name="agency_no" required>
                        </div>
                    </div>                
                  </div>                                    
                </div>       
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                            </div>
                            <input class="form-control" placeholder="{{$lang["account_number"]}}" type="number" name="acct_no" required>
                        </div>
                    </div>                
                  </div>                                    
                </div>     
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                            </div>
                            <input class="form-control" placeholder="{{$lang["account_document"]}}" type="number" name="account_document" required>
                        </div>
                    </div>                
                  </div>                                    
                </div>     
                
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fad fa-user"></i></span>
                    </div>
                    <input class="form-control" placeholder="{{$lang["account_name"]}}" type="text" name="acct_name" required>
                  </div>
                </div>                
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">#</span>
                    </div>
                    <input class="form-control" placeholder="{{$lang["routing_number_sort_code"]}}" type="text" name="routing_number">
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4 btn-block">{{$lang["save_account"]}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop