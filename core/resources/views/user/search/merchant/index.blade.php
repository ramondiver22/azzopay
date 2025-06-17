
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">  
      <div class="col-md-12">
        <div class="row">  
            <div class="col-md-6">
                <div class="card">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-6">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard text-primary" data-clipboard-text="{{$val->merchant_key}}" title="{{$lang["donation_copy"]}}">{{$lang["transactions_copy_merchant_key"]}}</a></p>
                      </div>  
                      <div class="col-6 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fal fa-ellipsis-h-alt"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          <a class="dropdown-item" href="{{route('log.merchant', ['id' => $val->merchant_key])}}">{{$lang["transactions_transactions"]}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#">{{$lang["donation_edit"]}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="">{{$lang["donation_delte"]}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                        <p>{{$lang["donation_reference"]}}: {{$val->ref_id}}</p>
                        <p>{{$lang["transactions_notify_email"]}}: @if($val->email==null) {{$lang["transactions_no_email"]}} @else {{$val->email}} @endif</p>
                        <p class="text-sm mb-2">{{$lang["donation_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["transactions_edit_merchant"]}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('update.merchant')}}" method="post" id="modal-detailx">
                      @csrf
                      <div class="form-group row">
                        <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" placeholder="{{$lang["transactions_merchant_name"]}}" value="{{$val->name}}" required>
                          <input type="hidden" name="id" value="{{$val->id}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="charge" required>
                                <option value="">{{$lang["transactions_who_pays_charges"]}}</option>
                                <option value="1" @if($val->charge==1) selected @endif>{{$lang["transactions_merchant"]}}</option>
                                <option value="0" @if($val->charge==0) selected @endif>{{$lang["transactions_client"]}}</option>
                            </select>
                        </div>
                      </div> 
                      <div class="form-group row">
                        <label class="col-form-label col-lg-12">{{$lang["transactions_send_notifications_to"]}}</label>
                        <div class="col-lg-12">
                          <input type="email" name="email" class="form-control" value="{{$val->email}}">
                          <span class="form-text text-xs">{{$lang["transactions_if_provided_this_email_address_will_get_transaction"]}}</span>
                        </div>
                      </div> 
                      <div class="text-right">
                        <button type="submit" class="btn btn-neutral btn-block" form="modal-detailx">{{$lang["transactions_update_merchant"]}}</button>
                      </div> 
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-body p-0">
                          <div class="card bg-white border-0 mb-0">
                              <div class="card-header">
                                <h3 class="mb-0 font-weight-bolder">{{$lang["transactions_delte_merchant"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{$lang["transactions_confirm_delete_merchant"]}}</span>
                              </div>
                              <div class="card-body">
                                  <a  href="{{route('delete.merchant', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["invoice_proceed"]}}</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </div>
@stop