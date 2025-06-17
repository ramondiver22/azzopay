@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.profile')==url()->current()) active @endif" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fad fa-user"></i> {{$lang["profile_index_profile"]}}</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.security')==url()->current()) active @endif" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="fadse"><i class="fad fa-cogs"></i> {{$lang["profile_index_security"]}}</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.api')==url()->current()) active @endif" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="fadse"><i class="fad fa-key"></i> {{$lang["profile_index_api_keys"]}}</a>
              </li>        
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.social')==url()->current()) active @endif" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="fadse"><i class="fad fa-share-square"></i> {{$lang["profile_index_social_profile"]}}</a>
              </li>                
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.bank')==url()->current()) active @endif" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="fadse"><i class="fad fa-university"></i> {{$lang["profile_index_bank_accounts"]}}</a>
              </li>              
              <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.compliance')==url()->current()) active @endif" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="fadse"><i class="fad fa-globe"></i> {{$lang["profile_index_compliance"]}}</a>
              </li>        
          </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if(route('user.profile')==url()->current())show active @endif" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
              <div class="row">
                <div class="col-md-8">
                    <div class="card">
                      <div class="card-header header-elements-inline">
                      <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_profile"]}}</h3>
                    </div>
                    <div class="card-body">
                      <form action="{{url('user/account')}}" method="post">
                        @csrf
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_full_name"]}}</label>
                            <div class="col-lg-9">
                              <div class="row">
                                  <div class="col-6">
                                    <input type="text" name="first_name" class="form-control" placeholder="{{$lang["profile_index_first_name"]}}" value="{{$user->first_name}}">
                                  </div>      
                                  <div class="col-6">
                                    <input type="text" name="last_name" class="form-control" placeholder="{{$lang["profile_index_last_name"]}}" value="{{$user->last_name}}">
                                  </div>
                              </div>
                            </div>
                          </div>  
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_business_name"]}}</label>
                            <div class="col-lg-9">
                              <input type="text" name="business_name" class="form-control" placeholder="Your Business Name" value="{{$user->business_name}}" required>
                              <span class="form-text text-xs">{{$lang["profile_index_your_business_name_is_the_official_name"]}}</span>
                            </div>
                          </div>   
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_phone_number"]}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-phone-alt"></i></span>
                                </div>
                                <input type="number" inputmode="numeric" name="phone" maxlength="14" class="form-control" value="{{$user->phone}}">
                              </div>
                            </div>
                          </div>     
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_email_address"]}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control" placeholder="{{$lang["profile_index_email_address"]}}" value="{{$user->email}}">
                              </div>
                            </div>
                          </div>                          
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_sporte_email"]}}</label>
                            <div class="col-lg-9">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fad fa-envelope"></i></span>
                                </div>
                                <input type="email" name="support_email" class="form-control" placeholder="{{$lang["profile_index_email_address"]}}" value="{{$user->support_email}}">
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-form-label col-lg-3">{{$lang["profile_index_country"]}}</label>
                            <div class="col-lg-9">
                              <select class="form-control select" disabled name="country" required>
                                  <option value="">{{$lang["profile_index_select_country"]}}</option> 
                                    @foreach($country as $val)
                                      <option value="{{$val->country_id}}" @if($val->country_id==$user->country) selected @endif>{{$val->real['nicename']}}</option>
                                    @endforeach
                              </select>
                            </div>
                          </div>                               
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-sm">{{$lang["profile_index_save_changes"]}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_business_logo"]}}</h3>
                      <p class="card-text text-sm">{{$lang["profile_index_we_recommend_you_use_square_logo"]}}</p>
                      <a href="#" class="avatar text-center">
                        <img src="{{url('/')}}/asset/profile/{{$cast}}"/>
                      </a>
                      <form action="{{url('user/avatar')}}" enctype="multipart/form-data" method="post">
                      @csrf
                          <div class="form-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="customFileLang" name="image" accept="image/*" required>
                              <label class="custom-file-label" for="customFileLang">{{$lang["profile_index_choose_media"]}}</label>
                            </div> 
                          </div>              
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_index_change_logo"]}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body text-center">
                      <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_delete_account"]}}</h3>
                      <p class="card-text text-sm mb-2">{{$lang["profile_index_closing_this_account_means"]}} {{$set->site_name}}</p>
                      <div class="text-right">
                          <a data-toggle="modal" data-target="#modal-formp" href="" class="btn btn-danger btn-block"><i class="fa fa-trash"></i> {{$lang["profile_index_delete_account"]}}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade @if(route('user.security')==url()->current())show active @endif" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
              <div class="card">
                <div class="card-header">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_password"]}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('change.password')}}" method="post">
                    @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="password" class="form-control" placeholder="{{$lang["profile_index_current_password"]}}" required>
                      </div>
                  </div>                
                  <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{$lang["profile_index_new_password"]}}" required>
                          <span class="error form-error-msg"><span id="result"></span></span>
                          
                      </div>
                  </div>                
                  <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{$lang["profile_index_confirm_password"]}}" required>
                          <span class="error form-error-msg" id="msg"></span>
                      </div>
                  </div> 
                  <h4 class="mb-0 text-dark font-weight-bolder">{{$lang["profile_index_password_requirements"]}}</h4>
                  <p class="mb-2 text-default text-sm">{{$lang["profile_index_ensure_taht_these_requirements"]}}</p>
                  <ul class="text-default text-sm">
                    <li>{{$lang["profile_index_minimum_eight_characters_long"]}}</li>
                    <li>{{$lang["profile_index_at_least_one_lowercase"]}}</li>
                    <li>{{$lang["profile_index_at_least_one_uppercase"]}}</li>
                    <li>{{$lang["profile_index_at_least_one_number_symbol"]}}</li>
                  </ul>           
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-sm">{{$lang["profile_index_change_password"]}}</button>
                  </div>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_two_factor_security_option"]}}</h3>
                </div>
                <div class="card-body">
                  <div class="align-item-sm-center flex-sm-nowrap text-left">
                    <p class="text-sm mb-2">
                      {{$lang["profile_index_two_factor_authentication_is_a_method"]}}
                    </p>
                    <span class="badge badge-pill badge-primary mb-3">
                      @if($user->fa_status==0)
                      {{$lang["profile_index_disabled"]}}
                      @else
                      {{$lang["profile_index_active"]}}
                      @endif
                    </span>
                    <ul class="text-default text-sm">
                      <li>{{$lang["profile_index_install_an_authentication_app_on_your_device"]}}</li>
                      <li>{{$lang["profile_index_use_the_authentcator_app_to_scan"]}}</li>
                      <li>{{$lang["profile_index_enter_the_code_generated_by_the_authenticator_app"]}}</li>
                    </ul>
                    <a data-toggle="modal" data-target="#modal-form2fa" href="" class="btn btn-neutral btn-sm">
                    @if($user->fa_status==0)
                      {{$lang["profile_index_enable_2fa"]}}
                    @elseif($user->fa_status==1)
                      {{$lang["profile_index_disable_2fa"]}}
                    @endif
                    </a>
                      <div class="modal fade" id="modal-form2fa" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-body text-center">
                              @if($user->fa_status==0)
                                <img src="{{$image}}" class="mb-3 user-profile">
                              @endif
                              <form action="{{route('change.2fa')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                  <div class="col-lg-12">
                                    <input type="text" pattern="\d*" name="code" class="form-control" minlength="6" maxlength="6" placeholder="{{$lang["profile_index_six_digit_code"]}}" required>
                                      <input type="hidden"  name="vv" value="{{$secret}}">
                                    @if($user->fa_status==0)
                                      <input type="hidden"  name="type" value="1">
                                    @elseif($user->fa_status==1)
                                      <input type="hidden"  name="type" value="0">
                                    @endif
                                  </div>
                                </div>            
                                <div class="text-right">
                                  <button type="submit" class="btn btn-neutral btn-block">
                                  @if($user->fa_status==0)
                                    {{$lang["profile_index_enable_2fa"]}}
                                  @elseif($user->fa_status==1)
                                    {{$lang["profile_index_disable_2fa"]}}
                                  @endif
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div> 
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade @if(route('user.api')==url()->current())show active @endif" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_api_keys"]}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('generateapi')}}" method="post">
                    @csrf
                    <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{$lang["profile_index_public_key"]}}</span>
                      </div> 
                      <input type="text" name="public_key" class="form-control" placeholder="Public key" value="{{$user->public_key}}">   
                      <div class="input-group-prepend">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->public_key}}" title="Copy"><i class="fad fa-copy"></i></span>
                      </div> 
                    </div>
                  </div>
                </div>                
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{$lang["profile_index_secret_key"]}}</span>
                      </div> 
                      <input type="text" name="secret_key" class="form-control" placeholder="Secret key" value="{{$user->secret_key}}">   
                      <div class="input-group-prepend">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->secret_key}}" title="Copy"><i class="fad fa-copy"></i></span>
                      </div> 
                    </div> 
                  </div>
                </div>                                    
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-sm">{{$lang["profile_index_generate_new_keys"]}}</button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="card">
                <div class="card-header header-elements-inline">
                  <h3 class="mb-0 font-weight-bolder">Cadastro de URL para Callback</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Cadastre uma URL caso você deseje que o sistema envie notificações sobre o status de suas transações.</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                              <span class="input-group-text text-xs text-uppercase" for="callback-url">URL</span>
                          </div> 
                          <input type="text" name="callback-url" id="callback-url" class="form-control" placeholder="https://minhaurldecallback.com.br/onnixpay/callbacks" value="{{$user->callback_url}}">   
                          <div class="input-group-prepend">
                            <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->callback_url}}" title="Copy"><i class="fad fa-copy"></i></span>
                          </div> 
                        </div>
                    </div>                
                                           
                    <div class="text-right">
                        <button type="button" class="btn btn-neutral btn-sm" id="callback-url-btn-confirm" onclick="updateCallbackUrl();">Atualizar URL de Callback</button>
                    </div>
                    
                </div>
              </div>

            </div>      
            <div class="tab-pane fade @if(route('user.social')==url()->current())show active @endif" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_social_link"]}}</h3>
                </div>
                <div class="card-body">
                  <form action="{{route('user.social')}}" method="post">
                    @csrf
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_facebook"]}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="facebook" class="form-control" placeholder="{{$lang["profile_index_your_facebook_profile_link"]}}" value="{{$user->facebook}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_twitter"]}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="twitter" class="form-control" placeholder="{{$lang["profile_index_your_twitter_profile_link"]}}" value="{{$user->twitter}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_instagram"]}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="instagram" class="form-control" placeholder="{{$lang["profile_index_your_instagram_profile_link"]}}" value="{{$user->instagram}}">    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_linkedin"]}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="linkedin" class="form-control" placeholder="{{$lang["profile_index_your_linkedin_profile_link"]}}" value="{{$user->linkedin}}">    
                      </div>
                    </div>               
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_youtube"]}}</label>
                      <div class="col-lg-10">
                        <input type="url" name="youtube" class="form-control" placeholder="{{$lang["profile_index_your_youtube_channel_link"]}}" value="{{$user->youtube}}">    
                      </div>
                    </div>                     
                    <div class="text-right">
                      <button type="submit" class="btn btn-neutral btn-sm">{{$lang["profile_index_save_changes"]}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>            
            <div class="tab-pane fade @if(route('user.bank')==url()->current())show active @endif" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
              @if($set->stripe_connect==0)
                <div class="row align-items-center py-4">
                  <div class="col-lg-6 col-5 text-left">
                    <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["profile_index_bank_account"]}}</a>
                  </div>
                </div>
              @endif
              <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_add_bank_account"]}}</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form role="form" action="{{route('submit.bank')}}" method="post"> 
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-control select" name="bank" required>
                                    <option value="">{{$lang["profile_index_select_bank"]}}</option> 
                                        @foreach($bnk as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <input type="text" name="acct_name" class="form-control" placeholder="{{$lang["profile_index_account_name"]}}" required>
                          </div>
                        </div>     
                                                                                         
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <input type="text" name="agency_no" pattern="\d*" maxlength="12" placeholder="{{$lang["profile_newsub_agency_number"]}}" class="form-control" required>
                          </div>
                        </div>    
                        
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <input type="text" name="acct_no" pattern="\d*" maxlength="12" placeholder="{{$lang["profile_index_account_no"]}}" class="form-control" required>
                          </div>
                        </div>    
                        
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <input type="text" name="account_document" pattern="\d*" maxlength="20" placeholder="{{$lang["profile_newsub_account_document"]}}" class="form-control" required>
                          </div>
                        </div>   
                        
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-control select" name="account_type" required>
                                    <option value="">{{$lang["profile_index_account_type"]}}</option> 
                                      <option value="individual">{{$lang["profile_index_individual"]}}</option>
                                      <option value="company">{{$lang["profile_index_company"]}}</option>
                                </select>
                            </div>
                        </div>  
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">#</span>
                            </div>
                            <input class="form-control" placeholder="{{$lang["profile_index_routing_number_sort_code"]}}" type="text" name="routing_number" required>
                          </div>
                        </div>                 
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_index_save"]}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                @if(count($bank)>0) 
                  @foreach($bank as $k=>$val)
                    <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <div class="row mb-2">
                              <div class="col-6">
                                <h3 class="mb-0 font-weight-bolder">{{$val->dabank['name']}}</h3>
                              </div>
                              <div class="col-6 text-right">
                                <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                  <i class="fad fa-chevron-circle-down"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left">
                                @if($val->status==0)
                                  <a class="dropdown-item" href="{{route('bank.default', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["profile_index_default"]}}</a>
                                @endif
                                  <a class="dropdown-item" data-toggle="modal" data-target="#modal-form{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{$lang["profile_index_edit"]}}</a>
                                  <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["profile_index_delete"]}}</a>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{$lang["profile_index_default_account"]}}: @if($val->status==1) {{$lang["profile_index_yes"]}} @else {{$lang["profile_index_no"]}} @endif</p>
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{$lang["profile_index_account_name"]}}: {{$val->acct_name}}</p>
                                <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{$lang["profile_index_routing_no_sort_code"]}}: {{$val->routing_number}}</p>
                                <p class="text-sm mb-2 font-weight-bolder text-uppercase">{{$lang["profile_index_account_type"]}}: {{$val->account_type}}</p>
                                <h4 class="mb-1 h2 text-primary font-weight-bolder">{{$val->agency_no}} / {{$val->acct_no}}</h4>
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
                                          <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_delte_bank_account"]}}</h3>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                          <span class="mb-0 text-xs">{{$lang["profile_index_are_you_sure_you_want_to_delete_bank_account"]}}</span>
                                        </div>
                                        <div class="card-body">
                                            <a  href="{{route('bank.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["profile_index_proceed"]}}</a>
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
                            <h3 class="mb-0">{{$lang["profile_index_edit_bank"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form role="form" action="{{route('bank.edit')}}" method="post"> 
                              @csrf
                              <div class="form-group row">
                                  <div class="col-lg-12">
                                      <select class="form-control select" name="bank" required>
                                          <option value="">{{$lang["profile_index_select_bank"]}}</option> 
                                              @foreach($bnk as $xval)
                                              <option value="{{$xval->id}}" @if($xval->id==$val->bank_id) selected @endif>{{$xval->name}}</option>
                                              @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-lg-12">
                                    <select class="form-control select" name="account_type" required>
                                        <option value="">{{$lang["profile_index_account_type"]}}</option> 
                                          <option value="individual" @if($val->account_type=='individual') selected @endif>{{$lang["profile_index_individual"]}}</option>
                                          <option value="company" @if($val->account_type=='company') selected @endif>{{$lang["profile_index_company"]}}</option>
                                    </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-lg-12">
                                  <input type="text" name="acct_name"  class="form-control" placeholder="{{$lang["profile_index_account_name"]}}" value="{{$val['acct_name']}}">
                                </div>
                              </div>    
                              
                              <div class="form-group row">
                                <div class="col-lg-12">
                                  <input type="number" name="agency_no" placeholder="{{$lang["profile_newsub_agency_number"]}}" class="form-control" value="{{$val['agency_no']}}">
                                  <input type="hidden" name="agency" value="{{$val['agency']}}">
                                </div>
                              </div> 
                              
                              <div class="form-group row">
                                <div class="col-lg-12">
                                  <input type="number" name="acct_no"  placeholder="{{$lang["profile_index_account_no"]}}" class="form-control" value="{{$val['acct_no']}}">
                                  <input type="hidden" name="id" value="{{$val['id']}}">
                                </div>
                              </div>    
                              
                              
                              <div class="form-group row">
                                <div class="col-lg-12">
                                  <input type="number" name="account_document"  placeholder="{{$lang["profile_newsub_account_document"]}}" class="form-control" value="{{$val['account_document']}}">
                                  <input type="hidden" name="document" value="{{$val['document']}}">
                                </div>
                              </div> 
                              
                              
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                  </div>
                                  <input class="form-control" placeholder="{{$lang["profile_index_routing_number"]}}" type="text" name="routing_number" value="{{$val['routing_number']}}" required>
                                </div>
                              </div>                
                              <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_index_update_account"]}}</button>
                              </div>
                            </form>
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
                      <h3 class="text-dark">{{$lang["profile_index_no_bank_account"]}}</h3>
                      <p class="text-dark text-sm card-text">{{$lang["profile_index_we_couldnt_find_any_bank_account_to_this_account"]}}</p>
                    </div>
                  </div>
                @endif
              </div>
              <div class="row">
                <div class="col-md-12">
                {{ $bank->links('pagination::bootstrap-4') }}
                </div>
              </div>
            </div>
            
            
            <div class="tab-pane fade @if(route('user.compliance')==url()->current())show active @endif" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
              <form action="{{route('submit.compliance')}}" method="post" enctype="multipart/form-data">
                <div class="card">
                  <div class="card-header header-elements-inline">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_compliance"]}}</h3>
                  </div>
                  <div class="card-body">
                    @csrf
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_business_name"]}}</label>
                      <div class="col-lg-10">
                        <input type="text" name="trading_name" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" value="{{$ver->trading_name}}" required>    
                      </div>
                    </div>                
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_description"]}}</label>
                      <div class="col-lg-10">
                        <textarea type="text" name="trading_desc" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" required>{{$ver->description}}</textarea>  
                      </div>
                    </div>   
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_staff_size"]}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="staff_size" @if($ver->status==1 || $user->business_level==3) disabled @endif required>
                              <option value="1-5" @if($ver->staff_size=="1-5") selected @endif>1-5 {{$lang["profile_index_people"]}}</option> 
                              <option value="5-50" @if($ver->staff_size=="5-50") selected @endif>5-50 {{$lang["profile_index_people"]}}</option> 
                              <option value="50+" @if($ver->staff_size=="50+") selected @endif>50+ {{$lang["profile_index_people"]}}</option> 
                          </select>
                      </div>
                    </div> 
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_industry"]}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="industry" @if($ver->status==1 || $user->business_level==3) disabled @endif id="industry" required>
                          </select>
                          <span class="text-xs text-gray">{{$lang["profile_index_current_category"]}}: {{$ver->industry}}</span>
                      </div>
                    </div>                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_category"]}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="category" @if($ver->status==1 || $user->business_level==3) disabled @endif id="category" required>
                          </select>
                          <span class="text-xs text-gray">{{$lang["profile_index_current_category"]}}: {{$ver->category}}</span>
                      </div>
                    </div> 
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_complience_mouth_revenue"]}}</label>
                      <div class="col-lg-10">
                          <input type="number" name="mouth_revenue"  class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->mouth_revenue}}">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_complience_patrimony"]}}</label>
                      <div class="col-lg-10">
                          <input type="number" name="patrimony"   class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->patrimony}}">
                      </div>
                    </div>
                    
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_complience_address_type"]}}</label>
                      <div class="col-lg-10">
                        <select class="form-control select" name="address_type" @if($ver->status==1 || $user->business_level==3) disabled @endif required>
                            <option value="COMERCIAL" @if($ver->address_type=="COMERCIAL") selected @endif>{{$lang["profile_complience_address_type_business"]}}</option> 
                            <option value="RESIDENTIAL" @if($ver->address_type=="RESIDENTIAL") selected @endif>{{$lang["profile_complience_address_type_residential"]}}</option> 
                        </select>
                      </div>
                    </div>  
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_complience_zipcode"]}}</label>
                      <div class="col-lg-10">
                          <input type="text" name="address_zipcode" pattern="\d*" maxlength="10" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address_zipcode}}">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_address"]}}</label>
                      <div class="col-lg-10">
                          <input type="text" name="address" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address}}">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_address_number"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="address_number"  maxlength="10" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address_number}}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_address_complement"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="address_complement"  maxlength="40" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address_complement}}">
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_neighborhood"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="neighborhood"  maxlength="40" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->neighborhood}}">
                        </div>
                    </div>
                    
                    
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_address_city"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="address_city"  maxlength="40" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address_city}}">
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_address_state"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="address_state"  maxlength="2" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address_state}}">
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_complience_phone_type"]}}</label>
                      <div class="col-lg-10">
                        <select class="form-control select" name="phone_type" @if($ver->status==1 || $user->business_level==3) disabled @endif required>
                            <option value="CELULAR" @if($ver->phone_type=="CELULAR") selected @endif>{{$lang["profile_complience_phone_type_cellphone"]}}</option> 
                            <option value="COMERCIAL" @if($ver->phone_type=="COMERCIAL") selected @endif>{{$lang["profile_complience_phone_type_business"]}}</option> 
                            <option value="RESIDENCIAL" @if($ver->phone_type=="RESIDENCIAL") selected @endif>{{$lang["profile_complience_phone_type_residential"]}}</option> 
                        </select>
                      </div>
                    </div>  
                    
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_phone"]}}</label>
                      <div class="col-lg-3">
                          <input type="number" name="phone_country_code" maxlength="2" placeholder="{{$lang["profile_complience_phone_country_code"]}}" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->phone_country_code}}">
                      </div>
                      <div class="col-lg-7">
                          <input type="number" name="phone"  placeholder="{{$lang["profile_complience_phone_placeholder"]}}" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->phone}}">
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_email"]}}</label>
                      <div class="col-lg-10">
                          <input type="email" name="email" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->email}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_website"]}}</label>
                      <div class="col-lg-10">
                          <input type="url" name="website" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->website}}">
                      </div>
                    </div>  
                    <div class="form-group row">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_business_type"]}}</label>
                      <div class="col-lg-10">
                        <select class="form-control select" name="business_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="seeAnotherField" required>
                            <option value="Starter Business" @if($ver->business_type=="Starter Business") selected @endif>{{$lang["profile_index_starter_business"]}}</option> 
                            <option value="Registered Business" @if($ver->business_type=="Registered Business") selected @endif>{{$lang["profile_index_registered_business"]}}</option> 
                        </select>
                        <span class="text-xs text-gray">{{$lang["profile_index_starter_business_can_only_accept_up_to"]}} {{$currency->name.number_format($set->starter_limit)}} {{$lang["profile_index_without_business_registration_documents"]}}</span>
                      </div>
                    </div>     
                    <div class="form-group row" id="otherFieldDiv">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_legal_business_name"]}}</label>
                      <div class="col-lg-10">
                        <input type="text" name="legal_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->legal_name}}" id="otherField">    
                      </div>
                    </div>                        
                    <div class="form-group row" id="6xxotherFieldDiv">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_tax_id"]}}</label>
                        <div class="col-lg-10">
                            <input type="text" name="tax_id" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->tax_id}}"  id="6xxotherField">
                        </div>
                    </div>                          
                    
                    <div class="form-group row" id="otherFieldDiv1">
                      <label class="col-form-label col-lg-2">{{$lang["profile_index_registration_type"]}}</label>
                      <div class="col-lg-10">
                          <select class="form-control select" name="registration_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="otherField1">
                              <option value="government_instrumentality" @if($ver->registration_type=="government_instrumentality") selected @endif>{{$lang["profile_index_government_instrumentality"]}}</option> 
                              <option value="governmental_unit" @if($ver->registration_type=="governmental_unit") selected @endif>{{$lang["profile_index_governmental_unit"]}}</option> 
                              <option value="incorporated_non_profit" @if($ver->registration_type=="incorporated_non_profit") selected @endif>{{$lang["profile_index_incorporated_non_profit"]}}</option> 
                              <option value="limited_liability_partnership" @if($ver->registration_type=="limited_liability_partnership") selected @endif>{{$lang["profile_index_limited_liability_partnership"]}}</option> 
                              <option value="multi_member_llc" @if($ver->registration_type=="multi_member_llc") selected @endif>{{$lang["profile_index_multi_member_llc"]}}</option> 
                              <option value="private_company" @if($ver->registration_type=="private_company") selected @endif>{{$lang["profile_index_private_company"]}}</option> 
                              <option value="private_corporation" @if($ver->registration_type=="private_corporation") selected @endif>{{$lang["profile_index_private_corporation"]}}</option> 
                              <option value="private_partnership" @if($ver->registration_type=="private_partnership") selected @endif>{{$lang["profile_index_private_partnership"]}}</option> 
                              <option value="public_company" @if($ver->registration_type=="public_company") selected @endif>{{$lang["profile_index_public_company"]}}</option> 
                              <option value="public_corporation" @if($ver->registration_type=="public_corporation") selected @endif>{{$lang["profile_index_public_corporation"]}}</option> 
                              <option value="public_partnership" @if($ver->registration_type=="public_partnership") selected @endif>{{$lang["profile_index_public_partnership"]}}</option> 
                              <option value="single_member_llc" @if($ver->registration_type=="single_member_llc") selected @endif>{{$lang["profile_index_single_member_llc"]}}</option> 
                              <option value="sole_proprietorship" @if($ver->registration_type=="sole_proprietorship") selected @endif>{{$lang["profile_index_sole_proprietorship"]}}</option> 
                              <option value="tax_exempt_government_instrumentality" @if($ver->registration_type=="tax_exempt_government_instrumentality") selected @endif>{{$lang["profile_index_tax_eempt_government_instrumentality"]}}</option> 
                              <option value="unincorporated_association" @if($ver->registration_type=="unincorporated_association") selected @endif>{{$lang["profile_index_unincorporated_association"]}}</option> 
                              <option value="unincorporated_non_profit" @if($ver->registration_type=="unincorporated_non_profit") selected @endif>{{$lang["profile_index_unincorporated_non_profit"]}}</option> 
                          </select>
                      </div>
                    </div>    
                    @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row" id="otherFieldDiv2">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_proof_of_registration"]}}</label>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLangx" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof" accept="image/*">
                            <label class="custom-file-label" for="customFileLangx">{{$lang["profile_index_front"]}}</label>
                          </div> 
                        </div>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsc" id="customFileLang4" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof_back" accept="image/*">
                            <label class="custom-file-label sdsc" for="customFileLang4">{{$lang["profile_index_back"]}}</label>
                          </div> 
                        </div>
                      </div> 
                    @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof}}">{{$lang["profile_index_view_proof_of_registration_front"]}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof_back}}">{{$lang["profile_index_view_proof_of_registration_back"]}}</a><br>
                    @endif                      
                    @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_proof_of_address"]}}</label>
                        <div class="col-lg-10">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsx" id="customFileLang2" @if($ver->status==1 || $user->business_level==3) disabled @endif name="paddress" accept="image/*" required>
                            <label class="custom-file-label sdsx" for="customFileLang2">{{$lang["profile_index_select_document"]}}</label>
                          </div> 
                        </div>
                      </div> 
                    @else
                      <a href="{{url('/')}}/asset/profile/{{$ver->paddress}}">{{$lang["profile_index_view_proof_of_address"]}}</a><br>
                    @endif 
                    <div id="otherFieldDiv3">
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_full_name"]}}</label>
                        <div class="col-lg-10">
                          <div class="row">
                              <div class="col-6">
                                <input type="text" name="first_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif  value="{{$ver->first_name}}" placeholder="{{$lang["profile_complience_first_name"]}}" id="1otherField">
                              </div>      
                              <div class="col-6">
                                <input type="text" name="last_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->last_name}}" placeholder="{{$lang["profile_complience_last_name"]}}" id="2otherField">
                              </div>
                          </div>
                        </div>
                      </div>  
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_data_of_birth"]}}</label>
                        <div class="col-lg-10">
                          <div class="row">
                              <div class="col-4">
                                <select class="form-control select" name="b_month" @if($ver->status==1 || $user->business_level==3) disabled @endif id="3otherField">
                                  <option value="1" @if($ver->month=="1") selected @endif>{{$lang["profile_index_jan"]}}</option>
                                  <option value="2" @if($ver->month=="2") selected @endif>{{$lang["profile_index_feb"]}}</option>
                                  <option value="3" @if($ver->month=="3") selected @endif>{{$lang["profile_index_mar"]}}</option>
                                  <option value="4" @if($ver->month=="4") selected @endif>{{$lang["profile_index_apr"]}}</option>
                                  <option value="5" @if($ver->month=="5") selected @endif>{{$lang["profile_index_may"]}}</option>
                                  <option value="6" @if($ver->month=="6") selected @endif>{{$lang["profile_index_jun"]}}</option>
                                  <option value="7" @if($ver->month=="7") selected @endif>{{$lang["profile_index_jul"]}}</option>
                                  <option value="8" @if($ver->month=="8") selected @endif>{{$lang["profile_index_aug"]}}</option>
                                  <option value="9" @if($ver->month=="9") selected @endif>{{$lang["profile_index_sep"]}}</option>
                                  <option value="10" @if($ver->month=="10") selected @endif>{{$lang["profile_index_oct"]}}</option>
                                  <option value="11" @if($ver->month=="11") selected @endif>{{$lang["profile_index_nov"]}}</option>
                                  <option value="12" @if($ver->month=="12") selected @endif>{{$lang["profile_index_dec"]}}</option> 
                                </select>
                              </div>      
                              <div class="col-4">
                                <input type="number" name="b_day" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="{{$lang["profile_index_day"]}}" value="{{$ver->day}}" min="1" max="30" value="{{$user->last_name}}" id="4otherField">
                              </div>                            
                              <div class="col-4">
                                <input type="number" name="b_year" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="{{$lang["profile_index_year"]}}" value="{{$ver->year}}" min="1950" max="{{date('Y')}}" id="5otherField">
                              </div>
                          </div>
                        </div>
                      </div>  
                      <div class="form-group row"> 
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_nationality"]}}</label>                          
                        <div class="col-lg-10">
                            <select class="form-control custom-select" name="nationality" @if($ver->status==1 || $user->business_level==3) disabled @endif id="country" id="7otherField">
                            </select>
                            <span class="text-xs text-gray">{{$lang["profile_index_current_nationality"]}}: {{$ver->nationality}}</span>
                        </div>
                      </div>  
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_gender"]}}</label>                          
                        <div class="col-lg-10">
                          <select class="form-control select" name="gender" @if($ver->status==1 || $user->business_level==3) disabled @endif id="70otherField">
                            <option value="male" @if($ver->gender=="male") selected @endif>{{$lang["profile_index_male"]}}</option>
                            <option value="female" @if($ver->gender=="female") selected @endif>{{$lang["profile_index_female"]}}</option>
                          </select>
                        </div>                            
                      </div>  
                        
                        
                        
                        <div class="form-group row" >
                            <label class="col-form-label col-lg-2">{{$lang["profile_complience_mother_name"]}}</label>
                            <div class="col-lg-10">
                                <input type="text" maxlength="40" name="mother_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->mother_name}}"  >
                            </div>
                        </div> 
                        
                        
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_index_id_document"]}}</label>                          
                        <div class="col-lg-10">
                          <select class="form-control select" name="id_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="7otherField">
                            <option value="National ID" @if($ver->id_type=="National ID") selected @endif>{{$lang["profile_index_national_id"]}}</option>
                            <option value="International Passport" @if($ver->id_type=="International Passport") selected @endif>{{$lang["profile_index_international_passport"]}}</option>
                            <option value="Voters Card" @if($ver->id_type=="Voters Card") selected @endif>{{$lang["profile_index_voters_card"]}}</option>
                            <option value="Driver License" @if($ver->id_type=="Driver License") selected @endif>{{$lang["profile_index_driver_license"]}}</option>
                          </select>
                        </div>                            
                      </div>
                        
                        
                        <div class="form-group row" >
                            <label class="col-form-label col-lg-2">{{$lang["profile_complience_document"]}}</label>
                            <div class="col-lg-10">
                                <input type="text" name="document" maxlength="20" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->document}}"  id="fField">
                            </div>
                        </div> 
                        
                        
                      @if($ver->status==0 || $user->business_level==2) 
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang1" name="idcard" accept="image/*">
                              <label class="custom-file-label sdsd" for="customFileLang1">{{$lang["profile_index_front"]}}</label>
                            </div> 
                          </div>
                        </div>                        
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang3" name="idcard_back" accept="image/*">
                              <label class="custom-file-label tests" for="customFileLang3">{{$lang["profile_index_back"]}}</label>
                            </div> 
                          </div>
                        </div>
                      @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard}}">{{$lang["profile_index_view_identity_document_front"]}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard_back}}">{{$lang["profile_index_view_indentity_document_back"]}}</a><br>
                      @endif                                                                                                                    
                  </div>
                  <div class="text-center">
                      @if($ver->status==0 || $ver->status==3)    
                        <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_index_submit_compliance_for_review"]}}</button>
                      @elseif($ver->status==1)
                        <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{$lang["profile_index_under_review"]}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==2)
                        <button type="submit" class="btn btn-neutral btn-block mb-5">{{$lang["profile_index_update_compliance"]}}</button>
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_index_approved"]}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==3)
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_index_approved"]}}</span>
                      @endif
                  </div> 
                </div>  
              </form>
            </div>   
          </div>
        </div>
      </div> 
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formp" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["profile_index_delete_account"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('delaccount')}}" method="post">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <textarea type="text" name="reason" class="form-control" rows="5" placeholder="{{$lang["profile_index_sorry_to_se_you_leave"]}}" required></textarea>
                    </div>
                  </div>             
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_index_delete_account"]}}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">

          </div>
        </div> 
      </div>
    </div>
@stop




@push('scripts')
<script>
 
    function updateCallbackUrl() {

        let data = {
            _token: "{{ csrf_token() }}",
            url: $("#callback-url").val()
        }

        $("#callback-url-btn-confirm").prop("disabled", true);
        $.post("{{route('register.callback.url')}}", data, function (json) {
            try {
                if (json.success) {
                  
                    toastr.success(json.message);
                    
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#callback-url-btn-confirm").prop("disabled", false);
        });
    }

</script>
@endpush