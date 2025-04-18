@extends('layouts.back-end.app')

@section('title', translate('Push_Notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/push-notification.png')}}" alt="">
                {{translate('push_Notification_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
            <!-- Inlile Menu -->
            @include('admin-views.notification.notification-inline-menu')
            <!-- End Inlile Menu -->

            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder text-capitalize">
                {{translate('read_documentation')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#docsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">

            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body py-5">

                        <div class="d-flex justify-content-between gap-3 flex-wrap mb-5">
                            <div class="table-responsive w-auto ovy-hidden">
                                @php($language = $language->value ?? null)
                                @php($default_lang = 'en')

                                @php($default_lang = json_decode($language)[0])
                                <ul class="nav nav-tabs w-fit-content flex-nowrap  border-0">
                                    @foreach (json_decode($language) as $lang)
                                        <li class="nav-item text-capitalize">
                                            <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}" href="javascript:" id="{{ $lang }}-link">{{ \App\CPU\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div>
                                <select name="for_customer" id="for_customer" class="form-control min-w-200 text-capitalize" onchange="select_user_type(this.value)">
                                    <option value="customer">{{translate('for_customer')}}</option>
                                    <option value="seller">{{translate('for_seller')}}</option>
                                    <option value="delivery_man">{{translate('for_delivery_man')}}</option>
                                </select>
                            </div>
                        </div>
                        <!--for customer -->
                        <div class="customer_view">
                            <form action="{{route('admin.notification.update-push-notification',['type'=>'customer'])}}" class="text-start" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @foreach ($user_type_customer as $key=>$value )
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-10">
                                                    <label for="customer{{$value['key']}}" class="switcher_content">
                                                        {{ translate($value['key'] == 'out_for_delivery_message' ? 'order_out_for_delivery_message' : ($value['key'] == 'order_canceled' ? 'order_canceled_message' : $value['key'])) }}
                                                    </label>
                                                    <label class="switcher" for="customer{{$value['key']}}">
                                                        <input type="checkbox" class="switcher_input" name="status{{$value['id']}}" id="customer{{$value['key']}}" value="1" {{$value['status']==1?'checked':''}}
                                                            onclick="toogleModal(event,'customer{{$value['key']}}','notification-on.png','notification-off.png',
                                                                                '{{translate('Want_to_Turn_ON_Push_Notification')}}',
                                                                                '{{translate('Want_to_Turn_OFF_Push_Notification')}}',
                                                                                `<p>{{translate('if_enabled_customers_will_receive_notifications_on_their_devices')}}</p>`,
                                                                                `<p>{{translate('if_disabled_customers_will_not_receive_notifications_on_their_devices')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>
                                                @foreach (json_decode($language) as $lang)
                                                    <?php
                                                        if (count($value['translations'])) {
                                                            $translate = [];
                                                            foreach ($value['translations'] as $t) {
                                                                if ($t->locale == $lang && $t->key == $value['key']) {
                                                                    $translate[$lang][$value['key']] = $t->value;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <input type="hidden" name="lang{{$value['id']}}[]" value="{{ $lang }}">
                                                    <textarea name="message{{$value['id']}}[]" class="form-control {{ $lang != $default_lang ? 'd-none' : '' }} lang_form {{ $lang }}-form">{{$translate[$lang][$value['key']]??$value['message']}}</textarea>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="reset"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn-secondary px-4">{{translate('reset')}}
                                    </button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-4">{{translate('submit')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end -->
                        <!--for seller -->
                        <div class="seller_view d-none">
                            <form action="{{route('admin.notification.update-push-notification',['type'=>'seller'])}}" class="text-start" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @foreach ($user_type_seller as $key=>$value )
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-10">
                                                    <label for="seller{{$value['key']}}" class="switcher_content">{{translate($value['key'])}}</label>
                                                    <label class="switcher" for="seller{{$value['key']}}">
                                                        <input type="checkbox" class="switcher_input" name="status{{$value['id']}}" id="seller{{$value['key']}}" value="1" {{$value['status']==1?'checked':''}}
                                                            onclick="toogleModal(event,'seller{{$value['key']}}','notification-on.png','notification-off.png',
                                                                                '{{translate('Want_to_Turn_ON_Push_Notification')}}',
                                                                                '{{translate('Want_to_Turn_OFF_Push_Notification')}}',
                                                                                `<p>{{translate('if_enabled_customers_will_receive_notifications_on_their_devices')}}</p>`,
                                                                                `<p>{{translate('if_disabled_customers_will_not_receive_notifications_on_their_devices')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>
                                                @foreach (json_decode($language) as $lang)
                                                    <?php
                                                        if (count($value['translations'])) {
                                                            $translate = [];
                                                            foreach ($value['translations'] as $t) {
                                                                if ($t->locale == $lang && $t->key == $value['key']) {
                                                                    $translate[$lang][$value['key']] = $t->value;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <input type="hidden" name="lang{{$value['id']}}[]" value="{{ $lang }}">
                                                    <textarea name="message{{$value['id']}}[]" class="form-control {{ $lang != $default_lang ? 'd-none' : '' }} lang_form {{ $lang }}-form">{{$translate[$lang][$value['key']]??$value['message']}}</textarea>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="reset"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn-secondary px-4">{{translate('reset')}}
                                    </button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-4">{{translate('submit')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end -->
                        <!--for delivery man -->
                        <div class="delivery_man_view d-none">
                            <form action="{{route('admin.notification.update-push-notification',['type'=>'delivery_man'])}}" class="text-start" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @foreach ($user_type_delivery_man as $key=>$value )
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-10">
                                                    <label for="delivery_man{{$value['key']}}" class="switcher_content">{{translate($value['key'])}}</label>
                                                    <label class="switcher" for="delivery_man{{$value['key']}}">
                                                        <input type="checkbox" class="switcher_input" name="status{{$value['id']}}" id="delivery_man{{$value['key']}}" value="1" {{$value['status']==1?'checked':''}}
                                                            onclick="toogleModal(event,'delivery_man{{$value['key']}}','notification-on.png','notification-off.png',
                                                                                '{{translate('Want_to_Turn_ON_Push_Notification')}}',
                                                                                '{{translate('Want_to_Turn_OFF_Push_Notification')}}',
                                                                                `<p>{{translate('if_enabled_customers_will_receive_notifications_on_their_devices')}}</p>`,
                                                                                `<p>{{translate('if_disabled_customers_will_not_receive_notifications_on_their_devices')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>
                                                @foreach (json_decode($language) as $lang)
                                                    <?php
                                                        if (count($value['translations'])) {
                                                            $translate = [];
                                                            foreach ($value['translations'] as $t) {
                                                                if ($t->locale == $lang && $t->key == $value['key']) {
                                                                    $translate[$lang][$value['key']] = $t->value;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <input type="hidden" name="lang{{$value['id']}}[]" value="{{ $lang }}">
                                                    <textarea name="message{{$value['id']}}[]" class="form-control {{ $lang != $default_lang ? 'd-none' : '' }} lang_form {{ $lang }}-form">{{$translate[$lang][$value['key']]??$value['message']}}</textarea>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="reset"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn-secondary px-4">{{translate('reset')}}
                                    </button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-4">{{translate('submit')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Customer Info Modal -->
    {{-- <div class="modal fade" id="orderPendingConfirmModal" tabindex="-1" aria-labelledby="orderPendingConfirmModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0 text-center">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <img width="80" class="mb-3" src="{{asset('/public/assets/back-end/img/notice.png')}}" loading="lazy" alt="">
                        <h4 class="lh-md">Warning!</h4>
                        <p>Disabling mail configuration services will prevent the system from sending emails. Please only turn off this service if you intend to temporarily suspend email sending. Note that this may affect system functionality that relies on email communication."</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <button type="button" class="btn btn--primary min-w-120 px-5" data-dismiss="modal">{{translate('ok')}}</button>
                        <button type="button" class="btn btn-danger-light min-w-120 px-5" data-dismiss="modal">{{translate('cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Modal -->
    <!-- Documentation Modal -->
    <div class="modal fade" id="docsModal" tabindex="-1" aria-labelledby="docsModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="d-flex flex-column gap-2">
                        <div class="text-center mb-1">
                            <img width="80" class="mb-4" src="{{asset('/public/assets/back-end/img/notice.png')}}" loading="lazy" alt="">
                            <h4 class="lh-md text-capitalize">{{translate('important_notice')}}!</h4>
                        </div>
                        <p class="mb-5">{{translate('to_include_specific_details_in_your_push_notification_message,you_can_use_the_following_placeholders')}}:</p>

                        <ul class="d-flex flex-column px-4 gap-2 mb-4">
                            <li><strong><?= '{deliveryManName}'?>:</strong> {{translate('the_name_of_the_delivery_person')}}.</li>
                            <li><strong><?= '{orderId}'?>:</strong> {{translate('the_unique_ID_of_the_order')}}.</li>
                            <li><strong><?= '{time}'?>:</strong> {{translate('the_expected_delivery_time')}}.</li>
                            <li><strong><?= '{userName}'?>:</strong> {{translate('the_name_of_the_user_who_placed_the_order')}}.</li>
                        </ul>

                        <div class="mx-auto w-100 max-w-300">
                            <button class="btn btn--primary btn-block" data-dismiss="modal">{{translate('got_it')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script>
            $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("." + lang + "-form").removeClass('d-none');
        })

        function select_user_type(type){
            if (type == 'customer') {
                $('.seller_view').addClass('d-none');
                $('.delivery_man_view').addClass('d-none');
                $('.customer_view').removeClass('d-none');
            } else if (type == 'seller') {
                $('.seller_view').removeClass('d-none');
                $('.delivery_man_view').addClass('d-none');
                $('.customer_view').addClass('d-none');
            } else if (type == 'delivery_man') {
                $('.seller_view').addClass('d-none');
                $('.delivery_man_view').removeClass('d-none');
                $('.customer_view').addClass('d-none');
            }

        }
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
