@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="row">  
                    @if(count($withdraw)>0) 
                    @foreach($withdraw as $k=>$val)
                        <div class="col-md-4">
                        <div class="card bg-white">
                            <!-- Card body -->
                            <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                <!-- Title -->
                                <h5 class="h4 mb-1 font-weight-bolder">{{$val->reference}}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="text-sm mb-0">{{$lang["profile_subacctrans_amount"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                                    @if($val->type==2)
                                    <p class="text-sm mb-0">{{$lang["profile_subacctrans_sub_account"]}}: @if($val->sub_id==null) [Deleted] @else {{$val->sub->name}} @endif</p>
                                    @elseif($val->type==1)
                                    <p class="text-sm mb-0">{{$lang["profile_subacctrans_bank"]}}: {{$val->dbank->name.' - '.$val->dbank->acct_no}}</p>
                                    @endif
                                    <p class="text-sm mb-0">{{$lang["profile_subacctrans_next_settlement"]}}: @if($val->status==0){{date("Y/m/d", strtotime($val->next_settlement))}} @else - @endif</p>
                                    <p class="text-sm mb-2">{{$lang["profile_subacctrans_date"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                                    @if($val->type==2)
                                    <span class="badge badge-pill badge-primary"><i class="fad fa-people-arrows"></i> {{$lang["profile_subacctrans_date"]}}</span>
                                    @endif                        
                                    @if($val->status==1)
                                    <span class="badge badge-pill badge-primary">{{$lang["profile_subacctrans_charge"]}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
                                    @endif
                                    @if($val->status==1)
                                    <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["profile_subacctrans_paid_out"]}}</span>
                                    @elseif($val->status==0)
                                    <span class="badge badge-pill badge-danger"><i class="fad fa-spinner"></i>  {{$lang["profile_subacctrans_pending"]}}</span>                        
                                    @elseif($val->status==2)
                                    <span class="badge badge-pill badge-info"><i class="fad fa-ban"></i> {{$lang["profile_subacctrans_declined"]}}</span>
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
                        <h3 class="text-dark">{{$lang["profile_subacctrans_no_payout"]}}</h3>
                        <p class="text-dark text-sm card-text">{{$lang["profile_subacctrans_we_couldnt_find_any_payouts"]}}</p>
                        </div>
                    </div>
                    @endif
                </div> 
                <div class="row">
                    <div class="col-md-12">
                    {{ $withdraw->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div> 
        </div> 

@stop