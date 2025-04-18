<h3 class="mt-4 mb-3 text-center text-lg-left mobile-fs-20">{{ translate('shopping_cart')}}</h3>

@php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
@php($cart=\App\Model\Cart::where(['customer_id' => (auth('customer')->check() ? auth('customer')->id() : session('guest_id'))])->get()->groupBy('cart_group_id'))

<div class="row g-3 mx-max-md-0">
    <!-- List of items-->
    <section class="col-lg-8 px-max-md-0">
        @if(count($cart)==0)
            @php($physical_product = false)
        @endif
            <!-- for web -->
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table __cart-table">
                    <thead class="thead-light">
                        <tr class="">
                            <th class="font-weight-bold __w-45">
                                <div class="pl-3">
                                    {{translate('product')}}
                                </div>
                            </th>
                            <th class="font-weight-bold pl-0 __w-15p text-capitalize">{{translate('unit_price')}}</th>
                            <th class="font-weight-bold __w-15p">
                                <span class="pl-3">{{translate('qty')}}</span>
                            </th>
                            <th class="font-weight-bold __w-15p text-end">
                                <div class="pr-3">
                                    {{translate('total')}}
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
                @foreach($cart as $group_key=>$group)
                    <div class="card __card cart_information __cart-table mb-3">
                        <?php
                            $physical_product = false;
                            $total_shipping_cost = 0;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical') {
                                    $physical_product = true;
                                }
                                if ($row->product_type == 'physical' && $row->shipping_type != "order_wise") {
                                    $total_shipping_cost += $row->shipping_cost;
                                }
                            }

                        ?>

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
                                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 px-0">
                                    @php($verify_status = \App\CPU\OrderManager::minimum_order_amount_verify($request, $group_key))
                                    @if($cartItem->seller_is=='admin')
                                        <a href="{{route('shopView',['id'=>0])}}" class="text-primary d-flex align-items-center gap-2">
                                            <img src="{{asset('public/assets/front-end/img/cart-store.png')}}" alt="">
                                            {{\App\CPU\Helpers::get_business_settings('company_name')}}
                                            @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                <span class="pl-1 text-danger pulse-button" data-toggle="tooltip" data-placement="right"
                                                    onclick="minimum_Order_Amount_message(this.getAttribute('data-title'))"
                                                    data-title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif" title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif">
                                                    <i class="czi-security-announcement"></i>
                                                </span>
                                            @endif
                                        </a>
                                    @else
                                        <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}" class="text-primary d-flex align-items-center gap-2">
                                            <img src="{{asset('public/assets/front-end/img/cart-store.png')}}" alt="">
                                            {{\App\Model\Shop::where(['seller_id'=>$cartItem['seller_id']])->first()->name}}
                                            @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                <span class="pl-1 text-danger pulse-button" data-toggle="tooltip" data-placement="right"
                                                    onclick="minimum_Order_Amount_message(this.getAttribute('data-title'))"
                                                    data-title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif" title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif">
                                                    <i class="czi-security-announcement"></i>
                                                </span>
                                            @endif
                                        </a>
                                    @endif

                                @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                <!--  shipping dropdown -->
                                <div class=" bg-white select-method-border rounded">
                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                    @if(isset($choosen_shipping)==false)
                                        @php($choosen_shipping['shipping_method_id']=0)
                                    @endif
                                    @php( $shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is']))
                                        @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')

                                        <div class="d-sm-flex">
                                            @isset($choosen_shipping['shipping_cost'])
                                                <div class="text-sm-nowrap mx-sm-2 mt-sm-2 mb-1">
                                                    <span class="font-weight-bold">{{translate('shipping_cost')}}</span> :<span>{{App\CPU\Helpers::currency_converter($choosen_shipping['shipping_cost'])}}</span>
                                                </div>
                                            @endisset

                                            <!-- chosen shipping method-->
                                            <select class="form-control fs-13 font-weight-bold text-capitalize border-aliceblue max-240px" onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                                <option>{{translate('choose_shipping_method')}}</option>
                                                @foreach($shippings as $shipping)
                                                    <option value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                        {{translate('shipping_method')}} : {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                @else
                                    @if ($shipping_type != 'order_wise')
                                        <div class="">
                                            <span class="font-weight-bold">{{translate('total_shipping_cost')}}</span> : <span>{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}</span>
                                        </div>
                                    @elseif($shipping_type == 'order_wise' && $choosen_shipping)
                                        <div class="">
                                            <span class="font-weight-bold">{{translate('total_shipping_cost')}}</span> : <span>{{\App\CPU\Helpers::currency_converter($choosen_shipping->shipping_cost)}}</span>
                                        </div>
                                    @endif
                                @endif

                                </div>
                                <!-- end shipping dropdown -->
                                </div>
                            @endif
                        @endforeach
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table __cart-table">
                            <tbody>
                            <?php
                                $physical_product = false;
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical') {
                                        $physical_product = true;
                                    }
                                }
                            ?>
                            @foreach($group as $cart_key=>$cartItem)
                            @php($product = $cartItem->all_product)
                            @php($product_status = $cartItem->all_product->status)
                                <tr>
                                    <td class="__w-45">
                                        <div class="d-flex gap-3">
                                            <div class="">
                                                <a href="{{ $product_status == 1 ? route('product',$cartItem['slug']) : 'javascript:'}}" class="position-relative overflow-hidden">
                                                    <img class="rounded __img-62 {{ $product_status == 0?'blur-section':'' }}"
                                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                                            alt="Product">
                                                    @if ($product_status == 0)
                                                        <span class="temporary-closed position-absolute text-center p-2">
                                                            <span>{{ translate('N/A') }}</span>
                                                        </span>
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="text-break __line-2 __w-18rem {{ $product_status == 0?'blur-section':'' }}">
                                                    <a href="{{ $product_status == 1 ? route('product',$cartItem['slug']) : 'javascript:'}}">{{$cartItem['name']}}</a>
                                                </div>

                                                <div class="d-flex flex-wrap gap-2 {{ $product_status == 0?'blur-section':'' }}">
                                                    @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                                        <div class="">
                                                            <span class="__text-12px text-capitalize">
                                                                <span class="text-muted">{{$key1}} </span> : <span class="fw-semibold">{{$variation}}</span>
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if ( $shipping_type != 'order_wise')
                                                    <div class="d-flex flex-wrap gap-2 {{ $product_status == 0?'blur-section':'' }}">
                                                        <span class="fw-semibold"> {{translate('shipping_cost')}}</span>:<span>{{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="{{ $product_status == 0?'blur-section':'' }} __w-15p">
                                        <div class="text-center">
                                            <div class="fw-semibold">{{ \App\CPU\Helpers::currency_converter($cartItem['price']-$cartItem['discount']) }}</div>
                                            <span class="text-nowrap fs-10">
                                                @if ($cartItem->tax_model === "exclude")
                                                    ({{translate('tax')}} : {{\App\CPU\Helpers::currency_converter($cartItem['tax']*$cartItem['quantity'])}})
                                                @else
                                                    ({{translate('tax_included')}})
                                                @endif
                                             </span>
                                        </div>
                                    </td>
                                    <td class="__w-15p text-center">
                                        <!-- Qty update -->
                                        @php($minimum_order=\App\CPU\ProductManager::get_product($cartItem['product_id']))
                                        @if ($product_status == 1)
                                            <div class="qty d-flex justify-content-center align-itmes-center gap-3">
                                                <span class="qty_minus " onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}')">
                                                    <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'tio-delete text-danger' : 'tio-remove' }}"></i>
                                                </span>
                                                <input type="text" class="qty_input cartQuantity{{ $cartItem['id'] }}" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cart_quantity_web{{$cartItem['id']}}"
                                                    onchange="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                <span class="qty_plus" onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                                    <i class="tio-add"></i>
                                                </span>
                                            </div>
                                        @else
                                        <div class="qty d-flex justify-content-center align-itmes-center gap-3">
                                            <span onclick="updateCartQuantityList('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-{{$cartItem['quantity']}}', 'delete')">
                                                <i class="tio-delete text-danger" data-toggle="tooltip" data-title="{{translate('product_not_available_right_now')}}"></i>
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="__w-15p text-end {{ $product_status == 0?'blur-section':'' }}">
                                        <div>
                                            {{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- free delivery section -->
                        @php($free_delivery_status = \App\CPU\OrderManager::free_delivery_order_amount($group[0]->cart_group_id))
                        @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                            <div class="free-delivery-area px-3 mb-3 mb-lg-2">
                                <div class="d-flex align-items-center gap-8">
                                    <img class="__w-30px" src="{{ asset('public/assets/front-end/img/icons/free-shipping.png') }}" alt="" >
                                    @if ($free_delivery_status['amount_need'] <= 0)
                                        <span class="text-muted fs-12 mt-1">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                    @else
                                    <span class="need-for-free-delivery font-bold fs-12 mt-1 text-primary">{{ \App\CPU\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                    <span class="text-muted fs-12 mt-1">{{ translate('add_more_for_free_delivery') }}</span>
                                    @endif
                                </div>
                                <div class="progress free-delivery-progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $free_delivery_status['percentage'] }}%" aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endif
                        <!-- end of free delivery section -->
                    </div>
                @endforeach
            </div>
            <!-- end web -->
            <!-- Mobile -->
            @foreach($cart as $group_key=>$group)
            <div class="cart_information mb-3 pb-3 w-100 d-lg-none">
                <?php
                    $physical_product = false;
                    $total_shipping_cost = 0;
                    foreach ($group as $row) {
                        if ($row->product_type == 'physical') {
                            $physical_product = true;
                        }
                        if ($row->product_type == 'physical' && $row->shipping_type != "order_wise") {
                            $total_shipping_cost += $row->shipping_cost;
                        }
                    }

                ?>

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
                        <div class="card-header d-flex flex-column gap-2 border-0 justify-content-between align-items-center">
                            @php($verify_status = \App\CPU\OrderManager::minimum_order_amount_verify($request, $group_key))
                            @if($cartItem->seller_is=='admin')
                                <a href="{{route('shopView',['id'=>0])}}" class="text-primary d-flex align-items-center gap-2">
                                    <img src="{{asset('public/assets/front-end/img/cart-store.png')}}">
                                    {{\App\CPU\Helpers::get_business_settings('company_name')}}
                                    @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                        <span class="pl-1 text-danger pulse-button" data-toggle="tooltip" data-placement="right"
                                            onclick="minimum_Order_Amount_message(this.getAttribute('data-title'))"
                                            data-title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif" title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif">
                                            <i class="czi-security-announcement"></i>
                                        </span>
                                    @endif
                                </a>
                            @else
                                <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}" class="text-primary d-flex align-items-center gap-2">
                                    <img src="{{asset('public/assets/front-end/img/cart-store.png')}}">
                                    {{\App\Model\Shop::where(['seller_id'=>$cartItem['seller_id']])->first()->name}}
                                    @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                        <span class="pl-1 text-danger pulse-button" data-toggle="tooltip" data-placement="right"
                                            onclick="minimum_Order_Amount_message(this.getAttribute('data-title'))"
                                            data-title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif" title="{{ translate('minimum_Order_Amount') }} {{ \App\CPU\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{\App\CPU\Helpers::get_business_settings('company_name')}} @else {{ \App\CPU\get_shop_name($cartItem['seller_id']) }} @endif">
                                            <i class="czi-security-announcement"></i>
                                        </span>
                                    @endif
                                </a>
                            @endif


                            <!--  shipping dropdown -->
                            <div class=" bg-white select-method-border rounded">
                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                    @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())
                                    @if(isset($choosen_shipping)==false)
                                        @php($choosen_shipping['shipping_method_id']=0)
                                    @endif
                                    @php( $shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is']))
                                        @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')

                                            <div class="d-sm-flex">
                                            <!-- choosen shipping method-->
                                                <select class="form-control fs-13 font-weight-bold text-capitalize border-aliceblue max-240px" onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                                    <option>{{translate('choose_shipping_method')}}</option>
                                                    @foreach($shippings as $shipping)
                                                        <option value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                            {{translate('shipping_method')}} : {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @isset($choosen_shipping['shipping_cost'])
                                                <div class="text-sm-nowrap mt-2 text-center fs-12">
                                                    <span class="font-weight-bold">{{translate('shipping_cost')}}</span> :<span>{{App\CPU\Helpers::currency_converter($choosen_shipping['shipping_cost'])}}</span>
                                                </div>
                                            @endisset
                                        @endif
                                @else
                                    @if ($shipping_type != 'order_wise')
                                        <div class="text-sm-nowrap text-center fs-12">
                                            <span class="font-weight-bold">{{translate('total_shipping_cost')}}</span> : <span>{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}</span>
                                        </div>
                                    @elseif($shipping_type == 'order_wise' && $choosen_shipping)
                                        <div class="text-sm-nowrap text-center fs-12">
                                            <span class="font-weight-bold">{{translate('total_shipping_cost')}}</span> : <span>{{\App\CPU\Helpers::currency_converter($choosen_shipping->shipping_cost)}}</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <!-- end shipping dropdown -->
                        </div>
                    @endif
                @endforeach

                <?php
                    $physical_product = false;
                    foreach ($group as $row) {
                        if ($row->product_type == 'physical') {
                            $physical_product = true;
                        }
                    }
                ?>
                @foreach($group as $cart_key=>$cartItem)
                @php($product = $cartItem->all_product)
                @php($product_status = $cartItem->all_product->status)
                    <div class="d-flex justify-content-between gap-3 p-3 fs-12  {{count($group)-1 == $cart_key ? '' :'border-bottom border-aliceblue'}}">
                        <div class="d-flex gap-3">
                            <div class="">
                                <a href="{{ $product_status == 1 ? route('product',$cartItem['slug']) : 'javascript:'}}" class="position-relative overflow-hidden">
                                    <img class="rounded __img-48 {{ $product_status == 0?'blur-section':'' }}"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"
                                            alt="Product">
                                    @if ($product_status == 0)
                                        <span class="temporary-closed position-absolute text-center p-2">
                                            <span>{{ translate('N/A') }}</span>
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <div class="text-break __line-2 {{ $product_status == 0?'blur-section':'' }}">
                                    <a href="{{ $product_status == 1 ? route('product',$cartItem['slug']) : 'javascript:'}}">{{$cartItem['name']}}</a>
                                </div>

                                <div class="d-flex flex-wrap column-gap-2 {{ $product_status == 0?'blur-section':'' }}">
                                    @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                        <div class="">
                                            <span class="__text-12px text-capitalize">
                                               <span class="text-muted"> {{$key1}} </span> : <span class="fw-semibold">{{$variation}}</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-wrap column-gap-2">
                                    <div class="text-nowrap text-muted">{{translate('unit_price')}} :</div>
                                    <div class="text-start d-flex gap-1 flex-wrap">
                                        <div class="fw-semibold">{{ \App\CPU\Helpers::currency_converter($cartItem['price']-$cartItem['discount']) }}</div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <div class="text-nowrap text-muted">{{translate('total')}} :</div>
                                    <div class="fw-semibold">
                                        {{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}

                                    </div>
                                    <span class="text-nowrap fs-10 mt-1px">
                                        @if ($cartItem->tax_model === "exclude")
                                            ({{translate('tax')}} : {{\App\CPU\Helpers::currency_converter($cartItem['tax']*$cartItem['quantity'])}})
                                        @else
                                            ({{translate('tax_included')}})
                                        @endif
                                    </span>
                                </div>
                                @if ( $shipping_type != 'order_wise')
                                    <div class="d-flex flex-wrap gap-2 {{ $product_status == 0?'blur-section':'' }}">
                                        <span class="text-muted"> {{translate('shipping_cost')}}</span>:<span class="fw-semibold">{{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <!-- Qty update -->
                            @php($minimum_order=\App\CPU\ProductManager::get_product($cartItem['product_id']))
                            @if ($product_status == 1)
                                <div class="qty d-flex flex-column align-items-center gap-3">
                                    <span class="qty_plus" onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '1')">
                                        <i class="tio-add"></i>
                                    </span>
                                    <input type="number" class="qty_input cartQuantity{{ $cartItem['id'] }}" value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]" id="cart_quantity_mobile{{$cartItem['id']}}"
                                    onchange="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '0')" data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <span class="qty_minus " onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-1', '{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}')">
                                        <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'tio-delete text-danger' : 'tio-remove' }}"></i>
                                    </span>
                                </div>
                            @else
                            <div class="qty d-flex flex-column align-items-center gap-3">
                                <span class="" onclick="updateCartQuantityListMobile('{{ $product->minimum_order_qty }}', '{{$cartItem['id']}}', '-{{$cartItem['quantity']}}', 'delete')">
                                    <i class="tio-delete text-danger" data-toggle="tooltip" data-title="{{translate('product_not_available_right_now')}}"></i>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- free delivery section -->
                @php($free_delivery_status = \App\CPU\OrderManager::free_delivery_order_amount($group[0]->cart_group_id))
                @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                    <div class="free-delivery-area px-3 mb-3 mb-lg-2">
                        <div class="d-flex align-items-center gap-8">
                            <img class="__w-30px" src="{{ asset('public/assets/front-end/img/icons/free-shipping.png') }}" alt="" >
                            @if ($free_delivery_status['amount_need'] <= 0)
                                <span class="text-muted fs-12 mt-1">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                            @else
                            <span class="need-for-free-delivery font-bold fs-12 mt-1 text-primary">{{ \App\CPU\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                            <span class="text-muted fs-12 mt-1">{{ translate('add_more_for_free_delivery') }}</span>
                            @endif
                        </div>
                        <div class="progress free-delivery-progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $free_delivery_status['percentage'] }}%" aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @endif
                <!-- end of free delivery section -->
            </div>
        @endforeach
        <!-- End Mobile -->


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
                    <div class="px-3 px-md-0 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <select class="form-control border-aliceblue" onchange="set_shipping_id(this.value,'all_cart_group')">
                                    <option>{{translate('choose_shipping_method')}}</option>
                                    @foreach($shippings as $shipping)
                                        <option
                                            value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                            {{translate('shipping_method')}} : {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if( $cart->count() == 0)
            <div class="card mb-4">
                <div class="card-body py-5">
                    <div class="py-md-4">
                        <div class="text-center text-capitalize">
                            <img class="mb-3 mw-100" src="{{asset('/public/assets/front-end/img/icons/empty-cart.svg')}}" alt="">
                            <p class="text-capitalize">{{translate('Your_Cart_is_Empty')}}!</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif


        <div class="px-3 px-md-0 mt-3 mt-md-0">
            <form  method="get">
                <div class="mb-lg-3">
                    <div class="row">
                        <div class="col-12">
                            <label for="phoneLabel" class="form-label input-label">{{translate('order_note')}} <span
                                                class="input-label-secondary">({{translate('optional')}})</span></label>
                            <textarea class="form-control w-100 border-aliceblue" id="order_note" name="order_note">{{ session('order_note')}}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Sidebar-->
    @include('web-views.partials._order-summary')


</div>

@push('script')
    <script>
         function updateCartQuantityList(minimum_order_qty, key, incr, e) {
            let quantity_id = 'cart_quantity_web';
            updateCartCommon(minimum_order_qty, key,incr,e, quantity_id);
        }

        function updateCartQuantityListMobile(minimum_order_qty, key, incr, e) {
            let quantity_id = 'cart_quantity_mobile';
            updateCartCommon(minimum_order_qty, key,incr,e, quantity_id);
        }

         function updateCartCommon(minimum_order_qty, key,incr,e,quantity_id) {
            let quantity = parseInt($("#"+quantity_id+ key).val())+parseInt(incr);
            let ex_quantity = $("#"+quantity_id+ key);
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
                    toastr.info("{{translate('item_has_been_removed_from_cart')}}", {
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
                        updateNavCart();
                        $('#cart-summary').empty().html(response);
                    }
                });
            }
        }
        //Increase/Decrease Product Quantity
        /* Increase */
        $('.qty_plus').on('click', function () {
            var $qty = $(this).parent().find('input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }
            quantityListener();
        });

        /* Decrease */
        $('.qty_minus').on('click', function () {
            var $qty = $(this).parent().find('input');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                $qty.val(currentVal - 1);
            }
            quantityListener();
        });

        /* show hide delete icon */
        function quantityListener() {
            $('.qty_input').each(function () {
                var qty = $(this);
                if (qty.val() == 1) {
                    qty.siblings('.qty_minus').html('<i class="tio-delete text-danger fs-12"></i>')
                } else {
                    qty.siblings('.qty_minus').html('<i class="tio-remove"></i>')
                }
            });
        }
        quantityListener();
    </script>
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
                    $('#loading').show();
                },
                success: function (data) {
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>
    <script>
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
                    $('#loading').show();
                },
                success: function (data) {
                    let url = "{{ route('checkout-details') }}";
                    location.href = url;

                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

    function minimum_Order_Amount_message(data)
    {
        toastr.error(data, {
            CloseButton: true,
            ProgressBar: true
        });
    }

</script>
@endpush
