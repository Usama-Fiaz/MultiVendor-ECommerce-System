@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Online_Shopping').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3">
        <!-- Main Banner -->
        @include('theme-views.partials._main-banner')

        <!-- Flash Deal -->
        @if ($web_config['flash_deals'])
            @include('theme-views.partials._flash-deals')
        @endif

        <!-- Find What You Need -->
        @include('theme-views.partials._find-what-you-need')

        <!-- Top Stores -->
        @if ($web_config['business_mode'] == 'multi' && count($top_sellers) > 0)
            @include('theme-views.partials._top-stores')
        @endif

        <!-- Featured Deals -->
        @if ($web_config['featured_deals']->count()>0)
            @include('theme-views.partials._featured-deals')
        @endif

        <!-- Recommended For You -->
        @include('theme-views.partials._recommended-product')

        <!-- More Stores -->
        @if($web_config['business_mode'] == 'multi')
            @include('theme-views.partials._more-stores')
        @endif

        <!-- Top Rated Products -->
        @include('theme-views.partials._top-rated-products')

        <!-- Today’s Best Deal an Just for you -->
        @include('theme-views.partials._best-deal-just-for-you')

        <!-- Home Categories -->
        @include('theme-views.partials._home-categories')

        <!-- Call To Action -->
        @if (isset($main_section_banner))
        <section class="">
            <div class="container">
                <div class="py-5 rounded position-relative">
                    <img src="{{asset('storage/app/public/banner')}}/{{$main_section_banner ? $main_section_banner['photo'] : ''}}"
                         onerror="this.src='{{theme_asset('assets/img/main-section-banner-placeholder.png')}}'"
                         alt="" class="rounded position-absolute dark-support img-fit start-0 top-0 index-n1 flipX-in-rtl">
                    <div class="row justify-content-center">
                        <div class="col-10 py-4">
                            <h6 class="text-primary mb-2">{{ translate('Do_not_Miss_Todays_Deal') }}!</h6>
                            <h2 class="fs-2 mb-4 absolute-dark">{{ translate('Let_us_Shopping_Today') }}</h2>
                            <div class="d-flex">
                                <a href="{{$main_section_banner ? $main_section_banner->url:''}}" class="btn btn-primary fs-16">{{ translate('Shop_Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>
@endsection

