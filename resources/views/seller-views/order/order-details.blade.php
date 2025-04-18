@extends('layouts.back-end.app-seller')

@section('title',translate('order_Details'))

@push('css_or_js')
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
                {{translate('order_details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{translate('Order_ID')}}  #{{$order['id']}}</h4>
                                <div class="">
                                    {{date('d M, Y , h:i A',strtotime($order['created_at']))}}
                                </div>
                                @if ($linked_orders->count() >0)
                                    <div class="d-flex flex-wrap gap-10">
                                        <div class="color-caribbean-green-soft font-weight-bold d-flex align-items-center rounded py-1 px-2"> {{translate('linked_orders')}} ({{$linked_orders->count()}}) : </div>
                                        @foreach($linked_orders as $linked)
                                            <a href="{{route('seller.orders.details',[$linked['id']])}}"
                                            class="btn color-caribbean-green text-white rounded py-1 px-2">{{$linked['id']}}</a>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                            <div class="text-sm-right">
                                <div class="d-flex flex-wrap gap-10 justify-content-end">
                                    <!-- order verificaiton button-->
                                    @if (isset($order->verification_images) && $order->verification_status ==1)
                                        <div>
                                            <button class="btn btn--primary px-4" data-toggle="modal" data-target="#order_verification_modal"><i
                                                class="tio-verified"></i> {{translate('order_verification')}}
                                            </button>
                                        </div>
                                    @endif
                                    <!-- order verificaiton button-->
                                    @if (isset($shipping_address['latitude']) && isset($shipping_address['longitude']))
                                    <div class="">
                                        <button class="btn btn--primary px-4" data-toggle="modal" data-target="#locationModal"><i
                                                class="tio-map"></i> {{translate('show_locations_on_map')}}</button>
                                    </div>
                                    @endif

                                    <a class="btn btn--primary px-4" target="_blank"
                                    href="{{route('seller.orders.generate-invoice',[$order['id']])}}">
                                        <i class="tio-print mr-1"></i> {{translate('print__Invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <!-- Order status -->
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('status')}}: </span>

                                        @if($order['order_status']=='pending')
                                        <span class="badge color-caribbean-green-soft font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ', translate($order['order_status']) )}}</span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ', translate('failed_To_Deliver'))}}</span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ', $order['order_status'] == 'processing' ? translate('Packaging') : translate($order['order_status']))}}</span>

                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                        @else
                                            <span class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                        @endif
                                    </div>
                                    <!-- Payment Method -->
                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{translate('payment_Method')}} :</span>
                                        <strong>{{str_replace('_',' ', translate($order['payment_method']))}}</strong>
                                    </div>
                                    <!-- reference-code -->
                                    @if(isset($order['transaction_ref']) && $order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offline_payments))
                                        <div class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{translate('reference_Code')}} :</span>
                                            <strong>{{translate(str_replace('_',' ',$order['transaction_ref']))}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif

                                    <!-- Payment Status -->
                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{translate('payment_Status')}}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success payment-status-span font-weight-bold">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="text-danger payment-status-span font-weight-bold">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </div>

                                    @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                                        <span>
                                            {{translate('order_verification_code')}} : <strong>{{$order['verification_code']}}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>

                            <!-- Order Note -->
                            @if ($order->order_note !=null)
                            <div class="mt-2 mb-5 w-100 d-block">
                                <div class="gap-10">
                                    <h4>{{translate('order_Note')}}:</h4>
                                    <div class="text-justify">{{$order->order_note}}</div>
                                </div>
                            </div>
                            @endif
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
                                @php($total_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)

                                @foreach($order->details as $key=>$detail)
                                    @if($detail->product_all_status)
                                        <tr>
                                            <td>{{ ++$row }}</td>
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

                                            @php($subtotal=$detail['price']*$detail['qty']+$detail['tax']-$detail['discount'])
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                                        </tr>
                                        @php($item_price+=$detail['price']*$detail['qty'])
                                        @php($discount+=$detail['discount'])
                                        @php($tax+=$detail['tax'])
                                        @php($total+=$subtotal)
                                        <!-- End Media -->
                                    @endif
                                    @php($sellerId=$detail->seller_id)

                                    @if(isset($detail->product_all_status->digital_product_type) && $detail->product_all_status->digital_product_type == 'ready_after_sell')
                                        <div class="modal fade" id="fileUploadModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('seller.orders.digital-file-upload-after-sell') }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if($detail->digital_file_after_sell)
                                                                <div class="mb-4">
                                                                    {{translate('uploaded_file')}} :
                                                                    <a href="{{ asset('storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}"
                                                                       class="btn btn-success btn-sm" title="Download" download><i class="tio-download"></i> {{translate('download')}}</a>
                                                                </div>
                                                            @else
                                                                <h4 class="text-center">{{translate('file_not_found')}}!</h4>
                                                            @endif
                                                            <div class="inputDnD form-group input_image" data-title="{{translate('drag_and_drop_file_or_Browse_file')}}">
                                                                <input type="file" name="digital_file_after_sell" class="form-control-file text--primary font-weight-bold" id="inputFile" accept=".jpg, .jpeg, .png, .gif, .zip, .pdf" onchange="readUrl(this)" data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                                            </div>
                                                            <div class="mt-1 text-info">{{translate('file_type')}}: jpg, jpeg, png, gif, zip, pdf</div>
                                                            <input type="hidden" value="{{ $detail->id }}" name="order_id">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                                            <button type="submit" class="btn btn--primary">{{translate('upload')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php($shipping=$order['shipping_cost'])
                        @php($coupon_discount=$order['discount_amount'])
                        <hr />
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row gy-1 text-sm-right">
                                    <dt class="col-5">{{translate('item_price')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('item_discount')}}</dt>
                                    <dd class="col-6 title-color">
                                        - <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($discount))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('sub_total')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price-$discount))}}</strong>
                                    </dd>
                                    <dt class="col-5">{{translate('coupon_discount')}}</dt>
                                    <dd class="col-6 title-color">
                                        - <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-uppercase">{{translate('vat')}}/{{translate('tax')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($tax))}}</strong>
                                    </dd>
                                    <dt class="col-5 text-capitalize">{{translate('delivery_fee')}}</dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</strong>
                                    </dd>

                                    @php($delivery_fee_discount = 0)
                                    @if ($order['is_shipping_free'])
                                        <dt class="col-5">{{translate('delivery_fee_discount')}} ({{ translate($order['free_delivery_bearer']) }} {{translate('bearer')}})</dt>
                                        <dd class="col-6 title-color">
                                            + {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}
                                        </dd>
                                        @php($delivery_fee_discount = $shipping)
                                        @php($total += $delivery_fee_discount)
                                    @endif

                                    @if($order['coupon_discount_bearer'] == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                        <dt class="col-5">{{translate('coupon_discount')}} ({{translate('admin_bearer')}})</dt>
                                        <dd class="col-6 title-color">
                                            + {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}
                                        </dd>
                                        @php($total += $coupon_discount)
                                    @endif

                                    <dt class="col-5"><strong>{{translate('total')}}</strong></dt>
                                    <dd class="col-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$coupon_discount -$delivery_fee_discount))}}</strong>
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
            <div class="col-lg-4 d-flex flex-column gap-3">
                {{-- Payment Information --}}
                @if($order->payment_method == 'offline_payment' && isset($order->offline_payments))
                <div class="card">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                            <h4 class="d-flex gap-2">
                                <img src="{{asset('/public/assets/back-end/img/product_setup.png')}}" alt="" width="20">
                                {{translate('Payment_Information')}}
                            </h4>
                        </div>

                        <div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>{{translate('payment_Method')}}</td>
                                        <td class="py-1 px-2">:</td>
                                        <td><strong>{{ translate($order['payment_method']) }}</strong></td>
                                    </tr>
                                    @foreach (json_decode($order->offline_payments->payment_info) as $key=>$item)
                                        @if (isset($item) && $key != 'method_id')
                                            <tr>
                                                <td>{{translate($key)}}</td>
                                                <td class="py-1 px-2">:</td>
                                                <td><strong>{{ $item }}</strong></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(isset($order->payment_note) && $order->payment_method == 'offline_payment')
                            <div class="payment-status mt-3">
                                <h4>{{translate('payment_Note')}}:</h4>
                                <p class="text-justify">
                                    {{ $order->payment_note }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <!-- End Body -->
                </div>
                @endif

                {{-- Order & Shipping Info Card --}}
                <div class="card">
                    <div class="card-body text-capitalize d-flex flex-column gap-4">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <h4 class="mb-0 text-center">{{translate('order_&_Shipping_Info')}}</h4>
                        </div>

                        <div>
                            <label class="font-weight-bold title-color fz-14">{{translate('change_order_status')}}</label>
                            <select name="order_status" onchange="order_status(this.value)" class="status form-control" data-id="{{$order['id']}}">
                                <option
                                    value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{translate('pending')}}</option>
                                <option
                                    value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{translate('confirmed')}}</option>
                                <option
                                    value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{translate('packaging')}} </option>

                                @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                                @if( $shippingMethod=='sellerwise_shipping')
                                    <option
                                        value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{translate('out_for_delivery')}} </option>
                                    <option
                                        value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{translate('delivered')}} </option>
                                    <option
                                        value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{translate('returned')}}</option>
                                    <option
                                        value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{translate('failed_to_deliver')}} </option>
                                    <option
                                        value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{translate('canceled')}} </option>
                                @endif
                            </select>
                        </div>
                         <!-- Payment Status -->
                         <div class="d-flex justify-content-between align-items-center gap-10 form-control flex-wrap h-100">
                            <span class="title-color">
                                {{translate('payment_status')}}
                            </span>
                            <div class="d-flex justify-content-end min-w-100 align-items-center gap-2">
                                <span class="text--primary font-weight-bold">{{ $order->payment_status=='paid' ? translate('paid'):translate('unpaid')}}</span>
                                <label class="switcher payment-status-text">
                                    <input class="switcher_input payment_status"  type="checkbox" name="status" value="{{$order->payment_status}}"
                                   {{ $order->payment_status=='paid' ? 'checked':''}} >
                                    <span class="switcher_control switcher_control_add"></span>
                                </label>
                            </div>
                        </div>

                        @if($physical_product)
                        <ul class="list-unstyled">
                            @if ($order->shipping_type == 'order_wise')
                            <li>
                                <label class="font-weight-bold title-color fz-14">
                                    {{translate('shipping_method')}} ({{$order->shipping ? $order->shipping->title : translate('no_shipping_method_selected')}})
                                </label>
                            </li>
                            @endif
                            @if ($shipping_method=='sellerwise_shipping')
                            <li>
                                <select class="form-control text-capitalize" name="delivery_type" onchange="choose_delivery_type(this.value)">
                                    <option value="0">
                                        {{translate('choose_delivery_type')}}
                                    </option>

                                    <option value="self_delivery" {{$order->delivery_type=='self_delivery'?'selected':''}}>
                                        {{translate('by_self_delivery_man')}}
                                    </option>
                                    <option value="third_party_delivery" {{$order->delivery_type=='third_party_delivery'?'selected':''}} >
                                        {{translate('by_third_party_delivery_service')}}
                                    </option>
                                </select>
                            </li>
                            <li id="choose_delivery_man" class="mt-3 choose_delivery_man">
                                <label for="" class="font-weight-bold title-color fz-14">
                                    {{translate('delivery_man')}}
                                </label>
                                <select class="form-control text-capitalize js-select2-custom" name="delivery_man_id" onchange="addDeliveryMan(this.value)">
                                    <option
                                        value="0">{{translate('select')}}</option>
                                    @foreach($delivery_men as $deliveryMan)
                                        <option
                                            value="{{$deliveryMan['id']}}" {{$order['delivery_man_id']==$deliveryMan['id']?'selected':''}}>
                                            {{$deliveryMan['f_name'].' '.$deliveryMan['l_name'].' ('.$deliveryMan['phone'].' )'}}
                                        </option>
                                    @endforeach
                                </select>

                                @if (isset($order->delivery_man))
                                    <div class="p-2 bg-light rounded mt-4">
                                        <div class="media m-1 gap-3">
                                            <img class="avatar rounded-circle"
                                                onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                                src="{{asset('storage/app/public/profile/'.isset($order->delivery_man->image) ?? '')}}"
                                                alt="Image">
                                            <div class="media-body">
                                                <h5 class="mb-1">{{ isset($order->delivery_man) ? $order->delivery_man->f_name.' '.$order->delivery_man->l_name :''}}</h5>
                                                <a href="tel:{{isset($order->delivery_man) ? $order->delivery_man->phone : ''}}" class="fz-12 title-color">{{isset($order->delivery_man) ? $order->delivery_man->phone :''}}</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-2 bg-light rounded mt-4">
                                        <div class="media m-1 gap-3">
                                            <img class="avatar rounded-circle"
                                                onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                                src="{{asset('public/assets/back-end/img/delivery-man.png')}}"
                                                alt="Image">
                                            <div class="media-body">
                                                <h5 class="mt-3">{{translate('no_delivery_man_assigned')}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                            @if (isset($order->delivery_man))
                                <li class="choose_delivery_man mt-3">
                                    <label class="font-weight-bold title-color fz-14">
                                        {{translate('deliveryman_will_get')}} ({{ session('currency_symbol') }})
                                    </label>
                                    <input type="number" id="deliveryman_charge" onkeyup="amountDateUpdate(this, event)" value="{{ $order->deliveryman_charge }}" name="deliveryman_charge" class="form-control" placeholder="Ex: 20" required>
                                </li>
                                <li class="choose_delivery_man mt-3">
                                    <label class="font-weight-bold title-color fz-14">
                                        {{translate('expected_delivery_date')}}
                                    </label>
                                    <input type="date" onchange="amountDateUpdate(this, event)" value="{{ $order->expected_delivery_date }}" name="expected_delivery_date" id="expected_delivery_date" class="form-control" required>
                                </li>
                            @endif

                            @endif
                            <li class=" mt-3" id="by_third_party_delivery_service_info">
                                <div class="p-2 bg-light rounded mt-4">
                                    <div class="media m-1 gap-3">
                                        <img class="avatar rounded-circle"
                                            onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                            src="{{asset('public/assets/back-end/img/third-party-delivery.png')}}"
                                            alt="Image">
                                        <div class="media-body">
                                            <h5 class="">{{isset($order->delivery_service_name) ? $order->delivery_service_name :translate('not_assign_yet')}}</h5>
                                            <span class="fz-12 title-color">{{translate('track_ID')}} :  {{$order->third_party_delivery_tracking_id}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>

                <!-- Customer Info Card -->
                @if(!$order->is_guest && $order->customer)
                <div class="card">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                            <h4 class="d-flex gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{translate('customer_information')}}
                            </h4>
                        </div>
                        <div class="media">
                            <div class="mr-3">
                                <img class="avatar rounded-circle avatar-70"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/profile/'.$order->customer->image)}}"
                                    alt="Image">
                            </div>
                            <div class="media-body d-flex flex-column gap-1">
                                <span class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong></span>
                                <span class="title-color">
                                    <strong>{{\App\Model\Order::where('customer_id',$order['customer_id'])->count()}} </strong>
                                    {{translate('orders')}}
                                </span>
                                <span class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                <span class="title-color break-all">{{$order->customer['email']}}</span>
                            </div>
                        </div>
                    </div>
                    <!-- End Body -->
                </div>
                @endif
                <!-- End Card -->

                <!-- Shipping Address Card -->
                @if($physical_product)
                <div class="card">
                    <!-- Body -->
                    @php($shipping_address=json_decode($order['shipping_address_data']))
                    @if($shipping_address)
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                    {{translate('shipping_address')}}
                                </h4>

                                <button class="btn btn-outline-primary btn-sm square-btn" title="Edit" data-toggle="modal" data-target="#shippingAddressUpdateModal">
                                    <i class="tio-edit"></i>
                                </button>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{translate('name')}} :</span>
                                    <strong>{{$shipping_address->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                                </div>
                                <div>
                                    <span>{{translate('contact')}} :</span>
                                    <strong>{{$shipping_address->phone}}</strong>
                                </div>
                                @if ($order->is_guest && $shipping_address->email)
                                <div>
                                    <span>{{translate('email')}} :</span>
                                    <strong>{{$shipping_address->email}}</strong>
                                </div>
                                @endif
                                <div>
                                    <span>{{translate('city')}} :</span>
                                    <strong>{{$shipping_address->city}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('zip_code')}} :</span>
                                    <strong>{{$shipping_address->zip}}</strong>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <!-- <span>{{translate('address')}} :</span> -->
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                                    {{$shipping_address->address}}
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
                @endif
                <!-- End Card -->

                <!-- Billing Address Card -->
                <div class="card">
                    <!-- Body -->

                    @php($billing=json_decode($order['billing_address_data']))
                    @if($billing)
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                                <h4 class="d-flex gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                    {{translate('billing_address')}}
                                </h4>

                                <button class="btn btn-outline-primary btn-sm square-btn" title="Edit" data-toggle="modal" data-target="#billingAddressUpdateModal">
                                    <i class="tio-edit"></i>
                                </button>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{translate('name')}} :</span>
                                    <strong>{{$billing->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                                </div>
                                <div>
                                    <span>{{translate('contact')}} :</span>
                                    <strong>{{$billing->phone}}</strong>
                                </div>
                                @if ($order->is_guest && $billing->email)
                                <div>
                                    <span>{{translate('email')}} :</span>
                                    <strong>{{$billing->email}}</strong>
                                </div>
                                @endif
                                <div>
                                    <span>{{translate('city')}} :</span>
                                    <strong>{{$billing->city}}</strong>
                                </div>
                                <div>
                                    <span>{{translate('zip_code')}} :</span>
                                    <strong>{{$billing->zip}}</strong>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                                    {{$billing->address}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{translate('no_billing_address_found')}}</span>
                            </div>
                        </div>
                    @endif
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Shop Info Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4 d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/shop-information.png')}}" alt="">
                            {{translate('shop_Information')}}
                        </h4>
                        <div class="media">
                            <div class="mr-3">
                                <img class="avatar rounded avatar-70"
                                     src="{{!empty($order->seller->shop) ? asset('storage/app/public/seller/'.auth('seller')->user()->image) : ''}}"
                                     onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'" alt="">
                            </div>
                            @if(!empty($order->seller->shop))
                            <div class="media-body d-flex flex-column gap-2">
                                <h5>{{ $order->seller->shop->name }}</h5>
                                <span class="title-color"><strong>{{ $total_delivered }}</strong> {{translate('orders_Served')}}</span>
                                <span class="title-color"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" class="mt-1" alt="">
                                    {{ $order->seller->shop->address }}
                                </div>
                            </div>
                            @else
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <span>{{translate('no_data_found')}}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

     <!-- order verificaiton modal-->
     @if (isset($order->verification_images))
        <div class="modal fade" id="order_verification_modal" tabindex="-1" aria-labelledby="order_verification_modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-4">
                        <h3 class="mb-0">{{translate('order_verification_images')}}</h3>
                        <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 pt-0">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <div class="row gx-2">
                                @foreach ($order->verification_images as $image)
                                    <div class="col-lg-4 col-sm-6 ">
                                        <div class="mb-2 mt-2 border-1">
                                            <img src="{{asset("storage/app/public/delivery-man/verification-image/".$image->image)}}"
                                            class="w-100"
                                            onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                            >
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('close')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- end order verificaiton modal-->

     <!-- Shipping Address Update Modal -->
     <div class="modal fade" id="shippingAddressUpdateModal" tabindex="-1" aria-labelledby="shippingAddressUpdateModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-4">
                    <h3 class="mb-0 text-center w-100">{{translate('shipping_address')}}</h3>
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <form action="{{route('seller.orders.address-update')}}" method="post">
                        @csrf
                        <div class="d-flex flex-column align-items-center gap-2">
                            <input name="address_type" value="shipping" hidden>
                            <input name="order_id" value="{{$order->id}}" hidden>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{translate('contact_person_name')}}</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{$shipping_address? $shipping_address->contact_person_name : ''}}" placeholder="{{ translate('ex') }}: {{translate('john_doe')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="title-color">{{translate('phone_number')}}</label>
                                        <input type="tel" name="phone_number" id="phone_number" value="{{$shipping_address ? $shipping_address->phone  : ''}}" class="form-control" placeholder="{{ translate('ex') }}:32416436546" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="title-color">{{translate('country')}}</label>
                                        <select name="country" id="country" class="form-control">
                                            @forelse($countries as $country)
                                                <option value="{{ $country['name'] }}" {{ isset($shipping_address) && $country['name'] == $shipping_address->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                            @empty
                                                <option value="">{{ translate('No_country_to_deliver') }}</option>
                                            @endforelse
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city" class="title-color">{{translate('city')}}</label>
                                        <input type="text" name="city" id="city" value="{{$shipping_address ? $shipping_address->city : ''}}" class="form-control" placeholder="{{ translate('ex') }}:{{translate('dhaka')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                        @if($zip_restrict_status == 1)
                                            <select name="zip"  class="form-control" data-live-search="true" required>
                                                @forelse($zip_codes as $code)
                                                    <option value="{{ $code->zipcode }}"{{isset($shipping_address) && $code->zipcode == $shipping_address->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                                @empty
                                                    <option value="">{{ translate('No_zip_to_deliver') }}</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <input type="text" class="form-control" value="{{$shipping_address ? $shipping_address->zip  : ''}}" id="zip" name="zip" placeholder="{{ translate('ex') }}: 1216" {{$shipping_address?'required':''}}>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address" class="title-color">{{translate('address')}}</label>
                                        <textarea name="address" id="address" name="address" rows="3" class="form-control" placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$shipping_address ? $shipping_address->address : ''}}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="latitude"
                                    name="latitude" class="form-control d-inline"
                                    placeholder="{{ translate('Ex') }} : -94.22213" value="{{$shipping_address->latitude ?? 0}}" required readonly>
                                <input type="hidden"
                                    name="longitude" class="form-control"
                                    placeholder="{{ translate('Ex') }} : 103.344322" id="longitude" value="{{$shipping_address->longitude??0}}" required readonly>
                                <!--End -->
                                <div class="col-12 ">
                                    <input id="pac-input" class="form-control rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                    <div class="dark-support rounded w-100 __h-200px mb-5" id="location_map_canvas_shipping"></div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('cancel')}}</button>
                                        <button type="submit" class="btn btn--primary px-5">{{translate('update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    @if($billing)
    <!-- Billing Address Update Modal -->
    <div class="modal fade" id="billingAddressUpdateModal" tabindex="-1" aria-labelledby="billingAddressUpdateModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-4">
                    <h3 class="mb-0 text-center w-100">{{translate('billing_address')}}</h3>
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <form action="{{route('seller.orders.address-update')}}" method="post">
                            @csrf
                            <div class="d-flex flex-column align-items-center gap-2">
                                <input name="address_type" value="billing" hidden>
                                <input name="order_id" value="{{$order->id}}" hidden>
                                <div class="row gx-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="title-color">{{translate('contact_person_name')}}</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{$billing? $billing->contact_person_name : ''}}" placeholder="{{ translate('ex') }}: {{translate('john_doe')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number" class="title-color">{{translate('phone_number')}}</label>
                                            <input type="tel" name="phone_number" id="phone_number" value="{{$billing ? $billing->phone  : ''}}" class="form-control" placeholder="{{ translate('ex') }}:32416436546" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country" class="title-color">{{translate('country')}}</label>
                                            <select name="country" id="country" class="form-control">
                                                @forelse($countries as $country)
                                                    <option value="{{ $country['name'] }}" {{ isset($billing) && $country['name'] == $billing->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                                @empty
                                                    <option value="">{{ translate('No_country_to_deliver') }}</option>
                                                @endforelse
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city" class="title-color">{{translate('city')}}</label>
                                            <input type="text" name="city" id="city" value="{{$billing ? $billing->city : ''}}" class="form-control" placeholder="{{ translate('ex') }}:{{translate('dhaka')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                            @if($zip_restrict_status == 1)
                                                <select name="zip"  class="form-control" data-live-search="true" required>
                                                    @forelse($zip_codes as $code)
                                                        <option value="{{ $code->zipcode }}"{{isset($billing) && $code->zipcode == $billing->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                                    @empty
                                                        <option value="">{{ translate('no_zip_to_deliver') }}</option>
                                                    @endforelse
                                                </select>
                                            @else
                                                <input type="text" class="form-control" value="{{$billing ? $billing->zip  : ''}}" id="zip" name="zip" placeholder="{{ translate('ex') }}: 1216" {{$billing?'required':''}}>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="address" class="title-color">{{translate('address')}}</label>
                                            <textarea name="address" id="billing_address"  rows="3" class="form-control" placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$billing ? $billing->address : ''}}</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" id="billing_latitude"
                                        name="latitude" class="form-control d-inline"
                                        placeholder="{{ translate('ex') }} : -94.22213" value="{{$billing->latitude ?? 0}}" required readonly>
                                    <input type="hidden"
                                        name="longitude" class="form-control"
                                        placeholder="{{ translate('ex') }} : 103.344322" id="billing_longitude" value="{{$billing->longitude ?? 0}}" required readonly>
                                    <!--End -->
                                    <div class="col-12 ">
                                        <!-- search -->
                                        <input id="billing-pac-input" class="form-control rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                        <!-- search -->
                                        <div class="rounded w-100 __h-200px mb-5" id="location_map_canvas_billing"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-3">
                                            <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('cancel')}}</button>
                                            <button type="submit" class="btn btn--primary px-5">{{translate('update')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    @endif


    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="locationModalLabel">{{translate('location_data')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="w-100 h-400" id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

   <!-- Show third party delivery info Modal -->
   <div class="modal" id="third_party_delivery_service_modal" role="dialog" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_third_party_delivery_info')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('seller.orders.update-deliver-info')}}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order['id']}}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{translate('delivery_service_name')}}</label>
                                    <input class="form-control" type="text" name="delivery_service_name" value="{{$order['delivery_service_name']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('tracking_id')}} ({{translate('optional')}})</label>
                                    <input class="form-control" type="text" name="third_party_delivery_tracking_id" value="{{$order['third_party_delivery_tracking_id']}}" id="">
                                </div>
                                <button class="btn btn--primary" type="submit">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection
@push('script')
    <script>
        $(document).on('click', '.payment_status', function (e) {
            e.preventDefault();
            var id = {{$order->id}};
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
                if(value == 'paid'){
                    value = 'unpaid'
                }else{
                    value = 'paid'
                }
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {

                            if(data.customer_status==0)
                            {
                                location.reload();
                                toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                            }else
                            {
                                location.reload();
                                toastr.success('{{translate("status_change_successfully")}}');
                            }
                        }
                    });
                }
            })
        });

        function order_status(status) {
            var value = status;
            Swal.fire({
                title: '{{translate("are_you_sure_change_this")}}?',
                text: "{{translate('you_wont_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate("yes_change_it")}}',
                cancelButtonText: '{{ translate("cancel") }}',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": value
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}}!!');
                                 location.reload();
                            } else {
                                if(data.payment_status == 0){
                                    toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
                                     location.reload();
                                }else if(data.customer_status==0)
                                {
                                    toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                                     location.reload();
                                }else{
                                    toastr.success('{{translate("status_change_successfully")}}!');
                                     location.reload();
                                }
                            }
                        }
                    });
                }
            })
        }
    </script>
