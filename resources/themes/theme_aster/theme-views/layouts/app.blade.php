<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') }}">
<head>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <title>@yield('title')</title>

    <!-- CSRF Token -->
    <meta name="base-url" content="{{ url('/') }}">

    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="_token" content="{{csrf_token()}}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>


    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.css') }}"/>
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->
    <!-- sweet alert Css -->
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.css') }}">
    <!--Toastr -->
    <link rel="stylesheet" href="{{theme_asset('assets/css/toastr.css')}}"/>

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style.css') }}"/>
    <!-- ======= END MAIN STYLES ======= -->

    @stack('css_or_js')

    <!-- ======= CUSTOMIZE STYLES ======= -->
    <link rel="stylesheet" href="{{ theme_asset('assets/css/custom.css') }}"/>
    <!-- ======= END CUSTOMIZE STYLES ======= -->

    <style>
        :root {
            --bs-primary: {{ $web_config['primary_color'] }};
            --bs-primary-rgb: {{ \App\CPU\hex_to_rgb($web_config['primary_color']) }};
            --primary-dark: {{ $web_config['primary_color'] }};
            --primary-light: {{ $web_config['primary_color_light'] }};
            --bs-secondary: {{ $web_config['secondary_color'] }};
            --bs-secondary-rgb: {{ \App\CPU\hex_to_rgb($web_config['secondary_color']) }};
        }
        .announcement-color{
            background-color: {{ $web_config['announcement']['color'] }};
            color:{{$web_config['announcement']['text_color']}};
        }
    </style>
    @php($google_tag_manager_id = \App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
    @if($google_tag_manager_id )
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{$google_tag_manager_id}}');</script>
        <!-- End Google Tag Manager -->

    @endif

    @php($pixel_analytices_user_code =\App\CPU\Helpers::get_business_settings('pixel_analytics'))
    @if($pixel_analytices_user_code)
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
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
<script>
    function setThemeMode() {
        if (localStorage.getItem('theme') === null) {
            document.body.setAttribute('theme', 'light');
        } else {
            document.body.setAttribute('theme', localStorage.getItem('theme'));
        }
    }
    setThemeMode();
</script>
@if($google_tag_manager_id)
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{$google_tag_manager_id}}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
{{--loader--}}
<div class="preloader d--none" id="loading">
    <img width="200"
         src="{{asset('storage/app/public/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
         onerror="this.src='{{theme_asset('assets/img/loader.gif')}}'">
</div>
{{--loader--}}


<!-- Header and top offer bar -->
@include('theme-views.layouts.partials._header')

<!-- Settings Sidebar -->
@include('theme-views.layouts.partials._settings-sidebar')

<!-- Main Content -->
@yield('content')

<!-- Feature -->
@include('theme-views.layouts.partials._feature')

<!-- Footer-->
@include('theme-views.layouts.partials._footer')

<!-- Back To Top Button -->
<a href="#" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- App Bar -->
<div class="app-bar px-sm-2 d-xl-none" id="mobile_app_bar">
    @include('theme-views.layouts.partials._app-bar')
</div>

<!-- Cookies -->
@php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
@if($cookie && $cookie['status']==1)
    <section id="cookie-section"></section>
@endif


<!-- ======= All Modals ======= -->

<!-- Register Modal -->
@include('theme-views.layouts.partials.modal._register')

<!-- Login Modal -->
@include('theme-views.layouts.partials.modal._login')

<!-- Seller Login Modal -->
@include('theme-views.layouts.partials.modal._seller-login')

<!-- Quick View Modal -->
@include('theme-views.layouts.partials.modal._quick-view')

<!-- Initial Modal -->
@include('theme-views.layouts.partials.modal._initial')

<span id="update_nav_cart_url" data-url="{{route('cart.nav-cart')}}"></span>
<span id="remove_from_cart_url" data-url="{{ route('cart.remove') }}"></span>
<span id="update_quantity_url" data-url="{{route('cart.updateQuantity.guest')}}"></span>
<span id="order_again_url" data-url="{{ route('cart.order-again') }}"></span>
<span id="authentication-status" data-auth="{{ auth('customer')->check() ? 'true' : 'false' }}"></span>
<span id="all-msg-container" data-afterextend="{{translate('see_less')}}" data-seemore="{{translate('see_more')}}"></span>
<span class="please_fill_out_this_field" data-text="{{ translate('please_fill_out_this_field') }}"></span>

@php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
<div class="social-chat-icons">
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{theme_asset('assets/img/whatsapp.svg')}}" width="35" class="chat-image-shadow"
                     alt="Chat with us on WhatsApp">
            </a>
        </div>
    @endif
</div>

<span class="cannot_use_zero" data-text="{{ translate('cannot_Use_0_only') }}"></span>

<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
<script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/easyzoom/easyzoom.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/toastr.js') }}"></script>
<script src="{{ theme_asset('assets/js/main.js') }}"></script>
<script src="{{ theme_asset('assets/js/custom.js') }}"></script>

{!! Toastr::message() !!}

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
    @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
    $(document).ready(function () {
        $('#initialModal').modal('show');
    });
    @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
    @endif
</script>

<script>
    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookies active absolute-white py-4">
            <div class="container">
                <h4 class="absolute-white mb-3">{{translate('Your_Privacy_Matter')}}</h4>
                <p>{{ $cookie ? $cookie['cookie_text'] : '' }}</p>
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="button" class="btn absolute-white btn-link" id="cookie-reject">{{translate('no_thanks')}}</button>
                    <button type="button" class="btn btn-primary" id="cookie-accept">{{translate('yes_i_Accept')}}</button>
                </div>
            </div>
        </div>
        `;
    $(document).on('click', '#cookie-accept', function () {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        $('#cookie-section').hide();
    });
    $(document).on('click', '#cookie-reject', function () {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        $('#cookie-section').hide();
    });

    $(document).ready(function () {
        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            $('#cookie-section').hide();
        } else {
            $('#cookie-section').html(cookie_content).show();
        }
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<script>
    function route_alert(route, message) {
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{translate('no')}}',
            confirmButtonText: '{{translate('yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }
</script>

@if(!auth('customer')->check())
<script>
    $(document).ready(function() {
        const currentUrl = new URL(window.location.href);
        const referral_code_parameter = new URLSearchParams(currentUrl.search).get("referral_code");

        if (referral_code_parameter) {
            $('#registerModal').modal('show');

            if ($('#referral_code').length) {
            $('#referral_code').val(referral_code_parameter);
            }
        }
    });
</script>
@endif

<script>
    // Input Field Validation Custom Message
    var errorMessages = {
        valueMissing: $('.please_fill_out_this_field').data('text')
    };

    $('input').each(function () {
        var $el = $(this);

        $el.on('invalid', function (event) {
            var target = event.target,
                validity = target.validity;
            target.setCustomValidity("");
            if (!validity.valid) {
                if (validity.valueMissing) {
                    target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                }
            }
        });
    });

    $('textarea').each(function () {
            var $el = $(this);

            $el.on('invalid', function (event) {
                var target = event.target,
                    validity = target.validity;
                target.setCustomValidity("");
                if (!validity.valid) {
                    if (validity.valueMissing) {
                        target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                    }
                }
            });
        });
</script>

@stack('script')

</body>
</html>
