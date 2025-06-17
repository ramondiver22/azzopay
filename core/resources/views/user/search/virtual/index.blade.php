
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_cards_virtual_cards"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["donation_name"]}}</th>
              <th>{{$lang["virtual_cards_card_number"]}}</th>
              <th>{{$lang["virtual_cards_cvv"]}}</th>
              <th>{{$lang["virtual_cards_expiration"]}}</th>
              <th>{{$lang["donation_type"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["donation_status"]}}</th>
              <th>{{$lang["virtual_cards_reference"]}}</th>
              <th>{{$lang["donation_created"]}}</th>
              <th>{{$lang["donation_updated"]}}</th>
              <th class="text-center">{{$lang["virtual_cards_action"]}}</th> 
            </tr>
          </thead>
          <tbody>  
              <tr>
                <td>1.</td>
                <td>{{$val->name_on_card}}</td>
                <td>{{$val->card_pan}}</td>
                <td>{{$val->cvv}}</td>
                <td>{{$val->expiration}}</td>
                <td>{{$val->card_type}}</td>
                <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>@if($val->status==0) <span class="badge badge-pill badge-danger">{{$lang["virtual_cards_terminated"]}}</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">{{$lang["donation_ative"]}}</span>@endif</td>
                <td>{{$val->ref_id}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                <td class="text-center">
                    <div class="dropdown">
                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a href="{{route('transactions.virtual', ['id'=>$val->id])}}" class="dropdown-item">{{$lang["transactions_transactions"]}}</a>
                            @if($val->status==1)
                                <a data-toggle="modal" data-target="#modal-formfund" href="" class="dropdown-item">{{$lang["virtual_cards_fund_card"]}}</a>
                                <a href="{{route('terminate.virtual', ['id'=>$val->card_hash])}}" class="dropdown-item">{{$lang["virtual_cards_terminate"]}}</a>
                            @endif
                        </div>
                    </div>
                </td> 
            </tr>
            <div class="modal fade" id="modal-formfund" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                <div class="modal-dialog modal- modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_cards_add_funds_to_virtual_card"]}}</h3>
                            <p class="form-text text-xs">Charge is {{$userTax->virtual_charge}}%.</p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('fund.virtual')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$val->card_hash}}">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["donation_amount"]}}</label>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </div>
                                        <input type="number" name="amount" class="form-control" max="{{$set->vc_max-$val->amount}}" required>
                                    </div>
                                </div>
                            </div>                 
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block my-4">{{$lang["virtual_cards_fund_card"]}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
              </div> 
          </tbody>
        </table>
      </div>
    </div>

@stop