@extends('layouts.front-end.app')

@section('title',translate($data['data_from']).' '.translate('products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="og:title" content="Products of {{$web_config['name']}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="twitter:title" content="Products of {{$web_config['name']}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <style>
        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;;
        }

        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;
        }

        .for-brand-hover:hover {
            color: {{$web_config['primary_color']}};
        }

        .for-hover-lable:hover {
            color: {{$web_config['primary_color']}}       !important;
        }

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}      !important;
        }

        .for-shoting {
            padding- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 9px;
        }

        .sidepanel {
        {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
        }
        .sidepanel .closebtn {
        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 25 px;
        }
        @media (max-width: 360px) {
            .for-shoting-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0% !important;
            }

            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

        }

        @media (max-width: 500px) {
            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 27%;
            }
        }
        /**/
    </style>
@endpush

@section('content')

@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
    <!-- Page Title-->
    <div class="container py-3" dir="{{Session::get('direction')}}">
        <div class="search-page-header">
            <div>
                <h5 class="font-semibold mb-1">{{translate(str_replace('_',' ',$data['data_from']))}} {{translate('products')}} {{ isset($data['brand_name']) ? '('.$data['brand_name'].')' : ''}}</h5>
                <div class="view-page-item-count">{{$products->total()}} {{translate('items_found')}}</div>
            </div>
            <form id="search-form" class="d-none d-lg-block" action="{{ route('products') }}" method="GET">
                <input hidden name="data_from" value="{{$data['data_from']}}">
                <div class="sorting-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                        <path d="M11.6667 7.80078L14.1667 5.30078L16.6667 7.80078" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.91675 4.46875H4.58341C4.3533 4.46875 4.16675 4.6553 4.16675 4.88542V8.21875C4.16675 8.44887 4.3533 8.63542 4.58341 8.63542H7.91675C8.14687 8.63542 8.33341 8.44887 8.33341 8.21875V4.88542C8.33341 4.6553 8.14687 4.46875 7.91675 4.46875Z" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.91675 11.9688H4.58341C4.3533 11.9688 4.16675 12.1553 4.16675 12.3854V15.7188C4.16675 15.9489 4.3533 16.1354 4.58341 16.1354H7.91675C8.14687 16.1354 8.33341 15.9489 8.33341 15.7188V12.3854C8.33341 12.1553 8.14687 11.9688 7.91675 11.9688Z" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14.1667 5.30078V15.3008" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <label class="for-shoting" for="sorting">
                        <span>{{translate('sort_by')}}</span>
                    </label>
                    <select onchange="filter(this.value)">
                        <option value="latest">{{translate('latest')}}</option>
                        <option
                            value="low-high">{{translate('low_to_High_Price')}} </option>
                        <option
                            value="high-low">{{translate('High_to_Low_Price')}}</option>
                        <option
                            value="a-z">{{translate('A_to_Z_Order')}}</option>
                        <option
                            value="z-a">{{translate('Z_to_A_Order')}}</option>
                    </select>
                </div>
            </form>
            <div class="d-lg-none">
                <div class="filter-show-btn btn btn--primary py-1 px-2 m-0">
                    <i class="tio-filter"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 rtl __inline-35" dir="{{Session::get('direction')}}">
        <div class="row">
            <!-- Sidebar-->
            <aside
                class="col-lg-3 hidden-xs col-md-3 col-sm-4 SearchParameters __search-sidebar {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}"
                id="SearchParameters">
                <!--Price Sidebar-->
                <div class="cz-sidebar __inline-35" id="shop-sidebar">
                    <div class="cz-sidebar-header bg-light">
                        <button class="close {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}" type="button" data-dismiss="sidebar" aria-label="Close">
                            <i class="tio-clear"></i>
                        </button>
                    </div>
                    <div class="pb-0">
                        <div class="text-center">
                            <div class="__cate-side-title border-bottom">
                                <span class="widget-title font-semibold">{{translate('filter')}} </span>
                            </div>
                            <div class="__p-25-10 w-100 pt-4">
                                <label class="w-100 opacity-75 text-nowrap for-shoting d-block mb-0" for="sorting" style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0">
                                    <select class="form-control custom-select" id="searchByFilterValue">
                                        <option selected disabled>{{translate('choose')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'selected':'':''}}>{{translate('best_selling_product')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'selected':'':''}}>{{translate('top_rated')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='most-favorite'?'selected':'':''}}>{{translate('most_favorite')}}</option>
                                        <option
                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'selected':'':''}}>{{translate('featured_deal')}}</option>
                                    </select>
                                </label>
                            </div>

                            <div class="__p-25-10 w-100 pt-0 d-lg-none">
                                <form id="search-form" action="{{ route('products') }}" method="GET">
                                    <input hidden name="data_from" value="{{$data['data_from']}}">
                                    <select class="form-control" onchange="filter(this.value)">
                                        <option value="latest">{{translate('latest')}}</option>
                                        <option
                                            value="low-high">{{translate('low_to_High_Price')}} </option>
                                        <option
                                            value="high-low">{{translate('High_to_Low_Price')}}</option>
                                        <option
                                            value="a-z">{{translate('A_to_Z_Order')}}</option>
                                        <option
                                            value="z-a">{{translate('Z_to_A_Order')}}</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-center">
                            <div class="__cate-side-title pt-0">
                                <span class="widget-title font-semibold">{{translate('price')}} </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center __cate-side-price">
                                <div class="__w-35p">
                                    <input class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                           type="number" value="0" min="0" max="1000000" id="min_price" placeholder="{{ translate('min')}}">

                                </div>
                                <div class="__w-10p">
                                    <p class="m-0">{{translate('to')}}</p>
                                </div>
                                <div class="__w-35p">
                                    <input value="100" min="100" max="1000000"
                                           class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                           type="number" id="max_price"  placeholder="{{ translate('max')}}">

                                </div>

                                <div class="d-flex justify-content-center align-items-center __number-filter-btn">

                                    <a class="" onclick="searchByPrice()">
                                        <i class="__inline-37 czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>

                    @if($web_config['brand_setting'])
                    <div>
                        <div class="text-center">
                            <div class="__cate-side-title">
                                <span class="widget-title font-semibold">{{translate('brands')}}</span>
                            </div>
                            <div class="__cate-side-price pb-3">
                                <div class="input-group-overlay input-group-sm">
                                    <input style="{{Session::get('direction') === "rtl" ? 'padding-right: 32px;' : ''}}" placeholder="{{ translate('search_by_brands') }}"
                                        class="__inline-38 cz-filter-search form-control form-control-sm appended-form-control"
                                        type="text" id="search-brand">
                                    <div class="input-group-append-overlay">
                                        <span class="input-group-text">
                                            <i class="czi-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <ul id="lista1" class="__brands-cate-wrap" data-simplebar data-simplebar-auto-hide="false">
                                @foreach(\App\CPU\BrandManager::get_active_brands() as $brand)
                                    <div class="brand mt-2 for-brand-hover {{Session::get('direction') === "rtl" ? 'mr-2' : ''}}" id="brand">
                                        <li class="flex-between __inline-39"
                                            onclick="location.href='{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}'">
                                            <div >
                                                {{ $brand['name'] }}
                                            </div>
                                            @if($brand['brand_products_count'] > 0 )
                                                <div class="__brands-cate-badge">
                                                    <span>
                                                    {{ $brand['brand_products_count'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </li>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <div class="mt-3 __cate-side-arrordion">
                        <!-- Categories-->
                        <div>
                            <div  class="text-center __cate-side-title">
                                <span class="widget-title font-semibold">{{translate('categories')}}</span>
                            </div>
                            @php($categories=\App\CPU\CategoryManager::parents())
                            <div class="accordion mt-n1 __cate-side-price" id="shop-categories">
                                @foreach($categories as $category)
                                    <div class="menu--caret-accordion">
                                        <div class="card-header flex-between">
                                            <div>
                                                <label class="for-hover-lable cursor-pointer" onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                    {{$category['name']}}
                                                </label>
                                            </div>
                                            <div class="px-2 cursor-pointer menu--caret">
                                                <strong class="pull-right for-brand-hover">
                                                    @if($category->childes->count()>0)
                                                        <i class="tio-next-ui fs-13"></i>
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                             id="collapse-{{$category['id']}}"
                                             style="display: none">
                                            @foreach($category->childes as $child)
                                                <div class="menu--caret-accordion">
                                                    <div class="for-hover-lable card-header flex-between">
                                                        <div>
                                                            <label class="cursor-pointer" onclick="location.href='{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}'">
                                                                {{$child['name']}}
                                                            </label>
                                                        </div>
                                                        <div class="px-2 cursor-pointer menu--caret">
                                                            <strong class="pull-right">
                                                                @if($child->childes->count()>0)
                                                                    <i class="tio-next-ui fs-13"></i>
                                                                @endif
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                        id="collapse-{{$child['id']}}"
                                                        style="display: none">
                                                        @foreach($child->childes as $ch)
                                                            <div class="card-header">
                                                                <label class="for-hover-lable d-block cursor-pointer text-left" onclick="location.href='{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}'">
                                                                    {{$ch['name']}}
                                                                </label>
                                                            </div>
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
                </div>

            </aside>

            <!-- Content  -->
            <section class="col-lg-9">
                @if (count($products) > 0)
                    <div class="row" id="ajax-products">
                        @include('web-views.products._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                @else
                    <div class="text-center pt-5 text-capitalize">

                        <img src="{{asset('public/assets/front-end/img/icons/product.svg')}}" alt="">
                        <h5>{{translate('no_product_found')}}!</h5>
                        <p class="text-center text-muted">{{translate('sorry_no_data_found_related_to_your_search')}}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "70%";
            document.getElementById("mySidepanel").style.height = "100vh";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function filter(value) {
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    min_price: '{{$data['min_price']}}',
                    max_price: '{{$data['max_price']}}',
                    sort_by: value
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    sort_by: '{{$data['sort_by']}}',
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                    $('#paginator-ajax').html(response.paginator);
                    console.log(response.total_product);
                    $('#price-filter-count').text(response.total_product + ' {{translate('items_found')}}')
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $('#searchByFilterValue, #searchByFilterValue-m').change(function () {
            var url = $(this).val();
            if (url) {
                window.location = url;
            }
            return false;
        });

        $("#search-brand").on("keyup", function () {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function () {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });

		$(".menu--caret").on("click", function (e) {
			var element = $(this).closest(".menu--caret-accordion");
			if (element.hasClass("open")) {
				element.removeClass("open");
				element.find(".menu--caret-accordion").removeClass("open");
				element.find(".card-body").slideUp(300, "swing");
			} else {
				element.addClass("open");
				element.children(".card-body").slideDown(300, "swing");
				element.siblings(".menu--caret-accordion").children(".card-body").slideUp(300, "swing");
				element.siblings(".menu--caret-accordion").removeClass("open");
				element.siblings(".menu--caret-accordion").find(".menu--caret-accordion").removeClass("open");
				element.siblings(".menu--caret-accordion").find(".card-body").slideUp(300, "swing");
			}
		});


    </script>


@endpush
