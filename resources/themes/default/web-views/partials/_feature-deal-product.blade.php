@if(isset($product))
    @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
    <div class="flash_deal_product rtl" onclick="location.href='{{route('product',$product->slug)}}'">
        @if($product->discount > 0)
        <div class="d-flex">
            <span class="for-discoutn-value p-1 pl-2 pr-2" style="{{Session::get('direction') === "rtl" ? 'border-radius:0px 5px' : 'border-radius:5px 0px'}};">
                @if ($product->discount_type == 'percent')
                    {{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif {{translate('off')}}
            </span>
        </div>
        @endif
        <div class=" d-flex">
            <div class="d-flex align-items-center justify-content-center"
                 style="padding-{{Session::get('direction') === "rtl" ?'right:12px':'left:12px'}};padding-block:12px;">
                <div class="flash-deals-background-image">
                    <img class="__img-125px"
                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"/>
                </div>
            </div>
            <div class="flash_deal_product_details pl-3 pr-3 pr-1 d-flex mt-3">
                <div>
                    <a href="{{route('product',$product->slug)}}" class="text-capitalize fw-semibold">
                        {{ Str::limit($product['name'], 23) }}
                    </a>
                    @if($overallRating[0] != 0 )
                        <div class="flash-product-review">
                            @for($inc=1;$inc<=5;$inc++)
                                @if ($inc <= (int)$overallRating[0])
                                    <i class="tio-star text-warning"></i>
                                @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                    <i class="tio-star-half text-warning"></i>
                                @else
                                    <i class="tio-star-outlined text-warning"></i>
                                @endif
                            @endfor
                            <label class="badge-style2">
                                ( {{$product->reviews->count()}} )
                            </label>
                        </div>
                    @endif
                    <div class="d-flex flex-wrap gap-8 align-items-center row-gap-0">
                        @if($product->discount > 0)
                            <strike
                                style="font-size: 12px!important;color: #9B9B9B!important;">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                        <span class="flash-product-price text-dark fw-semibold">
                            {{\App\CPU\Helpers::currency_converter($product->unit_price-\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif
