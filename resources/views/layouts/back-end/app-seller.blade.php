<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ Session::get('direction') }}"
    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->

    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <!--to make http ajax request to https-->
    <!--    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('storage/app/public/company/'.$web_config['fav_icon']->value)}}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/custom.css">


    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/style.css">
    @if (Session::get('direction') === 'rtl')
        <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/menurtl.css">
    @endif
    {{-- light box --}}
    <link rel="stylesheet" href="{{ asset('public/css/lightbox.css') }}">
    @stack('css_or_js')
    <!-- <style>
        :root {
            --theameColor: #045cff;
        }

        .rtl {
            direction: {{ Session::get('direction') }};
        }
    </style> -->
    <style>
        select {
            background-image: url('{{asset('/public/assets/back-end/img/arrow-down.png')}}');
            background-size: 7px;
            background-position: 96% center;
        }
    </style>
    <script
        src="{{ asset('public/assets/back-end') }}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js">
    </script>
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/toastr.css">
</head>

<body class="footer-offset">
    <!-- Builder -->
    @include('layouts.back-end.partials._front-settings')
    <!-- End Builder -->
    {{-- loader --}}
    <div class="row">
        <div class="col-12 position-fixed z-9999 mt-10rem">
            <div id="loading" style="display: none">
                <div id="loader"></div>
            </div>
        </div>
    </div>
    {{-- loader --}}
    <!-- JS Preview mode only -->
    @include('layouts.back-end.partials-seller._header')
    @include('layouts.back-end.partials-seller._side-bar')

    <!-- END ONLY DEV -->

    <main id="content" role="main" class="main pointer-event">
        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @include('layouts.back-end.partials-seller._footer')
        <!-- End Footer -->

        @include('layouts.back-end.partials-seller._modals')

        {{-- Toggle Modal --}}
        @include('layouts.back-end.partials-seller._toggle-modal')

    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    <span class="please_fill_out_this_field" data-text="{{ translate('please_fill_out_this_field') }}"></span>

    <!-- ========== END SECONDARY CONTENTS ========== -->
    <script src="{{ asset('public/assets/back-end') }}/js/custom.js"></script>
    <!-- JS Implementing Plugins -->

    <!-- JS Front -->
    <script src="{{ asset('public/assets/back-end') }}/js/vendor.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/theme.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/sweet_alert.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-left",
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

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function() {
            // ONLY DEV
            // =======================================================
            if (window.localStorage.getItem('hs-builder-popover') === null) {
                $('#builderPopover').popover('show')
                    .on('shown.bs.popover', function() {
                        $('.popover').last().addClass('popover-dark')
                    });

                $(document).on('click', '#closeBuilderPopover', function() {
                    window.localStorage.setItem('hs-builder-popover', true);
                    $('#builderPopover').popover('dispose');
                });
            } else {
                $('#builderPopover').on('show.bs.popover', function() {
                    return false
                });
            }
            // END ONLY DEV
            // =======================================================

            // BUILDER TOGGLE INVOKER
            // =======================================================
            $('.js-navbar-vertical-aside-toggle-invoker').click(function() {
                $('.js-navbar-vertical-aside-toggle-invoker i').tooltip('hide');
            });

            // INITIALIZATION OF MEGA MENU
            // =======================================================
            /*var megaMenu = new HSMegaMenu($('.js-mega-menu'), {
                desktop: {
                    position: 'left'
                }
            }).init();*/


            // INITIALIZATION OF NAVBAR VERTICAL NAVIGATION
            // =======================================================
            var sidebar = $('.js-navbar-vertical-aside').hsSideNav();


            // INITIALIZATION OF TOOLTIP IN NAVBAR VERTICAL MENU
            // =======================================================
            $('.js-nav-tooltip-link').tooltip({
                boundary: 'window'
            })

            $(".js-nav-tooltip-link").on("show.bs.tooltip", function(e) {
                if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
                    return false;
                }
            });


            // INITIALIZATION OF UNFOLD
            // =======================================================
            $('.js-hs-unfold-invoker').each(function() {
                var unfold = new HSUnfold($(this)).init();
            });


            // INITIALIZATION OF FORM SEARCH
            // =======================================================
            $('.js-form-search').each(function() {
                new HSFormSearch($(this)).init()
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format(
                    'MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function() {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });
        });
    </script>


    <script src="{{ asset('public/assets/back-end') }}/js/bootstrap.min.js"></script>
    {{-- light box --}}
    <script src="{{ asset('public/js/lightbox.min.js') }}"></script>
    <audio id="myAudio">
        <source src="{{ asset('public/assets/back-end/sound/notification.mp3') }}" type="audio/mpeg">
    </audio>
    <script>
        var audio = document.getElementById("myAudio");

        function playAudio() {
            audio.play();
        }

        function pauseAudio() {
            audio.pause();
        }

        $("#reset").on('click', function (){
            let placeholderImg = $("#placeholderImg").data('img');
            console.log(placeholderImg)
            $('#viewer').attr('src', placeholderImg);
            $('.spartan_remove_row').click();
        });
    </script>
    <script>
        setInterval(function() {
            $.get({
                url: '{{ route('seller.get-order-data') }}',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;
                    if (data.new_order > 0) {
                        playAudio();
                        $('#popup-modal').appendTo("body").modal('show');
                    }
                },
            });
        }, 10000);

        function check_order() {
            location.href = '{{ route('seller.orders.list', ['status' => 'all']) }}';
        }
    </script>

    <script>
        $("#search-bar-input").keyup(function() {
            $("#search-card").css("display", "block");
            let key = $("#search-bar-input").val();
            if (key.length > 0) {
                $.get({
                    url: '{{ url('/') }}/admin/search-function/',
                    dataType: 'json',
                    data: {
                        key: key
                    },
                    beforeSend: function() {
                        $('#loading').fadeIn();
                    },
                    success: function(data) {
                        $('#search-result-box').empty().html(data.result)
                    },
                    complete: function() {
                        $('#loading').fadeOut();
                    },
                });
            } else {
                $('#search-result-box').empty();
            }
        });

        $(document).mouseup(function(e) {
            var container = $("#search-card");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });

        function form_alert(id, message) {
            Swal.fire({
                title: '{{ translate("are_you_sure") }}?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
    </script>

    <script>
        function notification_data_view(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: "{{route('seller.messages.ajax-seller-notification-view')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                beforeSend: function () {
                    // $('#loading').show();
                },
                success: function (data) {
                    $('.notification_data_new_badge'+id).fadeOut();
                    $('#NotificationModalContent').empty().html(data.view);
                    $('#NotificationModal').modal('show');
                    data.notification_count == 0 ? $('.notification_data_new_count').fadeOut() : $('.notification_data_new_count').html(data.notification_count);
                },
                complete: function () {
                    // $('#loading').hide();
                },
            });
        }
    </script>

    <script>
        function call_demo() {
            toastr.info('{{ translate("update_option_is_disabled_for_demo") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
    <script>
        function openInfoWeb() {
            var x = document.getElementById("website_info");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('public/assets/back-end') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>

    @stack('script')

    @stack('script_2')



    {{-- ck editor --}}
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    {{-- <script>
        CKEDITOR.replace('editor');
    </script> --}}
    {{-- ck editor --}}

    {{-- <script>
        initSample();
    </script> --}}
    <script>
        function getRndInteger() {
            return Math.floor(Math.random() * 90000) + 100000;
        }
    </script>

    <script>
        function toogleModal(e, toggle_id, on_image, off_image, on_title, off_title, on_message, off_message) {
            e.preventDefault();
            if ($('#'+toggle_id).is(':checked')) {
                $('#toggle-title').empty().append(on_title);
                $('#toggle-message').empty().append(on_message);
                $('#toggle-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+on_image);
                $('#toggle-ok-button').attr('toggle-ok-button', toggle_id);
            } else {
                $('#toggle-title').empty().append(off_title);
                $('#toggle-message').empty().append(off_message);
                $('#toggle-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+off_image);
                $('#toggle-ok-button').attr('toggle-ok-button', toggle_id);
            }
            $('#toggle-modal').modal('show');
        }

        function confirmToggle() {
            var toggle_id = $('#toggle-ok-button').attr('toggle-ok-button');
            if ($('#'+toggle_id).is(':checked')) {
                $('#'+toggle_id).prop('checked', false);
            } else {
                $('#'+toggle_id).prop('checked', true);
            }
            $('#toggle-modal').modal('hide');

            if(toggle_id == 'email_verification'){
                if ($("#email_verification").is(':checked')) {
                    $('#otp_verification').removeAttr('checked');
                }
            }

            if(toggle_id == 'otp_verification'){
                if ($("#otp_verification").is(':checked')) {
                    $('#email_verification').removeAttr('checked');
                }
            }
        }

        function toogleStatusModal(e, toggle_id, on_image, off_image, on_title, off_title, on_message, off_message) {
            e.preventDefault();
            $('.toggle-modal-img-box .status-icon').attr('src', '');
            if ($('#'+toggle_id).is(':checked')) {
                $('#toggle-status-title').empty().append(on_title);
                $('#toggle-status-message').empty().append(on_message);
                $('#toggle-status-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+on_image);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
            } else {
                $('#toggle-status-title').empty().append(off_title);
                $('#toggle-status-message').empty().append(off_message);
                $('#toggle-status-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+off_image);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
            }
            $('#toggle-status-modal').modal('show');
        }

        function confirmStatusToggle() {
            var toggle_id = $('#toggle-status-ok-button').attr('toggle-ok-button');
            if ($('#'+toggle_id).is(':checked')) {
                $('#'+toggle_id).prop('checked', false);
                $('#'+toggle_id).val(0);
            } else {
                $('#'+toggle_id).prop('checked', true);
                $('#'+toggle_id).val(1);
            }
            $('#'+toggle_id+'_form').submit();
        }

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
    </script>
</body>

</html>
