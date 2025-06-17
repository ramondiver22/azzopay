
@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["bank"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#modal-formx" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["bank_account"]}}</a>
      </div>
    </div>
    <div class="modal fade" id="modal-formx" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="mb-0 font-weight-bolder">{{$lang["add_bank_account"]}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form role="form" action="{{route('submit.bank')}}" method="post"> 
              @csrf
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="name" class="form-control" placeholder="{{$lang["bank"]}}">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="acct_name" class="form-control" placeholder="{{$lang["account_name"]}}" required>
                </div>
              </div>                                                                      
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="acct_no" pattern="\d*" maxlength="12" placeholder="{{$lang["account_no"]}}" class="form-control" required>
                </div>
              </div>                        
              <div class="form-group row">
                <div class="col-lg-12">
                  <input type="text" name="swift" class="form-control text-uppercase" placeholder="{{$lang["swift_code"]}}" required>
                </div>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{$lang["save"]}}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <div class="row">
    @if(count($bank)>0) 
      @foreach($bank as $k=>$val)
        <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-6">
                    <h3 class="mb-0 font-weight-bolder">{{$val->name}}</h3>
                  </div>
                  <div class="col-6 text-right">
                    <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                      <i class="fad fa-chevron-circle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left">
                    @if($val->status==0)
                      <a class="dropdown-item" href="{{route('bank.default', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["default"]}}</a>
                    @endif
                      <a class="dropdown-item" data-toggle="modal" data-target="#modal-form{{$val->id}}" href="#"><i class="fad fa-pencil"></i>{{$lang["edit"]}}</a>
                      <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["delete"]}}</a>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{$lang["default"]}} @if($val->status==1) {{$lang["yes"]}} @else {{$lang["no"]}} @endif</p>
                    <p class="text-sm mb-0 font-weight-bolder text-uppercase">{{$lang["name"]}} {{$val->acct_name}}</p>
                    <p class="text-sm mb-2 font-weight-bolder text-uppercase">{{$lang["swift_code"]}} <span class="text-uppercase">{{$val->swift}}</span></p>
                    <h4 class="mb-1 h2 text-primary font-weight-bolder">{{$val->acct_no}}</h4>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card bg-white border-0 mb-0">
                            <div class="card-header">
                              <h3 class="mb-0 font-weight-bolder">{{$lang["delete_bank_account"]}}</h3>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <span class="mb-0 text-xs">{{$lang["are_you_sure_to_delete_account"]}}</span>
                            </div>
                            <div class="card-body">
                                <a  href="{{route('bank.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["proceed"]}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-form{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="mb-0">{{$lang["edit_bank"]}}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form role="form" action="{{route('bank.edit')}}" method="post"> 
                  @csrf
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="text" name="name" placeholder="{{$lang["bank_name"]}}" class="form-control"  value="{{$val['name']}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="text" name="acct_name" placeholder="{{$lang["account_name"]}}" class="form-control"  value="{{$val['acct_name']}}">
                    </div>
                  </div>                           
                  <div class="form-group row">
                    <div class="col-lg-12">
                      <input type="number" name="acct_no" placeholder="{{$lang["account_number"]}}"  class="form-control" value="{{$val['acct_no']}}">
                      <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                  </div>                    
                  <div class="form-group row">
                    <div class="col-lg-10">
                      <input type="text" name="swift" placeholder="{{$lang["swift_code"]}}Swift code" class="form-control"  value="{{$val['swift']}}">
                      <input type="hidden" name="id" value="{{$val['id']}}">
                    </div>
                  </div>
                  <div class="text-right">
                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["update_account"]}}</button>
                  </div>
                </form>
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
          <h3 class="text-dark">{{$lang["no_bank_account"]}}</h3>
          <p class="text-dark text-sm card-text">{{$lang["we_couldnt_find_any_bank_account"]}}</p>
        </div>
      </div>
    @endif
  </div>
  <div class="row">
      <div class="col-md-12">
      {{ $bank->links() }}
      </div>
    </div>
@stop