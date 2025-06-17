
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
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard text-primary" data-clipboard-text="{{route('view.invoice', ['id' => $val->ref_id])}}" title="{{$lang["donation_copy"]}}">{{$lang["donation_copy_link"]}}</a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fal fa-ellipsis-h-alt"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->status==0)
                            <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#">{{$lang["donation_edit"]}}</a>
                            <a class="dropdown-item" href="{{route('paid.invoice', ['id' => $val->ref_id])}}">{{$lang["invoice_mark_as_paid"]}}</a>
                            <a class="dropdown-item" href="{{route('reminder.invoice', ['id' => $val->ref_id])}}">{{$lang["invoice_resend"]}}</a>
                          @endif
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="">{{$lang["donation_delte"]}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row align-items-center">
                      <div class="col">
                        <h5 class="h4 mb-0 font-weight-bolder">{{$val->ref_id}}</h5>
                        <p class="text-sm mb-0">{{$lang["invoice_invoice_no"]}}: {{$val->invoice_no}}</p>
                        <p class="text-sm mb-0">{{$lang["donation_name"]}}: {{$val->item}}</p>
                        <p class="text-sm mb-0">{{$lang["invoice_recipient"]}}: {{$val->email}}</p>
                        <p class="text-sm mb-0">{{$lang["invoice_tax"]}}: {{$val->tax}}%</p>
                        <p class="text-sm mb-0">{{$lang["invoice_discount"]}}: {{$val->discount}}%</p>
                        <p class="text-sm mb-0">{{$lang["invoice_total"]}}: {{$currency->symbol.number_format($val->total)}}</p>
                        <p class="text-sm mb-0">{{$lang["invoice_sent"]}}: @if($val->sent==1) {{$lang["invoice_yes"]}} @ {{$val->sent_date}} @elseif($val->sent==0) {{$lang["invoice_no"]}} @endif</p>
                        <p class="text-sm mb-0">{{$lang["invoice_due_by"]}}: {{date("h:i:A j, M Y", strtotime($val->due_date))}}</p>
                        <p class="text-sm mb-2">{{$lang["invoice_created"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        @if($val->status==1)
                          <span class="badge badge-pill badge-primary">{{$lang["invoice_charge"]}}: {{$currency->symbol.number_format($val->charge)}}</span>
                          <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{$lang["invoice_paid"]}}</span>
                        @elseif($val->status==0)
                          <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i> {{$lang["invoice_pending"]}}</span>                    
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
                      <h3 class="mb-0 font-weight-bolder">{{$lang["invoice_edit_invoice"]}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('update.invoice')}}" method="post">
                        @csrf
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["invoice_amount"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">{{$currency->symbol}}</span>
                              </div>
                              <input type="hidden" name="id" value="{{$val->id}}"> 
                              <input type="number" step="any" name="amount" value="{{$val->amount}}" class="form-control" required="">
                            </div>
                          </div>
                        </div>                       
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["invoice_quantity"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="quantity" value="{{$val->quantity}}" class="form-control" required="">
                            </div>
                          </div>
                        </div>                        
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["invoice_tax"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="tax" maxlength="10" value="{{$val->tax}}" class="form-control">
                              <span class="input-group-append">
                                <span class="input-group-text">%</span>
                              </span>
                            </div>
                          </div>
                        </div>                      
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["invoice_discount"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group input-group-merge">
                              <input type="number" name="discount" maxlength="10" value="{{$val->discount}}" class="form-control">
                              <span class="input-group-append">
                                <span class="input-group-text">%</span>
                              </span>
                            </div>
                          </div>
                        </div>                           
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12" for="exampleDatepicker">{{$lang["invoice_due_date"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group">
                              <span class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                              </span>
                              <input type="text" class="form-control datepicker" name="due_date" value="{{$val->due_date}}" required>
                            </div>
                          </div>
                        </div>                
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{$lang["invoice_save"]}}</button>
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
                                    <h3 class="mb-0 font-weight-bolder">{{$lang["invoice_delete_invoice"]}}</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <span class="mb-0 text-xs">{{$lang["invoice_are_you_sure_you_anto_to_delete_transaction"]}}</span>
                                  </div>
                                  <div class="card-body">
                                      <a  href="{{route('delete.invoice', ['id' => $val->ref_id])}}" class="btn btn-danger btn-block">{{$lang["invoice_proceed"]}}</a>
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