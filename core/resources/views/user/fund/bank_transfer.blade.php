
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">{{$lang["fund_bank_transfer"]}}</h3>
          </div>
          <div class="card-body">
          <form method="post" action="{{route('bank_transfersubmit')}}" enctype="multipart/form-data">
          @csrf
           <div class="form-group row">
              <label class="col-form-label col-lg-2">{{$lang["fund_amount"]}}</label>
              <div class="col-lg-10">
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text">{{$currency->symbol}}</span>
                  </span>
                <input type="number" step="any" name="amount" max-length="10" class="form-control">
                  </div>
                </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-lg-3">{{$lang["fund_transfer_notes"]}}</label>
              <div class="col-lg-9">
                  <textarea type="text" class="form-control" rows="3" placeholder="{{$lang["fund_transaction_Details"]}}" name="details" required></textarea>
              </div>
            </div> 
            <div class="form-group row">
              <label class="col-form-label col-lg-3">{{$lang["fund_proof_of_payment"]}}</label>
              <div class="col-lg-9">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFileLang1" name="image" lang="en">
                  <label class="custom-file-label" for="customFileLang1">{{$lang["fund_choose_screenshot"]}}</label>
                </div>
              </div>
            </div> 
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
            <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-sm">{{$lang["fund_proceed"]}}</button>
            </div>
                </div>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-white">
          <div class="card-body text-center">
            <div class="">
              <div>
                <h3 class="card-title mb-3">{{$lang["fund_bank_details"]}}</h3>
                <ul class="list list-unstyled mb-0 card-text text-sm">
                  <li>{{$lang["fund_name"]}}: {{$bank->name}}</li>
                  <li>{{$lang["fund_bank"]}}: {{$bank->bank_name}}</li>
                  <li>{{$lang["fund_address"]}}: {{$bank->address}}</li>
                  <li>{{$lang["fund_swift_code"]}}: {{$bank->swift}}</li>
                  <li>{{$lang["fund_iban_code"]}}: {{$bank->iban}}</li>
                  <li>{{$lang["fund_account_number"]}}: {{$bank->acct_no}}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop