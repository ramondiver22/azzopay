@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row"> 
    @if(count($orders)>0)  
      @foreach($orders as $k=>$val)
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
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_product"]}}: {{$val->product->name}}</p>
                  @if($val->user_id!=null)
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_name"]}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</p>
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_email"]}}: {{$val->buyer->email}}</p>
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_phone"]}}: {{$val->buyer->phone}}</p>
                  @else
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_name"]}}: {{$val->first_name}} {{$val->last_name}}</p>
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_email"]}}: {{$val->email}}</p>
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_phone"]}}: {{$val->phone}}</p>
                  @endif
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_quantity"]}}: {{$val->quantity}}</p> 
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_country"]}}: {{$val->country}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_state"]}}: {{$val->state}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_town_city"]}}: {{$val->town}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_address"]}}: {{$val->address}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_region"]}}: {{$val->ship['region']}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_shipping_fee"]}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                  @if($val->product->note_status==1 || $val->product->note_status==2)
                      @if(!empty($val->note))
                          <p class="text-sm text-dark mb-0">{{$lang["product_orders_note"]}}: {{$val->note}}</p>
                      @endif
                  @endif                                        
                  @if($val->store_id==null)
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_type"]}}: {{$lang["product_orders_single_purchase"]}}</p>
                  @elseif($val->store_id!=null)
                      <p class="text-sm text-dark mb-0">{{$lang["product_orders_type"]}}: {{$lang["product_orders_store_purchase"]}}</p>
                  @endif     
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_amount"]}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                  <p class="text-sm text-dark mb-0">{{$lang["product_orders_total"]}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                  <p class="text-sm text-dark mb-2">{{$lang["product_orders_created"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                    <span class="badge badge-pill badge-primary">{{$lang["product_orders_fee"]}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
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
          <h3 class="text-dark">{{$lang["product_orders_no_orders"]}}</h3>
          <p class="text-dark text-sm card-text">{{$lang["product_we_couldnt_find_any_product"]}}</p>
        </div>
      </div>
    @endif
    </div> 
@stop