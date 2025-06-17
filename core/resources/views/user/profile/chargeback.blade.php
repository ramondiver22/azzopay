
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["profile_charge_back_charge_backs"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["profile_charge_back_sn"]}}</th>
              <th>{{$lang["profile_charge_back_amount"]}}</th>
              <th>{{$lang["profile_charge_back_reference_id"]}}</th>
              <th>{{$lang["profile_charge_back_created"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($charges as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>{{$currency->symbol.number_format($val->amount+$val->charge, 2, '.', '')}}</td>
                <td>#{{$val->ref}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop