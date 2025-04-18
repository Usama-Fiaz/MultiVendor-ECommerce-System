@extends('layouts.front-end.app')

@section('title',translate('shipping_Address'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/front-end/css/bootstrap-select.min.css') }}">

    <style>
        .btn-outline {
            border-color: {{$web_config['primary_color']}} ;
        }

        .btn-outline {
            border-color: {{$web_config['primary_color']}}    !important;
        }

        .btn-outline:hover {
            background: {{$web_config['primary_color']}};

        }

        .btn-outline:focus {
            border-color: {{$web_config['primary_color']}}    !important;
        }

        /*#location_map_canvas {*/
        /*    height: 100%;*/
        /*}*/

        .filter-option {
            display: block;
            width: 100%;
            height: calc(1.5em + 1.25rem + 2px);
            padding: 0.625rem 1rem;
            font-size: .9375rem;
            font-weight: 400;
            line-height: 1.5;
            color: #4b566b;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dae1e7;
            border-radius: 0.3125rem;
            box-shadow: 0 0 0 0 transparent;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-light + .dropdown-menu{
            transform: none !important;
            top: 41px !important;
        }
    </style>
@endpush

@section('content')
@php($billing_input_by_customer=\App\CPU\Helpers::get_business_settings('billing_input_by_customer'))
    <div class="container py-4 rtl __inline-56 px-0 px-md-3" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row mx-max-md-0">
            <div class="col-md-12 mb-3">
                <h3 class="font-weight-bold text-center text-lg-left">{{translate('checkout')}}</h3>
            </div>
            <section class="col-lg-8 px-max-md-0">
                <div class="checkout_details">
                <!-- Steps-->
                <div class="px-3 px-md-3">
                    @include('web-views.partials._checkout-steps',['step'=>2])
                </div>
                    @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                    <input type="hidden" id="physical_product" name="physical_product" value="{{ $physical_product_view ? 'yes':'no'}}">

                    <!-- Shipping methods table-->
                    @if($physical_product_view)
                        <div class="px-3 px-md-0">
                            <h4 class="pb-2 mt-4 fs-18 text-capitalize">{{ translate('shipping_address')}}</h4>
                        </div>

                        @php($shipping_addresses=\App\Model\ShippingAddress::where(['customer_id'=>auth('customer')->id(), 'is_billing'=>0, 'is_guest'=>0])->get())
                        <form method="post" class="card __card" id="address-form">
                            <div class="card-body p-0">
                                <ul class="list-group">
                                    <li class="list-group-item" onclick="anotherAddress()">
                                        @if ($shipping_addresses->count() >0)
                                            <div class="d-flex align-items-center justify-content-end gap-3">
                                                <div class="dropdown">
                                                    <button class="form-control dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{translate('saved_address')}}
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right saved-address-dropdown scroll-bar-saved-address" aria-labelledby="dropdownMenuButton">
                                                        @foreach($shipping_addresses as $key=>$address)
                                                        <div class="dropdown-item select_shipping_address {{$key == 0 ? 'active' : ''}}" id="shippingAddress{{$key}}">
                                                            <input type="hidden" class="selected_shippingAddress{{$key}}" value="{{$address}}">
                                                            <input type="hidden" name="shipping_method_id" value="{{$address['id']}}">
                                                            <div class="media gap-2">
                                                                <div class="">
                                                                    <i class="tio-briefcase"></i>
                                                                </div>
                                                                <div class="media-body">
                                                                    <div class="mb-1 text-capitalize">{{$address->address_type}}</div>
                                                                    <div class="text-muted fs-12 text-capitalize text-wrap">{{$address->address}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="accordion">
                                            <div class="">
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('contact_person_name')}}
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="contact_person_name" {{$shipping_addresses->count()==0?'required':''}} id="name">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('phone')}}
                                                                    <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="phone"  id="phone" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        @if(!auth('customer')->check())
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{ translate('email')}}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="email" class="form-control"  name="email" id="email" {{$shipping_addresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('address_type')}}</label>
                                                                <select class="form-control" name="address_type" id="address_type">
                                                                    <option
                                                                        value="permanent">{{ translate('permanent')}}</option>
                                                                    <option value="home">{{ translate('home')}}</option>
                                                                    <option
                                                                        value="others">{{ translate('others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('country')}}
                                                                    <span class="text-danger">*</span></label>
                                                                <select name="country" id="country" class="form-control selectpicker" data-live-search="true" required>
                                                                    @forelse($countries as $country)
                                                                        <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                    @empty
                                                                        <option value="">{{ translate('no_country_to_deliver') }}</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('city')}}<span  class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="city" id="city" {{$shipping_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('zip_code')}}
                                                                    <span class="text-danger">*</span></label>
                                                                @if($zip_restrict_status == 1)
                                                                    <select name="zip" class="form-control selectpicker" data-live-search="true" id="select2-zip-container" required>
                                                                        @forelse($zip_codes as $code)
                                                                        <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @empty
                                                                            <option value="">{{ translate('no_zip_to_deliver') }}</option>
                                                                        @endforelse
                                                                    </select>
                                                                @else
                                                                <input type="text" class="form-control"
                                                                       name="zip" id="zip" {{$shipping_addresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('address')}}<span class="text-danger">*</span></label>
                                                                <textarea class="form-control" id="address" type="text" name="address" id="address" {{$shipping_addresses->count()==0?'required':''}}></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <input id="pac-input" class="controls rounded __inline-46" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                        <div class="__h-200px" id="location_map_canvas"></div>
                                                    </div>
                                                    <!--save or update shipping address -->
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <label class="form-check-label" id="save_address_label">
                                                            <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="0">
                                                            @if(auth('customer')->check())
                                                                <input type="checkbox" name="save_address" id="save_address">
                                                                {{ translate('save_this_Address') }}
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <!-- end save or update shipping address -->
                                                    <input type="hidden" id="latitude"
                                                           name="latitude" class="form-control d-inline"
                                                           placeholder="{{ translate('ex')}} : -94.22213"
                                                           value="{{$default_location?$default_location['lat']:0}}" required
                                                           readonly>
                                                    <input type="hidden"
                                                           name="longitude" class="form-control"
                                                           placeholder="{{ translate('ex')}} : 103.344322" id="longitude"
                                                           value="{{$default_location?$default_location['lng']:0}}" required
                                                           readonly>

                                                    <button type="submit" class="btn btn--primary" style="display: none"
                                                            id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    @endif

                    <div style="display: {{$billing_input_by_customer?'':'none'}}">
                        <!-- billing methods table-->
                        <div class="billing-methods_label d-flex flex-wrap justify-content-between gap-2 mt-4 pb-3 px-3 px-md-0">
                            <h4 class="mb-0 fs-18 text-capitalize">{{ translate('billing_address')}}</h4>

                            @php($billing_addresses=\App\Model\ShippingAddress::where(['customer_id'=>auth('customer')->id(), 'is_billing'=>1, 'is_guest'=>'0'])->get())
                            @if($physical_product_view)
                                <div class="form-check d-flex gap-3 align-items-center">
                                    <input type="checkbox" id="same_as_shipping_address" onclick="hide_billingAddress()"
                                        name="same_as_shipping_address" class="form-check-input" {{$billing_input_by_customer==1?'':'checked'}}>
                                    <label class="form-check-label" for="same_as_shipping_address">
                                        {{ translate('same_as_shipping_address')}}
                                    </label>
                                </div>
                            @endif
                        </div>

                        <form method="post" class="card __card" id="billing-address-form">
                            <div id="hide_billing_address" class="">
                                <ul class="list-group">

                                    <li class="list-group-item" onclick="billingAddress()">
                                        @if ($billing_addresses->count() >0)
                                            <div class="d-flex align-items-center justify-content-end gap-3">

                                                <div class="dropdown">
                                                    <button class="form-control dropdown-toggle text-capitalize" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{translate('saved_address')}}
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right saved-address-dropdown scroll-bar-saved-address" aria-labelledby="dropdownMenuButton">
                                                        @foreach($billing_addresses as $key=>$address)
                                                            <div class="dropdown-item select_billing_address {{$key == 0 ? 'active' : ''}}" id="billingAddress{{$key}}">
                                                                <input type="hidden" class="selected_billingAddress{{$key}}" value="{{$address}}">
                                                                <input type="hidden" name="billing_method_id" value="{{$address['id']}}">
                                                                <div class="media gap-2">
                                                                    <div class="">
                                                                        <i class="tio-briefcase"></i>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="mb-1 text-capitalize">{{$address->address_type}}</div>
                                                                        <div class="text-muted fs-12 text-capitalize text-wrap">{{$address->address}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="accordion">
                                            <div class="">
                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('contact_person_name')}}<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="billing_contact_person_name" id="billing_contact_person_name"  {{$billing_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('phone')}}
                                                                    <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="billing_phone" id="billing_phone" {{$billing_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        @if(!auth('customer')->check())
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{ translate('email')}}
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        name="billing_contact_email" id="billing_contact_email" id {{$billing_addresses->count()==0?'required':''}}>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('address_type')}}</label>
                                                                <select class="form-control" name="billing_address_type" id="billing_address_type">
                                                                    <option value="permanent">{{ translate('permanent')}}</option>
                                                                    <option value="home">{{ translate('home')}}</option>
                                                                    <option value="others">{{ translate('others')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ translate('country')}}<span class="text-danger">*</span></label>
                                                                <select name="billing_country" id="" class="form-control selectpicker" data-live-search="true" id="billing_country">
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">{{ translate('city')}}<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="billing_city"
                                                                    name="billing_city" {{$billing_addresses->count()==0?'required':''}}>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ translate('zip_code')}}
                                                                    <span class="text-danger">*</span></label>
                                                                @if($zip_restrict_status)
                                                                    <select name="billing_zip" id="" class="form-control selectpicker" data-live-search="true" id="select_billing_zip">
                                                                        @foreach($zip_codes as $code)
                                                                            <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <input type="text" class="form-control" id="billing_zip"
                                                                           name="billing_zip" {{$billing_addresses->count()==0?'required':''}}>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label>{{ translate('address')}}<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="billing_address" type="billing_text" name="billing_address" id="billing_address" {{$billing_addresses->count()==0?'required':''}}></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <input id="pac-input-billing" class="controls rounded __inline-46"
                                                            title="{{translate('search_your_location_here')}}"
                                                            type="text"
                                                            placeholder="{{translate('search_here')}}"/>
                                                        <div class="__h-200px" id="location_map_canvas_billing"></div>
                                                    </div>

                                                    <!--save or update billing  address -->
                                                    <input type="hidden" name="billing_method_id" id="billing_method_id" value="0">
                                                    @if(auth('customer')->check())
                                                    <div class=" d-flex gap-3 align-items-center">
                                                        <label class="form-check-label" id="save-billing-address-label">
                                                            <input type="checkbox" name="save_address_billing" id="save_address_billing">
                                                            {{ translate('save_this_Address') }}
                                                        </label>
                                                    </div>
                                                    @endif
                                                    <!--end save or update billing  address -->
                                                    <input type="hidden" id="billing_latitude"
                                                        name="billing_latitude" class="form-control d-inline"
                                                        placeholder="{{ translate('ex')}} : -94.22213"
                                                        value="{{$default_location?$default_location['lat']:0}}" required
                                                        readonly>
                                                    <input type="hidden"
                                                        name="billing_longitude" class="form-control"
                                                        placeholder="{{ translate('ex')}} : 103.344322" id="billing_longitude"
                                                        value="{{$default_location?$default_location['lng']:0}}" required
                                                        readonly>

                                                    <button type="submit" class="btn btn--primary" style="display: none"
                                                            id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            @include('web-views.partials._order-summary')
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/front-end/js/bootstrap-select.min.js') }}"></script>

    <script>
         $(document).ready(function() {
            /*shipping*/
            let activeId = $('.select_shipping_address.active').attr('id');
            if(activeId){
                let shipping_value = $('.selected_' + activeId).val();
                shipping_method_select(shipping_value)
            }
            /*billing*/
            let billingaActiveId = $('.select_billing_address.active').attr('id');
            // alert('billingaActiveId')
            if(billingaActiveId){
                let billing_value = $('.selected_' + billingaActiveId).val();
                console.log(billing_value);
                billing_method_select(billing_value)
            }
        })
        /*shipping*/
        const addressItems = document.querySelectorAll('.select_shipping_address');
        addressItems.forEach(item => {
            item.addEventListener('click', function () {
                const selectedAddressId = item.id;
                let shipping_value = $('.selected_' + selectedAddressId).val();
                $('.select_shipping_address').removeClass('active');
                $('#'+selectedAddressId).addClass('active')
                shipping_method_select(shipping_value)
            });
        });


        /*shipping field*/
        function shipping_method_select(get_value){


            let shipping_method_id = $('.select_shipping_address.active input[name="shipping_method_id"]').val()
            let shipping_value= JSON.parse(get_value);
            $('#name').val(shipping_value.contact_person_name);
            $('#phone').val(shipping_value.phone);
            $('#address').val(shipping_value.address);
            $('#city').val(shipping_value.city);
            $('#zip').val(shipping_value.zip);
            $('#country').val(shipping_value.country);
            $('#address_type').val(shipping_value.address_type);
            let update_address = `
                <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${shipping_method_id}">
                <input type="checkbox" name="update_address" id="update_address">
                {{ translate('Update_this_Address') }}
            `;
            $('#save_address_label').html(update_address);
        }
        /* end */
        /*billing*/
        const addressItemsBilling = document.querySelectorAll('.select_billing_address');
        addressItemsBilling.forEach(item => {
            item.addEventListener('click', function () {
                const selectedBillingAddressId = item.id;
                let billing_value = $('.selected_' + selectedBillingAddressId).val();
                $('.select_billing_address').removeClass('active');
                $('#'+selectedBillingAddressId).addClass('active')
                billing_method_select(billing_value);
                console.log(billing_value)
            });
        });

        function billing_method_select(get_billing_value){

            let billing_value= JSON.parse(get_billing_value);
            let billing_method_id = $('.select_billing_address.active input[name="billing_method_id"]').val()
            $('#billing_contact_person_name').val(billing_value.contact_person_name);
            $('#billing_phone').val(billing_value.phone);
            $('#billing_address').val(billing_value.address);
            $('#billing_city').val(billing_value.city);
            $('#billing_zip').val(billing_value.zip);
            $('#select_billing_zip').text(billing_value);
            $('#billing_country').val(billing_value.country);
            $('#billing_address_type').val(billing_value.address_type);
            let update_address_billing = `
                <input type="hidden" name="billing_method_id" id="billing_method_id" value="${billing_method_id}">
                <input type="checkbox" name="update_billing_address" id="update_billing_address">
                {{ translate('Update_this_Address') }}
            `;
            $('#save-billing-address-label').html(update_address_billing);
        }
    </script>
    <script>

        function anotherAddress() {
            $('#sh-0').prop('checked', true);
            $("#collapseThree").collapse();
        }

        function billingAddress() {
            $('#bh-0').prop('checked', true);
            $("#billing_model").collapse();
        }

    </script>
    <script>
        function hide_billingAddress() {
            let check_same_as_shippping = $('#same_as_shipping_address').is(":checked");
            console.log(check_same_as_shippping);
            if (check_same_as_shippping) {
                $('#hide_billing_address').hide();
            } else {
                $('#hide_billing_address').show();
            }
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&callback=mapsShopping&libraries=places&v=3.49" defer></script>
    <script>
        function initAutocomplete() {
            var myLatLng = {
                lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: {{$default_location?$default_location['lng']:'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>

    <script>
        function initAutocompleteBilling() {
            var myLatLng = {
                lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: {{$default_location?$default_location['lng']:'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas_billing"), {
                center: {
                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('billing_latitude').value = coordinates['lat'];
                document.getElementById('billing_longitude').value = coordinates['lng'];

                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('billing_address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input-billing");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('billing_latitude').value = this.position.lat();
                        document.getElementById('billing_longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };

        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>
    <script>
        function checkout() {
            let physical_product = $('#physical_product').val();

            if(physical_product === 'yes') {
                var billing_addresss_same_shipping = $('#same_as_shipping_address').is(":checked");

                let allAreFilled = true;
                document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
                    if (!allAreFilled) return;
                    if (!i.value) allAreFilled = false;
                    if (i.type === "radio") {
                        let radioValueCheck = false;
                        document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                            if (r.checked) radioValueCheck = true;
                        });
                        allAreFilled = radioValueCheck;
                    }
                });

                //billing address saved
                let allAreFilled_shipping = true;

                if (billing_addresss_same_shipping != true) {

                    document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                        if (!allAreFilled_shipping) return;
                        if (!i.value) allAreFilled_shipping = false;
                        if (i.type === "radio") {
                            let radioValueCheck = false;
                            document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                                if (r.checked) radioValueCheck = true;
                            });
                            allAreFilled_shipping = radioValueCheck;
                        }
                    });
                }
            }else {
                var billing_addresss_same_shipping = false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('customer.choose-shipping-address-other')}}',
                data: {
                    physical_product: physical_product,
                    shipping: physical_product === 'yes' ? $('#address-form').serialize() : null,
                    billing: $('#billing-address-form').serialize(),
                    billing_addresss_same_shipping: billing_addresss_same_shipping
                },

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        location.href = '{{route('checkout-payment')}}';
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
                error: function (data) {
                    let error_msg = data.responseJSON.errors;
                    toastr.error(error_msg, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

        }
        function mapsShopping() {
            try {
                initAutocomplete();
            } catch (error) {
            }
            try {
                initAutocompleteBilling();
            } catch (error) {
            }
        }
    </script>
@endpush
