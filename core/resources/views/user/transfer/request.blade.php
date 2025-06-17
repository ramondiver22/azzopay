@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["transfer_request_request_money"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["transfer_request_create_request"]}}</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["transfer_request_request_money2"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.request')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="email" name="email" class="form-control" placeholder="Email" required>
                          <span class="form-text text-xs">{{$lang["transfer_request_user_must_have_an_account"]}} {{$set->site_name}}, {{$lang["transfer_request_transfer_charge_is"]}} {{$userTax->transfer_charge}}% + {{$currency->symbol.$userTax->transfer_chargep}} {{$lang["transfer_request_per_transaction_charge_will_be"]}}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <div class="input-group">
                          <span class="input-group-prepend">
                            <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="amount" placeholder="0.00" id="amount" required>
                        </div>
                      </div>
                    </div>                   
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["transfer_request_request_money"]}}</button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="row">  
          @if(count($request)>0)
            @foreach($request as $k=>$val)
              <div class="col-md-6">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <!-- Title -->
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->ref_id}}</h5>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col">
                          <p class="text-sm mb-0">{{$lang["transfer_request_amount"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                          @if($val->user_id==$user->id)
                          <p class="text-sm mb-0">{{$lang["transfer_request_to"]}}: {{$val->email}}</p>
                          @else
                          <p class="text-sm mb-0">{{$lang["transfer_request_from"]}}: {{$val->receiver['email']}}</p>
                          @endif
                          <p class="text-sm mb-2">{{$lang["transfer_request_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                          @if($val->status==1)
                            @if($val->email==$user['email'])
                            <span class="badge badge-pill badge-primary">{{$lang["transfer_request_charge"]}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
                            @endif
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["transfer_request_confirmed"]}}</span>
                          @elseif($val->status==0)
                            <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i> {{$lang["transfer_request_pending"]}}</span>                          
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["transfer_request_declined"]}}</span>                        
                          @endif
                        </div>
                      </div>
                      @if($val->status==0 && $val->email==$user['email'])
                        <div class="row">
                          <div class="col-12 mt-2">
                              <a href="{{route('send.pay', ['id'=>$val->confirm])}}" class="btn btn-sm btn-neutral" title="{{$lang["transfer_request_mark_as_received"]}}"><i class="fad fa-check"></i> {{$lang["transfer_request_send"]}}</a>
                              <a href="{{route('decline.pay', ['id'=>$val->confirm])}}" class="btn btn-sm btn-danger" title="{{$lang["transfer_request_mark_as_declined"]}}"><i class="fad fa-ban"></i> {{$lang["transfer_request_decline"]}}</a>
                          </div>
                        </div>
                      @endif
                  </div>
                </div>
              </div> 
            @endforeach
          @else
            <div class="col-md-12 mb-5">
              <div class="text-center mt-8">
                <div class="mb-3">
                  <img src="{{url('/')}}/asset/images/empty.svg">
                </div>
                <h3 class="text-dark">{{$lang["transfer_request_no_money_request"]}}</h3>
                <p class="text-dark text-sm card-text">{{$lang["transfer_request_we_couldnt_any_payouts"]}}</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $request->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{$lang["transfer_request_statistics"]}}
                </h4>
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{$lang["transfer_request_received"]}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($sent, 2, '.', '')}}</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{$lang["transfer_request_pending"]}}</span><br>
                  <span class="surtitle ">{{$lang["transfer_request_total"]}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop