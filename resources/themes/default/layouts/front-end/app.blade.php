<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180"
          href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}">
    <link rel="icon" type="image/png" sizes="32x32"
          href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}">

    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/tiny-slider/dist/tiny-slider.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/drift-zoom/dist/drift-basic.min.css"/>
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/lightgallery.js/dist/css/lightgallery.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/font-awesome.min.css">
    <!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/master.css"/>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Titillium+Web:wght@400;600;700&display=swap"
        rel="stylesheet">
    {{-- light box --}}
    <link rel="stylesheet" href="{{asset('public/css/lightbox.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/vendor/icon-set/style.css">
    @stack('css_or_js')

    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/home.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/responsive1.css"/>


    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/style.css">
    {{--dont touch this--}}
    <meta name="_token" content="{{csrf_token()}}">
    {{--dont touch this--}}
    <!--to make http ajax request to https-->
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <style>
        .rtl {
            direction: {{ Session::get('direction') }};
        }

        .dropdown-item:hover, .dropdown-item:focus {
            color: {{$web_config['primary_color']}};
        }

        .dropdown-item.active, .dropdown-item:active {
            color: {{$web_config['secondary_color']}};
        }

        .navbar-light .navbar-tool-icon-box {
            color: {{$web_config['primary_color']}};
        }

        .search_button {
            background-color: {{$web_config['primary_color']}};
        }


        .navbar-stuck-menu {
            background-color: {{$web_config['primary_color']}};
        }

        .mega-nav .nav-item .nav-link {
            color: {{$web_config['primary_color']}}                           !important;
        }
        .owl-theme .owl-nav [class*="owl-"],
        .checkbox-alphanumeric label:hover {
            border-color: {{$web_config['primary_color']}};
        }

        ::-webkit-scrollbar-thumb:hover {
            background: {{$web_config['secondary_color']}}        !important;
        }

        [type="radio"] {
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        [type="radio"] + span:after {
            box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}};
        }

        [type="radio"]:checked + span:after {
            background: {{$web_config['secondary_color']}};
            box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}};
        }
        .navbar-tool .navbar-tool-label {
            background-color: {{$web_config['secondary_color']}}!important;
        }

        .btn--primary {
            color: #fff;
            background-color: {{$web_config['primary_color']}}!important;
            border-color: {{$web_config['primary_color']}}!important;
        }

        .btn--primary:hover {
            color: #fff;
            background-color: {{$web_config['primary_color']}}!important;
            border-color: {{$web_config['primary_color']}}!important;
        }

        .btn-secondary {
            background-color: {{$web_config['secondary_color']}}!important;
            border-color: {{$web_config['secondary_color']}}!important;
        }

        .btn-outline-accent:hover {
            color: #fff;
            background-color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-accent {
            color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .text-accent {
            color: {{$web_config['primary_color']}};
        }

        .text-base-2,
        a:hover {
            color: {{$web_config['secondary_color']}};
        }

        .text-primary ,
        .text-base,
        .active-menu {
            color: {{$web_config['primary_color']}}!important;
        }

        .page-item.active > .page-link {
            box-shadow: 0 0.5rem 1.125rem -0.425rem{{$web_config['primary_color']}}


        }

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}};
        }

        .btn-outline-accent:not(:disabled):not(.disabled):active, .btn-outline-accent:not(:disabled):not(.disabled).active, .show > .btn-outline-accent.dropdown-toggle {
            background-color: {{$web_config['secondary_color']}};
            border-color: {{$web_config['secondary_color']}};
        }

        .btn-outline-primary {
            color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-primary:hover {
            background-color: {{$web_config['secondary_color']}};
            border-color: {{$web_config['secondary_color']}};
        }

        .btn-outline-primary:focus, .btn-outline-primary.focus {
            box-shadow: 0 0 0 0{{$web_config['secondary_color']}};
        }

        .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .show > .btn-outline-primary.dropdown-toggle {
            background-color: {{$web_config['primary_color']}};
            border-color: {{$web_config['primary_color']}};
        }

        .btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-primary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0{{$web_config['primary_color']}};
        }
        .for-discoutn-value {
            background: {{$web_config['primary_color']}};
        }
        .dropdown-menu {
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -8px !important;
        }
        :root {
            --base: {{$web_config['primary_color']}};
            --base-2: {{$web_config['secondary_color']}};
        }
        span.badge-accent {
            color: var(--base);
            background-color: {{$web_config['primary_color']}}40;
        }
        span.badge-accent:hover {
            color: var(--base) !important;
        }
    </style>

    @php($google_tag_manager_id = \App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
    @if($google_tag_manager_id )
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{$google_tag_manager_id}}');</script>
    <!-- End Google Tag Manager -->

    @endif

    @php($pixel_analytices_user_code =\App\CPU\Helpers::get_business_settings('pixel_analytics'))
    @if($pixel_analytices_user_code)
        <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $pixel_analytices_user_code }}');
            fbq('track', 'PageView');
            </script>
            <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $pixel_analytices_user_code }}&ev=PageView&noscript=1"/>
            </noscript>
        <!-- End Facebook Pixel Code -->
    @endif
