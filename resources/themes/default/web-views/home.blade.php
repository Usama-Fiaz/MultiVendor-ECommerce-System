@extends('layouts.front-end.app')

@section('title', $web_config['name']->value.' '.translate('online_Shopping').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/home.css"/>
    <style>
        .cz-countdown-days {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .btn-scroll-top {
            background: {{$web_config['primary_color']}};
        }

        .__best-selling:hover .ptr,
        .flash_deal_product:hover .flash-product-title {
            color: {{$web_config['primary_color']}};
        }

        .cz-countdown-hours {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-minutes {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-seconds {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .flash_deal_product_details .flash-product-price {
            color: {{$web_config['primary_color']}};
        }

        .featured_deal_left {
            background: {{$web_config['primary_color']}} 0% 0% no-repeat padding-box;
        }

        .category_div:hover {
            color: {{$web_config['secondary_color']}};
        }

        .deal_of_the_day {
            background: {{$web_config['secondary_color']}};
        }

        .best-selleing-image {
            background: {{$web_config['primary_color']}}10;
        }

        .top-rated-image {
            background: {{$web_config['primary_color']}}10;
        }

        @media (max-width: 800px) {
            .categories-view-all {
            {{session('direction') === "rtl" ? 'margin-left: 10px;' : 'margin-right: 6px;'}}

            }

            .categories-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 0px;' : 'margin-left: 6px;'}}

            }

            .seller-list-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 0px;' : 'margin-left: 10px;'}}

            }

            .seller-list-view-all {
            {{Session::get('direction') === "rtl" ? 'margin-left: 20px;' : 'margin-right: 10px;'}}

            }

            .category-product-view-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 16px;' : 'margin-left: -8px;'}}

            }

            .category-product-view-all {
            {{Session::get('direction') === "rtl" ? 'margin-left: -7px;' : 'margin-right: 5px;'}}

            }
        }

        @media (min-width: 801px) {
            .categories-view-all {
            {{session('direction') === "rtl" ? 'margin-left: 30px;' : 'margin-right: 27px;'}}

            }

            .categories-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 25px;' : 'margin-left: 25px;'}}

            }

            .seller-list-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 6px;' : 'margin-left: 10px;'}}

            }

            .seller-list-view-all {
            {{Session::get('direction') === "rtl" ? 'margin-left: 12px;' : 'margin-right: 10px;'}}

            }

            .seller-card {
            {{Session::get('direction') === "rtl" ? 'padding-left:0px !important;' : 'padding-right:0px !important;'}}

            }

            .category-product-view-title {
            {{Session::get('direction') === "rtl" ? 'margin-right: 10px;' : 'margin-left: -12px;'}}

            }

            .category-product-view-all {
            {{Session::get('direction') === "rtl" ? 'margin-left: -20px;' : 'margin-right: 0px;'}}

            }
        }

        .countdown-card {
            background: {{$web_config['primary_color']}}10;

        }

        .flash-deal-text {
            color: {{$web_config['primary_color']}};
        }

        .countdown-background {
            background: {{$web_config['primary_color']}};
        }

        .czi-arrow-left {
            color: {{$web_config['primary_color']}};
            background: {{$web_config['primary_color']}}10;
        }

        .czi-arrow-right {
            color: {{$web_config['primary_color']}};
            background: {{$web_config['primary_color']}}10;
        }

        .flash-deals-background-image {
            background: {{$web_config['primary_color']}}10;
        }

        .view-all-text {
            color: {{$web_config['secondary_color']}}  !important;
        }

        .feature-product .czi-arrow-left {
            color: {{$web_config['primary_color']}};
            background: {{$web_config['primary_color']}}10
        }

        .feature-product .czi-arrow-right {
            color: {{$web_config['primary_color']}};
            background: {{$web_config['primary_color']}}10;
            font-size: 12px;
        }

        /*  */
    </style>

    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/owl.carousel.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/owl.theme.default.min.css"/>
@endpush

@section('content')
    <div class="__inline-61">
        @php($decimal_point_settings = !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0)
        <!-- Hero (Banners + Slider)-->
        <section class="bg-transparent py-3">
            <div class="container position-relative">
                @include('web-views.partials._home-top-slider',['main_banner'=>$main_banner])
            </div>
        </section>

        <!--flash deal-->
        @if ($web_config['flash_deals'] && count($web_config['flash_deals']->products) >0 )
            @include('web-views.partials._flash-deal')
        @endif

        <!-- Products grid (featured products)-->
        @if ($featured_products->count() > 0 )
            <div class="container py-4 rtl px-0 px-md-3">
                <div class="__inline-62 pt-3">
                    <div class="feature-product-title mt-0"  style="color: {{$web_config['primary_color']}}">
                        {{ translate('featured_products')}}
                    </div>
                    <div class="text-end px-3 d-none d-md-block">
                        <a class="text-capitalize view-all-text" href="{{route('products',['data_from'=>'featured','page'=>1])}}" style="color: {{$web_config['primary_color']}}!important">
                            {{ translate('view_all')}}
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1' : 'right ml-1'}}"></i>
                        </a>
                    </div>
                    <div class="feature-product">
                        <div class="carousel-wrap p-1">
                            <div class="owl-carousel owl-theme " id="featured_products_list">
                                @foreach($featured_products as $product)
                                    <div>
                                        @include('web-views.partials._feature-product',['product'=>$product, 'decimal_point_settings'=>$decimal_point_settings])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-center pt-2 d-md-none">
                            <a class="text-capitalize view-all-text" href="{{route('products',['data_from'=>'featured','page'=>1])}}" style="color: {{$web_config['primary_color']}}!important">
                                {{ translate('view_all')}}
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1' : 'right ml-1'}}"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- category -->
        @include('web-views.partials._category-section-home')

        <!--featured deal-->
        @if($web_config['featured_deals'] && (count($web_config['featured_deals'])>0))
            <section class="featured_deal rtl">
                <div class="container">
                    <div class="__featured-deal-wrap bg--light">
                        <div class="d-flex flex-wrap justify-content-between gap-8 mb-3">
                            <div class="w-0 flex-grow-1">
                                <span class="featured_deal_title font-bold text-dark">{{ translate('featured_deal')}}</span>
                                <br>
                                <span class="text-left text-nowrap">{{ translate('see_the_latest_deals_and_exciting_new_offers')}}!</span>
                            </div>
                            <div>
                                <a class="text-capitalize view-all-text" href="{{route('products',['data_from'=>'featured_deal'])}}" style="color: {{$web_config['primary_color']}}!important">
                                    {{ translate('view_all')}}
                                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1' : 'right ml-1'}}"></i>
                                </a>
                            </div>
                        </div>
                        <div class="owl-carousel owl-theme new-arrivals-product">
                            @foreach($web_config['featured_deals'] as $key=>$product)
                                @include('web-views.partials._product-card-1',['product'=>$product, 'decimal_point_settings'=>$decimal_point_settings])
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if (isset($main_section_banner))
            <div class="container rtl pt-4 px-0 px-md-3">
                <a href="{{$main_section_banner->url}}"
                    class="cursor-pointer d-block">
                    <img class="d-block footer_banner_img __inline-63"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        src="{{asset('storage/app/public/banner')}}/{{$main_section_banner['photo']}}">
                </a>
            </div>
        @endif

        <!--top seller-->
        @php($business_mode=\App\CPU\Helpers::get_business_settings('business_mode'))
        @if ($business_mode == 'multi' && count($top_sellers) > 0)
            @include('web-views.partials._top-sellers')
        @endif

        <!-- deal of the day and latest product -->
        @include('web-views.partials._deal-of-the-day')
        <!-- end deal of the day and latest product -->

        <!-- Banner  mobile-->
        @if ($footer_banner->count() > 0 )
            @foreach($footer_banner as $key=>$banner)
            @if ($key == 0)
                <div class="container rtl d-sm-none">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <a href="{{$banner->url}}" class="d-block">
                                <img class="footer_banner_img __inline-63"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}">
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @endforeach
        @endif

        <!-- new-arrival -->

        <section class="new-arrival-section">

            <div class="container rtl mt-4">
                @if ($latest_products->count() >0 )
                    <div class="section-header">
                        <div class="arrival-title d-block">
                            <div class="text-capitalize">
                                {{ translate('new_arrivals')}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="container rtl mb-3 overflow-hidden">
                <div class="py-2">
                    <div class="new_arrival_product">
                        <div class="carousel-wrap">
                            <div class="owl-carousel owl-theme new-arrivals-product">
                                @foreach($latest_products as $key=>$product)
                                    @include('web-views.partials._product-card-2',['product'=>$product,'decimal_point_settings'=>$decimal_point_settings])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container rtl px-0 px-md-3">
                <div class="row g-3 mx-max-md-0">
                    <!-- best selling product -->
                    @if ($bestSellProduct->count() >0)
                        @include('web-views.partials._best-selling')
                    @endif
                    <!-- top rated  product -->
                    @if ($topRated->count() >0)
                        @include('web-views.partials._top-rated')
                    @endif
                </div>
            </div>
        </section>
        <!-- Banner  mobile-->
        @if ($footer_banner->count() > 0 )
            @foreach($footer_banner as $key=>$banner)
            @if ($key == 1)
                <div class="container rtl pt-4 d-sm-none">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <a href="{{$banner->url}}" class="d-block">
                                <img class="footer_banner_img __inline-63"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}">
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @endforeach
        @endif
        <!-- Banner  -->
        @if ($footer_banner->count() > 0 )
            <div class="container rtl d-md-block d-none">
                <div class="row g-3">
                    @foreach($footer_banner as $banner)
                        <div class="col-md-6">
                            <a href="{{$banner->url}}" class="d-block">
                                <img class="footer_banner_img __inline-63"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/banner')}}/{{$banner['photo']}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <!-- Brands -->
        @if($web_config['brand_setting'] && $brands->count() > 0)
            <section class="container rtl pt-4">
                <!-- Heading-->
                <div class="section-header">
                    <div class="text-black font-bold __text-22px">
                        <span> {{translate('brands')}}</span>
                    </div>
                    <div class="__mr-2px">
                        <a class="text-capitalize view-all-text" href="{{route('brands')}}" style="color: {{$web_config['primary_color']}}!important">
                            {{ translate('view_all')}}
                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                        </a>
                    </div>
                </div>
                <!-- Grid-->
                <div class="mt-sm-3 mb-3 brand-slider">
                    <div class="owl-carousel owl-theme p-2 brands-slider pb-5 pb-sm-0">
                        @foreach($brands as $brand)
                            <div class="text-center">
                                <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}"
                                   class="__brand-item">
                                    <img
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset("storage/app/public/brand/$brand->image")}}"
                                        alt="{{$brand->name}}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Categorized product -->
        @if ($home_categories->count() > 0)
            @foreach($home_categories as $category)
                @include('web-views.partials._category-wise-product')
            @endforeach
        @endif

        <!--delivery type -->
        @php($company_reliability = \App\CPU\Helpers::get_business_settings('company_reliability'))
        @if($company_reliability != null)
            @include('web-views.partials._company-reliability')
        @endif
    </div>
