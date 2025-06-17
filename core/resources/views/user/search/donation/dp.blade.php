@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          <div class="col-md-4">
            <div class="card">
              <img class="card-img-top" src="{{url('/')}}/asset/profile/{{$val->image}}" alt="Image placeholder">
              @php 
              $donors=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->get();
              $donated=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->sum('amount');
              @endphp
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-4">
                      <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard text-primary" data-clipboard-text="{{route('dpview.link', ['id' => $val->ref_id])}}" title="{{$lang["$lang[""]"]}}">{{$lang["donation_copy_link"]}}</a></p>
                    </div>
                    <div class="col-8 text-right">
                      <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fal fa-ellipsis-h-alt"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-left">
                        @if($val->active==1)
                            <a class='dropdown-item' href="{{route('dplinks.unpublish', ['id' => $val->id])}}">{{$lang["donation_disable"]}}</a>
                        @else
                            <a class='dropdown-item' href="{{route('dplinks.publish', ['id' => $val->id])}}">{{$lang["donation_active"]}}</a>
                        @endif
                        <a class="dropdown-item" href="{{route('user.sclinkstrans', ['id' => $val->id])}}">{{$lang["donation_transactions"]}}</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#donors{{$val->id}}" href="#">{{$lang["donation_donors"]}}</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#">{{$lang["donation_edit"]}}</a>
                        <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="">{{$lang["donation_delte"]}}</a>
                      </div>
                    </div>                        
                  </div>
                  <div class="row mb-3">
                    <div class="col-12">
                      <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                      <p>{{$lang["donation_reference"]}}: {{$val->ref_id}}</p>
                      <p>{{$lang["donation_donors"]}}: ({{count($donors)}})</p>
                      <p>{{$lang["donation_amount"]}}: {{$currency->symbol.number_format($donated)}}/{{$currency->symbol.number_format($val->amount)}}</p>
                      <p class="text-sm mb-2">{{$lang["donation_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                      @if($val->active==1)
                          <span class="badge badge-pill badge-success">{{$lang["donation_ative"]}}</span>
                      @else
                          <span class="badge badge-pill badge-danger">{{$lang["donation_disabled"]}}</span>
                      @endif
                    </div>
                  </div>
                  <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{($donated*100)/$val->amount}}%;"></div>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>             
          <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["donation_edit_payment_link"]}}</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="card-body">
                  <form action="{{route('update.dplinks')}}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" value="{{$val->name}}" placeholder="{{$lang["donation_payment_link_name"]}}" required>
                          <span class="form-text text-xs">{{$lang["donation_edit_donation_name"]}} {{$userTax->donation_charge}}% {{$lang["donation_per_donation"]}}</span>
                      </div>
                    </div>  
                    <div class="form-group row">
                      <label class="col-form-label col-lg-12">{{$lang["donation_goal"]}}</label>
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                              <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" class="form-control" name="goal" value="{{$val->amount}}" min="{{$donated}}" placeholder="0.00" required>
                          <span class="input-group-append">
                              <span class="input-group-text">.00</span>
                          </span>
                        </div>
                      </div>
                    </div>  
                    <div class="form-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFileLang" name="image" accept="image/*">
                        <label class="custom-file-label" for="customFileLang">{{$lang["donation_image"]}}</label>
                      </div> 
                    </div> 
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <textarea type="text" name="description" rows="4" class="form-control" placeholder="{{$lang["donation_description"]}}">{{$val->description}}</textarea>
                      </div>
                    </div>  
                    <input type="hidden" name="id" value="{{$val->id}}">                                     
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-block">{{$lang["donation_update_payment_link"]}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="donors{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
              <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                  <div class="modal-content">
                      <div class="modal-body p-0">
                          <div class="card bg-white border-0 mb-0">
                              <div class="card-body px-lg-5 py-lg-5">
                                <ul class="list-group list-group-flush list my--3">
                                  @if(count($donors)>0)
                                    @foreach($donors as $k=>$xval)
                                        <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="icon icon-shape text-white rounded-circle bg-success">
                                                    <i class="fa fa-bookmark-o"></i>
                                                </div>
                                            </div>
                                            <div class="col ml--2">
                                            <h4 class="mb-0">
                                                @if($xval->anonymous==0) 
                                                  @if($xval->user_id==null)
                                                      @php
                                                          $fff=App\Models\Transactions::whereref_id($xval->ref_id)->first();
                                                      @endphp
                                                      {{$fff['first_name'].' '.$fff['last_name']}}
                                                  @endif
                                                  {{$xval->user['first_name'].' '.$xval->user['last_name']}} 
                                                @else 
                                                  {{$lang["donation_anonymous"]}} 
                                                @endif
                                            </h4>
                                            <small>{{$currency->symbol.$xval->amount}} @ {{date("h:i:A j, M Y", strtotime($xval->created_at))}}</small>
                                            </div>
                                        </div>
                                        </li>
                                    @endforeach
                                  @else
                                    <li class="list-group-item px-0"><p class="text-sm">{{$lang["donation_no_donors"]}}</p></li>
                                  @endif
                                </ul>
                              </div>
                          </div>
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
                                <h3 class="mb-0 font-weight-bolder">{{$lang["donation_delte_payment_link"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{$lang["donation_are_you_sure_you_want_to_delete_this"]}}</span>
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