@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_business_document_title"]}}</h3>
                    </div>
                    <div class="card-body">
                    
                          

                            <div class="row">

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                

                                    @if(empty($compliance->business_proof) || in_array($compliance->business_proof_status, Array("NONE", "REJECTED")))  
                                    <form action="{{route('submit.business-compliance-proof')}}" method="post" enctype="multipart/form-data" id="form-business-residence"  >
                                        @csrf
                                        <div class="form-group">
                                            <label class="">{{$lang["compliance_business_document_residence"]}}</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"  id="business_id_document_residence" name="business_id_document_residence" accept="image/*">
                                                <label class="custom-file-label sdsd" for="business_id_document_residence">{{$lang["compliance_business_document_placeholder_proof"]}}</label>
                                            </div> 
                                        </div>  

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success btn-sm" id="btn-proof-submit">{{$lang["compliance_business_document_upload"]}}</button>
                                        </div>
                                    </form>
                                    @endif


                                    @if($compliance->business_proof_status == "REJECTED")  
                                    <div class="row"  id="row-proof-rejected" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-danger text-center">
                                                <i class="fa fa-times"></i> Comprovante de Endereço Rejeitado <br>
                                                {{$compliance->business_proof_msg}}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                     
                                    @if($compliance->business_proof_status == "APPROVED")  
                                    <div class="row"  id="row-proof-approved" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-success text-center">
                                                <i class="fa fa-check"></i> Comprovante de Endereço Aprovado
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row"  id="row-proof-analyses"  @if($compliance->business_proof_status != "PENDING") style="display:none;" @endif >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-hourglass"></i> Comprovante de Endereço em análise
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                    @if(empty($compliance->business_national_registry) || in_array($compliance->business_registry_status, Array("NONE", "REJECTED")) )
                                    <form action="{{route('submit.business-compliance-registry')}}" method="post" enctype="multipart/form-data" id="form-business-institutional" >
                                        @csrf
                                        <div class="form-group">
                                            <label class="">{{$lang["compliance_business_document_national_registry"]}}</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"  id="business_id_document_institutional" name="business_id_document_institutional" accept="image/*">
                                                <label class="custom-file-label sdsd" for="business_id_document_institutional">{{$lang["compliance_business_document_placeholder_national_registry"]}}</label>
                                            </div> 
                                        </div>  

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success btn-sm" id="btn-institutional-submit">{{$lang["compliance_business_document_national_registry_upload"]}}</button>
                                        </div>
                                    </form>
                                    @endif



                                    @if($compliance->business_registry_status == "REJECTED")  
                                    <div class="row"  id="row-institutional-rejected" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-danger text-center">
                                                <i class="fa fa-times"></i> Comprovante de Cadastro de CNPJ Rejeitado <br>
                                                {{$compliance->business_registry_msg}}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                     
                                    @if($compliance->business_registry_status == "APPROVED")  
                                    <div class="row"  id="row-institutional-approved" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-success text-center">
                                                <i class="fa fa-check"></i> Comprovante de Cadastro de CNPJ Aprovado
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row"   id="row-institutional-analyses" @if($compliance->business_registry_status != "PENDING") style="display:none;" @endif >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-hourglass"></i> Comprovante de Cadastro de CNPJ em análise
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>


                    </div>
                        
                </div>
                    
            </div>

        </div>
        <br><br><br>
        <div class="row">
            <div class="col col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <a class="btn btn-info btn-block" href="{{route('user.business-compliance')}}">{{$lang["compliance_personal_btn_back_to_data"]}}</a>
            </div>
            <div class="col col-lg-6 col-md-4 col-sm-0 col-xs-0"></div>
            <div class="col col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <a class="btn btn-primary btn-block" href="{{route('user.account-compliance')}}">{{$lang["compliance_personal_btn_back_to_beginning"]}}</a>
            </div>
        </div>

    </div>

</div>

        
@stop


@push('scripts')
<script>
        
    $(document).ready(function () {

        
        $("#form-business-residence").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#btn-proof-submit").prop("disabled", true);
            },
            success: function (json) {
                try {
                
                    if (json.success) {


                        $("#form-business-residence").hide();
                        $("#row-proof-rejected").remove();
                        $("#row-proof-analyses").show();
                        toastr.success(json.message);
                        
                        $("#btn-proof-submit").prop("disabled", false);
                    } else {
                        toastr.error(json.message);
                        $("#btn-proof-submit").prop("disabled", false);
                    }
                    
                } catch(e) {
                    toastr.error(e);
                    $("#btn-proof-submit").prop("disabled", false);
                }
            }
        });


        $("#form-business-institutional").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#btn-institutional-submit").prop("disabled", true);
            },
            success: function (json) {
                try {
                
                    if (json.success) {
                        toastr.success(json.message);
                        
                        $("#btn-institutional-submit").prop("disabled", false);

                        $("#form-business-institutional").hide();
                        $("#row-institutional-rejected").remove();
                        $("#row-institutional-analyses").show();
                    } else {
                        toastr.error(json.message);
                        $("#btn-institutional-submit").prop("disabled", false);
                    }
                    
                } catch(e) {
                    toastr.error(e);
                    $("#btn-institutional-submit").prop("disabled", false);
                }
            }
        });

    });
</script>
@endpush
    