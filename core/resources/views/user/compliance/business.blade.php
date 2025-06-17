@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card card-{{App\Models\Compliance::STATUS_BUSINESS[$compliance->status_business]['label']}}">
                  <div class="card-header header-elements-inline">
                        <label class="badge badge-{{App\Models\Compliance::STATUS_BUSINESS[$compliance->status_business]['label']}} float-right" >
                            {{App\Models\Compliance::STATUS_BUSINESS[$compliance->status_business]['text']}}
                        </label>

                        <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_module_business_title"]}}</h3>
                  </div>
                  <div class="card-body">
                      
                  
                
                          <form action="{{route('submit.personal-compliance')}}" method="post" enctype="multipart/form-data" id="form-business-compliance">
                              @csrf
                            

                                @if($compliance->status_business == 'REJECTED')
                                <div class="row mb-3">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            <h4 class="text-white">Compliance Rejeitada</h4>
                                            <p class="text-white">{{$compliance->business_status_msg}}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                              <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_business_business_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_legal_name"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="trading_name" id="trading_name" maxlength="80"  class="form-control" value="{{$compliance->trading_name}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_trade_name"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="legal_name" id="legal_name" maxlength="80"  class="form-control" value="{{$compliance->legal_name}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_cnpj"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="company_document_id" id="company_document_id" maxlength="19"  class="form-control" value="{{$compliance->company_document_id}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_fundation_date"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="company_fundation_date" id="company_fundation_date" maxlength="40" class="form-control date-mask" value="{{date('d/m/Y',  strtotime($compliance->company_fundation_date . ' 00:00:00'))}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                  <div class="form-group row">
                                                    <label class="col-form-label col-lg-12">{{$lang["compliance_business_staff_size"]}}</label>
                                                    <div class="col-lg-12">
                                                        <select class="form-control select" name="staff_size" id="staff_size" required>
                                                            <option value="1-5" @if($ver->staff_size=="1-5") selected @endif>1-5 {{$lang["compliance_business_staff_people"]}}</option> 
                                                            <option value="5-50" @if($ver->staff_size=="5-50") selected @endif>5-50 {{$lang["compliance_business_staff_people"]}}</option> 
                                                            <option value="50+" @if($ver->staff_size=="50+") selected @endif>50+ {{$lang["compliance_business_staff_people"]}}</option> 
                                                        </select>
                                                    </div>
                                                  </div> 
                                                </div> 
                                            </div>


                                            <div class="row">
                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_business_description"]}}</label>
                                                        <div class="col-lg-12">
                                                            <textarea type="text" id="trading_desc" name="trading_desc" class="form-control" required>{{$ver->description}}</textarea>  
                                                        </div>
                                                    </div>  
                                                </div> 
                                            </div>
                 
                      

                                            <div class="row">

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                      <label class="col-form-label col-lg-12">{{$lang["compliance_business_industry"]}}</label>
                                                      <div class="col-lg-12">
                                                          <select class="form-control select" name="industry"  id="industry" required>
                                                          </select>
                                                      </div>
                                                    </div>   
                                                </div>


                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                      <label class="col-form-label col-lg-12">{{$lang["compliance_business_category"]}}</label>
                                                      <div class="col-lg-12">
                                                          <select class="form-control select" name="category" id="category" required>
                                                          </select>
                                                      </div>
                                                    </div> 
                                                </div>  


                                            </div>
                                            
                                            <div class="row">
                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                      <label class="col-form-label col-lg-12">{{$lang["compliance_business_business_type"]}}</label>
                                                      <div class="col-lg-12">
                                                          <select class="form-control select" name="business_type" id="business_type" required>
                                                              <option value="Starter Business" @if($ver->business_type=="Starter Business") selected @endif>{{$lang["compliance_business_starter_business"]}}</option> 
                                                              <option value="Registered Business" @if($ver->business_type=="Registered Business") selected @endif>{{$lang["compliance_business_registered_business"]}}</option> 
                                                          </select>
                                                      </div>
                                                    </div>     
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    
                                                  <div class="form-group row">
                                                    <label class="col-form-label col-lg-12">{{$lang["complience_business_registration_type"]}}</label>
                                                    <div class="col-lg-12">
                                                        <select class="form-control select" name="registration_type"  id="registration_type">
                                                            <option value="government_instrumentality" @if($ver->registration_type=="government_instrumentality") selected @endif>{{$lang["complience_business_government_instrumentality"]}}</option> 
                                                            <option value="governmental_unit" @if($ver->registration_type=="governmental_unit") selected @endif>{{$lang["complience_business_governamental_unit"]}}</option> 
                                                            <option value="incorporated_non_profit" @if($ver->registration_type=="incorporated_non_profit") selected @endif>{{$lang["complience_business_incorporated_non_profit"]}}</option> 
                                                            <option value="limited_liability_partnership" @if($ver->registration_type=="limited_liability_partnership") selected @endif>{{$lang["complience_business_limited_liability_partnership"]}}</option> 
                                                            <option value="multi_member_llc" @if($ver->registration_type=="multi_member_llc") selected @endif>{{$lang["complience_business_multi_member_llc"]}}</option> 
                                                            <option value="private_company" @if($ver->registration_type=="private_company") selected @endif>{{$lang["complience_business_private_company"]}}</option> 
                                                            <option value="private_corporation" @if($ver->registration_type=="private_corporation") selected @endif>{{$lang["complience_business_private_comporation"]}}</option> 
                                                            <option value="private_partnership" @if($ver->registration_type=="private_partnership") selected @endif>{{$lang["complience_business_private_partnership"]}}</option> 
                                                            <option value="public_company" @if($ver->registration_type=="public_company") selected @endif>{{$lang["complience_business_public_company"]}}</option> 
                                                            <option value="public_corporation" @if($ver->registration_type=="public_corporation") selected @endif>{{$lang["complience_business_public_corporation"]}}</option> 
                                                            <option value="public_partnership" @if($ver->registration_type=="public_partnership") selected @endif>{{$lang["complience_business_public_partnership"]}}</option> 
                                                            <option value="single_member_llc" @if($ver->registration_type=="single_member_llc") selected @endif>{{$lang["complience_business_single_member_llc"]}}</option> 
                                                            <option value="sole_proprietorship" @if($ver->registration_type=="sole_proprietorship") selected @endif>{{$lang["complience_business_sole_proprietorship"]}}</option> 
                                                            <option value="tax_exempt_government_instrumentality" @if($ver->registration_type=="tax_exempt_government_instrumentality") selected @endif>{{$lang["complience_business_tax_exempt_government_instrumentality"]}}</option> 
                                                            <option value="unincorporated_association" @if($ver->registration_type=="unincorporated_association") selected @endif>{{$lang["complience_business_unincorporated_association"]}}</option> 
                                                            <option value="unincorporated_non_profit" @if($ver->registration_type=="unincorporated_non_profit") selected @endif>{{$lang["complience_business_unincorporated_non_profit"]}}</option> 
                                                        </select>
                                                    </div>
                                                  </div>    
                                                
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                            <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_business_contact_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_select_phone_country_code"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="phone_country_code" id="phone_country_code" required>
                                                                <option value="">{{$lang["compliance_business_select_country"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    @if(!empty($val->iso3))
                                                                    <option value="{{$val->phonecode}}" @if($compliance->business_phone_country_code==$val->phonecode) selected @endif  >{{$val->iso3}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>    
                                                </div>
                     

                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_phone_ddd"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="phone_ddd" id="phone_ddd" maxlength="3"  class="form-control" value="{{$compliance->business_phone_ddd}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_phone"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="phone" id="phone" maxlength="10"  class="form-control" value="{{$compliance->business_phone}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">

                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_website"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="website" id="website" maxlength="255"  class="form-control" value="{{$compliance->website}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>


                            <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_business_address_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_postalcode"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address_postalcode" id="office_address_postalcode" maxlength="9"  class="form-control" value="{{$compliance->office_address_postalcode}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_address"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address" id="office_address" maxlength="80"  class="form-control" value="{{$compliance->office_address}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            
                                            <div class="row">

                                                <div class="col col-lg-2 col-md-2 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_number"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address_number" id="office_address_number" maxlength="10"  class="form-control" value="{{$compliance->office_address_number}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                                        
                                                <div class="col col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_complement"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address_complement" id="office_address_complement" maxlength="40"  class="form-control" value="{{$compliance->office_address_complement}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_neighborhood"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address_neighborhood" id="office_address_neighborhood" maxlength="40" class="form-control" value="{{$compliance->office_address_neighborhood}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_state"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="office_address_state" id="office_address_state" required>
                                                                <option value="">{{$lang["compliance_business_select_state"]}}</option> 
                                                                @foreach($states as $state)
                                                                    <option value="{{$state->uf}}" @if($compliance->office_address_state==$state->uf) selected @endif  >{{$state->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>  

                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_city"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="office_address_city"  class="form-control" id="office_address_city" maxlength="40" value="{{$compliance->office_address_city}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_country"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="office_address_country_id" id="office_address_country_id" required>
                                                                <option value="">{{$lang["compliance_business_select_country"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    <option value="{{$val->id}}" @if($compliance->office_address_country_id==$val->id) selected @endif  >{{$val->nicename}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>  

                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>




                            <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_business_finance_health_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            


                                            <div class="row">

                                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_month_revenue"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="number" name="month_revenue" id="month_revenue" maxlength="255"  class="form-control" value="{{$compliance->month_revenue}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_patrimony"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="number" name="patrimony" id="patrimony" maxlength="255"  class="form-control" value="{{$compliance->patrimony}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            
                                            <div class="row">

                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_source_of_funds"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="source_of_funds" id="source_of_funds" maxlength="255"  class="form-control" value="{{$compliance->source_of_funds}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">

                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_source_of_capital"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="source_of_capital" id="source_of_capital" maxlength="255"  class="form-control" value="{{$compliance->source_of_capital}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">

                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_business_source_of_wealth"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="source_of_wealth" id="source_of_wealth" maxlength="255"  class="form-control" value="{{$compliance->source_of_wealth}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row mt-20">
                                                <div class="col col-md-6 col-lg-6 col-xs-6 col-sm-6 text-left">
                                                    <a href="{{route('user.account-compliance')}}" class="btn btn-neutral btn-block" id="btn-back">{{$lang["compliance_business_btn_back"]}} 
                                                        
                                                    </a>
                                                </div> 
                                                <div class="col col-md-6 col-lg-6 col-xs-6 col-sm-6 text-right">   
                                                    @if(
                                                        in_array($compliance->status_business, array("NONE", "REJECTED")) || 
                                                        in_array($compliance->business_registry_status, array("NONE", "REJECTED")) ||
                                                        in_array($compliance->business_proof_status, array("NONE", "REJECTED")) 
                                                    )   
                                                    <button type="button" class="btn btn-info btn-block" id="btn-goto-send-docs" onclick="saveInfo();">
                                                        {{$lang["compliance_business_btn_goto_send_docs"]}} 
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </form>    
                      
                  </div>
                    
                </div>
                
            </div>
@stop


@push('scripts')
<script>
         
    let selectedIndustry = "{{($compliance->industry != null ? $compliance->industry : '')}}";
    let selectedCategory = "{{($compliance->category != null ? $compliance->category : '')}}";

    $(document).ready(function () {

        $("#office_address_postalcode").blur(function () {
            findAddress($(this).val());
        });

    });
    
    
    function findAddress(cep) {
    
    
        $.get("<?php echo url("api/cep/search")?>/"+cep, {}, function (json) {
            
            try {
                   
                                                 
                if (json.success) {
                    $("#office_address").val(json.address.logradouro);
                    $("#office_address_number").val("");
                    $("#office_address_complement").val("");
                    $("#office_address_neighborhood").val(json.address.bairro);
                    $("#office_address_city").val(json.address.localidade);
                    $("#office_address_state").val(json.address.uf);
                } else {
                    console.log(json.message);
                }
            } catch (e) {
                console.log(e);
            }
            
        }, "json");
    
    }


             
                                    
                                   


    function saveInfo() {
        
        let data = {
          trading_name: $("#trading_name").val(),
          legal_name: $("#legal_name").val(),
          company_document_id: $("#company_document_id").val(),
          company_fundation_date: $("#company_fundation_date").val(),
          staff_size: $("#staff_size").val(),
          trading_desc: $("#trading_desc").val(),
          industry: $("#industry").val(),
          category: $("#category").val(),
          business_type: $("#business_type").val(),
          registration_type: $("#registration_type").val(),
          phone_country_code: $("#phone_country_code").val(),
          phone_ddd: $("#phone_ddd").val(),
          phone: $("#phone").val(),
          website: $("#website").val(),
          office_address_postalcode: $("#office_address_postalcode").val(),
          office_address: $("#office_address").val(),
          office_address_number: $("#office_address_number").val(),
          office_address_complement: $("#office_address_complement").val(),
          office_address_neighborhood: $("#office_address_neighborhood").val(),
          office_address_state: $("#office_address_state").val(),
          office_address_city: $("#office_address_city").val(),
          office_address_country_id: $("#office_address_country_id").val(),
          month_revenue: $("#month_revenue").val(),
          patrimony: $("#patrimony").val(),
          source_of_funds: $("#source_of_funds").val(),
          source_of_capital: $("#source_of_capital").val(),
          source_of_wealth: $("#source_of_wealth").val(),
            _token: get_form_csrf_data("form-business-compliance")
        };
        
        $("#btn-back, #btn-goto-send-docs").prop("disabled", true);
        $.post("<?php echo url("user/business-compliance")?>", data, function (json) {
            try {
                
                if (json.success) {
                    toastr.success(json.message);
                    setTimeout(function () {
                        location = json.redirect;
                    }, 1000);
                    
                } else {
                    toastr.error(json.message);
                }
                
            } catch(e) {
                toastr.error(e);
            }
        }, 'json').always(function () {
            $("#btn-back, #btn-goto-send-docs").prop("disabled", false);
        }).fail((json) => {
           toastr.error(json.responseJSON.message);
       });
        
    }
</script>
@endpush