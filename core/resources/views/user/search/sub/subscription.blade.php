
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["plans_subscriptions"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["plans_plan"]}}</th>
              <th>{{$lang["donation_reference_id"]}}</th>
              <th>{{$lang["plans_expiring_date"]}}</th>
              <th>{{$lang["plans_renewal"]}}</th>
              <th>{{$lang["donation_created"]}}</th>
            </tr>
          </thead>
          <tbody>  
              <tr>
                <td>1.</td>
                <td>@if($val->plan['amount']==null){{$currency->symbol.$val->amount}} @else {{$currency->symbol.$val->plan['amount']}} @endif</td>
                <td>{{$val->plan['name']}}</td>
                <td>#{{$val->ref_id}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->expiring_date))}}</td>
                <td>@if($val->times>0 && $val->status==1) {{$lang["invoice_yes"]}} @else {{$lang["invoice_no"]}} @endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

@stop