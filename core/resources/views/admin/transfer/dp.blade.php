
@extends('master')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-header header-elements-inline">
        <h3 class="mb-0">{{$lang['admin_transfer_dp_donations']}}</h3>
      </div>
      <div class="table-responsive py-4">
        <table class="table table-flush" id="datatable-buttons">
          <thead>
            <tr>
              <th>{{$lang['admin_transfer_dp_sn']}}</th>
              <th>{{$lang['admin_transfer_dp_merchant']}}</th>
              <th>{{$lang['admin_transfer_dp_name']}}</th>
              <th>{{$lang['admin_transfer_dp_donors']}}</th>
              <th>{{$lang['admin_transfer_dp_amount']}}</th>
              <th>{{$lang['admin_transfer_dp_reference_id']}}</th>
              <th>{{$lang['admin_transfer_dp_status']}}</th>
              <th>{{$lang['admin_transfer_dp_suspended']}}</th>
              <th>{{$lang['admin_transfer_dp_created']}}</th>
              <th>{{$lang['admin_transfer_dp_updated']}}</th>
              <th>{{$lang['admin_transfer_dp_link']}}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>  
            @foreach($links as $k=>$val)
                @php 
                  $donors=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->latest()->get();
                  $donated=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->sum('amount');
                  @endphp
              <tr>
                <td>{{++$k}}.</td>
                <td>@if($val->user['business_name']==null) [{{$lang['admin_transfer_dp_deleted']}}] @else {{$val->user['business_name']}} @endif</td>
                <td>{{$val->name}}</td>
                <td>{{count($donors)}}</td>
                <td>{{$currency->symbol.number_format($donated, 2, '.', '')}}/{{$currency->symbol.number_format($val->amount, 2, '.', '')}}</td>
                <td>#{{$val->ref_id}}</td>
                <td>
                    @if($val->active==1)
                        <span class="badge badge-pill badge-success">{{$lang['admin_transfer_dp_active']}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_dp_disabled']}}</span>
                    @endif
                </td>                
                <td>
                    @if($val->status==1)
                        <span class="badge badge-pill badge-success">{{$lang['admin_transfer_dp_yes']}}</span>
                    @else
                        <span class="badge badge-pill badge-danger">{{$lang['admin_transfer_dp_no']}}</span>
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
                                    <a class='dropdown-item' href="{{route('links.unpublish', ['id' => $val->id])}}">{{$lang['admin_transfer_dp_unsuspend']}}</a>
                                @else
                                    <a class='dropdown-item' href="{{route('links.publish', ['id' => $val->id])}}">{{$lang['admin_transfer_dp_suspend']}}</a>
                                @endif
                                <a class="dropdown-item" href="{{route('admin.linkstrans', ['id' => $val->id])}}">{{$lang['admin_transfer_dp_transactions']}}</a>
                                <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_dp_delete']}}</a>
                                <a data-toggle="modal" data-target="#description{{$val->id}}" href="" class="dropdown-item">{{$lang['admin_transfer_dp_description']}}</a>
                                <a class="dropdown-item" data-toggle="modal" data-target="#donors{{$val->id}}" href="#">{{$lang['admin_transfer_dp_donors_2']}}</a>
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
        @php 
            $donors=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->latest()->get();
            $donated=App\Models\Donations::wheredonation_id($val->id)->wherestatus(1)->sum('amount');
        @endphp
        <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card bg-white border-0 mb-0">
                            <div class="card-header">
                                <h3 class="mb-0">{{$lang['admin_transfer_dp_are_you_sure_you_want_to_delete']}}</h3>
                            </div>
                            <div class="card-body px-lg-5 py-lg-5 text-right">
                                <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang['admin_transfer_dp_close']}}</button>
                                <a  href="{{route('delete.link', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang['admin_transfer_dp_proceed']}}</a>
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
                            <img class="card-img-top" src="{{url('/')}}/asset/profile/{{$val->image}}" alt="Image placeholder">
                            <div class="card-body">
                                <p class="mb-0 text-sm">{{$val->description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="donors{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card bg-white border-0 mb-0">
                            <div class="card-body px-lg-5 py-lg-5">
                                <ul class="list-group list-group-flush list my--3">
                                @if(count($donors)>0)
                                    @foreach($donors as $k=>$xval)
                                        <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="icon icon-shape text-white rounded-circle bg-success">
                                                    <i class="fad fa-gift"></i>
                                                </div>
                                            </div>
                                            <div class="col ml--2">
                                            <h4 class="mb-0">
                                                @if($xval->anonymous==0) 
                                                @if($xval->user_id==null)
                                                    @php
                                                        $fff=App\Models\Transactions::whereref_id($xval->ref_id)->first();
                                                    @endphp
                                                    {{$fff['first_name'].' '.$fff['last_name']}}
                                                @endif
                                                {{$xval->user['first_name'].' '.$xval->user['last_name']}} 
                                                @else 
                                                {{$lang['admin_transfer_dp_anonymous']}} 
                                                @endif
                                            </h4>
                                            <small>{{$currency->symbol.$xval->amount}} @ {{date("h:i:A j, M Y", strtotime($xval->created_at))}}</small>
                                            </div>
                                        </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item px-0"><p class="text-sm">{{$lang['admin_transfer_dp_no_donnors']}}</p></li>
                                @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    @endforeach

@stop