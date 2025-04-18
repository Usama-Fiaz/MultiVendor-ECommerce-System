<form action="{{route('admin.pos.order')}}" id='order_place' method="post" >
    @csrf
<div id="cart">
    <div class="table-responsive pos-cart-table border">
        <table class="table table-align-middle m-0">
            <thead class="text-capitalize bg-light">
                <tr>
                    <th class="border-0 min-w-120">{{translate('item')}}</th>
                    <th class="border-0">{{translate('qty')}}</th>
                    <th class="border-0">{{translate('price')}}</th>
                    <th class="border-0 text-center">{{translate('delete')}}</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $subtotal = 0;
                $addon_price = 0;
                $tax = 0;
                $discount = 0;
                $discount_type = 'amount';
                $discount_on_product = 0;
                $total_tax = 0;
                $total_tax_show = 0;
                $ext_discount = 0;
                $ext_discount_type = 'amount';
                $coupon_discount =0;
                $include_tax = 0;
            ?>
            @if(session()->has($cart_id) && count(session()->get($cart_id)) > 0)
                <?php
                    $cart = session()->get($cart_id);
                    if(isset($cart['tax']))
                    {
                        $tax = $cart['tax'];
                    }
                    if(isset($cart['discount']))
                    {
                        $discount = $cart['discount'];
                        $discount_type = $cart['discount_type'];
                    }
                    if (isset($cart['ext_discount'])) {
                        $ext_discount = $cart['ext_discount'];
                        $ext_discount_type = $cart['ext_discount_type'];
                    }
                    if(isset($cart['coupon_discount']))
                    {
                        $coupon_discount = $cart['coupon_discount'];
                    }
                ?>
                @foreach(session()->get($cart_id) as $key => $cartItem)
                @if(is_array($cartItem))
                    <?php
                        $product = \App\Model\Product::find($cartItem['id']);

                        //tax calculation
                        $tax_calculate = \App\CPU\Helpers::tax_calculation($cartItem['price'], $product['tax'], $product['tax_type'])*$cartItem['quantity'];
                        $total_tax_show += $cartItem['tax_model'] != 'include' ? $tax_calculate : 0;
                        $total_tax += $product['tax_model']=='include' ? 0:$tax_calculate;

                        if($product->tax_model == 'include'){
                            $include_tax += \App\CPU\Helpers::tax_calculation($cartItem['price'], $product['tax'], $product['tax_type'])*$cartItem['quantity'];
                        }

                        $product_subtotal = $cartItem['price']*$cartItem['quantity'];
                        $subtotal += $product_subtotal;

                        $discount_on_product += ($cartItem['discount']*$cartItem['quantity']);
                    ?>

                <tr>
                    <td>
                        <div class="media align-items-center gap-10">
                            <img class="avatar avatar-sm" src="{{asset('storage/app/public/product/thumbnail')}}/{{$cartItem['image']}}"
                                    onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'" alt="{{$cartItem['name']}} image">
                            <div class="media-body">
                                <h5 class="text-hover-primary mb-0">
                                    {{Str::limit($cartItem['name'], 12)}}
                                    @if($cartItem['tax_model'] == 'include')
                                        <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('tax_included')}}">
                                            <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                        </span>
                                    @endif
                                </h5>
                                <small>{{Str::limit($cartItem['variant'], 20)}}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" data-key="{{$key}}" class="form-control qty" value="{{$cartItem['quantity']}}" min="1" onkeyup="updateQuantity('{{$cartItem['id']}}',this.value,event,'{{$cartItem['variant']}}')">
                    </td>
                    <td>
                        <div>
                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product_subtotal))}}
                        </div> <!-- price-wrap .// -->
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:removeFromCart({{$key}})" class="btn btn-sm rounded-circle">
                                <img src="{{ asset('public/assets/back-end/img/icons/pos-delete-icon.svg') }}" alt="">
                            </a>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <?php
        $total = $subtotal;
        $discount_amount = $discount_on_product;
        $total -= $discount_amount;

        $extra_discount = $ext_discount;
        $extra_discount_type = $ext_discount_type;
        if($extra_discount_type == 'percent' && $extra_discount > 0){
            $extra_discount =  (($subtotal-$include_tax)*$extra_discount) / 100;
        }
        if($extra_discount) {
            $total -= $extra_discount;
        }

        $total_tax_amount= $total_tax_show;
    ?>
    <div class="pt-4">
        <dl>
            <div class="d-flex gap-2 justify-content-between">
                <dt class="title-color text-capitalize font-weight-normal">{{translate('sub_total')}} : </dt>
                <dd>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</dd>
            </div>

            <div class="d-flex gap-2 justify-content-between">
                <dt class="title-color text-capitalize font-weight-normal">{{translate('product_Discount')}} :</dt>
                <dd>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(round($discount_amount,2))) }}</dd>
            </div>

            <div class="d-flex gap-2 justify-content-between">
                <dt class="title-color text-capitalize font-weight-normal">{{translate('extra_Discount')}} :</dt>
                <dd>
                    <button id="extra_discount" class="btn btn-sm p-0" type="button" data-toggle="modal" data-target="#add-discount">
                        <i class="tio-edit"></i>
                    </button>
                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($extra_discount))}}
                </dd>
            </div>

            <div class="d-flex justify-content-between">
                <dt class="title-color gap-2 text-capitalize font-weight-normal">{{translate('coupon_Discount')}} :</dt>
                <dd>
                    <button id="coupon_discount" class="btn btn-sm p-0" type="button" data-toggle="modal" data-target="#add-coupon-discount">
                        <i class="tio-edit"></i>
                    </button>
                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}
                </dd>
            </div>

            <div class="d-flex gap-2 justify-content-between">
                <dt class="title-color text-capitalize font-weight-normal">{{translate('tax')}} : </dt>
                <dd>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(round($total_tax_amount,2)))}}</dd>
            </div>

            <div class="d-flex gap-2 border-top justify-content-between pt-2">
                <dt class="title-color text-capitalize font-weight-bold title-color">{{translate('total')}} : </dt>
                <dd class="font-weight-bold title-color">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(round($total+$total_tax-$coupon_discount, 2)))}}</dd>
            </div>
        </dl>

        <div class="form-group col-12">
            <input type="hidden" class="form-control" name="amount" min="0" step="0.01"
                    value="{{\App\CPU\BackEndHelper::usd_to_currency($total+$total_tax-$coupon_discount)}}"
                    readonly>
        </div>
        <div class="pt-4 mb-4">
            <div class="title-color d-flex mb-2">{{translate('paid_By')}}:</div>
            <ul class="list-unstyled option-buttons">
                <li>
                    <input type="radio" id="cash" value="cash" name="type" hidden checked>
                    <label for="cash" class="btn btn--bordered btn--bordered-black px-4 mb-0">{{translate('cash')}}</label>
                </li>
                <li>
                    <input type="radio" value="card" id="card" name="type" hidden>
                    <label for="card" class="btn btn--bordered btn--bordered-black px-4 mb-0">{{translate('card')}}</label>
                </li>

                @php($wallet_status = \App\CPU\Helpers::get_business_settings('wallet_status') ?? 0)
                @if ($wallet_status)
                <li class="{{ (explode('-',session('current_user'))[0]=='wc') ? 'd-none':'' }}">
                    <input type="radio" value="wallet" id="wallet" name="type" hidden>
                    <label for="wallet" class="btn btn--bordered btn--bordered-black px-4 mb-0">{{translate('wallet')}}</label>
                </li>
                @endif
            </ul>
        </div>


    </div>
    <div class="d-flex gap-2 justify-content-between align-items-center pt-3 bottom-sticky-btns">
        @php($order_place_status = 0)
        @if(session()->has($cart_id) && count(session()->get($cart_id)) > 0)
            @foreach(session()->get($cart_id) as $key => $cartItem)
                @if(is_array($cartItem))
                    @php($order_place_status = 1)
                @endif
            @endforeach
        @endif

        @if($order_place_status)
            <span class="btn btn-danger btn-block" onclick="emptyCart()">
                <i class="fa fa-times-circle"></i>
                {{translate('cancel_Order')}}
            </span>

            <button id="submit_order" type="button" class="btn btn--primary btn-block m-0" data-toggle="modal" data-target="#paymentModal"  onclick="form_submit()">
                <i class="fa fa-shopping-bag"></i>
                {{translate('place_Order')}}
            </button>
        @else
            <span class="btn btn-danger btn-block empty_alert_show" onclick="empty_alert_show()">
                <i class="fa fa-times-circle"></i>
                {{translate('cancel_Order')}}
            </span>
            <button type="button" class="btn btn--primary btn-block m-0" onclick="empty_alert_show()">
                <i class="fa fa-shopping-bag"></i>
                {{translate('place_Order')}}
            </button>
        @endif

    </div>
</div>

</form>

@push('script_2')
<script>
    $('#type_ext_dis').on('change', function (){
        let type = $('#type_ext_dis').val();
        if(type === 'amount'){
            $('#dis_amount').attr('placeholder', 'Ex: 500');
        }else if(type === 'percent'){
            $('#dis_amount').attr('placeholder', 'Ex: 10%');
        }
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endpush
