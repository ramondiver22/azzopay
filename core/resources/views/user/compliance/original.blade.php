@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_original_hub_page_title"]}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.original-hub-save')}}" method="post" enctype="multipart/form-data" id="form-original-compliance">
                            @csrf
                            

                            <div class="row">
                                <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                    
                                    <div class="card">
                                        <div class="card-header header-elements-inline">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["compliance_original_hub_bank_account_title"]}}</h3>
                                        </div>
                                        <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_original_hub_bank_account_label"]}}</label>
                                                        <div class="col-lg-12">
                                                            <select class="form-control select" name="bank" id="bank" required>
                                                                <option value="">{{$lang["select_bank"]}}</option> 
                                                                    @foreach($bnk as $val)
                                                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                                                    @endforeach
                                                            </select>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_original_hub_bank_agency_label"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="branch" id="branch" maxlength="80"  class="form-control" value="" required>    
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-lg-12">{{$lang["compliance_original_hub_bank_account_label"]}}</label>
                                                        <div class="col-lg-12">
                                                            <input type="text" name="account" id="account" maxlength="80"  class="form-control" value="" required>    
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
                                                    <button type="button" class="btn btn-info btn-block" id="btn-save-register" onclick="saveOriginalRegister();">{{$lang["compliance_original_hub_btn_save_register"]}} 
                                                        
                                                    </button>
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

    function saveOriginalRegister() {


        let data = {
            bank: $("#bank").val(),
            branch: $("#branch").val(),
            account: $("#account").val(),
            _token: get_form_csrf_data("form-original-compliance")
        };
        
        $("#btn-save-register").prop("disabled", true);
        $.post("<?php echo route("user.original-hub-save"); ?>", data, function (json) {
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
            $("#btn-save-register").prop("disabled", false);
        }).fail((json) => {
           toastr.error(json.responseJSON.message);
       });
        

    }

</script>
@endpush