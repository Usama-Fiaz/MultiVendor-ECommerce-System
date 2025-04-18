@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))

<div class="card">
    @if($wishlists->count()>0)
        <div class="card-body p-2 p-sm-3">
            <div class="d-flex flex-column gap-10px">
            @foreach($wishlists as $key=>$wishlist)
                @php($product = $wishlist->product_full_info)
                @if( $wishlist->product_full_info)
                    <div class="wishlist-item" id="row_id{{$product->id}}">
                        <div class="wishlist-img position-relative">
                            <a href="{{route('product',$product->slug)}}" class="d-block h-100">
                                <img class="__img-full" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" alt="wishlist"
                                    >
                            </a>

                            @if($product->discount > 0)
                                <span class="for-discoutn-value px-1">
                                    @if ($product->discount_type == 'percent')
                                        {{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                    @elseif($product->discount_type =='flat')
                                        {{\App\CPU\Helpers::currency_converter($product->discount)}}
                                    @endif
                                </span>
                            @endif

                        </div>
                        <div class="wishlist-cont align-items-end align-items-sm-center">
                            <div class="wishlist-text">
                                <div class="font-name">
                                    <a href="{{route('product',$product['slug'])}}">{{$product['name']}}</a>
                                </div>
                                @if($brand_setting)
                                    <span class="sellerName"> {{translate('brand')}} : <span class="text-base">{{$product->brand?$product->brand['name']:''}}</span> </span>
                                @endif

                                <div class=" mt-sm-1">
                                    @if($product->discount > 0)
                                        <strike style="color: #9B9B9B;" class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-3'}}">
                                            {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                        </strike>
                                    @endif
                                    <span class="font-weight-bold amount text-dark">{{\App\CPU\Helpers::get_price_range($product) }}</span>
                                </div>
                            </div>
                            <a href="javascript:" onclick="removeWishlist('{{$product['id']}}', 'remove-wishlist-modal')" class="remove--icon">
                                <i class="fa fa-trash" style="color: red"></i>
                            </a>

                        </div>
                    </div>
                @else
                    <span class="badge badge-danger">{{translate('item_removed')}}</span>
                @endif
            @endforeach
            </div>

        </div>
    @else
        <div class="login-card">
            <div class="text-center py-3 text-capitalize">
                <img src="{{asset('public/assets/front-end/img/icons/wishlist.png')}}" alt="" class="mb-4" width="70">
                <h5 class="fs-14">{{translate('no_product_found_in_wishlist')}}!</h5>
            </div>
        </div>
    @endif
</div>

<div class="card-footer border-0">{{$wishlists->links()}}</div>
