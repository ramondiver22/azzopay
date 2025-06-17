
@extends('userlayout')

@section('content')
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="content-wrapper">
    <div class="row align-items-center py-4">
      <div class="col-lg-6 col-7">
        <h6 class="h2 d-inline-block mb-0">{{$lang["product_list_products"]}}</h6>
      </div>
      <div class="col-lg-6 col-5 text-right">
        <a data-toggle="modal" data-target="#category" href="" class="btn btn-sm btn-neutral"><i class="fad fa-filter"></i> {{$lang["product_list_category"]}}</a> 
        <a data-toggle="modal" data-target="#new-product" href="" class="btn btn-sm btn-neutral"><i class="fad fa-plus"></i> {{$lang["product_list_create_product"]}}</a> 
      </div>
    </div>
    <div class="modal fade" id="category" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title font-weight-bolder">{{$lang["product_list_category"]}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('submit.category')}}" method="post">
              @csrf
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{$lang["product_list_name"]}}</label>
                <div class="col-lg-12">
                  <input type="text" name="name" class="form-control" placeholder="{{$lang["product_list_name_of_category"]}}" required>
                </div>
              </div> 
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_list_create_category"]}}</button>
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
                          <a href="{{route('delete.category', ['id' => $val->id])}}" class="btn btn-sm btn-neutral"><i class="fad fa-trash"></i> {{$lang["product_list_delete"]}}</a>
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
                        <h3 class="text-dark">{{$lang["product_list_no_category_found"]}}</h3>
                        <p class="text-dark text-sm card-text">{{$lang["product_list_we_couldnt_find_any_product"]}}</p>
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
            <h3 class="modal-title font-weight-bolder">{{$lang["product_list_new_product"]}}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('submit.product')}}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{$lang["product_list_name"]}}</label>
                <div class="col-lg-12">
                  <input type="text" name="name" class="form-control" placeholder="{{$lang["product_list_the_name_of_your_product"]}}" required>
                </div>
              </div>  
              <div class="form-group row">
                <div class="col-lg-12">
                  <div class="custom-file text-center">
                    <input type="hidden" value="{{$product->id}}" name="id">
                    <input type="file" class="custom-file-input" name="file" accept="image/*" id="customFileLang">
                    <label class="custom-file-label" for="customFileLang">{{$lang["product_list_choose_media"]}}</label>
                  </div>
                </div>
              </div>            
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{$lang["product_list_category"]}}</label>
                <div class="col-lg-12">
                  <select class="form-control custom-select" name="category" required>
                    <option value="">{{$lang["product_list_select_category"]}}</option>
                    @foreach($category as $val)
                      <option value="{{$val->id}}">{{$val->name}}</option>
                    @endforeach
                  </select>
                </div>       
              </div>       
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{$lang["product_list_amount"]}}</label>
                <div class="col-lg-12">
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{$currency->symbol}}</span>
                    </div>
                    <input type="number" step="any" name="amount" maxlength="10" class="form-control" required="">
                  </div>
                </div>
              </div>  
              <div class="form-group row">
                <label class="col-form-label col-lg-12">{{$lang["product_list_quantity"]}}</label>
                <div class="col-lg-12">
                  <input type="number" name="quantity" class="form-control" value="1" required>
                </div>
              </div>               
              <div class="text-right">
                <button type="submit" class="btn btn-neutral btn-block">{{$lang["product_list_create_product"]}}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> 
    <div class="row">  
      <div class="col-md-8">
      @if(count($product)>0)
        @foreach($product as $k=>$val)
          <div class="card">
            <!-- Card body -->
            <div class="card-body">
              <div class="row mb-0">
                <div class="col-6">
                  <span class="form-text text-xl">{{$currency->symbol}} {{number_format($val->amount)}}.00</span>
                </div>
                <div class="col-6 text-right">
                  <a class="mr-0 text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="fadse">
                    <i class="fad fa-chevron-circle-down"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-left">
                    @if($val->status==1)
                      <a class="dropdown-item" href="{{route('edit.product', ['id' => $val->ref_id])}}"><i class="fad fa-pencil"></i>{{$lang["product_list_edit"]}}</a>
                      <a class="dropdown-item" href="{{route('orders', ['id' => $val->id])}}"><i class="fad fa-sync"></i>{{$lang["product_list_orders"]}}</a>
                    @endif
                    <a class="dropdown-item" data-toggle="modal" data-target="#delete{{$val->id}}" href="#"><i class="fad fa-trash-alt"></i>{{$lang["product_list_delete"]}}</a>
                  </div>
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col-auto">
                  <!-- Avatar -->
                  <a href="javascript:void;" class="avatar avatar-xl">
                    <img               
                    @if($val->new==0)
                      src="{{url('/')}}/asset/images/product-placeholder.jpg"
                    @else
                      @php $image=App\Models\Productimage::whereproduct_id($val->id)->first();@endphp
                      src="{{url('/')}}/asset/profile/{{$image['image']}}"
                    @endif alt="Image placeholder">
                  </a>
                </div>
                <div class="col">
                  <p class="">{{$val->name}}</p>
                  <p class="">{{$lang["product_list_sold"]}}: {{$val->sold}}/{{$val->quantity}}</p>
                  <p class="text-sm mb-2"><a class="btn-icon-clipboard text-uppercase" data-clipboard-text="{{route('product.link', ['id' => $val->ref_id])}}" title="{{$lang["product_list_copy"]}}">{{$lang["product_list_copy_product_link"]}}</a></p>
                  @if($val->status==1)
                      <span class="badge badge-pill badge-primary"><i class="fad fa-check"></i> {{$lang["product_list_active"]}}</span>
                  @else
                      <span class="badge badge-pill badge-danger">{{$lang["product_list_disabled"]}}</span>
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
                                  <h3 class="mb-0 font-weight-bolder">{{$lang["product_list_delete_product"]}}</h3>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <span class="mb-0 text-xs">{{$lang["product_list_are_you_sure_you_want_to_delete"]}}</span>
                                </div>
                                <div class="card-body">
                                    <a  href="{{route('delete.product', ['id' => $val->id])}}" class="btn btn-danger btn-block">{{$lang["product_list_proceed"]}}</a>
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
          <h3 class="text-dark">{{$lang["product_list_no_product_found"]}}</h3>
          <p class="text-dark text-sm card-text">{{$lang["product_list_we_couldnt_find_any_product"]}}</p>
        </div>
      </div>
      @endif
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col text-center">
                <h4 class="mb-4 text-primary font-weight-bolder">
                {{$lang["product_list_statistics"]}}
                </h4>
                <span class="text-sm text-dark mb-0"><i class="fa fa-google-wallet"></i> {{$lang["product_list_received"]}}</span><br>
                <span class="text-xl text-dark mb-0">{{$currency->name}} {{number_format($received)}}.00</span><br>
                <hr>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col">
                <div class="my-4">
                  <span class="surtitle">{{$lang["product_list_pending"]}}</span><br>
                  <span class="surtitle ">{{$lang["product_list_total"]}}</span>
                </div>
              </div>
              <div class="col-auto">
                <div class="my-4">
                  <span class="surtitle ">{{$currency->name}} 00.00</span><br>
                  <span class="surtitle ">{{$currency->name}} {{number_format($total)}}.00</span>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
@stop