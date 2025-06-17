@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang['admin_withdraw_settlements']}}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mt-5">
                            <div class="col col-md-4 col-xs-6">
                                <div class="form-group">
                                    <label for="filter-start">Data Inicial</label>
                                    <input type="text" name="filter-start"  id="filter-start" class="form-control date-mask" >
                                </div>
                            </div>
                            <div class="col col-md-4 col-xs-6">
                                <div class="form-group">
                                    <label for="filter-end">Data Final</label>
                                    <input type="text" name="filter-end"  id="filter-end" class="form-control date-mask" >
                                </div>
                            </div>
                            <div class="col col-md-4 col-xs-6">
                                <div class="form-group">
                                    <label for="filter-status">Status</label>
                                    <select name="filter-status"  id="filter-status" class="form-control"> 
                                        <option value="0">Pendentes</option>
                                        <option value="ALL">Todos</option>
                                        <option value="1">Pagos</option>
                                        <option value="2">Cancelados</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>


                        <div class="row mt-5">
                            <div class="col col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label for="filter-text">Filtrar por nome, email, documento do cliente</label>
                                    <input type="text" name="filter-text"  id="filter-text" class="form-control" >
                                </div>
                            </div>
                        </div>


                        <div class="row mt-5">
                            <div class="col col-md-12 text-center">
                                <button type="button" class="btn btn-info btn-lg" id="btn-account-flow-filter" onclick="filtrar();">
                                    <div class="loader" id="account-flow-filter" style="display: none; float: left;"></div>
                                    Filtrar Dados
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive py-4 mt-5">
                            <table class="table table-flush" >
                                <thead>
                                    <tr>
                                        <th class="text-center">{{$lang['admin_withdraw_sn']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_ref']}}</th>
                                        <th>{{$lang['admin_withdraw_name']}}</th>
                                        <th>{{$lang['admin_withdraw_stripe_email']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_amount']}}</th>                                                                     
                                        <th class="text-center">{{$lang['admin_withdraw_charge']}}</th>                                                                     
                                        <th class="text-center">{{$lang['admin_withdraw_status']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_method']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_bank']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_agency']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_acct_no']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_acct_name']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_document']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_date']}}</th>
                                        <th class="text-center">{{$lang['admin_withdraw_last_update']}}</th>
                                        <th class="text-center">Aprovar</th>    
                                        <th class="text-center">Cancelar</th>    
                                    </tr>
                                </thead>
                                
                                <tbody id="withdrawals-table">
                                            
                                </tbody>                    
                            </table>
                        </div>


                        <div class="row mt-5">
                            <div class="col col-lg-12" id="pagination">

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

    $(document).ready(function() {
        filtrar();
    });
    
    
    var page = 1;
    
    function filtrar() {
        page = 1;
        listWithdrawals();
    }
    
    function proximaPagina() {
        page++;
        listWithdrawals();
    }
    
    
    function irParaPagina(p) {
        page = p;
        if (page < 1) {
            page = 1;
        }
        listWithdrawals();
    }
    
    function paginaAnterior() {
        page--;
        if (page < 1) {
            page = 1;
        }
        listWithdrawals();
    }
    
    
    
    function listWithdrawals() {
        let data = {
            _token: "{{ csrf_token() }}",
            start: $("#filter-start").val(),
            end: $("#filter-end").val(),
            text: $("#filter-text").val(),
            status: $("#filter-status").val(),
            page: page
        };
        
        $("#btn-account-flow-filter").prop("disabled", true);
        $("#account-flow-filter").show();
        $.post("{{route('admin.withdrawal.search')}}", data, function (json) {
            try {
                  
                if (json.success) {
                    $("#withdrawals-table").html(json.html);
                    $("#pagination").html(json.pagination);
                    
                } else {
                    toastr.error(json.message);
                }
                  
            } catch (e) {
                toastr.error(e);
            }
        }, 'json').always(function () {
            $("#btn-account-flow-filter").prop("disabled", false);
            $("#account-flow-filter").hide();
        });
        
    }

</script>
@endpush