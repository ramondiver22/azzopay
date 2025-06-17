@extends('userlayout')

@section('content')

<style>
#chart-payment-div {
  margin-left: calc(50% - 100px);
  width: 200px;
  height: 200px;
}
</style>
<div class="container-fluid mt--6">
    <div class="content-wrapper">
      
        <div class="row">
            <div class="col-lg-9 col-xs-12">

                <div class="row mt-2">
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card" style="background-color: rgb(255 1 126);">
                            <div class="card-body">
                                
                                <h4 class="card-title">{{$lang["home_trasactions_number"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <h4>{{$n_transactions}}</h4>
                                        
                                        <svg  class="float-right" style="margin-top: -28px;" width="28" height="16" viewBox="0 0 28 16" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.706.22L4.324 7.033.33 2.813l.042 12.615 11.858.044-3.953-4.22 6.383-6.769L10.706.22zm6.588 15.384l6.424-6.857 3.953 4.22L27.63.307H15.771l3.994 4.22-6.424 6.813 3.953 4.264z" fill="#65A300"></path></svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{$lang["home_avg_ticket"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <h4>R$ {{number_format($n_avgticket, 2, ",", ".")}}</h4>
                                        <svg class="float-right" style="margin-top: -28px;" width="34" height="20" viewBox="0 0 34 20"><path fill="#7052C8" fill-rule="evenodd" stroke="#7052C8" d="M2.134 18.644a1.134 1.134 0 01-.852-1.882l6.401-7.296a1.134 1.134 0 011.704-.002l4.854 5.512 4.771-7.78a1.134 1.134 0 011.797-.181l4.243 4.542 5.834-9.995a1.135 1.135 0 011.959 1.144l-6.603 11.31a1.134 1.134 0 01-1.808.203l-4.267-4.567-4.796 7.82a1.132 1.132 0 01-1.818.157l-5.016-5.696-5.55 6.326a1.136 1.136 0 01-.853.385z"></path></svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card" style="background-color: rgb(195 255 0); color: #000 !important;">
                            <div class="card-body">
                                <h4 class="card-title" style="color: #000;">{{$lang["home_transactions_volume"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <h4 style="color: #000;">R$ {{number_format($n_transactions_total, 2, ",", ".")}}</h4>
                                        <svg class="float-right" style="margin-top: -28px;" width="26" height="28" viewBox="0 0 26 28" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M25.556 11.18a12.374 12.374 0 00-1.296-3.051 12.705 12.705 0 00-2.067-2.618 12.96 12.96 0 00-2.691-2.01 13.121 13.121 0 00-3.137-1.262A13.41 13.41 0 0013 1.807V0a15.29 15.29 0 00-3.844.494 15.03 15.03 0 00-3.585 1.442 14.833 14.833 0 00-3.077 2.297c-.915.89-1.71 1.896-2.361 2.993l1.608.903a12.396 12.396 0 00-1.296 3.05A12.375 12.375 0 000 14.454c0 1.1.15 2.2.445 3.272a12.39 12.39 0 001.296 3.05c.57.96 1.266 1.84 2.067 2.619.823.8 1.73 1.47 2.691 2.01.997.56 2.051.98 3.137 1.262 1.1.285 2.232.431 3.364.431 1.132 0 2.264-.145 3.365-.431a13.13 13.13 0 003.136-1.261c.98-.55 1.886-1.227 2.692-2.011.823-.8 1.51-1.683 2.067-2.618A12.397 12.397 0 0026 14.453c0-1.102-.15-2.202-.444-3.273zm-2.905 8.693a10.9 10.9 0 01-1.771 2.244 11.146 11.146 0 01-4.996 2.804c-.944.245-1.913.37-2.884.37a11.474 11.474 0 01-5.572-1.451 11.17 11.17 0 01-2.308-1.723 10.913 10.913 0 01-1.77-2.244c-.494-.83-.864-1.71-1.112-2.616a10.603 10.603 0 01-.381-2.804 10.638 10.638 0 011.492-5.42l1.609.903 1.608.903 1.608.903 1.612.905 1.607.904a1.785 1.785 0 00-.25.902 1.792 1.792 0 00.93 1.562c.284.158.602.242.927.244a1.92 1.92 0 001.313-.53c.117-.114.214-.24.293-.374a1.747 1.747 0 00.186-1.37 1.808 1.808 0 00-1.312-1.276 1.92 1.92 0 00-.48-.063V3.613c.97 0 1.94.125 2.884.37a11.177 11.177 0 014.996 2.804 10.879 10.879 0 011.77 2.245c.494.83.864 1.71 1.112 2.615.252.918.382 1.861.382 2.805 0 .944-.13 1.887-.382 2.805a10.6 10.6 0 01-1.111 2.616z" fill="#4079BB"></path></svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{$lang["home_payment_methods"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16"><g fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" stroke="#7052c8"><path d="M5.469 11.836l2.398-2.402s.012-.008.031-.008c.024 0 .032.008.036.008l2.406 2.41c.41.41.93.676 1.488.765l-2.39 2.395a2.035 2.035 0 01-2.875 0l-2.43-2.43c.5-.113.96-.367 1.336-.738zm0 0M10.34 4.156l-2.406 2.41c-.02.016-.051.016-.067 0L5.47 4.164a2.768 2.768 0 00-1.336-.738l2.43-2.43a2.035 2.035 0 012.875 0l2.39 2.395a2.713 2.713 0 00-1.488.765zm0 0M.996 6.563l1.695-1.696h.832c.325 0 .649.133.88.363L6.8 7.63c.176.176.383.3.605.375-.222.07-.433.195-.605.367L4.402 10.77c-.23.23-.554.363-.879.363h-.832L.996 9.438a2.035 2.035 0 010-2.875zm8 1.808a1.484 1.484 0 00-.601-.367c.218-.074.425-.2.601-.375l2.41-2.406c.23-.23.551-.368.88-.368h1.01l1.708 1.708a2.035 2.035 0 010 2.875l-1.707 1.707h-1.012c-.328 0-.648-.137-.879-.368zm0 0" stroke-width="0.8000039999999999"></path></g></svg>
                                        <span >{{$lang["home_pix"]}}</span>
                                        <span class="float-right"><strong>R$ {{number_format($n_transactions_pix, 2, ",", ".")}}</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <svg width="16" height="11" viewBox="0 0 16 11"><g fill="#7052C8"><path d="M14.772 9.978h.023a.445.445 0 00.323-.11.375.375 0 00.122-.314V1.092A.362.362 0 0015.118.8a.464.464 0 00-.346-.11L1.205.688a.445.445 0 00-.323.11.375.375 0 00-.122.314v8.462a.362.362 0 00.122.293.464.464 0 00.346.11h13.544zm-13.521.687a1.249 1.249 0 01-.905-.311 1.004 1.004 0 01-.344-.8v-8.42a1.018 1.018 0 01.345-.82c.238-.217.568-.33.882-.312H14.75c.337-.018.667.095.905.311.239.217.364.515.344.8v8.42c.02.306-.105.604-.344.82-.235.214-.56.327-.892.312H1.25z"></path><path d="M2.717 8.23v-1H6.34v1zm8.749-1.657c-.402 0-.729.31-.729.692 0 .382.327.691.729.691h1.647c.402 0 .728-.31.728-.691 0-.382-.326-.692-.728-.692h-1.647zm0-.676h1.647c.795 0 1.44.613 1.44 1.368 0 .755-.645 1.367-1.44 1.367h-1.647c-.796 0-1.44-.612-1.44-1.367s.644-1.368 1.44-1.368z"></path><circle cx="3.019" cy="3.829" r="1"></circle><circle cx="4.528" cy="3.829" r="1"></circle><circle cx="6.038" cy="3.829" r="1"></circle><circle cx="7.547" cy="3.829" r="1"></circle><circle cx="9.057" cy="3.829" r="1"></circle><circle cx="10.566" cy="3.829" r="1"></circle></g></svg>
                                        <span >{{$lang["home_credit_card"]}}</span>
                                        <span class="float-right" ><strong>R$ {{number_format($n_transactions_creditcard, 2, ",", ".")}}</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <svg width="16" height="10" viewBox="0 0 16 10"><g fill="#E39D30" fill-rule="evenodd"><path d="M0 0h1v10H0zm2.667 0h1v10h-1zm2 0h1v10h-1zm2 0h1v10h-1zm2.666 0h1v10h-1zm2 0h1v10h-1zm3.334 0h1v10h-1zM1.333 0h1v10h-1z"></path><path d="M4 0h1v10H4zm2 0h1v10H6zm2.667 0h1v10h-1zm2 0h1v10h-1zm2 0h1v10h-1zm2.666 0h1v10h-1z"></path></g></svg>
                                        <span >{{$lang["home_boleto"]}}</span>
                                        <span class="float-right" ><strong>R$ {{number_format($n_transactions_boleto, 2, ",", ".")}}</strong></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{$lang["home_payment_brands"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16"><g fill="none" fill-rule="evenodd"><path fill="#F5F5F5" d="M1 0h14a1 1 0 011 1v14a1 1 0 01-1 1H1a1 1 0 01-1-1V1a1 1 0 011-1z"></path><path fill="#F79F1A" d="M9.931 5A3.1 3.1 0 008 5.669 2.968 2.968 0 019.138 8c0 .941-.443 1.782-1.138 2.331.53.418 1.2.669 1.931.669C11.627 11 13 9.657 13 8c0-1.656-1.373-3-3.069-3"></path><path fill="#EA001B" d="M6.863 8c0-.941.443-1.782 1.137-2.331A3.1 3.1 0 006.069 5C4.374 5 3 6.344 3 8s1.374 3 3.069 3A3.1 3.1 0 008 10.331 2.967 2.967 0 016.863 8"></path><path fill="#FF5F01" d="M9 8c0-.808-.39-1.528-1-2-.61.472-1 1.192-1 2s.39 1.528 1 2c.61-.472 1-1.192 1-2"></path></g></svg>
                                        <span >{{$lang["home_mastercard"]}}</span>
                                        <span  class="float-right" ><strong>R$ {{number_format($n_transactions_mastercard, 2, ",", ".")}}</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><rect width="16" height="16" rx="1" fill="#F2F2F3"></rect><path d="M7.953 6.196l-.65 3.036h-.785l.65-3.036h.785zm3.305 1.96l.413-1.14.238 1.14h-.651zm.876 1.075h.726l-.633-3.035h-.67a.357.357 0 00-.335.222l-1.179 2.814h.825l.164-.454h1.008l.094.454zm-2.05-.99c.004-.802-1.108-.846-1.1-1.203.002-.11.106-.226.333-.256a1.49 1.49 0 01.774.136l.138-.644a2.122 2.122 0 00-.735-.134c-.776 0-1.323.412-1.327 1.004-.005.437.39.68.688.826.306.149.408.244.407.378-.002.203-.244.293-.47.296-.394.007-.624-.106-.806-.19l-.143.664c.183.084.522.157.874.161.825 0 1.364-.408 1.367-1.038zM6.83 6.196L5.558 9.232h-.83l-.626-2.423c-.039-.15-.072-.204-.187-.267-.189-.103-.5-.199-.775-.259l.019-.087h1.336c.17 0 .323.113.362.31l.331 1.756.817-2.066h.826" fill="#15195A"></path></svg>
                                        <span >{{$lang["home_visa"]}}</span>
                                        <span  class="float-right" ><strong>R$ {{number_format($n_transactions_visa, 2, ",", ".")}}</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <img src="{{url('/')}}/asset/images/brands/elo.svg" width="16" height="32" />
                                        <span >{{$lang["home_elo"]}}</span>
                                        <span  class="float-right" ><strong>R$ {{number_format($n_transactions_elo, 2, ",", ".")}}</strong></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col col-lg-4 col-xs-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{$lang["home_reason_for_refusal"]}}</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16"><g fill="#7052C8" fill-rule="evenodd"><path d="M9.838 4.168l-2.61 2.611-2.61-2.611-.45.45 2.61 2.61-2.61 2.61.45.451 2.61-2.61 2.61 2.61.451-.45-2.611-2.61 2.611-2.61z"></path><path fill-rule="nonzero" d="M6.946 13.893a6.916 6.916 0 004.438-1.609L15.099 16l.901-.901-3.715-3.716a6.92 6.92 0 001.607-4.437A6.954 6.954 0 006.946 0 6.954 6.954 0 000 6.946c0 3.83 3.116 6.947 6.946 6.947zm0-12.62a5.678 5.678 0 015.671 5.672 5.678 5.678 0 01-5.671 5.672 5.678 5.678 0 01-5.67-5.672 5.676 5.676 0 015.67-5.672z"></path></g></svg>
                                        <span >{{$lang["home_no_acquirer_configured"]}}</span>
                                        <span  class="float-right" ><strong>0</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <svg width="13" height="12" viewBox="0 0 13 12"><path fill="#7052C8" d="M12.612 5.167L6.64.09a.367.367 0 00-.482 0L.149 5.196a.442.442 0 00-.128.47.394.394 0 00.369.282h1.27v5.21H.39V12h12.015v-.842h-1.12v-5.21h1.125c.215 0 .39-.189.39-.42a.43.43 0 00-.188-.361zM6.398.957l4.882 4.149H1.515L6.398.956zm1.159 4.99v5.211H5.388v-5.21h2.169zm-5.117 0h2.17v5.211H2.44v-5.21zm8.066 5.211h-2.17v-5.21h2.17v5.21z"></path></svg>
                                        <span >{{$lang["home_issuer"]}}</span>
                                        <span  class="float-right" ><strong>0</strong></span>
                                    </li>
                                    <li class="list-group-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16"><path fill="#C3232A" d="M.624 2.962a14.99 14.99 0 003.55-.965A17.15 17.15 0 007.524.144a.852.852 0 01.962.009 16.446 16.446 0 003.34 1.844c1.099.448 2.293.793 3.572.974.348.052.602.301.602.586 0 3.05-.497 5.411-1.701 7.376-1.205 1.99-3.107 3.542-5.919 4.98a.89.89 0 01-.782-.008c-2.8-1.43-4.692-2.99-5.907-4.972C.497 8.968 0 6.607 0 3.557c0-.302.275-.552.624-.595zm4.702 5.196c-.285-.232-.285-.603 0-.836a.84.84 0 011.025 0L7.746 8.46l2.674-2.172c.275-.232.74-.232 1.025-.008.275.232.286.612 0 .836l-3.18 2.602a.853.853 0 01-1.026 0l-1.913-1.56zm-.507-5.11a16.889 16.889 0 01-3.36.991c.052 2.637.507 4.688 1.532 6.368 1.025 1.69 2.642 3.042 5.009 4.3 2.357-1.258 3.974-2.61 4.999-4.3 1.035-1.68 1.49-3.73 1.543-6.368a17.018 17.018 0 01-3.372-.99A17.657 17.657 0 018 1.358a17.756 17.756 0 01-3.181 1.69z"></path></svg>
                                        <span >{{$lang["home_denied_by_anti_fraud"]}}</span>
                                        <span  class="float-right" ><strong>0</strong></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
          
            <div class="col-lg-3 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{$lang["home_transactions_by_status"]}}</h4>
                        <div class="row">
                            <div class="col col-xs-12 text-center">
                                <div id="chart-payment-div">

                                </div>
                            </div>
                        </div>
                        
                        <ul class="list-group">
                            <li class="list-group-item">
                                <small class="text-success">{{$lang["home_paid"]}}</small>
                                <small class="text-success float-right">{{$n_paid}}</small>
                            </li>
                            <li class="list-group-item">
                                <small class="text-yellow">{{$lang["home_waiting_payment"]}}</small>
                                <small class="text-yellow float-right">{{$n_pending}}</small>
                            </li>
                            <li class="list-group-item">
                                <small class="text-danger">{{$lang["home_defaulter"]}}</small>
                                <small class="text-danger float-right">{{$n_defaulter}}</small>
                            </li>
                        </ul>
                        
                        
                    </div>
                </div>
            </div>
        </div>
      
    <div class="row"> 
      <div class="col-lg-8">
        <div class="row"> 
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h4 class="font-weight-bolder text-gray"><i class="fad fa-undo-alt"></i> {{$lang["earning_log"]}}</h5>
                @if(count($history)>0)
                <canvas id="myChart" width="80%" height="50%"></canvas>
                @else
                  <div class="text-center mb-5 mt-8">
                    <div class="mb-3">
                      <img src="{{url('/')}}/asset/images/empty.svg">
                    </div>
                    <h3 class="text-dark">{{$lang["no_earning_history"]}}</h3>
                    <p class="text-dark text-sm card-text">{{$lang["we_couldnt_find_any_log_to_this_account"]}}</p>
                  </div>
                @endif
              </div>     
            </div>     
          </div>                  
          
          <div class="col-12">

            <div class="card mt-5">
              <div class="card-body">
                  <h5 class="text-gray mb-3 h4"><i class="fad fa-sack-dollar"></i> Lançamentos Futuros </h5>
                  <div class="table-responsive py-4">
                      <table class="table table-flush table-bordered table-condensed table-striped" >
                          <thead>
                          <tr>
                              <th class="text-center">Data</th>
                              <th class="text-center">Descrição</th>
                              <th class="text-center">Valor a Receber</th>
                              <th class="text-center">Data Liberação</th>
                          </tr>
                          </thead>
                          <tbody> 
                          
                          @foreach($pendingBalanceList as $pendingBalanceReg)
                            
                              <tr>
                                  <td class="text-center">{{date("d/m/Y H:i:s", strtotime($pendingBalanceReg->created_at))}}.</td>
                                  <td>{{$pendingBalanceReg->description}}</td>
                                  <td class="text-center">{{$currency->symbol. " " . number_format($pendingBalanceReg->amount, 2, ',', '.')}}</td>
                                  <td class="text-center">{{date("d/m/Y", strtotime($pendingBalanceReg->liquidation_date))}}</td>
                                  
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
          </div>



        </div> 
      </div>  
      <div class="col-lg-4"> 
        <div class="row align-items-center text-center">

          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-sack-dollar"></i> Saldo Bloqueado</h5>
            <h5 class="mb-1 h2">{{$currency->name}} {{number_format($pendingBalance, 2, '.', '.')}}</h5>
            <hr>
          </div> 

          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-sack-dollar"></i> {{$lang["revenue"]}}</h5>
            <h5 class="mb-1 h2">{{$currency->name}} {{number_format($revenue, 2, ',', '.')}}</h5>
            <hr>
          </div> 


          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-cart-plus"></i> {{$lang["total_payout"]}}</h5>
            <h5 class="mb-1 h2">{{$currency->name}} {{number_format($t_payout, 2, '.', '')}}</h5>
            @if($user->business_level==1)
              <p class="text-gray mb-3">{{number_format($t_payout/$set->withdraw_limit*100, 2, '.', '')}}% {{$lang["of_limit"]}}</p>
              @if($user->kyc_status==0)
              <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i>  {{$lang["upgrade_account"]}}</a> 
              @endif
            @elseif($user->business_level==2)
              <p class="text-gray mb-3">{{number_format($t_payout/$set->starter_limit*100, 2, '.', '')}}% {{$lang["of_limit"]}}</p>
              @if($user->kyc_status==0)
              <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i>  {{$lang["upgrade_account"]}}</a> 
              @endif
            @elseif($user->business_level==3)
            <p class="text-gray mb-3">{{$lang["no_limit"]}}</p>
            @endif
            <hr>
          </div>          
          <div class="col-12 mt-5">   
            <h5 class="text-gray mb-3 h4"><i class="fad fa-calendar"></i> {{$lang["next_payout"]}}</h5>
            <h5 class="mb-2 h2">{{$currency->name}} {{number_format($n_payout, 2, '.', '')}}</h5>
            <p class="text-gray mb-3">{{$lang["due"]}} {{date("Y/m/d", strtotime($set->next_settlement))}}</p>
            <a href="{{route('user.withdraw')}}" class="btn btn-sm btn-neutral"><i class="fad fa-history"></i> {{$lang["past_payouts"]}}</a> 
          </div>
        </div>

        <div class="row mt-5">

        <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
              <h4 class="font-weight-bolder">{{$lang["api_documentation"]}}</h4>
                <p class="text-gray mb-1">{{$lang["our_documentation_need_to_integrate"]}} {{$set->site_name}} {{$lang["in_your_website"]}}</p>
                <!--
                <a href="{{route('user.merchant-documentation')}}" class="btn btn-sm btn-neutral mb-5"><i class="fad fa-file-alt"></i> {{$lang["go_to_docs"]}}</a> 
                -->
                
                <a href="https://documenter.getpostman.com/view/2065421/UzXLyHss"  target="DOCS"  class="btn btn-sm btn-neutral mb-5"><i class="fad fa-file-alt"></i> {{$lang["go_to_docs"]}}</a> 
                
                
                <h4 class="mb-2 font-weight-bolder">{{$lang["your_keys"]}}</h4>
                <div class="mb-3">
                  <span class="text-gray mb-3">{{$lang["also_available_in"]}}</span> <a href="{{route('user.api')}}">{{$lang["settings_api_keys"]}}</a>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{$lang["public_key"]}}</span>
                      </div> 
                      <input type="text" name="public_key" class="form-control" placeholder="{{$lang["public_key"]}}" value="{{$user->public_key}}">   
                      <div class="input-group-prepend bg-gray">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->public_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                      </div> 
                    </div>
                  </div>
                </div>                
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">{{$lang["secret_key"]}}</span>
                      </div> 
                      <input type="text" name="secret_key" class="form-control" placeholder="{{$lang["secret_key"]}}" value="{{$user->secret_key}}">   
                      <div class="input-group-prepend bg-gray">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->secret_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                      </div> 
                    </div> 
                  </div>
                </div>              
                <div class="form-group row">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text text-xs text-uppercase">Basic</span>
                      </div> 
                      <input type="text" name="secret_key" class="form-control" placeholder="Basic" value="{{base64_encode($user->public_key. ":" .$user->secret_key)}}">   
                      <div class="input-group-prepend bg-gray">
                        <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{base64_encode($user->public_key. ":" .$user->secret_key)}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                      </div> 
                    </div> 
                  </div>
                </div>
              </div>              
            </div>              
          </div> 
          
        
        </div>


      </div>
    </div>
        
        
  <script src="{{url('/')}}/asset/js/amcharts/index.js"></script>
  <script src="{{url('/')}}/asset/js/amcharts/xy.js"></script>
  <script src="{{url('/')}}/asset/js/amcharts/Animated.js"></script>
  <script src="{{url('/')}}/asset/js/amcharts/pt_BR.js"></script>
  <script src="{{url('/')}}/asset/js/amcharts/percent.js"></script>
  
  <script>
am5.ready(function() {

    var root = am5.Root.new("chart-payment-div");

    /*am5.Color.new(root, {
        colors: [
        am5.color(0x73556E),
        am5.color(0x9FA1A6),
        am5.color(0xF2AA6B),
        am5.color(0xF28F6B),
        am5.color(0xA95A52),
        am5.color(0xE35B5D),
        am5.color(0xFFA446)
      ]
    })*/

    root.setThemes([
      am5themes_Animated.new(root)
    ]);

    var chart = root.container.children.push(am5percent.PieChart.new(root, {
      innerRadius: 100,
      layout: root.verticalLayout
    }));

    var series = chart.series.push(am5percent.PieSeries.new(root, {
      valueField: "size",
      categoryField: "sector"
    }));

    series.set("colors", am5.ColorSet.new(root, {
      colors: [
        am5.color(0x0099FF33),
        am5.color(0x00FF00FF),
        am5.color(0x00666666),
        am5.color(0xF28F6B),
        am5.color(0xA95A52),
        am5.color(0xE35B5D),
        am5.color(0xFFA446)
      ]
    }))
    series.data.setAll([
      { sector: "Paga", size: 75.8, fill: am5.color(0x000000) },
      { sector: "Aguardando pagamento", size: 15.8 },
      { sector: "Inadimplente", size: 8.4 }
    ]);

    series.appear(1000, 100);

    
    
    /*series.get("colors").set("colors", [
        am5.color("#058d27"),
        am5.color("#058d27"),
        am5.color("#058d27"),
        am5.color("#058d27"),
        am5.color("#058d27")
    ]);*/

    // Add label
    var label = root.tooltipContainer.children.push(am5.Label.new(root, {
      x: am5.p50,
      y: am5.p50,
      centerX: am5.p50,
      centerY: am5.p50,
      fill: am5.color(0x000000),
      fontSize: 50
    }));


}); // end am5.ready()
</script>



@stop