@extends('layouts.front-end.app')

@section('title',translate('home'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Page Title-->
    <div class="container __inline-45">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 folot-left headerTitle">{{translate('ADDRESSES')}}</h1>
            </div>
        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 __inline-45">
        <div class="row">
            <!-- Sidebar-->
            <div class="sidebarR col-lg-3">
                <!--Price Sidebar-->
                <div class="price_sidebar rounded-lg box-shadow-sm __mb-n-10" id="shop-sidebar">
                    <div class="box-shadow-sm">

                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL">
                            <h3 class="widget-title btnF font-bold"><a href="{{ route('orderList') }}" class="__color-1B7FED">{{translate('my_Orders')}}</a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('wishList') }}"> {{translate('wishlist')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href=""> {{translate('chat_with_sellers')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('profile') }}"> {{translate('profile_info')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{translate('address')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class=" sidebarL">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="{{ route('support-ticket') }}">{{translate('support_ticket')}} </a>
                            </h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-0 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{translate('transaction_history')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                    <div class="pb-1 __pt-12">
                        <!-- Filter by price-->
                        <div class="sidebarL ">
                            <h3 class="widget-title btnF font-bold"><a
                                    href="">{{translate('payment_method')}} </a></h3>
                            <div class="divider-role __inline-41"></div>

                        </div>
                    </div>
                </div>
            </div>

            <section class="col-lg-9 mt-3">
                <span class="__color-6A6A6A">{{translate('no_address_found')}}.</span>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="row">
                                    <div class="col-md-12"><h5
                                            class="modal-title font-nameA ">{{translate('add_a_new_address')}}</h5>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-body">
                                <form class="">

                                    <!-- Nav pills -->
                                    <ul class="nav nav-pills ml-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active btn-p" data-toggle="pill"
                                               href="#home">{{translate('permanent')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill"
                                               href="#menu1">{{translate('home')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="pill"
                                               href="#menu2">{{translate('office')}}</a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div id="home" class="container tab-pane active"><br>


                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label
                                                        for="firstName">{{translate('contact_person_name')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{translate('last_name')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="firstName">{{translate('city')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{translate('zip_code')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="firstName">{{translate('state')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">{{translate('country')}}</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="firstName">{{translate('phone')}}</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                           placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">

                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class=" closeB"
                                                    data-dismiss="modal">{{translate('close')}}</button>
                                            <button type="button"
                                                    class="btn btn-p"> {{translate('update_Information')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-p btn-b float-right" data-toggle="modal" data-target="#exampleModal">
                {{translate('add_New_Address')}}
            </button>
        </div>
    </div>

@endsection

