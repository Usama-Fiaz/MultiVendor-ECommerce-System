@if (count($products) > 0)
<ul class="list-group list-unstyled gap-3">
    @foreach($products as $product)
    <li>
        <div class="media gap-3 border-bottom pb-2 cursor-pointer" onclick="$('.search-bar-input-mobile').val('{{$product['name']}}'); $('.search-bar-input').val('{{$product['name']}}'); quickView('{{$product->id}}')">
                <img class="avatar avatar-xl border" width="75" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}" alt="">
                <div class="media-body d-flex flex-column gap-1">
                    <h6 class="fz-13 mb-1 text-truncate custom-width">{{$product['name']}}</h6>
                    <div class="fz-10">{{ translate('category') }}: {{ $product->category->name ?? 'N/a' }}</div>
                    <div class="fz-10">{{ translate('brand_Name') }}: {{ $product->brand->name }}</div>
                    @if ($product->added_by == 'admin')
                    <div class="fz-10">{{ translate('seller') }}: {{ $web_config['name']->value }}</div>
                    @else
                    <div class="fz-10">{{ translate('seller') }}: {{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</div>
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ul>
@else

<div>
    <h5 class="m-0 text-muted">{{ translate('No_Product_Found') }}</h5>
</div>

@endif
