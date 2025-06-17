@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h3 class="mb-0">{{$lang['admin_transfer_invoice_invoice_logs']}}</h3>
                    </div>

                    <div class="card-body">

                        <form id="form-table-filter" class="mt-3">
                            
                            <div class="row p-2">
                                <div class="col-md-4">
                                    <div class="form-group mt-2">
                                        <label for="filter-start mb-2">Data inicial</label>
                                        <div class="input-group input-group-alternative input-group-merge">
                                            <input class="form-control date-mask"
                                                placeholder=""
                                                name="filter-start" id="filter-start" type="text" value="{{ date('d/m/Y', strtotime('-1 month')) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-2">
                                        <label for="filter-end mb-2">Data final</label>
                                        <div class="input-group input-group-alternative input-group-merge">
                                            <input class="form-control date-mask"
                                                placeholder=""
                                                name="filter-end" id="filter-end"  type="text" value="{{ date('d/m/Y') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-2">
                                        <label for="filter-status mb-2">Status</label>
                                        <select name="filter-status" id="filter-status" class="form-control">
                                            <option value="T">Todas as Invoices</option>
                                            <option value="0">Pendentes</option>
                                            <option value="1">Pagas</option>
                                            <option value="2">Canceladas</option>                                          
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mt-2">

                                        <label for="filter-search mb-2">Buscar por nome do cliente</label>
                                        <div class="input-group input-group-alternative input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fad fa-search"></i></span>
                                            </div>
                                            <input class="form-control"
                                                id="filter-search" name="filter-search" type="text">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 text-center mt-5">
                                    <button class="btn btn-sm btn-neutral" type="button" id="btn-filtrar-invoice" onclick="filtrar();">
                                        <span class="fad fa-filter mr-1"></span> Filtrar Registros
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive py-4 mt-3 table-bordered border-dark">

                            <table class="table table-flush table-bordered" >
                                
                                <tbody id="invoices-body">
                                             
                                </tbody>                    
                            </table>
                        </div>


                        <div class="row mt-5">
                            <div class="col col-lg-12 col-xs-12 col-md-12 col-sm-12">
                                <nav aria-label="Page navigation" class="text-center" id="invoices-pagination" >

                                </nav>
                            </div>
                        </div>

                    </div>
                </div>


                @foreach($invoice as $k=>$val)
                <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card bg-white border-0 mb-0">
                                    <div class="card-header">
                                        <h3 class="mb-0">{{$lang['admin_transfer_invoice_are_you_sure_you_want_to_delete']}}</h3>
                                    </div>
                                    <div class="card-body px-lg-5 py-lg-5 text-right">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_invoice_close']}}</button>
                                        <a  href="{{route('invoice.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_transfer_invoice_proceed']}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach


            </div>
        </div>
    </div>
</div>



@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        listInvoices();
    });
    
    
    var page = 1;
    
    function filtrar() {
        page = 1;
        listInvoices();
    }
    
    function proximaPagina() {
        page++;
        listInvoices();
    }
    
    
    function irParaPagina(p) {
        page = p;
        if (page < 1) {
            page = 1;
        }
        listInvoices();
    }
    
    function paginaAnterior() {
        page--;
        if (page < 1) {
            page = 1;
        }
        listInvoices();
    }
    
    
    function listInvoices() {
        let data = {
            _token: "{{ csrf_token() }}",
            start: $("#filter-start").val(),
            end: $("#filter-end").val(),
            status: $("#filter-status").val(),
            search: $("#filter-search").val(),
            page: page
        };
            
        $("#btn-filtrar-invoice").prop("disabled", true);
        $.post("{{route('invoice.search')}}", data, function (json) {
            try {
                
                if (json.success) {
                    $("#invoices-body").html(json.html);
                    $("#invoices-pagination").html(json.pagination);
                } else {
                    toastr.error(json.message);
                }
                
            } catch (e) {
                toastr.error(e);
            }
        }, 'json').always(function () {
            $("#btn-filtrar-invoice").prop("disabled", false);
        });
        
    }
</script>
@endpush


@stop