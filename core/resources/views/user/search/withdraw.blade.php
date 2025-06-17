@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
            <div class="col-md-6">
              <div class="card bg-white">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <!-- Title -->
                      <h5 class="h4 mb-0 font-weight-bolder">{{$val->reference}}</h5>
                    </div>
                    <div class="col-6 text-right">
                      <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fal fa-ellipsis-h-alt"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-left">
                        @if($val->status==0)
                          <a class="dropdown-item" data-toggle="modal" data-target="#modal-forma{{$val->id}}" href="#">{{$lang["withdraw_edit"]}}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col">
                        <p class="text-sm mb-0">{{$lang["withdraw_amount"]}}: {{$currency->symbol.number_format($val->amount)}}</p>
                        <p class="text-sm mb-0">{{$lang["withdraw_bank"]}}: {{$val->wallet['name']}} - {{$val->wallet['acct_no']}}</p>
                        <p class="text-sm mb-0">{{$lang["withdraw_next_settlemnt"]}}: @if($val->status==0){{date("Y/m/d", strtotime($val->next_settlement))}} @else - @endif</p>
                        <p class="text-sm mb-2">{{$lang["withdraw_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                        @if($val->status==1)
                          <span class="badge badge-pill badge-primary">{{$lang["withdraw_charge"]}}: {{$currency->symbol.number_format($val->charge)}}</span>
                        @endif
                        @if($val->status==1)
                          <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{$lang["withdraw_paid_out"]}}</span>
                        @elseif($val->status==0)
                          <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i>  {{$lang["withdraw_pending"]}}</span>                        
                        @elseif($val->status==2)
                          <span class="badge badge-pill badge-info"><i class="fa fa-close"></i> {{$lang["transfer_declined"]}}</span>
                        @endif
                      </div>
                    </div>
                </div>
              </div>
            </div> 
        </div> 
      </div> 

@stop