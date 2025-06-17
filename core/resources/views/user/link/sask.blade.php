@extends('paymentlayout')

@section('content')
<div class="main-content">
  <div class="header py-7 py-lg-8 pt-lg-1">
    <div class="container">
      <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            <div class="card-profile-image mb-5">
                <img src="{{url('/')}}/asset/profile/{{$merchant->image}}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">    
    </div>
  </div>
  <div class="container mt--8 pb-5 mb-0">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-7">
        <div class="card card-profile bg-white border-0 mb-5">
          <div class="card-body pt-7 px-5">
            <div class="row">


                <div class="col-6">
                    @if($userTax->enable_pix_payment  > 0)
                    <div class="text-center text-dark mb-5">
                        <div class="btn-wrapper text-center">
                            <a href="{{route('pix.scview.link', ['id' => $link])}}" class="btn btn-neutral btn-icon mb-3">
                                <span class="btn-inner--icon">
                                  <img src="{{url('asset/images/pix-icon-blue.png')}}" style="width: 25px; height: 25px;" />
                                </span>
                            </a>
                            <p class="text-xs text-uppercase">{{$lang["link_pay_with_pix"]}}</p> 
                        </div>
                    </div>     
                    @endif  
                </div>
                              
                <div class="col-6">
                    @if($userTax->enable_boleto_payment  > 0)
                    <div class="text-center text-dark mb-5">
                        <div class="btn-wrapper text-center">
                            <a href="{{route('boleto.scview.link', ['id' => $link])}}" class="btn btn-neutral btn-icon mb-3">
                                <span class="btn-inner--icon"><i class="fad fa-barcode"></i></span>
                            </a>
                            <p class="text-xs text-uppercase">{{$lang["link_pay_with_boleto"]}}</p> 
                        </div>
                    </div>           
                    @endif           
                </div>
              
                <div class="col-6">
                    @if($userTax->enable_creditcard_payment  > 0)
                    <div class="text-center text-dark mb-5">
                        <div class="btn-wrapper text-center">
                            <a href="{{route('card.scview.link', ['id' => $link])}}" class="btn btn-neutral btn-icon mb-3">
                                <span class="btn-inner--icon"><i class="fad fa-credit-card"></i></span>
                            </a>
                            <p class="text-xs text-uppercase">{{$lang["link_pay_with_card"]}}</p> 
                        </div>
                    </div>     
                    @endif  
                </div>
                             
                <div class="col-6">
                    @if($userTax->enable_account_payment  > 0)
                    <div class="text-center text-dark mb-5">
                        <div class="btn-wrapper text-center">
                            <a href="{{route('account.scview.link', ['id' => $link])}}" class="btn btn-neutral btn-icon mb-3">
                                <span class="btn-inner--icon"><i class="fad fa-user"></i></span>
                            </a>
                            <p class="text-xs text-uppercase">{{$lang["link_pay_with_account"]}}</p> 
                        </div>
                    </div>       
                    @endif               
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop