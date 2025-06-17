@extends('master')

@section('content')
<div class="container-fluid mt--6">
  <div class="content-wrapper">

        @if($ticket->files!=null)
        <div class="card">
          <div class="card-header">
            <!-- Title -->
            <h5 class="h3 mb-0">{{$lang['admin_users_edit_ticket_attachments']}}</h5>
          </div>
          <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
              
                @php
                    $arrayFiles = json_decode($ticket->files);
                @endphp
                @if(is_array($arrayFiles)) 
                    @foreach($arrayFiles as $ticketFile)
                
                <div class="timeline-block">
                  <span class="timeline-step badge-success">
                    <i class="fa fa-file"></i>
                  </span>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between pt-1">
                      <div>
                        <a href="{{url('/')}}/asset/profile/{{$ticketFile}}"><span class="text-muted text-sm">{{$ticketFile}}</span></a>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
        @endif
    <div class="card shadow">
          <div class="card-header bg-transparent">
            <h3 class="mb-0">{{$lang['admin_users_edit_ticket_log']}}</h3>
          </div>
          <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
              <div class="timeline-block">
                  <span class="timeline-step badge-primary">
                      <i class="fa fa-star"></i>
                  </span>
                  <div class="timeline-content">
                      <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($ticket->created_at))}}</small>
                      <h5 class="mt-3 mb-0">{{$ticket->message}}</h5>
                      <p class="text-sm mt-1 mb-0">{{$ticket->user['first_name'].' '.$ticket->user['last_name']}}</p>
                  </div>
              </div>
            @foreach($reply as $df)
              @if($df->status==1)
                <div class="timeline-block">
                  <span class="timeline-step badge-primary">
                    <i class="fa fa-star"></i>
                  </span>
                  <div class="timeline-content">
                    <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($df->created_at))}}</small>
                    <h5 class="mt-3 mb-0">{{$df->reply}}</h5>
                    <p class="text-sm mt-1 mb-0">{{$ticket->user['first_name'].' '.$ticket->user['last_name']}}</p>
                  </div>
                </div>
                @elseif($df->status==0)
                  <div class="timeline-block">
                      <span class="timeline-step badge-primary">
                      <i class="fa fa-star"></i>
                      </span>
                      <div class="timeline-content">
                      <small class="text-xs">{{date("Y/m/d h:i:A", strtotime($df->created_at))}}</small>
                      <h5 class="mt-3 mb-0">{{$df->reply}}</h5>
                      <p class="text-sm mt-1 mb-0">@if($df->staff_id==1) {{$lang['admin_users_edit_ticket_administrator']}} @else {{$df->staff['first_name'].' '.$df->staff['last_name']}} - <span class="badge badge-pill badge-success">{{$lang['admin_users_edit_ticket_staff']}}</span> @endif</p>
                      </div>
                  </div>
                @endif
            @endforeach
            </div>
          </div>
        </div>
		<div class="card">
          	<div class="card-header header-elements-inline">
            	<h3 class="mb-0">{{$lang['admin_users_edit_ticket_reply']}}</h3>
          	</div>
          	<div class="card-body">
				<form method="post" action="{{route('ticket.reply')}}">
					@csrf
					<textarea class="form-control mb-3" rows="4" placeholder="{{$lang['admin_users_edit_ticket_enter_your_message']}}"  name="reply" required></textarea>
					<input type="hidden"  name="ticket_id" value="{{$ticket->ticket_id}}">			
					<input type="hidden"  name="staff_id" value="{{$admin->id}}">			
					<div class="d-flex align-items-center">
						<button type="submit" class="btn btn-success btn-sm">{{$lang['admin_users_edit_ticket_send']}}</button>
					</div>	
				</form>
		  	</div>
        </div>
	</div>
@stop