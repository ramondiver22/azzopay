
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0 font-weight-bolder">{{$lang["plans_plans"]}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang["donation_sn"]}}</th>
              <th>{{$lang["donation_name"]}}</th>
              <th>{{$lang["donation_amount"]}}</th>
              <th>{{$lang["plans_interval"]}}</th>
              <th>{{$lang["plans_expired_active"]}}</th>
              <th>{{$lang["donation_status"]}}</th>
              <th>{{$lang["donation_created"]}}</th>
              <th class="scope"></th>  
              <th class="scope"></th>  
            </tr>
          </thead>
          <tbody>  
              @php 
                $active=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '>', Carbon\Carbon::now())->count();
                $expired=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '<', Carbon\Carbon::now())->count();
              @endphp
              <tr>
                  <td>{{++$k}}.</td>
                  <td>{{$val->name}}</td>
                  <td>{{$currency->symbol.number_format($val->amount)}}</td>
                  <td>{{$val->intervals}} - @if($val->times==null) {{$lang["plans_indefinitely"]}} @else {{$val->times}} {{$lang["plans_times"]}}time(s) @endif</td>
                  <td>{{$expired}} / {{$active}}</td>
                  <td>@if($val->active==0) <span class="badge badge-pill badge-danger">{{$lang["donation_disabled"]}}</span> @elseif($val->active==1) <span class="badge badge-pill badge-success">{{$lang["donation_ative"]}}</span>@endif</td>
                  <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                  <td><a class="btn-icon-clipboard text-primary text-uppercase" data-clipboard-text="{{route('subview.link', ['id' => $val->ref_id])}}" title="{{$lang["donation_copy"]}}">{{$lang["plans_copy_subscription_link"]}}</a></td>
                  <td class="text-right">
                  <div class="dropdown">
                          <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a href="{{route('user.plansub', ['id' => $val->ref_id])}}" class="dropdown-item">{{$lang["plans_subscribers"]}}</a>
                              <a data-toggle="modal" data-target="#edit{{$val->id}}" href="" class="dropdown-item">{{$lang["donation_edit"]}}</a>
                              @if($val->active==1)
                                <a class='dropdown-item' href="{{route('sub.plan.unpublish', ['id' => $val->id])}}">{{$lang["donation_disable"]}}</a>
                              @else
                                <a class='dropdown-item' href="{{route('sub.plan.publish', ['id' => $val->id])}}">{{$lang["donation_active"]}}</a>
                              @endif
                          </div>
                      </div>
                  </td> 
              </tr>
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
                                <span class="form-text text-xs">{{$lang["plans_amount_interval_can_only"]}}</span>
                            </div>
                        </div>
                        @if($active<1)
                        <div class="form-group row">
                          <label class="col-form-label col-lg-12">{{$lang["invoice_amount"]}}</label>
                          <div class="col-lg-12">
                              <div class="input-group">
                                  <span class="input-group-prepend">
                                      <span class="input-group-text">{{$currency->symbol}}</span>
                                  </span>
                                  <input type="number" class="form-control" name="amount" placeholder="0.00" min="10" value="{{$val->amount}}">
                                  <span class="input-group-append">
                                      <span class="input-group-text">.00</span>
                                  </span>
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
                                  <option value="4 Months" @if($val->intervals=='4 Months') selected @endif>{{$lang["plans_quartely"]}}</option>
                                  <option value="6 Months" @if($val->intervals=='6 Months') selected @endif>{{$lang["plans_every_six_months"]}}</option>
                                  <option value="1 Year" @if($val->intervals=='1 Year') selected @endif>{{$lang["plans_yearly"]}}</option>
                              </select>
                          </div>
                        </div> 
                        @endif
                        <input name="plan_id" type="hidden" value="{{$val->id}}">               
                        <div class="text-right">
                          <button type="submit" class="btn btn-neutral btn-block">{{$lang["plans_save"]}}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div> 
          </tbody>
        </table>
      </div>
    </div>

@stop