<script>
    $( document ).ready(function() {
        let delivery_type = '{{$order->delivery_type}}';

        if(delivery_type === 'self_delivery'){
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        }else if(delivery_type === 'third_party_delivery')
        {
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').show();
        }else{
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();
        }
    });
</script>
<script>
    function choose_delivery_type(val)
    {

        if(val==='self_delivery')
        {
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        }else if(val==='third_party_delivery'){
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').show();
            $('#third_party_delivery_service_modal').modal("show");
        }else{
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();
        }

    }
</script>
    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/seller/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: {
                    'order_id': '{{$order['id']}}',
                    'delivery_man_id': id
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('{{ translate("delivery_man_successfully_assigned/changed") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        location.reload();
                    } else {
                        toastr.error('{{ translate("deliveryman_man_can_not_assign/change_in_that_status") }}', {
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

        function waiting_for_location() {
            toastr.warning('{{translate("waiting_for_location")}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function amountDateUpdate(t, e){
            let field_name = $(t).attr('name');
            let field_val = $(t).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.orders.amount-date-update')}}",
                method: 'POST',
                data: {
                    'order_id': '{{$order['id']}}',
                    'field_name': field_name,
                    'field_val': field_val
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('{{ translate("deliveryman_charge_add_successfully") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('{{ translate("failed_to_add_deliveryman_charge") }}', {
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
    </script>
    <!--shipping address map -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&callback=map_callback_fucntion&libraries=places&v=3.49" defer></script>
    <script>
        /* shipping address  map */
        function initAutocomplete() {
            var myLatLng = { lat: {{$shipping_address->latitude??'-33.8688'}}, lng: {{$shipping_address->longitude??'151.2195'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas_shipping"), {
                center: { lat: {{$shipping_address->latitude??'-33.8688'}}, lng: {{$shipping_address->longitude??'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };

        $(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });
        /* end shipping address map*/

        /* billing address  map */
        function billing_map() {
            var myLatLng = { lat: {{$billing->latitude??'-33.8688'}}, lng: {{$billing->longitude??'151.2195'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas_billing"), {
                center: { lat: {{$billing->latitude??'-33.8688'}}, lng: {{$billing->longitude??'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('billing_latitude').value = coordinates['lat'];
                document.getElementById('billing_longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('billing_address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("billing-pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };

        $(document).on("keydown", "input", function(e) {
            if (e.which==13) e.preventDefault();
        });

        /* end billing address map  */

        /* show location map */
        function show_location_map(){
            var myLatLng = { lat: {{$shipping_address->latitude??'null'}}, lng: {{$shipping_address->longitude??'null'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: {{$shipping_address->latitude??'null'}}, lng: {{$shipping_address->longitude??'null'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            @if($shipping_address && isset($shipping_address))
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng({{$shipping_address->latitude}}, {{$shipping_address->longitude}}),
                map: map,
                title: "{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}",
                icon: "{{asset('public/assets/front-end/img/customer_location.png')}}"
            });

            google.maps.event.addListener(marker, 'click', (function (marker) {
                return function () {
                    infowindow.setContent("<div class='float-left'><img class='__inline-5' src='{{asset('storage/app/public/profile/')}}{{$order->customer->image??""}}'></div><div class='float-right __p-10'><b>{{$order->customer->f_name??""}} {{$order->customer->l_name??""}}</b><br/>{{$shipping_address->address??""}}</div>");
                    infowindow.open(map, marker);
                }
            })(marker));
            locationbounds.extend(marker.getPosition());
            @endif
            google.maps.event.addListenerOnce(map, 'idle', function () {
                map.fitBounds(locationbounds);
            });

        }
        /*End Show location on map*/

        function map_callback_fucntion(){
            initAutocomplete();
            billing_map();
            show_location_map();
        }

        function readUrl(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    let imgData = e.target.result;
                    let imgName = input.files[0].name;
                    input.setAttribute("data-title", imgName);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
