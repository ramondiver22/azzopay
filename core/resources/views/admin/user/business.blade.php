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
                        
                        <h3 class="mb-0 font-weight-bolder">Verificação de Conformidade da Empresa</h3>
                    </div>


                    <div class="card-body">


                        <div class="row">
                            <div class="col col-md-6">

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Razão Social:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="trading_name" value="{{$compliance->trading_name}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('trading_name');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Nome Fantasia:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="legal_name" value="{{$compliance->legal_name}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('legal_name');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">CNPJ:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="company_document_id" value="{{$compliance->company_document_id}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('company_document_id');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Data de Fundação:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="company_fundation_date" value="{{($compliance->company_fundation_date != null ? date('d/m/Y', strtotime($compliance->company_fundation_date)) : '')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('company_fundation_date');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Quantidade de Funcionários:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="staff_size" value="{{$compliance->staff_size}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('staff_size');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                     
                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-12 mb-2">Descrição das atividades da empresa:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <textarea type="text" id="trading_desc" name="trading_desc" class="form-control" readonly>{{$compliance->description}}</textarea>
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('trading_desc');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                                
                                
                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Atividade Industrial:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="staff_size" value="{{App\Models\Compliance::INDUSTRIAL_ACTIVITY[$compliance->industry]['text']}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('staff_size');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                 
                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Categoria de Serviço:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="category" value="{{App\Models\Compliance::SERVICE_CATEGORY[$compliance->category]['text']}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('category');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Tipo de Empresa:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="business_type" value="{{App\Models\Compliance::BUSINESS_TYPE[$compliance->business_type]['text']}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('business_type');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Tipo de Registro:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="registration_type" value="{{App\Models\Compliance::REGISTRATION_TYPE[$compliance->registration_type]['text']}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('registration_type');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                  
                                <div class="form-group row mt-2"> 
                                    <label class="col-form-label col-md-6">Telefone:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="business_phone" value="{{'+'.$compliance->phonecode . ' (' . $compliance->business_phone_ddd . ') ' . $compliance->business_phone}}"> 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('business_phone');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Website:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="website" value="{{$compliance->website}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('website');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Faturamento Mensal:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="month_revenue" value="{{number_format($compliance->month_revenue, 2, ',', '.')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('month_revenue');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Patrimônio:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="patrimony" value="{{number_format($compliance->patrimony, 2, ',', '.')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('patrimony');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Origem dos Fundos:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <textarea type="text" id="source_of_funds" name="source_of_funds" class="form-control" readonly>{{$compliance->source_of_funds}}</textarea>
                                            <div class="input-group-append" onclick="copyToClipboard('source_of_funds');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Origem do Capital da Empresa:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <textarea type="text" id="source_of_capital" name="source_of_capital" class="form-control" readonly>{{$compliance->source_of_capital}}</textarea>
                                            <div class="input-group-append" onclick="copyToClipboard('source_of_capital');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Fonte de Riqueza dos Sócios:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <textarea type="text" id="source_of_wealth" name="source_of_wealth" class="form-control" readonly>{{$compliance->source_of_wealth}}</textarea>
                                            <div class="input-group-append" onclick="copyToClipboard('source_of_wealth');">
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
                                            @if($compliance->business_registry_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->business_registry_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            Comprovante de Endereço da Empresa
                                        </h1>
                                    </div>
                                </div>

                                @if(empty($compliance->business_national_registry))
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="alert alert-danger">
                                            Documento não enviado
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row mt-1">
                                    <div class="col col-xs-12">
                                        <div class="card-img-actions d-inline-block mb-3 mt-3">
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->business_national_registry}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($compliance->business_national_registry))
                                    <div class="row">
                                     
                                        @if($compliance->business_registry_status == 'PENDING')
                                        <div class="col col-xs-6">
                                            <button onclick="modalRejectDocument('c');" class="btn btn-danger btn-block m-2">
                                                {{$lang["admin_users_personal_document_analises_reject"]}}
                                            </button>
                                        </div>
                                        <div class="col col-xs-6">
                                            <button onclick="modalApproveDocument('c');" class="btn btn-success btn-block m-2">
                                                {{$lang["admin_users_personal_document_analises_approve"]}}
                                            </button>
                                        </div>
                                    
                                        @elseif($compliance->business_registry_status == 'APPROVED')
                                         
                                        <div class="col col-xs-12">
                                            <div class="alert alert-success">
                                                {{$lang["admin_users_personal_document_analises_document_approved"]}}
                                            </div>
                                        </div>

                                        @elseif($compliance->business_registry_status == 'REJECTED')

                                        <div class="col col-xs-12">
                                            <div class="alert alert-danger">
                                                {{$lang["admin_users_personal_document_analises_document_rejected"]}}<br>
                                                {{$compliance->business_registry_msg}}
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                @endif


                            </div>
                        </div>

                    </div>


                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0 font-weight-bolder">Dados de Localização</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col col-md-6">

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Endereço: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address" value="{{$compliance->office_address}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Número: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_number" value="{{$compliance->office_address_number}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_number');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Complemento: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_complement" value="{{$compliance->office_address_complement}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_complement');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Bairro: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_neighborhood" value="{{$compliance->office_address_neighborhood}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_neighborhood');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">CEP:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_postalcode" value="{{$compliance->office_address_postalcode}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_postalcode');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Estado: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_state" value="{{($state->name??'')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_state');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">Cidade: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_city" value="{{$compliance->office_address_city}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_city');">
                                                <span class="input-group-text" style="cursor: pointer;"><i class="fad fa-copy"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row mt-2">
                                    <label class="col-form-label col-md-6">País: </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text"  class="form-control" readonly id="office_address_country_id" value="{{($addressCountry->nicename??'')}}" > 
                                            
                                            <div class="input-group-append" onclick="copyToClipboard('office_address_country_id');">
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
                                            @if($compliance->business_proof_status == 'PENDING')
                                            <img src="{{url('/')}}/asset/images/hourglass-orange.png" style="width: 40px; margin-rigth: 10px;" />
                                            @elseif($compliance->business_proof_status == 'APPROVED')
                                            <img src="{{url('/')}}/asset/images/mark-green.png" style="width: 40px; margin-rigth: 10px;" />
                                            @else
                                            <img src="{{url('/')}}/asset/images/cross-red.png" style="width: 40px; margin-rigth: 10px;" />
                                            @endif
                                            Comprovante de Endereço da Empresa
                                        </h1>
                                    </div>
                                </div>

                                @if(empty($compliance->business_proof))
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
                                            <img class="img-fluid document-image" src="{{url('/')}}/asset/profile/{{$compliance->business_proof}}" style="max-width: 100%; margin: 0 auto;" alt="">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($compliance->business_proof))
                                    <div class="row">
                                     
                                        @if($compliance->business_proof_status == 'PENDING')
                                        <div class="col col-xs-6">
                                            <button onclick="modalRejectDocument('r');" class="btn btn-danger btn-block m-2">
                                                {{$lang["admin_users_personal_document_analises_reject"]}}
                                            </button>
                                        </div>
                                        <div class="col col-xs-6">
                                            <button onclick="modalApproveDocument('r');" class="btn btn-success btn-block m-2">
                                                {{$lang["admin_users_personal_document_analises_approve"]}}
                                            </button>
                                        </div>
                                    
                                        @elseif($compliance->business_proof_status == 'APPROVED')
                                         
                                        <div class="col col-xs-12">
                                            <div class="alert alert-success">
                                                {{$lang["admin_users_personal_document_analises_document_approved"]}}
                                            </div>
                                        </div>

                                        @elseif($compliance->business_proof_status == 'REJECTED')

                                        <div class="col col-xs-12">
                                            <div class="alert alert-danger">
                                                {{$lang["admin_users_personal_document_analises_document_rejected"]}}<br>
                                                {{$compliance->business_proof_msg}}
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
                            
                            @if($compliance->status_business == 'PENDING')
                            <div class="col col-xs-6">
                                <button onclick="modalRejectCompliance();" class="btn btn-danger btn-block m-2">Rejeitar Compliance</button>
                            </div>
                            <div class="col col-xs-6">
                                <button onclick="modalApproveCompliance();" class="btn btn-success btn-block m-2">Aprovar Compliance</button>
                            </div>
                        
                            @elseif($compliance->status_business == 'APPROVED')
                                
                            <div class="col col-xs-12">
                                <div class="alert alert-success">
                                    Compliance Aprovado
                                </div>
                            </div>

                            @elseif($compliance->status_business == 'REJECTED')

                            <div class="col col-xs-12">
                                <div class="alert alert-danger">
                                    Compliance Rejeitado<br>
                                    {{$compliance->business_status_msg}}
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
                            <h3 class="mb-0">Rejeitar Compliance da Empresa</h3>
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
                            <h3 class="mb-0">Aprovar Compliance da Empresa</h3>
                        </div>
                        <div class="card-body">
                            <h4>Deseja realmente aprovar o compliance da empresa?</h4>
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
        $.post("{{route('admin.user.business.reject')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-reject-document-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.business-compliance', ['userId' => $user->id])}}";
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
        $.post("{{route('admin.user.business.approve')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-approve-document-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.business-compliance', ['userId' => $user->id])}}";
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
        $.post("{{route('admin.user.business.reject')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-reject-compliance-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.business-compliance', ['userId' => $user->id])}}";
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
        $.post("{{route('admin.user.business.approve')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-approve-compliance-btn-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('admin.user.business-compliance', ['userId' => $user->id])}}";
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