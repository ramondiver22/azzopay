
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0 font-weight-bolder">{{$lang["report_donations_title"]}}</h3>
        </div>

        <div class="card-body">


            <form action="{{route('user.submit.reports.donations')}}" method="post">
                @csrf
                <div class="row mt-5">
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="start_date">{{$lang["report_donations_start_date"]}}</label>
                            <input type="text" id="start_date" name="start_date" class="form-control date-mask" autocomplete="off" required="" value="{{$startDate}}">
                        </div>
                    </div>
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="end_date">{{$lang["report_donations_end_date"]}}</label>
                            <input type="text" id="end_date" name="end_date" class="form-control date-mask" autocomplete="off" required="" value="{{$endDate}}">
                        </div>
                    </div>

                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <button type="submit" class="btn btn-neutral my-4" ><i class="fad fa-external-link"></i>{{$lang["report_donations_btn_filter"]}}</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-buttons">
                    <thead>
                    <tr>
                        <th>{{$lang["report_donations_cod"]}}</th>
                        <th>{{$lang["report_donations_campaign"]}}</th>
                        <th>{{$lang["report_donations_donnor"]}}</th>
                        <th>{{$lang["report_donations_date"]}}</th>
                        <th>{{$lang["report_donations_liquid_amount"]}}</th>
                        <th>{{$lang["report_donations_fees"]}}</th>
                        <th>{{$lang["report_donations_received_total"]}}</th>
                    </tr>
                    </thead>
                    <tbody> 
                    @php
                        $valorTotal = 0;
                        $chargeTotal = 0;
                        $valorRecebidoTotal = 0;
                    @endphp 
                    @foreach($donations as $donation)
                        @php
                            $valorTotal += $donation->amount;
                            $chargeTotal += $donation->charge;
                            $valorRecebidoTotal += ($donation->amount + $donation->charge);
                        @endphp
                        <tr>
                            <td>{{$donation->ref_id}}.</td>
                            <td>{{$donation->campaign}}</td>
                            <td>@if($donation->anonymous > 0) {{$lang["report_donations_anonymous"]}} @else {{$donation->first_name}} {{$donation->last_name}} @endif</td>
                            <td>{{date("d/m/Y h:i:s", strtotime($donation->created_at))}}</td>
                            <td>{{$currency->symbol.number_format($donation->amount, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($donation->charge, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($donation->amount+$donation->charge, 2, '.', '')}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4">{{$lang["report_donations_total"]}}</th>
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