</head>
<!-- Body-->
<body class="toolbar-enabled">
    @if($google_tag_manager_id)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{$google_tag_manager_id}}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <span id="order_again_url" data-url="{{ route('cart.order-again') }}"></span>

<!-- Sign in / sign up modal-->
@include('layouts.front-end.partials._modals')
<!-- Navbar-->
<!-- Quick View Modal-->
@include('layouts.front-end.partials._quick-view-modal')
<!-- Navbar Electronics Store-->
@include('layouts.front-end.partials._header')
<!-- Page title-->

<span id="authentication-status" data-auth="{{ auth('customer')->check() ? 'true' : 'false' }}"></span>

{{--loader--}}
    <div class="row">
        <div class="col-12" style="margin-top:10rem;position: fixed;z-index: 9999;">
            <div id="loading" style="display: none;">
               <center>
                <img width="200"
                     src="{{asset('storage/app/public/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
                     onerror="this.src='{{asset('public/assets/front-end/img/loader.gif')}}'">
               </center>
            </div>
        </div>
    </div>
{{--loader--}}

<!-- Page Content-->
@yield('content')

<span id="update_nav_cart_url" data-url="{{route('cart.nav-cart')}}"></span>
<span id="remove_from_cart_url" data-url="{{ route('cart.remove') }}"></span>
<span id="update_quantity_url" data-url="{{route('cart.updateQuantity.guest')}}"></span>
<span id="order_again_url" data-url="{{ route('cart.order-again') }}"></span>
<!-- Footer-->
<!-- Footer-->
@include('layouts.front-end.partials._footer')
@include('layouts.front-end.partials.modal._dynamic-modals')


<!-- Toolbar for handheld devices-->

<!-- Back To Top Button-->
<a class="btn-scroll-top btn--primary" href="#top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">{{translate('top')}}</span><i
        class="btn-scroll-top-icon czi-arrow-up"> </i>
</a>
<div class="__floating-btn">
    @php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="wa-widget-send-button">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{asset('public/assets/front-end/img/whatsapp.svg')}}" class="wa-messenger-svg-whatsapp wh-svg-icon" alt="Chat with us on WhatsApp">
            </a>
        </div>
    @endif

    <!-- Vendor scrits: js libraries and plugins-->
</div>

{{--<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery.slim.min.js"></script>--}}
<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script
    src="{{asset('public/assets/front-end')}}/vendor/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

{{-- light box --}}
<script src="{{asset('public/js/lightbox.min.js')}}"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/drift-zoom/dist/Drift.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/lg-video.js/dist/lg-video.min.js"></script>
{{--Toastr--}}
<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
<!-- Main theme script-->
<script src="{{asset('public/assets/front-end')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/js/custom.js"></script>
<script src="{{asset('public/assets/front-end')}}/js/slick.min.js"></script>

<script src="{{asset('public/assets/front-end')}}/js/sweet_alert.js"></script>
{{--Toastr--}}
<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
{!! Toastr::message() !!}

<script>
    $(document).ready(function() {
        const $stickyElement = $('.bottom-sticky_ele');
        const $offsetElement = $('.bottom-sticky_offset');

        if($stickyElement.length !== 0){
            $(window).on('scroll', function() {
                const elementOffset = $offsetElement.offset().top - ($(window).height()/1.2);
                const scrollTop = $(window).scrollTop();

                if (scrollTop >= elementOffset) {
                    $stickyElement.addClass('stick');
                } else {
                    $stickyElement.removeClass('stick');
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('.password-toggle-btn').on('click',function(){
            let checkbox = $(this).find('input[type=checkbox]');
            let eyeIcon = $(this).find('i');
            checkbox.change(function () {
                if (checkbox.is(':checked')) {
                    eyeIcon.removeClass('tio-hidden').addClass('tio-invisible');
                } else {
                    eyeIcon.removeClass('tio-invisible').addClass('tio-hidden');
                }
            });
        })

    });
</script>

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

<script>
    function addWishlist(product_id, modalId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('store-wishlist')}}",
            method: 'POST',
            data: {
                product_id: product_id
            },
            success: function (data) {
                if (data.value == 1) {
                    $('.countWishlist').html(data.count);
                    $('.countWishlist-' + product_id).text(data.product_count);
                    $('.tooltip').html('');
                    $(`.wishlist_icon_${product_id}`).removeClass('fa fa-heart-o').addClass('fa fa-heart');
                    $('#add-wishlist-modal').modal('show');
                    $(`#${modalId}`).modal('show');
                } else if (data.value == 2) {
                    $('#remove-wishlist-modal').modal('show');
                    $('.countWishlist').html(data.count);
                    $('.countWishlist-' + product_id).text(data.product_count);
                    $(`.wishlist_icon_${product_id}`).removeClass('fa fa-heart').addClass('fa fa-heart-o');
                } else {
                    $('#login-alert-modal').modal('show');
                }
            }
        });
    }

    function removeWishlist(product_id, modalId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('delete-wishlist')}}",
            method: 'POST',
            data: {
                id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $(`#${modalId}`).modal('show');

                $('.countWishlist').html(parseInt($('.countWishlist').html())-1);
                $('#row_id'+product_id).hide();
                $('.tooltip').html('');
                if (parseInt($('.countWishlist').html()) % 15 === 0) {
                    if ($('#wishlist_paginated_page').val() == 1) {
                        $("#set-wish-list").empty().append(`
                            <center>
                                <h6 class="text-muted">
                                    {{ translate('no_data_found') }}.
                                </h6>
                            </center>
                        `);
                    } else {
                        let page_value =$('#wishlist_paginated_page').val();
                        window.location.href = '{{ route('wishlists', ['page' => '']) }}' + (page_value - 1);
                    }
                }
            },
            complete: function () {
                $('#loading').hide();
            },
        });


    }

    function quickView(product_id) {
        $.get({
            url: '{{route('quick-view')}}',
            dataType: 'json',
            data: {
                product_id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function addToCart(form_id = 'add-to-cart-form', redirect_to_checkout=false) {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('cart.add') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == 1) {
                        updateNavCart();
                        toastr.success(response.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('.call-when-done').click();
                        if(redirect_to_checkout)
                        {
                            location.href = "{{route('checkout-details')}}";
                        }
                        return false;
                    } else if (response.status == 0) {
                        $('#outof-stock-modal-message').html(response.message);
                        $('#outof-stock-modal').modal('show');
                        return false;
                    }
                },
                complete: function () {
                    $('#loading').hide();

                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: 'Cart',
                text: '{{ translate("please_choose_all_the_options")}}'
            });
        }
    }

    function buy_now() {
        addToCart('add-to-cart-form',true);
        /* location.href = "{{route('checkout-details')}}"; */
    }

    function currency_change(currency_code) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{route('currency.change')}}',
            data: {
                currency_code: currency_code
            },
            success: function (data) {
                toastr.success('{{ translate("currency_changed_to")}}' + data.name);
                location.reload();
            }
        });
    }

    function removeFromCart(key) {
        $.post('{{ route('cart.remove') }}', {_token: '{{ csrf_token() }}', key: key}, function (response) {
            $('#cod-for-cart').hide();
            updateNavCart();
            $('#cart-summary').empty().html(response.data);
            toastr.info('{{ translate('item_has_been_removed_from_cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
            let segment_array = window.location.pathname.split('/');
            let segment = segment_array[segment_array.length - 1];
            if(segment === 'checkout-payment' || segment === 'checkout-details'){
                location.reload();
            }
        });
    }

    function updateNavCart() {
        $.post('{{route('cart.nav-cart')}}', {_token: '{{csrf_token()}}'}, function (response) {
            $('#cart_items').html(response.data);
        });
    }
    /*new*/
    $("#add-to-cart-form").on("submit", function (e) {
        e.preventDefault();
    });

    /*new*/
    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                productType = $(this).attr('product-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt($('.input-number').val());
                // alert(currentVal);
            if (!isNaN(currentVal)) {
                    // console.log(productType)
                    if (type == 'minus') {

                        if (currentVal > $('.input-number').attr('min')) {
                        $('.input-number').val(currentVal - 1).change();
                    }
                        if (parseInt($('.input-number').val()) == $('.input-number').attr('min')) {
                            $(this).attr('disabled', true);
                    }

                    } else if (type == 'plus') {
                        // alert('ok out of stock');
                        if (currentVal < $('.input-number').attr('max') || (productType === 'digital')) {
                        $('.input-number').val(currentVal + 1).change();
                    }

                        if ((parseInt(input.val()) == $('.input-number').attr('max')) && (productType === 'physical')) {
                            $(this).attr('disabled', true);
                    }

                }
            } else {
                $('.input-number').val(0);
            }
        });

            $('.input-number').focusin(function () {
                $(this).data('oldValue', $(this).val());
        });

            $('.input-number').change(function () {
                productType = $(this).attr('product-type');
                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());
                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                        $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: '{{ translate("sorry_the_minimum_order_quantity_does_not_match")}}'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (productType === 'digital' || valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: '{{ translate("sorry_stock_limit_exceeded.") }}'
                    });
                    $(this).val($(this).data('oldValue'));
                }


        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if (
                $.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)
            ) {
                // let it happen, don't do anything
                 return;
            }
            // Ensure that it is a number and stop the keypress
            if (
                (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
                (e.keyCode < 96 || e.keyCode > 105)
            ) {
                e.preventDefault();
            }
        });
    }

    function updateQuantity(key, element) {
        $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
            _token: '<?php echo e(csrf_token()); ?>',
            key: key,
            quantity: element.value
        }, function (data) {
            updateNavCart();
            $('#cart-summary').empty().html(data);
        });
    }



    function updateCartQuantity(cart_id, product_id, action, event) {
        let remove_url = $("#remove_from_cart_url").data("url");
        let update_quantity_url = $("#update_quantity_url").data("url");
        let token = $('meta[name="_token"]').attr("content");
        let product_qyt =
            parseInt($(`.cartQuantity${cart_id}`).val()) + parseInt(action);
        let cart_quantity_of = $(`.cartQuantity${cart_id}`);
        let segment_array = window.location.pathname.split("/");
        let segment = segment_array[segment_array.length - 1];

        if (cart_quantity_of.val() == 0) {
            toastr.info($('.cannot_use_zero').data('text'), {
                CloseButton: true,
                ProgressBar: true,
            });
            cart_quantity_of.val(cart_quantity_of.data("min"));
        }else if (
            (cart_quantity_of.val() == cart_quantity_of.data("min") &&
                event == "minus")
        ) {
            $.post(
                remove_url,
                {
                    _token: token,
                    key: cart_id,
                },
                function (response) {
                    updateNavCart();
                    toastr.info(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                    });
                    if (
                        segment === "shop-cart" ||
                        segment === "checkout-payment" ||
                        segment === "checkout-details"
                    ) {
                        location.reload();
                    }
                }
            );
        } else {
            if(cart_quantity_of.val() < cart_quantity_of.data("min")){
                let min_value = cart_quantity_of.data("min");
                toastr.error('Minimum order quantity cannot be less than '+min_value);
                cart_quantity_of.val(min_value)
                updateCartQuantity(cart_id, product_id, action, event)
            }else{
                $(`.cartQuantity${cart_id}`).html(product_qyt);
                $.post(
                    update_quantity_url,
                    {
                        _token: token,
                        key: cart_id,
                        product_id: product_id,
                        quantity: product_qyt,
                    },
                    function (response) {
                        if (response["status"] == 0) {
                            toastr.error(response["message"]);
                        } else {
                            toastr.success(response["message"]);
                        }
                        response["qty"] <= 1
                            ? $(`.quantity__minus${cart_id}`).html(
                                '<i class="tio-delete-outlined text-danger fs-10"></i>'
                            )
                            : $(`.quantity__minus${cart_id}`).html(
                                '<i class="tio-remove fs-10"></i>'
                            );

                        $(`.cartQuantity${cart_id}`).val(response["qty"]);
                        $(`.cartQuantity${cart_id}`).html(response["qty"]);
                        $(`.cart_quantity_multiply${cart_id}`).html(response["qty"]);
                        $(".cart_total_amount").html(response.total_price);
                        $(`.discount_price_of_${cart_id}`).html(
                            response["discount_price"]
                        );
                        $(`.quantity_price_of_${cart_id}`).html(
                            response["quantity_price"]
                        );
                        $(`.total_discount`).html(
                            response["total_discount_price"]
                        );
                        $(`.free_delivery_amount_need`).html(
                            response.free_delivery_status.amount_need
                        );
                        if(response.free_delivery_status.amount_need <=0){
                            $('.amount_fullfill').removeClass('d-none');
                            $('.amount_need_to_fullfill').addClass('d-none');
                        }else{
                            $('.amount_fullfill').addClass('d-none');
                            $('.amount_need_to_fullfill').removeClass('d-none');
                        }
                        const progressBar = document.querySelector('.progress-bar');
                        progressBar.style.width = response.free_delivery_status.percentage + '%';
                        if (response["qty"] == cart_quantity_of.data("min")) {
                            cart_quantity_of
                                .parent()
                                .find(".quantity__minus")
                                .html(
                                    '<i class="tio-delete-outlined text-danger fs-10"></i>'
                                );
                        } else {
                            cart_quantity_of
                                .parent()
                                .find(".quantity__minus")
                                .html('<i class="tio-remove fs-10"></i>');
                        }
                        if (
                            segment === "shop-cart" ||
                            segment === "checkout-payment" ||
                            segment === "checkout-details"
                        ) {
                            location.reload();
                        }
                    }
                );
            }
        }
    }
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('cart.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#chosen_price_mobile').html(data.price);
                    $('#set-tax-amount-mobile').html(data.tax);
                    $('#set-tax-amount').html(data.tax);
                    $('#set-discount-amount').html(data.discount);
                    $('#available-quantity').html(data.quantity);
                    $('.cart-qty-field').attr('max', data.quantity);
                }
            });
        }
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input:radio:checked').length == count) {
            return true;
        }
        return false;
    }

    @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
    $(document).ready(function () {
        $('#popup-modal').appendTo("body").modal('show');
    });
    @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
    @endif

    $(".clickable").click(function () {
        window.location = $(this).find("a").attr("href");
        return false;
    });
