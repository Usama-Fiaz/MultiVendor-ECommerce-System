@extends('layouts.back-end.app')

@section('title', translate('order_Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/order_report.png')}}" alt="">
                {{translate('order_Report')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="row gx-2 gy-3 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control text-ellipsis" name="seller_id">
                                <option value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                <option value="inhouse" {{ $seller_id == 'inhouse' ? 'selected' : '' }}>{{translate('in-House')}}</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller['id'] }}" {{ $seller_id == $seller['id'] ? 'selected' : '' }}>
                                        {{$seller['f_name']}} {{$seller['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                <label>{{ ucwords(translate('start_date'))}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                <label>{{ ucwords(translate('end_date'))}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 filter-btn">
                            <button type="submit" class="btn btn--primary px-4 px-md-5">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/cart.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">{{ $order_count['total_order'] }}</h4>
                        <h6 class="subtext">{{translate('total_Orders')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $order_count['canceled_order'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('canceled')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('this_count_is_the_summation_of')}} {{translate('failed_to_deliver')}}, {{translate('canceled')}}, {{translate('and')}} {{translate('returned_orders')}}">
                                    <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $order_count['ongoing_order'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('ongoing')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('this_count_is_the_summation_of')}} {{translate('pending')}}, {{translate('confirmed')}}, {{translate('packaging')}}, {{translate('out_for_delivery_orders')}}">
                                    <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $order_count['delivered_order'] }}</strong>
                            <div class="d-flex">
                                <span>{{translate('completed')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('this_count_is_the_summation_of_delivered_orders')}}">
                                    <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/products.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($due_amount+$settled_amount)) }}
                        </h4>
                        <h6 class="subtext">{{translate('total_Order_Amount')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($due_amount)) }}
                            </strong>
                            <div class="d-flex">
                                <span>{{translate('due_Amount')}}</span>
                                <span class="trx-y-2 ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('the_ongoing_order_amount_will_be_shown_here')}}">
                                    <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($settled_amount)) }}
                            </strong>
                            <div class="d-flex">
                                <span>{{translate('already_Settled')}}</span>
                                <span class="trx-y-2 ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('after_the_order_is_delivered_total_order_amount_will_be_shown_here')}}">
                                    <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach(array_values($chart_data['order_amount']) as $amount)
                @php($chart_val[] = \App\CPU\BackEndHelper::usd_to_currency($amount))
            @endforeach
            <div class="center-chart-area">
                <div class="center-chart-header">
                    <h3 class="title">{{translate('order_Statistics')}}</h3>
                </div>
                <canvas id="updatingData" class="store-center-chart style-2"
                        data-hs-chartjs-options='{
                "type": "bar",
                "data": {
                  "labels": [{{ '"'.implode('","', array_keys($chart_data['order_amount'])).'"' }}],
                  "datasets": [{
                    "label": "{{translate('total_settled_amount')}}",
                    "data": [{{ '"'.implode('","', array_values($chart_val)).'"' }}],
                    "backgroundColor": "#a2ceee",
                    "hoverBackgroundColor": "#0177cd",
                    "borderColor": "#a2ceee"
                  }]
                },
                "options": {
                  "scales": {
                    "yAxes": [{
                      "gridLines": {
                        "color": "#e7eaf3",
                        "drawBorder": false,
                        "zeroLineColor": "#e7eaf3"
                      },
                      "ticks": {
                        "beginAtZero": true,
                        "fontSize": 12,
                        "fontColor": "#97a4af",
                        "fontFamily": "Open Sans, sans-serif",
                        "padding": 5,
                        "postfix": " {{ \App\CPU\BackEndHelper::currency_symbol() }}"
                      }
                    }],
                    "xAxes": [{
                      "gridLines": {
                        "display": false,
                        "drawBorder": false
                      },
                      "ticks": {
                        "fontSize": 12,
                        "fontColor": "#97a4af",
                        "fontFamily": "Open Sans, sans-serif",
                        "padding": 5
                      },
                      "categoryPercentage": 0.3,
                      "maxBarThickness": "10"
                    }]
                  },
                  "cornerRadius": 5,
                  "tooltips": {
                    "prefix": " ",
                    "hasIndicator": true,
                    "mode": "index",
                    "intersect": false
                  },
                  "hover": {
                    "mode": "nearest",
                    "intersect": true
                  }
                }
              }'>
                </canvas>
            </div>
            <div class="right-content">
                <!-- Dognut Pie -->
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-header border-0 ">
                        <h5 class="card-title">
                            <span>{{translate('payment_Statistics')}}</span>
                        </h5>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="position-relative pie-chart">
                            <div id="dognut-pie"  class="label-hide"></div>
                            <!-- Total Orders -->
                            <div class="total--orders">
                                <h3 class="mb-1">
                                    {{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['total_payment'])) }}
                                </h3>
                                <span>{{translate('completed')}} <br> {{translate('payments')}}</span>
                            </div>
                            <!-- Total Orders -->
                        </div>
                        <div class="apex-legends">
                            <div class="before-bg-004188">
                                <span>{{translate('cash_Payments')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['cash_payment'])) }})</span>
                            </div>
                            <div class="before-bg-0177CD">
                                <span>{{translate('digital_Payments')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['digital_payment'])) }})</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="before-bg-A2CEEE">
                                <span>{{translate('wallet')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['wallet_payment'])) }})</span>
                            </div>
                            <div class="before-bg-CDE6F5">
                                <span>{{translate('offline_payments')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['offline_payment'])) }})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dognut Pie -->
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Orders')}}
                        <span class="badge badge-soft-dark radius-50 fz-14">{{ $orders->total() }}</span>
                    </h4>
                    <form action="" method="GET" class="mb-0">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                            <input type="hidden" value="{{ $date_type }}" name="date_type">
                            <input type="hidden" value="{{ $from }}" name="from">
                            <input type="hidden" value="{{ $to }}" name="to">
                            <input id="datatableSearch_" value="{{ $search }}" type="search" name="search" class="form-control" placeholder="{{ translate('search_by_order_id')}}" aria-label="Search orders" required>
                            <button type="submit" class="btn btn--primary">{{ translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.report.order-report-excel', ['date_type'=>request('date_type'), 'seller_id'=>request('seller_id'), 'from'=>request('from'), 'to'=>request('to'), 'search'=>request('search')]) }}">
                                    <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                    {{translate('excel')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatable"
                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                       class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('order_ID')}}</th>
                        <th>{{translate('total_Amount')}}</th>
                        <th>{{translate('product_Discount')}}</th>
                        <th>{{translate('coupon_Discount')}}</th>
                        <th>{{translate('shipping_Charge')}}</th>
                        <th>{{translate('VAT/TAX')}}</th>
                        <th>{{translate('commission')}}</th>
                        <th class="text-center">{{translate('status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $key=>$order)
                        <tr>
                            <td>{{ $orders->firstItem()+$key }}</td>
                            <td>
                                <a class="title-color" href="{{route('admin.orders.details',['id'=>$order->id])}}">{{$order->id}}</a>
                            </td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->details_sum_discount)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->shipping_cost - ($order->extra_discount_type == 'free_shipping_over_order_amount' ? $order->extra_discount : 0))) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->details_sum_tax)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->admin_commission)) }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    @if($order['order_status']=='pending')
                                        <span class="badge badge-soft-info fz-12">
                                            {{translate($order['order_status'])}}
                                        </span>
                                    @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                        <span class="badge badge-soft-warning fz-12">
                                            {{str_replace('_',' ',($order['order_status'] == 'processing') ? translate('packaging'):translate($order['order_status']))}}
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
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($orders->total()==0)
                        <tr>
                            <td colspan="9">
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                         alt="Image Description">
                                    <p class="mb-0">{{ translate('no_data_to_found')}}</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-responsive mt-4">
            <div class="px-4 d-flex justify-content-center justify-content-md-end">
                <!-- Pagination -->
                {!! $orders->links() !!}
            </div>
        </div>
    </div>
