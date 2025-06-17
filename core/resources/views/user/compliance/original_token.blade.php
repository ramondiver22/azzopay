@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                
                
                
                <div class="card">
                  <div class="card-header header-elements-inline">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_original_hub_token_validation"]}}</h3>
                  </div>
                  <div class="card-body">
                      
                        <div class="text-center text-dark mb-5">
                            <span class="text-gray ">{{$lang["compliance_original_hub_token_instrunctions"]}} +{{$compliance->mobilephone_country_code}} {{$compliance->mobilephone_ddd}} {{$compliance->mobilephone}}.
                                 {{$lang["compliance_original_hub_token_instrunctions_2"]}}</span>
                        </div>
                      
                        <div class="row">
                    
                            <div class="col col-lg-3 col-md-3 col-sm-2 col-xs-0"></div>
                            <div class="col col-lg-6 col-md-6 col-sm-8 col-xs-12">
                                <div class="card mt-50">
                                    <div class="card-body text-center">
                                        <form action="{{route('submit.business-compliance-registry')}}" method="post" enctype="multipart/form-data" id="form-original-token-validation" >
                                            @csrf

                                            <div class="form-group">
                                                <label >{{$lang["compliance_original_hub_token_label"]}}</label>
                                                <input type="text" id="original-token" name="original-token" class="form-control text-center" value="" />
                                            </div>
                                            

                                            <div class="row mt-20">
                                                <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                                                    <button type="button" class="btn btn-primary" id="btn-form-validate-token" onclick="validateTokenOriginal();">
                                                        {{$lang["compliance_original_hub_token_validate_btn"]}}
                                                    </button>

                                                </div>
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
    </div>
</div>
@stop



@push('scripts')
<script>


function validateTokenOriginal() {
        
        let data = {
            code: $("#original-token").val(),
            _token: get_form_csrf_data("form-original-token-validation")
        };
        
        $("#btn-form-validate-token").prop("disabled", true);
        $.post("<?php echo url("user/original-compliance-validate")?>", data, function (json) {
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
            $("#btn-form-validate-token").prop("disabled", false);
        }).fail((json) => {
           toastr.error(json.responseJSON.message);
       });
        
    }

</script>
@endpush