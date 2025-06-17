@extends('master')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{$lang['admin_web_currency_title']}}</h3>
            </div>
            <div class="table-responsive py-4">
                <table class="table table-flush" id="datatable-buttons">
                    <thead>
                        <tr>
                            <th>{{$lang['admin_web_currency_sn']}}</th>
                            <th>{{$lang['admin_web_currency_name']}}</th>
                            <th>{{$lang['admin_web_currency_country']}}</th>
                            <th>{{$lang['admin_web_currency_currency']}}</th>
                            <th>{{$lang['admin_web_currency_symbol']}}</th>
                            <th>{{$lang['admin_web_currency_status']}}</th>
                            <th class="text-center"></th>    
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cur as $k=>$val)
                        <tr>
                            <td>{{++$k}}.</td>
                            <td>{{$val->name}}</td>
                            <td>{{$val->country}}</td>
                            <td>{{$val->currency}}</td>
                            <td>{{$val->symbol}}</td>
                            <td>                                    
                                @if($val->status==1)
                                    <span class="badge badge-success">{{$lang['admin_web_currency_active']}}</span>
                                @else
                                    <span class="badge badge-danger">{{$lang['admin_web_currency_pending']}}</span>
                                @endif
                            </td>                               
                            <td class="text-center">
                            @if($val->status==0)
                                <div class="text-right">
                                    <div class="dropdown">
                                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @if($val->status==0)
                                            <a class='dropdown-item' href="{{route('change.currency', ['id' => $val->id])}}">{{$lang['admin_web_currency_set_as_default']}}</a>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                @endif  
                            </td>                 
                        </tr>
                        @endforeach               
                    </tbody>                    
                </table>
            </div>
        </div>
@stop