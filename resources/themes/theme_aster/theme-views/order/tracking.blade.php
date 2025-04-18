@extends('theme-views.layouts.app')

@section('title', translate('Track_Order_Result ').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <div class="mt-4">
                        <h4 class="text-center text-uppercase mb-5">{{ translate('Your_order') }} #{{ $orderDetails['id'] }} {{ translate('is') }}
                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                            @else
                                {{translate($orderDetails['order_status'])}}
                            @endif
                        </h4>
                        <div class="row justify-content-center">
                            <div class="col-xl-10">
                                <div id="timeline">
                                    <div
                                        @if($orderDetails['order_status']=='processing')
                                            class="bar progress two"
                                        @elseif($orderDetails['order_status']=='out_for_delivery')
                                            class="bar progress three"
                                        @elseif($orderDetails['order_status']=='delivered')
                                            class="bar progress four"
                                        @else
                                            class="bar progress one"
                                        @endif
                                    ></div>
                                    <div class="state">
                                        <ul>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track1.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="badge active">
                                                    <span>1</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div>
                                                    <div class="state-text">{{translate('Order_placed')}}</div>
                                                    <div class="mt-2 fs-12">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track2.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>2</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div>
                                                    <div class="state-text">{{translate('Packaging_order')}}</div>
                                                    @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                        <div class="mt-2 fs-12">
                                                            @if(\App\CPU\order_status_history($orderDetails['id'],'processing'))
                                                            {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'],'processing')))}}
                                                            @endif
                                                        </div>
                                                    @endif

                                                </div>
                                            </li>

                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track4.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>3</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div class="state-text">{{translate('Order_is_on_the_way')}}</div>
                                                @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                                    <div class="mt-2 fs-12">
                                                        @if(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery'))
                                                            {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                            <li>
                                                <div class="state-img">
                                                    <img width="30" src="{{theme_asset('assets/img/icons/track5.png')}}" class="dark-support" alt="">
                                                </div>
                                                <div class="{{($orderDetails['order_status']=='delivered')?'badge active' : 'badge'}}">
                                                    <span>4</span>
                                                    <i class="bi bi-check"></i>
                                                </div>
                                                <div class="state-text">{{translate('Order_Delivered')}}</div>
                                                @if($orderDetails['order_status']=='delivered')
                                                    <div class="mt-2 fs-12">
                                                        @if(\App\CPU\order_status_history($orderDetails['id'], 'delivered'))
                                                        {{date('d M, Y h:i A',strtotime(\App\CPU\order_status_history($orderDetails['id'], 'delivered')))}}
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 bg-light p-3 p-sm-4">

                            <div class="d-flex justify-content-between">
                                <h5 class="mb-4">{{ translate('order_details') }}</h5>
                                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#order_details">
                                    <span class="media-body hover-primary text-nowrap">{{translate('view_order_details')}}</span>
                                </button>
                            </div>
                            <div class="row gy-3 text-dark track-order-details-info">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('Order_ID') }}</div>

                                            @if(auth('customer')->check())
                                                <div class="fw-bold cursor-pointer" onclick="location.href='{{ route('account-order-details', ['id'=>$orderDetails->id]) }}'">{{ $orderDetails['id'] }}</div>
                                            @else
                                                <div class="fw-bold cursor-pointer" data-bs-toggle="modal" data-bs-target="#loginModal">{{ $orderDetails['id'] }}</div>
                                            @endif
                                        </div>
                                        @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                            <div class="column-2">
                                                <div>{{translate('verification_code')}}</div>
                                                <div class="fw-bold cursor-pointer" >{{ $orderDetails['verification_code'] }}</div>
                                            </div>
                                        @endif
                                        <div class="column-2">
                                            <div>{{ translate('Order_Created_At') }}</div>
                                            <div class="fw-bold">{{date('D, d M, Y ',strtotime($orderDetails['created_at']))}}</div>
                                        </div>
                                        @if($orderDetails->delivery_man_id && $orderDetails['order_status'] !="delivered")
                                        <div class="column-2">
                                            <div>{{ translate('Estimated_Delivery_Date') }}</div>
                                            <div class="fw-bold">
                                                @if($orderDetails['expected_delivery_date'])
                                                {{date('D, d M, Y ',strtotime($orderDetails['expected_delivery_date']))}}
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="column-2">
                                            <div>{{ translate('Order_Status') }}</div>
                                            @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                                                </div>
                                            @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                                </div>
                                            @else
                                                <div class="fw-bold">
                                                    {{translate($orderDetails['order_status'])}}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="column-2">
                                            <div>{{ translate('Payment_Status') }}</div>
                                            @if($orderDetails['payment_status']=="paid")
                                            <div class="fw-bold">{{ translate('paid') }}</div>
                                            @else
                                                <div class="fw-bold">{{ translate('unpaid') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Order Detials Modal  -->

        @php($order = \App\Model\OrderDetail::where('order_id', $orderDetails->id)->get())

        <div class="modal fade" id="order_details" tabindex="-1" aria-labelledby="order_details" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header mx-3 border-0">
                        <div>
                            <h6 class="modal-title fs-5" id="reviewModalLabel">{{translate('order')}} #{{ $orderDetails['id']  }}</h6>

                            @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                <h5 class="small">{{translate('verification_code')}} : {{ $orderDetails['verification_code'] }}</h5>
                            @endif
                        </div>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0 px-sm-4">
                        <div class="product-table-wrap">
                            <div class="table-responsive">
                                <table class="table text-capitalize text-start align-middle">
                                    <thead class="mb-3">
                                        <tr>
                                            <th class="min-w-300 text-nowrap">{{translate('product_details')}}</th>
                                            <th>{{translate('QTY')}}</th>
                                            <th class="text-end text-nowrap">{{translate('sub_total')}}</th>
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
                                                    </div>
                                                    @if($order_details->product && $orderDetails->payment_status == 'paid' && $order_details->product->digital_product_type == 'ready_product')
                                                        <a onclick="digital_product_download('{{ route('digital-product-download', $order_details->id) }}')" href="javascript:"
                                                           class="btn btn-primary btn-sm rounded-pill mb-1"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-placement="bottom"
                                                           data-bs-title="{{translate('Download')}}">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @elseif($order_details->product && $orderDetails->payment_status == 'paid' && $order_details->product->digital_product_type == 'ready_after_sell')
                                                        @if($order_details->digital_file_after_sell)
                                                            <a onclick="digital_product_download('{{ route('digital-product-download', $order_details->id) }}')" href="javascript:"
                                                               class="btn btn-primary btn-sm rounded-pill mb-1"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-placement="bottom"
                                                               data-bs-title="{{translate('Download')}}">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        @else
                                                            <span class="btn btn-success btn-sm mb-1 opacity-half cursor-auto"
                                                                  data-bs-toggle="tooltip"
                                                                  data-bs-placement="bottom"
                                                                  data-bs-title="{{translate('Product_not_uploaded_yet')}}">
                                                                            <i class="bi bi-download"></i>
                                                                        </span>
                                                        @endif
                                                    @endif
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
                                            <th class="text-muted text-nowrap">{{translate('sub_total')}}</th>
                                            @if ($orderDetails['order_type'] == 'default_type')
                                                <th class="text-muted">{{translate('shipping')}}</th>
                                            @endif
                                            <th class="text-muted">{{translate('tax')}}</th>
                                            <th class="text-muted">{{translate('discount')}}</th>
                                            <th class="text-muted text-nowrap">{{translate('coupon_discount')}}</th>
                                            @if ($orderDetails['order_type'] == 'POS')
                                                <th class="text-muted text-nowrap">{{translate('extra_discount')}}</th>
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
        <!-- End Order Details Modal  -->
    </main>
    <!-- End Main Content -->


    <!-- Modal -->
    <div class="modal fade __sign-in-modal" id="digital_product_order_otp_verify" tabindex="-1"
    aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
    </div>

@endsection


@push('script')
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
                   $('#order_details').modal('hide');
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

