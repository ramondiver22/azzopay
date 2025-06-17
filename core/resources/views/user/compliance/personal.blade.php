@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card card-{{App\Models\Compliance::STATUS_PERSONAL[$compliance->status_personal]['label']}}">
                    <div class="card-header header-elements-inline">
                        <label class="badge badge-{{App\Models\Compliance::STATUS_PERSONAL[$compliance->status_personal]['label']}} float-right" >
                            {{App\Models\Compliance::STATUS_PERSONAL[$compliance->status_personal]['text']}}
                        </label>
                        <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_module_personal_title"]}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('submit.personal-compliance')}}" method="post" enctype="multipart/form-data" id="form-personal-compliance">
                            @csrf
                             
                            @if($compliance->status_personal == 'REJECTED')
                            <div class="row mb-3">
                                <div class="col col-xs-12">
                                    <div class="alert alert-danger">
                                        <h4 class="text-white">Compliance Rejeitada</h4>
                                        <p class="text-white">{{$compliance->personal_status_msg}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_personal_personal_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_first_name"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="first_name" id="first_name" maxlength="80"  class="form-control" value="{{$compliance->first_name}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_last_name"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="last_name" id="last_name" maxlength="80"  class="form-control" value="{{$compliance->last_name}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_cpf"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="cpf" id="cpf" maxlength="14"  class="form-control" value="{{$compliance->cpf}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_rg"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="rg" id="rg" maxlength="40" class="form-control" value="{{$compliance->rg}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_birthday"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="birthday" id="birthday" maxlength="40" class="form-control date-mask" value="{{date('d/m/Y',  strtotime($compliance->birthday . ' 00:00:00'))}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_gender"]}}</label>                     
                                                        <div class="col-lg-12">
                                                          <select class="form-control select" name="gender" id="gender" >
                                                            <option value="male" @if($compliance->gender=="male") selected @endif>{{$lang["compliance_personal_male"]}}</option>
                                                            <option value="female" @if($compliance->gender=="female") selected @endif>{{$lang["compliance_personal_female"]}}</option>
                                                            <option value="other" @if($compliance->gender=="other") selected @endif>{{$lang["compliance_personal_other"]}}</option>
                                                          </select>
                                                        </div>                            
                                                    </div>  
                                                </div>


                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_nationality"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="nationality" id="nationality" required>
                                                                <option value="">{{$lang["compliance_personal_select_nationality"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    <option value="{{$val->id}}" @if($compliance->nationality==$val->id) selected @endif  >{{$val->nicename}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>  


                                            </div>
                                            
                                            <div class="row">
                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_email"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="email" id="email" maxlength="80"  class="form-control" value="{{$compliance->email}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_mother_name"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="mother_name" id="mother_name" maxlength="80"  class="form-control" value="{{$compliance->mother_name}}" required>    
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
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_personal_contact_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_phone_country_code"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="phone_country_code" id="phone_country_code" required>
                                                                <option value="">{{$lang["compliance_personal_select_phone_country_code"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    @if(!empty($val->iso3))
                                                                    <option value="{{$val->phonecode}}" @if($compliance->phone_country_code==$val->phonecode) selected @endif  >{{$val->iso3}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_phone_ddd"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="phone_ddd" id="phone_ddd" maxlength="3"  class="form-control" value="{{$compliance->phone_ddd}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_phone"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="phone" id="phone" maxlength="10"  class="form-control" value="{{$compliance->phone}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_mobilephone_country_code"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="mobilephone_country_code" id="mobilephone_country_code"  required>
                                                                <option value="">{{$lang["compliance_personal_select_mobilephone_country_code"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    @if(!empty($val->iso3))
                                                                    <option value="{{$val->phonecode}}" @if($compliance->mobilephone_country_code==$val->phonecode) selected @endif  >{{$val->iso3}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_phone_ddd"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="mobilephone_ddd" id="mobilephone_ddd" maxlength="3"  class="form-control" value="{{$compliance->mobilephone_ddd}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_mobilephone"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="mobilephone" id="mobilephone" maxlength="10"  class="form-control" value="{{$compliance->mobilephone}}" required>    
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
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_personal_address_info"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_postalcode"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="address_zipcode" id="address_zipcode" maxlength="9"  class="form-control" value="{{$compliance->address_zipcode}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_address"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="address" id="address" maxlength="80"  class="form-control" value="{{$compliance->address}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">


                                                <div class="col col-lg-2 col-md-2 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_number"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="address_number" id="address_number" maxlength="10"  class="form-control" value="{{$compliance->address_number}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-5 col-md-5 col-sm-6 col-xs-6">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_complement"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="address_complement" id="address_complement" maxlength="40"  class="form-control" value="{{$compliance->address_complement}}" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_neighborhood"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="neighborhood" id="neighborhood" maxlength="40" class="form-control" value="{{$compliance->neighborhood}}" required>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">

                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_state"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="address_state" id="address_state" required>
                                                                <option value="">{{$lang["compliance_personal_select_state"]}}</option> 
                                                                @foreach($states as $state)
                                                                    <option value="{{$state->uf}}" @if($compliance->address_state==$state->uf) selected @endif  >{{$state->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>  

                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_city"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="address_city"  class="form-control" id="address_city" maxlength="40" value="{{$compliance->address_city}}" required>    
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_personal_country"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="address_country_id" id="address_country_id" required>
                                                                <option value="">{{$lang["compliance_personal_select_country"]}}</option> 
                                                                @foreach($bcountry as $val)
                                                                    <option value="{{$val->id}}" @if($compliance->address_country_id==$val->id) selected @endif  >{{$val->nicename}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>  
                                                </div>  

                                            </div>
                                            
                                            <div class="row mt-20">
                                                <div class="col col-md-6 col-lg-6 col-xs-6 col-sm-6 text-left">
                                                    <a href="{{route('user.account-compliance')}}" class="btn btn-neutral btn-block">{{$lang["compliance_personal_btn_back"]}} 
                                                        <span id="btn-back"></span>
                                                    </a>
                                                </div>
                                                <div class="col col-md-6 col-lg-6 col-xs-6 col-sm-6 text-right">  
                                                    @if(
                                                        in_array($compliance->status_personal, array("NONE", "REJECTED")) || 
                                                        in_array($compliance->personal_document_status, array("NONE", "REJECTED")) ||
                                                        in_array($compliance->personal_proof_status, array("NONE", "REJECTED")) ||
                                                        in_array($compliance->personal_selfie_status, array("NONE", "REJECTED"))
                                                    )  
                                                    <button type="button" class="btn btn-info btn-block" onclick="saveInfo();">{{$lang["compliance_personal_btn_goto_send_docs"]}} 
                                                        <span id="btn-goto-send-docs"></span>
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

        </div>
    </div>
</div>
@stop



@push('scripts')
<script>
        
    $(document).ready(function () {

        $("#address_zipcode").blur(function () {
            console.log("ok");
            findAddress($(this).val());
        });
        
    });
    
    
    function findAddress(cep) {
    
    
        $.get("<?php echo url("api/cep/search")?>/"+cep, {}, function (json) {
            
            try {
                
                if (json.success) {
                    $("#address").val(json.address.logradouro);
                    $("#address_number").val("");
                    $("#address_complement").val("");
                    $("#neighborhood").val(json.address.bairro);
                    $("#address_city").val(json.address.localidade);
                    $("#address_state").val(json.address.uf);
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
            first_name: $("#first_name").val(),
            last_name: $("#last_name").val(),
            cpf: $("#cpf").val(),
            rg: $("#rg").val(),
            gender: $("#gender").val(),
            email: $("#email").val(),
            nationality: $("#nationality").val(),
            phone_country_code: $("#phone_country_code").val(),
            phone_ddd: $("#phone_ddd").val(),
            phone: $("#phone").val(),
            mobilephone_country_code: $("#mobilephone_country_code").val(),
            mobilephone_ddd: $("#mobilephone_ddd").val(),
            mobilephone: $("#mobilephone").val(),
            address_zipcode: $("#address_zipcode").val(),
            address: $("#address").val(),
            address_number: $("#address_number").val(),
            address_complement: $("#address_complement").val(),
            neighborhood: $("#neighborhood").val(),
            address_state: $("#address_state").val(),
            address_city: $("#address_city").val(),
            address_country_id: $("#address_country_id").val(),
            birthday: $("#birthday").val(),
            mother_name: $("#mother_name").val(),
            _token: get_form_csrf_data("form-personal-compliance")
        };
        
        $("#btn-back, #btn-goto-send-docs").prop("disabled", true);
        $.post("<?php echo url("user/personal-compliance")?>", data, function (json) {
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