
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["profile_newsub_create_sub_account"]}}</h3>
      </div>      
      <div class="card-body">
        <form action="{{route('submit.subacct2')}}" method="post"> 
            @csrf  
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_bank"]}}</label>
                <div class="col-lg-10">
                    <select class="form-control select" name="bank">
                        <option value="">{{$lang["profile_newsub_select_bank"]}}</option> 
                            @foreach($bnk as $val)
                            <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_account_name"]}}</label>
                <div class="col-lg-10">
                    <input type="text" name="acct_name" class="form-control" placeholder="{{$lang["profile_newsub_account_name"]}}" required>
                </div>
            </div>     
                                                                               
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_agency_number"]}}</label>
                <div class="col-lg-10">
                    <input type="text" pattern="\d*" name="agency_no" maxlength="10" placeholder="{{$lang["profile_newsub_agency_number"]}}" class="form-control" required>
                </div>
            </div>  
            
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_account_number"]}}</label>
                <div class="col-lg-10">
                    <input type="text" pattern="\d*" name="acct_no" maxlength="12" placeholder="{{$lang["profile_newsub_account_number"]}}" class="form-control" required>
                </div>
            </div>     
            
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_account_document"]}}</label>
                <div class="col-lg-10">
                    <input type="text" pattern="\d*" name="acct_no" maxlength="20" placeholder="{{$lang["profile_newsub_account_document"]}}" class="form-control" required>
                </div>
            </div>     
            @if(Session::get('type')==1)
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_subaccount_share_of_payments"]}}</label>
                <div class="col-lg-10">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{$currency->symbol}}</span>
                    </div>
                    <input type="number" name="flat_share" class="form-control" required>
                    </div>
                </div>
            </div> 
            @elseif(Session::get('type')==2)                   
            <div class="form-group row">
                <label class="col-form-label col-lg-2">{{$lang["profile_newsub_subaccount_share_of_payments"]}}</label>
                <div class="col-lg-10">
                    <div class="input-group">
                    <input type="number" name="percent_share" class="form-control" min="1" max="99">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                    </div>
                </div>
            </div>  
            @endif                
            <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-md">{{$lang["profile_newsub_save"]}}</button>
            </div>
        </form>
      </div>
    </div>

@stop