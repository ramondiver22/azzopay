@extends('userlayout')

@section('content')
<div class="container-fluid mt--6">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.storefront')==url()->current()) active @endif" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fad fa-store-alt"></i> {{$lang["product_storefront_store_front"]}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.shipping')==url()->current()) active @endif" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="fadse"><i class="fad fa-street-view"></i> {{$lang["product_storefront_shipping_regions_rate"]}}</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.product')==url()->current()) active @endif" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="fadse"><i class="fad fa-shopping-bag"></i> {{$lang["product_storefront_products"]}}</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.list')==url()->current()) active @endif" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="fadse"><i class="fad fa-shopping-cart"></i> {{$lang["product_storefront_client_orders"]}}</a>
                        </li>                         
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 @if(route('user.your-list')==url()->current()) active @endif" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="fadse"><i class="fad fa-shopping-cart"></i> {{$lang["product_storefront_your_orders"]}}</a>
                        </li>   
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if(route('user.storefront')==url()->current())show active @endif" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-5 text-left">
                        <a data-toggle="modal" data-target="#new-store" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["product_storefront_new_store_front"]}}</a>                 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">  
                            @if(count($store)>0)
                                @foreach($store as $k=>$val)
                                    <div class="col-md-4">
                                        <div class="card bg-white">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="row mb-2">
                                            <div class="col-4">
                                                <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('store.link', ['id' => $val->store_url])}}" title="{{$lang["product_storefront_copy"]}}">{{$lang["product_storefront_copy_link"]}} <i class="fad fa-link text-xs"></i></a></p>
                                            </div>  
                                            <div class="col-8 text-right">
                                                <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                                <i class="fad fa-chevron-circle-down"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <a href="{{route('storefront.products', ['id' => $val->id])}}" class="dropdown-item"><i class="fad fa-shopping-bag"></i> {{$lang["product_storefront_products"]}}</a>
                                                    <a href="{{route('store.your-list', ['id' => $val->id])}}" class="dropdown-item"><i class="fad fa-shopping-cart"></i> {{$lang["product_storefront_orders"]}}</a>
                                                    <a data-toggle="modal" data-target="#edit{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{$lang["product_storefront_edit"]}}</a>
                                                    @if($val->status==1)
                                                        <a class='dropdown-item' href="{{route('store.unpublish', ['id' => $val->id])}}"><i class="fad fa-ban"></i>{{$lang["product_storefront_disabled"]}}</a>
                                                    @else
                                                        <a class='dropdown-item' href="{{route('store.publish', ['id' => $val->id])}}"><i class="fad fa-check"></i>{{$lang["product_storefront_activate"]}}</a>
                                                    @endif
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["product_storefront_delete"]}}</a>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="row">
                                            <div class="col">
                                                <h5 class="h4 mb-1 font-weight-bolder">{{$val->store_name}}</h5>
                                                <p>{{$lang["product_storefront_category"]}}: {{$val->category}}</p>
                                                <p>{{$lang["product_storefront_revenue"]}}: {{$currency->name.' '.$val->revenue}}</p>
                                                <p class="text-sm mb-2">{{$lang["product_storefront_date"]}}: {{date("h:i:A j, M Y", strtotime($val->created_at))}}</p>
                                                @if($val->status==1)
                                                    <span class="badge badge-pill badge-success"><i class="fad fa-check"></i> {{$lang["product_storefront_active"]}}</span>
                                                @else
                                                    <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["product_storefront_disabled"]}}</span>
                                                @endif
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
                                        <h3 class="text-dark">{{$lang["product_storefront_no_storefront_found"]}}</h3>
                                        <p class="text-dark text-sm card-text">{{$lang["product_storefront_we_couldnt_find_any_tore_front"]}}</p>
                                    </div>
                                </div>
                            @endif
                        </div> 
                        <div class="row">
                        <div class="col-md-12">
                        {{ $store->links('pagination::bootstrap-4') }}
                        </div>
                        </div>
                    </div> 
                </div>
                @foreach($store as $k=>$val)
                    <div class="modal fade" id="delete{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["product_storefront_delete_store_front"]}}</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span class="mb-0 text-xs">{{$lang["product_storefront_are_you_sure_you_want_to_delete"]}}</span>
                                        </div>
                                        <div class="card-body">
                                            <a  href="{{route('delete.storefront', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["product_storefront_proceed"]}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="edit{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_edit_store"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('edit.store')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_store_name"]}}</label>
                                    <div class="col-lg-12">
                                        <input type="text" name="store_name" class="form-control" value="{{$val->store_name}}" placeholder="{{$lang["product_storefront_the_name_of_your_store"]}}" required>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$val->id}}" name="id">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_store_description"]}}</label>
                                    <div class="col-lg-12">
                                        <textarea type="text" name="store_desc" class="form-control" required>{{$val->store_desc}}</textarea>
                                    </div>
                                </div>   
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_category"]}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="category" required>
                                            <option @if($val->category=="Animals & Pets")selected @endif>{{$lang["product_storefront_animals_pet"]}}</option>
                                            <option @if($val->category=="Arts and Crafts")selected @endif>{{$lang["product_storefront_arts_and_crafts"]}}</option>
                                            <option @if($val->category=="Baby Products")selected @endif>{{$lang["product_storefront_baby_produts"]}}</option>
                                            <option @if($val->category=="Beauty and Skincare")selected @endif>{{$lang["product_storefront_beauty_and_skincare"]}}</option>
                                            <option @if($val->category=="Books and Media")selected @endif>{{$lang["product_storefront_books_and_media"]}}</option>
                                            <option @if($val->category=="Building and Construction")selected @endif>{{$lang["product_storefront_building_and_construction"]}}</option>
                                            <option @if($val->category=="Daily Essentials")selected @endif>{{$lang["product_storefront_daily_essentials"]}}</option>
                                            <option @if($val->category=="Drinks")selected @endif>{{$lang["product_storefront_drinks"]}}</option>
                                            <option @if($val->category=="Education")selected @endif>{{$lang["product_storefront_education"]}}</option>
                                            <option @if($val->category=="Electronics")selected @endif>{{$lang["product_storefront_electronics"]}}</option>
                                            <option @if($val->category=="Food & Beverages")selected @endif>{{$lang["product_storefront_food_beverages"]}}</option>
                                            <option @if($val->category=="Gaming")selected @endif>{{$lang["product_storefront_gaming"]}}</option>
                                            <option @if($val->category=="Groceries")selected @endif>{{$lang["product_storefront_groceries"]}}</option>
                                            <option @if($val->category=="Gym and Fitness")selected @endif>{{$lang["product_storefront_gym_and_fitness"]}}</option>
                                            <option @if($val->category=="Health & Pharmaceuticals")selected @endif>{{$lang["product_storefront_health_pharmaceuticals"]}}</option>
                                            <option @if($val->category=="Home & Kitchen")selected @endif>{{$lang["product_storefront_home_kitchen"]}}</option>
                                            <option @if($val->category=="Insurance")selected @endif>{{$lang["product_storefront_insurance"]}}</option>
                                            <option @if($val->category=="Kids Fashion")selected @endif>{{$lang["product_storefront_kids_fashion"]}}</option>
                                            <option @if($val->category=="Makeup and Cosmetics")selected @endif>{{$lang["product_storefront_makeup_and_cosmetics"]}}</option>
                                            <option @if($val->category=="Mens Fashion")selected @endif>{{$lang["product_storefront_mens_fashion"]}}</option>
                                            <option @if($val->category=="Office Equipment")selected @endif>{{$lang["product_storefront_office_equipment"]}}</option>
                                            <option @if($val->category=="Others")selected @endif>{{$lang["product_storefront_others"]}}</option>
                                            <option @if($val->category=="Personal Care")selected @endif>{{$lang["product_storefront_personal_care"]}}</option>
                                            <option @if($val->category=="Phones and Tablets")selected @endif>{{$lang["product_storefront_phones_and_tablets"]}}</option>
                                            <option @if($val->category=="Professional Services")selected @endif>{{$lang["product_storefront_professional_services"]}}</option>
                                            <option @if($val->category=="Religious Organization")selected @endif>{{$lang["product_storefront_religious_organization"]}}</option>
                                            <option @if($val->category=="Restaurant")selected @endif>{{$lang["product_storefront_restaurant"]}}</option>
                                            <option @if($val->category=="Supermarket")selected @endif>{{$lang["product_storefront_supermarket"]}}</option>
                                            <option @if($val->category=="Toys & Games")selected @endif>{{$lang["product_storefront_toys_games"]}}</option>
                                            <option @if($val->category=="Womens Fashion")selected @endif>{{$lang["product_storefront_womens_fashion"]}}</option>
                                        </select>
                                    </div>       
                                </div>  
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_shipping_status"]}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="shipping_status" required>
                                            <option value='0' @if($val->shipping_status==0) selected @endif>{{$lang["product_storefront_disabled"]}}</option>
                                            <option value='1' @if($val->shipping_status==1) selected @endif>{{$lang["product_storefront_active"]}}</option>
                                        </select>
                                    </div>                                            
                                </div>    
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_delivery_note"]}}</label>
                                    <div class="col-lg-12">
                                        <select class="form-control custom-select" name="note_status" required>
                                            <option value='0' @if($val->note_status==0) selected @endif>{{$lang["product_storefront_disabled"]}}</option>
                                            <option value='1' @if($val->note_status==1) selected @endif>{{$lang["product_storefront_required"]}}</option>
                                            <option value='2' @if($val->note_status==2) selected @endif>{{$lang["product_storefront_optional"]}}</option>
                                        </select>
                                    </div>
                                </div>                            
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_edit_store"]}}</button>
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="new-store" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_create_store"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.store')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_store_name"]}}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="store_name" class="form-control" placeholder="{{$lang["product_storefront_store_name"]}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_store_description"]}}</label>
                                <div class="col-lg-12">
                                    <textarea type="text" name="store_desc" class="form-control" required></textarea>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_shipping_status"]}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping_status" required>
                                        
                                        <option value='1'>{{$lang["product_storefront_active"]}}</option>
                                        <option value='0'>{{$lang["product_storefront_disabled"]}}</option>
                                    </select>
                                </div>                                            
                            </div>    
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_disabled"]}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="note_status" required>
                                        <option value='1'>{{$lang["product_storefront_required"]}}</option>
                                        <option value='0'>{{$lang["product_storefront_disabled"]}}</option>                                      
                                        <option value='2'>{{$lang["product_storefront_optional"]}}</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_category"]}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="category" required>
                                        <option>{{$lang["product_storefront_animals_pet"]}}</option>
                                        <option>{{$lang["product_storefront_arts_and_crafts"]}}</option>
                                        <option>{{$lang["product_storefront_baby_produts"]}}</option>
                                        <option>{{$lang["product_storefront_beauty_and_skincare"]}}</option>
                                        <option>{{$lang["product_storefront_books_and_media"]}}</option>
                                        <option>{{$lang["product_storefront_building_and_construction"]}}</option>
                                        <option>{{$lang["product_storefront_daily_essentials"]}}</option>
                                        <option>{{$lang["product_storefront_drinks"]}}</option>
                                        <option>{{$lang["product_storefront_education"]}}</option>
                                        <option>{{$lang["product_storefront_electronics"]}}</option>
                                        <option>{{$lang["product_storefront_food_beverages"]}}</option>
                                        <option>{{$lang["product_storefront_gaming"]}}</option>
                                        <option>{{$lang["product_storefront_groceries"]}}</option>
                                        <option>{{$lang["product_storefront_gym_and_fitness"]}}</option>
                                        <option>{{$lang["product_storefront_health_pharmaceuticals"]}}</option>
                                        <option>{{$lang["product_storefront_home_kitchen"]}}</option>
                                        <option>{{$lang["product_storefront_insurance"]}}</option>
                                        <option>{{$lang["product_storefront_kids_fashion"]}}</option>
                                        <option>{{$lang["product_storefront_makeup_and_cosmetics"]}}</option>
                                        <option>{{$lang["product_storefront_mens_fashion"]}}</option>
                                        <option>{{$lang["product_storefront_office_equipment"]}}</option>
                                        <option>{{$lang["product_storefront_others"]}}</option>
                                        <option>{{$lang["product_storefront_personal_care"]}}</option>
                                        <option>{{$lang["product_storefront_phones_and_tablets"]}}</option>
                                        <option>{{$lang["product_storefront_professional_services"]}}</option>
                                        <option>{{$lang["product_storefront_religious_organization"]}}</option>
                                        <option>{{$lang["product_storefront_restaurant"]}}</option>
                                        <option>{{$lang["product_storefront_supermarket"]}}</option>
                                        <option>{{$lang["product_storefront_toys_games"]}}</option>
                                        <option>{{$lang["product_storefront_womens_fashion"]}}</option>
                                    </select>
                                </div>       
                            </div>                                
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_create_store"]}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>   
            </div>            
            <div class="tab-pane fade @if(route('user.shipping')==url()->current())show active @endif" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-8 text-left">
                        <a data-toggle="modal" data-target="#new-shipping" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["product_storefront_new_add_shipping_fee"]}}</a>    
                    </div>
                </div>
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic2">
                        <thead>
                            <tr>
                            <th>{{$lang["product_storefront_sn"]}}</th>
                            <th></th>
                            <th>{{$lang["product_storefront_region"]}}</th>
                            <th>{{$lang["product_storefront_amount"]}}</th>
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($shipping as $k=>$val)
                            <tr>
                                <td>{{++$k}}.</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                        <i class="fad fa-chevron-circle-down"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a data-toggle="modal" data-target="#editship{{$val->id}}" href="" class="dropdown-item"><i class="fad fa-pencil"></i>{{$lang["product_storefront_edit"]}}</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteship{{$val->id}}" href=""><i class="fad fa-trash"></i>{{$lang["product_storefront_delete"]}}</a>
                                        </div>
                                    </div>
                                </td> 
                                <td>{{$val->region}}</td>
                                <td>{{$currency->name.' '.$val->amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                @foreach($shipping as $k=>$val)
                    <div class="modal fade" id="deleteship{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card bg-white border-0 mb-0">
                                        <div class="card-header">
                                            <h3 class="mb-0 font-weight-bolder">{{$lang["product_storefront_delete_shipping_fee"]}}</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                            <span class="mb-0 text-xs">{{$lang["product_storefront_are_you_sure_you_want_to_delete"]}}</span>
                                        </div>
                                        <div class="card-body">
                                            <a  href="{{route('delete.shipping', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["product_storefront_proceed"]}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editship{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_edit_shipping_fee"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('edit.shipping')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_region"]}}</label>
                                    <div class="col-lg-12">
                                        <input type="text" name="region" class="form-control" value="{{$val->region}}" placeholder="New York" required>
                                    </div>
                                </div>
                                <input type="hidden" value="{{$val->id}}" name="id">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">{{$currency->symbol}}</span>
                                        </span>
                                        <input type="number" class="form-control" name="amount" value="{{$val->amount}}" placeholder="0.00" required>
                                        <span class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_edit_shipping_fee"]}}</button>
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="modal fade" id="new-shipping" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_create_shipping_fee"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.shipping')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_region"]}}</label>
                                <div class="col-lg-12">
                                    <input type="text" name="region" class="form-control" placeholder="New York" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">{{$currency->symbol}}</span>
                                    </span>
                                    <input type="number" class="form-control" name="amount" placeholder="0.00" required>
                                    <span class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </span>
                                    </div>
                                </div>
                            </div>                                
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_create_shipping_fee"]}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>  

            </div>
            <div class="tab-pane fade @if(route('user.product')==url()->current())show active @endif" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                <div class="row align-items-center py-4">
                    <div class="col-12">
                        <a data-toggle="modal" data-target="#category" href="" class="btn btn-sm btn-neutral"><i class="fad fa-filter"></i> {{$lang["product_storefront_category"]}}</a> 
                        <a data-toggle="modal" data-target="#new-product" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["product_storefront_create_product"]}}</a> 
                        <a data-toggle="modal" data-target="#statistic" href="" class="btn btn-sm btn-neutral"><i class="fad fa-sync"></i> {{$lang["product_storefront_statistics"]}}</a> 
                    </div>
                </div>
                <div class="modal fade" id="category" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_category"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.category')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-12">{{$lang["product_storefront_name"]}}</label>
                                    <div class="col-lg-12">
                                    <input type="text" name="name" class="form-control" placeholder="{{$lang["product_storefront_name_of_category"]}}" required>
                                    </div>
                                </div> 
                                <div class="text-right">
                                    <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_create_category"]}}</button>
                                </div>
                                <ul class="list-group list-group-flush list">
                                    @if(count($category)>0)
                                    @foreach($category as $k=>$val)
                                        <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                            <span class="text-gray text-xs">{{$val->name}}</span>
                                            </div>
                                            <div class="col-4 text-right">
                                            <a href="{{route('delete.category', ['id' => $val->id])}}" class="btn btn-sm btn-neutral"><i class="fad fa-trash"></i> {{$lang["product_storefront_delete"]}}</a>
                                            </div>
                                        </div>
                                        </li>
                                    @endforeach
                                    @else
                                    <div class="row text-center">
                                        <div class="col-md-12 mb-5">
                                        <div class="text-center mt-8">
                                            <div class="mb-3">
                                            <img src="{{url('/')}}/asset/images/empty.svg">
                                            </div>
                                            <h3 class="text-dark">{{$lang["product_storefront_no_category_found"]}}</h3>
                                            <p class="text-dark text-sm card-text">{{$lang["product_storefront_we_couldnt_findany_category"]}}</p>
                                        </div>
                                        </div>
                                    </div>
                                    @endif
                                </ul>                   
                            </form>
                        </div>
                        </div>
                    </div>
                </div>    
                <div class="modal fade" id="new-product" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_new_product"]}}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('submit.product')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_name"]}}</label>
                                <div class="col-lg-12">
                                <input type="text" name="name" class="form-control" placeholder="{{$lang["product_storefront_th_name_of_your_product"]}}" required>
                                </div>
                            </div>              
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_category"]}}</label>
                                <div class="col-lg-12">
                                <select class="form-control custom-select" name="category" required>
                                    <option value="">{{$lang["product_storefront_select_product"]}}</option>
                                    @foreach($category as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                                </div>       
                            </div>       
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_amount"]}}</label>
                                <div class="col-lg-12">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">{{$currency->symbol}}</span>
                                    </div>
                                    <input type="number" step="any" name="amount" maxlength="10" class="form-control" required>
                                </div>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_quantity"]}}</label>
                                <div class="col-lg-12">
                                    <input type="number" name="quantity" class="form-control" value="1" required>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label class="col-form-label col-lg-12">{{$lang["product_storefront_shipping_status"]}}</label>
                                <div class="col-lg-12">
                                    <select class="form-control custom-select" name="shipping_status" required>
                                        
                                        <option value='1'>{{$lang["product_storefront_active"]}}</option>
                                        <option value='0'>{{$lang["product_storefront_disabled"]}}</option>
                                    </select>
                                </div>                                            
                            </div>   
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="custom-file text-center">
                                        <input type="file" class="custom-file-input" name="file" accept="image/*" id="customFileLang" required>
                                        <label class="custom-file-label" for="customFileLang">{{$lang["product_storefront_choose_media"]}}</label>
                                        <span class="form-text text-xs">{{$lang["product_storefront_recommended_image_size"]}}</span>
                                    </div>
                                </div>
                            </div>             
                            <div class="text-right">
                                <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_storefront_create_product"]}}</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>                
                <div class="modal fade" id="statistic" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title font-weight-bolder">{{$lang["product_storefront_statistics"]}}</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{$lang["product_storefront_received"]}}</span><br>
                                        <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($received)}}.00</span><br>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="my-4">
                                        <span class="surtitle">{{$lang["product_storefront_pending"]}}</span><br>
                                        <span class="surtitle ">{{$lang["product_storefront_total"]}}</span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="my-4">
                                            <span class="surtitle ">{{$currency->name}} {{number_format($total, 2, '.', '')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="row">  
                    <div class="col-md-12">
                        <div class="row">  
                            @if(count($product)>0)
                                @foreach($product as $k=>$val)
                                    <div class="col-md-4">
                                        <div class="card">
                                            <img class="card-img-top" 
                                            @if($val->new==0)
                                                src="{{url('/')}}/asset/images/product-placeholder.jpg"
                                            @else
                                                @php
                                                    $image=App\Models\Productimage::whereproduct_id($val->id)->first();
                                                @endphp
                                                src="{{url('/')}}/asset/profile/{{$image['image']}}"
                                            @endif 
                                            alt="Image placeholder">
                                            <!-- Card body -->
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-8">
                                                    <p class="text-sm text-dark mb-2"><a class="btn-icon-clipboard" data-clipboard-text="{{route('user.ask', ['id' => $val->ref_id])}}" title="{{$lang["product_storefront_copy"]}}">{{$lang["product_storefront_copy_link"]}} <i class="fad fa-link text-xs"></i></a></p>
                                                    </div>
                                                    <div class="col-4 text-right">
                                                    <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                                                        <i class="fad fa-chevron-circle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a class="dropdown-item" href="{{route('edit.product', ['id' => $val->ref_id])}}"><i class="fad fa-pencil"></i>{{$lang["product_storefront_edit"]}}</a>
                                                        <a class="dropdown-item" href="{{route('orders', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{$lang["product_storefront_orders"]}}</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="#"><i class="fad fa-trash-alt"></i>{{$lang["product_storefront_delete"]}}</a>
                                                    </div>
                                                    </div>                        
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                    <h5 class="h4 mb-1 font-weight-bolder">{{$val->name}}</h5>
                                                    <p>Sold: {{$val->sold}}/{{$val->quantity}}</p>
                                                    @if($val->status==1)
                                                        <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{$lang["product_storefront_active"]}}</span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger"><i class="fad fa-ban"></i> {{$lang["product_storefront_disabled"]}}</span>
                                                    @endif
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
                                                            <h3 class="mb-0 font-weight-bolder">{{$lang["product_storefront_delete_product"]}}</h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <span class="mb-0 text-xs">{{$lang["product_storefront_are_you_sure_you_want_to_delete"]}}</span>
                                                            </div>
                                                            <div class="card-body">
                                                                <a  href="{{route('delete.product', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["product_storefront_proceed"]}}</a>
                                                            </div>
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
                                    <h3 class="text-dark">{{$lang["product_storefront_no_product_found"]}}</h3>
                                    <p class="text-dark text-sm card-text">{{$lang["product_storefront_we_couldnt_find_any_product"]}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> 
            <div class="tab-pane fade @if(route('user.list')==url()->current())show active @endif" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{$lang["product_storefront_client_orders"]}}</h5>
                        </div>
                        <div class="row">  
                        @if(count($orders)>0)  
                        @foreach($orders as $k=>$val)
                            <div class="col-md-6">
                            <div class="card bg-white">
                                <!-- Card body -->
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h4 mb-1">{{$val->ref_id}}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_product"]}}: {{$val->product->name}}</p>
                                        @if($val->user_id!=null)
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_name"]}}: {{$val->buyer->first_name}} {{$val->buyer->last_name}}</p>
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_email"]}}: {{$val->buyer->email}}</p>
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_phone"]}}: {{$val->buyer->phone}}</p>
                                        @else
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_name"]}}: {{$val->first_name}} {{$val->last_name}}</p>
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_email"]}}: {{$val->email}}</p>
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_phone"]}}: {{$val->phone}}</p>
                                        @endif
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_quantity"]}}: {{$val->quantity}}</p> 
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_country"]}}: {{$val->country}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_state"]}}: {{$val->state}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_town_city"]}}: {{$val->town}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_address"]}}: {{$val->address}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_region"]}}: {{($val->ship != null ? $val->ship['region'] : "")}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_shipping_fee"]}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                                        @if($val->product->note_status==1 || $val->product->note_status==2)
                                            @if(!empty($val->note))
                                                <p class="text-sm text-dark mb-0">{{$lang["product_storefront_note"]}}: {{$val->note}}</p>
                                            @endif
                                        @endif                                        
                                        @if($val->store_id==null)
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_type"]}}: {{$lang["product_storefront_single_purchase"]}}</p>
                                        @elseif($val->store_id!=null)
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_type"]}}: {{$lang["product_storefront_store_purchase"]}}</p>
                                        @endif     
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_amount"]}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_total"]}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_created"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
                                        <span class="badge badge-pill badge-primary">{{$lang["product_storefront_fee"]}}: {{$currency->symbol.number_format($val->charge, 2, '.', '')}}</span>
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
                            <h3 class="text-dark">{{$lang["product_storefront_no_orders"]}}</h3>
                            <p class="text-dark text-sm card-text">{{$lang["product_storefront_we_couldnt_find_any_product_order"]}}</p>
                            </div>
                        </div>
                        @endif
                        </div> 
                    </div>
                </div>
            </div>             
            <div class="tab-pane fade @if(route('user.your-list')==url()->current())show active @endif" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">      
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{$lang["product_storefront_your_orders"]}}</h5>
                        </div>
                        <div class="row">  
                        @if(count($yourorders)>0)  
                        @foreach($yourorders as $k=>$val)
                            <div class="col-md-6">
                            <div class="card bg-white">
                                <!-- Card body -->
                                <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h4 mb-1">{{$val->ref_id}}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col">
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_product"]}}: {{$val->product->name}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_product"]}}: {{$val->quantity}}</p> 
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_country"]}}: {{$val->country}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_state"]}}: {{$val->state}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_town_city"]}}: {{$val->town}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_address"]}}: {{$val->address}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_region"]}}: {{($val->ship != null ? $val->ship['region'] : "")}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_shipping_fee"]}}: @if($val->ship!=null){{$currency->symbol.$val->shipping_fee}} @endif</p>
                                        @if($val->product->note_status==1 || $val->product->note_status==2)
                                            @if(!empty($val->note))
                                                <p class="text-sm text-dark mb-0">{{$lang["product_storefront_note"]}}: {{$val->note}}</p>
                                            @endif
                                        @endif                                        
                                        @if($val->store_id==null)
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_type"]}}: {{$lang["product_storefront_single_purchase"]}}</p>
                                        @elseif($val->store_id!=null)
                                            <p class="text-sm text-dark mb-0">{{$lang["product_storefront_type"]}}: {{$lang["product_storefront_store_purchase"]}}</p>
                                        @endif      
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_amount"]}}: {{$currency->symbol}}{{number_format($val->amount, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_total"]}}: {{$currency->symbol.number_format($val->amount*$val->quantity+$val->shipping_fee, 2, '.', '')}}</p>
                                        <p class="text-sm text-dark mb-0">{{$lang["product_storefront_created"]}}: {{date("Y/m/d h:i:A", strtotime($val->created_at))}}</p>
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
                            <h3 class="text-dark">{{$lang["product_storefront_no_orders"]}}</h3>
                            <p class="text-dark text-sm card-text">{{$lang["product_storefront_we_couldnt_find_any_product_order"]}}</p>
                            </div>
                        </div>
                        @endif
                        </div> 
                    </div>
                </div>
            </div>       
        </div>       
  @stop