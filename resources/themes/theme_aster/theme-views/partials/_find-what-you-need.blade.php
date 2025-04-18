<section>
    <div class="container">
        <div class="flexible-grid lg-down-1 gap-3">
            <!-- for mobile start -->
            @if(isset($footer_banner[0]))
                <div class="col-12 d-sm-none">
                    <a href="{{ $footer_banner[0]['url'] }}" class="ad-hover">
                        <img src="{{asset('storage/app/public/banner')}}/{{$footer_banner[0]['photo']}}" loading="lazy" alt=""
                                onerror="this.src='{{theme_asset('assets/img/image-place-holder-2_1.png')}}'"
                                class="dark-support rounded w-100">
                    </a>
                </div>
            @endif
            <!-- for mobile end -->

            @if(auth('customer')->check() && count($order_again)>0)
                <div class="bg-primary-light rounded p-3 d-none d-sm-block">
                    <h3 class="text-primary mb-3 mt-2">{{ translate('Order_Again') }}</h3>
                    <p>{{ translate('Want_to_order_your_usuals') }}? {{ translate('Just_reorder_from_your_previous_orders.') }}</p>

                    <div class="d-flex flex-wrap gap-3 custom-scrollbar" style="--height: 26.5rem">
                        @foreach($order_again as $order)
                            <div class="card rounded-10 flex-grow-1">
                                <div class="p-3">
                                    <h6 class="fs-12 text-primary mb-1">
                                        @if($order['order_status'] =='processing')
                                            {{ translate('packaging') }}
                                        @elseif($order['order_status'] =='failed')
                                            {{ translate('Failed_to_Deliver') }}
                                        @elseif($order['order_status'] == 'all')
                                            {{ translate('all') }}
                                        @else
                                            {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                        @endif
                                    </h6>
                                    <div class="fs-10">{{ translate('on') }} {{date('d M Y',strtotime($order['updated_at']))}}</div>

                                    <div class="bg-light my-2 rounded-10 p-4">
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            @foreach($order['details']->take(3) as $key=>$detail)
                                                <div>
                                                    <img width="42" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail['product']['thumbnail'] ?? ''}}" loading="lazy"
                                                         onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                         alt="" class="dark-support rounded">
                                                </div>
                                            @endforeach

                                            @if(count($order['details']) > 3)
                                                <h6 class="fw-medium fs-12 text-center">+{{ count($order['details'])-3 }} <br>
                                                    <a href="{{ route('account-order-details', ['order_id'=>$order['id']]) }}">{{ translate('more') }}</a>
                                                </h6>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div class="">
                                            <h6 class="fs-10 mb-2"><a href="{{ route('account-order-details', ['id' => $order['id']]) }}">{{ translate('Order_ID') }}: #{{ $order['id'] }}</a></h6>
                                            <h6>{{ translate('Final_Total') }} : {{ \App\CPU\Helpers::currency_converter($order['order_amount']) }}</h6>
                                        </div>
                                        <a href="javascript:" onclick="order_again({{ $order['id'] }})" class="btn btn-primary">{{ translate('order_again') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="d-none d-sm-block">
                    @if($sidebar_banner)
                        <a href="{{ $sidebar_banner['url'] }}">
                            <img src="{{asset('storage/app/public/banner')}}/{{$sidebar_banner ? $sidebar_banner['photo'] : ''}}"
                                 onerror="this.src='{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}'"alt="" class="dark-support rounded w-100">
                        </a>
                    @else
                        <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}" class="dark-support rounded w-100">
                    @endif
                </div>
            @endif

            <div class="card">
                <div class="p-3 p-sm-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 mb-sm-4">
                        <h2><span class="text-primary">{{translate('Find')}}</span> {{translate('what_you_need')}}</h2>
                        <div class="swiper-nav d-flex gap-2 align-items-center">
                            <div
                                class="swiper-button-prev find-what-you-need-nav-prev position-static rounded-10"></div>
                            <div
                                class="swiper-button-next find-what-you-need-nav-next position-static rounded-10"></div>
                        </div>
                    </div>
                    <div class="swiper-container">
                        <!-- Swiper -->
                        <div class="position-relative d-none d-md-block">
                            <div class="swiper" data-swiper-loop="true" data-swiper-margin="16"
                                 data-swiper-speed="2000" data-swiper-pagination-el="null"
                                 data-swiper-navigation-next=".find-what-you-need-nav-next"
                                 data-swiper-navigation-prev=".find-what-you-need-nav-prev">
                                <div class="swiper-wrapper">
                                    @foreach($category_slider as $ke=>$all_category)

                                    <div class="swiper-slide align-items-start bg-white">
                                        <div class="flexible-grid md-down-1 gap-3 w-100" style="--width: 1fr">
                                            <!-- Category Wrap -->
                                            @foreach($all_category as $key=>$category)

                                                <div class="bg-light rounded p-4">
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-start">
                                                        <div class="">
                                                            <h5 class="mb-1 text-truncate" style="--width: 16ch">
                                                                {{$category['name']}}</h5>
                                                            <div class="text-muted">{{$category['product_count']}} {{translate('products')}}</div>
                                                        </div>

                                                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="btn-link">{{translate('view_all')}}<i
                                                                class="bi bi-chevron-right text-primary"></i></a>
                                                    </div>

                                                    <div class="find-what-you-need-items">
                                                        @foreach($category['childes'] as $key=>$sub_category)
                                                            <a href="{{route('products',['id'=> $sub_category['id'],'data_from'=>'category','page'=>1])}}"
                                                               class="d-flex flex-column gap-2 mb-3 align-items-center">
                                                                <div class="img-wrap bg-white w-100 rounded justify-content-center d-flex">
                                                                    <div class="floting-text">
                                                                        <span class="truncate text-center">
                                                                            <span>
                                                                                {{
                                                                                    count($category['childes'])<4 && in_array($key, [0,1,2]) && !array_key_exists(++$key, $category['childes']) ?
                                                                                            ($sub_category['sub_category_product_count'] > 1 ? ($sub_category['sub_category_product_count']-1).'+' : $sub_category['sub_category_product_count'])
                                                                                    : $sub_category['sub_category_product_count']
                                                                                }}
                                                                            </span>
                                                                            {{ translate('products') }}
                                                                        </span>
                                                                    </div>
                                                                    <div
                                                                        class="ov-hidden rounded w-100" style="height: 5rem;">
                                                                        <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                                             src="{{asset('storage/app/public/category')}}/{{$sub_category['icon']}}" alt=""
                                                                             loading="lazy" class="dark-support img-fit " >
                                                                    </div>
                                                                </div>
                                                                <div class="truncate text-center">{{$sub_category['name']}}</div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Swiper -->
                        <div class="position-relative d-md-none">
                            <div class="swiper" data-swiper-loop="true"
                                 data-swiper-speed="2000" data-swiper-margin="10" data-swiper-pagination-el="null"
                                 data-swiper-navigation-next=".find-what-you-need-nav-next"
                                 data-swiper-navigation-prev=".find-what-you-need-nav-prev">
                                <div class="swiper-wrapper">
                                    @foreach($final_category as $key=>$category)
                                    <div class="swiper-slide align-items-start d-block bg-light rounded p-3 p-sm-4 align-items-stretch">
                                        <div>
                                            <div
                                                class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-start">
                                                <div class="">
                                                    <h5 class="mb-1 text-truncate" style="--width: 16ch">
                                                        {{$category['name']}}</h5>
                                                    <div class="text-muted">{{$category['product_count']}} {{translate('products')}}</div>
                                                </div>

                                                <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="btn-link">{{translate('view_all')}}<i
                                                        class="bi bi-chevron-right text-primary"></i></a>
                                            </div>

                                            <div class="auto-col gap-3" style="--minWidth: 3.75rem; --maxWidth: 5rem">
                                                @foreach($category['childes'] as $key=>$sub_category)
                                                    <a href="{{route('products',['id'=> $sub_category['id'],'data_from'=>'category','page'=>1])}}"
                                                        class="d-flex flex-column gap-2 mb-3 align-items-start">
                                                        <div
                                                            class="avatar avatar-xxl ov-hidden hover-zoom-in rounded">
                                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                                    src="{{asset('storage/app/public/category')}}/{{$sub_category['icon']}}" alt=""
                                                                    loading="lazy" class="dark-support img-fit " >
                                                        </div>
                                                        <div class="text-truncate">{{$sub_category['name']}}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- for mobile start -->
            @if(count($random_coupon)>0)
                <div class="col-12 d-sm-none">
                    <div class="bg-primary-light rounded p-3 mt-lg-3">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <h3 class="text-primary my-3">{{ translate('Happy_Club') }}</h3>
                        </div>
                        <p>{{ translate('collect_coupons_from_stores_and_apply_to_get_special_discount_from_stores') }}</p>

                        <div class="d-flex flex-wrap gap-3">
                            @foreach($random_coupon as $coupon)
                            <div class="club-card card custom-border-color hover-shadow flex-grow-1" onclick="coupon_copy('{{ $coupon->code }}')">
                                <div class="d-flex flex-column gap-2 p-3">
                                    <h5 class="d-flex gap-2 align-items-center">
                                        @if($coupon->coupon_type == 'free_delivery')
                                            {{translate($coupon->coupon_type)}}
                                            <img src="{{ theme_asset('assets/img/svg/delivery-car.svg') }}" alt="" class="svg">
                                        @else
                                            {{ $coupon->discount_type == 'amount' ? \App\CPU\Helpers::currency_converter($coupon->discount) : $coupon->discount.'%' }} OFF
                                            <img src="{{ theme_asset('assets/img/svg/dollar.svg') }}" alt="" class="svg">
                                        @endif
                                    </h5>
                                    <h6 class="fs-12">
                                        <span class="text-muted">{{ translate('for') }}</span>
                                        <span class="text-uppercase ">
                                            @if($coupon->seller_id == '0')
                                                {{ translate('All_Shops') }}
                                            @elseif($coupon->seller_id == NULL)
                                                {{ $web_config['name']->value }}
                                            @else
                                                <a class="shop-name" onclick="location.href='{{isset($coupon->seller->shop) ? route('shopView',['id'=>$coupon->seller->shop['id']]) : 'javascript:'}}'">
                                                    {{ isset($coupon->seller->shop) ? $coupon->seller->shop->name : translate('shop_not_found') }}
                                                </a>
                                            @endif
                                        </span>
                                    </h6>
                                    <h6 class="text-primary fs-12">{{ translate('code') }}: {{ $coupon->code }}</h6>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 d-sm-none">
                    @if($top_side_banner)
                        <a href="{{ $top_side_banner['url'] }}">
                            <img src="{{asset('storage/app/public/banner')}}/{{$top_side_banner ? $top_side_banner['photo'] : ''}}"
                                    onerror="this.src='{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}'"
                                    alt="" class="dark-support rounded w-100">
                        </a>
                    @else
                        <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}" class="dark-support rounded w-100">
                    @endif
                </div>
            @endif
            <!-- for mobile end -->
        </div>
    </div>
</section>
