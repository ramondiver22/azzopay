
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["bill_categories"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["bill_s_n"]}}</th>
              <th>{{$lang["bill_biller_code"]}}</th>
              <th>{{$lang["bill_name"]}}</th>
              <th>{{$lang["bill_country"]}}</th>
              <th>{{$lang["bill_country"]}}</th>
              <th>{{$lang["bill_name"]}}</th>
              <th>{{$lang["bill_item_code"]}}</th>
              <th>{{$lang["bill_short_name"]}}</th>
              <th>{{$lang["bill_label_name"]}}</th>
              <th>{{$lang["bill_amount"]}}</th>
            </tr>
          </thead>
          <tbody>  
            @foreach($log['data'] as $k=>$v)
                
                <tr>
                    <td>{{++$k}}.</td>
                    <td>{{$v['biller_code']}}</td>
                    <td>{{$v['name']}}</td>
                    <td>{{$v['country']}}</td>
                    <td>{{$v['is_airtime']}}</td>
                    <td>{{$v['biller_name']}}</td>
                    <td>{{$v['item_code']}}</td>
                    <td>{{$v['short_name']}}</td>
                    <td>{{$v['label_name']}}</td>
                    <td>{{$v['amount']}}</td>
                </tr>
                
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop