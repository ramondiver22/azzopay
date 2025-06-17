
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["profile_audit_audit_logs"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["profile_audit_sn"]}}</th>
              <th>{{$lang["profile_audit_reference_id"]}}</th>
              <th>{{$lang["profile_audit_log"]}}</th>
              <th>{{$lang["profile_audit_created"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($audit as $k=>$val)
              <tr>
                <td>{{++$k}}.</td>
                <td>#{{$val->trx}}</td>
                <td>{{$val->log}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop