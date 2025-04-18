@extends('layouts.back-end.app-seller')
@section('title', translate('shop_view'))
@push('css_or_js')
<!-- Custom styles for this page -->
<link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
            {{translate('shop_Info')}}
        </h2>
    </div>
    <!-- End Page Title -->

    @include('seller-views.shop.inline-menu')

    <div class="row my-3 gy-3">
        @php($minimum_order_amount=\App\CPU\Helpers::get_business_settings('minimum_order_amount_status'))
        @php($minimum_order_amount_by_seller=\App\CPU\Helpers::get_business_settings('minimum_order_amount_by_seller'))

        @if ($minimum_order_amount && $minimum_order_amount_by_seller)
            <div class="col-md-6 {{ $minimum_order_amount_by_seller ? '':'d--none' }}">
                <form action="{{route('seller.shop.order-settings')}}" method="post" enctype="multipart/form-data"
                    id="add_fund">
                    @csrf
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="text-capitalize mb-0">
                                <i class="tio-dollar-outlined"></i>
                                {{translate('minimum_order_amount')}}
                            </h5>
                        </div>
                        <div class="card-body"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="mb-3">
                                <label class="title-color" for="minimum_order_amount">{{translate('amount')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('set_the_minimum_order_amount_a_customer_must_order_from_this_seller_shop')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="number" step="0.01" class="form-control w-100" id="minimum_order_amount"
                                    name="minimum_order_amount" min="1" value="{{ \App\CPU\Convert::default($seller->minimum_order_amount) ?? 0 }}"
                                    placeholder="0.00">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" id="submit"
                                    class="btn btn--primary px-4">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif


        @php($free_delivery_status=\App\CPU\Helpers::get_business_settings('free_delivery_status'))
        @php($free_delivery_responsibility=\App\CPU\Helpers::get_business_settings('free_delivery_responsibility'))

        @if ($free_delivery_status && $free_delivery_responsibility == 'seller')
        <div class="col-sm-12 col-md-6 {{ ($free_delivery_status && $free_delivery_responsibility == 'admin') ? 'd--none':'' }}">
            <form action="{{route('seller.shop.order-settings')}}" method="post" enctype="multipart/form-data"
                id="add_fund">
                @csrf
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="text-capitalize mb-0">
                            <i class="tio-dollar-outlined"></i>
                            {{translate('free_delivery_over_amount')}}
                        </h5>
                    </div>
                    <div class="card-body"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="row align-items-end">
                            <div class="col-xl-6 col-md-6">
                                <div
                                    class="d-flex justify-content-between align-items-center gap-10 form-control form-group">
                                    <span class="title-color d-flex align-items-center gap-1">
                                        {{translate('free_Delivery')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{translate('if_enabled_free_delivery_will_be_available_when_customers_order_over_a_certain_amount')}}">
                                            <img width="16"
                                                src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="free_delivery_status">
                                        <input type="checkbox" class="switcher_input" name="free_delivery_status"
                                            id="free_delivery_status" {{$seller->free_delivery_status == 1?'checked':''}}
                                            onclick="toogleModal(event,'free_delivery_status','free-delivery-on.png','free-delivery-off.png','{{translate('want_to_Turn_ON_Free_Delivery')}}','{{translate('want_to_Turn_OFF_Free_Delivery')}}',`<p>{{translate('if_enabled_the_free_delivery_feature_will_be_shown_from_the_system')}}</p>`,`<p>{{translate('if_disabled_the_free_delivery_feature_will_be_hidden_from_the_system')}}</p>`)">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex align-items-center gap-2"
                                        for="free_delivery_over_amount">
                                        {{translate('free_Delivery_Over')}} ({{\App\CPU\BackEndHelper::currency_symbol()}})
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                            data-placement="top" title="{{ translate('customers_will_get_free_delivery_if_the_order_amount_exceeds_the_given_amount') }} {{ translate('and_the_given_amount_will_be_added_as_seller_expenses') }}">
                                            <img width="16"
                                                src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </label>
                                    <input type="number" class="form-control" name="free_delivery_over_amount"
                                        id="free_delivery_over_amount" min="0"
                                        placeholder="{{translate('ex')}} : {{translate('10')}}"
                                        value="{{ \App\CPU\Convert::default($seller->free_delivery_over_amount) ?? 0 }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit"
                                class="btn btn--primary px-4">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection

@push('script')

@endpush
