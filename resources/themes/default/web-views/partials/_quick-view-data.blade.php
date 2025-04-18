@php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    $productReviews = \App\CPU\ProductManager::get_product_review($product->id);
@endphp

<style>
    .product-title2 {
        font-family: 'Roboto', sans-serif !important;
        font-weight: 400 !important;
        font-size: 22px !important;
        color: #000000 !important;
        position: relative;
        display: inline-block;
        word-wrap: break-word;
        overflow: hidden;
        max-height: 1.2em; /* (Number of lines you want visible) * (line-height) */
        line-height: 1.2em;
    }

    .cz-product-gallery {
        display: block;
    }

    .cz-preview {
        width: 100%;
        margin-top: 0;
        margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
        max-height: 100% !important;
    }

    .cz-preview-item > img {
        width: 100%;
    }

    .details {
        border: 1px solid #E2F0FF;
        border-radius: 3px;
        padding: 16px;
    }

    @media (max-width: 991px) {
        .details {
            border: none;
            border-radius: 0;
            padding: 0;
        }
    }

    img, figure {
        max-width: 100%;
        vertical-align: middle;
    }

    .cz-thumblist-item {
        display: block;
        position: relative;
        width: 64px;
        height: 64px;
        margin: .625rem;
        transition: border-color 0.2s ease-in-out;
        border: 1px solid #E2F0FF;
        border-radius: .3125rem;
        text-decoration: none !important;
        overflow: hidden;
    }

    .for-hover-bg {
        font-size: 18px;
        height: 45px;
    }

    .cz-thumblist-item > img {
        display: block;
        width: 80%;
        transition: opacity .2s ease-in-out;
        max-height: 58px;
        opacity: .6;
    }

    @media (max-width: 767.98px) and (min-width: 576px) {
        .cz-preview-item > img {
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .cz-thumblist {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-pack: center;
            justify-content: center;
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
            padding-top: 1rem;
            padding-right: 22px;
            padding-bottom: 10px;
        }

        .cz-thumblist-item {
            margin: 0;
        }

        .cz-thumblist {
            padding-top: 8px !important;
        }

        .cz-preview-item > img {
            width: 100%;
        }
    }
</style>

<div class="modal-header rtl">
    <div>
        <h4 class="modal-title product-title">
            <a class="product-title2" href="{{route('product',$product->slug)}}" data-toggle="tooltip"
               data-placement="right"
               title="Go to product page">{{$product['name']}}
                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-2' : 'right ml-2'}} font-size-lg"
                   style="margin-right: 0 !important;"></i>
            </a>
        </h4>
    </div>
    <div>
        <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<div class="modal-body rtl">
    <div class="row ">
        <div class="col-lg-5 col-md-4 col-12">
            <div class="cz-product-gallery position-relative">
                <div class="cz-preview">
                    @if($product->images!=null && json_decode($product->images)>0)
                        @if(json_decode($product->colors) && $product->color_image)
                            @foreach (json_decode($product->color_image) as $key => $photo)
                                @if($photo->color != null)
                                    <div class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                        <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset("storage/app/public/product/$photo->image_name")}}" alt="Product image" width="">
                                    </div>
                                @else
                                    <div class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                        <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset("storage/app/public/product/$photo->image_name")}}" alt="Product image" width="">
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @foreach (json_decode($product->images) as $key => $photo)
                                <div class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                    <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset("storage/app/public/product/$photo")}}" alt="Product image" width="">
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>

                <div class="cz-product-gallery-icons">
                    <div class="d-flex flex-column">
                        <button type="button" onclick="addWishlist('{{$product['id']}}')"
                                class="btn __text-18px border wishlight-pos-btn d-sm-none">
                            <i class="fa {{($wishlist_status == 1?'fa-heart':'fa-heart-o')}} wishlist_icon_{{$product['id']}}"
                               style="color: {{$web_config['primary_color']}}" id="wishlist_icon_{{$product['id']}}"
                               aria-hidden="true"></i>
                        </button>

                        <div style="text-align:{{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                             class="sharethis-inline-share-buttons share--icons">
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 515px;">
                    <div class="d-flex">
                        @if($product->images!=null && json_decode($product->images)>0)
                            @if(json_decode($product->colors) && $product->color_image)
                                @foreach (json_decode($product->color_image) as $key => $photo)
                                    @if($photo->color != null)
                                        <div class="cz-thumblist">
                                            <a href="javascript:"
                                               class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                                <img class="click-img" id="preview-img{{$photo->color}}"
                                                     src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                     alt="Product thumb">
                                            </a>
                                        </div>
                                    @else
                                        <div class="cz-thumblist">
                                            <a href="javascript:"
                                               class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                                <img class="click-img" id="preview-img{{$key}}"
                                                     src="{{asset("storage/app/public/product/$photo->image_name")}}"
                                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                     alt="Product thumb">
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @foreach (json_decode($product->images) as $key => $photo)
                                    <div class="cz-thumblist">
                                        <a href="javascript:"
                                           class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                            <img class="click-img" id="preview-img{{$key}}"
                                                 src="{{asset("storage/app/public/product/$photo")}}"
                                                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                 alt="Product thumb">
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Product details-->
        <div class="col-lg-7 col-md-8 col-12 mt-md-0 mt-sm-3" style="direction: {{ Session::get('direction') }}">
            <div class="details __h-100">
                <a href="{{route('product',$product->slug)}}" class="h3 mb-2 product-title">{{$product->name}}</a>

                <div class="d-flex flex-wrap align-items-center mb-2 pro">
                    <div class="star-rating" style="{{Session::get('direction') === "rtl" ? 'margin-left: 10px;' : 'margin-right: 10px;'}}">
                        @for($inc=0;$inc<5;$inc++)
                            @if($inc<$overallRating[0])
                                <i class="sr-star czi-star-filled active"></i>
                            @else
                                <i class="sr-star czi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span
                        class="d-inline-block  align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-md-2 ml-sm-0' : 'mr-md-2 mr-sm-0'}} fs-14 text-muted">({{$overallRating[0]}})</span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}"><span style="color: {{$web_config['primary_color']}}">{{$overallRating[1]}}</span> {{translate('reviews')}}</span>
                    <span class="__inline-25"></span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}"><span style="color: {{$web_config['primary_color']}}">{{$countOrder}}</span> {{translate('orders')}}   </span>
                    <span class="__inline-25">    </span>
                    <span class="font-regular font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-0 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-0 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}} text-capitalize"> <span style="color: {{$web_config['primary_color']}}"> {{$countWishlist}}</span> {{translate('wish_listed')}} </span>

                </div>

                <div class="mb-3">
                    <span class="f-20 font-weight-normal text-accent ">
                        {!! \App\CPU\Helpers::get_price_range_with_discount($product) !!}
                    </span>
                </div>
                <form id="add-to-cart-form" class="mb-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-3">
                        @if (count(json_decode($product->colors)) > 0)
                            <div class="flex-start">
                                <div class="product-description-label text-dark font-bold">
                                    {{translate('color')}}:
                                </div>
                                <div class="__pl-15 mt-1">
                                    <ul class="flex-start checkbox-color mb-0 p-0" style="list-style: none;">
                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <li>
                                                <input type="radio"
                                                       id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                       name="color" value="{{ $color }}"
                                                       @if($key == 0) checked @endif>
                                                <label style="background: {{ $color }};"
                                                       for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                       data-toggle="tooltip"
                                                       onclick="quick_view_preview_image_by_color('{{ str_replace('#','',$color) }}')">
                                                    <span class="outline" style="border-color: {{ $color }}"></span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @php
                            $qty = 0;
                            foreach (json_decode($product->variation) as $key => $variation) {
                                $qty += $variation->qty;
                            }
                        @endphp

                    </div>

                    @foreach (json_decode($product->choice_options) as $key => $choice)
                        <div class="flex-start">
                            <div class="product-description-label text-dark font-bold mt-1 text-capitalize">
                                {{ $choice->title }}:
                            </div>
                            <div>
                                <ul class="checkbox-alphanumeric checkbox-alphanumeric--style-1 mt-1">
                                    @foreach ($choice->options as $index => $option)
                                        <span>
                                <input type="radio" id="{{ $choice->name }}-{{ $option }}" name="{{ $choice->name }}"
                                       value="{{ $option }}" @if($index==0) checked @endif>
                                <label for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                            </span>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3">
                        <div class="product-quantity d-flex flex-column __gap-15">
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-description-label text-dark font-bold mt-0">{{translate('quantity')}}:</div>
                                <div class="d-flex justify-content-center align-items-center quantity-box border rounded border-base"
                                     style="color: {{$web_config['primary_color']}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-number __p-10" type="button" data-type="minus" data-field="quantity"
                                            disabled="disabled" style="color: {{$web_config['primary_color']}}">
                                        -
                                    </button>
                                </span>
                                    <input type="text" name="quantity"
                                           class="form-control input-number text-center cart-qty-field __inline-29 border-0 "
                                           placeholder="1" value="{{ $product->minimum_order_qty ?? 1 }}"
                                           product-type="{{ $product->product_type }}" min="{{ $product->minimum_order_qty ?? 1 }}"
                                           max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                    <span class="input-group-btn">
                                    <button class="btn btn-number __p-10" type="button" product-type="{{ $product->product_type }}"
                                            data-type="plus" data-field="quantity" style="color: {{$web_config['primary_color']}}">
                                        +
                                    </button>
                                </span>
                                </div>
                            </div>
                            <div id="chosen_price_div">
                                <div
                                    class="d-flex justify-content-start align-items-center {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                    <div class="product-description-label text-dark font-bold text-capitalize">
                                        <strong>{{translate('total_price')}}</strong> : </div>
                                    &nbsp; <strong id="chosen_price" class="text-base"></strong>
                                    <small class="{{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}  font-regular">
                                        (<small>{{translate('tax')}} : </small>
                                        <small id="set-tax-amount"></small>)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters d-none mt-2 flex-start d-flex">
                        <div class="col-12">
                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                <h5 class="mt-3 text-danger">{{translate('out_of_stock')}}</h5>
                            @endif
                        </div>
                    </div>

                    <div class="__btn-grp align-items-center mt-2">
                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) &&
                        $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date
                        <= $seller_vacation_end_date))) || ($product->added_by == 'admin' && ($inhouse_temporary_close ||
                            ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <=
                                $inhouse_vacation_end_date))))

                            <button class="btn btn-secondary" type="button" disabled>
                                {{translate('buy_now')}}
                            </button>

                            <button class="btn btn--primary string-limit" type="button" disabled>
                                {{translate('add_to_cart')}}
                            </button>
                        @else
                            <button class="btn btn-secondary" onclick="buy_now()" type="button">
                                {{translate('buy_now')}}
                            </button>
                            <button class="btn btn--primary string-limit" onclick="addToCart()" type="button">
                                {{translate('add_to_cart')}}
                            </button>
                        @endif

                        <button type="button" onclick="addWishlist('{{$product['id']}}')" class="btn __text-18px border">
                            <i class="fa {{($wishlist_status == 1?'fa-heart':'fa-heart-o')}} wishlist_icon_{{$product['id']}}"
                               style="color: {{$web_config['primary_color']}}" id="wishlist_icon_{{$product['id']}}"
                               aria-hidden="true"></i>
                            <span
                                class="fs-14 text-muted align-bottom countWishlist-{{$product['id']}}">{{$countWishlist}}</span>
                        </button>

                        @if(($product->added_by == 'seller' && ($seller_temporary_close ||
                        (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >=
                        $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) || ($product->
                            added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status &&
                            $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))
                            <div class="alert alert-danger" role="alert">
                                {{translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now')}}
                            </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip(),
        $('[data-toggle="popover"]').popover()

        $('.click-img').click(function () {
            var idimg = $(this).attr('id');
            var srcimg = $(this).attr('src');
            $(".show-imag").attr('src', srcimg);
        });
    });

    function quick_view_preview_image_by_color(key) {
        let id = $('#preview-img' + key);
        $(id).click();
    }
</script>

<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons" async="async"></script>

