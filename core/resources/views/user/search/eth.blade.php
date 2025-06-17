
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row" id="earnings">
          <div class="col-md-4">
              <div class="card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row align-items-center mb-2">                   
                    <div class="col">
                      <h3 class="h4 mb-0 font-weight-bolder">{{$val->trx}}</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      @if($val->type==2 || $val->type==5)
                      <p class="text-sm mb-0">{{$lang["btc_sold"]}}: ${{number_format($val->total)}}</p>
                      <p class="text-sm mb-0">{{$lang["btc_paid_for"]}}: {{$currency->symbol.number_format($val->amount)}}</p>
                      @endif
                      @if($val->type==1 || $val->type==4)
                      <p class="text-sm mb-0">{{$lang["btc_paid_for"]}}: ${{number_format($val->amount)}}</p>
                      <p class="text-sm mb-0">{{$lang["btc_wallet_address"]}}: {{$val->wallet}}</p>
                      @endif
                      <p class="text-sm mb-0">{{$lang["btc_rate"]}}: {{$currency->symbol.$val->rate}}</p>
                      <p class="text-sm mb-0">{{$lang["donation_created"]}}: {{date("j M, Y h:i:A", strtotime($val->created_at))}}</p>
                      <p class="text-sm mb-2">{{$lang["donation_updated"]}}: {{date("j M, Y h:i:A", strtotime($val->updated_at))}}</p>
                      <div class="row align-items-center mb-2">                 
                        <div class="col-12 text-left">
                                              
                            <img src="{{url('/')}}/asset/payment_gateways/ethereum.png" alt="ethereum" style="height:auto; max-width:10%;"/>
                          
                          @if($val->status==0)
                            <span class="badge badge-pill badge-primary">{{$lang["btc_pending"]}}</span> 
                          @elseif($val->status==1)
                            <span class="badge badge-pill badge-success">{{$lang["donation_charge"]}}: {{$currency->symbol.number_format($val->charge)}}</span>                      
                            <span class="badge badge-pill badge-success">{{$lang["btc_paid_out"]}}</span>                      
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-danger">{{$lang["btc_declined"]}}</span> 
                          @endif                     
                          @if($val->type==1 || $val->type==4)
                            <span class="badge badge-pill badge-info">{{$lang["btc_buy"]}}</span>
                          @elseif($val->type==2 || $val->type==5)
                            <span class="badge badge-pill badge-success">{{$lang["btc_sell"]}}</span>                    
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
@stop