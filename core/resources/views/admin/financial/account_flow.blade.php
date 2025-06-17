@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0">Gerenciamento de Taxas Recebidas</h3>
        </div>

        <div class="card-body">

            <form action="{{route('account.flow.export')}}" method="post" target="_fees_report">
                @csrf  
                <div class="row mt-5">
                    <div class="col col-md-3 col-xs-6">
                        <div class="form-group">
                            <label for="filter-start">Data Inicial</label>
                            <input type="text" name="filter-start"  id="filter-start" class="form-control date-mask" >
                        </div>
                    </div>
                    <div class="col col-md-3 col-xs-6">
                        <div class="form-group">
                            <label for="filter-end">Data Final</label>
                            <input type="text" name="filter-end"  id="filter-end" class="form-control date-mask" >
                        </div>
                    </div>
                    <div class="col col-md-3 col-xs-6">
                        <div class="form-group">
                            <label for="filter-type">Tipo de Lançamento</label>
                            <select name="filter-type"  id="filter-type" class="form-control"> 
                                <option value="ALL">Todos</option>
                                <option value="CREDIT">Entradas</option>
                                <option value="DEBIT">Saídas</option>
                            </select>
                        </div>
                    </div>
                    <div class="col col-md-3 col-xs-6">
                        <div class="form-group">
                            <label for="filter-entity">Tipo de Movimento</label>
                            <select name="filter-entity"  id="filter-entity" class="form-control"> 
                                <option value="ALL">Todos</option>
                                @foreach(App\Models\History::ENTITIES as $ent)
                                <option value="{{$ent}}">{{App\Models\History::getStringEntity($ent)}}</option>
                                @endforeach
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

                <div class="row">
                    <div class="col col-md-6">
                    </div>
                    <div class="col col-md-6">

                        <div class="row">
                            <div class="col col-xs-6 col-md-8" >
                                <div class="form-group row" >
                                    <div class="col-lg-12">
                                        <select class="form-control select" name="fileType" id="fileType">
                                            <option value='XLS'>Excel</option>
                                            <option value='CSV'>CSV</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col col-xs-6 col-md-4">
                                <button type="submit" class="btn btn-info btn-md w-100" >
                                    Exportar
                                </button>
                            </div>
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
            </form>



            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="mb-2">Total das Entradas: </h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h3 class="mb-2">R$ <strong id="balance-total-credits"></strong></h3>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h3 class="mb-2">Total das Saídas: </h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h3 class="mb-2">R$ <strong id="balance-total-debits"></strong></h3>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h3 class="mb-2">Saldos de Clientes: </h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h3 class="mb-2">R$ <strong id="balance-total-users"></strong></h3>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h3 class="mb-2">Saldo de Caixa: </h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h3 class="mb-2">R$ <strong id="balance-total-amount"></strong></h3>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="table-responsive py-4">
                <table class="table table-striped table-bordered table-condensed" >
                    <thead>
                        <tr>
                            <th class="text-center">Data</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Movimento</th>
                            <th class="text-center">Referência</th>
                            <th class="text-center">Tipo de Transação</th>
                            <th class="text-center">Valor</th>
                        </tr>
                    </thead>
                    
                    <tbody id="fees-table">  
                    
                    </tbody>
                </table>
            </div>

            <div class="row mt-5">
                <div class="col col-lg-12" id="pagination">

                </div>
            </div>
        </div>
        
    </div>
@stop



@push('scripts')
<script>

    $(document).ready(function() {
        
    });
    
    
    var page = 1;
    
    function filtrar() {
        page = 1;
        listFees();
    }
    
    function proximaPagina() {
        page++;
        listFees();
    }
    
    
    function irParaPagina(p) {
        page = p;
        if (page < 1) {
            page = 1;
        }
        listFees();
    }
    
    function paginaAnterior() {
        page--;
        if (page < 1) {
            page = 1;
        }
        listFees();
    }
    
    
    
    function listFees() {
        let data = {
            _token: "{{ csrf_token() }}",
            start: $("#filter-start").val(),
            end: $("#filter-end").val(),
            text: $("#filter-text").val(),
            entity: $("#filter-entity").val(),
            type: $("#filter-type").val(),
            page: page
        };
        
        $("#btn-account-flow-filter").prop("disabled", true);
        $("#account-flow-filter").show();
        $.post("{{route('account.flow.list')}}", data, function (json) {
            try {
                  
                if (json.success) {
                    $("#fees-table").html(json.html);
                    $("#pagination").html(json.pagination);
                    $("#balance-total-credits").html(json.totalCredits);
                    $("#balance-total-debits").html(json.totalDebits);
                    $("#balance-total-amount").html(json.totalAmount);
                    $("#balance-total-users").html(json.usersBalances);
                    
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