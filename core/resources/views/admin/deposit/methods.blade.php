@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang["admin_deposits_method_payment_gateways"]}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang["admin_deposits_method_sn"]}}</th>
                                    <th>{{$lang["admin_deposits_method_mains_name"]}}</th>
                                    <th>{{$lang["admin_deposits_method_name_for_users"]}}</th>
                                    <th>{{$lang["admin_deposits_method_status"]}}</th>
                                    <th>{{$lang["admin_deposits_method_enviroment"]}}</th>
                                    <th>{{$lang["admin_deposits_method_updated"]}}</th>
                                    <th class="text-center">{{$lang["admin_deposits_method_action"]}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($gateway as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td>{{$val->main_name}}</td>
                                    <td>{{$val->name}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger">{{$lang["admin_deposits_method_disabled"]}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success">{{$lang["admin_deposits_method_active"]}}</span> 
                                        @endif
                                    </td>  
                                    <td>
                                        @if($val->sandbox==0)
                                            <span class="badge badge-success">{{$lang["admin_deposits_method_production"]}}</span>
                                        @elseif($val->sandbox==1)
                                            <span class="badge badge-info">{{$lang["admin_deposits_method_development"]}}</span> 
                                        @endif
                                    </td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-center">
                                    <a data-toggle="modal" data-target="#edit{{$val->id}}" class="" >
                                        <i class="icon-pencil7 mr-2"></i>{{$lang["admin_deposits_method_edit"]}}
                                    </a>
                                    </td>                   
                                </tr>
                                @endforeach               
                            </tbody>                    
                        </table>
                    </div>
                </div>
                @foreach($gateway as $k=>$val)
                    <div id="edit{{$val->id}}" class="modal fade" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{$val->main_name}}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{url('admin/storegateway')}}" method="post">
                                @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>{{$lang["admin_deposits_method_name_of_gateway"]}}</label>
                                                    <input value="{{$val->id}}"type="hidden" name="id">
                                                    <input type="text" value="{{$val->name}}" name="name" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>{{$lang["admin_deposits_method_deposit_charge"]}}</label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" name="charge" maxlength="10" class="form-control"value="{{$val->charge}}">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>{{$lang["admin_deposits_method_minimun_deposit"]}}</label>
                                                    <div class="input-group">
                                                        <input type="number" name="minamo" maxlength="10" class="form-control"value="{{$val->minamo}}">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text">{{$currency->name}}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>{{$lang["admin_deposits_method_maxmum_deposit"]}}</label>
                                                    <div class="input-group">
                                                        <input type="number" name="maxamo" maxlength="10" class="form-control"value="{{$val->maxamo}}">
                                                        <span class="input-group-append">
                                                            <span class="input-group-text">{{$currency->name}}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($val->id==101)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_paypal_business_email"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>  
                                        @elseif($val->id==102)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_perfect_money_usd_account"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_alternate_passphrase"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($val->id==103)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_publishable"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_secret_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($val->id==104)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_merchant_email"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_secret_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div> 
                                            @elseif($val->id==107)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_public_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_secret_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>                                                        
                                            @elseif($val->id==108)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_public_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_secret_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>                                                        
                                            @elseif($val->id==508)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_public_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_secret_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>                                                     
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_merchant_code"]}}</label>
                                                        <input type="text" value="{{$val->val3}}" class="form-control" id="val3" name="val3">
                                                    </div>
                                                </div>
                                            </div>                                                    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_private_key"]}}</label>
                                                        <input type="text" value="{{$val->val4}}" class="form-control" id="val4" name="val4">
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($val->id==501)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_api_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_xpub_code"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($val->id==505)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_public_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_private_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>                                                      
                                            @elseif($val->id==506 || $val->id==507)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_public_key"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_private_key"]}}</label>
                                                        <input type="text" value="{{$val->val2}}" class="form-control" id="val2" name="val2">
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label>{{$lang["admin_deposits_method_payment_details"]}}</label>
                                                        <input type="text" value="{{$val->val1}}" class="form-control" id="val1" name="val1">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group">
                                                <label>{{$lang["admin_deposits_method_status"]}}</label>
                                                <select class="form-control select" name="status">
                                                    <option value="1" 
                                                        @if($val->status==1)
                                                            selected
                                                        @endif
                                                        >{{$lang["admin_deposits_method_active"]}}
                                                    </option>
                                                    <option value="0"  
                                                        @if($val->status==0)
                                                            selected
                                                        @endif
                                                        >{{$lang["admin_deposits_method_deactive"]}}
                                                    </option>
                                                </select>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label>{{$lang["admin_deposits_method_enviroment"]}}</label>
                                                <select class="form-control select" name="sandbox">
                                                    <option value="1" 
                                                        @if($val->sandbox==1)
                                                            selected
                                                        @endif
                                                        >{{$lang["admin_deposits_method_development"]}}
                                                    </option>
                                                    <option value="0"  
                                                        @if($val->sandbox==0)
                                                            selected
                                                        @endif
                                                        >{{$lang["admin_deposits_method_production"]}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_deposits_method_close"]}}</button>
                                        <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_deposits_method_save_changes"]}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop