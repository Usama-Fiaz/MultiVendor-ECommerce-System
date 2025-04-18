@extends('layouts.front-end.app')

@section('title', translate('track_Order'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
@endpush

@section('content')
    <!-- Order Details Modal-->
    <?php
        $order = \App\Model\OrderDetail::where('order_id', $orderDetails->id)->get();
    ?>
    <div class="modal fade  rtl" id="order-details">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 mx-2">
                    <div>
                        <h5 class="modal-title">{{ translate('order')}} # {{$orderDetails['id']}}</h5>
                        @if ($order_verification_status && $orderDetails->order_type == "default_type")
                            <h5 class="small">{{translate('verification_code')}} : {{ $orderDetails['verification_code'] }}</h5>
                        @endif
                    </div>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pt-0 ">
                    <div class="product-table-wrap">
                        <div class="table-responsive">
                            <table class="table __table text-capitalize text-start table-align-middle min-w400">
                                <thead class="mb-3">
                                    <tr>
                                        <th class="min-w-300">{{translate('product_details')}}</th>
                                        <th>{{translate('QTY')}}</th>
                                        <th class="text-end">{{translate('sub_total')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php( $totalTax = 0)
                                    @php($sub_total=0)
                                    @php($total_tax=0)
                                    @php($total_shipping_cost=0)
                                    @php($total_discount_on_product=0)
                                    @php($extra_discount=0)
                                    @php($coupon_discount=0)
                                    @foreach($order as $key=>$order_details)
                                        @php($productDetails = App\Model\Product::where('id', $order_details->product_id)->first())
                                    <tr>
                                        <td >
                                            <div class="media align-items-center gap-3">
                                                <img class="rounded border"
                                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$productDetails['thumbnail']}}"
                                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" width="100px"                                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$productDetails['thumbnail']}}"
                                                    alt="Image Description">
                                                <div >
                                                    <h6 class="title-color mb-2">{{Str::limit($productDetails['name'],30)}}</h6>
                                                    <div class="d-flex flex-column">
                                                        <small>
                                                            <strong>{{translate('unit_price')}} :</strong>
                                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order_details['price']))}}
                                                            @if ($order_details->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{translate('tax').":".($productDetails->tax)}}{{$productDetails->tax_type ==="percent" ? '%' :''}})
                                                            @endif
                                                        </small>
                                                        @if ($order_details->variant)
                                                            <small><strong>{{translate('variation')}} :</strong> {{$order_details['variant']}}</small>
                                                        @endif
                                                    </div>
                                                    @if($order_details->product && $orderDetails->payment_status == 'paid' && $order_details->product->digital_product_type == 'ready_product')
                                                        <a onclick="digital_product_download('{{ route('digital-product-download', $order_details->id) }}')" href="javascript:" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{translate('download')}}">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    @elseif($order_details->product && $orderDetails->payment_status == 'paid' && $order_details->product->digital_product_type == 'ready_after_sell')
                                                        @if($order_details->digital_file_after_sell)
                                                            <a onclick="digital_product_download('{{ route('digital-product-download', $order_details->id) }}')" href="javascript:" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="{{translate('download')}}">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="btn btn-success disabled" data-toggle="tooltip" data-placement="top" title="{{translate('product_not_uploaded_yet')}}">
                                                                <i class="fa fa-download"></i>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                           {{$order_details->qty}}
                                        </td>
                                        <td class="text-end">
                                            {{\App\CPU\Helpers::currency_converter($order_details['price']*$order_details['qty'])}}
                                        </td>
                                    </tr>
                                        @php($sub_total+=$order_details['price']*$order_details['qty'])
                                        @php($total_tax+=$order_details['tax'])
                                        @php($total_discount_on_product+=$order_details['discount'])
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                    @php($total_shipping_cost=$orderDetails['shipping_cost'])
                    <?php
                        if ($orderDetails['extra_discount_type'] == 'percent') {
                            $extra_discount = ($sub_total / 100) * $orderDetails['extra_discount'];
                        } else {
                            $extra_discount = $orderDetails['extra_discount'];
                        }
                        if(isset($orderDetails['discount_amount'])){
                            $coupon_discount =$orderDetails['discount_amount'];
                        }
                    ?>

                    <div class="bg-light rounded border p3">
                        <div class="table-responsive">
                            <table class="table __table text-end table-align-middle text-capitalize">
                                <thead>
                                    <tr>
                                        <th class="text-muted">{{translate('sub_total')}}</th>
                                        @if ($orderDetails['order_type'] == 'default_type')
                                            <th class="text-muted">{{translate('shipping')}}</th>
                                        @endif
                                        <th class="text-muted">{{translate('tax')}}</th>
                                        <th class="text-muted">{{translate('discount')}}</th>
                                        <th class="text-muted">{{translate('coupon_discount')}}</th>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <th class="text-muted">{{translate('extra_discount')}}</th>
                                        @endif
                                        <th class="text-muted">{{translate('total')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-dark">
                                            {{\App\CPU\Helpers::currency_converter($sub_total)}}
                                        </td>
                                        @if ($orderDetails['order_type'] == 'default_type')
                                            <td class="text-dark">
                                                {{\App\CPU\Helpers::currency_converter($orderDetails['is_shipping_free'] ? $total_shipping_cost-$orderDetails['extra_discount']:$total_shipping_cost)}}
                                            </td>

                                        @endif

                                        <td class="text-dark">
                                            {{\App\CPU\Helpers::currency_converter($total_tax)}}
                                        </td>
                                        <td class="text-dark">
                                            -{{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}
                                        </td>
                                        <td class="text-dark">
                                            - {{\App\CPU\Helpers::currency_converter($coupon_discount)}}
                                        </td>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <td class="text-dark">
                                                - {{\App\CPU\Helpers::currency_converter($extra_discount)}}
                                            </td>
                                        @endif
                                        <td class="text-dark">
                                            {{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-($orderDetails->discount)-$total_discount_on_product - $coupon_discount - $extra_discount)}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container pt-4 pb-5 rtl">
        <!-- Progress-->
        <div class="card border-0 box-shadow-lg">
            <div class="card-body py-5">
                <div class="mx-auto mw-1000">
                    <h2 class="mb-3 text-center text-capitalize">{{ translate('track_order')}}</h2>

                    <form action="{{route('track-order.result')}}" type="submit" method="post" class="p-3">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control prepended-form-control" type="text" name="order_id"
                                    placeholder="{{translate('order_id')}}" value="{{$orderDetails->id}}" required>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <input class="form-control prepended-form-control" type="text" name="phone_number"
                                    placeholder="{{translate('your_phone_number')}}" value="{{ $user_phone }}" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn--primary w-100" type="submit" name="trackOrder">{{translate('track_order')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
                <h6 class="font-weight-bold text-center m-0 pt-5 pb-4">
                    <span class="text-capitalize">{{ translate('your_order')}}</span> <span>:</span> <span class="text-base">{{$orderDetails['id']}}</span>
                </h6>
                <ul class="nav nav-tabs media-tabs nav-justified order-track-info">
                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                        <li class="nav-item">
                            <div class="nav-link active-status">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mx-sm-auto mb-3">
                                        <img src="{{asset('/public/assets/front-end/img/track-order/order-placed.png')}}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_placed')}}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-sm-center gap-1 mt-2">
                                            <img src="{{asset('/public/assets/front-end/img/track-order/clock.png')}}" width="14" alt="">
                                            <span class="text-muted fs-12">{{date('h:i A, d M Y',strtotime($orderDetails->created_at))}}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </li>

                        <li class="nav-item ">
                            <div class="nav-link {{($orderDetails['order_status']=='confirmed') || ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mb-3 mx-sm-auto">
                                        <img src="{{asset('/public/assets/front-end/img/track-order/order-confirmed.png')}}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('order_confirmed')}}</h6>
                                        </div>
                                        @if(($orderDetails['order_status']=='confirmed') || ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered') && \App\CPU\order_status_history($orderDetails['id'],'confirmed'))
                                            <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-1">
                                                <img src="{{asset('/public/assets/front-end/img/track-order/clock.png')}}" width="14" alt="">
                                                <span class="text-muted fs-12">
                                                    {{date('h:i A, d M Y',strtotime(\App\CPU\order_status_history($orderDetails['id'],'confirmed')))}}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="nav-link {{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mb-3 mx-sm-auto">
                                        <img src="{{asset('/public/assets/front-end/img/track-order/shipment.png')}}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 text-capitalize fs-14">{{ translate('preparing_shipment')}}</h6>
                                        </div>
                                        @if( ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')  && \App\CPU\order_status_history($orderDetails['id'],'processing'))
                                            <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                <img src="{{asset('/public/assets/front-end/img/track-order/clock.png')}}" width="14" alt="">
                                                <span class="text-muted fs-12">
                                                    {{date('h:i A, d M Y',strtotime(\App\CPU\order_status_history($orderDetails['id'],'processing')))}}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link {{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mb-3 mx-sm-auto">
                                        <img src="{{asset('/public/assets/front-end/img/track-order/on-the-way.png')}}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_is_on_the_way')}}</h6>
                                        </div>
                                    {{--  --}}
                                        @if( ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                            <div class="d-flex align-items-center justify-content-sm-center mt-1">
                                                <img class="mx-sm-1" src="{{asset('/public/assets/front-end/img/track-order/clock.png')}}" width="20" alt="">
                                                <span class="text-muted fs-14">
                                                    @if(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery'))
                                                        {{date('h:i A, d M Y',strtotime(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                        @if ($orderDetails->delivery_type == 'third_party_delivery')
                                            <div class="mt-1">
                                                <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                    <span class="text-muted fs-14 text-capitalize">{{translate('delivery_service_name')}} : </span> <span class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_service_name}}</span>
                                                </span>
                                                <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                    <span class="text-muted fs-14 text-capitalize"> {{translate('tracking_ID')}} : </span><span class="fs-14 fw-semibold text-dark">{{$orderDetails->third_party_delivery_tracking_id}}</span>
                                                </span>
                                            </div>
                                        @endif
                                        @if ($orderDetails->delivery_type == 'self_delivery' && isset($orderDetails->delivery_man))
                                            <div class="mt-1">
                                                <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                    <span class="text-muted fs-14 text-capitalize">{{translate('delivery_man_name')}} : </span> <span class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_man->f_name.' '.$orderDetails->delivery_man->l_name}}</span>
                                                </span>
                                                <span class="d-flex align-items-center justify-content-sm-center text-nowrap">
                                                    <span class="text-muted fs-14 text-capitalize"> {{translate('contact_number')}} : </span><span class="fs-14 fw-semibold text-dark">{{$orderDetails->delivery_man->phone}}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link {{($orderDetails['order_status']=='delivered')?'active-status' : ''}}">
                                <div class="d-flex flex-sm-column gap-3 gap-sm-0">
                                    <div class="media-tab-media mb-3 mx-sm-auto">
                                        <img src="{{asset('/public/assets/front-end/img/track-order/delivered.png')}}" alt="">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-sm-center">
                                            <h6 class="media-tab-title text-nowrap mb-0 fs-14">{{ translate('order_Shipped')}}</h6>
                                        </div>
                                        @if(($orderDetails['order_status']=='delivered') && \App\CPU\order_status_history($orderDetails['id'],'delivered'))
                                            <div class="d-flex align-items-center justify-content-sm-center mt-2 gap-2">
                                                <img src="{{asset('/public/assets/front-end/img/track-order/clock.png')}}" width="14" alt="">
                                                <span class="text-muted fs-12">
                                                    {{date('h:i A, d M Y',strtotime(\App\CPU\order_status_history($orderDetails['id'],'delivered')))}}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @elseif($orderDetails['order_status']=='returned')
                            <li class="nav-item">
                                <div class="nav-link text-center">
                                    <h1 class="text-warning text-capitalize">{{ translate('product_successfully_returned')}}</h1>
                                </div>
                            </li>
                        @elseif($orderDetails['order_status']=='canceled')
                        <li class="nav-item">
                            <div class="nav-link text-center">
                                <h1 class="text-danger text-capitalize">{{ translate("your_order_has_been_canceled")}}</h1>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="nav-link text-center">
                                <h1 class="text-danger text-capitalize">{{ translate("sorry_we_can_not_complete_your_order")}}</h1>
                            </div>
                        </li>
                    @endif
                </ul>
                <div class="text-center pt-4">
                    <a class="btn btn--primary btn-sm text-capitalize" href="#order-details" data-toggle="modal">{{ translate('view_order_details')}}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="digital_product_order_otp_verify" tabindex="-1"
    aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>

    <script>
        function digital_product_download(link)
        {
            $.ajax({
                type: "GET",
                url: link,
                responseType: 'blob',
                beforeSend: function () {
                    $("#loading").addClass("d-grid");
                },
                success: function (data) {
                    if (data.status == 1 && data.file_path) {
                        const a = document.createElement('a');
                        a.href = data.file_path;
                        a.download = data.file_name;
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(data.file_path);

                    } else if(data.status == 2) {
                        $('#order-details').modal('hide');
                        $('#digital_product_order_otp_verify .modal-body').empty().html(data.view);
                        $('#digital_product_order_otp_verify').modal('show');

                        let new_counter = $(".verifyCounter");
                            let new_seconds = new_counter.data('second');
                            function new_tick() {
                                let m = Math.floor(new_seconds / 60);
                                let s = new_seconds % 60;
                                new_seconds--;
                                new_counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                                if (new_seconds > 0) {
                                    setTimeout(new_tick, 1000);
                                    $('.resend-otp-button').attr('disabled', true);
                                    $(".resend_otp_custom").slideDown();
                                }else {
                                    $('.resend-otp-button').removeAttr('disabled');
                                    $(".verifyCounter").html("0:00");
                                    $(".resend_otp_custom").slideUp();
                                }
                            }
                        new_tick();
                        otp_verify_events();
                    }else if(data.status == 0) {
                        toastr.error(data.message);
                        $('#digital_product_order_otp_verify').modal('hide');
                    }
                },
                error: function () {

                },
                complete: function () {
                    $("#loading").removeClass("d-grid");
                },
            });
        }

        // ==== Start Otp Verification Js ====
        function otp_verify_events()
        {
            $(".otp-form .submit-btn").attr("disabled", true);
            $(".otp-form .submit-btn").addClass("disabled");
            $(".otp-form *:input[type!=hidden]:first").focus();
            let otp_fields = $(".otp-form .otp-field"),
            otp_value_field = $(".otp-form .otp-value");
            otp_fields.on("input", function (e) {
                    $(this).val($(this).val().replace(/[^0-9]/g, ""));
                    let otp_value = "";
                    otp_fields.each(function () {
                        let field_value = $(this).val();
                        if (field_value != "") otp_value += field_value;
                    });
                    otp_value_field.val(otp_value);
                    if (otp_value.length === 4) {
                        $(".otp-form .submit-btn").attr("disabled", false);
                        $(".otp-form .submit-btn").removeClass("disabled");
                    } else {
                        $(".otp-form .submit-btn").attr("disabled", true);
                        $(".otp-form .submit-btn").addClass("disabled");
                    }
                })
                .on("keyup", function (e) {
                    let key = e.keyCode || e.charCode;
                    if (key == 8 || key == 46 || key == 37 || key == 40) {
                        $(this).prev().focus();
                    } else if (key == 38 || key == 39 || $(this).val() != "") {
                        $(this).next().focus();
                    }
                })
                .on("paste", function (e) {
                    let paste_data = e.originalEvent.clipboardData.getData("text");
                    let paste_data_splitted = paste_data.split("");
                    $.each(paste_data_splitted, function (index, value) {
                        otp_fields.eq(index).val(value);
                    });
                });
        }
        // ==== End Otp Verification Js ====

        function download_otp_verify(){
            let formData = $('.digital_product_download_otp_verify');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: formData.attr('action'),
                data: formData.serialize(),
                beforeSend: function () {
                    $("#loading").addClass("d-grid");
                },
                success: function (data) {

                    if (data.status == 1) {
                        $('.verify-message').addClass('text-success').removeClass('text-danger');
                        if(data.file_path){
                            const a = document.createElement('a');
                            a.href = data.file_path;
                            a.download = data.file_name;
                            a.style.display = 'none';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(data.file_path);
                        }
                        $('#digital_product_order_otp_verify').modal('hide');
                    }else{
                        $('.verify-message').addClass('text-danger').removeClass('text-success');
                    }
                    $('.verify-message').html(data.message).fadeIn();
                },
                error: function (error) {

                },
                complete: function () {
                    $("#loading").removeClass("d-grid");
                },
            });
        };

        function download_resend_otp_verify(){
            $('input.otp-field').val('');
            $('.verify-message').fadeOut(300).empty();
            let formData = $('.digital_product_download_otp_verify');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('digital-product-download-otp-reset') }}",
                method: 'POST',
                data: formData.serialize(),
                beforeSend: function () {
                    $("#loading").addClass("d-grid");
                },
                success: function (data) {
                    if (data.status == 1) {
                        // Countdown
                        let new_counter = $(".verifyCounter");
                        let new_seconds = data.new_time;
                        function new_tick() {
                            let m = Math.floor(new_seconds / 60);
                            let s = new_seconds % 60;
                            new_seconds--;
                            new_counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                            if (new_seconds > 0) {
                                setTimeout(new_tick, 1000);
                                $('.resend-otp-button').attr('disabled', true);
                                $(".resend_otp_custom").slideDown();
                            }
                            else {
                                $('.resend-otp-button').removeAttr('disabled');
                                $(".verifyCounter").html("0:00");
                                $(".resend_otp_custom").slideUp();
                            }
                        }
                        new_tick();

                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                complete: function () {
                    $("#loading").removeClass("d-grid");
                },
            });
        };

    </script>
@endpush
