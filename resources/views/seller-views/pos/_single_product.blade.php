<div class="pos-product-item card" onclick="quickView('{{$product->id}}')">
    <div class="pos-product-item_thumb">
        <img class="img-fit" src="{{asset('storage/app/public/product/thumbnail')}}/{{$product->thumbnail}}"
                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'">
    </div>

    <div class="pos-product-item_content clickable">
        <div class="pos-product-item_title">
            {{ $product['name'] }}
        </div>
        <div class="pos-product-item_price">
            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']- \App\CPU\Helpers::get_product_discount($product, $product['unit_price'])))  }}
        </div>
       <div class="pos-product-item_hover-content">
           <div class="d-flex flex-wrap gap-2">
               <span class="fz-22">{{ $product['product_type'] == 'physical' ? $product['current_stock'] : translate('in_Stock') }}</span>
           </div>
       </div>
    </div>
</div>
