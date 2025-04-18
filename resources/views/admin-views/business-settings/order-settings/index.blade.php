@extends('layouts.back-end.app')
@section('title', translate('order_settings'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_setup')}}
            </h2>

            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>


                <div class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{asset('/public/assets/back-end/img/note.png')}}" alt="">
                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_the_Save_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card">
            <div class="border-bottom px-4 py-3">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/header-logo.png')}}" alt="">
                    {{translate('order_Settings')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.business-settings.order-settings.update-order-settings')}}" method="post" enctype="multipart/form-data" id="add_fund">
                    @csrf
                    <div class="row align-items-end">
                        @php($order_Verification=\App\CPU\Helpers::get_business_settings('order_Verification'))
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group d-flex justify-content-between align-items-center gap-10 form-control">
                                <span class="title-color">
                                    {{translate('order_Delivery_Verification')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('customers_receive_a_verification_code_after_placing_an_order')}}. {{translate('when_a_deliveryman_arrives_for_delivery_they_must_provide_the_code_to_the_deliveryman_to_verify_the_order_delivery')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="order_verification">
                                    <input type="checkbox" value="1" class="switcher_input" name="order_verification" id="order_verification" {{ $order_Verification == 1 ? 'checked':'' }}
                                    onclick="toogleModal(event,'order_verification','order-verifications-on.png','order-verifications-off.png','{{translate('want_to_Turn_ON_Order_Delivery_Verification')}}','{{translate('want_to_Turn_OFF_Order_Delivery_Verification')}}',`<p>{{translate('if_enabled_deliverymen_must_verify_the_order_deliveries_by_collecting_the_OTP_from_customers')}}</p>`,`<p>{{translate('if_disabled_deliverymen_do_not_need_to_verify_the_order_deliveries')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        @php($minimum_order_amount_status=\App\CPU\Helpers::get_business_settings('minimum_order_amount_status'))
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color">
                                    {{translate('minimum_order_amount')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled_customers_must_place_at_least_or_more_than_the_order_amount_that_admin_or_sellers_set')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="minimum_order_amount_status">
                                    <input type="checkbox" value="1" class="switcher_input" name="minimum_order_amount_status" id="minimum_order_amount_status" {{ $minimum_order_amount_status == 1 ? 'checked':'' }}
                                    onclick="toogleModal(event,'minimum_order_amount_status','minimum-order-amount-on.png','minimum-order-amount-off.png','{{translate('want_to_Turn_ON_Minimum_Order_Amount')}}','{{translate('want_to_Turn_OFF_Minimum_Order_Amount')}}',`<p>{{translate('if_enabled_customers_must_order_over_the_minimum_amount_of_orders_that_admin_or_sellers_set')}}</p>`,`<p>{{translate('if_disabled_there_will_be_no_minimum_order_restrictions_and_customers_can_place_any_order_amount')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            @php($billing_input_by_customer=\App\CPU\Helpers::get_business_settings('billing_input_by_customer'))

                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1">
                                    {{translate('show_Billing_Address_In_Checkout')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled_the_billing_address_will_be_shown_on_the_checkout_page')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="billing_input_by_customer">
                                    <input type="checkbox" value="1" class="switcher_input" name="billing_input_by_customer" id="billing_input_by_customer" {{$billing_input_by_customer == 1?'checked':''}}
                                    onclick="toogleModal(event,'billing_input_by_customer','billing-address-on.png','billing-address-off.png','{{translate('want_to_Turn_ON_Billing_Address_in_Checkout')}}','{{translate('want_to_Turn_OFF_Billing_Address_in_Checkout')}}',`<p>{{translate('if_enabled_the_billing_address_will_be_shown_on_the_checkout_page')}}</p>`,`<p>{{translate('if_disabled_the_billing_address_will_be_hidden_from_the_checkout_page')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                        </div>

                        @php($free_delivery=\App\CPU\Helpers::get_business_settings('free_delivery_status'))
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1">
                                    {{translate('free_Delivery')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled_free_delivery_will_be_available_when_customers_order_over_a_certain_amount')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="free_delivery_status">
                                    <input type="checkbox" value="1" class="switcher_input" name="free_delivery_status" id="free_delivery_status" {{$free_delivery == 1?'checked':''}}
                                    onclick="toogleModal(event,'free_delivery_status','free-delivery-on.png','free-delivery-off.png','{{translate('want_to_Turn_ON_Free_Delivery')}}','{{translate('want_to_Turn_OFF_Free_Delivery')}}',`<p>{{translate('if_enabled_the_free_delivery_feature_will_be_shown_from_the_system')}}</p>`,`<p>{{translate('if_disabled_the_free_delivery_feature_will_be_hidden_from_the_system')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                        @php($free_delivery_responsibility=\App\CPU\Helpers::get_business_settings('free_delivery_responsibility'))
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label class="title-color d-flex" for="free_delivery_responsibility">{{translate('free_Delivery_Responsibility')}} </label>
                                <select name="free_delivery_responsibility" id="free_delivery_responsibility" class="form-control  js-select2-custom">
                                    <option value="admin" {{ $free_delivery_responsibility == 'admin' ? 'selected':'' }}>{{ translate('admin') }}</option>
                                    <option value="seller" {{ $free_delivery_responsibility == 'seller' ? 'selected':'' }}>{{ translate('seller') }}</option>
                                </select>
                            </div>
                        </div>

                        @php($free_delivery_over_amount_seller=\App\CPU\Helpers::get_business_settings('free_delivery_over_amount_seller'))
                        <div class="col-xl-4 col-md-6" style="{{ $free_delivery_responsibility == 'seller'? 'display:none':''}}" id="free_delivery_over_amount_admin_area">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center gap-2" for="free_delivery_over_amount_seller">
                                    {{translate('free_Delivery_Over')}} ({{\App\CPU\BackEndHelper::currency_symbol()}})
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('free_delivery_over_amount_for_every_seller_if_they_do_not_set_any_range_yet')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="number" class="form-control" min="0" name="free_delivery_over_amount_seller" id="free_delivery_over_amount_seller" placeholder="{{translate('ex')}} : {{translate('10')}}" value="{{ \App\CPU\Convert::default($free_delivery_over_amount_seller) ?? 0 }}">
                            </div>
                        </div>

                        @php($refund_day_limit=\App\CPU\Helpers::get_business_settings('refund_day_limit'))
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label class="title-color" for="refund_day_limit">{{translate('Refund_Order_Validity')}} ({{translate('days')}})</label>
                                <input type="text" class="form-control" name="refund_day_limit" id="refund_day_limit" placeholder="{{translate('ex')}} : {{translate('10')}}" value="{{ $refund_day_limit ?? 0 }}">
                            </div>
                        </div>
                        @php($guest_checkout=\App\CPU\Helpers::get_business_settings('guest_checkout'))
                        <div class="col-xl-4 col-md-6">
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                <span class="title-color d-flex align-items-center gap-1">
                                    {{translate('guest_checkout')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled_users_can_complete_the_checkout_process_without_logging_in_to_the_system')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </span>

                                <label class="switcher" for="guest_checkout">
                                    <input type="checkbox" value="1" class="switcher_input" name="guest_checkout" id="guest_checkout" {{$guest_checkout == 1?'checked':''}}
                                    onclick="toogleModal(event,'guest_checkout','guest-checkout-on.png','guest-checkout-off.png','{{translate('by_Turning_ON_Guest_Checkout_Mode')}}','{{translate('by_Turning_Off_Guest_Checkout_Mode')}}',`<p>{{translate('user_can_place_order_without_login')}}</p>`,`<p>{{translate('user_cannot_place_order_without_login')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit" class="btn btn--primary px-4">{{translate('save')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>


    </div>
@endsection

@push('script_2')
<script>
    $('#free_delivery_responsibility').on('change', function(){
        if ($(this).val() == 'admin') {
            $('#free_delivery_over_amount_admin_area').fadeIn();
        }else{
            $('#free_delivery_over_amount_admin_area').fadeOut();
        }
    });
</script>
@endpush