</script>

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<script>
    function couponCode() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '{{ route('coupon.apply') }}',
            data: $('#coupon-code-ajax').serializeArray(),
            success: function (data) {
                /* console.log(data);
                return false; */
                if (data.status == 1) {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.success(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                } else {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.error(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                }
                setInterval(function () {
                    location.reload();
                }, 2000);
            }
        });
    }

    jQuery(".search-bar-input").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(".search-bar-input-mobile").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input-mobile").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(document).mouseup(function (e) {
        var container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    function route_alert(route, message) {
        Swal.fire({
            title: '{{ translate("are_you_sure")}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{ translate("no")}}',
            confirmButtonText: '{{ translate("yes")}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }

    function order_again(order_id) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: $("#order_again_url").data("url"),
            data: {
                order_id,
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (response) {
                if (response.status === 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 3000, // duration
                    });
                    location.href = response.redirect_url;
                    return false;
                } else if (response.status === 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000, // duration
                    });
                    return false;
                }
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
</script>
<script>
    $('.filter-show-btn').on('click', function(){
        $('#shop-sidebar').toggleClass('show active');
    })
    $('.cz-sidebar-header .close').on('click', function(){
        $('#shop-sidebar').removeClass('show active');
    })
    $('.remove-address-by-modal').on('click', function (){
        let link = $(this).data('link');
        $('#remove-address-link').attr('href', link);
        $('#remove-address').modal('show');
    });
</script>

<script>
    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookie-section">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between column-gap-4 row-gap-3">
                    <div class="text-wrapper">
                        <h5 class="title">{{ translate("Your_Privacy_Matter")}}</h5>
                        <div>{{ $cookie ? $cookie['cookie_text'] : '' }}</div>
                    </div>
                    <div class="btn-wrapper">
                        <span class="text-white cursor-pointer" id="cookie-reject">{{ translate("no_thanks")}}</span>
                        <button class="btn btn-success cookie-accept" id="cookie-accept">{{ translate('yes_i_Accept')}}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    $(document).on('click','#cookie-accept',function() {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        $('#cookie-section').hide();
    });
    $(document).on('click','#cookie-reject',function() {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        $('#cookie-section').hide();
    });

    $(document).ready(function() {
        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            $('#cookie-section').hide();
        }else{
            $('#cookie-section').html(cookie_content).show();
        }
    });
</script>

@if(!auth('customer')->check())
<script>
    $(document).ready(function() {
        const currentUrl = new URL(window.location.href);
        const referral_code_parameter = new URLSearchParams(currentUrl.search).get("referral_code");
        if (referral_code_parameter) {
            @if (Request::is('customer/auth/sign-up*'))
                if ($('#referral_code').length) {
                    $('#referral_code').val(referral_code_parameter);
                }
            @else
                window.location.href = "{{route('customer.auth.sign-up')}}?referral_code=" + referral_code_parameter;
            @endif
        }
    });
</script>
@endif

<script>
    /*========================
    04: Background Image
    ==========================*/
    var $bgImg = $("[data-bg-img]");
    $bgImg
        .css("background-image", function () {
            return 'url("' + $(this).data("bg-img") + '")';
        })
        .removeAttr("data-bg-img")
        .addClass("bg-img");
</script>

@stack('script')

</body>
</html>
