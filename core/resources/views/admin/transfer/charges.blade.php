
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{$lang['admin_transfer_charges_title']}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang['admin_transfer_charges_sn']}}</th>
              <th>{{$lang['admin_transfer_charges_amount']}}</th>
              <th>{{$lang['admin_transfer_charges_reference_id']}}</th>
              <th>{{$lang['admin_transfer_charges_log']}}</th>
              <th>{{$lang['admin_transfer_charges_created']}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($charges as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>{{$currency->symbol.number_format((float)$val->amount,2, '.', '')}}</td>
                <td>#{{$val->ref_id}}</td>
                <td>{{$val->log}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop