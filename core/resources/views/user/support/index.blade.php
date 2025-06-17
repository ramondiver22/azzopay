@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["support_disputes"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a href="{{route('open.ticket')}}" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["support_open_ticket"]}}</a>
      </div>
    </div>
    <div class="row">
      @if(count($ticket)>0)
        @foreach($ticket as $k=>$val)
          <div class="col-md-6">
            <div class="card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-7">
                      <!-- Title -->
                      <h3 class="mb-0 font-weight-bolder">#{{$val->ticket_id}}</h3>
                    </div>
                    <div class="col-5 text-right">
                      <a href="{{url('/')}}/user/reply-ticket/{{$val->id}}" class="btn btn-sm btn-neutral">{{$lang["support_reply"]}}</a>
                      <a data-toggle="modal" data-target="#delete{{$val->id}}" href="" class="btn btn-sm btn-danger">{{$lang["support_delete"]}}</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <p class="text-sm mb-0">{{$lang["support_subject"]}}: {{$val->subject}}</p>
                      <p class="text-sm mb-0">{{$lang["support_transaction_reference"]}}: @if($val->ref_no==null){{$lang["support_null"]}} @else {{$val->ref_no}} @endif</p>
                      <p class="text-sm mb-0">{{$lang["support_priority"]}}: {{$val->priority}}</p>
                      <p class="text-sm mb-0">{{$lang["support_status"]}}: @if($val->status==0){{$lang["support_open"]}} @elseif($val->status==1){{$lang["support_closed"]}} @elseif($val->status==2){{$lang["support_resolved"]}} @endif</p>
                      <p class="text-sm mb-0">{{$lang["support_created"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                      <p class="text-sm mb-2">{{$lang["support_updated"]}}: {{date("Y/m/d h:i:A", strtotime($val->updated_at))}}</p>
                      <span class="badge badge-pill badge-success">{{$val->type}}</span>
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
                                <h3 class="mb-0 font-weight-bolder">{{$lang["support_delete_ticket"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="mb-0 text-xs">{{$lang["support_support_confirm_delete"]}}</span>
                              </div>
                              <div class="card-body">
                                  <a  href="{{route('ticket.delete', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["support_proceed"]}}</a>
                              </div>
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
            <h3 class="text-dark">{{$lang["support_no_ticket_found"]}}</h3>
            <p class="text-dark text-sm card-text">{{$lang["support_we_couldnt_find_any_ticket"]}}</p>
          </div>
        </div>
      @endif
    </div>
    <div class="row">
      <div class="col-md-12">
      {{ $ticket->links('pagination::bootstrap-4') }}
      </div>
    </div>
@stop