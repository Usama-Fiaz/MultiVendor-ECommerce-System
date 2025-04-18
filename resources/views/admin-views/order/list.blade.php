@extends('layouts.back-end.app')

@section('title', translate('order_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div>
            <!-- Page Title -->
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                    <span class="page-header-title">
                        @if($status =='processing')
                            {{translate('packaging')}}
                        @elseif($status =='failed')
                            {{translate('failed_to_Deliver')}}
                        @elseif($status == 'all')
                            {{translate('all')}}
                        @else
                            {{translate(str_replace('_',' ',$status))}}
                        @endif
                    </span>
                    {{translate('orders')}}
                </h2>
                <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
            </div>
            <!-- End Page Title -->

            <!-- Order States -->
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.orders.list',['status'=>request('status')])}}" id="form-data" method="GET">
                        <div class="row gx-2">
                            <div class="col-12">
                                <h4 class="mb-3 text-capitalize">{{translate('filter_order')}}</h4>
                            </div>
                            @if(request('delivery_man_id'))
                                <input type="hidden" name="delivery_man_id" value="{{ request('delivery_man_id') }}">
                            @endif

                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color text-capitalize" for="filter">{{translate('order_type')}}</label>
                                    <select name="filter" id="filter" class="form-control">
                                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                        <option value="admin" {{ $filter == 'admin' ? 'selected' : '' }}>{{translate('in_House_Order')}}</option>
                                        <option value="seller" {{ $filter == 'seller' ? 'selected' : '' }}>{{translate('seller_Order')}}</option>
                                        @if(($status == 'all' || $status == 'delivered') && !request()->has('delivery_man_id'))
                                        <option value="POS" {{ $filter == 'POS' ? 'selected' : '' }}>{{translate('POS_Order')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3" id="seller_id_area" style="{{ $filter && $filter == 'admin'?'display:none':'' }}">
                                <div class="form-group">
                                    <label class="title-color" for="store">{{translate('store')}}</label>
                                    <select name="seller_id" id="seller_id" class="form-control">
                                        <option value="all">{{translate('all_shop')}}</option>
                                        <option value="0" id="seller_id_inhouse">{{translate('inhouse')}}</option>
                                        @foreach ($sellers as $seller)
                                            @isset($seller->shop)
                                                <option value="{{$seller->id}}"{{request('seller_id') == $seller->id ? 'selected' :''}}>
                                                    {{ $seller->shop->name }}
                                                </option>
                                            @endisset
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color" for="customer">{{translate('customer')}}</label>

                                    <input type="hidden" id='customer_id'  name="customer_id" value="{{request('customer_id') ? request('customer_id') : 'all'}}">
                                    <select  onchange="customer_id_value(this.value)"
                                    data-placeholder="@if($customer == 'all')
                                                        {{translate('all_customer')}}
                                                    @else
                                                        {{$customer->name ? $customer->name : $customer->f_name.' '.$customer->l_name.' '.'('.$customer->phone.')'}}
                                                    @endif"
                                    class="js-data-example-ajax form-control form-ellipsis">
                                        <option value="all">{{translate('all_customer')}}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- week, month, year -->

                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <label class="title-color" for="date_type">{{translate('date_type')}}</label>
                                <div class="form-group">
                                    <select class="form-control __form-control" name="date_type" id="date_type">
                                        <option value="" selected disabled>{{translate('select_Date_Type')}}</option>
                                        <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                        <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                        <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                        <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3" id="from_div">
                                <label class="title-color" for="customer">{{translate('start_date')}}</label>
                                <div class="form-group">
                                    <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3" id="to_div">
                                <label class="title-color" for="customer">{{translate('end_date')}}</label>
                                <div class="form-group">
                                    <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                </div>
                            </div>
                             <!-- End week, month, year -->
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('admin.orders.list',['status'=>request('status')])}}" class="btn btn-secondary px-5">
                                        {{translate('reset')}}
                                    </a>
                                    <button type="submit" class="btn btn--primary px-5" onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                        {{translate('show_data')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <!-- Data Table Top -->
                    <div class="px-3 py-4 light-bg">
                        <div class="row g-2 align-items-center flex-grow-1">
                            <div class="col-md-4">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{translate('order_list')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{$orders->total()}}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                                <form action="" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{translate('search_by_Order_ID')}}" aria-label="Search by Order ID" value="{{ $search }}">
                                        <button type="submit" class="btn btn--primary input-group-text">{{translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.order-bulk-export', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'search' => $search,'seller_id'=>$seller_id,'customer_id'=>$customer_id, 'date_type'=>$date_type]) }}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('order_ID')}}</th>
                                    <th>{{translate('order_Date')}}</th>
                                    <th>{{translate('customer_Info')}}</th>
                                    <th>{{translate('store')}}</th>
                                    <th class="text-right">{{translate('total_Amount')}}</th>
                                    <th class="text-center">{{translate('order_Status')}} </th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $key=>$order)

                                <tr class="status-{{$order['order_status']}} class-all">
                                    <td class="">
                                        {{$orders->firstItem()+$key}}
                                    </td>
                                    <td >
                                        <a class="title-color" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                    </td>
                                    <td>
                                        <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                                        <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                                    </td>
                                    <td>
                                        @if($order->is_guest)
                                            <strong class="title-name">{{translate('guest_customer')}}</strong>
                                        @elseif($order->customer_id == 0)
                                            <strong class="title-name">{{translate('walking_customer')}}</strong>
                                        @else
                                            @if($order->customer)
                                                <a class="text-body text-capitalize" href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                    <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                </a>
                                                <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                            @else
                                                <label class="badge badge-danger fz-12">{{translate('invalid_customer_data')}}</label>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <span class="store-name font-weight-medium">
                                            @if($order->seller_is == 'seller')
                                                {{ isset($order->seller->shop) ? $order->seller->shop->name : 'Store not found' }}
                                            @elseif($order->seller_is == 'admin')
                                                {{translate('in_House')}}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div>
                                            @php($discount = 0)
                                            @if($order->order_type == 'default_type' && $order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                                @php($discount = $order->discount_amount)
                                            @endif

                                            @php($free_shipping = 0)
                                            @if($order->is_shipping_free)
                                                @php($free_shipping = $order->shipping_cost)
                                            @endif

                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount+$discount+$free_shipping))}}
                                        </div>

                                        @if($order->payment_status=='paid')
                                            <span class="badge text-success fz-12 px-0">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="badge text-danger fz-12 px-0">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center text-capitalize">
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>

                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning fz-12">
                                                {{str_replace('_',' ',$order['order_status'] == 'processing' ? translate('packaging'):translate($order['order_status']))}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger fz-12">
                                                {{translate('failed_to_deliver')}}
                                            </span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-success fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger fz-12">
                                                {{translate($order['order_status'])}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{translate('view')}}"
                                                href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                <img src="{{asset('/public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </a>
                                            <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank" title="{{translate('invoice')}}"
                                                href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                                <i class="tio-download-to"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    <!-- End Pagination -->
                </div>
            </div>
            <!-- End Order States -->

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{translate('order_list')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->
    </div>
@endsection

@push('script_2')
    <script>
        function customer_id_value(id){
            $('#customer_id').empty().val(id);
        }
        $('.js-data-example-ajax').select2({
        // Need Add a initial option
        data: [{ id: '', text: 'Select your option', disabled: true, selected: true }],
        ajax: {
            url: '{{route('admin.orders.customers')}}',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                // console.log(data);
                return {
                results: data

                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }

        }
    });
    $(document).ready(function() {
        $('.select2-container--default').addClass('form-control');
        $('.select2-container--default').addClass('p-0');
        $('.select2-selection').addClass('border-0');
    });

    </script>
    <script>
        function filter_order() {
            $.get({
                url: '{{route('admin.orders.inhouse-order-filter')}}',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                    toastr.success('{{translate("order_filter_success")}}');
                    location.reload();
                },
                complete: function () {
                    $('#loading').fadeOut();
                },
            });
        };
    </script>
    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if(fr != ''){
                $('#to_date').attr('required','required');
            }
            if(to != ''){
                $('#from_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{translate("invalid_date_range")}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

        $("#date_type").change(function() {
            let val = $(this).val();
            $('#from_div').toggle(val === 'custom_date');
            $('#to_div').toggle(val === 'custom_date');

            if(val === 'custom_date'){
                $('#from_date').attr('required','required');
                $('#to_date').attr('required','required');
                $('.filter-btn').attr('class','filter-btn col-12 text-right');
            }else{
                $('#from_date').val(null).removeAttr('required')
                $('#to_date').val(null).removeAttr('required')
                $('.filter-btn').attr('class','col-sm-6 col-md-3 filter-btn');
            }
        }).change();

        $("#filter").change(function() {
            let val = $(this).val();
            if(val === 'admin'){
                $('#seller_id_area').fadeOut();
            }else{
                $('#seller_id_area').fadeIn();
                $('#seller_id').val('all');
            }
            if(val === 'seller'){
                $('#seller_id_inhouse').fadeOut();
            }else{
                $('#seller_id_inhouse').fadeIn();
            }
        });
    </script>
@endpush
