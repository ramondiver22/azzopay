<!doctype html>
<html class="no-js" lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <base href="{{url('/')}}"/>
        <title>{{ $title }} | {{$set->site_name}}</title>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{ $logo->image_link2}}" />
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/nucleo/css/nucleo.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
		    <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/quill/dist/quill.core.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/css/argon.css?v=1.1.0" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/css/sweetalert.css" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">

        <style>
          .loader {
            border: 2px solid #f3f3f3; /* Light grey */
            border-top: 2px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            animation: spin 2s linear infinite;
          }

          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        </style>
         @yield('css')
    </head>
<!-- header begin-->
<body>
<div class=""></div>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="{{url('/')}}">
          <img src="{{url('/')}}/asset/{{ $logo->dark}}" class="navbar-brand-img" alt="...">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav mb-3">
            <li class="nav-item">
              <a class="nav-link @if(route('admin.dashboard')==url()->current()) active @endif" href="{{route('admin.dashboard')}}">
                <i class="fad fa-globe"></i>
                <span class="nav-link-text">{{$lang["master_summary"]}}</span>
              </a>
            </li>                                             
          </ul>
          <h6 class="navbar-heading p-0 text-muted">{{$lang["master_client"]}}</h6>
          <ul class="navbar-nav mb-3"> 
            @if($admin->profile==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.users')==url()->current()) active @endif" href="{{route('admin.users')}}">
                <i class="fad fa-user"></i>
                <span class="nav-link-text">{{$lang["master_customers"]}}</span>
              </a>
            </li> 
            @endif         
            @if($admin->id==1)    
            <li class="nav-item">
              <a class="nav-link @if(route('admin.staffs')==url()->current()) active @endif" href="{{route('admin.staffs')}}">
                <i class="fad fa-user"></i>
                <span class="nav-link-text">{{$lang["master_staffs"]}}</span>
              </a>
            </li> 
            @endif     
            @if($admin->promo==1)                  
            <li class="nav-item">
              <a class="nav-link @if(route('admin.promo')==url()->current()) active @endif" href="{{route('admin.promo')}}">
                <i class="fad fa-envelope"></i>
                <span class="nav-link-text">{{$lang["master_promotional_emails"]}}</span>
              </a>
            </li>   
            @endif
            @if($admin->support==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.ticket')==url()->current()) active @endif" href="{{route('admin.ticket')}}">
                <i class="fad fa-ticket"></i>
                <span class="nav-link-text">{{$lang["master_spport_ticket"]}}
                  @if(count($pticket)>0)
                   <span class="badge badge-sm badge-circle badge-floating badge-danger border-white">{{count($pticket)}}</span>
                  @endif
                </span>
              </a>
            </li>  
            @endif
            @if($admin->message==1)        
            <li class="nav-item">
              <a class="nav-link @if(route('admin.message')==url()->current()) active @endif" href="{{route('admin.message')}}">
                <i class="fad fa-sticky-note"></i>
                <span class="nav-link-text">{{$lang["master_messages"]}}</span>
              </a>
            </li>  
            @endif
            @if($admin->deposit==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.banktransfer')==url()->current() || route('admin.deposit.log')==url()->current()) active @endif" href="#card" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                <i class="fad fa-money-bill-wave"></i>
                <span class="nav-link-text">{{$lang["master_deposit"]}}
                  @if(count($pdeposit)>0 || count($pbank)>0)
                  <span class="badge badge-sm badge-circle badge-floating badge-danger border-white">{{count($pdeposit) + count($pbank)}}</span>
                  @endif
                </span>
              </a>
              <div class="collapse @if(route('admin.banktransfer')==url()->current() || route('admin.deposit.log')==url()->current()) show @endif" id="card">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item @if(route('admin.banktransfer')==url()->current()) active @endif">
                    <a href="{{route('admin.banktransfer')}}" class="nav-link">{{$lang["master_bank_transfer_logs"]}}
                      @if(count($pbank)>0)
                        <span class="badge badge-sm badge-circle badge-floating badge-danger border-white">
                        {{count($pbank)}}
                        </span>
                      @endif
                    </a>
                  </li>
                  <li class="nav-item @if(route('admin.deposit.log')==url()->current()) active @endif">
                    <a href="{{route('admin.deposit.log')}}" class="nav-link">{{$lang["master_deposit_log"]}}
                      @if(count($pdeposit)>0)
                      <span class="badge badge-sm badge-circle badge-floating badge-danger border-white">
                      {{count($pdeposit)}}
                      </span>
                      @endif
                      </a>
                  </li>                          
                </ul>
              </div>
            </li> 
            @endif

            <li class="nav-item">
              <a class="nav-link @if(route('admin.deposit.method')==url()->current()) active @endif" href="{{route('admin.deposit.method')}}">
                <i class="fad fa-badge-percent"></i>
                <span class="nav-link-text">{{$lang["master_payment_gateway"]}}</span>
              </a>
            </li>

            <?php /*
            @if($admin->bill==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.bpay')==url()->current()) active @endif" href="{{route('admin.bpay')}}">
                <i class="fad fa-building"></i>
                <span class="nav-link-text">{{$lang["master_bill_payment"]}}</span>
              </a>
            </li> 
            @endif 

            @if($admin->vcard==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.vcard')==url()->current()) active @endif" href="{{route('admin.vcard')}}">
                <i class="fad fa-credit-card"></i>
                <span class="nav-link-text">{{$lang["master_virtual_cards"]}}</span>
              </a>
            </li>
            @endif 
            */ ?>

            <!--
              @if($admin->crypto==1) 
              <li class="nav-item">
                <a class="nav-link @if(route('admin.trades')==url()->current()) active @endif" href="{{route('admin.trades')}}">
                  <i class="fad fa-sync"></i>
                  <span class="nav-link-text">{{__('Cryptocurrency')}}</span>
                </a>
              </li>  
              @endif 
            -->          
            @if($admin->store==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.product')==url()->current()) active @endif" href="{{route('admin.product')}}">
                <i class="fad fa-shopping-bag"></i>
                <span class="nav-link-text">{{$lang["master_products"]}}</span>
              </a>
            </li> 
            @endif
            @if($admin->single_charge==1 || $admin->donation==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.sclinks')==url()->current() || route('admin.dplinks')==url()->current()) active @endif" href="#navbar-examples3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples3">
                <!--For modern browsers-->
                <i class="fad fa-tags"></i>
                <span class="nav-link-text">{{$lang["master_payment_links"]}}</span>
              </a>
              <div class="collapse @if(route('admin.sclinks')==url()->current() || route('admin.dplinks')==url()->current()) show @endif" id="navbar-examples3">
                <ul class="nav nav-sm flex-column">
                  @if($admin->single_charge==1)
                  <li class="nav-item @if(route('admin.sclinks')==url()->current()) active @endif text-default">
                    <a href="{{route('admin.sclinks')}}" class="nav-link">{{$lang["master_single_charge"]}}</a>
                  </li>  
                  @endif
                  @if($admin->donation==1)                               
                  <li class="nav-item @if(route('user.dplinks')==url()->current()) active @endif text-default">
                    <a href="{{route('admin.dplinks')}}" class="nav-link">{{$lang["master_donation"]}}</a>
                  </li>       
                  @endif                        
                </ul>
              </div>
            </li> 
            @endif
            @if($admin->transfer==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.ownbank')==url()->current()) active @endif" href="{{route('admin.ownbank')}}">
                <i class="fad fa-random"></i>
                <span class="nav-link-text">{{$lang["master_transfers"]}}</span>
              </a>
            </li>
            @endif
            @if($admin->request_money==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.request')==url()->current()) active @endif" href="{{route('admin.request')}}">
                <i class="fad fa-handshake"></i>
                <span class="nav-link-text">{{$lang["master_request_money"]}}</span>
              </a>
            </li> 
            @endif
            @if($admin->invoice==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.invoice')==url()->current()) active @endif" href="{{route('admin.invoice')}}">
                <i class="fad fa-envelope"></i>
                <span class="nav-link-text">{{$lang["master_invoice"]}}</span>
              </a>
            </li> 
            @endif

            <?php /* 
            @if($admin->merchant==1)
            <li class="nav-item">
              <a class="nav-link @if(route('merchant.log')==url()->current()) active @endif" href="{{route('merchant.log')}}">
                <i class="fad fa-laptop"></i>
                <span class="nav-link-text">{{$lang["master_website_integration"]}}</span>
              </a>
            </li>  
            @endif
            @if($admin->subscription==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.plan')==url()->current()) active @endif" href="{{route('admin.plan')}}">
                <i class="fad fa-layer-group"></i>
                <span class="nav-link-text">{{$lang["master_payment_plans"]}}</span>
              </a>
            </li> 
            @endif
            */ ?>
            
            @if($admin->settlement==1)
            <li class="nav-item">
              <a href="{{route('admin.withdraw.log')}}" class="nav-link @if(route('admin.withdraw.log')==url()->current()) active @endif">
                <i class="fad fa-arrow-circle-down"></i>
                <span class="nav-link-text mr-1">{{$lang["master_payout"]}}</span>
                @if(count($pwithdraw)>0)
                  <span class="badge badge-sm badge-circle badge-floating badge-danger border-white">
                    {{count($pwithdraw)}}
                  </span>
                @endif
              </a>
            </li>
            @endif


            @if($admin->charges==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.charges')==url()->current()) active @endif" href="{{route('admin.charges')}}">
                <i class="fad fa-comment-dollar"></i>
                <span class="nav-link-text">{{$lang["master_charges"]}}</span>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link @if(route('admin.country')==url()->current()) active @endif" href="{{route('admin.country')}}">
                <i class="fad fa-globe"></i>
                <span class="nav-link-text">{{$lang["master_country_supported"]}}</span>
              </a>
            </li>           
            <li class="nav-item">
              <a class="nav-link @if(route('admin.currency')==url()->current()) active @endif" href="{{route('admin.currency')}}">
                <i class="fad fa-money-bill-wave-alt"></i>
                <span class="nav-link-text">{{$lang["master_currency"]}}</span>
              </a>
            </li>	            
            <li class="nav-item">
              <a class="nav-link @if(route('admin.lbank')==url()->current()) active @endif" href="{{route('admin.lbank')}}">
                <i class="fad fa-university"></i>
                <span class="nav-link-text">{{$lang["master_bank_supported"]}}</span>
              </a>
            </li>					     
          </ul>

          <h6 class="navbar-heading p-0 text-muted">Gerenciamento Financeiro</h6>
          <ul class="navbar-nav mb-3">   
              <li class="nav-item">
                <a class="nav-link @if(route('charges.management')==url()->current()) active @endif" href="{{route('charges.management')}}">
                  <i class="fad fa-comment-dollar"></i>
                  <span class="nav-link-text">Taxas</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if(route('account.flow')==url()->current()) active @endif" href="{{route('account.flow')}}">
                  <i class="fad fa-comment-dollar"></i>
                  <span class="nav-link-text">Fluxo de Caixa</span>
                </a>
              </li>
          </ul>



          <h6 class="navbar-heading p-0 text-muted">{{$lang["master_more"]}}</h6>
          <ul class="navbar-nav mb-3"> 
            @if($admin->blog==1)
            <li class="nav-item">
              <a class="nav-link @if(route('admin.blog')==url()->current() || route('admin.cat')==url()->current()) show @endif" href="#brcard" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                <i class="fad fa-newspaper"></i>
                  <span class="nav-link-text">{{$lang["master_blog"]}}</span>
              </a>
              <div class="collapse @if(route('admin.blog')==url()->current() || route('admin.cat')==url()->current()) show @endif" id="brcard">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item @if(route('admin.blog')==url()->current()) active @endif"><a href="{{route('admin.blog')}}" class="nav-link">{{$lang["master_articles"]}}</a></li>
                  <li class="nav-item @if(route('admin.cat')==url()->current()) active @endif"><a href="{{route('admin.cat')}}" class="nav-link">{{$lang["master_category"]}}</a></li>
                </ul>
              </div>
            </li>
            @endif 
            @if($admin->id==1)
            <li class="nav-item">
              <a class="nav-link  @if(route('homepage')==url()->current() || route('admin.service')==url()->current() || route('admin.brand')==url()->current() || route('admin.logo')==url()->current() || route('admin.review')==url()->current() || route('admin.page')==url()->current() || route('admin.faq')==url()->current() || route('admin.terms')==url()->current() || route('privacy-policy')==url()->current() || route('about-us')==url()->current() || route('social-links')==url()->current()) active @endif" href="#xx" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                <i class="fad fa-desktop"></i>
                <span class="nav-link-text">{{$lang["master_website_design"]}}</span>
              </a>
              <div class="collapse @if(route('homepage')==url()->current() || route('admin.service')==url()->current() || route('admin.brand')==url()->current() || route('admin.logo')==url()->current() || route('admin.review')==url()->current() || route('admin.page')==url()->current() || route('admin.faq')==url()->current() || route('admin.terms')==url()->current() || route('privacy-policy')==url()->current() || route('about-us')==url()->current() || route('social-links')==url()->current()) show @endif " id="xx">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item @if(route('homepage')==url()->current()) active @endif"><a href="{{route('homepage')}}" class="nav-link">{{$lang["master_homepage"]}}</a></li>
                    <li class="nav-item @if(route('admin.brand')==url()->current()) active @endif"><a href="{{route('admin.brand')}}" class="nav-link">{{$lang["master_brands"]}}</a></li>	
                    <li class="nav-item @if(route('admin.logo')==url()->current()) active @endif"><a href="{{route('admin.logo')}}" class="nav-link">{{$lang["master_logo_favicon"]}}</a></li>	
                    <li class="nav-item @if(route('admin.review')==url()->current()) active @endif"><a href="{{route('admin.review')}}"class="nav-link">{{$lang["master_platform_review"]}}</a></li>
					          <li class="nav-item @if(route('admin.service')==url()->current()) active @endif"><a href="{{route('admin.service')}}"class="nav-link">{{$lang["master_services"]}}</a></li>
                    <li class="nav-item @if(route('admin.page')==url()->current()) active @endif"><a href="{{route('admin.page')}}" class="nav-link">{{$lang["master_webpages"]}}</a></li>
                    <li class="nav-item @if(route('admin.faq')==url()->current()) active @endif"><a href="{{route('admin.faq')}}" class="nav-link">{{$lang["master_faqs"]}}</a></li>
                    <li class="nav-item @if(route('admin.terms')==url()->current()) active @endif"><a href="{{route('admin.terms')}}" class="nav-link">{{$lang["master_terms_conditions"]}}</a></li>
                    <li class="nav-item @if(route('privacy-policy')==url()->current()) active @endif"><a href="{{route('privacy-policy')}}" class="nav-link">{{$lang["master_privacy_policy"]}}</a></li>
                    <li class="nav-item @if(route('about-us')==url()->current()) active @endif"><a href="{{route('about-us')}}" class="nav-link">{{$lang["master_abount_us"]}}</a></li>
                    <li class="nav-item @if(route('social-links')==url()->current()) active @endif"><a href="{{route('social-links')}}" class="nav-link">{{$lang["master_social_links"]}}</a></li>                           
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link @if(route('admin.setting')==url()->current()) active @endif" href="{{route('admin.setting')}}">
                <i class="fad fa-cogs"></i>
                <span class="nav-link-text">{{$lang["master_settings"]}}</span>
              </a>
            </li> 
            @endif           
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Search form -->
            
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item">
              <a class="nav-link pr-0" href="javascript:void" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{url('/')}}/asset/profile/person.png">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                      <span class="mb-0 text-sm text-default">{{Auth::guard('admin')->user()->username}}</span>
                    </div>
                </div>
              </a>
            </li> 
            <li class="nav-item">
              <a href="{{route('admin.logout')}}" class="nav-link pr-0">
                <i class="fad fa-sign-out text-dark"></i>
              </a>
            </li>             
          </ul>
        </div>
      </div>
    </nav>
    <div class="header pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">

              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

@yield('content')
  </div>
</div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery/dist/jquery.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/js-cookie/js.cookie.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Optional JS -->
  <script src="{{url('/')}}/asset/dashboard/vendor/chart.js/dist/Chart.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/chart.js/dist/Chart.extension.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/jvectormap-next/jquery-jvectormap.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/js/vendor/jvectormap/jquery-jvectormap-world-mill.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/clipboard/dist/clipboard.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/select2/dist/js/select2.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/nouislider/distribute/nouislider.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/quill/dist/quill.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/dropzone/dist/min/dropzone.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/jszip.min.js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/pdfmake.min..js"></script>
  <script src="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons/js/vfs_fonts.js"></script>
  <!-- Argon JS -->
  <script src="{{url('/')}}/asset/dashboard/js/argon.js?v=1.1.0"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{url('/')}}/asset/dashboard/js/demo.min.js"></script>
  <script src="{{url('/')}}/asset/js/toast.js"></script>
  <script src="{{url('/')}}/asset/tinymce/tinymce.min.js"></script>
	<script src="{{url('/')}}/asset/tinymce/init-tinymce.js"></script>


  <script src="{{url('/')}}/asset/js/priceformat/jquery.price_format.js"></script>
  <script src="{{url('/')}}/asset/js/jquery.maskedinput.min.js"></script>
  <script src="{{url('/')}}/asset/js/jquery.inputmask.js"></script>
  <script src="{{url('/')}}/asset/js/jquery.alphanumeric.js"></script>
  <script src="{{url('/')}}/asset/js/ajaxform.js"></script>
  


  @stack('scripts')
</body>

</html>
@yield('script')
@if (session('success'))
    <script>
      "use strict";
      toastr.success("{{ session('success') }}");
    </script>    
@endif

@if (session('alert'))
    <script>
      "use strict";
      toastr.warning("{{ session('alert') }}");
    </script>
@endif
<script type="text/javascript">
"use strict";
  function exchange(){
      try {
          var percent = $("#percent").val();
          var duration = $("#duration").val();
          var period = $("#period").find(":selected").text();
          var myarr1 = period.split("-");
          var dar1 = myarr1[1].split("<");	
          var compound = parseFloat(percent)*parseFloat(duration)*parseInt(dar1);
          var interest = compound-100;
          $("#compound").val(compound);
          $("#interest").val(interest);
      } catch (e) {}
  }

  $("#percent").change(exchange);
  exchange();
  $("#duration").change(exchange);
  exchange();
  $("#period").change(exchange);
  exchange();



  $(document).ready(function () {
				$(".phone-mask").inputmask({
					mask: ["(99) 9999 - 9999", "(99) 99999 - 9999"],
					keepStatic: true
				});

				$(".mobilephone-mask").inputmask({
					mask: ["(99) 9999 - 9999", "(99) 99999 - 9999"],
					keepStatic: true
				});

				$(".date-mask").inputmask({
					mask: ["99/99/9999"],
					keepStatic: true
				});

				$(".cpf-mask").inputmask({
					mask: ["999.999.999-99"],
					keepStatic: true
				});
				$(".cnpj-mask").inputmask({
					mask: ["99.999.999/9999-99"],
					keepStatic: true
				});
				$(".cep-mask").inputmask({
					mask: ["99999-999"],
					keepStatic: true
				});
				
				$('.money-mask').priceFormat({
					prefix: '',
					centsSeparator: ',',
					thousandsSeparator: '.'
				});


				$('.currency-mask').priceFormat({
					prefix: '',
					centsSeparator: ',',
					thousandsSeparator: '.',
					centsLimit: 6
				});

        $(".numeric-input").numeric();
		});
</script> 
