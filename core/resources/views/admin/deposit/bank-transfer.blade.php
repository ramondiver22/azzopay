@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang["admin_deposits_bank_transfer_update_bank_details"]}}</h3>
                        <p class="card-text text-sm">{{$lang["admin_deposits_bank_transfer_last_updated"]}}: {{date("Y/m/d h:i:A", strtotime($bank->updated_at))}}</p>
                    </div>
                    <div class="card-body">
                        <form action="{{url('admin/bankdetails')}}" method="post">
                        @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_name"]}}</label>
                                <div class="col-lg-10">
                                <input type="text" name="name" class="form-control" value="{{$bank->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_bank_name"]}}</label>
                                <div class="col-lg-10">
                                <input type="text" name="bank_name" class="form-control" value="{{$bank->bank_name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_bank_address"]}}</label>
                                <div class="col-lg-10">
                                <input type="text" name="address" class="form-control" value="{{$bank->address}}">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_iban_code"]}}</label>
                                <div class="col-lg-10">
                                <input type="text" name="iban" class="form-control" value="{{$bank->iban}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_swift_code"]}}</label>
                                <div class="col-lg-10">
                                <input type="text" name="swift" class="form-control" value="{{$bank->swift}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">{{$lang["admin_deposits_bank_transfer_account_number"]}}</label>
                                <div class="col-lg-10">
                                <input type="number" name="acct_no" class="form-control" value="{{$bank->acct_no}}">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <div class="col-lg-5"> 
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        @if($bank->status==1)
                                            <input type="checkbox" name="status" id="customCheckLogin" class="custom-control-input" value="1" checked>
                                        @else
                                            <input type="checkbox" name="status" id="customCheckLogin"  class="custom-control-input" value="1">
                                        @endif
                                        <label class="custom-control-label" for="customCheckLogin">
                                        <span class="text-muted">{{$lang["admin_deposits_bank_transfer_status"]}}</span>     
                                        </label>
                                    </div> 
                                </div> 
                            </div>               
                            <div class="text-right">
                                <button type="submit" class="btn btn-success btn-sm">{{$lang["admin_deposits_bank_transfer_save"]}}</button>
                            </div>
                        </form>
                    </div>
                </div> 
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$lang["admin_deposits_bank_transfer_bank_transfer"]}}</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead>
                                <tr>
                                    <th>{{$lang["admin_deposits_bank_transfer_sn"]}}</th>
                                    <th>{{$lang["admin_deposits_bank_transfer_name"]}}</th>
                                    <th>{{$lang["admin_deposits_bank_transfer_amount"]}}</th>                                                                       
                                    <th>{{$lang["admin_deposits_bank_transfer_status"]}}</th>
                                    <th>{{$lang["admin_deposits_bank_transfer_created"]}}</th>
                                    <th>{{$lang["admin_deposits_bank_transfer_updated"]}}</th>
                                    <th class="text-center">{{$lang["admin_deposits_bank_transfer_action"]}}</th>    
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($deposit as $k=>$val)
                                <tr>
                                    <td>{{++$k}}.</td>
                                    <td><a href="{{url('admin/manage-user')}}/{{$val->user['id']}}">{{$val->user['first_name'].' '.$val->user['last_name']}}</a></td>
                                    <td>{{$currency->symbol.number_format($val->amount)}}</td>
                                    <td>
                                        @if($val->status==0)
                                            <span class="badge badge-danger badge-pill">{{$lang["admin_deposits_bank_transfer_pending"]}}</span>
                                        @elseif($val->status==1)
                                            <span class="badge badge-success badge-pill">{{$lang["admin_deposits_bank_transfer_approved"]}}</span>
                                        @elseif($val->status==2)
                                            <span class="badge badge-info badge-pill">{{$lang["admin_deposits_bank_transfer_declined"]}}</span> 
                                        @endif
                                    </td>  
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->created_at))}}</td>
                                    <td>{{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</td>
                                    <td class="text-center">
                                        <div class="">
                                            <div class="dropdown">
                                                <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="dropdown-item">{{$lang["admin_deposits_bank_transfer_delete"]}}</a>
                                                    @if($val->status==0)
                                                        <a class='dropdown-item' href="{{route('deposit.declinebk', ['id' => $val->id])}}">{{$lang["admin_deposits_bank_transfer_decline"]}}</a>
                                                        <a class='dropdown-item' href="{{route('deposit.approvebk', ['id' => $val->id])}}">{{$lang["admin_deposits_bank_transfer_approve"]}}</a>
                                                    @endif
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
                @foreach($deposit as $k=>$val)
                    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0">{{$lang["admin_deposits_bank_transfer_are_you_sure"]}}</h3>
                                        </div>
                                        <div class="card-body px-lg-5 py-lg-5 text-right">
                                            <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_deposits_bank_transfer_close"]}}</button>
                                            <a  href="{{route('banktransfer.delete', ['id' => $val->id])}}" class="btn btn-danger btn-sm">{{$lang["admin_deposits_bank_transfer_proceed"]}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="details{{$val->id}}" class="modal fade" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">   
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="text-sm text-dark">{{$val->details}}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-neutral btn-sm" data-dismiss="modal">{{$lang["admin_deposits_bank_transfer_close"]}}</button>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="modal fade" id="screenshot{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                            <div class="castro-fade">
                                <div class="modal-body p-0" >
                                    <div class=" border-0 mb-0 text-center">
                                        <div class="px-lg-5 py-lg-5">
                                            <img src="{{url('/')}}/asset/screenshot/{{$val->image}}" class="mb-3 user-profile">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop