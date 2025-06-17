@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row">  
        <div class="col-md-6">
          <div class="card bg-white">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col-8">
                  <!-- Title -->
                  <h5 class="h4 mb-0 font-weight-bolder">{{$val->ref_id}}</h5>
                </div>
              </div>
              <div class="row">
                  <div class="col">
                    <p class="text-sm mb-0">{{$lang["oder_product"]}}: {{$val->product->name}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_name"]}}: {{$val->first_name}} {{$val->last_name}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_email"]}}: {{$val->email}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_phone"]}}: {{$val->phone}}</p>
                    @if($val->product->quantity_status==0)
                    <p class="text-sm mb-0">{{$lang["oder_quantity"]}}: {{$val->quantity}}</p>
                    @endif                        
                    @if($val->product->shipping_status==1)
                    <p class="text-sm mb-0">{{$lang["oder_country"]}}: {{$val->country}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_state"]}}: {{$val->state}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_town_city"]}}: {{$val->town}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_address"]}}: {{$val->address}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_shipping_fee"]}}: {{$currency->symbol.$val->shipping_fee}}</p>
                    @endif
                    @if($val->product->note_status==1 || $val->product->note_status==2)
                        @if(!empty($val->note))
                            <p class="text-sm mb-0">{{$lang["oder_note"]}}: {{$val->note}}</p>
                        @endif
                    @endif    
                    <p class="text-sm mb-0">{{$lang["oder_amount"]}}: {{$currency->symbol}}{{number_format($val->amount)}}</p>
                    <p class="text-sm mb-0">{{$lang["oder_total"]}}: {{$currency->symbol.number_format($val->total)}}</p>
                    <p class="text-sm mb-2">{{$lang["oder_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                    <span class="badge badge-pill badge-primary">{{$lang["oder_fee"]}}: {{$currency->symbol.number_format($val->charge)}}</span>
                  </div>
                </div>
            </div>
          </div>
        </div> 
    </div> 
@stop