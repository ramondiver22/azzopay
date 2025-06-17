
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{$lang['admin_transfer_orders_plans_title']}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang['admin_transfer_orders_plans_sn']__('S / N')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_author']__('Author')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_name']__('Name')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_amount']__('Amount')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_interval']__('Interval')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_expired_active']__('Expired/Active')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_status']__('Status')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_suspended']__('Suspended')}}</th>
              <th>{{$lang['admin_transfer_orders_plans_created']__('Created')}}</th>
              <th class="scope"></th>  
              <th class="scope"></th>  
            </tr>
          </thead>
          
          <tbody>  
            @foreach($plans as $k=>$val)
              @php 
                $active=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '>', Carbon\Carbon::now())->count();
                $expired=App\Models\Subscribers::whereplan_id($val->id)->where('expiring_date', '<', Carbon\Carbon::now())->count();
              @endphp
              <tr>
                  <td>{{++$k}}.</td>
                  <td>{{$val->user['business_name']}}</td>
                  <td>{{$val->name}}</td>
                  <td>{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                  <td>{{$val->intervals}} - @if($val->times==null) {{$lang['admin_transfer_orders_plans_indefinitely']}} @else {{$val->times}} time(s) @endif</td>
                  <td>{{$expired}} / {{$active}}</td>
                  <td>@if($val->active==0) <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_plans_disabled']}}</span> @elseif($val->active==1) <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_plans_actived']}}</span>@endif</td>
                  <td>
                    @if($val->status==1)
                        <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_plans_yes']}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_plans_no']}}</span>
                    @endif
                    </td>
                  <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                  <td>{{route('subview.link', ['id' => $val->ref_id])}}</td>
                  <td class="text-right">
                  <div class="dropdown">
                          <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a href="{{route('admin.plansub', ['id' => $val->ref_id])}}" class="dropdown-item">{{$lang['admin_transfer_orders_plans_subscribers']}}</a>
                              @if($val->status==0)
                                <a class='dropdown-item' href="{{route('plan.unpublish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_plans_disable']}}</a>
                              @else
                                <a class='dropdown-item' href="{{route('plan.publish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_plans_activate']}}</a>
                              @endif
                          </div>
                      </div>
                  </td> 
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

@stop