
@extends('userlayout')

@section('content')

@php
  $upd=App\Models\Virtual::whereUser_id(Auth::guard('user')->user()->id)->orderBy('id', 'DESC')->get();
  foreach($upd as $trx){
    $data = array("id"=>$trx->card_hash);
    $check = new Laravel\Flutterwave\VirtualCard();
    $getCard = $check->getCard($data);
    $result = $getCard;
    $amo=str_replace( ',', '', $result['data']['amount']);
    if($amo<$trx->amount){
        if($result['data']['is_active']==true){
            $trx->amount=$amo;
            $trx->save();
        }else{
            $trx->amount=0;
            $trx->save();
        }
    }else{
        $trx->amount=$amo;
        $trx->save();
    }
  }
@endphp
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-5">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fa fa-plus"></i> {{$lang["virtual_create_card"]}}</a>
      </div>
    </div>
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white border-0 mb-0">
              <div class="card-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_new_virtual_card"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fad fa-times"></i></span>
                </button>
                <p class="form-text text-xs">{{$lang["virtual_card_creation_charge_is"]}} {{$set->virtual_createcharge}}% +  {{$currency->symbol.$set->virtual_createchargep}} {{$lang["virtual_of_amount_entitled_to_card"]}} {{$currency->name.number_format($set->vc_max)}}.</p>
              </div>
              <div class="card-body">
                <form method="post" action="{{route('create.virtual')}}">
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <div class="row">
                          <div class="col-6">
                            <input type="text" name="first_name" class="form-control" placeholder="{{$lang["virtual_first_name"]}}">
                          </div>      
                          <div class="col-6">
                            <input type="text" name="last_name" class="form-control" placeholder="{{$lang["virtual_last_name"]}}">
                          </div>
                      </div>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{$lang["virtual_amount"]}}</label>
                    <div class="col-lg-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{$currency->symbol}}</span>
                            </div>
                            <input type="number" name="amount" id="createamount" class="form-control" min="{{$set->vc_min}}" max="{{$set->vc_max}}" onkeyup="createcharge()" required>
                            <input type="hidden" value="{{$set->virtual_createcharge}}" id="chargecreate">
                            <input type="hidden" value="{{$set->virtual_createchargep}}" id="chargecreatex">
                        </div>
                    </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-lg-12">
                          <select class="form-control select" name="bg" required>
                            <option value="">{{$lang["virtual_select_card_style"]}}</option> 
                            <option value="bg-newlife">{{$lang["virtual_new_life"]}}</option>                             
                            <option value="bg-morpheusden">{{$lang["virtual_morpheus_den"]}}</option>                             
                            <option value="bg-sharpblues">{{$lang["virtual_sharp_blue"]}}</option>                             
                            <option value="bg-fruitblend">{{$lang["virtual_fruit_blend"]}}</option>                             
                            <option value="bg-deepblue">{{$lang["virtual_deep_blue"]}}</option>                             
                            <option value="bg-fabledsunset">{{$lang["virtual_fabled_sunset"]}}</option>                             
                            <option value="bg-white">{{$lang["virtual_white"]}}</option>                             
                          </select>
                      </div>
                  </div>                                                  
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block my-4">{{$lang["virtual_create_card"]}} <span id="resulttransfer6"></span></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
    <div class="row">
    @if(count($card)>0)  
      @foreach($card as $k=>$val)
        <div class="col-lg-4">
          <div class="card {{$val->bg}}">
            <!-- Card body -->
            <div class="card-body">
              <div class="row justify-content-between align-items-center">
                <div class="col">
                  <span class="@if($val->bg=='bg-white' || $val->bg==null)text-primary @else text-white @endif">{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</span> @if($val->status==0) <span class="badge badge-pill badge-danger">{{$lang["virtual_terminated"]}}</span> @elseif($val->status==1) <span class="badge badge-pill badge-success">{{$lang["virtual_active"]}}</span> @elseif($val->status==2) <span class="badge badge-pill badge-danger">{{$lang["virtual_blocked"]}}</span>@endif
                </div>
                <div class="col-auto">
                  <a class="mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fad fa-chevron-circle-down @if($val->bg=='bg-white' || $val->bg==null)text-dark @else text-white @endif"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-left">
                    <a href="{{route('transactions.virtual', ['id'=>$val->id])}}" class="dropdown-item"><i class="fad fa-sync"></i>{{$lang["virtual_transactions"]}}</a>
                    <a data-toggle="modal" data-target="#modal-more{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-credit-card"></i>{{$lang["virtual_card_details"]}}</a>
                      @if($val->status==1)
                        <a data-toggle="modal" data-target="#modal-formfund{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-money-bill-wave-alt"></i>{{$lang["virtual_fund_card"]}}</a>
                        <a data-toggle="modal" data-target="#modal-formwithdraw{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-arrow-circle-down"></i>{{$lang["virtual_withdraw_money"]}}</a>
                        <a href="{{route('terminate.virtual', ['id'=>$val->id])}}" class="dropdown-item"><i class="fad fa-ban"></i>{{$lang["virtual_terminate"]}}</a>
                        <a href="{{route('block.virtual', ['id'=>$val->id])}}" class="dropdown-item"><i class="fad fa-sad-tear text-danger"></i>{{$lang["virtual_freeze"]}}</a>
                      @elseif($val->status==2)
                        <a href="{{route('unblock.virtual', ['id'=>$val->id])}}" class="dropdown-item"><i class="fad fa-smile text-success"></i>{{$lang["virtual_unfreeze"]}}</a>
                      @endif
                  </div>
                </div>
              </div>             
              <div class="my-4">
                <span class="h6 surtitle @if($val->bg=='bg-white' || $val->bg==null)text-gray @else text-white @endif mb-2">
                {{$val->first_name}} {{$val->last_name}}- {{$val->card_type}}
                </span>
                <div class="card-serial-number h1 @if($val->bg=='bg-white' || $val->bg==null)text-primary @else text-white @endif">
                  <div>{{$val->card_pan}}</div>
                </div>
              </div>
              <div class="row">               
                <div class="col">
                  <span class="h6 surtitle @if($val->bg=='bg-white' || $val->bg==null)text-gray @else text-white @endif">{{$lang["virtual_expiry_date"]}}</span>
                  <span class="d-block h3 @if($val->bg=='bg-white' || $val->bg==null)text-primary @else text-white @endif">{{$val->expiration}}</span>
                </div>
                <div class="col">
                  <span class="h6 surtitle @if($val->bg=='bg-white' || $val->bg==null)text-gray @else text-white @endif">{{$lang["virtual_cvv"]}}</span>
                  <span class="d-block h3 @if($val->bg=='bg-white' || $val->bg==null)text-primary @else text-white @endif">{{$val->cvv}}</span>
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
          <h3 class="text-dark">{{$lang["virtual_no_irtual_card"]}}</h3>
          <p class="text-dark text-sm card-text">{{$lang["virtual_we_couldnt_find_any_virtual_card"]}}</p>
        </div>
      </div>
    @endif  
    </div>
    @foreach($card as $k=>$val)
      <div class="modal fade" id="modal-formwithdraw{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-white border-0 mb-0">
                <div class="card-header">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_withdraw_founds_from_virtual_card"]}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('withdraw.virtual')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$val->card_hash}}">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-12">{{$lang["virtual_amount"]}}</label>
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                </div>
                                <input type="number" step="any" name="amount" id="amounttransfer4{{$val->id}}" class="form-control" max="{{$val->amount}}" required>
                                <input type="hidden" value="{{$userTax->virtual_charge}}" id="vtransfer3{{$val->id}}">
                                <input type="hidden" value="{{$userTax->virtual_chargep}}" id="vtransferx{{$val->id}}">
                            </div>
                            <p class="form-text text-xs">{{$lang["virtual_charge_is"]}} {{$userTax->virtual_charge}}% +  {{$currency->symbol.$userTax->virtual_chargep}}.</p>
                        </div>
                    </div>                 
                    <div class="text-right">
                        <button type="submit" class="btn btn-neutral btn-block my-4">{{$lang["virtual_withdraw_funds"]}} <span id="resulttransfer4{{$val->id}}"></span></button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
      </div> 
      <div class="modal fade" id="modal-formfund{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-white border-0 mb-0">
                <div class="card-header">
                    <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_add_founds_to_virtual_card"]}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('fund.virtual')}}">
                    @csrf
                    <input type="hidden" name="id" value="{{$val->card_hash}}">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-12">{{$lang["virtual_amount"]}}</label>
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                </div>
                                <input type="number" step="any" name="amount" id="amounttransfer5{{$val->id}}" class="form-control" max="{{$set->vc_max-$val->amount}}" required>
                                <input type="hidden" value="{{$userTax->virtual_charge}}" id="vtransfer3{{$val->id}}">
                                <input type="hidden" value="{{$userTax->virtual_chargep}}" id="vtransferx{{$val->id}}">
                            </div>
                            <p class="form-text text-xs">{{$lang["virtual_charge_is"]}} {{$userTax->virtual_charge}}% +  {{$currency->symbol.$userTax->virtual_chargep}}.</p>
                        </div>
                    </div>                 
                    <div class="text-right">
                        <button type="submit" class="btn btn-neutral btn-block my-4">{{$lang["virtual_pay"]}} <span id="resulttransfer5{{$val->id}}"></span></button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
      </div>      
      <div class="modal fade" id="modal-more{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0 font-weight-bolder">{{$lang["virtual_card_details"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>{{$lang["virtual_state"]}}: {{$val->state}}</p>
                <p>{{$lang["virtual_city"]}}: {{$val->city}}</p>
                <p>{{$lang["virtual_zip_cade"]}}Zip Code: {{$val->zip_code}}</p>
                <p>{{$lang["virtual_address"]}}: {{$val->address}}</p>
              </div>
            </div>
        </div>
      </div> 
    @endforeach

@stop