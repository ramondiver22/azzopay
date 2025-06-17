
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header">
        <h3 class="mb-0 font-weight-bolder">{{$plan->name}} - #{{$plan->ref_id}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["plans_sn"]}}</th>
              <th>{{$lang["plans_amount"]}}</th>
              <th>{{$lang["plans_charge"]}}</th>
              <th>{{$lang["plans_subscriber"]}}</th>
              <th>{{$lang["plans_reference_id"]}}</th>
              <th>{{$lang["plans_expiring_date"]}}</th>
              <th>{{$lang["plans_renewal"]}}</th>
              <th>{{$lang["plans_created"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($sub as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>{{$currency->symbol.number_format($val->charge, 2, '.', '')}}</td>
                <td>{{$val->user['first_name'].' '.$val->user['last_name']}}</td>
                <td>#{{$val->ref_id}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->expiring_date))}}</td>
                <td>@if($val->times>0 && $val->status==1) {{$lang["plans_yes"]}} @else {{$lang["plans_no"]}} @endif</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop