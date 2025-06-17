@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                
                
                
                <div class="card">
                  <div class="card-header header-elements-inline">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["admin_users_compliance_title"]}}</h3>
                  </div>
                  <div class="card-body">
                      
                        <div class="text-center text-dark mb-5">
                            <span class="text-gray text-xs">{{$lang["admin_users_complience_verify_your_business"]}}</span>
                        </div>
                        
                      
                        <div class="row">
                    
                            <div class="col col-lg-1 col-md-1 col-sm-0 col-xs-0"></div>
                            <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="card mt-50">
                                    <div class="card-body text-center">

                                        <img src="{{url('/')}}/asset/images/personal-verification.png" width="200" height="200"  />
                                        
                                        <h2 class="card-title">{{$lang["admin_users_compliance_personal_verification"]}}</h2>
                                        <ul class="list-group">
                                            <li class="list-group-item text-center">
                                                <span class="text-gray">{{$lang["admin_users_compliance_verify_your_personal_data"]}}</span>
                                            </li>

                                            <li class="list-group-item text-center">
                                                <h4 class="badge badge-{{App\Models\Compliance::STATUS_PERSONAL[$compliance->status_personal]['label']}}">
                                                    {{App\Models\Compliance::STATUS_PERSONAL[$compliance->status_personal]['text']}}
                                                </h4>
                                            </li>


                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col col-xs-4 text-center"><strong>Doc. Id.</strong></div>
                                                    <div class="col col-xs-4 text-center"><strong>C. Res</strong></div>
                                                    <div class="col col-xs-4 text-center"><strong>Self</strong></div>
                                                </div>
                                                  
                                                <div class="row">
                                                    <div class="col col-xs-4 text-center">
                                                        <img src="{{url('/')}}/asset/images/{{App\Models\Compliance::DOCUMENT_STATUS[$compliance->personal_document_status]['image']}}" style="width: 40px;" /> 
                                                    </div>
                                                    <div class="col col-xs-4 text-center">
                                                        <img src="{{url('/')}}/asset/images/{{App\Models\Compliance::PROOF_STATUS[$compliance->personal_proof_status]['image']}}" style="width: 40px;" />
                                                    </div>
                                                    <div class="col col-xs-4 text-center">
                                                        <img src="{{url('/')}}/asset/images/{{App\Models\Compliance::SELFIE_STATUS[$compliance->personal_selfie_status]['image']}}" style="width: 40px;" />
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item text-center">
                                                
                                                <a href="{{route('admin.user.personal-compliance', ['userId' => $user->id])}}" class="btn btn-primary">
                                                    {{$lang["admin_users_compliance_start_personal_verification"]}}
                                                </a>
                                            
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-lg-2 col-md-2 col-sm-0 col-xs-0"></div>
                            <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="card mt-50">
                                    <div class="card-body text-center">
                                        
                                        <img src="{{url('/')}}/asset/images/business-verification.png" width="200" height="200"  />
                                        
                                        <h2 class="card-title">{{$lang["admin_users_compliance_business_verification"]}}</h2>
                                        
                                        <ul class="list-group">
                                            <li class="list-group-item text-center">
                                                <span class="text-gray">{{$lang["admin_users_compliance_verify_your_business_data"]}}</span>
                                            </li>

                                            <li class="list-group-item text-center">
                                                <h4 class="badge badge-{{App\Models\Compliance::STATUS_BUSINESS[$compliance->status_business]['label']}}">
                                                    {{App\Models\Compliance::STATUS_BUSINESS[$compliance->status_business]['text']}}
                                                </h4>
                                            </li>


                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col col-xs-6 text-center"><strong>CNPJ</strong></div>
                                                    <div class="col col-xs-6 text-center"><strong>C. End</strong></div>
                                                </div>
                                                  
                                                <div class="row">
                                                    <div class="col col-xs-6 text-center">
                                                        <img src="{{url('/')}}/asset/images/{{App\Models\Compliance::REGISTRY_STATUS[$compliance->business_registry_status]['image']}}" style="width: 40px;" /> 
                                                    </div>
                                                    <div class="col col-xs-6 text-center">
                                                        <img src="{{url('/')}}/asset/images/{{App\Models\Compliance::PROOF_STATUS[$compliance->business_proof_status]['image']}}" style="width: 40px;" />
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-group-item text-center">
                                                <a href="{{route('admin.user.business-compliance', ['userId' => $user->id])}}" class="btn btn-primary">
                                                    {{$lang["admin_users_compliance_start_business_verification"]}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>


                      
                  </div>
                    
                </div>
                
            </div>
@stop