
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["plans_subscription_pyment"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#create-plan" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["plans_create_plan"]}}</a> 
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="modal fade" id="create-plan" tabindex="-1" role="dialog" aria-labelledby="create-plan" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title font-weight-bolder" id="exampleModalLabel">{{$lang["plans_create_new_plan"]}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('submit.plan')}}" method="post" id="modal-details">
                  @csrf
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <input type="text" name="name" class="form-control" placeholder="{{$lang["plans_name"]}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                </span>
                                <input type="number" step="any" class="form-control" name="amount" placeholder="0.00">
                            </div>
                            <span class="form-text text-xs">{{$lang["plans_leave_empty_to_allow_customers"]}}</span>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <select class="form-control select" name="interval">
                                <option value="">{{$lang["plans_select_interval"]}}</option>
                                <option value="1 Hour">{{$lang["plans_hourly"]}}</option>
                                <option value="1 Day">{{$lang["plans_daily"]}}</option>
                                <option value="1 Week">{{$lang["plans_weekly"]}}</option>
                                <option value="1 Month">{{$lang["plans_monthly"]}}</option>
                                <option value="4 Months">{{$lang["plans_quaterly"]}}</option>
                                <option value="6 Months">{{$lang["plans_ever_six_months"]}}</option>
                                <option value="1 Year">{{$lang["plans_yearly"]}}</option>
                            </select>
                        </div>
                    </div>           
                    <div class="form-group row">
                      <div class="col-lg-12">
                          <input type="number" name="times" placeholder="{{$lang["plans_number_of_times_to_charge"]}}" class="form-control">
                          <span class="form-text text-xs">{{$lang["plans_leave_mpty_to_charge_indefinitely"]}}</span>
                      </div>
                    </div> 
                    <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block" form="modal-details">{{$lang["plans_create_plan"]}}</button>
                    </div>         
                </form>
              </div>
            </div>
          </div>
        </div>         
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">  
          @if(count($plans)>0)
            @foreach($plans as $k=>$val)
              @php 
                $active=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '>', Carbon\Carbon::now())->count();
                $expired=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '<', Carbon\Carbon::now())->count();
              @endphp
              <div class="col-md-4">
                <div class="card bg-white">
                  <!-- Card body -->
                  <div class="card-body">
                    <div class="row mb-2">
                      <div class="col-4">
                        <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('subview.link', ['id' => $val->ref_id])}}" title="Copy">{{$lang["plans_copy_link"]}} <i class="fad fa-link text-xs"></i></a></p>
                      </div>  
                      <div class="col-8 text-right">
                        <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                          <i class="fad fa-chevron-circle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                          <a href="{{route('user.plansub', ['id' => $val->ref_id])}}" class="dropdown-item"><i class="fad fa-user"></i>{{$lang["plans_subscribers"]}}</a>
                          <a data-toggle="modal" data-target="#edit{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{$lang["plans_edit"]}}</a>
                          @if($val->active==1)
                            <a class='dropdown-item' href="{{route('sub.plan.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{$lang["plans_disable"]}}</a>
                          @else
                            <a class='dropdown-item' href="{{route('sub.plan.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["plans_activate"]}}</a>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                        <p>{{$lang["plans_interval"]}}: {{$val->intervals}} - @if($val->times==null) {{$lang["plans_indefinitely"]}} @else {{$val->times}} {{$lang["plans_times"]}} @endif</p>
                        <p>{{$lang["plans_amount"]}}: {{$currency->symbol.number_format($val->amount, 2, '.', '')}}</p>
                        <p>{{$lang["plans_expired_active"]}}: {{$expired}} / {{$active}}</p>
                        <p class="text-sm mb-2">{{$lang["plans_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                        @if($val->active==1)
                            <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["plans_active"]}}</span>
                        @else
                            <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["plans_disabled"]}}</span>
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
                <h3 class="text-dark">{{$lang["plans_no_subscription_plan_found"]}}</h3>
                <p class="text-dark text-sm card-text">{{$lang["plans_we_couldnt_find_any_subscription_plan"]}}</p>
              </div>
            </div>
          @endif
        </div> 
        <div class="row">
          <div class="col-md-12">
          {{ $plans->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div> 
    </div>
    @foreach($plans as $k=>$val)
      <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="mb-0 font-weight-bolder">{{$lang["plans_edit_plan"]}}</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('update.plan')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-form-label col-lg-12">{{$lang["plans_plan_name"]}}<span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <input type="text" name="name" class="form-control" value="{{$val->name}}" required>
                        <span class="form-text text-xs">{{$lang["plans_amount_interval_can_only_be_edited"]}}</span>
                    </div>
                </div>
                @if(1>$active)
                <div class="form-group row">
                  <label class="col-form-label col-lg-12">{{$lang["plans_amount"]}}</label>
                  <div class="col-lg-12">
                      <div class="input-group">
                          <span class="input-group-prepend">
                              <span class="input-group-text">{{$currency->symbol}}</span>
                          </span>
                          <input type="number" step="any" class="form-control" name="amount" placeholder="0.00" min="10" value="{{$val->amount}}">
                      </div>
                      <span class="form-text text-xs">{{$lang["plans_leave_empty_to_allow_customers"]}}</span>
                  </div>
                </div>  
                <div class="form-group row">
                  <label class="col-form-label col-lg-12">{{$lang["plans_interval"]}}</label>
                  <div class="col-lg-12">
                      <select class="form-control select" name="interval">
                          <option value="1 Hour" @if($val->intervals=='1 Hour') selected @endif>{{$lang["plans_hourly"]}}</option>
                          <option value="1 Day" @if($val->intervals=='1 Day') selected @endif>{{$lang["plans_daily"]}}</option>
                          <option value="1 Week" @if($val->intervals=='1 Week') selected @endif>{{$lang["plans_weekly"]}}</option>
                          <option value="1 Month" @if($val->intervals=='1 Month') selected @endif>{{$lang["plans_monthly"]}}</option>
                          <option value="4 Months" @if($val->intervals=='4 Months') selected @endif>{{$lang["plans_quaterly"]}}</option>
                          <option value="6 Months" @if($val->intervals=='6 Months') selected @endif>{{$lang["plans_ever_six_months"]}}</option>
                          <option value="1 Year" @if($val->intervals=='1 Year') selected @endif>{{$lang["plans_yearly"]}}</option>
                      </select>
                  </div>
                </div> 
                @endif
                <input name="plan_id" type="hidden" value="{{$val->id}}">               
                <div class="text-right">
                  <button type="submit" class="btn btn-neutral btn-block">{{$lang["plans_edit_plan"]}}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div> 
    @endforeach

@stop