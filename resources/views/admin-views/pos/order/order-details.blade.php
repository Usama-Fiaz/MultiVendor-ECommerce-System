@extends('layouts.back-end.app')

@section('title', translate('order_Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
                {{ translate('order_Details') }}
            </h2>
        </div>

        <!-- End Page Header -->

        <div class="row gx-2 gy-3" id="printableArea">
            <div class="col-lg-8 col-xl-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{translate('order_ID')}} #{{$order['id']}}</h4>
                                <div class="">
                                    <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                                </div>
                            </div>
                            <div class="text-sm-right">
                                <div class="d-flex flex-wrap gap-10 justify-content-sm-end">
                                    <a class="btn btn--primary px-4" target="_blank"
                                    href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                        <img src="{{ asset('public/assets/back-end/img/icons/uil_invoice.svg') }}" alt="" class="mr-1">
                                        {{translate('print_Invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <!-- Order status -->
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('status')}}: </span>
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{translate(str_replace('_',' ',$order['order_status']))}}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('payment_Method')}} :</span>
                                        <strong>  {{translate(str_replace('_',' ',$order['payment_method']))}}</strong>
                                    </div>
                                    @if(isset($order['transaction_ref']) && $order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offline_payments))
                                        <!-- reference-code -->
                                        <div class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{translate('reference_Code')}} :</span>
                                            <strong>{{translate(str_replace('_',' ',$order['transaction_ref']))}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif
                                    <!-- Payment Status -->
                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{translate('payment_Status')}}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success font-weight-bold">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="text-danger font-weight-bold">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </div>
                                    @if(\App\CPU\Helpers::get_business_settings('order_verification') && $order->order_type == "default_type")
                                        <span class="ml-2 ml-sm-3">
                                            <b>
                                                {{translate('order_verification_code')}} : {{$order['verification_code']}}
                                            </b>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('item_details')}}</th>
                                        <th>{{translate('item_price')}}</th>
                                        <th>{{translate('tax')}}</th>
                                        <th>{{translate('item_discount')}}</th>
                                        <th>{{translate('total_price')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @php($item_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($extra_discount=0)
                                @php($product_price=0)
                                @php($total_product_price=0)
                                @php($coupon_discount=0)
                                @foreach($order->details as $key=>$detail)
                                    @if($detail->product_all_status)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <img class="avatar avatar-60 rounded"
                                                            onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail->product_all_status['thumbnail']}}"
                                                            alt="Image Description">
                                                    <div>
                                                        <h6 class="title-color">{{substr($detail->product_all_status['name'],0,30)}}{{strlen($detail->product_all_status['name'])>10?'...':''}}</h6>
                                                        <div><strong>{{translate('qty')}} :</strong> {{$detail['qty']}}</div>
                                                        <div>
                                                            <strong>{{translate('unit_price')}} :</strong>
                                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']+($detail->tax_model =='include' ? $detail['tax']:0)))}}
                                                            @if ($detail->tax_model =='include')
                                                                ({{translate('tax_incl.')}})
                                                            @else
                                                                ({{translate('tax').":".($detail->product_all_status->tax)}}{{$detail->product_all_status->tax_type ==="percent" ? '%' :''}})
                                                            @endif
                                                        </div>
                                                        @if ($detail->variant)
                                                            <div><strong>{{translate('variation')}} :</strong> {{$detail['variant']}}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($detail->product_all_status->digital_product_type == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2" title="File Upload" data-toggle="modal" data-target="#fileUploadModal-{{ $detail->id }}" onclick="modalFocus('fileUploadModal-{{ $detail->id }}')">
                                                        <i class="tio-file-outlined"></i> {{translate('file')}}
                                                    </button>
                                                @endif

                                            </td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']*$detail['qty']))}}</td>
                                            <td>
                                                {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['tax']))}}
                                            </td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</td>
                                            @php($item_price+=$detail['price']*$detail['qty'])
                                            @php($subtotal=$detail['price']*$detail['qty']+$detail['tax']-$detail['discount'])
                                            @php($product_price = $detail['price']*$detail['qty'])
                                            @php($total_product_price+=$product_price)
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                                            @if($detail->product_all_status->product_type == 'digital')
                                                <div class="modal fade" id="fileUploadModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form action="{{ route('admin.pos.digital-file-upload-after-sell') }}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    @if($detail->product_all_status->digital_product_type == 'ready_after_sell' && $detail->digital_file_after_sell)
                                                                        <div class="mb-4">
                                                                            {{translate('uploaded_file')}} :
                                                                            <a href="{{ asset('storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}"
                                                                               class="btn btn-success btn-sm" title="Download" download><i class="tio-download"></i> Download</a>
                                                                        </div>
                                                                    @elseif($detail->product_all_status->digital_product_type == 'ready_product' && $detail->product_all_status->digital_file_ready)
                                                                        <div class="mb-4">
                                                                            {{translate('uploaded_file')}} :
                                                                            <a href="{{ asset('storage/app/public/product/digital-product/'.$detail->product_all_status->digital_file_ready) }}"
                                                                               class="btn btn-success btn-sm" title="Download" download><i class="tio-download"></i> Download</a>
                                                                        </div>
                                                                    @endif

                                                                    @if($detail->product_all_status->digital_product_type == 'ready_after_sell')
                                                                        <input type="file" name="digital_file_after_sell" class="form-control">
                                                                        <div class="mt-1 text-info">{{translate('file_type')}}: jpg, jpeg, png, gif, zip, pdf</div>
                                                                        <input type="hidden" value="{{ $detail->id }}" name="order_id">
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                                                    @if($detail->product_all_status->digital_product_type == 'ready_after_sell')
                                                                        <button type="submit" class="btn btn--primary">{{translate('upload')}}</button>
                                                                    @endif
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </tr>
                                        @php($discount+=$detail['discount'])
                                        @php($tax+=$detail['tax'])
                                        @php($total+=$subtotal)
                                    @endif
                                    @php($sellerId=$detail->seller_id)
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php($shipping=$order['shipping_cost'])
                        <hr>
                        <?php
                            if ($order['extra_discount_type'] == 'percent') {
                                $extra_discount = ($total_product_price / 100) * $order['extra_discount'];
                            } else {
                                $extra_discount = $order['extra_discount'];
                            }
                            if(isset($order['discount_amount'])){
                                $coupon_discount =$order['discount_amount'];
                            }
                        ?>
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-5">{{translate('item_price')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('item_discount')}}</dt>
                                    <dd class="col-6 title-color">
                                        - <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($discount))}}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{translate('extra_discount')}}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($extra_discount))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('sub_total')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price-$discount-$extra_discount))}}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{translate('coupon_discount')}}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-uppercase">{{translate('vat')}}/{{translate('tax')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($tax))}}</strong>
                                    </dd>
                                    <dt class="col-sm-5">{{translate('total')}}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$extra_discount-$coupon_discount))}}</strong>
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4 col-xl-3">
                <!-- Card -->
                <div class="card">

                    <!-- Body -->
                    @if($order->customer)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{translate('customer_information')}}
                            </h4>

                            <div class="media flex-wrap gap-3">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/profile/'.$order->customer->image)}}"
                                        alt="Image">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span class="title-color hover-c1"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong></span>
                                    <span class="title-color">
                                        <strong>{{\App\Model\Order::where('order_type','POS')->where('customer_id',$order['customer_id'])->count()}} </strong>{{translate('orders')}}
                                    </span>
                                    <span class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{translate('no_customer_found')}}</span>
                            </div>
                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="locationModalLabel">{{translate('location_Data')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="__h-400px w-100" id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script>
        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{translate("are_you_sure_change_this")}}?',
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate("yes_change_it")}}!',
                cancelButtonText: '{{ translate("cancel") }}',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            toastr.success('{{translate("status_change_successfully")}}');
                            location.reload();
                        }
                    });
                }
            })
        });

        function order_status(status) {
            @if($order['order_status']=='delivered')
            Swal.fire({
                title: '{{translate("order_is_already_delivered_and_transaction_amount_has_been_disbursed_changing_status_can_be_the_reason_of_miscalculation")}}!',
                text: "{{translate('think_before_you_proceed')}}.",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate("yes_change_it")}}!',
                cancelButtonText: '{{ translate("cancel") }}',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{translate("status_change_successfully")}}!');
                                location.reload();
                            }

                        }
                    });
                }
            })
            @else
            Swal.fire({
                title: '{{translate("are_you_sure_change_this")}}?',
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate("yes_change_it")}}!',
                cancelButtonText: '{{ translate("cancel") }}',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{translate("status_change_successfully")}}!');
                                location.reload();
                            }

                        }
                    });
                }
            })
            @endif
        }
    </script>

    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/admin/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: {
                    'order_id': '{{$order['id']}}',
                    'delivery_man_id': id
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('{{ translate("delivery_man_successfully_assigned_or_changed") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('{{ translate("deliveryman_can_not_assign_or_change_in_that_status") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('{{ translate("add_valid_data") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('{{ translate("only_available_when_order_is_out_for_delivery") }}!', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function waiting_for_location() {
            toastr.warning('{{translate("waiting_for_location")}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

@endpush
