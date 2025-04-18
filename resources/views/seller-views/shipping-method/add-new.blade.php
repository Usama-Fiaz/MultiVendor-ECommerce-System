@extends('layouts.back-end.app-seller')

@section('title', translate('add_Shipping'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/shipping_method.png')}}" alt="">
                {{translate('shipping_method')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                            <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                            {{translate('shipping')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-capitalize" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <select class="form-control text-capitalize" name="shippingCategory" onchange="seller_shipping_type(this.value);">
                                    <option value="0" selected disabled>---{{translate('select')}}---</option>
                                    <option value="order_wise" {{$shippingType=='order_wise'?'selected':'' }} >{{translate('order_wise')}} </option>
                                    <option  value="category_wise" {{$shippingType=='category_wise'?'selected':'' }} >{{translate('category_wise')}}</option>
                                    <option  value="product_wise" {{$shippingType=='product_wise'?'selected':'' }}>{{translate('product_wise')}}</option>
                                </select>
                            </div>
                            <div class="mt-2 mx-3" id="product_wise_note">
                                <p>
                                    <img width="16" class="mt-n1" src="{{asset('/public/assets/back-end/img/danger-info.png')}}" alt="">
                                    <strong>{{translate('note')}}</strong>
                                    : {{translate("please_make_sure_all_the product`s_delivery_charges_are_up_to_date.")}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div id="order_wise_shipping">
            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('add_order_wise_shipping')}}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.business-settings.shipping-method.add')}}" method="post">
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
                                            <input type="number" min="0" step="0.01" max="1000000" name="cost" class="form-control" placeholder="{{translate('ex')}} :  {{\App\CPU\Helpers::currency_converter("10")}}" required>
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

            <div class="card mt-2">
                <div class="px-3 py-4">
                    <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                        {{translate('order_wise_shipping_method')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $shipping_methods->count() }}</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
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
                                <th>{{$shipping_methods->firstItem()+$k}}</th>
                                <td>{{$method['title']}}</td>
                                <td>
                                    {{$method['duration']}}
                                </td>
                                <td>
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($method['cost']))}}
                                </td>

                                <td>
                                    <form action="{{route('seller.business-settings.shipping-method.status-update')}}" method="post" id="shipping_methods{{$method['id']}}_form" class="shipping_methods_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$method['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input" id="shipping_methods{{$method['id']}}" name="status" value="1" {{ $method['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'shipping_methods{{$method['id']}}','category-status-on.png','category-status-off.png','{{translate('want_to_Turn_ON_This_Shipping_Method')}}','{{translate('want_to_Turn_OFF_This_Shipping_Method')}}',`<p>{{translate('if_you_enable_this_shipping_method_will_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>`,`<p>{{translate('if_you_disable_this_shipping_method_will_not_be_shown_in_the_user_app_and_website_for_customer_checkout')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a  class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{translate('edit')}}"
                                            href="{{route('seller.business-settings.shipping-method.edit',[$method['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a  class="btn btn-outline-danger btn-sm delete"
                                            title="{{translate('delete')}}"
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

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {!! $shipping_methods->links() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-2" id="update_category_shipping_cost">
            <div class="px-3 pt-4">
                <h5 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/delivery.png')}}" alt="">
                    {{translate('category_wise_shipping_cost')}}
                </h5>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table" cellspacing="0"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('category_name')}}</th>
                                <th>{{translate('cost_per_product')}}</th>
                                <th class="text-center">{{translate('multiply_with_QTY')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="{{route('seller.business-settings.category-shipping-cost.store')}}" method="POST">
                                @csrf
                                @foreach ($all_category_shipping_cost as $key=>$item)
                                    @if($item->category)
                                        <tr>
                                            <td>
                                                {{$key+1}}
                                            </td>
                                            <td>
                                                {{$item->category!=null?$item->category->name:translate('not_found')}}
                                            </td>
                                            <td>
                                                <input type="hidden" class="form-control w-auto" name="ids[]" value="{{$item->id}}">
                                                <input type="number" class="form-control w-auto" min="0" step="0.01" name="cost[]" value="{{\App\CPU\BackEndHelper::usd_to_currency($item->cost)}}">
                                            </td>
                                            <td>
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" name="multiplyQTY[]" class="switcher_input"
                                                        id="" value="{{$item->id}}" {{$item->multiply_qty == 1?'checked':''}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="4">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary ">{{translate('save')}}</button>
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
@endsection

@push('script')
<script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
            let shipping_type = '{{$shippingType}}';

            if(shipping_type==='category_wise')
            {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();

            }else if(shipping_type==='order_wise'){
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            }else{

                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').hide();
                $('#product_wise_note').show();
            }
        });

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
                text: "{{translate('you_wont_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate("yes_delete_it")}}',
                cancelButtonText: '{{ translate("cancel") }}',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.business-settings.shipping-method.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate("shipping_Method_deleted_successfully")}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
    <script>
        function seller_shipping_type(val)
        {
            console.log("val");
            if(val==='category_wise')
            {
                $('#product_wise_note').hide();
                $('#order_wise_shipping').hide();
                $('#update_category_shipping_cost').show();
            }else if(val==='order_wise'){
                $('#product_wise_note').hide();
                $('#update_category_shipping_cost').hide();
                $('#order_wise_shipping').show();
            }else{
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
                    url: "{{route('seller.business-settings.shipping-type.store')}}",
                    method: 'POST',
                    data: {
                        shippingType: val
                    },
                    success: function (data) {
                        toastr.success('{{translate("shipping_method_updated_successfully")}}!!');
                    }
                });
        }
    </script>
@endpush
