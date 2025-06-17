
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h3 class="mb-0 font-weight-bolder">{{$lang["report_products_title"]}}</h3>
        </div>
       
    
        <div class="card-body">
            <form action="{{route('user.submit.reports.links')}}" method="post">
                @csrf
                <div class="row mt-5">
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="start_date">{{$lang["report_products_starte_date"]}}</label>
                            <input type="text" id="start_date" name="start_date" class="form-control date-mask" autocomplete="off" required="" value="{{$startDate}}">
                        </div>
                    </div>
                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-6">
                        <div class="form-group">
                            <label for="end_date">{{$lang["report_products_end_date"]}}</label>
                            <input type="text" id="end_date" name="end_date" class="form-control date-mask" autocomplete="off" required="" value="{{$endDate}}">
                        </div>
                    </div>

                    <div class="col col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                        <button type="submit" class="btn btn-neutral my-4" ><i class="fad fa-external-link"></i>{{$lang["report_products_btn_search"]}}</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-buttons">
                    <thead>
                        <tr>
                            <th>{{$lang["report_products_code"]}}</th>
                            <th>{{$lang["report_products_date"]}}</th>
                            <th>{{$lang["report_products_client"]}}</th>
                            <th>{{$lang["report_products_product"]}}</th>
                            <th>{{$lang["report_products_quantity"]}}</th>
                            <th>{{$lang["report_products_credit"]}}</th>
                            <th>{{$lang["report_products_shipping_fee"]}}</th>
                            <th>{{$lang["report_products_charges"]}}</th>
                            <th>{{$lang["report_products_received_total"]}}</th>
                        </tr>
                    </thead>
                    <tbody> 
                    @php
                        $valorTotal = 0;
                        $chargeTotal = 0;
                        $valorShippingTotal = 0;
                        $valorRecebidoTotal = 0;
                        $quantityTotal = 0;
                    @endphp 
                    @foreach($products as $order)
                        @php
                            $valorTotal += ($order->amount - $order->charge);
                            $chargeTotal += $order->charge;
                            $quantityTotal += $order->quantity;
                            $valorShippingTotal += $order->shipping_fee;
                            $valorRecebidoTotal += ($order->total);
                        @endphp
                        <tr>
                            <td>{{$order->ref_id}}.</td>
                            <td>{{date("d/m/Y h:i:s", strtotime($order->created_at))}}</td>
                            <td>{{$order->first_name}} {{$order->last_name}}</td>
                            <td>{{$order->name}}</td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$currency->symbol.number_format(($order->amount - $order->charge), 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($order->shipping_fee, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($order->charge, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format(($order->total), 2, '.', '')}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4">{{$lang["report_products_total"]}}</th>
                            <td>{{$quantityTotal}}</td>
                            <td>{{$currency->symbol.number_format($valorTotal, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($valorShippingTotal, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($chargeTotal, 2, '.', '')}}</td>
                            <td>{{$currency->symbol.number_format($valorRecebidoTotal, 2, '.', '')}}</td>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@stop
    