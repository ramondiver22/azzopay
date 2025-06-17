
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{$lang['admin_transfer_orders_sc_single_charge']}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang['admin_transfer_orders_sc_sn']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_merchant']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_name']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_amount']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_reference_id']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_redirect_url']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_status']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_suspended']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_created']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_updated']}}</th>
              <th>{{$lang['admin_transfer_orders_sc_link']}}</th>
              <th></th>
            </tr>
          </thead>
          
          <tbody>  
            @foreach($links as $k=>$val)
                
              <tr>
                <td>{{++$k}}.</td>
                <td>@if(!isset($val->user['business_name'])) [{{$lang['admin_transfer_orders_sc_deleted']}}] @else {{$val->user['business_name']}} @endif</td>
                <td>{{$val->name}}</td>
                <td>@if($val->amount==null) {{$lang['admin_transfer_orders_sc_not_fixed']}} @else {{$currency->symbol.number_format($val->amount, 2, '.', '')}} @endif</td>
                <td>#{{$val->ref_id}}</td>
                <td>@if($val->redirect_link == null) null @else {{$val->redirect_link}} @endif</td>
                <td>
                    @if($val->active==1)
                        <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_sc_active']}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_sc_disabled']}}</span>
                    @endif
                </td>                
                <td>
                    @if($val->status==1)
                        <span class="badge badge-pill badge-success">{{$lang['admin_transfer_orders_sc_yes']}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_orders_sc_no']}}</span>
                    @endif
                </td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                <td>{{route('scview.link', ['id' => $val->ref_id])}}</td>
                <td class="text-center">
                    <div class="">
                        <div class="dropdown">
                            <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @if($val->status==1)
                                    <a class='dropdown-item' href="{{route('links.unpublish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_sc_unsuspend']}}</a>
                                @else
                                    <a class='dropdown-item' href="{{route('links.publish', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_sc_suspend']}}</a>
                                @endif
                                <a class="dropdown-item" href="{{route('admin.linkstrans', ['id' => $val->id])}}">{{$lang['admin_transfer_orders_sc_transactions']}}</a>
                                <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_orders_sc_delete']}}</a>
                                <a data-toggle="modal" data-target="#description{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_orders_sc_description']}}</a>
                            </div>
                        </div>
                    </div> 
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @foreach($links as $k=>$val)
    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <h3 class="mb-0">{{$lang['admin_transfer_orders_sc_are_you_sure_you_want_to_delete']}}</h3>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_orders_sc_close']}}</button>
                            <a  href="{{route('delete.link', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_transfer_orders_sc_proceed']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                
    <div class="modal fade" id="description{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-white border-0 mb-0">
                        <div class="card-header">
                            <p class="mb-0 text-sm">{{$val->description}}</p>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5 text-right">
                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_orders_sc_close_1']}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@stop