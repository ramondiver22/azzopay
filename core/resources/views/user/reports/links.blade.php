
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0 font-weight-bolder">{{$lang["report_links_title"]}}</h3>
        </div>
        
        <div class="card-body">
            <form action="{{route('user.submit.reports.links')}}" method="post">
                @csrf
                <div class="row mt-5">
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="start_date">{{$lang["report_links_starte_date"]}}</label>
                            <input type="text" id="start_date" name="start_date" class="form-control date-mask" autocomplete="off" required="" value="{{$startDate}}">
                        </div>
                    </div>
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="end_date">{{$lang["report_links_end_date"]}}</label>
                            <input type="text" id="end_date" name="end_date" class="form-control date-mask" autocomplete="off" required="" value="{{$endDate}}">
                        </div>
                    </div>

                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <button type="submit" class="btn btn-neutral my-4" ><i class="fad fa-external-link"></i>{{$lang["report_links_btn_search"]}}</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-buttons">
                    <thead>
                    <tr>
                        <th>{{$lang["report_links_code"]}}</th>
                        <th>{{$lang["report_links_date"]}}</th>
                        <th>{{$lang["report_links_campaign"]}}</th>
                        <th>{{$lang["report_links_credit"]}}</th>
                        <th>{{$lang["report_links_charges"]}}</th>
                        <th>{{$lang["report_links_received_total"]}}</th>
                    </tr>

                    </thead>
                    <tbody> 
                    @php
                        $valorTotal = 0;
                        $chargeTotal = 0;
                        $valorRecebidoTotal = 0;
                    @endphp 
                    @foreach($links as $link)
                        @php
                            $valorTotal += $link->amount;
                            $chargeTotal += $link->charge;
                            $valorRecebidoTotal += ($link->amount + $link->charge);
                        @endphp
                        <tr>
                            <td>{{$link->ref_id}}.</td>
                            <td>{{date("d/m/Y h:i:s", strtotime($link->created_at))}}</td>
                            <td>{{$link->name}}</td>
                            <td>{{$currency->symbol.number_format($link->amount, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($link->charge, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format(($link->amount + $link->charge), 2, '.', '')}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="3">{{$lang["report_links_total"]}}</th>
                            <td>{{$currency->symbol.number_format($valorTotal, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($chargeTotal, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($valorRecebidoTotal, 2, '.', '')}}</td>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@stop