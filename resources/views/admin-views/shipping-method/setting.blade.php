@extends('layouts.back-end.app')

@section('title', translate('shipping_method'))

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

        <div class="card">
            <div class="card-header">
                <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                    {{translate('shipping')}}
                </h5>
            </div>
            @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
            <div class="card-body">
                <form action="{{ route('admin.business-settings.shipping-method.shipping-store') }}" method="post">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label class="title-color d-flex">{{translate('shipping_responsibility')}}</label>
                                <div class="form-control min-form-control-height h-auto form-group d-flex flex-wrap gap-2">
                                    <!-- Custom Radio -->
                                    <div class="custom-control custom-radio flex-grow-1">
                                        <input type="radio" class="custom-control-input" value="inhouse_shipping" name="shipping_method" id="inhouse_shipping" {{ $shippingMethod=='inhouse_shipping'?'checked':'' }}>
                                        <label class="custom-control-label" for="inhouse_shipping" onclick="shipping_responsibility_modal(event,'inhouse_shipping','seller-wise-shipping.png','inhouse-shipping.png','{{translate('want_to_change_the_shipping_responsibility_to_Seller_Wise')}}','{{translate('want_to_change_the_shipping_responsibility_to_Inhouse')}}',`<p>{{translate('admin_will_handle_the_shipping_responsibilities_when_you_choose_inhouse_shipping_method')}}</p>`,`<p>{{translate('admin_will_handle_the_shipping_responsibilities_when_you_choose_inhouse_shipping_method')}}</p>`)">{{translate('inhouse_shipping')}}</label>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="custom-control custom-radio flex-grow-1">
                                        <input type="radio" class="custom-control-input" value="sellerwise_shipping" name="shipping_method" id="sellerwise_shipping" {{ $shippingMethod=='sellerwise_shipping'?'checked':'' }}>
                                        <label class="custom-control-label" for="sellerwise_shipping" onclick="shipping_responsibility_modal(event,'sellerwise_shipping','inhouse-shipping.png','seller-wise-shipping.png','{{translate('Want_to_change_the_shipping_responsibility_to_Inhouse')}}','{{translate('want_to_change_the_shipping_responsibility_to_Seller_Wise')}}',`<p>{{translate('sellers_will_handle_the_shipping_responsibilities_when_you_choose_seller_wise_shipping_method')}}</p>`,`<p>{{translate('sellers_will_handle_the_shipping_responsibilities_when_you_choose_seller_wise_shipping_method')}}</p>`)">{{translate('seller_wise_shipping')}}</label>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>

                        @php($admin_shipping = \App\Model\ShippingType::where('seller_id',0)->first())
                        @php($shippingType =isset($admin_shipping)==true?$admin_shipping->shipping_type:'order_wise')
                        <div class="col-md-6">
                            <div class="">

                                <label class="title-color" id="for_inhouse_deliver" style="{{ $shippingMethod != 'sellerwise_shipping' ? 'display:none':'' }}">{{translate('shipping_method')}}</label>
                                <label class="title-color" id="for_seller_deliver" style="{{ $shippingMethod == 'sellerwise_shipping' ? 'display:none':'' }}">{{translate('shipping_method_for_In-house_deliver')}}</label>

                                <select class="form-control text-capitalize w-100" name="shippingCategory"
                                        onchange="shipping_type(this.value);">
                                    <option value="0" selected disabled>---{{translate('select')}}---</option>
                                    <option
                                        value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{translate('order_wise')}} </option>
                                    <option
                                        value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{translate('category_wise')}}</option>
                                    <option
                                        value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{translate('product_wise')}}</option>
                                </select>
                                <div class="mt-2" id="product_wise_note">
                                    <p>
                                        <img width="16" class="mt-n1" src="{{asset('/public/assets/back-end/img/danger-info.png')}}" alt="">
                                        <strong>{{translate('note')}}</strong>
                                        : {{translate("please_make_sure_all_the product`s_delivery_charges_are_up_to_date.")}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="d-flex justify-content-end gap-10">
                                <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div id="update_category_shipping_cost">
            @php($categories = App\Model\Category::where(['position' => 0])->get())
            <div class="card mt-3">
                <div class="px-3 pt-4">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('category_wise_shipping_cost')}}
                    </h5>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                            cellspacing="0"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('image')}}</th>
                                <th>{{translate('category_name')}}</th>
                                <th>{{translate('cost_per_product')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form action="{{route('admin.business-settings.category-shipping-cost.store')}}"
                                    method="POST">
                                @csrf
                                @php($sl =0)
                                @foreach ($all_category_shipping_cost as $key=>$item)
                                    @if($item->category)
                                        <tr>
                                            <td>
                                                {{++$sl}}
                                            </td>
                                            <td>
                                                <img class="rounded" width="64"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('storage/app/public/category')}}/{{$item->category['icon']}}">
                                            </td>
                                            <td>
                                                {{$item->category!=null?$item->category->name:translate('not_found')}}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control w-auto" name="ids[]"
                                                        value="{{$item->id}}">
                                                <input type="number" class="form-control w-auto" min="0" step="0.01"
                                                        name="cost[]"
                                                        value="{{\App\CPU\BackEndHelper::usd_to_currency($item->cost)}}">
                                            </td>
                                            <td>
                                                <label class="mx-auto switcher">
                                                    <input type="checkbox" class="status switcher_input"
                                                            name="multiplyQTY[]"
                                                            id=""
                                                            value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="5">
                                        <div class="d-flex flex-wrap justify-content-end gap-10">
                                            <button type="submit"
                                                    class="btn btn--primary px-5">{{translate('save')}}</button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="order_wise_shipping">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('add_order_wise_shipping')}}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.business-settings.shipping-method.add')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="title">{{translate('title')}}</label>
                                            <input type="text" name="title" class="form-control" placeholder="{{translate('title')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="duration">{{translate('duration')}}</label>
                                            <input type="text" name="duration" class="form-control" placeholder="{{translate('ex')}} : {{translate('4_to_6_days')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <label class="title-color d-flex" for="cost">{{translate('cost')}}</label>
                                            <input type="number" min="0" step="0.01" max="1000000" name="cost" class="form-control" placeholder="{{translate('ex')}} :" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-10">
                            <button type="submit" class="btn btn--primary px-5">{{translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="px-3 py-4">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('order_wise_shipping_method')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $shipping_methods->count() }}</span>
                    </h5>
                </div>
                <div class="table-responsive pb-3">
                    <table
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        cellspacing="0"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('title')}}</th>
                            <th>{{translate('duration')}}</th>
                            <th>{{translate('cost')}}</th>
                            <th class="text-center">{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shipping_methods as $k=>$method)
                            <tr>
                                <th>{{$k+1}}</th>
                                <td>{{$method['title']}}</td>
                                <td>
                                    {{$method['duration']}}
                                </td>
                                <td>
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($method['cost']))}}
                                </td>
                                <td>
                                    <form action="{{route('admin.business-settings.shipping-method.status-update')}}" method="post" id="shipping_methods{{$method['id']}}_form" class="shipping_methods_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$method['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input" id="shipping_methods{{$method['id']}}" name="status" value="1" {{ $method['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'shipping_methods{{$method['id']}}','category-status-on.png','category-status-off.png','{{translate('want_to_Turn_ON_This_Shipping_Method')}}','{{translate('want_to_Turn_OFF_This_Shipping_Method')}}',`<p>{{translate('if_you_enable_this_shipping_method_will_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>`,`<p>{{translate('if_you_disable_this_shipping_method_will_not_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-10">
                                        <a class="btn btn-outline--primary btn-sm edit"
                                            title="{{ translate('edit')}}"
                                            href="{{route('admin.business-settings.shipping-method.edit',[$method['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a title="{{translate('delete')}}"
                                            class="btn btn-outline-danger btn-sm delete"
                                            id="{{ $method['id'] }}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function () {
            let shipping_type = '{{$shippingType}}';

            if (shipping_type === 'category_wise') {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();

            } else if (shipping_type === 'order_wise') {
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            } else {
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').hide();
                $('#product_wise_note').show();
            }
        });
    </script>
    <script>
        function shipping_responsibility(val) {
            if (val === 'inhouse_shipping') {
                $("#sellerwise_shipping").prop("checked", false);
                $("#inhouse_shipping").prop("checked", true);
                $("#for_inhouse_deliver").show();
                $("#for_seller_deliver").hide();
            } else {
                $("#inhouse_shipping").prop("checked", false);
                $("#sellerwise_shipping").prop("checked", true);
                $("#for_inhouse_deliver").hide();
                $("#for_seller_deliver").show();
            }
        }

        function shipping_responsibility_modal(e, toggle_id, on_image, off_image, on_title, off_title, on_message, off_message)
        {
            e.preventDefault();
            if ($('#'+toggle_id).is(':checked')) {
                $('#toggle-title').empty().append(on_title);
                $('#toggle-message').empty().append(on_message);
                $('#toggle-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+on_image);
            } else {
                $('#toggle-title').empty().append(off_title);
                $('#toggle-message').empty().append(off_message);
                $('#toggle-image').attr('src', "{{asset('/public/assets/back-end/img/modal')}}/"+off_image);
            }
            $('#toggle-ok-button').attr('toggle-ok-button', toggle_id);
            $('#toggle-ok-button').attr('onclick', 'shipping_responsibility_modalConfirmToggle()');
            $('#toggle-modal').modal('show');
        }

        function shipping_responsibility_modalConfirmToggle() {
            var toggle_id = $('#toggle-ok-button').attr('toggle-ok-button');

            if ($('#'+toggle_id).is(':checked') && toggle_id === 'inhouse_shipping') {
                shipping_responsibility('sellerwise_shipping');
            } else if($('#'+toggle_id).is(':checked') && toggle_id === 'sellerwise_shipping'){
                shipping_responsibility('inhouse_shipping');
            } else if($('#'+toggle_id).not(':checked') && toggle_id === 'inhouse_shipping'){
                shipping_responsibility('inhouse_shipping');
            } else if($('#'+toggle_id).not(':checked') && toggle_id === 'sellerwise_shipping'){
                shipping_responsibility('sellerwise_shipping');
            }

            $('#toggle-modal').modal('hide');
        }
    </script>
    <script>
        function shipping_type(val) {
            console.log(val);
            if (val === 'category_wise') {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();
            } else if (val === 'order_wise') {
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            } else {
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').hide();
                $('#product_wise_note').show();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.shipping-type.store')}}",
                method: 'POST',
                data: {
                    shippingType: val
                },
                success: function (data) {
                    toastr.success("{{translate('shipping_method_updated_successfully')}}!!");
                }
            });
        }
    </script>
    <script>
        $('.shipping_methods_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success("{{translate('order_wise_shipping_method_Status_updated_successfully')}}");
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{translate("are_you_sure_delete_this")}} ?',
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate("yes_delete_it")}}!',
                cancelButtonText: '{{ translate("cancel") }}',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.business-settings.shipping-method.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate("Order_Wise_Shipping_Method_deleted_successfully")}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
