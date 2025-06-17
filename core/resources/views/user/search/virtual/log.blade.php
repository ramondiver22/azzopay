
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_cards_virtual_card_transaction_history"]}}{{$val->card->ref_id}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["donation_description"]}}</th>
              <th>{{$lang["donation_reference"]}}</th>
              <th>{{$lang["donation_type"]}}</th>
              <th>{{$lang["donation_created"]}}</th>
              <th>{{$lang["donation_updated"]}}</th>
            </tr>
          </thead>
          <tbody>  
              <tr>
                <td>1.</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>{{$val->description}}</td>
                <td>{{$val->trx}}</td>
                <td>
                @if($val->type==1)
                  <span class="badge badge-pill badge-primary">{{$lang["transactions_credit"]}}</span>
                @elseif($val->type==2)
                  <span class="badge badge-pill badge-primary">{{$lang["transactions_debit"]}}</span>                        
                @endif
                </td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

@stop