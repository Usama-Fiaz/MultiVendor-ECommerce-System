
<div class="col-lg-6 px-max-md-0">
    <div class="card card __shadow h-100">
        <div class="card-body p-xl-35">
            <div class="row d-flex justify-content-between mx-1 mb-3">
                <div>
                    <img class="size-30" src="{{asset("public/assets/front-end/png/top-rated.png")}}"
                        alt="">
                    <span class="font-bold pl-1">{{ translate('top_rated')}}</span>
                </div>
                <div>
                    <a class="text-capitalize view-all-text" style="color: {{$web_config['primary_color']}}!important"
                    href="{{route('products',['data_from'=>'top-rated','page'=>1])}}">{{ translate('view_all')}}
                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                    </a>
                </div>
            </div>
            <div class="row g-3">
                @foreach($topRated as $key=>$top)
                    @if($top->product && $key<6)
                        <div class="col-sm-6">
                            <a class="__best-selling" href="{{route('product',$top->product->slug)}}">
                                @if($top->product->discount > 0)
                                <div class="d-flex">
                                    <span class="for-discoutn-value p-1 pl-2 pr-2"
                                        style="{{Session::get('direction') === "rtl" ? 'border-radius:0px 5px' : 'border-radius:5px 0px'}};">
                                        @if ($top->product->discount_type == 'percent')
                                            {{round($top->product->discount)}}%
                                        @elseif($top->product->discount_type =='flat')
                                            {{\App\CPU\Helpers::currency_converter($top->product->discount)}}
                                        @endif {{translate('off')}}
                                    </span>
                                </div>
                                @endif
                                <div class="d-flex flex-wrap">
                                    <div class="top-rated-image">
                                        <img class="rounded"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$top->product['thumbnail']}}"
                                            alt="Product"/>
                                    </div>
                                    <div class="top-rated-details">
                                        <h6 class="widget-product-title">
                                            <span class="ptr">
                                                {{\Illuminate\Support\Str::limit($top->product['name'],100)}}
                                            </span>
                                        </h6>
                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($top->product['reviews']))
                                        @if($overallRating[0] != 0 )
                                            <div class="rating-show">
                                                <span class="d-inline-block font-size-sm text-body">
                                                    @for ($inc = 1; $inc <= 5; $inc++)
                                                        @if ($inc <= (int)$overallRating[0])
                                                            <i class="sr-star czi-star-filled "></i>
                                                        @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1)
                                                            <i class="tio-star-half text-warning"></i>
                                                        @else
                                                            <i class="sr-star czi-star "></i>
                                                        @endif
                                                    @endfor
                                                    <label
                                                        class="badge-style">( {{$top->product->reviews_count}} )</label>
                                                </span>
                                            </div>
                                        @endif
                                        <div class="widget-product-meta d-flex flex-wrap gap-8 align-items-center row-gap-0">
                                            <span>
                                                @if($top->product->discount > 0)
                                                    <strike class="__text-12px __color-9B9B9B">
                                                        {{\App\CPU\Helpers::currency_converter($top->product->unit_price)}}
                                                    </strike>
                                                @endif
                                            </span>
                                            <span class="text-accent text-dark">
                                                {{\App\CPU\Helpers::currency_converter(
                                                $top->product->unit_price-(\App\CPU\Helpers::get_product_discount($top->product,$top->product->unit_price))
                                                )}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
