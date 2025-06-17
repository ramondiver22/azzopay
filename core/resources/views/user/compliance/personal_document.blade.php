@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_module_personal_title"]}}</h3>
                    </div>
                    <div class="card-body">
                    
                        @if(empty($compliance->idcard) || empty($compliance->idcard_back) || in_array($compliance->personal_document_status, Array("NONE", "REJECTED"))) 
                        <form action="{{route('submit.personal-compliance-document')}}" method="post" enctype="multipart/form-data" id="form-personal-id-compliance"  >
                            @csrf


                            <div class="row">

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="">{{$lang["compliance_personal_document_type"]}}</label>  
                                        <select class="form-control select" name="id_type" id="id_type">
                                            <option value="National ID" @if($ver->id_type=="National ID") selected @endif>{{$lang["compliance_personal_document_id_card"]}}</option>
                                            <option value="International Passport" @if($ver->id_type=="International Passport") selected @endif>{{$lang["compliance_personal_document_passport"]}}</option>
                                            <option value="Voters Card" @if($ver->id_type=="Voters Card") selected @endif>{{$lang["compliance_personal_document_voters_card"]}}</option>
                                            <option value="Driver License" @if($ver->id_type=="Driver License") selected @endif>{{$lang["compliance_personal_document_drivers_license"]}}</option>
                                        </select>                       
                                    </div>
                                </div>

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="">{{$lang["compliance_personal_document_type_number"]}}</label>
                                        <input type="text" name="document" id="document" maxlength="20"  class="form-control" value="" required>    
                                        
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="">{{$lang["compliance_personal_document_front"]}}</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"  id="personal_id_document_front" name="personal_id_document_front" accept="image/*">
                                            <label class="custom-file-label sdsd" for="personal_id_document_front">{{$lang["compliance_personal_document_front"]}}</label>
                                        </div> 
                                    </div>  
                                </div>
                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="">{{$lang["compliance_personal_document_back"]}}</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"  id="personal_id_document_back" name="personal_id_document_back" accept="image/*">
                                            <label class="custom-file-label sdsd" for="personal_id_document_back">{{$lang["compliance_personal_document_back"]}}</label>
                                        </div> 
                                    </div>  
                                </div>
                            </div>

                                        
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm" id="btn-document-submit" >{{$lang["compliance_personal_document_upload"]}}</button>
                            </div>

                        </form>  
                        @endif   


                        @if($compliance->personal_document_status == "REJECTED")  
                        <div class="row mt-3"  id="row-document-rejected" >
                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-danger text-center">
                                    <i class="fa fa-times"></i> Documento Rejeitado <br>
                                    {{$compliance->personal_document_msg}}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($compliance->personal_document_status == "APPROVED")  
                        <div class="row" id="row-document-approved" >
                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-success text-center">
                                    <i class="fa fa-check"></i> Documento Aprovado
                                </div>
                            </div>
                        </div> 
                        @endif 
                        

                        <div class="row" id="row-document-analyses" @if($compliance->personal_document_status != "PENDING") style="display:none;" @endif >
                            <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-warning text-center">
                                    <i class="fa fa-hourglass"></i> Documento em análise
                                </div>
                            </div>
                        </div>
                        

                        <hr>
                          

                            <div class="row">

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">

                                    @if(empty($compliance->proof) || in_array($compliance->personal_proof_status, Array("NONE", "REJECTED"))) 
                                    <form action="{{route('submit.personal-compliance-proof')}}" method="post" enctype="multipart/form-data" id="form-personal-residence"  >
                                        @csrf
                                        <div class="form-group">
                                            <label class="">{{$lang["compliance_personal_document_residence"]}}</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"  id="personal_id_document_residence" name="personal_id_document_residence" accept="image/*">
                                                <label class="custom-file-label sdsd" for="personal_id_document_residence">{{$lang["compliance_personal_document_placeholder_residence"]}}</label>
                                            </div> 
                                        </div>  

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success btn-sm" id="btn-proof-submit">{{$lang["compliance_personal_document_upload"]}}</button>
                                        </div>
                                    </form>
                                    @endif

                                    @if($compliance->personal_proof_status == "REJECTED")  
                                    <div class="row mt-3"  id="row-proof-rejected" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-danger text-center">
                                                <i class="fa fa-times"></i> Comprovante de Residência Rejeitado <br>
                                                {{$compliance->personal_proof_msg}}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                        

                                    @if($compliance->personal_proof_status == "APPROVED")  
                                    <div class="row" id="row-proof-approved" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-success text-center">
                                                <i class="fa fa-check"></i> Comprovante de Residência Aprovado
                                            </div>
                                        </div>
                                    </div> 
                                    @endif 
                                     
                                    <div class="row"  id="row-proof-analyses"  @if($compliance->personal_proof_status != "PENDING") style="display:none;" @endif  >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-hourglass"></i> Comprovante de Residência em Análise
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-lg-6 col-md-6 col-sm-6 col-xs-6">


                                    @if(empty($compliance->selfie) || in_array($compliance->personal_selfie_status, Array("NONE", "REJECTED"))) 
                                    <form action="{{route('submit.personal-compliance-selfie')}}" method="post" enctype="multipart/form-data" id="form-personal-selfie" >
                                        @csrf
                                        <div class="form-group">
                                            <label class="">{{$lang["compliance_personal_document_selfie"]}}</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"  id="personal_id_document_selfie" name="personal_id_document_selfie" accept="image/*">
                                                <label class="custom-file-label sdsd" for="personal_id_document_selfie">{{$lang["compliance_personal_document_placeholder_selfie"]}}</label>
                                            </div> 
                                        </div>  

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success btn-sm" id="btn-selfie-submit">{{$lang["compliance_personal_document_upload"]}}</button>
                                        </div>
                                    </form>
                                    @endif

                                    @if($compliance->personal_selfie_status == "REJECTED")  
                                    <div class="row mt-3"  id="row-selfie-rejected" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-danger text-center">
                                                <i class="fa fa-times"></i> Selfie Rejeitada <br>
                                                {{$compliance->personal_selfie_msg}}
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($compliance->personal_selfie_status == "APPROVED")  
                                    <div class="row" id="row-selfie-approved" >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-success text-center">
                                                <i class="fa fa-check"></i> Selfie Aprovada
                                            </div>
                                        </div>
                                    </div> 
                                    @endif 
                                    
                                    <div class="row" id="row-selfie-analyses"  @if($compliance->personal_selfie_status != "PENDING") style="display:none;" @endif >
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-hourglass"></i> Slefie em Análise
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
                <a class="btn btn-info btn-block" href="{{route('user.personal-compliance')}}">{{$lang["compliance_personal_btn_back_to_data"]}}</a>
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

        $("#form-personal-id-compliance").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#btn-document-submit").prop("disabled", true);
            },
            success: function (json) {
                try {
                
                    if (json.success) {


                        $("#form-personal-id-compliance").hide();
                        $("#row-document-rejected").remove();
                        $("#row-document-analyses").show();
                        toastr.success(json.message);
                        $("#btn-document-submit").prop("disabled", false);
                    } else {
                        toastr.error(json.message);
                        $("#btn-document-submit").prop("disabled", false);
                    }
                    
                } catch(e) {
                    toastr.error(e);
                    $("#btn-document-submit").prop("disabled", false);
                }
            }
        });
        
    
        $("#form-personal-residence").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#btn-proof-submit").prop("disabled", true);
            },
            success: function (json) {
                try {
                
                    if (json.success) {


                        $("#form-personal-residence").hide();
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


        $("#form-personal-selfie").ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#btn-selfie-submit").prop("disabled", true);
            },
            success: function (json) {
                try {
                
                    if (json.success) {
                        toastr.success(json.message);
                        
                        $("#btn-selfie-submit").prop("disabled", false);

                        $("#row-selfie-rejected").remove();
                        $("#form-personal-selfie").hide();
                        $("#row-selfie-analyses").show();
                    } else {
                        toastr.error(json.message);
                        $("#btn-selfie-submit").prop("disabled", false);
                    }
                    
                } catch(e) {
                    toastr.error(e);
                    $("#btn-selfie-submit").prop("disabled", false);
                }
            }
        });

    });
</script>
@endpush
    