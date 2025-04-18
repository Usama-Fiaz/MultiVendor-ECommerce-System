@extends('layouts.back-end.app')

@section('title', translate('earning_Reports'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/earning_report.png')}}" alt="">
                {{translate('earning_Reports')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.report.earning-report-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{ translate('filter_Data')}}</h4>
                    <div class="row gy-3 gx-2 align-items-center text-left">
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
                                <input type="date" name="from" value="{{ $from }}" id="from_date" class="form-control">
                                <label>{{ translate('start Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{ $to }}" name="to" id="to_date" class="form-control">
                                <label>{{ translate('end Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn--primary px-4 w-100">
                                {{ translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

         <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/stores.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $data['total_seller'] }}</h4>
                        <h6 class="subtext">{{ translate('total_Seller')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/cart.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $data['all_product'] }}</h4>
                        <h6 class="subtext">{{ translate('total_Seller_Products')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $data['rejected_product'] }}</strong>
                            <div>{{ translate('denied')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $data['pending_product'] }}</strong>
                            <div>{{ translate('pending_Request')}}</div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $data['active_product'] }}</strong>
                            <div>{{ translate('approved')}}</div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/total-earning.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_earning)) }}</h4>
                        <h6 class="subtext">{{ translate('total_Earning')}}</h6>
                    </div>
                </div>
            </div>
            <div class="center-chart-area">
                <div class="center-chart-header">
                    <h3 class="title">{{ translate('earning_Statistics') }}</h3>
                    <h5 class="subtitle d-flex">
                        <span>{{ translate('average_Earning_Value') }} :</span>
                        <span>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(array_sum($chart_earning_statistics)/count($chart_earning_statistics))) }}</span>
                    </h5>
                </div>
                <canvas id="updatingData" class="store-center-chart"
                    data-hs-chartjs-options='{
                "type": "bar",
                "data": {
                  "labels": [{{ '"'.implode('","', array_keys($chart_earning_statistics)).'"' }}],
                  "datasets": [{
                     "label": "{{translate('total_Earnings')}}",
                    "data": [{{ '"'.implode('","', array_values($chart_earning_statistics)).'"' }}],
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
                            <span>{{ translate('seller_Wallet_Status')}}</span>
                        </h5>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="position-relative pie-chart">
                            <div id="dognut-pie" class="label-hide"></div>
                            <!-- Total Orders -->
                            <div class="total--orders">
                                <h3>{{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['wallet_amount'])) }}</h3>
                                <span>{{ translate('wallet_Amount')}}</span>
                            </div>
                            <!-- Total Orders -->
                        </div>
                        <div class="apex-legends">
                            <div class="before-bg-004188">
                                <span>{{translate('withdrawble_Balance')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['withdrawable_balance'])) }})</span>
                            </div>
                            <div class="before-bg-0177CD">
                                <span>{{translate('pending_Withdraws')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['pending_withdraw'])) }})</span>
                            </div>
                            <div class="before-bg-A2CEEE">
                                <span>{{translate('already_Withdrawn')}} ({{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($payment_data['already_withdrawn'])) }})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dognut Pie -->
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Seller')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ count($table_earning['seller_earn_table']) }}</span>
                    </h4>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.report.seller-earning-excel-export', ['date_type'=>$date_type, 'from'=>$from, 'to'=>$to]) }}">
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
                        <th>{{translate('seller_Info')}}</th>
                        <th>{{translate('earn_From_Order')}}</th>
                        <th>{{translate('earn_From_Shipping')}}</th>
                        <th>{{translate('commission_Given')}}</th>
                        <th>{{translate('discount_Given')}}</th>
                        <th>{{translate('tax_Collected')}}</th>
                        <th>{{translate('refund_Given')}}</th>
                        <th>{{translate('total_Earning')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php($i=0)
                    @foreach($table_earning['seller_earn_table'] as $key=>$seller_earn)
                        @php($shipping_earn_table = isset($table_earning['shipping_earn_table'][$key]['amount']) ? $table_earning['shipping_earn_table'][$key]['amount'] : 0)
                        @php($commission_given_table = isset($table_earning['commission_given_table'][$key]['amount']) ? $table_earning['commission_given_table'][$key]['amount'] : 0)
                        @php($discount_given_table = isset($table_earning['discount_given_table'][$key]['amount']) ? $table_earning['discount_given_table'][$key]['amount'] : 0)
                        @php($discount_given_bearer_admin_table = isset($table_earning['discount_given_bearer_admin_table'][$key]['amount']) ? $table_earning['discount_given_bearer_admin_table'][$key]['amount'] : 0)
                        @php($total_tax_table = isset($table_earning['total_tax_table'][$key]['amount']) ? $table_earning['total_tax_table'][$key]['amount'] : 0)
                        @php($total_refund_table = isset($table_earning['total_refund_table'][$key]['amount']) ? $table_earning['total_refund_table'][$key]['amount'] : 0)
                        @php($total_earn_from_order=$seller_earn['amount']+$discount_given_table-$total_tax_table)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>
                                <div>
                                    <h6 class="mb-1">
                                        <a class="title-color" href="{{ route('admin.sellers.view', ['id' => $seller_earn['seller_id']]) }}">{{ $seller_earn['name'] }}</a>
                                    </h6>
                                </div>
                            </td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_earn_from_order)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping_earn_table)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($commission_given_table)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($discount_given_table)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax_table)) }}</td>
                            <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_refund_table)) }}</td>
                            <td>
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_earn_from_order+$shipping_earn_table+$total_tax_table-$discount_given_table-$total_refund_table-$commission_given_table)) }}
                            </td>
                        </tr>
                    @endforeach
                    @if(count($table_earning['seller_earn_table'])==0)
                        <tr>
                            <td colspan="9">
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                         alt="Image Description">
                                    <p class="mb-0">{{ translate('no_data_to_show')}}</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('script')

@endpush

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

    <script>

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
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
            }else{
                $('#from_date').val(null).removeAttr('required')
                $('#to_date').val(null).removeAttr('required')
            }
        }).change();
    </script>



    <!-- Dognut Pie Chart -->
    <script>
        var options = {
            series: [
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['withdrawable_balance']) }},
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['pending_withdraw']) }},
                {{ \App\CPU\BackEndHelper::usd_to_currency($payment_data['already_withdrawn']) }},
            ],
            chart: {
                width: 320,
                type: 'donut',
            },
            labels: [
                '{{translate('withdrawble_Balance')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['withdrawable_balance'])) }})',
                '{{translate('pending_Withdraws')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['pending_withdraw'])) }})',
                '{{translate('already_Withdrawn')}} ({{ \App\CPU\BackEndHelper::currency_symbol() }}{{ \App\CPU\BackEndHelper::format_currency(\App\CPU\BackEndHelper::usd_to_currency($payment_data['already_withdrawn'])) }})'
            ],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#004188', '#004188', '#004188']
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
            colors: ['#004188', '#0177CD', '#0177CD'],
            fill: {
                colors: ['#004188', '#A2CEEE', '#0177CD']
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

    </script>


@endpush
