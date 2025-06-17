@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          <div class="col-md-4">
            <div class="card bg-white">
              <!-- Card body -->
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-4">
                    <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard text-primary" data-clipboard-text="{{route('scview.link', ['id' => $val->ref_id])}}" title="{{$lang["donation_copy"]}}">{{$lang["donation_copy_link"]}}</a></p>
                  </div>  
                  <div class="col-8 text-right">
                    <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fal fa-ellipsis-h-alt"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left">
                      @if($val->active==1)
                          <a class='dropdown-item' href="{{route('sclinks.unpublish', ['id' => $val->id])}}">{{$lang["donation_disable"]}}</a>
                      @else
                          <a class='dropdown-item' href="{{route('sclinks.publish', ['id' => $val->id])}}">{{$lang["donation_active"]}}</a>
                      @endif
                      <a class="dropdown-item" href="{{route('user.sclinkstrans', ['id' => $val->id])}}">{{$lang["donation_transactions"]}}</a>
                      <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#">{{$lang["donation_edit"]}}</a>
                      <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="">{{$lang["donation_delte"]}}</a>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                    <p>{{$lang["donation_reference"]}}: {{$val->ref_id}}</p>
                    <p>{{$lang["donation_amount"]}}: @if($val->amount==null) {{$lang["transactions_not_fixed"]}} @else {{$currency->symbol.number_format($val->amount)}} @endif</p>
                    <p class="text-sm mb-2">{{$lang["donation_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                    @if($val->active==1)
                        <span class="badge badge-pill badge-success">{{$lang["donation_ative"]}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang["donation_disabled"]}}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>    
          <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["transactions_payment_link_edit_payment_link"]}}</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="{{route('update.sclinks')}}" method="post">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" value="{{$val->name}}" placeholder="{{$lang["transactions_payment_link_payment_link_name"]}}" required>
                          <span class="form-text text-xs">{{$lang["transactions_payment_link_single_charge_allows_you_to_create"]}} {{$set->single_charge}}% {{$lang["transactions_payment_link_per_transaction"]}}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">{{$currency->symbol}}</span>
                            </span>
                            <input type="number" class="form-control" name="amount" value="{{$val->amount}}" placeholder="0.00">
                            <span class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </span>
                        </div>
                        <span class="form-text text-xs">{{$lang["transactions_payment_link_leave_empty_to_allow"]}}</span>
                      </div> 
                    </div>  
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <textarea type="text" name="description" rows="4" class="form-control" placeholder="{{$lang["donation_description"]}}">{{$val->description}}</textarea>
                      </div>
                    </div>              
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="redirect_url" class="form-control" value="{{$val->redirect_link}}" placeholder="https://your-domain.com">
                            <span class="form-text text-xs">{{$lang["transactions_payment_link_redirect_after_payment_optional"]}}</span>
                      </div>                        
                    </div> 
                    <input type="hidden" name="id" value="{{$val->id}}">                                     
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-block">{{$lang["transactions_payment_link_update_payment_link"]}}</button>
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
                                <h3 class="mb-0 font-weight-bolder">{{$lang["transactions_payment_link_delete_payment_link"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{$lang["transactions_payment_link_confirm_delete_payment_link"]}}</span>
                              </div>
                              <div class="card-body">
                                  <a  href="{{route('delete.user.link', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["donation_proceed"]}}</a>
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