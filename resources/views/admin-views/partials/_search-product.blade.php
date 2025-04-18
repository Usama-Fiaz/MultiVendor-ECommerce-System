@foreach ($products as $key => $product)
    <div class="select-product-item media gap-3 border-bottom pb-2 cursor-pointer">
        <img class="avatar avatar-xl border" width="75"
        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
            alt="">
        <div class="media-body d-flex flex-column gap-1">
            <h6 class="product-id" hidden>{{$product['id']}}</h6>
            <h6 class="fz-13 mb-1 text-truncate custom-width product-name">{{$product['name']}}</h6>
            <div class="fz-10">{{translate('category')}} : {{isset($product->category) ? $product->category->name : translate('category_not_found') }}</div>
            <div class="fz-10">{{translate('brand')}} : {{isset($product->brand) ? $product->brand->name : translate('brands_not_found') }}</div>
            @if ($product->added_by == "seller")
                <div class="fz-10">{{translate('shop')}} : {{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</div>
            @else
                <div class="fz-10">{{translate('shop')}} : {{$web_config['name']->value}}</div>
            @endif
        </div>
    </div>
@endforeach
