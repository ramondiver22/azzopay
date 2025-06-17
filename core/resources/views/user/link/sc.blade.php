@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-4">
        <h6 class="h2 d-inline-block mb-0">{{$lang["link_single_charge"]}}</h6>
      </div>
      <div class="col-8 text-right">
        <a data-toggle="modal" data-target="#single-charge" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["link_create_payment_link"]}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="single-charge" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["link_create_new_payment_link"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.singlecharge')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" placeholder="{{$lang["link_payment_link_name"]}}" required>
                          <span class="form-text text-xs">{{$lang["link_single_charge_allows_you"]}} {{$userTax->single_charge}}% {{$lang["link_per_transaction"]}}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                            <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="amount" placeholder="0.00">
                        </div>
                        <span class="form-text text-xs">{{$lang["link_leave_empty_to_allow"]}}</span>
                      </div>
                    </div>  
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <textarea type="text" name="description" placeholder="{{$lang["link_description"]}}" rows="4" class="form-control" required></textarea>
                      </div>
                    </div>           
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="redirect_url" class="form-control" placeholder="https://your-domain.com">
                            <span class="form-text text-xs">{{$lang["link_redirect_after_payment"]}}</span>
                      </div>                        
                    </div> 
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["link_create_link"]}}</button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          @if(count($links)>0)
            @foreach($links as $k=>$val)
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('scview.link', ['id' => $val->ref_id])}}" title="Copy">{{$lang["link_copy_link"]}} <i class="fad fa-link text-xs"></i></a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->active==1)
                              <a class='dropdown-item' href="{{route('sclinks.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{$lang["link_disable"]}}</a>
                          @else
                              <a class='dropdown-item' href="{{route('sclinks.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["link_activate"]}}</a>
                          @endif
                          <a class="dropdown-item" href="{{route('user.sclinkstrans', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{$lang["link_transactions"]}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{$lang["link_edit"]}}</a>
                          <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["link_delete"]}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                        <p>{{$lang["link_reference"]}}: {{$val->ref_id}}</p>
                        <p>{{$lang["link_amount"]}}: @if($val->amount==null) {{$lang["link_not_fixed"]}} @else {{$currency->symbol.number_format($val->amount, 2, '.', '')}} @endif</p>
                        <p class="text-sm mb-2">{{$lang["link_date"]}}: {{date("d/m/Y H:i:s", strtotime($val->created_at))}}</p>
                        @if($val->active==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["link_active"]}}</span>
                        @else
                            <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["link_disabled"]}}</span>
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
                      <h3 class="mb-0 font-weight-bolder">{{$lang["link_edit_payment_link"]}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="{{route('update.sclinks')}}" method="post">
                        @csrf
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <input type="text" name="name" class="form-control" value="{{$val->name}}" placeholder="{{$lang["link_payment_link_name"]}}" required>
                              <span class="form-text text-xs">{{$lang["link_single_charge_allows_you"]}} {{$userTax->single_charge}}% {{$lang["link_per_transaction"]}}</span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                </span>
                                <input type="number" step="any" class="form-control" name="amount" value="{{$val->amount}}" placeholder="0.00">
                            </div>
                            <span class="form-text text-xs">{{$lang["link_leave_empty_to_allow"]}}</span>
                          </div> 
                        </div>  
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <textarea type="text" name="description" rows="4" class="form-control" placeholder="{{$lang["link_description"]}}" required>{{$val->description}}</textarea>
                          </div>
                        </div>              
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <input type="text" name="redirect_url" class="form-control" value="{{$val->redirect_link}}" placeholder="https://your-domain.com">
                                <span class="form-text text-xs">{{$lang["link_redirect_after_payment"]}}</span>
                          </div>                        
                        </div> 
                        <input type="hidden" name="id" value="{{$val->id}}">                                     
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{$lang["link_update_payment_link"]}}</button>
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
                                    <h3 class="mb-0 font-weight-bolder">{{$lang["link_delte_payment_link"]}}</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <span class="mb-0 text-xs">{{$lang["link_are_you_sure_to_delete"]}}</span>
                                  </div>
                                  <div class="card-body">
                                      <a  href="{{route('delete.user.link', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["link_proceed"]}}</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            @endforeach
          @else
          <div class="col-md-12 mb-5">
              <div class="text-center mt-8">
                <div class="mb-3">
                  <img src="{{url('/')}}/asset/images/empty.svg">
                </div>
                <h3 class="text-dark">{{$lang["link_no_payment_link_found"]}}</h3>
                <p class="text-dark text-sm card-text">{{$lang["link_we_couldnt_find_any_donation"]}}</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $links->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
@stop