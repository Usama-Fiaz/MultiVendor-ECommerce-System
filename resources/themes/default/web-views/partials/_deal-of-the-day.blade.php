@if(isset($product))
    <div class="container rtl">
        <div class="row g-4 pt-2 mt-0 pb-2 __deal-of align-items-start">
            <!-- Deal of the day/Recommended Product -->
            <div class="col-xl-3 col-md-4">
                <div class="deal_of_the_day h-100 bg--light">
                    @if(isset($deal_of_the_day->product))
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <h4 class="font-bold m-0 align-items-center text-uppercase text-center px-2" style="color: {{$web_config['primary_color']}}"> {{ translate('deal_of_the_day') }}</h4>
                        </div>
                        <div class="recomanded-product-card mt-0">
                            <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                <div class="position-relative">
                                    <img class="__rounded-top"
                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal_of_the_day->product['thumbnail']}}"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        alt="">
                                    @if($deal_of_the_day->discount > 0)
                                    <span class="for-discoutn-value p-1 pl-2 pr-2">
                                        @if ($deal_of_the_day->discount_type == 'percent')
                                            {{round($deal_of_the_day->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                        @elseif($deal_of_the_day->discount_type =='flat')
                                            {{\App\CPU\Helpers::currency_converter($deal_of_the_day->discount)}}
                                        @endif {{translate('off')}}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="__i-1 bg-transparent text-center mb-0">
                                <div class="__p-20px px-0">
                                    @php($overallRating = \App\CPU\ProductManager::get_overall_rating($deal_of_the_day->product['reviews']))
                                    @if($overallRating[0] != 0 )
                                        <div class="rating-show">
                                            <span class="d-inline-block font-size-sm text-body">
                                                @for($inc=1;$inc<=5;$inc++)
                                                    @if ($inc <= (int)$overallRating[0])
                                                        <i class="tio-star text-warning"></i>
                                                    @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1)
                                                        <i class="tio-star-half text-warning"></i>
                                                    @else
                                                        <i class="tio-star-outlined text-warning"></i>
                                                    @endif
                                                @endfor
                                                <label class="badge-style">( {{$deal_of_the_day->product->reviews_count}} )</label>
                                            </span>
                                        </div>
                                    @endif
                                    <h6 class="font-semibold pt-2">
                                        {{\Illuminate\Support\Str::limit($deal_of_the_day->product['name'],30)}}
                                    </h6>
                                    <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">

                                        @if($deal_of_the_day->product->discount > 0)
                                        <strike class="__text-12px __color-9B9B9B">
                                            {{\App\CPU\Helpers::currency_converter($deal_of_the_day->product->unit_price)}}
                                        </strike>
                                        @endif
                                        <span class="text-accent __text-22px text-dark">
                                            {{\App\CPU\Helpers::currency_converter(
                                                $deal_of_the_day->product->unit_price-(\App\CPU\Helpers::get_product_discount($deal_of_the_day->product,$deal_of_the_day->product->unit_price))
                                            )}}
                                        </span>
                                    </div>
                                    <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase" onclick="location.href='{{route('product',$deal_of_the_day->product->slug)}}'">{{translate('buy_now')}}
                                    </button>

                                </div>
                            </div>
                        </div>
                    @else
                        @if(isset($product))
                            <div class="d-flex justify-content-center align-items-center py-4">
                                <h4 class="font-bold m-0 align-items-center text-uppercase text-center px-2" style="color: {{$web_config['primary_color']}}"> {{ translate('recommended_product') }}</h4>
                            </div>
                            <div class="recomanded-product-card mt-0">

                                <div class="d-flex justify-content-center align-items-center __pt-20 __m-20-r">
                                    <div class="position-relative">
                                        <img
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            alt="">
                                        @if($product->discount > 0)
                                            <span class="for-discoutn-value p-1 pl-2 pr-2">
                                                @if ($product->discount_type == 'percent')
                                                    {{round($product->discount,(!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                                                @elseif($product->discount_type =='flat')
                                                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                                                @endif {{translate('off')}}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="__i-1 bg-transparent text-center mb-0">
                                    <div class="__p-20px px-0 pb-0">
                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product['reviews']))
                                        @if($overallRating[0] != 0 )
                                            <div class="rating-show">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for($inc=0;$inc<5;$inc++)
                                                        @if ($inc <= (int)$overallRating[0])
                                                            <i class="tio-star text-warning"></i>
                                                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="tio-star-outlined text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <label class="badge-style">( {{$product->reviews_count}} )</label>
                                                </span>

                                            </div>
                                        @endif
                                        <h6 class="font-semibold pt-2">
                                            {{\Illuminate\Support\Str::limit($product['name'],30)}}
                                        </h6>
                                        <div class="mb-4 pt-1 d-flex flex-wrap justify-content-center align-items-center text-center gap-8">
                                            @if($product->discount > 0)
                                                <strike class="__text-12px __color-9B9B9B">
                                                    {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                                </strike>
                                            @endif
                                            <span class="text-accent __text-22px text-dark">
                                                {{\App\CPU\Helpers::currency_converter(
                                                    $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                                                )}}
                                            </span>
                                        </div>
                                        <button class="btn btn--primary font-bold px-4 rounded-10 text-uppercase" onclick="location.href='{{route('product',$product->slug)}}'">{{translate('buy_now')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <!-- Latest products -->
            <div class="col-xl-9 col-md-8">
                <div class="latest-product-margin">
                    <div class="d-flex justify-content-between mb-14px">
                        <div class="text-center">
                            <span
                                class="for-feature-title __text-22px font-bold text-center">{{ translate('latest_products')}}</span>
                        </div>
                        <div class="mr-1">
                            <a class="text-capitalize view-all-text"style="color: {{$web_config['primary_color']}}!important"
                            href="{{route('products',['data_from'=>'latest'])}}" >
                                {{ translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row mt-0 g-2">
                        @foreach($latest_products as $product)
                            <div class="col-xl-3 col-sm-4 col-md-6 col-lg-4 col-6">
                                <div>
                                    @include('web-views.partials._single-product',['product'=>$product,'decimal_point_settings'=>$decimal_point_settings])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
