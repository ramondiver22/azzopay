
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-8">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang["profile_sub_create_sub_account"]}}</a>
      </div>
    </div>
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="mb-0 font-weight-bolder">{{$lang["profile_sub_add_sub_account"]}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('submit.subacct')}}" method="post"> 
              @csrf
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="subname" class="form-control" placeholder="{{$lang["profile_sub_subaccounts_name"]}}">
                </div>
              </div>              
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="email" name="subemail" class="form-control" placeholder="{{$lang["profile_sub_subaccounts_email"]}}">
                </div>
              </div>      
              <div class="form-group row">
                <div class="col-lg-12">
                  <select class="form-control select" name="xcountry" id="xcountry" required>
                      <option value="">{{$lang["profile_sub_subaccount_country"]}}</option> 
                        @foreach($country as $val)
                          <option value="{{$val->country_id}}">{{$val->real['name']}}</option>
                        @endforeach
                  </select>
                </div>
              </div>          
              <div class="form-group row" id="splittype">
                <div class="col-lg-12">
                  <select class="form-control select" name="type" id="spt" required>
                    <option value=''>{{$lang["profile_sub_split_type"]}}</option>
                    <option value='1'>{{$lang["profile_sub_flat"]}}</option>
                    <option value='2'>{{$lang["profile_sub_percentage"]}}</option>
                  </select>
                </div>
              </div> 
              <div class="form-group row">
                  <div class="col-lg-12">
                      <select class="form-control select" name="account_type" required>
                          <option value="">{{$lang["profile_sub_account_type"]}}</option> 
                            <option value="individual">{{$lang["profile_sub_individual"]}}</option>
                            <option value="company">{{$lang["profile_sub_company"]}}</option>
                      </select>
                  </div>
              </div>  
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">#</span>
                  </div>
                  <input class="form-control" placeholder="{{$lang["profile_sub_routing_number"]}}" type="text" name="routing_number" required>
                </div>
              </div>                  
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_sub_save"]}}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          @if(count($sub)>0)
            @foreach($sub as $k=>$val)
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          @if($val->active==1)
                            <a class='dropdown-item' href="{{route('subacct.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{$lang["profile_sub_disabled"]}}</a>
                          @else
                            <a class='dropdown-item' href="{{route('subacct.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["profile_sub_disabled"]}}</a>
                          @endif
                            <a class="dropdown-item" href="{{route('user.subaccttrans', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{$lang["profile_sub_transactions"]}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["profile_sub_delete"]}}</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <p>{{$lang["profile_sub_email"]}}: {{$val->email}}</p>
                        <p>{{$lang["profile_sub_bank"]}}: {{$val->dbank['name']}}</p>
                        <p>{{$lang["profile_sub_type"]}}: @if($val->type==1){{$currency->symbol.$val->amount}} {{$lang["profile_sub_form_every_payout"]}} @else {{$val->amount}}% {{$lang["profile_sub_of_every_payout"]}}  @endif</p>
                        <p>{{$lang["profile_sub_account_number"]}}: {{$val->acct_no}}</p>
                        <p>{{$lang["profile_sub_account_name"]}}: {{$val->acct_name}}</p>
                        <p class="text-sm mb-2">{{$lang["profile_sub_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        @if($val->active==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_sub_active"]}}</span>
                        @else
                            <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["profile_sub_disabled"]}}</span>
                        @endif
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
                <h3 class="text-dark">{{$lang["profile_sub_no_sub_account_found"]}}</h3>
                <p class="text-dark text-sm card-text">{{$lang["profile_sub_we_couldnt_find_any_sub_account"]}}</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $sub->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
    @foreach($sub as $k=>$val)
    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0 font-weight-bolder">{{$lang["profile_sub_delete_sub_account"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            <span class="mb-0 text-xs">{{$lang["profile_sub_are_you_sure_you_want_to_delete"]}}</span>
                        </div>
                        <div class="card-body">
                            <a  href="{{route('subacct.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["profile_sub_proceed"]}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-form{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="mb-0">{{$lang["profile_sub_edit_sub_account"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form role="form" action="{{route('subacct.edit')}}" method="post"> 
                    @csrf
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="subname" placeholder="{{$lang["profile_sub_sub_account_name"]}}" class="form-control" value="{{$val['name']}}">
                    </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="name" placeholder="{{$lang["profile_sub_bank_name"]}}" class="form-control" value="{{$val['bank']}}">
                    </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="text" name="acct_name" class="form-control" placeholder="{{$lang["profile_sub_account_name"]}}" value="{{$val['acct_name']}}">
                    </div>
                    </div>       
                          
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="text" pattern="\d*" name="agency_no" placeholder="{{$lang["profile_newsub_agency_number"]}}" class="form-control" value="{{$val['agency_no']}}" maxlength="12">
                            <input type="hidden" name="agency" value="{{$val['agency']}}">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="text" pattern="\d*" name="acct_no" placeholder="{{$lang["profile_sub_account_number"]}}" class="form-control" value="{{$val['acct_no']}}" maxlength="12">
                            <input type="hidden" name="id" value="{{$val['id']}}">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="text" pattern="\d*" name="account_document" placeholder="{{$lang["profile_newsub_account_document"]}}" class="form-control" value="{{$val['account_document']}}" maxlength="12">
                            <input type="hidden" name="document" value="{{$val['document']}}">
                        </div>
                    </div>                   
                    <div class="form-group row">
                    <div class="col-lg-10">
                        <input type="text" name="swift" placeholder="Swift code" class="form-control" placeholder="{{$lang["profile_sub_swift_code"]}}" value="{{$val['swift_code']}}">
                        <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                          </div>
                          <input class="form-control" placeholder="{{$lang["profile_sub_rounting_number_sort_code"]}}" type="number" name="routing_number" value="{{$val['routing_number']}}" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="account_type" required>
                                <option value="">{{$lang["profile_sub_account_type"]}}</option> 
                                  <option value="individual" @if($val->account_type=='individual') selected @endif>{{$lang["profile_sub_individual"]}}</option>
                                  <option value="company" @if($val->account_type=='company') selected @endif>{{$lang["profile_sub_company"]}}</option>
                            </select>
                        </div>
                      </div>
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_sub_update_account"]}}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@stop