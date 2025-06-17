<!doctype html>
<html class="no-js" lang="en">
    <head>
        <base href="{{url('/')}}"/>
        <title>{{ $title }} | {{$set->site_name}}</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
        <meta name="robots" content="index, follow">
        <meta name="apple-mobile-web-app-title" content="{{$set->site_name}}"/>
        <meta name="application-name" content="{{$set->site_name}}"/>
        <meta name="msapplication-TileColor" content="#ffffff"/>
        <meta name="description" content="{{$set->site_desc}}" />
        <link rel="shortcut icon" href="{{url('/')}}/asset/{{$logo->image_link2}}" />
        <link rel="stylesheet" href="{{url('/')}}/asset/css/toast.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('/')}}/asset/dashboard/css/argon.css?v=1.1.0" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/css/all.css" rel="stylesheet" type="text/css">
        <link href="{{url('/')}}/asset/fonts/fontawesome/styles.min.css" rel="stylesheet" type="text/css">


        <style>
            .custom-spinner-loader {
                border: 4px solid #f3f3f3; /* Light grey */
                border-top: 4px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 30px;
                height: 30px;
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
  <body class="">
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-dark">
      <div class="container">
        <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
          <div class="navbar-collapse-header">
          </div>
        </div>
      </div>
    </nav>
<!-- header end -->

@yield('content')
{!!$set->livechat!!}
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{url('/')}}/asset/dashboard/vendor/jquery/dist/jquery.min.js"></script>
  <script src="https://js.stripe.com/v3/"></script>
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
  <script src="{{url('/')}}/asset/dashboard/js/argon.js?v=1.1.0"></script>
  <script src="{{url('/')}}/asset/js/countries.js"></script>
  <script src="{{url('/')}}/asset/tinymce/tinymce.min.js"></script>
  <script src="{{url('/')}}/asset/tinymce/init-tinymce.js"></script>
  <script src="{{url('/')}}/asset/js/toast.js"></script>

  <script src="{{url('/')}}/asset/js/JsBarcode.all.min.js"></script>
  <script>
  var ppxd =  "{{$stripe->val1}}";
  </script>
  

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


  function get_form_csrf_data(formid) {

      let ajaxcointableform = JSON.stringify($('#'+formid).serializeArray());
      let formdata          = $.parseJSON(ajaxcointableform);
      for (let i = 0; i < formdata.length; i++) {
          let inputname         = formdata[i]['name'];
          let inputval          = formdata[i]['value'];

          if (inputname === '_token') {
              return inputval;
          }
      }
  }


function copyToClipboard(field) {
    /* Get the text field */
    var copyText = document.getElementById(field);

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");

}

  $('#xx').change(function() {
  $('#castro').val($('#xx').val());
  $('#xcastro').val($('#xx').val());
  });  
</script>
<script type="text/javascript">
  "use strict";
  function sess(){
      try {
        var quantity = $("#quanz").val();
        var amount = $("#amount4").val();
        var subtotal = parseFloat(amount)*parseInt(quantity);
        var total = parseFloat(subtotal);
        $("#product4").text(quantity);
        $("#subtotal4").text(subtotal);
        $("#total4").text(total);
      } catch (e) {

      }
  }
$("#quanz").change(sess);
sess();
</script>
<script type="text/javascript">
  "use strict"; 
  function mystatus() {
      try {
        var x = document.getElementById("xstatus").value;
        document.getElementById("boom").value = x;
      } catch (e) {

      }
  }  

  try {
    populateCountries("country", "state");
  } catch (e) {

  }

function sellVals(){

  try {
    var quantity = ($("#quantity").val().length > 0 ? $("#quantity").val() : 1);
    var amount = parseFloat($("#amount").val());
    var xx = $("#ship_fee").find(":selected").val();
    var myarr = xx.split("-");
    var ship_fee = parseFloat(myarr[1].split("<"));
    var subtotal = parseFloat(amount * quantity);
    var total = parseFloat(subtotal + ship_fee);
    $("#product1").text(quantity);
    $("#subtotal1").text(subtotal.toFixed(2));
    $("#total1").text(total.toFixed(2));
    $("#inputGrandTotal").val(total.toFixed(2));
    $("#flat").text(ship_fee.toFixed(2));
    $("#xship").val(myarr[0].split("<"));
    $("#xship_fee").val(myarr[1].split("<"));

    try {
      calcInstallments("inputGrandTotal", @if (isset($merchant)) {{$merchant->id}} @elseif(isset($product))  {{$product->user_id}} @else 0 @endif, "creditcard_installments");
    } catch (e) {
      console.log(e);
    }
  } catch (e) {
    console.log(e);
  }
}

$(document).ready(function () {
    $("#quantity").change(sellVals).trigger("change");
    $("#ship_fee").change(sellVals).trigger("change");
});

sellVals();

function gvals(){
  try {
    var amount = parseFloat($("#amount3").val());
    var xx = $("#ship_fee").find(":selected").val();
    var myarr = xx.split("-");
    var ship_fee = parseFloat(myarr[1].split("<"));
    var subtotal = amount;
    var quantity = parseInt($("#qtdProducts").val());
    let shipFeeTotal = (ship_fee * quantity);
    var total = (subtotal + shipFeeTotal);
    $("#subtotal3").text(subtotal.toFixed(2));
    $("#total3").text(total.toFixed(2));
    $("#inputGrandTotal").val(total.toFixed(2));
    //$("#flat").text(shishipFeeTotalp_fee.toFixed(2));
    $("#flat").text(shipFeeTotal.toFixed(2));
    $("#xship").val(myarr[0].split("<"));
    $("#xship_fee").val(myarr[1].split("<"));

    try {
      calcInstallments("inputGrandTotal", @if (isset($merchant)) {{$merchant->id}} @elseif(isset($product))  {{$product->user_id}} @else 0 @endif, "creditcard_installments");
    } catch (e) {
      console.log(e);
    }
  } catch (e) {
    console.log(e);
  }
}

$(document).ready(function () {
    $("#quantity").change(gvals).trigger("change");
    $("#ship_fee").change(gvals).trigger("change");
});

gvals();  


  function calcInstallments(fieldId, userId, targetField) {
      

      let data = {
          _token: "{{ csrf_token() }}",
          user: userId,
          amount: $("#"+fieldId).val()
      };
      
      $("#installment-loader").show();
      $.post("{{ route('creditcard.calc.installments')}}", data, function (json) {
          try {
              if (json.success) {
                  $("#" + targetField).html(json.html);
              } else {
                  console.log(json.message);
              }
          } catch (e) {
              console.log(e);
          }
          
      }, "json").always(function () {
          $("#installment-loader").hide();
      });
  }
</script>