@endsection

@push('script')
    <script>
        /*--flash deal Progressbar --*/
        function update_flash_deal_progress_bar(){
            const current_time_stamp = new Date().getTime();
            const start_date = new Date('{{$web_config['flash_deals']['start_date'] ?? ''}}').getTime();
            const countdownElement = document.querySelector('.cz-countdown');
            const get_end_time = countdownElement.getAttribute('data-countdown');
            const end_time = new Date(get_end_time).getTime();
            let time_progress = ((current_time_stamp - start_date) / (end_time - start_date))*100;
            const progress_bar = document.querySelector('.flash-deal-progress-bar');
            progress_bar.style.width = time_progress + '%';
        }
            update_flash_deal_progress_bar();
            setInterval(update_flash_deal_progress_bar, 10000);
        /*-- end flash deal Progressbar --*/
    </script>

    <!-- Owl Carousel -->
    <script src="{{asset('public/assets/front-end')}}/js/owl.carousel.min.js"></script>

    <script>
        $('.flash-deal-slider').owlCarousel({
            loop: false,
            autoplay: true,
            center:false,
            margin: 10,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1.1
                },
                360: {
                    items: 1.2
                },
                375: {
                    items: 1.4
                },
                480: {
                    items: 1.8
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
            }
        })
        $('.flash-deal-slider-mobile').owlCarousel({
            loop: false,
            autoplay: true,
            center:true,
            margin: 10,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1.1
                },
                360: {
                    items: 1.2
                },
                375: {
                    items: 1.4
                },
                480: {
                    items: 1.8
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 4
                },
            }
        })

        $('#web-feature-deal-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 20,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 2
                },
                //Extra extra large
                1400: {
                    items: 2
                }
            }
        })

        $('.new-arrivals-product').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}'></i>", "<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1.1
                },
                360: {
                    items: 1.2
                },
                375: {
                    items: 1.4
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })

        $('.category-wise-product-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}'></i>", "<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            responsive: {
                0: {
                    items: 1.2
                },
                375: {
                    items: 1.4
                },
                425: {
                    items: 2
                },
                576: {
                    items: 3
                },
                768: {
                    items: 4
                },
                992: {
                    items: 5
                },
                1200: {
                    items: 6
                },
            }
        })
    </script>
    <script>
        $('#featured_products_list').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 6
                },
            }
        });
    </script>
    <script>
        $('.hero-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // '{{session('direction')}}': false,
            // center: true,
            items: 1
        });
    </script>
    <script>
        $('.brands-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 10,
            nav: false,
            '{{session('direction')}}': true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 4,
                    dots: true,
                },
                360: {
                    items: 5,
                    dots: true,
                },
                //Small
                576: {
                    items: 6,
                    dots: false,
                },
                //Medium
                768: {
                    items: 7,
                    dots: false,
                },
                //Large
                992: {
                    items: 9,
                    dots: false,
                },
                //Extra large
                1200: {
                    items: 11,
                    dots: false,
                },
                //Extra extra large
                1400: {
                    items: 12,
                    dots: false,
                }
            }
        })
    </script>

    <script>
        $('#category-slider, #top-seller-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 20,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 6
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 10
                },
                //Extra extra large
                1400: {
                    items: 11
                }
            }
        })
        $('.categories--slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 20,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 3
                },
                360: {
                    items: 3.2
                },
                375: {
                    items: 3.5
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 6
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 10
                },
                //Extra extra large
                1400: {
                    items: 11
                }
            }
        })
    </script>
    <script>
        // Others Store Slider
        const othersStore = $(".others-store-slider").owlCarousel({
            responsiveClass: true,
            nav: false,
            dots: false,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            rtl: {{ session()->get('direction') == 'rtl' ? true : 'false'}},
            responsive: {
                0: {
                    items: 1.3,
                    margin: 10,
                },
                480: {
                    items: 2,
                    margin: 26,
                },
                768: {
                    items: 2,
                    margin: 26,
                },
                992: {
                    items: 3,
                    margin: 26,
                },
                1200: {
                    items: 4,
                    margin: 26,
                },
            },
        });
        $(".store-next").on("click", function () {
            othersStore.trigger("next.owl.carousel", [600]);
        });
        $(".store-prev").on("click", function () {
            othersStore.trigger("prev.owl.carousel", [600]);
        });
    </script>
@endpush

