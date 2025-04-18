@if ($method)
    @php($product_price_total=0)
    @php($total_tax=0)
    @php($total_shipping_cost=0)
    @php($order_wise_shipping_discount=\App\CPU\CartManager::order_wise_shipping_discount())
    @php($total_discount_on_product=0)
    @php($cart=\App\CPU\CartManager::get_cart())
    @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
    @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost())
    @php($get_shipping_cost_saved_for_free_delivery=\App\CPU\CartManager::get_shipping_cost_saved_for_free_delivery())
    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
    @php($coupon_dis=session()->has('coupon_discount')?session('coupon_discount'):0)
    @if($cart->count() > 0)
        @foreach($cart as $key => $cartItem)
            @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
            @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
            @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
        @endforeach

        @if(session()->missing('coupon_type') || session('coupon_type') !='free_delivery')
            @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
        @else
            @php($total_shipping_cost=$shipping_cost)
        @endif
    @else
        <span>{{ translate('empty_cart') }}</span>
    @endif
    @php($total_offline_amount = $product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)

    <div class="payment-list-area">
        <div class="p-3 my-3 rounded" style="background: rgba(243, 250, 253, 1);">
            <h6 class="pb-2" style="color: {{$web_config['primary_color']}};">{{ $method->method_name }}</h6>
            <input type="hidden" value="offline_payment" name="payment_method">
            <input type="hidden" value="{{ $method->id }}" name="method_id">

            <div class="row">
                @foreach ($method->method_fields as $method_field)
                <div class="col-6 pb-2">
                    <span>{{ translate($method_field['input_name']) }}</span> : <span>{{ $method_field['input_data'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <h4 class="text-center py-3" style="font-weight: 600;">
            {{ translate('amount') }} : {{ \App\CPU\Helpers::currency_converter($total_offline_amount) }}
        </h4>

        <div class="row">
            @foreach ($method->method_informations as $informations)
                <div class="col-md-12 col-lg-6 mb-3">
                    <label style="font-weight: 600;">
                        {{ translate($informations['customer_input']) }}
                        <span class="text-danger">{{ $informations['is_required'] == 1?'*':''}}</span>
                    </label>
                    <input type="text" class="form-control" name="{{ $informations['customer_input'] }}" placeholder="{{ translate($informations['customer_placeholder']) }}" {{ $informations['is_required'] == 1?'required':''}}>
                </div>
            @endforeach

            <div class="col-12 mb-3">
                <label style="font-weight: 600;">{{translate('payment_note')}}</label>
                <textarea name="payment_note" id="" class="form-control" placeholder="{{translate('payment_note')}}"></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-10 justify-content-end pt-4">
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{translate('close')}}</button>
        <button type="submit" class="btn btn-sm btn-primary">{{translate('submit')}}</button>
    </div>

@else
    <div class="text-center py-5">
        <img class="pt-5" src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}" alt="">
        <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method first') }}</p>
    </div>
@endif
