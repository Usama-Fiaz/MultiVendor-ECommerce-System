@extends('layouts.front-end.app')

@section('title', translate('my_Address'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
    <link rel="stylesheet" href="{{ asset('public/assets/front-end/css/bootstrap-select.min.css') }}">

    <style>
        .cz-sidebar-body h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}} !important;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: {{$web_config['primary_color']}};
        }

        .iconHad {
            color: {{$web_config['primary_color']}};
        }
        .namHad {
            padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 13px;
        }
        .modal-backdrop {
            z-index: 0 !important;
            display: none;
        }
        .donate-now li {
            margin: {{Session::get('direction') === "rtl" ? '0 0 0 5px' : '0 5px 0 0'}};
        }
        .donate-now input[type="radio"]:checked + label,
        .Checked + label {
            background: {{$web_config['primary_color']}};
        }
        .filter-option{
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
    <div class="__account-address">
        <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-name">{{translate('add_new_address')}}</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('address-store')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Nav pills -->
                                    <ul class="donate-now d-flex">
                                        <li>
                                            <input type="radio" id="a25" name="addressAs" value="permanent"/>
                                            <label for="a25" class="component">{{translate('permanent')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a50" name="addressAs" value="home"/>
                                            <label for="a50" class="component">{{translate('home')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="a75" name="addressAs" value="office" checked="checked"/>
                                            <label for="a75" class="component">{{translate('office')}}</label>
                                        </li>

                                    </ul>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <!-- Nav pills -->

                                <ul class="donate-now">
                                    <li>
                                        <input type="radio" name="is_billing" id="b25" value="0" checked/>
                                        <label for="b25" class="billing_component">{{translate('shipping')}}</label>
                                    </li>
                                    <li>
                                        <input type="radio" name="is_billing" id="b50" value="1"/>
                                        <label for="b50" class="billing_component">{{translate('billing')}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>
                                   <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">{{translate('contact_person_name')}}</label>
                                            <input class="form-control" type="text" id="name" name="name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{translate('phone')}}</label>
                                            <input class="form-control" type="text" id="phone" name="phone" required>
                                        </div>
                                    </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address-city">{{translate('city')}}</label>
                                        <input class="form-control" type="text" id="address-city" name="city" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="zip">{{translate('zip_code')}}</label>
                                        @if($zip_restrict_status)
                                            <select name="zip" id="" class="form-control selectpicker" data-live-search="true">
                                                @foreach($zip_codes as $code)
                                                    <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input class="form-control" type="text" id="zip" name="zip" required>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address-city">{{translate('country')}}</label>
                                        <select name="country" id="" class="form-control selectpicker" data-live-search="true">
                                            @foreach($countries as $d)
                                                <option value="{{ $d['name'] }}">{{ $d['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">{{translate('address')}}</label>

                                        <textarea class="form-control" id="address"
                                                            type="text"  name="address" required></textarea>
                                    </div>
                                    @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                                    <div class="form-group col-md-12">
                                        <input id="pac-input" class="controls rounded __inline-46" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                        <div class="__h-200px" id="location_map_canvas"></div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="latitude"
                                name="latitude" class="form-control d-inline"
                                placeholder="{{ translate('ex')}} : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                            <input type="hidden"
                                name="longitude" class="form-control"
                                placeholder="{{ translate('ex')}} : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('add_informations')}}  </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>

        <!-- Page Content-->
        <div class="container py-4 rtl">
            <div class="row ">
                <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
                <!-- Content  -->
                <section class="col-lg-9">
                    <div class="d-flex justify-content-end mb-3 d-lg-none">
                        <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                                </svg>
                        </button>
                    </div>

                    <div class="card card-body border-0">
                        <!-- Addresses list-->
                        <div class="d-flex justify-content-between align-items-center mb-3 gap-2">
                            <h5 class="font-bold m-0">{{translate('addresses')}}</h5>
                            <button type="submit" class="btn btn--primary text-capitalize" data-toggle="modal"
                                data-target="#exampleModal" id="add_new_address">
                                <img src="{{asset('/public/assets/front-end/img/add-address-icon.png')}}" class="" alt="">
                                {{translate('add_address')}}
                            </button>
                        </div>
                        @if ($shippingAddresses->count() ==0)
                            <div class="text-center text-capitalize pb-5 pt-5">
                                <img class="mb-4" src="{{asset('public/assets/front-end/img/icons/address.svg')}}" alt="" width="70">
                                <h5 class="fs-14">{{translate('no_address_found')}}!</h5>
                            </div>
                        @endif
                        <div class="row g-3">

                            @foreach($shippingAddresses as $shippingAddress)
                                <section class="col-lg-6 col-md-6">
                                    <div class="card __shadow h-100">
                                        <div class="card-header d-flex justify-content-between d-flex align-items-center bg-aliceblue border-0">
                                            <div class="w-0 flex-grow-1">
                                                <h6 class="mb-0 fw-semibold"> {{translate($shippingAddress['address_type'])}} {{translate('address')}} ({{$shippingAddress['is_billing']==1?translate('Billing_address'):translate('shipping_address')}}) </h6>
                                            </div>
                                            <div class="d-flex justify-content-between gap-2 align-items-center">
                                                <a class="" title="Edit Address" id="edit" href="{{route('address-edit',$shippingAddress->id)}}">
                                                    <img src="{{asset('/public/assets/front-end/img/address-edit-icon.png')}}" width="19" alt="">
                                                </a>

                                                <button class="no-gutter remove-address-by-modal" data-link="{{ route('address-delete',['id'=>$shippingAddress->id])}}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- edit modal -->


                                        <div class="card-body">
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px">{{translate('Name')}} </span> :&nbsp; {{$shippingAddress['contact_person_name']}}</span>
                                            </div>
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px">{{translate('phone')}}  </span> :&nbsp; {{$shippingAddress['phone']}}</span>
                                            </div>
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px">{{translate('city')}}  </span> :&nbsp; {{$shippingAddress['city']}}</span>
                                            </div>
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px"> {{translate('zip_code')}} </span> :&nbsp;{{$shippingAddress['zip']}}</span>
                                            </div>
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px">{{translate('address')}} </span> :&nbsp;{{$shippingAddress['address']}}</span>
                                            </div>
                                            <div><span class="font-nameA"> <span class="fw-semibold min-w-60px">{{translate('country')}} </span> :&nbsp;{{$shippingAddress['country']}}</span>
                                            </div>

                                        </div>
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('public/assets/front-end/js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function (){

            $('.address_type_li').on('click', function (e) {
                // e.preventDefault();
                $('.address_type_li').find('.address_type').removeAttr('checked', false);
                $('.address_type_li').find('.component').removeClass('active_address_type');
                $(this).find('.address_type').attr('checked', true);
                $(this).find('.address_type').removeClass('add_type');
                $('#defaultValue').removeClass('add_type');
                $(this).find('.address_type').addClass('add_type');

                $(this).find('.component').addClass('active_address_type');
            });
        })

        $('#addressUpdate').on('click', function(e){
            e.preventDefault();
            let addressAs, address, name, zip, city, state, country, phone;

            addressAs = $('.add_type').val();

            address = $('#own_address').val();
            name = $('#person_name').val();
            zip = $('#zip_code').val();
            city = $('#city').val();
            state = $('#own_state').val();
            country = $('#own_country').val();
            phone = $('#own_phone').val();

            let id = $(this).attr('data-id');

            if (addressAs != '' && address != '' && name != '' && zip != '' && city != '' && state != '' && country != '' && phone != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('address-update')}}",
                    method: 'POST',
                    data: {
                        id : id,
                        addressAs: addressAs,
                        address: address,
                        name: name,
                        zip: zip,
                        city: city,
                        state: state,
                        country: country,
                        phone: phone
                    },
                    success: function () {
                        toastr.success('{{translate('address_update_successfully')}}.');
                        location.reload();


                    }
                });
            }else{
                toastr.error('{{translate('all_input_field_required')}}.');
            }

        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&v=3.49"></script>
    <script>

        function initAutocomplete() {
            var myLatLng = { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
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
        $(document).on('ready', function () {
            initAutocomplete();

        });

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
    </script>
@endpush