@endsection

@push('script_2')

    <!-- Chart JS -->
    <script src="{{ asset('public/assets/back-end') }}/js/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <!-- Chart JS -->
    <!-- Apex Charts -->
    <script src="{{ asset('/public/assets/back-end/js/apexcharts.js') }}"></script>
    <!-- Apex Charts -->

    <!-- Dognut Pie Chart -->
    <script>
        var options = {
            series: [
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['cash_payment']) }},
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['digital_payment']) }},
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['wallet_payment']) }},
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['offline_payment']) }}
            ],
            chart: {
                width: 320,
                type: 'donut',
            },
            labels: [
                '{{translate('cash_Payments')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['cash_payment'])) }})',
                '{{translate('digital_Payments')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['digital_payment'])) }})',
                '{{translate('wallet_Payments')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['wallet_payment'])) }})',
                '{{translate('offline_Payments')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['offline_payment'])) }})',
            ],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#004188', '#004188', '#004188', '#7b94a4']
                }
            },
            responsive: [{
                breakpoint: 1650,
                options: {
                    chart: {
                        width: 260
                    },
                }
            }],
            colors: ['#004188', '#0177CD', '#0177CD', '#7b94a4'],
            fill: {
                colors: ['#004188', '#A2CEEE', '#0177CD', '#7b94a4']
            },
            legend: {
                show: false
            },
        };

        var chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();
    </script>
    <!-- Dognut Pie Chart -->

    <script>

        // Bar Charts
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ url('/') }}/admin/store/get-stores',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        // all:true,
                        @if (isset($zone))
                        zone_ids: [{{ $zone->id }}],
                        @endif
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

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
                    toastr.error('Invalid date range!', Error, {
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

    </script>

@endpush
