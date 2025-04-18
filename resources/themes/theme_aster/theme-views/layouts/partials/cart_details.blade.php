@php
    $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
    $cart = \App\Model\Cart::where(['customer_id' => (auth('customer')->check() ? auth('customer')->id() : session('guest_id'))])->with(['seller','all_product.category'])->get()->groupBy('cart_group_id');
@endphp
<div class="container">
    <h4 class="text-center mb-3">{{ translate('Cart_List') }}</h4>
    <form action="#">
        <div class="row gy-3">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-30">
                            <ul class="cart-step-list">
                                <li class="current cursor-pointer" onclick="location.href='{{route('shop-cart')}}'"><span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                <li class=" cursor-pointer" onclick="location.href='{{ route('checkout-details') }}'"><span><i class="bi bi-check2"></i></span> {{ translate('Shopping_Details') }}</li>
                                <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                            </ul>
                        </div>
                        @if(count($cart)==0)
                            @php $physical_product = false; @endphp
                        @endif

                        @foreach($cart as $group_key=>$group)
                            @php
                                $physical_product = false;
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical') {
                                        $physical_product = true;
                                    }
                                }
                            @endphp

                            @foreach($group as $cart_key=>$cartItem)
                                @if ($shippingMethod=='inhouse_shipping')
                                        <?php

                                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                                        ?>
                                @else
                                        <?php
                                        if ($cartItem->seller_is == 'admin') {
                                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                        } else {
                                            $seller_shipping = \App\Model\ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                        }
                                        ?>
                                @endif

                                @if($cart_key==0)
                                    @php
                                        $verify_status = \App\CPU\OrderManager::minimum_order_amount_verify($request, $group_key);
                                    @endphp

                                    <div class="bg-primary-light py-2 px-2 px-sm-3 mb-3 mb-sm-4">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                @if($cartItem->seller_is=='admin')
                                                    <a href="{{route('shopView',['id'=>0])}}">
                                                        <h5>
                                                            {{\App\CPU\Helpers::get_business_settings('company_name')}}
                                                        </h5>
                                                    </a>
                                                @else
                                                    <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}">
                                                        <h5>
                                                            {{ \App\CPU\get_shop_name($cartItem['seller_id']) }}
                                                        </h5>
                                                    </a>
                                                @endif

                                                @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                <span class="ps-2 text-danger pulse-button" data-bs-toggle="tooltip" data-bs-placement="right"
                                                    data-bs-custom-class="custom-tooltip" onclick="minimum_Order_Amount_message(this.getAttribute('data-bs-title'))"
                                                    data-bs-title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                                @endif
                                            </div>

                                            @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                @php
                                                    $choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first()
                                                @endphp

                                                @if(isset($choosen_shipping)==false)
                                                    @php $choosen_shipping['shipping_method_id']=0 @endphp
                                                @endif

                                                @php
                                                    $shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is'])
                                                @endphp

                                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                <div class="border bg-white rounded custom-ps-3">
                                                    <div class="shiiping-method-btn d-flex gap-2 p-2 flex-wrap">
                                                        <div class="flex-middle flex-nowrap fw-semibold text-dark gap-2">
                                                            <i class="bi bi-truck"></i>
                                                            {{ translate('Shipping_Method') }}:
                                                        </div>
                                                        <div class="dropdown">
                                                                <button type="button" class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark p-0" data-bs-toggle="dropdown" aria-expanded="false">

                                                                <?php
                                                                    $shippings_title = translate('choose_shipping_method');
                                                                    foreach ($shippings as $shipping) {
                                                                        if ($choosen_shipping['shipping_method_id'] == $shipping['id']) {
                                                                            $shippings_title = ucfirst($shipping['title']).' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost']);
                                                                        }
                                                                    }
                                                                ?>
                                                                {{ $shippings_title }}

                                                                </button>
                                                                <ul class="dropdown-menu dropdown-left-auto" style="--bs-dropdown-min-width: 8rem">
                                                                    @foreach($shippings as $shipping)
                                                                    <li class="cursor-pointer" onclick="set_shipping_id('{{$shipping['id']}}','{{$cartItem['cart_group_id']}}')">
                                                                        {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="table-responsive d-none d-sm-block">
                                @php
                                    $physical_product = false;
                                    foreach ($group as $row) {
                                        if ($row->product_type == 'physical') {
                                            $physical_product = true;
                                        }
                                    }
                                @endphp
                                <table class="table align-middle">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="border-0">{{ translate('product_details') }}</th>
                                        <th class="border-0 text-center">{{ translate('qty') }}</th>
                                        <th class="border-0 text-end">{{ translate('unit_price') }}</th>
                                        <th class="border-0 text-end">{{ translate('discount') }}</th>
                                        <th class="border-0 text-end">{{ translate('total') }}</th>
                                        @if ( $shipping_type != 'order_wise')
                                        <th class="border-0 text-end">{{ translate('shipping_cost') }} </th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($group as $cart_key=>$cartItem)

                                    @php($product = $cartItem->all_product)

                                        <tr>
                                            <td>
                                                <div class="media align-items-center gap-3">
                                                    <div class="avatar avatar-xxl rounded border position-relative overflow-hidden">
                                                        <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                             src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                                             class="dark-support img-fit rounded img-fluid overflow-hidden {{ $product->status == 0?'blur-section':'' }}" alt="">

                                                        @if ($product->status == 0)
                                                        <span class="temporary-closed position-absolute text-center p-2">
                                                            <span>{{ translate('not_Available') }}</span>
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="media-body d-flex gap-1 flex-column {{ $product->status == 0?'blur-section':'' }}">
                                                        <h6 class="text-truncate text-capitalize" style="--width: 20ch">
                                                            <a href="{{ $product->status == 1?route('product',$cartItem['slug']):'javascript:' }}">{{$cartItem['name']}}</a>
                                                        </h6>
                                                        @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                            <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                                        @endforeach
                                                        <div class="fs-12">{{ translate('Unit_Price') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['price']) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($product->status == 1)
                                                    <div class="quantity quantity--style-two d-inline-flex">
                                                        <span class="quantity__minus " onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}')">
                                                            <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                                        </span>
                                                        <input type="text" class="quantity__qty cartQuantity{{ $cartItem['id'] }}" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantity{{$cartItem['id']}}"
                                                            onchange="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                                        <span class="quantity__plus" onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                                            <i class="bi bi-plus"></i>
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="quantity quantity--style-two d-inline-flex">
                                                        <span class="quantity__minus " onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-{{$cartItem['quantity']}}', 'delete')">
                                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                                        </span>
                                                        <input type="hidden" class="quantity__qty cartQuantity{{ $cartItem['id'] }}" value="1" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantity{{$cartItem['id']}}"
                                                        data-min="1">
                                                    </div>
                                                @endif

                                            </td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</td>
                                            <td class="text-end">{{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</td>
                                            <td>
                                                @if ( $shipping_type != 'order_wise')
                                                    {{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                @php($free_delivery_status = \App\CPU\OrderManager::free_delivery_order_amount($group[0]->cart_group_id))

                                @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                <div class="free-delivery-area px-3 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('public/assets/front-end/img/icons/free-shipping.png') }}" alt="" width="40">
                                        @if ($free_delivery_status['amount_need'] <= 0)
                                            <span class="text-muted fs-16">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                        @else
                                            <span class="need-for-free-delivery font-bold">{{ \App\CPU\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                            <span class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                        @endif
                                    </div>
                                    <div class="progress free-delivery-progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $free_delivery_status['percentage'] }}%" aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                @endif
                            </div>



                            <!-- Static Markup -->
                            <div class="d-flex flex-column d-sm-none">
                                @foreach($group as $cart_key=>$cartItem)

                                @php($product = $cartItem->all_product)

                                <div class="border-bottom d-flex align-items-start justify-content-between gap-2 py-2">
                                    <div class="media gap-2">
                                        <div class="avatar avatar-lg rounded border position-relative overflow-hidden">
                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                    src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                                    class="dark-support img-fit rounded img-fluid overflow-hidden {{ $product->status == 0?'blur-section':'' }}" alt="">
                                            @if ($product->status == 0)
                                            <span class="temporary-closed position-absolute text-center p-2">
                                                <span>{{ translate('N/A') }}</span>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="media-body d-flex gap-1 flex-column {{ $product->status == 0?'blur-section':'' }}">
                                            <h6 class="text-truncate text-capitalize" style="--width: 20ch">
                                                <a href="{{route('product',$cartItem['slug'])}}">{{$cartItem['name']}}</a>
                                            </h6>
                                            @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                <div class="fs-12">{{$key1}} : {{$variation}}</div>
                                            @endforeach
                                            <div class="fs-12">{{ translate('Unit_Price') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['price']*$cartItem['quantity']) }}</div>
                                            <div class="fs-12">{{ translate('discount') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</div>
                                            <div class="fs-12">{{ translate('total') }} : {{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</div>
                                            @if ( $shipping_type != 'order_wise')
                                            <div class="fs-12">{{ translate('shipping_cost') }} : {{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="quantity quantity--style-two flex-column d-inline-flex">
                                        @if ($product->status == 1)
                                        <span class="quantity__minus " onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}')">
                                            <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                        </span>
                                        <input type="text" class="quantity__qty cartQuantity{{ $cartItem['id'] }}" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantityMobile{{$cartItem['id']}}"
                                                onchange="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                        <span class="quantity__plus" onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                        @else
                                        <span class="quantity__minus " onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-{{$cartItem['quantity']}}', 'delete')">
                                            <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                        </span>
                                        <input type="hidden" class="quantity__qty cartQuantity{{ $cartItem['id'] }}" value="1" name="quantity[{{ $cartItem['id'] }}]" id="cartQuantityMobile{{$cartItem['id']}}"
                                        data-min="1">
                                        @endif
                                    </div>
                                </div>
                                @endforeach

                                @php($free_delivery_status = \App\CPU\OrderManager::free_delivery_order_amount($group[0]->cart_group_id))

                                @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                <div class="free-delivery-area px-3 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('public/assets/front-end/img/icons/free-shipping.png') }}" alt="" width="40">
                                        @if ($free_delivery_status['amount_need'] <= 0)
                                            <span class="text-muted fs-16">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                        @else
                                            <span class="need-for-free-delivery font-bold">{{ \App\CPU\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                            <span class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                        @endif
                                    </div>
                                    <div class="progress free-delivery-progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $free_delivery_status['percentage'] }}%" aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                @endif

                            </div>

                        @endforeach

                        @if($shippingMethod=='inhouse_shipping')
                            <?php
                                $physical_product = false;
                                foreach($cart as $group_key=>$group){
                                    foreach ($group as $row) {
                                        if ($row->product_type == 'physical') {
                                            $physical_product = true;
                                        }
                                    }
                                }
                            ?>

                            <?php
                                $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                            ?>
                            @if ($shipping_type == 'order_wise' && $physical_product)
                                @php($shippings=\App\CPU\Helpers::get_shipping_methods(1,'admin'))
                                @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                @if(isset($choosen_shipping)==false)
                                    @php($choosen_shipping['shipping_method_id']=0)
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-control text-dark" onchange="set_shipping_id(this.value,'all_cart_group')">
                                            <option>{{ translate('choose_shipping_method')}}</option>
                                            @foreach($shippings as $shipping)
                                                <option
                                                    value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if( $cart->count() == 0)
                            <div class="d-flex justify-content-center align-items-center">
                                <h4 class="text-danger text-capitalize">{{ translate('cart_empty') }}</h4>
                            </div>
                        @endif

                        <form  method="get">
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="phoneLabel" class="form-label input-label">{{translate('order_note')}} <span
                                                class="input-label-secondary">({{translate('Optional')}})</span></label>
                                        <textarea class="form-control w-100" rows="5" id="order_note" name="order_note">{{ session('order_note')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order summery Content -->
            @include('theme-views.partials._order-summery')
        </div>
    </form>
</div>

@push('script')
<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }

    function updateCartQuantityList(minimum_order_qty, key, incr, e) {
        let quantity = parseInt($("#cartQuantity" + key).val())+parseInt(incr);
        let ex_quantity = $("#cartQuantity" + key);
        updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
    }
    function updateCartQuantityListMobile(minimum_order_qty, key, incr, e) {
        let quantity = parseInt($("#cartQuantityMobile" + key).val())+parseInt(incr);
        let ex_quantity = $("#cartQuantityMobile" + key);
        updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
    }

    function updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity) {
        if(minimum_order_qty > quantity && e != 'delete' ) {
            toastr.error('{{translate("minimum_order_quantity_cannot_be_less_than_")}}' + minimum_order_qty);
            $(".cartQuantity" + key).val(minimum_order_qty);
            return false;
        }
        if (ex_quantity.val() == ex_quantity.data('min') && e == 'delete') {
            $.post("{{ route('cart.remove') }}", {
                _token: '{{ csrf_token() }}',
                key: key
            },
            function (response) {
                updateNavCart();
                toastr.info("{{translate('Item_has_been_removed_from_cart')}}", {
                    CloseButton: true,
                    ProgressBar: true
                });
                let segment_array = window.location.pathname.split('/');
                let segment = segment_array[segment_array.length - 1];
                if (segment === 'checkout-payment' || segment === 'checkout-details') {
                    location.reload();
                }
                $('#cart-summary').empty().html(response.data);
            });
        }else{
            $.post('{{route('cart.updateQuantity')}}', {
                _token: '{{csrf_token()}}',
                key,
                quantity
            }, function (response) {
                if (response.status == 0) {
                    toastr.error(response.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $(".cartQuantity" + key).val(response['qty']);
                } else {
                    if (response['qty'] == ex_quantity.data('min')) {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                    } else {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                    }
                    updateNavCart();
                    $('#cart-summary').empty().html(response);
                }
            });
        }
    }


    function checkout() {
        let order_note = $('#order_note').val();
        //console.log(order_note);
        $.post({
            url: "{{route('order_note')}}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,

            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                let url = "{{ route('checkout-details') }}";
                location.href = url;

            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }

    function minimum_Order_Amount_message(data)
    {
        toastr.warning(data, {
            CloseButton: true,
            ProgressBar: true
        });
    }
</script>
@endpush
