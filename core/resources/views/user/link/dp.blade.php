@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-4">
        <h6 class="h2 d-inline-block mb-0">{{$lang["link_donation"]}}</h6>
      </div>
      <div class="col-8 text-right">
        <a data-toggle="modal" data-target="#donation-page" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["link_create_payment_link"]}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">   
        <div class="modal fade" id="donation-page" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["link_create_new_payment_link"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.donationpage')}}" enctype="multipart/form-data" method="post" id="modal-detailx">
                  @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="text" name="name" class="form-control" placeholder="{{$lang["link_payment_link_name"]}}" required>
                          <span class="form-text text-xs">{{$lang["link_create_donation_page"]}} {{$userTax->donation_charge}}% {{$lang["link_per_donation"]}}</span>
                      </div>
                    </div>  
                    <div class="form-group row">
                      <label class="col-form-label col-lg-12">{{$lang["link_goal"]}}</label>
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                              <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="goal" placeholder="0.00" required>
                        </div>
                      </div>
                    </div>  
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="customFileLang" name="image" accept="image/*" required>
                          <label class="custom-file-label" for="customFileLang">{{$lang["link_image"]}}</label>
                          <span class="form-text text-xs">{{$lang["link_recomended_image_size"]}}</span>
                        </div> 
                      </div>    
                    </div>    
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <textarea type="text" name="description" placeholder="{{$lang["link_description"]}}" rows="4" class="form-control" required></textarea>
                      </div>
                    </div>   
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-detailx">{{$lang["link_create_link"]}}</button>
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
                <div class="card">
                  <img class="card-img-top" src="{{url('/')}}/asset/profile/{{$val->image}}" alt="{{$lang["link_image_placeholder"]}}">
                  @php 
                  $donors=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->latest()->get();
                  $donated=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->sum('amount');
                  @endphp
                    <div class="card-body">
                      <div class="row mb-2">
                        <div class="col-4">
                          <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('dpview.link', ['id' => $val->ref_id])}}" title="Copy">{{$lang["link_copy_link"]}} <i class="fad fa-link text-xs"></i></a></p>
                        </div>
                        <div class="col-8 text-right">
                          <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                            <i class="fad fa-chevron-circle-down"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-left">
                            @if($val->active==1)
                                <a class='dropdown-item' href="{{route('dplinks.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{$lang["link_disable"]}}</a>
                            @else
                                <a class='dropdown-item' href="{{route('dplinks.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["link_activate"]}}</a>
                            @endif
                            <a class="dropdown-item" href="{{route('user.sclinkstrans', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{$lang["link_transactions"]}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#donors{{$val->id}}" href="#"><i class="fad fa-user"></i>{{$lang["link_donors"]}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{$lang["link_edit"]}}</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["link_delete"]}}</a>
                          </div>
                        </div>                        
                      </div>
                      <div class="row mb-3">
                        <div class="col-12">
                          <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                          <p>{{$lang["link_reference"]}}: {{$val->ref_id}}</p>
                          <p>{{$lang["link_donors"]}}: ({{count($donors)}})</p>
                          <p>{{$lang["link_amount"]}}: {{$currency->symbol.number_format($donated, 2, '.', '')}}/{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                          <p class="text-sm mb-2">{{$lang["link_date"]}}: {{date("d/m/Y H:i:s", strtotime($val->created_at))}}</p>
                          @if($val->active==1)
                              <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["link_date"]}}</span>
                          @else
                              <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["link_disabled"]}}</span>
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
                      <h3 class="mb-0 font-weight-bolder">{{$lang["link_edit_payment_link"]}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="card-body">
                      <form action="{{route('update.dplinks')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group row">
                          <div class="col-lg-12">
                              <input type="text" name="name" class="form-control" value="{{$val->name}}" placeholder="{{$lang["link_payment_link_name"]}}" required>
                              <span class="form-text text-xs">{{$lang["link_edit_donation_page"]}} {{$userTax->donation_charge}}% {{$lang["link_per_donation"]}}</span>
                          </div>
                        </div>  
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["link_goal"]}}</label>
                          <div class="col-lg-12">
                            <div class="input-group">
                              <span class="input-group-prepend">
                                  <span class="input-group-text">{{$currency->symbol}}</span>
                              </span>
                              <input type="number" step="any" class="form-control" name="goal" value="{{$val->amount}}" min="{{$donated}}" placeholder="0.00" required>
                            </div>
                          </div>
                        </div>  
                        <div class="form-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLang" name="image" accept="image/*">
                            <label class="custom-file-label" for="customFileLang">{{$lang["link_image"]}}</label>
                            <span class="form-text text-xs">{{$lang["link_recomended_image_size"]}}</span>
                          </div> 
                        </div> 
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <textarea type="text" name="description" rows="4" class="form-control" placeholder="{{$lang["link_description"]}}" required>{{$val->description}}</textarea>
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
                                                  <div class="icon icon-shape text-success rounded-circle bg-white">
                                                      <i class="fad fa-gift"></i>
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
                                                      {{$lang["link_anonymous"]}} 
                                                    @endif
                                                </h4>
                                                <small>{{$currency->symbol.$xval->amount}} @ {{date("h:i:A j, M Y", strtotime($xval->created_at))}}</small>
                                                </div>
                                            </div>
                                            </li>
                                        @endforeach
                                      @else
                                        <li class="list-group-item px-0"><p class="text-sm">{{$lang["link_do_donors"]}}</p></li>
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
                                    <h3 class="mb-1 font-weight-bolder">{{$lang["link_delte_payment_link"]}}</h3>
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