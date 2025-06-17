@extends('paymentlayout')
@section('content')

<div class="main-content">
    <!-- Header -->
    <div class="header py-7 py-lg-6 pt-lg-1">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5 mb-0">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card card-profile bg-white border-0 mb-5">
            <div class="card-body pt-7 px-5">
              <div class="text-center text-dark mb-5">
                <small>{{$lang["payment_please_send_exact"]}} <span class="text-success"> {{ $bcoin }}</span> {{__('ETH')}} {{$lang["payment_to"]}} <span class="text-primary"> {{ $wallet}} .</span> {{$lang["payment_your_account_will_be_credited"]}}</small>
              </div>
              <div class="text-center">
                {!! $qr !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection