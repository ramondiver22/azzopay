
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_log_transaction_history"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["virtual_log_sn"]}}</th>
              <th>{{$lang["virtual_log_amount"]}}</th>
              <th>{{$lang["virtual_log_description"]}}</th>
              <th>{{$lang["virtual_log_type"]}}</th>
              <th>{{$lang["virtual_log_created"]}}</th>
            </tr>
          </thead>
          <tbody>  
          @php 
          $item=array();
          $item=json_decode($log, true); 
          @endphp
            @foreach($item['data'] as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>
                @if($val['product']=='Card Issuance Fee')
                {{$currency->symbol.number_format($val['amount']*$set->virtual_createcharge+$set->virtual_createchargep, 2, '.', '')}}
                @else
                {{$currency->symbol.number_format($val['amount'], 2, '.', '')}}
                @endif
                </td>
                <td>{{$val['gateway_reference_details']}}</td>
                <td>
                @if($val['indicator']=='C')
                  <span class="badge badge-pill badge-primary">{{$lang["virtual_log_credit"]}}</span>
                @elseif($val['indicator']=='D')
                  <span class="badge badge-pill badge-primary">{{$lang["virtual_log_debit"]}}</span>                        
                @endif
                </td>
                <td>{{date("Y/m/d h:i:A", strtotime($val['created_at']))}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop