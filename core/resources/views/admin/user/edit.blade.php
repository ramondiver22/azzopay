@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_users_edit_update_account_information']}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/profile-update')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_business_name']}}</label>
                                <div class="col-lg-10">
                                    <input type=""hidden value="{{$client->id}}" name="id">
                                    <input type="text" name="business_name" class="form-control" value="{{$client->business_name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_first_name']}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="first_name" class="form-control" value="{{$client->first_name}}">
                                </div>
                            </div>                          
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_last_name']}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="last_name" class="form-control" value="{{$client->last_name}}">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_email']}}</label>
                                <div class="col-lg-10">
                                    <input type="email" name="email" class="form-control" readonly value="{{$client->email}}">
                                </div>
                            </div>                            
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_support_email']}}</label>
                                <div class="col-lg-10">
                                    <input type="email" name="support_email" class="form-control" readonly value="{{$client->support_email}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_mobile']}}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="mobile" class="form-control" value="{{$client->phone}}">
                                </div>
                            </div>                                                                        
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_balance']}}</label>
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" name="balance" max-length="10" value="{{$client->balance}}" class="form-control">
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang['admin_users_edit_status']}}<span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        @if($client->email_verify==1)
                                            <input type="checkbox" name="email_verify" id=" customCheckLogin5" class="custom-control-input" value="1" checked>
                                        @else
                                            <input type="checkbox" name="email_verify"id=" customCheckLogin5"  class="custom-control-input" value="1">
                                        @endif
                                        <label class="custom-control-label" for=" customCheckLogin5">
                                        <span class="text-muted">{{$lang['admin_users_edit_email_verification']}}</span>     
                                        </label>
                                    </div>                                     
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        @if($client->fa_status==1)
                                            <input type="checkbox" name="fa_status" id=" customCheckLogin6" class="custom-control-input" value="1" checked>
                                        @else
                                            <input type="checkbox" name="fa_status" id=" customCheckLogin6"  class="custom-control-input" value="1">
                                        @endif
                                        <label class="custom-control-label" for=" customCheckLogin6">
                                        <span class="text-muted">{{$lang['admin_users_edit_2fa_security']}}</span>     
                                        </label>
                                    </div>                                                              
                                </div>
                            </div>                 
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang['admin_users_edit_save']}}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-5">

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a href="{{route('admin.email', ['email' => $client->email, 'name' => $client->business_name])}}" class="btn btn-primary btn-block m-2">{{$lang['admin_users_index_send_email']}}</a>
                    </div>

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        @if($client->status==0)
                            <a class='btn btn-danger btn-block m-2' href="{{route('user.block', ['id' => $client->id])}}">{{$lang['admin_users_index_block']}}</a>
                        @else
                            <a class='btn btn-success btn-block m-2' href="{{route('user.unblock', ['id' => $client->id])}}">{{$lang['admin_users_index_unblock']}}</a>
                        @endif
                    </div>

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a data-toggle="modal" data-target="#delete{{$client->id}}" href="" class="btn btn-danger btn-block m-2">{{$lang['admin_users_index_delete']}}</a>
                    </div>

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a class='btn btn-success btn-block m-2' href="{{route('user.taxes', ['id' => $client->id])}}">{{$lang['admin_users_index_taxes']}}</a>
                    </div>

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a data-toggle="modal" data-target="#modal-change-enroller" href="" class="btn btn-primary btn-block m-2">Alterar Patrocinador</a>
                    </div>

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a href="{{route('admin.user.compliance', ['id' => $client->id])}}" class="btn btn-primary btn-block m-2">Compliance</a>
                    </div>       

                    <div class="col col-xs-12 col-lg-3 col-md-4 col-sm-6">
                        <a data-toggle="modal" data-target="#modal-change-password" href="" class="btn btn-primary btn-block m-2">Alterar Senha</a>
                    </div>
                                    
                </div>


            </div>
            <div class="col-md-4">
                
                <div class="card">
                    <div class="card-body text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid rounded-circle" src="{{url('/')}}/asset/profile/{{$client->image}}" width="120" height="120" alt="">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_users_edit_compliance']}}</h3>
                    </div>                    
                    <div class="card-body">
                        
                        <div class="alert alert-{{App\Models\Compliance::STATUS_PERSONAL[$xver->status_personal]['label']}} mt-5">
                            Verificação de Identidade: {{App\Models\Compliance::STATUS_PERSONAL[$xver->status_personal]['text']}}
                        </div>
                    
                        <div class="alert alert-{{App\Models\Compliance::STATUS_BUSINESS[$xver->status_business]['label']}} mt-5">
                            Verificação Empresarial: {{App\Models\Compliance::STATUS_BUSINESS[$xver->status_business]['text']}}
                        </div>
                    
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                            <div>
                                <ul class="list list-unstyled mb-0">
                                    <li><span class="text-sm">{{$lang['admin_users_edit_joined']}} {{date("Y/m/d h:i:A", strtotime($client->created_at))}}</span></li>
                                    <li><span class="text-sm">{{$lang['admin_users_edit_last_login']}} {{date("Y/m/d h:i:A", strtotime($client->last_login))}}</span></li>
                                    <li><span class="text-sm">{{$lang['admin_users_edit_last_update']}} {{date("Y/m/d h:i:A", strtotime($client->updated_at))}}</span></li>
                                    <li><span class="text-sm">{{$lang['admin_users_edit_ip_address']}} {{$client->ip_address}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">{{$lang['admin_users_edit_audit_logs']}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                        <thead>
                            <tr>
                            <th>{{$lang['admin_users_edit_sn']}}</th>
                            <th>{{$lang['admin_users_edit_reference_id']}}</th>
                            <th>{{$lang['admin_users_edit_reference_id']}}</th>
                            <th>{{$lang['admin_users_edit_created']}}</th>
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($audit as $k=>$val)
                            <tr>
                                <td>{{++$k}}.</td>
                                <td>#{{$val->trx}}</td>
                                <td>{{$val->log}}</td>
                                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="delete{{$client->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang['admin_users_index_are_you_sure_you_want_to_delete']}}</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_users_index_close']}}</button>
                            <a  href="{{route('user.delete', ['id' => $client->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_users_index_proceed']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-change-enroller" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">Alterar Patrocinador do cliente</h3>
                            
                            <div class="row">
                                <div class="col col-xs-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Informe o email ou id do patrocinador</label>
                                        <div class="input-group">
                                            <input type="text" name="modal-change-enroller-id"  id="modal-change-enroller-id"  value="" class="form-control">
                                            <span class="input-group-append" style="cursor: pointer;" onclick="searchForEnroller();">
                                                <span class="input-group-text">Buscar</span>
                                            </span>
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col col-xs-12">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <strong>Patrocinador Selecionado: </strong> <strong id="modal-change-enroller-name"></strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-change-enroller-cancel">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-change-enroller-save" onclick="changeUserEnroller();">Alterar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">Alterar Senha do cliente</h3>
                            
                            <div class="row">
                                <div class="col col-xs-12">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label class="col-form-label" for="modal-change-password-new">Informe a nova senha</label>
                                            <input type="text" name="modal-change-password-new" id="modal-change-password-new" class="form-control" placeholder="" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col col-xs-12">
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label class="col-form-label" for="modal-change-password-confirm">Confirme a nova senha</label>
                                            <input type="text" name="modal-change-password-confirm" id="modal-change-password-confirm" class="form-control" placeholder="" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal" id="modal-change-password-cancel">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm" id="modal-change-password-save" onclick="changeUserPasword();">Alterar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop



@push('scripts')
<script>
 
    function searchForEnroller() {

        let data = {
            _token: "{{ csrf_token() }}",
            code: $("#modal-change-enroller-id").val(),
            user: {{$client->id}}
        }

        $("#modal-change-enroller-id").prop("disabled", true);
        $.post("{{route('user.manage.search.enroler')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-change-enroller-name").html(json.name);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-change-enroller-id").prop("disabled", false);
        });
    }


    function changeUserEnroller() {

        let data = {
            _token: "{{ csrf_token() }}",
            code: $("#modal-change-enroller-id").val(),
            user: {{$client->id}}
        }

        $("#modal-change-enroller-cancel, #modal-change-enroller-save").prop("disabled", true);
        $.post("{{route('user.manage.change.enroller')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-change-enroller-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    setTimeout(() => {
                        location = "{{route('user.manage', ['id' => $client->id])}}";
                    }, 3000);
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-change-enroller-cancel, #modal-change-enroller-save").prop("disabled", false);
        });
    }


    function changeUserPasword() {

        let data = {
            _token: "{{ csrf_token() }}",
            password: $("#modal-change-password-new").val(),
            confirmation: $("#modal-change-password-confirm").val(),
            user: {{$client->id}}
        }

        $("#modal-change-password-cancel, #modal-change-password-save").prop("disabled", true);
        $.post("{{route('user.manage.change.password')}}", data, function (json) {
            try {
                if (json.success) {
                    $("#modal-change-password-cancel").prop("disabled", false).trigger("click");
                    toastr.success(json.message);
                    
                } else {
                    toastr.error(json.message);
                }
            } catch (e) {
                toastr.error(e);
            }
        }, "json").always(function () {
            $("#modal-change-password-cancel, #modal-change-password-save").prop("disabled", false);
        });
    }

         

</script>
@endpush