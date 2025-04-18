@extends('layouts.back-end.app')

{{-- @dd(session()->all()) --}}

@section('title', translate('POS'))
@section('content')
<!-- Content -->
	<!-- ========================= SECTION CONTENT ========================= -->
	<section class="section-content pt-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card">
                        <h5 class="p-3 m-0 bg-light">{{translate('product_Section')}}</h5>
                        <div class="px-3 py-4">
                            <div class="row gy-1">
                                <div class="col-sm-6">
                                    <div class="input-group d-flex justify-content-end" >
                                        <select name="category" id="category" class="form-control js-select2-custom w-100" title="select category" onchange="set_category_filter(this.value)">
                                            <option value="">{{translate('all_categories')}}</option>
                                            @foreach ($categories as $item)
                                            <option value="{{$item->id}}" {{$category==$item->id?'selected':''}}>{{$item->defaultName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <form class="">
                                        <!-- Search -->
                                        <div class="input-group-overlay input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="search" autocomplete="off" type="text" value="{{$keyword?$keyword:''}}"
                                                    name="search" class="form-control search-bar-input" placeholder="{{translate('search_by_name_or_sku')}}"
                                                    aria-label="Search here">
                                            <diV class="card pos-search-card w-4 position-absolute z-index-1 w-100">
                                                <div id="pos-search-box" class="card-body search-result-box d--none"></div>
                                            </diV>
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2" id="items">
                            <div class="pos-item-wrap">
                                @foreach($products as $product)
                                    @include('admin-views.pos._single_product',['product'=>$product])
                                @endforeach
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="px-4 d-flex justify-content-lg-end">
                                <!-- Pagination -->
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>
                    </div>
				</div>
				<div class="col-lg-5 mb-5">
                    <div class="card billing-section-wrap">
                        <h5 class="p-3 m-0 bg-light">{{translate('billing_Section')}}</h5>
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-outline--primary d-flex align-items-center gap-2" onclick="view_all_hold_orders()">
                                    {{translate('view_All_Hold_Orders')}}
                                    <span class="badge badge-danger rounded-circle total_hold_orders">
                                        <?php
                                            $view_all_hold_orders = 0;
                                            if(session()->has('cart_name')){
                                                foreach (session('cart_name') as $item){
                                                    if (session()->has($item) && count(session($item)) > 1){
                                                        $view_all_hold_orders++;
                                                    }
                                                }
                                            }
                                        ?>
                                        {{ $view_all_hold_orders }}
                                    </span>
                                </button>
                            </div>

                            <div class="form-group d-flex gap-2">

                                <?php
                                    $user_id = 0;
                                    if(Str::contains(session('current_user'), 'sc'))
                                    {
                                        $user_id = explode('-',session('current_user'))[1];
                                    }
                                ?>

                                <select onchange="customer_change(this.value);" id='customer' name="customer_id" data-placeholder="Walking Customer" class="js-example-matcher form-control form-ellipsis">
                                    <option value="0" {{ $user_id == 0 ? 'selected':''}}>{{translate('walking_customer')}}</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $user_id == $customer->id ? 'selected':''}}>{{ $customer->f_name }} {{ $customer->l_name }} ({{ $customer->phone }})</option>
                                    @endforeach
                                </select>

                                <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="Add New Customer">
                                    {{ translate('add_New_Customer')}}
                                </button>
                            </div>

                            <div id="cart-summary">
                                @include('admin-views.pos._cart-summary')
                            </div>

                        </div>
                    </div>
				</div>
			</div>
		</div><!-- container //  -->
	</section>
    <!-- End Content -->

    {{-- Quick View Modal --}}
    <div class="modal fade pt-5" id="quick-view" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>

    @php($order=\App\Model\Order::find(session('last_order')))
    @if($order)
    @php(session(['last_order'=> false]))
    <div class="modal fade py-5" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('print_Invoice')}}</h5>
                    <button id="invoice_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12">
                        <center>
                            <input id="print_invoice" type="button" class="btn btn--primary non-printable" onclick="printDiv('printableArea')"
                                value="{{translate('proceed')}}, {{translate('if_thermal_printer_is_ready')}}"/>
                            <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{translate('back')}}</a>
                        </center>
                        <hr class="non-printable">
                    </div>
                    <div class="row m-auto" id="printableArea">
                        @include('admin-views.pos.order.invoice')
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Add New Customer --}}
    <div class="modal fade" id="add-customer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('add_new_customer')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.pos.customer-store')}}" method="post" id="product_form"
                          >
                        @csrf
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{translate('first_name')}} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"  placeholder="{{translate('first_name')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{translate('last_name')}} <span
                                                class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"  placeholder="{{translate('last_name')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{translate('email')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"  placeholder="{{translate('ex')}}: ex@example.com" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label" >{{translate('phone')}}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"  placeholder="{{translate('phone')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pl-2" >
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('country')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country') }}"  placeholder="{{translate('country')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('city')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city') }}"  placeholder="{{translate('city')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('zip_code')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}"  placeholder="{{translate('zip_code')}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{translate('address')}} <span
                                            class="input-label-secondary text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address') }}"  placeholder="{{translate('address')}}" required>
                                    </div>
                                </div>
                            </div>

                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit_new_customer" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button class="d-none" id="hold-orders-modal-btn" type="button" data-toggle="modal" data-target="#hold-orders-modal"></button>

    {{-- Hold Orders Modal --}}
    <div class="modal fade" id="hold-orders-modal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pt-3 flex-wrap gap-2">
                    <h5 class="modal-title">{{translate('list_of_hold_orders')}}</h5>

                    <div class="">
                        <div class="search-form">
                            <button type="button" class="btn"><i class="tio-search"></i></button>
                            <input type="text" class="js-form-search form-control view_all_hold_orders_seach" placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="modal-body pt-3" id="hold-orders-modal-content">

                </div>
            </div>
        </div>
    </div>

    {{-- Coupon Discount --}}
    <div class="modal fade" id="add-coupon-discount" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('coupon_discount') }}</h5>
                    <button id="coupon_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="title-color">{{ translate('coupon_code') }}</label>
                        <input type="text" id="coupon_code" class="form-control" name="coupon_code"
                            placeholder="SULTAN200">
                        {{-- <input type="hidden" id="user_id" name="user_id" > --}}
                    </div>

                    <div class="form-group">
                        <button class="btn btn--primary" onclick="coupon_discount();"
                            data-dismiss="modal">{{ translate('submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Coupon Discount --}}

    {{-- Discont Amount --}}
    <div class="modal fade" id="add-discount" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('update_discount') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="title-color">{{ translate('type') }}</label>
                        <select name="type" id="type_ext_dis" class="form-control">
                            <option value="amount" {{ isset($discount_type) && $discount_type == 'amount' ? 'selected' : '' }}>{{ translate('amount') }}
                            </option>
                            <option value="percent" {{ isset($discount_type) && $discount_type == 'percent' ? 'selected' : '' }}>
                                {{ translate('percent') }}(%)
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="title-color">{{ translate('discount') }}</label>
                        <input type="number" id="dis_amount" class="form-control" name="discount" placeholder="Ex: 500">
                    </div>
                    <div class="form-group">
                        <button class="btn btn--primary" onclick="extra_discount()" data-dismiss="modal">{{ translate('submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Discont Amount --}}

    {{-- Add Tax --}}
    <div class="modal fade" id="add-tax" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('update_tax')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.pos.tax')}}" method="POST" class="row">
                        @csrf
                        <div class="form-group col-12">
                            <label for="">{{translate('tax')}} (%)</label>
                            <input type="number" class="form-control" name="tax" min="0">
                        </div>

                        <div class="form-group col-sm-12">
                            <button class="btn btn--primary" type="submit">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Tax --}}

    {{-- Short Cut Keys --}}
    <div class="modal fade" id="short-cut-keys" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('short_cut_keys')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span>{{translate('to_click_order')}} : alt + O</span><br>
                    <span>{{translate('to_click_payment_submit')}} : alt + S</span><br>
                    <span>{{translate('to_close_payment_submit')}} : alt + Z</span><br>
                    <span>{{translate('to_click_cancel_cart_item_all')}} : alt + C</span><br>
                    <span>{{translate('to_click_add_new_customer')}} : alt + A</span> <br>
                    <span>{{translate('to_submit_add_new_customer_form')}} : alt + N</span><br>
                    <span>{{translate('to_click_short_cut_keys')}} : alt + K</span><br>
                    <span>{{translate('to_print_invoice')}} : alt + P</span> <br>
                    <span>{{translate('to_cancel_invoice')}} : alt + B</span> <br>
                    <span>{{translate('to_focus_search_input')}} : alt + Q</span> <br>
                    <span>{{translate('to_click_extra_discount')}} : alt + E</span> <br>
                    <span>{{translate('to_click_coupon_discount')}} : alt + D</span> <br>
                    <span>{{translate('to_click_clear_cart')}} : alt + X</span> <br>
                    <span>{{translate('to_click_new_order')}} : alt + R</span> <br>
                </div>
            </div>
        </div>
    </div>
    {{-- Short Cut Keys --}}

@endsection

@push('script_2')
<script>
    let dropdownSelect = $('#dropdown-order-select');
    dropdownSelect.on('click', '.dropdown-menu .dropdown-item:not(:last-child)', function (e) {
        let selectedText = $(this).text();
        dropdownSelect.find('.dropdown-toggle').text(selectedText);
    })
</script>
<script>
        function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
        }

    $(document).on('ready', function () {
        $.ajax({
            url: '{{route('admin.pos.get-cart-ids')}}',
            type: 'GET',

            dataType: 'json', // added data type
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#cart-summary').empty().html(data.view);
                view_all_hold_orders('keyup');
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    });

    function form_submit(){
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '{{ translate("no") }}',
            confirmButtonText: '{{ translate("yes") }}',
            reverseButtons: true
        }).then(function (result) {
            if(result.value){
                $('#order_place').submit();
            }
        });
    }
</script>
<script>
    document.addEventListener("keydown", function(event) {
    "use strict";
    if (event.altKey && event.code === "KeyO")
    {
        $('#submit_order').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyZ")
    {
        $('#payment_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyS")
    {
        $('#order_complete').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyC")
    {
        emptyCart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyA")
    {
        $('#add_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyN")
    {
        $('#submit_new_customer').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyK")
    {
        $('#short-cut').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyP")
    {
        $('#print_invoice').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyQ")
    {
        $('#search').focus();
        $("#-pos-search-box").css("display", "none");
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyE")
    {
        $("#pos-search-box").css("display", "none");
        $('#extra_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyD")
    {
        $("#pos-search-box").css("display", "none");
        $('#coupon_discount').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyB")
    {
        $('#invoice_close').click();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyX")
    {
        clear_cart();
        event.preventDefault();
    }
    if (event.altKey && event.code === "KeyR")
    {
        new_order();
        event.preventDefault();
    }

});
</script>
<!-- JS Plugins Init. -->
<script>
    jQuery(".search-bar-input").on('keyup',function () {
        //$('#pos-search-box').removeClass('d-none');
        $(".pos-search-card").removeClass('d-none').show();
        let name = $(".search-bar-input").val();
        //console.log(name);
        if (name.length >0) {
            $('#pos-search-box').removeClass('d-none').show();
            $.get({
                url: '{{route('admin.pos.search-products')}}',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                    //console.log(data.count);

                    $('.search-result-box').empty().html(data.result);
                    if(data.count==1)
                    {
                        $('.search-result-box').empty().hide();
                        $('#search').val('');
                        quickView(data.id);
                    }

                },
                complete: function () {
                    $('#loading').fadeOut();
                },
            });
        } else {
            $('.search-result-box').empty().hide();
        }
    });

    // Close the search-result-box when clicking outside of it
    window.addEventListener("click", function(event) {
        let search_result_boxes = document.getElementsByClassName("search-result-box");
        for (let i = 0; i < search_result_boxes.length; i++) {
            let search_result_box = search_result_boxes[i];
            if (event.target !== search_result_box && !search_result_box.contains(event.target)) {
                search_result_box.style.display = "none";
            }
        }
    });
</script>
<script>
    "use strict";
    function customer_change(val) {
        //let  cart_id = $('#cart_id').val();
        $.post({
                url: '{{route('admin.pos.remove-discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    //cart_id:cart_id,
                    user_id:val
                },
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                    $('#cart-summary').empty().html(data.view);
                    view_all_hold_orders('keyup');
                },
                complete: function () {
                    $('#loading').fadeOut();
                }
            });
    }
</script>
<script>
    "use strict";
    function clear_cart()
    {
        let url = "{{route('admin.pos.clear-cart-ids')}}";
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function new_order()
    {
        let url = "{{route('admin.pos.new-cart-id')}}";
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function cart_change(val)
    {
        let  cart_id = val;
        let url = "{{route('admin.pos.change-cart')}}"+'/?cart_id='+val;
        document.location.href=url;
    }
</script>
<script>
    "use strict";
    function extra_discount()
    {
        //let  user_id = $('#customer').val();
        let discount = $('#dis_amount').val();
        let type = $('#type_ext_dis').val();
        //let  cart_id = $('#cart_id').val();
        if(discount > 0)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: "{{route('admin.pos.discount')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    discount:discount,
                    type:type,
                    //cart_id:cart_id
                },
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                   // console.log(data);
                    if(data.extra_discount==='success')
                    {
                        toastr.success('{{ translate("extra_discount_added_successfully") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.extra_discount==='empty')
                    {
                        toastr.warning('{{ translate("your_cart_is_empty") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                    }else{
                        toastr.warning('{{ translate("this_discount_is_not_applied_for_this_amount") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('.modal-backdrop').addClass('d-none');
                    $('#cart').empty().html(data.view);

                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').fadeOut();
                }
            });
        }else{
            toastr.warning('{{ translate("amount_can_not_be_negative_or_zero") }}!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    }
</script>
<script>
    "use strict";
    function coupon_discount()
    {

        let  coupon_code = $('#coupon_code').val();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.pos.coupon-discount')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    coupon_code:coupon_code,
                },
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                    console.log(data);
                    if(data.coupon === 'success')
                    {
                        toastr.success('{{ translate("coupon_added_successfully") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'amount_low')
                    {
                        toastr.warning('{{ translate("this_discount_is_not_applied_for_this_amount") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.coupon === 'cart_empty')
                    {
                        toastr.warning('{{ translate("your_cart_is_empty") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    else {
                        toastr.warning('{{ translate("coupon_is_invalid") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                    $('#cart').empty().html(data.view);

                    $('#search').focus();
                },
                complete: function () {
                    $('.modal-backdrop').addClass('d-none');
                    $(".footer-offset").removeClass("modal-open");
                    $('#loading').fadeOut();
                }
            });

    }

    function view_all_hold_orders(action = null)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: "{{route('admin.pos.view-all-hold-orders')}}",
            data: {
                _token: '{{csrf_token()}}',
                customer : $('.view_all_hold_orders_seach').val()
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#hold-orders-modal-content').empty().html(data.view);
                if(action != 'keyup')
                {
                    $('#hold-orders-modal-btn').click();
                }
                $('.total_hold_orders').text(data.total_hold_orders);
            },
            complete: function () {
                $('#loading').fadeOut();
            }
        });
    }

    $('.view_all_hold_orders_seach').keyup(function(){
        view_all_hold_orders('keyup');
    });

    function cancel_customer_order(cart_id)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: "{{route('admin.pos.cancel-customer-order')}}",
            data: {
                _token: '{{csrf_token()}}',
                cart_id: cart_id,
            },
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#hold-orders-modal-content').empty().html(data.view);
                // $('#hold-orders-modal-btn').click();
                location.reload();
            },
            complete: function () {
                $('#loading').fadeOut();
            }
        });
    }
</script>
<script>
    $(document).on('ready', function () {
        @if($order)
        $('#print-invoice').modal('show');
        @endif
    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        // location.reload();
    }

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword= $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('admin.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                toastr.success(key+' '+'{{translate("selected")}}!', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
        });
    }

    function addon_quantity_input_toggle(e)
    {
        var cb = $(e.target);
        if(cb.is(":checked"))
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        }
        else
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        }
    }
    function quickView(product_id) {
        $.ajax({
            url: '{{route('admin.pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });

        if (($('input:radio:checked').length - 1) == count) {
            return true;
        }
        return false;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "{{ translate('Cart') }}",
                    text: "{{ translate('Sorry_the_minimum_value_was_reached') }}"
                });
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "{{ translate('Cart') }}",
                    text: "{{ translate('Sorry_stock_limit_exceeded.') }}"
                });
                $(this).val($(this).data('oldValue'));
            }
        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getVariantPrice(type = null) {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('admin.pos.variant_price') }}'+(type ? '?type='+type : ''),
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {

                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#set-discount-amount').html(data.discount);
                    $('.product-tax-show').html(data.tax);

                    if (data.quantity <= 0) {
                        $('.stock-status-in-quick-view').removeClass('text-success').addClass('text-danger');
                        $('.stock-status-in-quick-view').html(`<i class="tio-checkmark-circle-outlined"></i> {{translate('stock_out')}}`);
                    }else{
                        $('.stock-status-in-quick-view').removeClass('text-danger').addClass('text-success');
                        $('.stock-status-in-quick-view').html(`<i class="tio-checkmark-circle-outlined"></i> {{translate('in_stock')}}`);
                    }

                    if (data.in_cart_status == 0) {
                        $('.quick-view-modal-add-cart-button').text("{{translate('add_to_cart')}}");
                        $('.in-cart-quantity-system').addClass("d--none").removeClass('d-flex');
                        $('.default-quantity-system').removeClass("d--none").addClass('d-flex');
                    }else{
                        $('.default-quantity-system').addClass("d--none").removeClass('d-flex');
                        $('.in-cart-quantity-system').removeClass("d--none").addClass('d-flex');
                        $('.quick-view-modal-add-cart-button').text("{{translate('update_to_cart')}}");
                        if (type == null) {
                            $('.in-cart-quantity-field').val(data.in_cart_data.quantity);
                            $('.in-cart-chosen_price').text(data.in_cart_data.price);
                            data.in_cart_data.quantity == 1 ? $('.in-cart-quantity-minus').attr('disabled', true) : '';
                        }else{
                            $('.in-cart-chosen_price').text(data.price);
                        }
                    }

                }
            });
        }
    }

    function getVariantForAlreayInCart(event = null){
        let current_val = parseFloat($('.in-cart-quantity-field').val());

        if (current_val > 0) {
            $('.in-cart-quantity-minus').removeAttr('disabled');
            if (event == 'plus') {
                $('.in-cart-quantity-field').val(current_val + 1);
            } else {
                $('.in-cart-quantity-field').val(current_val - 1);
                if (current_val <= 2) {
                    $('.in-cart-quantity-minus').attr('disabled', true);
                }
            }
        } else {
            $('.in-cart-quantity-minus').attr('disabled', true);
        }

        getVariantPrice('already_in_cart');
    }


    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {

                    if (data.data == 1) {
                        $('#cart-summary').empty().html(data.view);
                        toastr.success('{{ translate("cart_updated")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        data.in_cart_data && data.in_cart_data == 1 ? $('.in-cart-quantity-field').val(data.request_quantity) : '';
                        return false;
                    } else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: "{{ translate('Cart') }}",
                            text: '{{ translate("sorry_product_is_out_of_stock.")}}'
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{ translate("item_has_been_added_in_your_cart")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#cart').empty().html(data.view);
                    view_all_hold_orders('keyup');
                    //updateCart();
                    $('.search-result-box').empty().hide();
                    $('#search').val('');
                },
                complete: function () {
                    $('#loading').fadeOut();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: "{{ translate('Cart') }}",
                text: '{{ translate("please_choose_all_the_options")}}'
            });
        }
    }

    function removeFromCart(key) {
        //console.log(key);
        $.post('{{ route('admin.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {

            $('#cart').empty().html(data.view);
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                //updateCart();

                toastr.info('{{ translate("item_has_been_removed_from_cart")}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                view_all_hold_orders('keyup');
            }


        });
    }

    function emptyCart() {
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            text: '{{translate("you_want_to_remove_all_items_from_cart")}}!!',
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#161853',
            cancelButtonText: '{{translate("no")}}',
            confirmButtonText: '{{translate("yes")}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('{{ route('admin.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
                    $('#cart').empty().html(data.view);
                    toastr.info('{{ translate("item_has_been_removed_from_cart")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    view_all_hold_orders('keyup');
                });
            }
        })
    }

    function updateCart() {
        $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
            view_all_hold_orders('keyup');
        });
    }

   $(function(){
        $(document).on('click','input[type=number]',function(){ this.select(); });
    });


    function updateQuantity(key,qty,e, variant=null){

        if(qty!==""){
            var element = $( e.target );
            var minValue = parseInt(element.attr('min'));
            // maxValue = parseInt(element.attr('max'));
            var valueCurrent = parseInt(element.val());

            //var key = element.data('key');

            $.post('{{ route('admin.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:qty, variant:variant}, function (data) {

                if(data.product_type==='physical' && data.qty<0)
                {
                    toastr.warning('{{translate("product_quantity_is_not_enough")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.upQty==='zeroNegative')
                {
                    toastr.warning('{{translate("product_quantity_can_not_be_zero_or_less_than_zero_in_cart")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.qty_update==1){
                    toastr.success('{{translate("product_quantity_updated")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                $('#cart').empty().html(data.view);
                view_all_hold_orders('keyup');
            });
        }else{
            var element = $( e.target );
            var minValue = parseInt(element.attr('min'));
            var valueCurrent = parseInt(element.val());

            $.post('{{ route('admin.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:minValue, variant:variant}, function (data) {

                if(data.product_type==='physical' && data.qty<0)
                {
                    toastr.warning('{{translate("product_quantity_is_not_enough")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.upQty==='zeroNegative')
                {
                    toastr.warning('{{translate("product_quantity_can_not_be_zero_or_less_than_zero_in_cart")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                if(data.qty_update==1){
                    toastr.success('{{translate("product_quantity_updated")}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
                $('#cart').empty().html(data.view);
                view_all_hold_orders('keyup');
            });
        }

        // Allow: backspace, delete, tab, escape, enter and .
        if(e.type == 'keydown')
        {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    $('#order_place').submit(function(eventObj) {
        if($('#customer').val())
        {
            $(this).append('<input type="hidden" name="user_id" value="'+$('#customer').val()+'" /> ');
        }
        return true;
    });

</script>
<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>

<script>
    function edit_customer_modal()
    {
        let customer_id = $('#edit-customer-icon').attr('data-customer-id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: "{{route('admin.pos.customer-edit')}}",
            data: {
                _token: '{{csrf_token()}}',
                customer_id: customer_id,
            },
            beforeSend: function () {

            },
            success: function (data) {
                $('#edit-customer-content').empty().html(data.view);
                $('#edit-customer-btn').click();
            },
            complete: function () {

            },
        });
    }
</script>

<script>
    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
        return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
        return null;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (data.text.indexOf(params.term) > -1) {
        var modifiedData = $.extend({}, data, true);
        modifiedData.text += ' (matched)';

        // You can return modified objects from here
        // This includes matching the `children` how you want in nested data sets
        return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    $(".js-example-matcher").select2({
        matcher: matchCustom
    });

    function empty_alert_show() {
        toastr.warning('{{translate("Cart_is_empty")}}!', {
            CloseButton: true,
            ProgressBar: true
        });
    }
</script>
@endpush
