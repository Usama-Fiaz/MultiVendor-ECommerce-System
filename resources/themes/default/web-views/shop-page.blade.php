@extends('layouts.front-end.app')

@section('title',translate('shop_Page'))

@push('css_or_js')
    @if($shop['id'] != 0)
        <meta property="og:image" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="og:title" content="{{ $shop->name}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="og:title" content="{{ $shop['name']}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    @if($shop['id'] != 0)
        <meta property="twitter:card" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="twitter:card"
              content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @endif

    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">


    <link href="{{asset('public/assets/front-end')}}/css/home.css" rel="stylesheet">
    <style>

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}                        !important;
        }

        /*  */
    </style>
@endpush

@section('content')

    @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
    <!-- Page Content-->
    <div class="container py-4 __inline-67">
        <div class="rtl">
            <!-- banner  -->
            <div class="bg-white __shop-banner-main">
                @if($shop['id'] != 0)
                    <img class="__shop-page-banner"
                            src="{{asset('storage/app/public/shop/banner')}}/{{$shop->banner}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            alt="">
                @else
                    @php($banner=\App\CPU\Helpers::get_business_settings('shop_banner'))
                    <img class="__shop-page-banner"
                            src="{{asset("storage/app/public/shop")}}/{{$banner??""}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            alt="">
                @endif
                <!-- seller info+contact -->
                <div class="position-relatve z-index-99 rtl w-100" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="__rounded-10 bg-white position-relative">
                        <div class="d-flex flex-wrap justify-content-between seller-details">
                            <!-- logo -->
                            <div class="d-flex align-items-center p-2 flex-grow-1">
                                <div class="">
                                    @if($shop['id'] != 0)
                                        <div class="position-relative">
                                            @if($seller_temporary_close || $inhouse_temporary_close)
                                                <span class="temporary-closed-details">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                            $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                                <span class="temporary-closed-details">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                            <img class="__inline-68"
                                                src="{{asset('storage/app/public/shop')}}/{{$shop->image}}"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                alt="">
                                        </div>
                                    @else
                                        <div class="position-relative">
                                            @if($seller_temporary_close || $inhouse_temporary_close)
                                                <span class="temporary-closed-details">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @elseif(($seller_id==0 && $inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                            $seller_id!=0 && $seller_vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date)
                                                <span class="temporary-closed-details">
                                                    <span>{{translate('closed_now')}}</span>
                                                </span>
                                            @endif
                                            <img class="__inline-68"
                                                src="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                alt="">
                                        </div>
                                    @endif
                                </div>
                                <div class="__w-100px flex-grow-1 {{Session::get('direction') === "rtl" ? ' pr-2 pr-sm-4' : ' pl-2 pl-sm-4'}}">
                                    <span class="font-weight-bold ">
                                        @if($shop['id'] != 0)
                                            {{ $shop->name}}
                                        @else
                                            {{ $web_config['name']->value }}
                                        @endif
                                    </span>
                                    <div class="">
                                        <div class="d-flex flex-wrap __text-12px py-1 fw-bold" style="color : {{$web_config['primary_color']}}">
                                            <span class="text-nowrap">{{ $total_review}} {{translate('reviews')}} </span>

                                            <span class="__inline-69"></span>

                                            <span class="text-nowrap">{{ $total_order}} {{translate('orders')}}</span>
                                            @php($minimum_order_amount_status=\App\CPU\Helpers::get_business_settings('minimum_order_amount_status'))
                                            @php($minimum_order_amount_by_seller=\App\CPU\Helpers::get_business_settings('minimum_order_amount_by_seller'))
                                            @if ($minimum_order_amount_status ==1 && $minimum_order_amount_by_seller ==1)
                                                <span class="__inline-69"></span>
                                                @if($shop['id'] == 0)
                                                    @php($minimum_order_amount=\App\CPU\Helpers::get_business_settings('minimum_order_amount'))
                                                    <span>{{ \App\CPU\Helpers::currency_converter($minimum_order_amount)}} {{translate('minimum_order_amount')}}</span>
                                                @else
                                                    <span>{{ \App\CPU\Helpers::currency_converter($shop->seller->minimum_order_amount)}} {{translate('minimum_order_amount')}}</span>
                                                @endif
                                            @endif
                                        </div>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <=$avg_rating)
                                                    <i class="tio-star text-warning"></i>
                                                @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=((int)$avg_rating+.30))
                                                    <i class="tio-star-half text-warning"></i>
                                                @else
                                                    <i class="tio-star-outlined text-warning"></i>
                                                @endif
                                            @endfor
                                            (<span class="ml-1">{{round($avg_rating,1)}}</span>)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- contact -->
                            <div class="d-flex align-items-center">
                                <div class="{{Session::get('direction') === "rtl" ? 'ml-sm-4' : 'mr-sm-4'}}">
                                    @if($seller_id!=0)
                                        @if (auth('customer')->check())
                                            <div class="d-flex">
                                                <button class="btn btn--primary __inline-70 rounded-10  text-capitalize chat-with-seller-button d-none d-sm-inline-block" data-toggle="modal"
                                                        data-target="#exampleModal" {{ ($shop->temporary_close || ($shop->vacation_status && date('Y-m-d') >= date('Y-m-d', strtotime($shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($shop->vacation_end_date)))) ? 'disabled' : '' }}>
                                                    <img src="{{asset('/public/assets/front-end/img/shopview-chat.png')}}" loading="eager" class="" alt="">
                                                    <span class="d-none d-sm-inline-block">
                                                        {{translate('chat_with_seller')}}
                                                    </span>
                                                </button>

                                                <button class="btn bg-transparent border-0 __inline-70 rounded-10  text-capitalize chat-with-seller-button d-sm-inline-block d-md-none" data-toggle="modal"
                                                        data-target="#exampleModal" {{ ($shop->temporary_close || ($shop->vacation_status && date('Y-m-d') >= date('Y-m-d', strtotime($shop->vacation_start_date)) && date('Y-m-d') <= date('Y-m-d', strtotime($shop->vacation_end_date)))) ? 'disabled' : '' }}>
                                                    <img src="{{asset('/public/assets/front-end/img/icons/shopview-chat-blue.svg')}}" loading="eager" class="" alt="">
                                                </button>


                                            </div>
                                        @else
                                            <div class="d-flex">
                                                <a href="{{route('customer.auth.login')}}"
                                                class="btn btn--primary __inline-70 rounded-10  text-capitalize chat-with-seller-button">
                                                    <img src="{{asset('/public/assets/front-end/img/shopview-chat.png')}}" loading="eager" class="" alt="">
                                                    <span class="d-none d-sm-inline-block">
                                                        {{translate('chat_with_seller')}}
                                                    </span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 justify-content-sm-between py-4" dir="{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
            <div class="d-flex flex-wrap justify-content-between align-items-center w-max-md-100 me-auto gap-3">
                <h3 class="widget-title align-self-center font-bold __text-18px my-0">{{translate('categories')}}</h3>
                <div class="filter-ico-button btn btn--primary p-2 m-0 d-lg-none d-flex align-items-center">
                    <i class="tio-filter"></i>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <!-- Static Filter Form -->
                <form>
                    <div class="sorting-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                            <path d="M11.6667 7.80078L14.1667 5.30078L16.6667 7.80078" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.91675 4.46875H4.58341C4.3533 4.46875 4.16675 4.6553 4.16675 4.88542V8.21875C4.16675 8.44887 4.3533 8.63542 4.58341 8.63542H7.91675C8.14687 8.63542 8.33341 8.44887 8.33341 8.21875V4.88542C8.33341 4.6553 8.14687 4.46875 7.91675 4.46875Z" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.91675 11.9688H4.58341C4.3533 11.9688 4.16675 12.1553 4.16675 12.3854V15.7188C4.16675 15.9489 4.3533 16.1354 4.58341 16.1354H7.91675C8.14687 16.1354 8.33341 15.9489 8.33341 15.7188V12.3854C8.33341 12.1553 8.14687 11.9688 7.91675 11.9688Z" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.1667 5.30078V15.3008" stroke="#D9D9D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <label class="for-shoting" for="sorting">
                            <span class="text-nowrap">{{translate('sort_by')}}</span>
                        </label>
                        <select onchange="sort_by_data(this.value)">
                            <option value="latest">{{translate('latest')}}</option>
                            <option
                                value="low-high">{{translate('low_to_High_Price')}} </option>
                            <option
                                value="high-low">{{translate('high_to_Low_Price')}}</option>
                            <option
                                value="a-z">{{translate('A_to_Z_Order')}}</option>
                            <option
                                value="z-a">{{translate('Z_to_A_Order')}}</option>
                        </select>
                    </div>
                </form>
                <!-- shopView -->
                <form method="get" action="{{route('shopView',['id'=>$seller_id])}}">
                    <div class="search_form input-group search-form-input-group">
                        <input type="hidden" name="category_id" value="{{request('category_id')}}" >
                        <input type="hidden" name="sub_category_id" value="{{request('sub_category_id')}}" >
                        <input type="hidden" name="sub_sub_category_id" value="{{request('sub_sub_category_id')}}" >
                        <input type="text" class="form-control rounded-left" name="product_name" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" value="{{request('product_name')}}" placeholder="{{translate('search_products_from_this_store')}}">
                        <button type="submit" class="btn--primary btn">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="row rtl">
            <div class="col-lg-3 mr-0 {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                <aside class="SearchParameters" id="SearchParameters">
                    <!-- Categories Sidebar-->
                    <div class="__shop-page-sidebar">
                        <div class="cz-sidebar-header">
                            <button class="close {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}" type="button" data-dismiss="sidebar" aria-label="Close">
                                <i class="tio-clear"></i>
                            </button>
                        </div>
                        <div class="accordion __cate-side-arrordion">
                            @foreach($categories as $category)
                                <div class="menu--caret-accordion">

                                <div class="card-header flex-between">
                                    <div>
                                        <label class="for-hover-lable cursor-pointer" onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}'">
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
                                                    <label class="cursor-pointer" onclick="location.href='{{route('shopView',['id'=> $seller_id,'sub_category_id'=>$child['id']])}}'">
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
                                                        <label class="for-hover-lable d-block cursor-pointer text-left" onclick="location.href='{{route('shopView',['id'=> $seller_id,'sub_sub_category_id'=>$ch['id']])}}'">
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
                </aside>
            </div>
            {{-- main body (Products) --}}
            <div class="col-lg-9 product-div">
                <!-- Products grid-->
                @if (count($products) > 0)
                    <div class="row g-3" id="ajax-products">
                        @include('web-views.products._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                @else
                    <div class="text-center pt-5 text-capitalize">
                        <img src="{{asset('public/assets/front-end/img/icons/product.svg')}}" alt="">
                        <h5>{{translate('no_product_found')}}!</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <span id="filter_url" data-url="{{url('/')}}/shopView/{{$shop['id']}}"></span>
    <!-- modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-faded-info">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{translate('Send_Message_to_seller')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('messages_store')}}" method="post" id="chat-form">
                        @csrf
                        @if($shop['id'] != 0)
                            <input value="{{$shop->id}}" name="shop_id" hidden>
                            <input value="{{$shop->seller_id}}}" name="seller_id" hidden>
                        @endif

                        <textarea name="message" class="form-control" required placeholder="{{ translate('Write_here') }}..."></textarea>
                        <br>

                        <div class="justify-content-end gap-2 d-flex flex-wrap">
                            <a href="{{route('chat', ['type' => 'seller'])}}" class="btn btn-soft-primary bg--secondary border">
                                {{translate('go_to_chatbox')}}
                            </a>
                            @if($shop['id'] != 0)
                                <button
                                    class="btn btn--primary text-white">{{translate('send')}}</button>
                            @else
                                <button class="btn btn--primary text-white"
                                        disabled>{{translate('send')}}</button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.filter-ico-button').on('click', function(){
            $('.__shop-page-sidebar').toggleClass('active')
        })
        $('.close').on('click', function(){
            $('.__shop-page-sidebar').removeClass('active')
        })
    </script>

    <script>
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{route('messages_store')}}',
                data: $('#chat-form').serialize(),
                success: function (respons) {

                    toastr.success('{{translate("send_successfully")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
    </script>
    <script>
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
        function sort_by_data(value) {
            $.get({
                url: $("#filter_url").data("url"),
                data: {
                    sort_by: value,
                    category_id : '{{request('category_id')}}',
                    sub_category_id : '{{request('sub_category_id')}}',
                    sub_sub_category_id : '{{request('sub_sub_category_id')}}',
                    product_name : '{{request('product_name')}}',

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
    </script>
@endpush
