@extends('userlayout')

@section('content')

<style>
/* Estilos para um visual mais moderno e clean */

/* Variáveis de cores para consistência */
:root {
    --primary-color: #007bff; /* Azul primário */
    --success-color: #28a745; /* Verde sucesso */
    --warning-color: #ffc107; /* Amarelo aviso */
    --danger-color: #dc3545; /* Vermelho perigo */
    --info-color: #17a2b8; /* Ciano informação */
    --card-bg: #ffffff; /* Fundo do card */
    --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Sombra suave */
}

/* Estilo geral do container */
.container-fluid {
    padding-top: 2rem;
}

/* Estilo para os cards de métricas (KPIs) */
.kpi-card {
    border: none;
    border-radius: 0.5rem;
    box-shadow: var(--card-shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.kpi-card .card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.kpi-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #6c757d; /* Texto cinza suave */
    margin-bottom: 0.5rem;
}

.kpi-card .kpi-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
    color: #343a40; /* Texto escuro */
}

.kpi-card .kpi-icon {
    font-size: 2.5rem;
    opacity: 0.2;
    position: absolute;
    top: 50%;
    right: 1.5rem;
    transform: translateY(-50%);
}

.kpi-card.bg-primary { background-color: #e9f5ff !important; }
.kpi-card.bg-success { background-color: #e6fff0 !important; }
.kpi-card.bg-warning { background-color: #fffbe6 !important; }

/* Estilo para a seção de métodos de pagamento e recusa (listas) */
.data-list .list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.25rem;
    border: none;
    border-bottom: 1px solid #f8f9fa;
}

.data-list .list-group-item:last-child {
    border-bottom: none;
}

.data-list .list-group-item svg {
    margin-right: 10px;
}

/* Estilo para o gráfico */
#chart-payment-div {
    width: 100%;
    height: 300px; /* Aumentar a altura para melhor visualização */
    margin: 0 auto;
}

/* Ajustes para a tabela de Lançamentos Futuros */
.table-responsive {
    border-radius: 0.5rem;
    overflow: hidden;
}

.table-flush th {
    background-color: #f8f9fa;
    font-weight: 600;
}

/* Ajuste para ícones Font Awesome (assumindo que o FAD seja um ícone personalizado ou uma classe existente) */
.fad {
    margin-right: 0.5rem;
}

</style>

<div class="container-fluid mt--6">
    <div class="content-wrapper">
      
        <!-- Seção de Cards de Métricas (KPIs) -->
        <div class="row">
            
            <!-- Card de Transações -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card kpi-card bg-primary">
                    <div class="card-body position-relative">
                        <h4 class="card-title">{{$lang["home_trasactions_number"]}}</h4>
                        <p class="kpi-value">{{$n_transactions}}</p>
                        <i class="fad fa-exchange-alt kpi-icon text-primary"></i>
                    </div>
                </div>
            </div>
            
            <!-- Card de Ticket Médio -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card kpi-card bg-success">
                    <div class="card-body position-relative">
                        <h4 class="card-title">{{$lang["home_avg_ticket"]}}</h4>
                        <p class="kpi-value">R$ {{number_format($n_avgticket, 2, ",", ".")}}</p>
                        <i class="fad fa-money-check-alt kpi-icon text-success"></i>
                    </div>
                </div>
            </div>
            
            <!-- Card de Volume de Transações -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card kpi-card bg-warning">
                    <div class="card-body position-relative">
                        <h4 class="card-title">{{$lang["home_transactions_volume"]}}</h4>
                        <p class="kpi-value">R$ {{number_format($n_transactions_total, 2, ",", ".")}}</p>
                        <i class="fad fa-chart-line kpi-icon text-warning"></i>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">

            <!-- Coluna Principal (8/12) - Gráfico de Histórico e Lançamentos Futuros -->
            <div class="col-lg-8">
                
                <!-- Gráfico de Histórico de Ganhos -->
                <div class="card kpi-card">
                    <div class="card-body">
                        <h4 class="font-weight-bolder text-gray"><i class="fad fa-undo-alt"></i> {{$lang["earning_log"]}}</h4>
                        @if(count($history)>0)
                        <canvas id="myChart" width="100%" height="40%"></canvas>
                        @else
                            <div class="text-center mb-5 mt-8">
                                <div class="mb-3">
                                    <img src="{{url('/')}}/asset/images/empty.svg" style="max-width: 200px;">
                                </div>
                                <h3 class="text-dark">{{$lang["no_earning_history"]}}</h3>
                                <p class="text-dark text-sm card-text">{{$lang["we_couldnt_find_any_log_to_this_account"]}}</p>
                            </div>
                        @endif
                    </div>     
                </div>     
                
                <!-- Tabela de Lançamentos Futuros -->
                <div class="card kpi-card mt-4">
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
                                        <td class="text-center">{{date("d/m/Y H:i:s", strtotime($pendingBalanceReg->created_at))}}</td>
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

            <!-- Coluna Lateral (4/12) - Status, Saldos e API -->
            <div class="col-lg-4"> 
                
                <!-- Card de Transações por Status (Gráfico de Pizza) -->
                <div class="card kpi-card">
                    <div class="card-body">
                        <h4 class="card-title">{{$lang["home_transactions_by_status"]}}</h4>
                        <div class="row">
                            <div class="col col-xs-12 text-center">
                                <div id="chart-payment-div">
                                    <!-- Gráfico será renderizado aqui -->
                                </div>
                            </div>
                        </div>
                        
                        <ul class="list-group list-group-flush data-list">
                            <li class="list-group-item">
                                <small class="text-success font-weight-bold">{{$lang["home_paid"]}}</small>
                                <small class="text-success font-weight-bold">{{$n_paid}}</small>
                            </li>
                            <li class="list-group-item">
                                <small class="text-warning font-weight-bold">{{$lang["home_waiting_payment"]}}</small>
                                <small class="text-warning font-weight-bold">{{$n_pending}}</small>
                            </li>
                            <li class="list-group-item">
                                <small class="text-danger font-weight-bold">{{$lang["home_defaulter"]}}</small>
                                <small class="text-danger font-weight-bold">{{$n_defaulter}}</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Cards de Saldos e Payouts -->
                <div class="card kpi-card mt-4">
                    <div class="card-body">
                        
                        <div class="text-center mb-4">   
                            <h5 class="text-gray mb-2 h4"><i class="fad fa-lock"></i> Saldo Bloqueado</h5>
                            <h5 class="mb-1 h2 text-danger">{{$currency->name}} {{number_format($pendingBalance, 2, ',', '.')}}</h5>
                        </div> 
                        <hr>
                        <div class="text-center mb-4">   
                            <h5 class="text-gray mb-2 h4"><i class="fad fa-sack-dollar"></i> {{$lang["revenue"]}}</h5>
                            <h5 class="mb-1 h2 text-success">{{$currency->name}} {{number_format($revenue, 2, ',', '.')}}</h5>
                        </div> 
                        <hr>
                        <div class="text-center mb-4">   
                            <h5 class="text-gray mb-2 h4"><i class="fad fa-cart-plus"></i> {{$lang["total_payout"]}}</h5>
                            <h5 class="mb-1 h2 text-info">{{$currency->name}} {{number_format($t_payout, 2, ',', '.')}}</h5>
                            @if($user->business_level==1)
                                <p class="text-gray mb-3">{{number_format($t_payout/$set->withdraw_limit*100, 2, ',', '.')}}% {{$lang["of_limit"]}}</p>
                                @if($user->kyc_status==0)
                                    <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i> {{$lang["upgrade_account"]}}</a> 
                                @endif
                            @elseif($user->business_level==2)
                                <p class="text-gray mb-3">{{number_format($t_payout/$set->starter_limit*100, 2, ',', '.')}}% {{$lang["of_limit"]}}</p>
                                @if($user->kyc_status==0)
                                    <a href="{{route('user.compliance')}}" class="btn btn-sm btn-neutral"><i class="fad fa-arrow-up"></i> {{$lang["upgrade_account"]}}</a> 
                                @endif
                            @elseif($user->business_level==3)
                                <p class="text-gray mb-3">{{$lang["no_limit"]}}</p>
                            @endif
                        </div>          
                        <hr>
                        <div class="text-center">   
                            <h5 class="text-gray mb-2 h4"><i class="fad fa-calendar"></i> {{$lang["next_payout"]}}</h5>
                            <h5 class="mb-2 h2 text-primary">{{$currency->name}} {{number_format($n_payout, 2, ',', '.')}}</h5>
                            <p class="text-gray mb-3">{{$lang["due"]}} {{date("Y/m/d", strtotime($set->next_settlement))}}</p>
                            <a href="{{route('user.withdraw')}}" class="btn btn-sm btn-neutral"><i class="fad fa-history"></i> {{$lang["past_payouts"]}}</a> 
                        </div>
                    </div>
                </div>

                <!-- Card de Métodos de Pagamento -->
                <div class="card kpi-card mt-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="fad fa-credit-card"></i> {{$lang["home_payment_methods"]}}</h4>
                        <ul class="list-group list-group-flush data-list">
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-qrcode text-primary"></i> {{$lang["home_pix"]}}</span>
                                <span class="font-weight-bold text-primary">R$ {{number_format($n_transactions_pix, 2, ",", ".")}}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-credit-card text-success"></i> {{$lang["home_credit_card"]}}</span>
                                <span class="font-weight-bold text-success">R$ {{number_format($n_transactions_creditcard, 2, ",", ".")}}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-barcode text-warning"></i> {{$lang["home_boleto"]}}</span>
                                <span class="font-weight-bold text-warning">R$ {{number_format($n_transactions_boleto, 2, ",", ".")}}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card de Marcas de Pagamento (Simplificado) -->
                <div class="card kpi-card mt-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="fad fa-tags"></i> {{$lang["home_payment_brands"]}}</h4>
                        <ul class="list-group list-group-flush data-list">
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fab fa-cc-mastercard text-danger"></i> {{$lang["home_mastercard"]}}</span>
                                <span class="font-weight-bold text-danger">R$ {{number_format($n_transactions_mastercard, 2, ",", ".")}}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fab fa-cc-visa text-info"></i> {{$lang["home_visa"]}}</span>
                                <span class="font-weight-bold text-info">R$ {{number_format($n_transactions_visa, 2, ",", ".")}}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-credit-card-front text-secondary"></i> {{$lang["home_elo"]}}</span>
                                <span class="font-weight-bold text-secondary">R$ {{number_format($n_transactions_elo, 2, ",", ".")}}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card de Motivos de Recusa -->
                <div class="card kpi-card mt-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="fad fa-times-circle"></i> {{$lang["home_reason_for_refusal"]}}</h4>
                        <ul class="list-group list-group-flush data-list">
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-exclamation-triangle text-warning"></i> {{$lang["home_no_acquirer_configured"]}}</span>
                                <span class="font-weight-bold text-warning">0</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-university text-danger"></i> {{$lang["home_issuer"]}}</span>
                                <span class="font-weight-bold text-danger">0</span>
                            </li>
                            <li class="list-group-item">
                                <span class="font-weight-bold"><i class="fad fa-shield-alt text-danger"></i> {{$lang["home_denied_by_anti_fraud"]}}</span>
                                <span class="font-weight-bold text-danger">0</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card de Documentação API -->
                <div class="card kpi-card mt-4">
                    <div class="card-body">
                        <h4 class="font-weight-bolder mb-3"><i class="fad fa-file-alt"></i> {{$lang["api_documentation"]}}</h4>
                        <p class="text-gray mb-3">{{$lang["our_documentation_need_to_integrate"]}} {{$set->site_name}} {{$lang["in_your_website"]}}</p>
                        
                        <a href="https://documenter.getpostman.com/view/2065421/UzXLyHss" target="DOCS" class="btn btn-sm btn-primary btn-block mb-4"><i class="fad fa-file-alt"></i> {{$lang["go_to_docs"]}}</a> 
                        
                        <h4 class="mb-2 font-weight-bolder">{{$lang["your_keys"]}}</h4>
                        <div class="mb-3">
                            <span class="text-gray mb-3">{{$lang["also_available_in"]}}</span> <a href="{{route('user.api')}}">{{$lang["settings_api_keys"]}}</a>
                        </div>
                        
                        <!-- Campos de Chaves API -->
                        <div class="form-group">
                            <label class="form-control-label text-xs text-uppercase">{{$lang["public_key"]}}</label>
                            <div class="input-group">
                                <input type="text" name="public_key" class="form-control" placeholder="{{$lang["public_key"]}}" value="{{$user->public_key}}">   
                                <div class="input-group-append">
                                    <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->public_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                                </div> 
                            </div>
                        </div>                
                        <div class="form-group">
                            <label class="form-control-label text-xs text-uppercase">{{$lang["secret_key"]}}</label>
                            <div class="input-group">
                                <input type="text" name="secret_key" class="form-control" placeholder="{{$lang["secret_key"]}}" value="{{$user->secret_key}}">   
                                <div class="input-group-append">
                                    <span class="input-group-text btn-icon-clipboard" data-clipboard-text="{{$user->secret_key}}" title="Copy"><i class="fad fa-clipboard"></i></span>
                                </div> 
                            </div> 
                        </div>              
                        <div class="form-group">
                            <label class="form-control-label text-xs text-uppercase">Basic</label>
                            <div class="input-group">
                                <input type="text" name="secret_key" class="form-control" placeholder="Basic" value="{{base64_encode($user->public_key. ":" .$user->secret_key)}}">   
                                <div class="input-group-append">
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
        
<script src="{{url('/')}}/asset/js/amcharts/index.js"></script>
<script src="{{url('/')}}/asset/js/amcharts/xy.js"></script>
<script src="{{url('/')}}/asset/js/amcharts/Animated.js"></script>
<script src="{{url('/')}}/asset/js/amcharts/pt_BR.js"></script>
<script src="{{url('/')}}/asset/js/amcharts/percent.js"></script>

<script>
am5.ready(function() {

    var root = am5.Root.new("chart-payment-div");

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

    // Cores mais modernas para o gráfico de pizza
    series.set("colors", am5.ColorSet.new(root, {
      colors: [
        am5.color(0x28a745), // Verde para Paga
        am5.color(0xffc107), // Amarelo para Aguardando
        am5.color(0xdc3545), // Vermelho para Inadimplente
      ]
    }))
    
    // Dados do gráfico (mantidos como estavam)
    series.data.setAll([
      { sector: "Paga", size: 75.8, fill: am5.color(0x28a745) },
      { sector: "Aguardando pagamento", size: 15.8 },
      { sector: "Inadimplente", size: 8.4 }
    ]);

    series.appear(1000, 100);

    // Ajuste do label central para melhor contraste
    var label = root.tooltipContainer.children.push(am5.Label.new(root, {
      x: am5.p50,
      y: am5.p50,
      centerX: am5.p50,
      centerY: am5.p50,
      fill: am5.color(0x343a40), // Cor escura para o texto
      fontSize: 50
    }));


}); // end am5.ready()
</script>


@stop
