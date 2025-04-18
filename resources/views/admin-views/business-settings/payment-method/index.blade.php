@extends('layouts.back-end.app')

@section('title', translate('payment_Method'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.third-party-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{route('admin.business-settings.payment-method.update')}}"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        method="post">
                    @csrf
                    <h5 class="mb-4 text-uppercase d-flex text-capitalize">{{translate('payment_methods')}}</h5>

                    <div class="row">
                        @php($cash_on_delivery=\App\CPU\Helpers::get_business_settings('cash_on_delivery'))
                        @isset($cash_on_delivery)
                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('cash_on_delivery')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled,_the_cash_on_delivery_option_will_be_available_on_the_system._Customers_can_use_COD_as_a_payment_option')}}.">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="cash_on_delivery">
                                        <input type="checkbox" class="switcher_input" name="cash_on_delivery"
                                        onclick="toogleStatusModal(event,'cash_on_delivery','cod-on.png','cod-on.png',
                                        '{{translate('want_to_Turn_ON_the_Cash_On_Delivery_option')}}?','{{translate('want_to_Turn_OFF_the_Cash_On_Delivery_option')}}?',
                                        `<p>{{translate('if_enabled_customers_can_select_Cash_on_Delivery_as_a_payment_method_during_checkout')}}</p>`,
                                        `<p>{{translate('if_disabled_the Cash_on_Delivery_payment_method_will_be_hidden_from_the_checkout_page')}}</p>`)"
                                         id="cash_on_delivery" value="1" {{$cash_on_delivery['status']==1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endisset

                        @php($digital_payment=\App\CPU\Helpers::get_business_settings('digital_payment'))
                        @isset($digital_payment)

                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('digital_payment')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled,_customers_can_choose_digital_payment_options_during_the_checkout_process')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="digital_payment">
                                        <input type="checkbox" class="switcher_input" name="digital_payment"
                                        onclick="toogleStatusModal(event,'digital_payment','digital-paymet-on.png','digital-payment-off.png',
                                        '{{translate('want_to_Turn_ON_the_Digital_Payment_option')}}?','{{translate('want_to_Turn_OFF_the_Digital_Payment_option')}}?',
                                        `<p>{{translate('if_enabled_customers_can_select_Digital_Payment_during_checkout')}}</p>`,
                                        `<p>{{translate('if_disabled_Digital_Payment_options_will_be_hidden_from_the_checkout_page')}}</p>`)"
                                        id="digital_payment" value="1" {{$digital_payment['status']==1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endisset

                        @php($offline_payment=\App\CPU\Helpers::get_business_settings('offline_payment'))
                        @isset($offline_payment)

                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('offline_payment')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('offline_Payment_allows_customers_to_use_external_payment_methods._They_must_share_payment_details_with_the_seller_afterward._Admin_can_set_whether_customers_can_make_offline_payments_by_enabling/disabling_this_button.
                                        ')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="offline_payment">
                                        <input type="checkbox" class="switcher_input" name="offline_payment"
                                        onclick="toogleStatusModal(event,'offline_payment','digital-paymet-on.png','digital-payment-off.png',
                                        '{{translate('want_to_Turn_ON_the_Offline_Payment_option')}}?','{{translate('want_to_Turn_OFF_the_Offline_Payment_option')}}?',
                                        `<p>{{translate('if_enabled_customers_can_pay_through_external_payment_methods')}}</p>`,
                                        `<p>{{translate('if_disabled_customers_have_to_use_the_system-added_payment_gateways')}}</p>`)"
                                         id="offline_payment" value="1" {{$offline_payment['status']==1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endisset

                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn--primary px-5 text-uppercase">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($payment_gateway_published_status)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-around">
                        <h4 class="text-danger bg-transparent">
                            <i class="tio-info-outined"></i>
                            {{ translate('Your current payment settings are disabled, because you have enabled
                            payment gateway addon, To visit your currently active payment gateway settings please follow
                            the link.') }}
                        </h4>
                        <span>
                            <a href="{{!empty($payment_url) ? $payment_url : ''}}" class="btn btn-outline-primary"><i class="tio-settings mr-1"></i>{{translate('settings')}}</a>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- payment getway -->
        <div class="row gy-3" id="payment-gatway-cards">
            @foreach($payment_gateways as $key=>$payment)
                <div class="col-md-6">
                    <div class="card">
                        <form action="{{route('admin.business-settings.payment-method.addon-payment-set')}}" method="POST"
                              id="{{$payment->key_name}}-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header d-flex flex-wrap align-content-around">
                                <h5>
                                    <span class="text-uppercase">{{str_replace('_',' ',$payment->key_name)}}</span>
                                </h5>

                                @php($additional_data = $payment['additional_data'] != null ? json_decode($payment['additional_data']) : [])

                                <?php
                                    if ($additional_data != null){
                                        $img_path = asset('storage/app/public/payment_modules/gateway_image/'. $additional_data->gateway_image ?? '');
                                    }else{
                                        $img_path = asset('/public/assets/back-end/img/modal/payment-methods/'.$payment->key_name.'.png');
                                    }
                                ?>

                                <label class="switcher show-status-text">
                                    <input class="switcher_input" type="checkbox" name="status" value="1"
                                    onclick="paymentMethodStatusModal(event,'{{$payment->key_name}}','{{ $img_path }}',
                                    '{{translate('want_to_Turn_ON_')}}{{str_replace('_',' ',$payment->key_name)}}{{translate('_as_the_Digital_Payment_method')}}?','{{translate('want_to_Turn_OFF_')}}{{str_replace('_',' ',$payment->key_name)}}{{translate('_as_the_Digital_Payment_method')}}??',
                                    `<p>{{translate('if_enabled_customers_can_use_this_payment_method')}}</p>`,
                                    `<p>{{translate('if_disabled_this_payment_method_will_be_hidden_from_the_checkout_page')}}</p>`)"
                                        id="{{$payment->key_name}}" {{$payment['is_active']==1?'checked':''}}>

                                    <span class="switcher_control" data-ontitle="{{ translate('on') }}" data-offtitle="{{ translate('off') }}"></span>
                                </label>
                            </div>


                            <div class="card-body">
                                <div class="payment--gateway-img">
                                    <img style="height: 80px" id="gateway_img{{$payment->key_name}}"
                                         src="{{asset('storage/app/public/payment_modules/gateway_image')}}/{{$additional_data != null ? $additional_data->gateway_image : ''}}"
                                         onerror="this.src='{{asset('public/assets/back-end/img/payment-gateway-placeholder.png')}}'"
                                         alt="public">
                                </div>

                                <input name="gateway" value="{{$payment->key_name}}" class="d-none">

                                @php($mode=$payment->live_values['mode'])
                                <div class="form-group" style="margin-bottom: 10px">
                                    <select class="js-example-responsive form-control" name="mode">
                                        <option value="live" {{$mode=='live'?'selected':''}}>{{translate('live')}}</option>
                                        <option value="test" {{$mode=='test'?'selected':''}}>{{translate('test')}}</option>
                                    </select>
                                </div>

                                @php($skip=['gateway','mode','status'])

                                @foreach($payment->live_values as $key=>$value)
                                    @if(!in_array($key,$skip))
                                        @if($payment->key_name === 'paystack' && $key == 'callback_url')
                                            <div class="form-group" style="margin-bottom: 10px">
                                                <label for="exampleFormControlInput1"
                                                       class="form-label">{{ucwords(str_replace('_',' ',$key))}}</label>

                                                <div class="d-flex">
                                                    <span class="form-control" id="id_paystack">{{ url('/') }}/payment/paystack/callback</span>
                                                    <span class="btn btn--primary text-nowrap"
                                                          onclick="copyToClipboard('#id_paystack')"><i
                                                            class="tio-copy"></i> {{\App\CPU\translate('Copy_URI')}}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group" style="margin-bottom: 10px">
                                                <label for="exampleFormControlInput1"
                                                       class="form-label">{{ucwords(str_replace('_',' ',$key))}}
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                       name="{{$key}}"
                                                       placeholder="{{ucwords(str_replace('_',' ',$key))}} *"
                                                       value="{{env('APP_ENV')=='demo'?'':$value}}">
                                            </div>
                                        @endif
                                    @endif
                                @endforeach

                                <div class="form-group" style="margin-bottom: 10px">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{translate('payment_gateway_title')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control"
                                           name="gateway_title"
                                           placeholder="{{translate('payment_gateway_title')}}"
                                           value="{{$additional_data != null ? $additional_data->gateway_title : ''}}" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 10px">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{translate('Choose_Logo')}} </label>
                                    <input type="file" class="form-control" name="gateway_image" accept=".jpg, .png, .jpeg|image/*"
                                    onchange="document.getElementById('gateway_img{{$payment->key_name}}').src = window.URL.createObjectURL(this.files[0])">
                                </div>

                                <div class="text-right" style="margin-top: 20px">
                                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@push('script')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success("{{translate('Copied_to_the_clipboard')}}");
        }
    </script>

    <script>
        function paymentMethodStatusModal(e, toggle_id, image, on_title, off_title, on_message, off_message) {
            e.preventDefault();

            $('#toggle-status-image').attr('src', image);
            if ($('#'+toggle_id).is(':checked')) {
                $('#toggle-status-title').empty().append(on_title);
                $('#toggle-status-message').empty().append(on_message);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
                $('.toggle-modal-img-box .status-icon').attr('src', '{{ asset("/public/assets/back-end/img/modal/status-green.png") }}');
            } else {
                $('#toggle-status-title').empty().append(off_title);
                $('#toggle-status-message').empty().append(off_message);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
                $('.toggle-modal-img-box .status-icon').attr('src', '{{ asset("/public/assets/back-end/img/modal/status-warning.png") }}');
            }
            $('#toggle-status-modal').modal('show');
        }
    </script>

    <script>
        @if($payment_gateway_published_status)
            $('#payment-gatway-cards').find('input').each(function(){
                $(this).attr('disabled', true);
            });
            $('#payment-gatway-cards').find('select').each(function(){
                $(this).attr('disabled', true);
            });
            $('#payment-gatway-cards').find('.switcher_input').each(function(){
                $(this).removeAttr('checked', true);
            });
            $('#payment-gatway-cards').find('button').each(function(){
                $(this).attr('disabled', true);
            });
        @endif
    </script>
@endpush
