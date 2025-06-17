@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <a href="{{route('user.manage', ['id' => $user->id])}}" class="btn btn-primary float-right m-2">{{$lang["admin_users_compliance_go_to_profile"]}}</a>
                        <a href="{{route('admin.user.compliance', ['id' => $user->id])}}" class="btn btn-neutral float-right m-2">{{$lang["admin_users_compliance_btn_back"]}}</a>
                        <h3 class="mb-0 font-weight-bolder">{{$lang["admin_users_compliance_personal_info"]}}</h3> 
                    </div>
                    <div class="card-body">


                        <div class="row">
                            <div class="col col-md-6">

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_first_name"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="first_name" value="{{$compliance->first_name}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('first_name');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_last_name"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="last_name" value="{{$compliance->last_name}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('last_name');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_cpf"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="cpf" value="{{$compliance->cpf}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('cpf');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_rg"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="rg" value="{{$compliance->rg}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('rg');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_birthday"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="birthday" value="{{($compliance->birthday != null ? date('d/m/Y',  strtotime($compliance->birthday . ' 00:00:00')) : '')}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('birthday');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6 col-lg-6">{{$lang["admin_users_compliance_personal_gender"]}}:</label>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="gender" value="{{$lang['admin_users_compliance_personal_' . $compliance->gender]}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('gender');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                      
                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_nationality"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="nationality" value="{{$country->nicename??''}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('nationality');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_email"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="email" value="{{$compliance->email??''}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('email');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_mother_name"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="mother_name" value="{{$compliance->mother_name??''}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('mother_name');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_phone"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="phone" value="{{'+'.$compliance->phone_country_code . ' (' . $compliance->phone_ddd . ') ' . $compliance->phone}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('phone');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_mobilephone"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="mobilephone" value="{{'+'.$compliance->mobilephone_country_code . ' (' . $compliance->mobilephone_ddd . ') ' . $compliance->mobilephone}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('mobilephone');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_document_type"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="id_type" value="{{$documentType}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('id_type');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_document_type_number"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="document" value="{{$compliance->document}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('document');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="col col-md-6">
                                <h4 class="">{{$lang["admin_users_personal_document_analises_title"]}}</h4>


                                <div class="row mt-3">
                                    <div class="col col-xs-12 text-center">
                                        <h1 class="">
                                            @if($compliance->personal_document_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->personal_document_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            {{$lang["admin_users_personal_document_analises_document_front"]}}
                                        </h1>
                                    </div>
                                </div>

                                @if(empty($compliance->idcard))
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            {{$lang["admin_users_personal_document_analises_document_not_sent"]}}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="card-img-actions d-inline-block mb-3 mt-3">
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->idcard}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif


                                <div class="row mt-3">
                                    <div class="col col-xs-12 text-center">
                                        <h1 class="">
                                            @if($compliance->personal_document_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->personal_document_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            {{$lang["admin_users_personal_document_analises_document_back"]}}
                                        </h1>
                                    </div>
                                </div>


                                @if(empty($compliance->idcard_back))
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            {{$lang["admin_users_personal_document_analises_document_not_sent"]}}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="card-img-actions d-inline-block mb-3 mt-3">
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->idcard_back}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($compliance->idcard) && !empty($compliance->idcard_back))
                                    <div class="row">
                                     
                                        @if($compliance->personal_document_status == 'PENDING')
                                        <div class="col col-xs-6">
                                            <button onclick="modalRejectDocument('d');" class="btn btn-danger btn-block m-2">{{$lang["admin_users_personal_document_analises_reject"]}}</button>
                                        </div>
                                        <div class="col col-xs-6">
                                            <button onclick="modalApproveDocument('d');" class="btn btn-success btn-block m-2">{{$lang["admin_users_personal_document_analises_approve"]}}</button>
                                        </div>
                                    
                                        @elseif($compliance->personal_document_status == 'APPROVED')
                                         
                                        <div class="col col-xs-12">
                                            <div class="alert alert-success">
                                                {{$lang["admin_users_personal_document_analises_document_approved"]}}
                                            </div>
                                        </div>

                                        @elseif($compliance->personal_document_status == 'REJECTED')

                                        <div class="col col-xs-12">
                                            <div class="alert alert-danger">
                                                {{$lang["admin_users_personal_document_analises_document_rejected"]}}<br>
                                                {{$compliance->personal_document_msg}}
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                @endif


                                <hr class="mt-5">


                                <div class="row mt-3">
                                    <div class="col col-xs-12 text-center">
                                        <h1 class="">
                                            @if($compliance->personal_selfie_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->personal_selfie_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            {{$lang["admin_users_personal_document_analises_selfie"]}}
                                        </h1>
                                    </div>
                                </div>

                                @if(empty($compliance->selfie))
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            {{$lang["admin_users_personal_document_analises_document_not_sent"]}}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="card-img-actions d-inline-block mb-3 mt-3">
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->selfie}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($compliance->selfie))
                                    <div class="row">
                                     
                                        @if($compliance->personal_selfie_status == 'PENDING')
                                        <div class="col col-xs-6">
                                            <button onclick="modalRejectDocument('s');" class="btn btn-danger btn-block m-2">{{$lang["admin_users_personal_document_analises_reject"]}}</button>
                                        </div>
                                        <div class="col col-xs-6">
                                            <button onclick="modalApproveDocument('s');" class="btn btn-success btn-block m-2">{{$lang["admin_users_personal_document_analises_approve"]}}</button>
                                        </div>
                                    
                                        @elseif($compliance->personal_selfie_status == 'APPROVED')
                                         
                                        <div class="col col-xs-12">
                                            <div class="alert alert-success">
                                                {{$lang["admin_users_personal_document_analises_document_approved"]}}
                                            </div>
                                        </div>

                                        @elseif($compliance->personal_selfie_status == 'REJECTED')

                                        <div class="col col-xs-12">
                                            <div class="alert alert-danger">
                                                {{$lang["admin_users_personal_document_analises_document_rejected"]}}<br>
                                                {{$compliance->personal_selfie_msg}}
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                @endif

                            </div>
                            
                        </div>
      
                    </div>

                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["admin_users_compliance_personal_address_info"]}}</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col col-md-6">

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_address"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address" value="{{$compliance->address}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_number"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_number" value="{{$compliance->address_number}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_number');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_complement"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_complement" value="{{$compliance->address_complement}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_complement');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_neighborhood"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="neighborhood" value="{{$compliance->neighborhood}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('neighborhood');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_postalcode"]}}:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_zipcode" value="{{$compliance->address_zipcode}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_zipcode');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_state"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_state" value="{{($state->name??'')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_state');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_city"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_city" value="{{$compliance->address_city}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_city');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">{{$lang["admin_users_compliance_personal_country"]}}: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="address_country_id" value="{{($addressCountry->nicename??'')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('address_country');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>



                            <div class="col col-md-6">
                                
                                <div class="row mt-3">
                                    <div class="col col-xs-12 text-center">
                                        <h1 class="">
                                            @if($compliance->personal_proof_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->personal_proof_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            {{$lang["admin_users_personal_document_analises_proof"]}}
                                        </h1>
                                    </div>
                                </div>

                                @if(empty($compliance->proof))
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            {{$lang["admin_users_personal_document_analises_document_not_sent"]}}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="card-img-actions d-inline-block mb-3 mt-3">
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->proof}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($compliance->proof))
                                    <div class="row">
                                     
                                        @if($compliance->personal_proof_status == 'PENDING')
                                        <div class="col col-xs-6">
                                            <button onclick="modalRejectDocument('r');" class="btn btn-danger btn-block m-2">{{$lang["admin_users_personal_document_analises_reject"]}}</button>
                                        </div>
                                        <div class="col col-xs-6">
                                            <button onclick="modalApproveDocument('r');" class="btn btn-success btn-block m-2">{{$lang["admin_users_personal_document_analises_approve"]}}</button>
                                        </div>
                                    
                                        @elseif($compliance->personal_proof_status == 'APPROVED')
                                         
                                        <div class="col col-xs-12">
                                            <div class="alert alert-success">
                                                {{$lang["admin_users_personal_document_analises_document_approved"]}}
                                            </div>
                                        </div>

                                        @elseif($compliance->personal_proof_status == 'REJECTED')

                                        <div class="col col-xs-12">
                                            <div class="alert alert-danger">
                                                {{$lang["admin_users_personal_document_analises_document_rejected"]}}<br>
                                                {{$compliance->personal_proof_msg}}
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                @endif

                            </div>
                        </div>
                        
                    </div>

                    <div class="card-footer">
                            <div class="row">
                                
                                @if($compliance->status_personal == 'PENDING')
                                <div class="col col-xs-6">
                                    <button onclick="modalRejectCompliance();" class="btn btn-danger btn-block m-2">Rejeitar Compliance</button>
                                </div>
                                <div class="col col-xs-6">
                                    <button onclick="modalApproveCompliance();" class="btn btn-success btn-block m-2">Aprovar Compliance</button>
                                </div>
                            
                                @elseif($compliance->status_personal == 'APPROVED')
                                    
                                <div class="col col-xs-12">
                                    <div class="alert alert-success">
                                        Compliance Aprovado
                                    </div>
                                </div>

                                @elseif($compliance->status_personal == 'REJECTED')

                                <div class="col col-xs-12">
                                    <div class="alert alert-danger">
                                        Compliance Rejeitado<br>
                                        {{$compliance->personal_status_msg}}
                                    </div>
                                </div>
                                @endif

                            </div>
                    </div>
                    
                </div>
                
            </div>

        </div>
    </div>
</div>



    <div class="modal fade" id="modal-reject-document" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_users_personal_document_analises_modal_reject_title"]}}</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="modal-reject-document-type" />
                            <div class="form-group row">
                                <label class="col-form-label col-md-12">{{$lang["admin_users_personal_document_analises_modal_message"]}}</label>
                                <div class="col-md-12">
                                    <textarea maxlength="255" rows="6"  class="form-control" name="modal-reject-document-message" id="modal-reject-document-message" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-reject-document-btn-cancel">{{$lang["admin_users_personal_document_analises_modal_cancel"]}}</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-reject-document-btn-save" onclick="rejectDocument();">{{$lang["admin_users_personal_document_analises_modal_approve"]}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <div class="modal fade" id="modal-approve-document" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang["admin_users_personal_document_analises_modal_approve_title"]}}</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="modal-approve-document-type" />
                            
                            <h4>{{$lang["admin_users_personal_document_analises_modal_approve_question"]}}</h4>
                        </div>
                        <div class="card-footer px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-approve-document-btn-cancel">{{$lang["admin_users_personal_document_analises_modal_cancel"]}}</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-approve-document-btn-save" onclick="approveDocument();">{{$lang["admin_users_personal_document_analises_modal_approve"]}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-reject-compliance" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">Rejeitar Compliance do Usuário</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-form-label col-md-12">Informe a mensagem que será exibida ao usuário</label>
                                <div class="col-md-12">
                                    <textarea maxlength="255" rows="6"  class="form-control" name="modal-reject-compliance-message" id="modal-reject-compliance-message" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-reject-compliance-btn-cancel">{{$lang["admin_users_personal_document_analises_modal_cancel"]}}</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-reject-compliance-btn-save" onclick="rejectCompliance();">{{$lang["admin_users_personal_document_analises_modal_approve"]}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    

    <div class="modal fade" id="modal-approve-compliance" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">Aprovar Compliance do Cliente</h3>
                        </div>
                        <div class="card-body">
                            <h4>Deseja realmente aprovar o compliance do cliente?</h4>
                        </div>
                        <div class="card-footer px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-approve-compliance-btn-cancel">{{$lang["admin_users_personal_document_analises_modal_cancel"]}}</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-approve-compliance-btn-save" onclick="approveCompliance();">{{$lang["admin_users_personal_document_analises_modal_approve"]}}</a>
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

        
    });
    
    function modalRejectDocument(type) {
        var modalRejectDocument = new bootstrap.Modal(document.getElementById('modal-reject-document'), { keyboard: false });
        modalRejectDocument.show();

        $("#modal-reject-document-type").val(type);
        $("#modal-reject-document-message").val("");
        $("#modal-reject-document-btn-cancel, #modal-reject-document-btn-save").prop("disabled", false);
    }

    function rejectDocument() {

        let data = {
            _token: "{{ csrf_token() }}",
            type: $("#modal-reject-document-type").val(),
            message: $("#modal-reject-document-message").val(),
            user: {{$user->id}}
        }

        $("#modal-reject-document-btn-cancel, #modal-reject-document-btn-save").prop("disabled", true);
        $.post("{{route('admin.user.compliance.reject')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-reject-document-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.personal-compliance', ['userId' => $user->id])}}";
                    }, 3000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-reject-document-btn-cancel, #modal-reject-document-btn-save").prop("disabled", false);
        });
    }
    

    function modalApproveDocument(type) {
        var modalApproveDocument = new bootstrap.Modal(document.getElementById('modal-approve-document'), { keyboard: false });
        modalApproveDocument.show();

        $("#modal-approve-document-type").val(type);
        $("#modal-approve-document-message").val("");
        $("#modal-approve-document-btn-cancel, #modal-approve-document-btn-save").prop("disabled", false);
    }
      
    function approveDocument() {

        let data = {
            _token: "{{ csrf_token() }}",
            type: $("#modal-approve-document-type").val(),
            user: {{$user->id}}
        }
        
        $("#modal-approve-document-btn-cancel, #modal-approve-document-btn-save").prop("disabled", true);
        $.post("{{route('admin.user.compliance.approve')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-approve-document-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.personal-compliance', ['userId' => $user->id])}}";
                    }, 3000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-approve-document-btn-cancel, #modal-approve-document-btn-save").prop("disabled", false);
        });
    }
    

     

    function modalRejectCompliance() {
        var modalRejectCompliance = new bootstrap.Modal(document.getElementById('modal-reject-compliance'), { keyboard: false });
        modalRejectCompliance.show();

        $("#modal-reject-compliance-message").val("");
        $("#modal-reject-compliance-btn-cancel, #modal-reject-compliance-btn-save").prop("disabled", false);
    }

    function rejectCompliance() {

        let data = {
            _token: "{{ csrf_token() }}",
            type: "p",
            message: $("#modal-reject-compliance-message").val(),
            user: {{$user->id}}
        }

        $("#modal-reject-compliance-btn-cancel, #modal-reject-compliance-btn-save").prop("disabled", true);
        $.post("{{route('admin.user.compliance.reject')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-reject-compliance-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.personal-compliance', ['userId' => $user->id])}}";
                    }, 3000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-reject-compliance-btn-cancel, #modal-reject-compliance-btn-save").prop("disabled", false);
        });
    }
    

    function modalApproveCompliance(type) {
        var modalApproveCompliance = new bootstrap.Modal(document.getElementById('modal-approve-compliance'), { keyboard: false });
        modalApproveCompliance.show();

        $("#modal-approve-compliance-message").val("");
        $("#modal-approve-compliance-btn-cancel, #modal-approve-compliance-btn-save").prop("disabled", false);
    }
        
    function approveCompliance() {

        let data = {
            _token: "{{ csrf_token() }}",
            type: "p",
            user: {{$user->id}}
        }
        
        $("#modal-approve-compliance-btn-cancel, #modal-approve-compliance-btn-save").prop("disabled", true);
        $.post("{{route('admin.user.compliance.approve')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-approve-compliance-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.personal-compliance', ['userId' => $user->id])}}";
                    }, 3000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-approve-compliance-btn-cancel, #modal-approve-compliance-btn-save").prop("disabled", false);
        });
    }
    
</script>
@endpush