@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["transfer_transfer_money"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["transfer_send_money"]}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 h4 font-weight-bolder">{{$lang["transfer_transfer_money_2"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.transfer')}}" method="post" id="modal-details">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="email" name="email" class="form-control" placeholder="{{$lang["transfer_email_address"]}}" required>
                        <span class="form-text text-xs">{{$lang["transfer_transfer_charge_is"]}} {{$userTax->transfer_charge}}% + {{$currency->symbol.$set->transfer_chargep}} {{$lang["transfer_per_transaction_if_user_is_not_a_member"]}} {{$set->site_name}}, {{$lang["transfer_registration_will_be_required"]}}</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{$lang["transfer_amount"]}}</label>
                    <div class="col-lg-12">
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">{{$currency->symbol}}</span>
                        </span>
                        <input type="number" step="any" class="form-control" name="amount" id="amounttransfer" min="{{$set->min_transfer}}"  onkeyup="transfercharge()" required>
                        <input type="hidden" value="{{$userTax->transfer_charge}}" id="chargetransfer">
                      </div>
                    </div>
                  </div>                   
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["transfer_transfer_money"]}} <span id="resulttransfer"></span></button>
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
          @if(count($transfer)>0)  
            @foreach($transfer as $k=>$val)
              <div class="col-md-6">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <!-- Title -->
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->ref_id}}</h5>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col">
                          @if($val->receiver != null && $val->receiver['id']==$user->id)
                          <p>{{$lang["transfer_received"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                          <p>{{$lang["transfer_from"]}}: {{$val->sender['email']}}</p>
                          @elseif($val->sender != null && $val->sender['id']==$user->id)
                          <p>{{$lang["transfer_sent"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                            @if($val->receiver == null || $val->receiver['id']==null)
                            <p>{{$lang["transfer_to"]}}: {{$val->temp}}</p>
                            @else
                            <p>{{$lang["transfer_to"]}}: {{$val->receiver['email']}}</p>
                            @endif
                          @endif
                          <p class="text-sm mb-2">{{$lang["transfer_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                          @if($val->sender['id']==$user->id) 
                          <span class="badge badge-pill badge-primary">{{$lang["transfer_charge"]}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}} </span>
                          @endif
                          @if($val->status==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["transfer_confirmed"]}}</span>
                          @elseif($val->status==0)
                            <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i> {{$lang["transfer_pending"]}}</span>                        
                          @elseif($val->status==2)
                            <span class="badge badge-pill badge-info"><i class="fad fa-check"></i> {{$lang["transfer_returned"]}}</span>
                          @endif
                        </div>
                      </div>
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
              <h3 class="text-dark">{{$lang["transfer_no_ransfer_request"]}}</h3>
              <p class="text-dark text-sm card-text">{{$lang["transfer_we_couldnt_find_any_transfer"]}}</p>
            </div>
          </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $transfer->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{$lang["transfer_statistics"]}}
                </h4>
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{$lang["transfer_sent"]}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($sent, 2, '.', '')}}</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{$lang["transfer_pending"]}}</span><br>
                  <span class="surtitle">{{$lang["transfer_returned"]}}</span><br>
                  <span class="surtitle ">{{$lang["transfer_total"]}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} {{number_format($pending, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($rebursed, 2, '.', '')}}</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        @foreach($received as $k=>$val)
          <div class="card">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col-8">
                  <h5 class="h3 mb-1 font-weight-bolder">#{{$val->ref_id}}</h5>
                </div>
                <div class="col-4 text-right">
                  @if($val->status==0)
                  <a href="{{url('/')}}/user/received/{{$val->id}}" class="btn btn-sm btn-neutral" title="{{$lang["transfer_mark_as_received"]}}"><i class="fa fa-check"></i> {{$lang["transfer_confirm"]}}</a>
                  @endif
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col">
                  <p>{{$lang["transfer_email"]}}: {{$val->sender['email']}}</p>
                  <p>{{$lang["transfer_total"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                  <p class="text-sm mb-2">{{$lang["transfer_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                  @if($val->status==1)
                    <span class="badge badge-pill badge-success"><i class="fa fa-check"></i> {{$lang["transfer_received"]}}</span>
                  @elseif($val->status==0)
                    <span class="badge badge-pill badge-danger"><i class="fa fa-spinner"></i> {{$lang["transfer_pending"]}}</span>                       
                  @elseif($val->status==2)
                    <span class="badge badge-pill badge-info"><i class="fa fa-ban"></i> {{$lang["transfer_returned"]}}</span>                    
                  @endif

                </div>
              </div>
            </div>
          </div>
        @endforeach 
      </div>
    </div>
@stop