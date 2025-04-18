@extends('layouts.back-end.app')

@section('title', translate('delivery_Restriction'))

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-selection__rendered{
            width: 100%;
        }
        .bootstrap-tagsinput {
            min-width: auto;
            width: 100%;
            margin-bottom: 0;
        }
        /* .bootstrap-tagsinput input {
            height: 48px !important;
        } */
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_Setup')}}
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

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0 text-capitalize d-flex gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/delivery2.png')}}" alt="">
                    {{translate('delivery')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center gap-10 form-control h-auto min-form-control-height mt-2" id="customer_wallet_section">
                            <span class="title-color">
                                {{translate('delivery_available_country')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_admin_can_deliver_orders_outside_his_country') }}">
                                    <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                            </span>

                            <form action="{{ route('admin.business-settings.delivery-restriction.country-restriction-status-change') }}" method="post" id="country_area_form">
                                @csrf
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input" name="status" id="country_area" {{ isset($country_restriction_status->value) && $country_restriction_status->value  == 1 ? 'checked' : '' }} value="1" onclick="toogleStatusModal(event,'country_area','delivery-available-country-on.png','delivery-available-country-off.png','{{translate('want_to_Turn_ON_Delivery_Available_Country')}}?','{{translate('want_to_Turn_OFF_Delivery_Available_Country')}}?',`<p>{{translate('If_enabled_the_admin_or_seller_can_deliver_orders_to_the_selected_countries')}}</p>`,`<p>{{translate('If_disabled_there_will_be_no_delivery_restrictions_for_admin_or_sellers')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center gap-10 form-control h-auto min-form-control-height mt-2" id="customer_wallet_section">
                            <span class="title-color">
                                {{translate('delivery_available_zip_code_area')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_deliveries_will_be_available_only_in_the_added_zip_code_areas') }}">
                                    <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                            </span>

                            <form action="{{ route('admin.business-settings.delivery-restriction.zipcode-restriction-status-change') }}" method="post" id="zip_area_form">
                                @csrf
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input" name="status" id="zip_area" {{ isset($zip_code_area_restriction_status) && $zip_code_area_restriction_status->value  == 1? 'checked' : '' }} value="1" onclick="toogleStatusModal(event,'zip_area','zip-code-on.png','zip-code-off.png','{{translate('want_to_Turn_ON_Delivery_Available_Zip_Code_Area')}}','{{translate('want_to_Turn_OFF_Delivery_Available_Zip_Code_Area')}}',`<p>{{translate('if_enabled_deliveries_will_be_available_only_in_the_added_zip_code_areas')}}</p>`,`<p>{{translate('if_disabled_there_will_be_no_delivery_restrictions_based_on_zip_code_areas')}}</p>`)">
                                    <span class="switcher_control"></span>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-2">
            <!-- Delivery to Country -->
            <div class="col-lg-6 {{ isset($country_restriction_status->value) && $country_restriction_status->value  != 1 ? 'd-none' : '' }}">
                <div class="card mb-3">
                    <div class="card-body country-disable">
                        <form action="{{ route('admin.business-settings.delivery-restriction.add-delivery-country') }}"
                            method="post">
                            @csrf
                            <div class="form-group">
                                <label class="title-color d-flex font-weight-bold">{{translate('country')}} </label>
                                <div class="d-flex gap-2">
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="country_code[]" id="choice_attributes" multiple="multiple">
                                        @foreach($countries as $country)
                                            <option value="{{ $country['code'] }}" {{ in_array($country['code'], $stored_country_code) ? 'disabled' : '' }}>
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn--primary px-4">{{translate('save')}}</button>
                                </div>
                            </div>
                        </form>
                        <div class="mt-6">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{translate('sl')}}</th>
                                        <th class="text-center">{{translate('country_name')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($stored_countries as $k=>$store)
                                        <td class="">{{$stored_countries->firstItem()+$k}}</td>
                                        @foreach($countries as $country)
                                            @if($store->country_code == $country['code'])
                                                <td class="text-center">{{ $country['name'] }}</td>
                                            @endif
                                        @endforeach
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-danger btn-sm square-btn"
                                                    href="javascript:"
                                                    title="{{translate('delete')}}"
                                                    onclick="form_alert('country-{{$store->id}}','{{translate('want_to_delete_this_item?')}}')">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                    <form
                                                        action="{{route('admin.business-settings.delivery-restriction.delivery-country-delete',['id' => $store->id])}}"
                                                        method="post" id="country-{{$store->id}}">
                                                        @csrf @method('delete')
                                                    </form>
                                            </div>
                                        </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <div class="text-center p-4">
                                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                                    <p class="mb-0">{{translate('no_country_found')}}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="d-flex justify-content-lg-end">
                                    <!-- Pagination -->
                                    {{$stored_countries->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Delivery to Country -->

            <!-- Delivery to zipcode area -->
            <div class="col-lg-6 {{ isset($zip_code_area_restriction_status) && $zip_code_area_restriction_status->value  != 1? 'd-none' : '' }}">
                <div class="card mb-3">
                    <div class="card-body zip-disable">
                        <form action="{{ route('admin.business-settings.delivery-restriction.add-zip-code') }}"
                            method="post">
                            @csrf
                            <label class="title-color d-flex font-weight-bold"> {{translate('zip_code')}} </label>

                            <div class="d-flex gap-2">
                                <input type="text" class="form-control " name="zipcode" placeholder="{{ translate('enter_zip_code') }}" data-role="tagsinput" required>
                                <button type="submit" class="btn btn--primary px-4 zip_code">{{translate('save')}}</button>
                            </div>
                            <p class="mt-2">* {{translate('multiple_zip_codes_can_be_inputted_by_comma_separating_or_pressing_enter_button')}}</p>
                        </form>

                        <div class="mt-6">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{translate('sl')}}</th>
                                        <th class="text-center">{{translate('zip_code')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($stored_zip as $k=>$zip)
                                        <tr>
                                            <td>{{$stored_zip->firstItem()+$k}}</td>
                                            <td class="text-center">{{ $zip->zipcode }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-danger btn-sm square-btn"
                                                    href="javascript:"
                                                    title="{{translate('delete')}}"
                                                    onclick="form_alert('zip-{{$zip->id}}','{{translate('want_to_delete_this_item?')}}')">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                    <form
                                                        action="{{route('admin.business-settings.delivery-restriction.zip-code-delete',['id' => $zip->id])}}"
                                                        method="post" id="zip-{{$zip->id}}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </div>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <div class="text-center p-4">
                                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                                    <p class="mb-0">{{translate('no_zip_code_found')}}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <div class="d-flex justify-content-lg-end">
                                    <!-- Pagination -->
                                    {{$stored_zip->links()}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Delivery to zipcode area -->

        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/back-end') }}/js/tags-input.min.js"></script>
    <script>

        $('.zip_code').on('click', function(){
            if ($.trim($("input[name='zipcode']").val()) === '') {
                toastr.error("{{ translate('please_enter_zip_code') }}");
            }
        })
        $(".js-example-responsive").select2({
            theme: "classic",
            placeholder: "{{ translate('Select_Country') }}",
            allowClear: true,

        });
        $('.select2-search__field').css('width', '100%');

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        // $(".js-example-responsive").select2({
        //     width: 'resolve'
        // });


    </script>
    <script>
        let country_status = {{ isset($country_restriction_status)? $country_restriction_status->value: 0  }};
        let zip_status = {{ isset($zip_code_area_restriction_status)? $zip_code_area_restriction_status->value : 0 }};

        if (country_status === 0) {
            $(".country-disable").hide();
        }
        if(zip_status === 0) {
            $(".zip-disable").hide();
        }

        function status_change(t) {
            let url = $(t).data('url');
            let checked = $(t).prop("checked");
            let status = checked === true ? 1 : 0;

            Swal.fire({
                title: '{{ translate("are_you_sure") }}?',
                text: '{{ translate("want_to_change_status") }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            status: status
                        },
                        success: function (data) {
                            if (data.status === true) {
                                toastr.success(data.message);
                                if (status === 0){
                                    $(t).parents('.card-header').siblings('.card-body').hide();
                                } else if (status === 1){
                                    $(t).parents('.card-header').siblings('.card-body').show();
                                }
                            }
                        }
                    });
                }
            }
            )
        }

        $('#country_area_form').on('submit', function(e){
            e.preventDefault();

            let data = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: data.attr('action'),
                type: "POST",
                data: data.serialize(),
                success: function (data) {
                    if (data.status === true) {
                        toastr.success(data.message);
                        location.reload();
                    }
                }
            });
        })

        $('#zip_area_form').on('submit', function(e){
            e.preventDefault();

            let data = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: data.attr('action'),
                type: "POST",
                data: data.serialize(),
                success: function (data) {
                    if (data.status === true) {
                        toastr.success(data.message);
                        location.reload();
                    }
                }
            });
        })

    </script>
@endpush
