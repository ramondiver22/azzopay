@extends('loginlayout')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="header py-5 pt-7">
      <div class="container">
        <div class="header-body text-center mb-7">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-7">
          <div class="card border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <h3 class="text-dark font-weight-bolder">{{$lang["profile_complience_title"]}}</h3>
                <span class="text-gray text-xs">{{$lang["profile_complience_verify_your_business"]}}</span>
              </div>
              <form action="{{route('submit.compliance')}}" method="post" enctype="multipart/form-data">
                <div class="">
                  <div class="">
                      @csrf
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_business_name"]}}</label>
                        <div class="col-lg-10">
                          <input type="text" name="trading_name" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" value="{{$ver->trading_name}}" required>    
                        </div>
                      </div>                
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_description"]}}</label>
                        <div class="col-lg-10">
                          <textarea type="text" name="trading_desc" @if($ver->status==1 || $user->business_level==3) disabled @endif class="form-control" required>{{$ver->description}}</textarea>  
                        </div>
                      </div>   
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_staff_size"]}}</label>
                        <div class="col-lg-10">
                            <select class="form-control select" name="staff_size" @if($ver->status==1 || $user->business_level==3) disabled @endif required>
                                <option value="1-5" @if($ver->staff_size=="1-5") selected @endif>1-5 {{$lang["profile_complience_people"]}}</option> 
                                <option value="5-50" @if($ver->staff_size=="5-50") selected @endif>5-50 {{$lang["profile_complience_people"]}}</option> 
                                <option value="50+" @if($ver->staff_size=="50+") selected @endif>50+ {{$lang["profile_complience_people"]}}</option> 
                            </select>
                        </div>
                      </div> 


                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_industry"]}}</label>
                        <div class="col-lg-10">
                            <select class="form-control select" name="industry" @if($ver->status==1 || $user->business_level==3) disabled @endif id="industry" required>
                            </select>
                            <span class="text-xs text-gray">{{$lang["profile_complience_current_category"]}}: {{$ver->industry}}</span>
                        </div>
                      </div>                    
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_category"]}}</label>
                        <div class="col-lg-10">
                            <select class="form-control select" name="category" @if($ver->status==1 || $user->business_level==3) disabled @endif id="category" required>
                            </select>
                            <span class="text-xs text-gray">{{$lang["profile_complience_current_category"]}}: {{$ver->category}}</span>
                        </div>
                      </div> 
                      
                      <div class="form-group row">
                            <label class="col-form-label col-lg-2">{{$lang["profile_complience_address"]}}</label>
                            <div class="col-lg-10">
                                <input type="text" name="address" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->address}}">
                            </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_phone"]}}</label>
                          <div class="col-lg-10">
                              <input type="number" name="phone" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->phone}}">
                          </div>
                      </div>                      
                      <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_email"]}}</label>
                          <div class="col-lg-10">
                              <input type="email" name="email" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->email}}">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_website"]}}</label>
                          <div class="col-lg-10">
                              <input type="url" name="website" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->website}}">
                          </div>
                      </div>  
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_business_type"]}}</label>
                        <div class="col-lg-10">
                            <select class="form-control select" name="business_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="seeAnotherField" required>
                                <option value="Starter Business" @if($ver->business_type=="Starter Business") selected @endif>{{$lang["profile_complience_start_business"]}}</option> 
                                <option value="Registered Business" @if($ver->business_type=="Registered Business") selected @endif>{{$lang["profile_complience_registered_business"]}}</option> 
                            </select>
                            <span class="text-xs text-gray">{{$lang["profile_complience_starter_business_can_only_accept_up_to"]}} {{$currency->name.number_format($set->starter_limit)}} {{$lang["profile_complience_without_business_registration_documents"]}}</span>
                        </div>
                      </div>     
                      <div class="form-group row" id="otherFieldDiv">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_legal_business_name"]}}</label>
                        <div class="col-lg-10">
                          <input type="text" name="legal_name" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->legal_name}}" id="otherField">    
                        </div>
                      </div>                        
                      <div class="form-group row" id="6xxotherFieldDiv">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_tax_id"])}}</label>
                          <div class="col-lg-10">
                              <input type="text" name="tax_id" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->tax_id}}"  id="6xxotherField">
                          </div>
                      </div>                          
                      <div class="form-group row" id="fFieldDiv">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_vat_id"]}}</label>
                          <div class="col-lg-10">
                              <input type="text" name="vat_id" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->vat_id}}"  id="fField">
                          </div>
                      </div>                        
                      <div class="form-group row" id="ffFieldDiv">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_registration_no"]}}</label>
                          <div class="col-lg-10">
                              <input type="text" name="reg_no" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif value="{{$ver->reg_no}}"  id="ffField">
                          </div>
                      </div>     
                      <div class="form-group row" id="otherFieldDiv1">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_registration_type"]}}</label>
                        <div class="col-lg-10">
                            <select class="form-control select" name="registration_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="otherField1">
                                <option value="government_instrumentality" @if($ver->registration_type=="government_instrumentality") selected @endif>{{$lang["profile_complience_government_instrumentality"]}}</option> 
                                <option value="governmental_unit" @if($ver->registration_type=="governmental_unit") selected @endif>{{$lang["profile_complience_governmental_unit"]}}</option> 
                                <option value="incorporated_non_profit" @if($ver->registration_type=="incorporated_non_profit") selected @endif>{{$lang["profile_complience_incorporated_non_profit"]}}</option> 
                                <option value="limited_liability_partnership" @if($ver->registration_type=="limited_liability_partnership") selected @endif>{{$lang["profile_complience_limited_liability_partnership"]}}</option> 
                                <option value="multi_member_llc" @if($ver->registration_type=="multi_member_llc") selected @endif>{{$lang["profile_complience_multi_member_llc"]}}</option> 
                                <option value="private_company" @if($ver->registration_type=="private_company") selected @endif>{{$lang["profile_complience_private_company"]}}</option> 
                                <option value="private_corporation" @if($ver->registration_type=="private_corporation") selected @endif>{{$lang["profile_complience_private_comporation"]}}</option> 
                                <option value="private_partnership" @if($ver->registration_type=="private_partnership") selected @endif>{{$lang["profile_complience_private_partnership"]}}</option> 
                                <option value="public_company" @if($ver->registration_type=="public_company") selected @endif>{{$lang["profile_complience_public_company"]}}</option> 
                                <option value="public_corporation" @if($ver->registration_type=="public_corporation") selected @endif>{{$lang["profile_complience_public_corporation"]}}</option> 
                                <option value="public_partnership" @if($ver->registration_type=="public_partnership") selected @endif>{{$lang["profile_complience_public_partnership"]}}</option> 
                                <option value="single_member_llc" @if($ver->registration_type=="single_member_llc") selected @endif>{{$lang["profile_complience_single_member_llc"]}}</option> 
                                <option value="sole_proprietorship" @if($ver->registration_type=="sole_proprietorship") selected @endif>{{$lang["profile_complience_sole_proprietorship"]}}</option> 
                                <option value="tax_exempt_government_instrumentality" @if($ver->registration_type=="tax_exempt_government_instrumentality") selected @endif>{{$lang["profile_complience_tax_exempt_government_instrumentality"]}}</option> 
                                <option value="unincorporated_association" @if($ver->registration_type=="unincorporated_association") selected @endif>{{$lang["profile_complience_unincorporated_association"]}}</option> 
                                <option value="unincorporated_non_profit" @if($ver->registration_type=="unincorporated_non_profit") selected @endif>{{$lang["profile_complience_unincorporated_non_profit"]}}</option> 
                            </select>
                        </div>
                      </div>    
                      @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row" id="otherFieldDiv2">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_proof_of_rgistration"]}}</label>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFileLangx" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof" accept="image/*">
                            <label class="custom-file-label" for="customFileLangx">{{$lang["profile_complience_fron"]}}</label>
                          </div> 
                        </div>
                        <div class="col-lg-5">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsc" id="customFileLang4" @if($ver->status==1 || $user->business_level==3) disabled @endif name="proof_back" accept="image/*">
                            <label class="custom-file-label sdsc" for="customFileLang4">{{$lang["profile_complience_back"]}}</label>
                          </div> 
                        </div>
                      </div> 
                      @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof}}">{{$lang["profile_complience_view_proof_of_registration_front"]}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->proof_back}}">{{$lang["profile_complience_view_proof_of_registration_back"]}}</a><br>
                      @endif                      
                      @if($ver->status==0 || $user->business_level==2)                   
                      <div class="form-group row">
                        <label class="col-form-label col-lg-2">{{$lang["profile_complience_proof_of_address"]}}</label>
                        <div class="col-lg-10">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input sdsx" id="customFileLang2" @if($ver->status==1 || $user->business_level==3) disabled @endif name="paddress" accept="image/*" required>
                            <label class="custom-file-label sdsx" for="customFileLang2">{{$lang["profile_complience_select_document"]}}</label>
                          </div> 
                        </div>
                      </div> 
                      @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->paddress}}">{{$lang["profile_complience_view_proof_of_address"]}}</a><br>
                      @endif 
                      <div id="otherFieldDiv3">
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_full_name"]}}</label>
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
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_date_of_birth"]}}</label>
                          <div class="col-lg-10">
                            <div class="row">
                                <div class="col-4">
                                  <select class="form-control select" name="b_month" @if($ver->status==1 || $user->business_level==3) disabled @endif id="3otherField">
                                    <option value="1" @if($ver->month=="1") selected @endif>{{$lang["profile_complience_jan"]}}</option>
                                    <option value="2" @if($ver->month=="2") selected @endif>{{$lang["profile_complience_feb"]}}</option>
                                    <option value="3" @if($ver->month=="3") selected @endif>{{$lang["profile_complience_mar"]}}</option>
                                    <option value="4" @if($ver->month=="4") selected @endif>{{$lang["profile_complience_apr"]}}</option>
                                    <option value="5" @if($ver->month=="5") selected @endif>{{$lang["profile_complience_may"]}}</option>
                                    <option value="6" @if($ver->month=="6") selected @endif>{{$lang["profile_complience_jun"]}}</option>
                                    <option value="7" @if($ver->month=="7") selected @endif>{{$lang["profile_complience_jul"]}}</option>
                                    <option value="8" @if($ver->month=="8") selected @endif>{{$lang["profile_complience_aug"]}}</option>
                                    <option value="9" @if($ver->month=="9") selected @endif>{{$lang["profile_complience_sep"]}}</option>
                                    <option value="10" @if($ver->month=="10") selected @endif>{{$lang["profile_complience_oct"]}}</option>
                                    <option value="11" @if($ver->month=="11") selected @endif>{{$lang["profile_complience_nov"]}}</option>
                                    <option value="12" @if($ver->month=="12") selected @endif>{{$lang["profile_complience_dec"]}}</option> 
                                  </select>
                                </div>      
                                <div class="col-4">
                                  <input type="number" name="b_day" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="{{$lang["profile_complience_day"]}}" value="{{$ver->day}}" min="1" max="30" value="{{$user->last_name}}" id="4otherField">
                                </div>                            
                                <div class="col-4">
                                  <input type="number" name="b_year" class="form-control" @if($ver->status==1 || $user->business_level==3) disabled @endif placeholder="{{$lang["profile_complience_year"]}}" value="{{$ver->year}}" min="1950" max="{{date('Y')}}" id="5otherField">
                                </div>
                            </div>
                          </div>
                        </div>  
                        <div class="form-group row"> 
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_nationality"]}}</label>                          
                          <div class="col-lg-10">
                              <select class="form-control custom-select" name="nationality" @if($ver->status==1 || $user->business_level==3) disabled @endif id="country" id="7otherField">
                              </select>
                              <span class="text-xs text-gray">{{$lang["profile_complience_current_nationality"]}}: {{$ver->nationality}}</span>
                          </div>
                        </div>  
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_gender"]}}</label>                          
                          <div class="col-lg-10">
                            <select class="form-control select" name="gender" @if($ver->status==1 || $user->business_level==3) disabled @endif id="70otherField">
                              <option value="male" @if($ver->gender=="male") selected @endif>{{$lang["profile_complience_male"]}}</option>
                              <option value="female" @if($ver->gender=="female") selected @endif>{{$lang["profile_complience_female"]}}</option>
                            </select>
                          </div>                            
                        </div>                        
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">{{$lang["profile_complience_id_document"]}}</label>                          
                          <div class="col-lg-10">
                            <select class="form-control select" name="id_type" @if($ver->status==1 || $user->business_level==3) disabled @endif id="7otherField">
                              <option value="National ID" @if($ver->id_type=="National ID") selected @endif>{{$lang["profile_complience_national_id"]}}</option>
                              <option value="International Passport" @if($ver->id_type=="International Passport") selected @endif>{{$lang["profile_complience_international_passport"]}}</option>
                              <option value="Voters Card" @if($ver->id_type=="Voters Card") selected @endif>{{$lang["profile_complience_voters_card"]}}</option>
                              <option value="Driver License" @if($ver->id_type=="Driver License") selected @endif>{{$lang["profile_complience_driver_license"]}}</option>
                            </select>
                          </div>                            
                        </div>
                        @if($ver->status==0 || $user->business_level==2) 
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang1" name="idcard" accept="image/*">
                              <label class="custom-file-label sdsd" for="customFileLang1">{{$lang["profile_complience_front"]}}</label>
                            </div> 
                          </div>
                        </div>                        
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" @if($ver->status==1 || $user->business_level==3) disabled @endif id="customFileLang3" name="idcard_back" accept="image/*">
                              <label class="custom-file-label tests" for="customFileLang3">{{$lang["profile_complience_back"]}}</label>
                            </div> 
                          </div>
                        </div>
                        @else
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard}}">{{$lang["profile_complience_view_identity_document_front"]}}</a><br>
                        <a href="{{url('/')}}/asset/profile/{{$ver->idcard_back}}">{{$lang["profile_complience_view_identity_document_back"]}}</a><br>
                        @endif                           
                      </div>                                             
                  </div>
                  <div class="text-center">
                      @if($ver->status==0 || $ver->status==3)    
                        <button type="submit" class="btn btn-neutral btn-block">{{$lang["profile_complience_submit_complience_for_review"]}}</button>
                      @elseif($ver->status==1)
                        <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{$lang["profile_complience_under_review"]}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==2)
                        <button type="submit" class="btn btn-neutral btn-block mb-5">{{$lang["profile_complience_update_compliance"]}}</button>
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_complience_approved"]}}</span>                  
                      @elseif($ver->status==2 && $user->business_level==3)
                        <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_complience_approved"]}}</span>
                      @endif
                  </div> 
                </div>                           
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop