<!doctype html>
<html class="no-js" lang="en">
    <head>
        <base href="{{url('/')}}"/>
        <title>{{ $title }} - {{$set->site_name}}</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="index, follow">
        <meta name="apple-mobile-web-app-title" content="{{$set->site_name}}"/>
        <meta name="application-name" content="{{$set->site_name}}"/>
        <meta name="msapplication-TileColor" content="#ffffff"/>
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{$logo->image_link2}}" />
        <link href="{{url('/')}}/asset/static/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/et-line/style.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/themify-icons/themify-icons.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/owl-carousel/css/owl.carousel.min.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/plugin/magnific/magnific-popup.css" rel="stylesheet">
        <link href="{{url('/')}}/asset/static/style/master.css" rel="stylesheet">
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">

        <style>
            .custom-spinner-loader {
                border: 16px solid #f3f3f3; /* Light grey */
                border-top: 16px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 120px;
                height: 120px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

        </style>
         @yield('css')
    </head>

    <body data-spy="scroll" data-target="#navbar-collapse-toggle" data-offset="98">
    <!-- Preload -->
    <!--
    <div id="loading">
        <div class="load-circle"><span class="one"></span></div>
    </div>
    -->
    <!-- End Preload -->
    <!-- Header -->
    <header class="header-nav header-dark">
        <div class="fixed-header-bar">
            <!-- Header Nav -->
            <div class="navbar navbar-main navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img alt="" title="" src="{{url('/')}}/asset/{{$logo->image_link}}" style="max-width: 280px;">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-collapse-overlay" id="navbar-main-collapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('about')}}">{{$lang["layout_why"]}} {{$set->site_name}}</a>
                            </li>                                                         
                            <li class="nav-item mm-in px-dropdown">
                                <a class="nav-link">{{$lang["layout_features"]}}</a>
                                <ul class="px-dropdown-menu mm-dorp-in">
                                    @if($set->transfer==1)      
                                    <li><a href="{{route('user.transfer')}}">{{$lang["layout_transfer_money"]}}</a></li>
                                    @endif
                                    @if($set->request_money==1)
                                    <li><a href="{{route('user.request')}}">{{$lang["layout_request_money"]}}</a></li>
                                    @endif
                                    @if($set->vcard==1)
                                    <li><a href="{{route('user.virtualcard')}}">{{$lang["layout_virtual_cards"]}}</a></li>
                                    @endif
                                    @if($set->bill==1) 
                                    <li><a href="{{route('user.airtime')}}">{{$lang["layout_bill_payment"]}}</a></li>
                                    @endif
                                    <li><a href="{{route('user.subaccounts')}}">{{$lang["layout_sub_accounts"]}}</a></li>
                                    @if($set->store==1) 
                                    <li><a href="{{route('user.storefront')}}">{{$lang["layout_store_front"]}}</a></li>
                                    @endif
                                    @if($set->single==1)
                                    <li><a href="{{route('user.sclinks')}}">{{$lang["layout_single_charge"]}}</a></li>
                                    @endif
                                    @if($set->donation==1) 
                                    <li><a href="{{route('user.dplinks')}}">{{$lang["layout_donations"]}}</a></li>
                                    @endif
                                    @if($set->invoice==1) 
                                    <li><a href="{{route('user.invoice')}}">{{$lang["layout_invoice"]}}</a></li>
                                    @endif
                                    @if($set->subscription==1)
                                    <li><a href="{{route('user.plan')}}">{{$lang["layout_subscription_service"]}}</a></li>
                                    @endif
                                    @if($set->merchant==1)
                                    <li><a href="{{route('user.merchant')}}">{{$lang["layout_website_integration"]}}</a></li>
                                    @endif
                                </ul>
                            </li>                            
                            <li class="nav-item mm-in px-dropdown">
                                <a class="nav-link">{{$lang["layout_help"]}}</a>
                                <ul class="px-dropdown-menu mm-dorp-in">
                                    <li><a href="{{route('faq')}}">{{$lang["layout_faqs"]}}</a></li>
                                    <li><a href="{{route('contact')}}">{{$lang["layout_contact_us"]}}</a></li>
                                </ul>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('blog')}}">{{$lang["layout_blog"]}}</a>
                            </li>                           
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Header Nav -->
        </div>
    </header>
    <!-- Header End -->
    <!-- Main -->
    <main>
@yield('content')
    <footer class="footer effect-section p-60px-t">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-15px-tb">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="p-25px-b">
                                    <img class="logo-dark nav-img" alt="" title="" src="{{url('/')}}/asset/{{$logo->image_link}}">
                                </div>
                                <p>
                                    {{$set->site_desc}}
                                </p>
                                <div class="social-icon si-30 theme round nav">
                                    @foreach($social as $socials)
                                        @if(!empty($socials->value))
                                            <a href="{{$socials->value}}" ><i class="fab fa-{{$socials->type}}"></i></a>
                                        @endif
                                    @endforeach 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 m-15px-tb">
                        <h5 class="footer-title">{{$lang["layout_our_solutions"]}}</h5>
                        <div class="row">
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @if($set->transfer==1)      
                                    <li><a href="{{route('user.transfer')}}">{{$lang["layout_transfer_money"]}}</a></li>
                                    @endif
                                    @if($set->request_money==1)
                                    <li><a href="{{route('user.request')}}">{{$lang["layout_request_money"]}}</a></li>
                                    @endif
                                    @if($set->vcard==1)
                                    <li><a href="{{route('user.virtualcard')}}">{{$lang["layout_virtual_cards"]}}</a></li>
                                    @endif
                                    @if($set->bill==1) 
                                    <li><a href="{{route('user.airtime')}}">{{$lang["layout_bill_payment"]}}</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    <li><a href="{{route('user.subaccounts')}}">{{$lang["layout_sub_accounts"]}}</a></li>
                                    @if($set->store==1) 
                                    <li><a href="{{route('user.storefront')}}">{{$lang["layout_store_front"]}}</a></li>
                                    @endif
                                    @if($set->single==1)
                                    <li><a href="{{route('user.sclinks')}}">{{$lang["layout_single_charge"]}}</a></li>
                                    @endif
                                    @if($set->donation==1) 
                                    <li><a href="{{route('user.dplinks')}}">{{$lang["layout_donations"]}}</a></li>
                                    @endif
                                </ul>
                            </div>                
                            <div class="col-lg-4 m-15px-tb">
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @if($set->invoice==1) 
                                    <li><a href="{{route('user.invoice')}}">{{$lang["layout_invoice"]}}</a></li>
                                    @endif
                                    @if($set->subscription==1)
                                    <li><a href="{{route('user.plan')}}">{{$lang["layout_subscription_service"]}}</a></li>
                                    @endif
                                    @if($set->merchant==1)
                                    <li><a href="{{route('user.merchant')}}">{{$lang["layout_website_integration"]}}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>                        
                        <div class="row">             
                            <div class="col-lg-4 m-15px-tb">
                                <h5 class="footer-title">
                                {{$lang["layout_help"]}}
                                </h5>
                                <ul class="list-unstyled links-dark footer-link-1">
                                    <li><a href="{{url('/')}}/#contact" >{{$lang["layout_contact"]}}</a></li>
                                    <li><a href="{{url('/')}}/#faq">{{$lang["layout_faqs"]}}</a></li>
                                    <li><a href="{{route('terms')}}" >{{$lang["layout_terms_of_use"]}}</a></li>
                                    <li><a href="{{route('privacy')}}" >{{$lang["layout_privacy_police"]}}</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-4 m-15px-tb">
                                <h5 class="footer-title">
                                {{$lang["layout_more"]}}
                                </h5>
                                <ul class="list-unstyled links-dark footer-link-1">
                                    @foreach($pages as $vpages)
                                        @if(!empty($vpages))
                                    <li><a href="{{url('/')}}/page/{{$vpages->id}}">{{$vpages->title}}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom footer-border-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-6  m-5px-tb">
                        <h4>{{$lang["layout_company_info"]}}</h4>
                        @if(!empty($set->company_name))
                        <p class="m-0px ">{{$set->company_name}}</p>
                        @endif
                        @if(!empty($set->company_document))
                        <p class="m-0px ">{{$set->company_document}}</p>
                        @endif
                        @if(!empty($set->company_address))
                        <p class="m-0px ">{{$set->company_address}}</p>
                        @endif
                        
                        <ul class="nav justify-content-center justify-content-md-start links-dark font-small footer-link-1">
                        </ul>
                    </div>
                    <div class="col-md-6 text-center text-md-right m-5px-tb">
                        <p class="m-0px font-small">{{$set->site_name}}  &copy; {{date('Y')}}. {{$lang["layout_all_rights_reserved"]}}.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
{!!$set->livechat!!}
        <script>
            var urx = "{{url('/')}}";
        </script>
        <script src="{{url('/')}}/asset/static/js/jquery-3.2.1.min.js"></script>
        <script src="{{url('/')}}/asset/static/js/jquery-migrate-3.0.0.min.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/appear/jquery.appear.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/bootstrap/js/popper.min.js"></script>
        <script src="{{url('/')}}/asset/static/plugin/bootstrap/js/bootstrap.js"></script>
        <script src="{{url('/')}}/asset/static/js/custom.js"></script>
        <script src="{{url('/')}}/asset/js/toast.js"></script>
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

</body>
</html>