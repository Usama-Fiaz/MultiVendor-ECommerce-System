@extends('layouts.back-end.app-seller')
@section('title', translate('expense_Transactions'))
@section('content')
    <div class="content container-fluid ">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/order_report.png')}}" alt="">
                {{translate('transaction_report')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('seller-views.transaction.transaction-report-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card mb-2">
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="row  gy-2 align-items-center text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_year')}}</option>
                                <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_month')}}</option>
                                <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_week')}}</option>
                                <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control __form-control">
                                <label>{{translate('start_Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control __form-control">
                                <label>{{translate('end_Date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn--primary px-4 w-100" onclick="formUrlChange(this)"
                                    data-action="{{ url()->current() }}">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content expense--content">
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/expense.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_expense)) }}</h4>
                        <h6 class="subtext">
                            <span>{{translate('total_Expense')}}</span>
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('free_delivery')}}, {{translate('coupon_discount_will_be_shown_here')}}">
                                <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/free-delivery.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($free_delivery)) }}</h4>
                        <h6 class="subtext">{{translate('free_Delivery')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/public/assets/back-end/img/coupon-discount.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount)) }}</h4>
                        <h6 class="subtext">
                            <span>{{translate('coupon_Discount')}}</span>
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('discount_on_purchase_and_first_delivery_coupon_amount_will_be_shown_here')}}">
                                <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="center-chart-area">
                <div class="center-chart-header">
                    <h3 class="title">{{translate('expense_Statistics')}}</h3>
                </div>
                <canvas id="updatingData" class="store-center-chart"
                        data-hs-chartjs-options='{
                "type": "bar",
                "data": {
                  "labels": [{{ '"'.implode('","', array_keys($expense_transaction_chart['discount_amount'])).'"' }}],
                  "datasets": [{
                    "label": "{{translate('total_expense_amount')}}",
                    "data": [ {{ '"'.implode('","', array_values($expense_transaction_chart['discount_amount'])).'"' }}],
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
                        "postfix": " $"
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
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{translate('total_Transactions')}}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $expense_transactions_table->total() }}</span>
                    </h4>
                    <form action="" method="GET" class="mb-0">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{ translate('search_by_Order_ID_or_Transaction_ID')}}"
                                   aria-label="Search orders"
                                   value="{{ $search }}"
                                   required>
                            <button type="submit"
                                    class="btn btn--primary">{{ translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <div>
                        <a href="{{ route('seller.transaction.expense-transaction-summary-pdf', ['date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to')]) }}" class="btn btn-outline--primary text-nowrap btn-block">
                            <i class="tio-file-text"></i>
                            {{translate('download_PDF')}}
                        </a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{ route('seller.transaction.expense-transaction-export-excel', ['date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to'), 'search'=>request('search')]) }}"  >{{translate('excel')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                           style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                           class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('XID')}}</th>
                            <th>{{translate('transaction_Date')}}</th>
                            <th>{{translate('order_ID')}}</th>
                            <th>{{translate('expense_Amount')}}</th>
                            <th>{{translate('expense_Type')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expense_transactions_table as $key=>$transaction)
                            <tr>
                                <td>{{ $expense_transactions_table->firstItem()+$key }}</td>
                                <td>{{ $transaction->order_transaction->transaction_id }}</td>
                                <td>{{ date_format($transaction->order_transaction->updated_at, 'd F Y, h:i:s a') }}</td>
                                <td>
                                    <a class="title-color" href="{{route('seller.orders.details',['id'=>$transaction->id])}}">
                                        {{$transaction->id}}
                                    </a>
                                </td>
                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(($transaction->coupon_discount_bearer == 'seller'?$transaction->discount_amount:0) + ($transaction->free_delivery_bearer=='seller'?$transaction->extra_discount:0))) }}</td>
                                <td>
                                    {{ $transaction->coupon_discount_bearer == 'seller'?(isset($transaction->coupon->coupon_type) ? ($transaction->coupon->coupon_type == 'free_delivery' ? 'Free Delivery Coupon':ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type))) : ''):'' }}
                                    <br>
                                    {{ $transaction->free_delivery_bearer == 'seller' ? ucwords(str_replace('_', ' ', $transaction->extra_discount_type)):'' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('seller.transaction.pdf-order-wise-expense-transaction', ['id'=>$transaction->id]) }}" class="btn btn-outline-success square-btn btn-sm">
                                            <i class="tio-download-to"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($expense_transactions_table)==0)
                            <tr>
                                <td colspan="7">
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

    </div>
@endsection

@push('script')

    <!-- Chart JS -->
    <script src="{{ asset('public/assets/back-end') }}/js/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/js/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <!-- Chart JS -->

    <script>
        $(document).ready(function () {
            $('.js-select2-custom').select2();
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
                    toastr.error('{{ translate("invalid_date_range") }}!', Error, {
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
@endpush

@push('script_2')
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
