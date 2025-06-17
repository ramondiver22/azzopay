@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 mb-0">
                <div class="card-header">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["support_new_ticket"]}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('submit-ticket')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_subject"]}}</label>
                            <div class="col-lg-10">
                                <div class="input-group input-group-merge">
                                <input type="text" name="subject" class="form-control" required="">
                                </div>
                            </div>
                        </div>                       
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_reference"]}} <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <div class="input-group input-group-merge">
                                <input type="text" name="ref_no" class="form-control" placeholder="{{$lang["support_transaction_reference_number"]}}">
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_priority"]}}</label>
                            <div class="col-lg-10">
                                <select class="form-control select" name="priority" required>
                                    <option  value="Low">{{$lang["support_low"]}}</option>
                                    <option  value="Medium">{{$lang["support_medium"]}}</option> 
                                    <option  value="High">{{$lang["support_high"]}}</option>  
                                </select>
                            </div>
                        </div>                      
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_type"]}}</label>
                            <div class="col-lg-10">
                                <select class="form-control select" name="type" required>
                                    <option  value="subscription">{{$lang["support_subscription"]}}</option>
                                    <option  value="money_transfer">{{$lang["support_money_transfer"]}}</option> 
                                    <option  value="request_money">{{$lang["support_request_money"]}}</option>  
                                    <option  value="settlement">{{$lang["support_settlement"]}}</option>  
                                    <option  value="store">{{$lang["support_store"]}}</option>  
                                    <option  value="single_charge">{{$lang["support_single_charge"]}}</option>  
                                    <option  value="donation">{{$lang["support_donation"]}}</option>  
                                    <option  value="invoice">{{$lang["support_invoice"]}}</option>  
                                    <option  value="charges">{{$lang["support_charges"]}}</option>  
                                    <option  value="bank_transfer">{{$lang["support_bank_transfer"]}}</option>  
                                    <option  value="deposit">{{$lang["support_deposit"]}}</option>  
                                    <option  value="virtual_card">{{$lang["support_virtual_card"]}}</option>  
                                    <option  value="bill_payment">{{$lang["support_bill_payment"]}}</option>  
                                    <option  value="crypto_currency">{{$lang["support_cryptocurrency"]}}</option>  
                                    <option  value="others">{{$lang["support_others"]}}</option>  
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_details"]}}</label>
                            <div class="col-lg-10">
                                <textarea name="details" class="form-control" rows="6" required placeholder="{{$lang["support_description"]}}"></textarea>
                            </div>
                        </div>            
                        
                        
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["support_select_a_file"]}} <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileLang" name="image[]" multiple required>
                                    <label class="custom-file-label" for="customFileLang">{{$lang["support_choose_media"]}}</label>
                                </div> 
                            </div>
                        </div>                
                        <div class="text-right">
                            <button type="submit" class="btn btn-neutral btn-sm">{{$lang["support_save"]}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
@stop