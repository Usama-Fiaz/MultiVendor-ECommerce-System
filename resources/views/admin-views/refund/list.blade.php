@extends('layouts.back-end.app')

@section('title',translate('reund_requests'))

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/refund-request.png')}}" alt="">
            {{translate('pending_Refund_Requests')}}
            <span class="badge badge-soft-dark radius-50">{{$refund_list->total()}}</span>
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Card -->
    <div class="card">
        <!-- Header -->
        <div class="p-3">
            <div class="row justify-content-between align-items-center">
                <div class="col-12 col-md-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{translate('search_by_order_id_or_refund_id')}}" aria-label="Search orders" value="{{ $search }}">
                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
                <div class="col-12 mt-3 col-md-8">

                    <div class="d-flex gap-3 justify-content-md-end">
                        <div class="dropdown text-nowrap">
                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.refund-section.refund.export',['status'=>request('status'),'search'=>$search])}}">
                                        <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <select name="" id="" class="form-control w-auto" onchange="filter_order(this.value)">
                            <option value="all" {{ session()->has('show_inhouse_and_seller_orders') && session('show_inhouse_and_seller_orders') == 1 ?'selected':''}}>{{translate('all')}}</option>
                            <option value="inhouse" {{session()->has('show_inhouse_orders') && session('show_inhouse_orders')==1?'selected':''}}>{{translate('inhouse_Requests')}}</option>
                            <option value="seller" {{session()->has('show_seller_orders') && session('show_seller_orders') == 1?'selected':''}}>{{translate('seller_Requests')}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('order_id')}} </th>
                        <th>{{translate('product_info')}}</th>
                        <th>{{translate('customer_info')}}</th>
                        <th class="text-end">{{translate('total_amount')}}</th>
                        <th>{{translate('refund_status')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($refund_list as $key=>$refund)
                    <tr>
                        <td>{{$refund_list->firstItem()+$key}}</td>
                        <td>
                            <a href="{{route('admin.orders.details',['id'=>$refund->order_id])}}" class="title-color hover-c1">
                                {{$refund->order_id}}
                            </a>
                        </td>
                        <td>
                            @if ($refund->product!=null)
{{--                                @dd($refund)--}}
{{--                                @dd($refund->product->thumbnail )--}}
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{route('admin.product.view',[$refund->product->id])}}">
                                        <img onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'"
                                            src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{ $refund->product->thumbnail }}"
                                             class="avatar border" alt="">
                                    </a>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{route('admin.product.view',[$refund->product->id])}}" class="title-color font-weight-bold hover-c1">
                                            {{\Illuminate\Support\Str::limit($refund->product->name,35)}}
                                        </a>
                                        <span class="fz-12">{{translate('QTY')}} : {{ $refund->order_details->qty }}</span>
                                    </div>
                                </div>
                            @else
                                {{translate('product_name_not_found')}}
                            @endif

                        </td>
                        <td>
                            @if ($refund->customer !=null)
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{route('admin.customer.view',[$refund->customer->id])}}" class="title-color font-weight-bold hover-c1">
                                        {{$refund->customer->f_name. ' '.$refund->customer->l_name}}
                                    </a>
                                    <a href="tel:{{$refund->customer->phone}}" class="title-color hover-c1 fz-12">{{$refund->customer->phone}}</a>
                                </div>
                            @else
                                <a href="#" class="title-color hover-c1">
                                    {{translate('customer_not_found')}}
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 text-end">
                                <div>{{\App\CPU\Helpers::currency_converter($refund->amount)}}</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex flex-column gap-1">
                                @if($refund->status=='pending')
                                    <span class="badge badge-soft--primary">{{translate($refund->status)}}</span>
                                @elseif($refund->status=='approved')
                                    <span class="badge badge-soft-success">{{translate($refund->status)}}</span>
                                @elseif($refund->status=='rejected')
                                    <span class="badge badge-soft-danger">{{translate($refund->status)}}</span>
                                @else
                                    <span class="badge badge-soft-warning">{{translate($refund->status)}}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a  class="btn btn-outline--primary btn-sm"
                                    title="{{translate('view')}}"
                                    href="{{route('admin.refund-section.refund.details',['id'=>$refund['id']])}}">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <div class="table-responsive mt-4">
            <div class="px-4 d-flex justify-content-lg-end">
                <!-- Pagination -->
                {!! $refund_list->links() !!}
            </div>
        </div>

        @if(count($refund_list)==0)
            <div class="text-center p-4">
                <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                <p class="mb-0">{{ translate('no_data_to_show')}}</p>
            </div>
        @endif
        <!-- End Footer -->
    </div>
    <!-- End Card -->
</div>
@endsection

@push('script_2')
    <script>
        function filter_order(type = null) {
            $.get({
                url: '{{route('admin.refund-section.refund.inhouse-order-filter')}}'+ (type ? '?type='+type : ''),
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
@endpush
