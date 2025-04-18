@extends('layouts.front-end.app')

@section('title',translate('my_Wallet_History'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
@endpush

@section('content')

    <!-- Page Title-->
    <div class="page-title-overlap bg-dark pt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
            <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                        <li class="breadcrumb-item"><a class="text-nowrap" href="{{route('home')}}"><i class="czi-home"></i>{{translate('home')}}</a></li>
                        <li class="breadcrumb-item text-nowrap"><a href="#">{{translate('account')}}</a>
                        </li>
                        <li class="breadcrumb-item text-nowrap active" aria-current="page">{{translate('wallet_history')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="order-lg-1 text-center text-lg-{{Session::get('direction') === "rtl" ? 'right pl-lg-4' : 'left pr-lg-4'}}">
                <h1 class="h3 text-light mb-0">{{translate('my_orders')}}</h1>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')
            <!-- Content  -->
            <section class="col-lg-9 col-md-8">
                <!-- Toolbar-->
                <div class="d-flex justify-content-between align-items-center pt-lg-2 pb-4 pb-lg-5 mb-lg-3">
                    <div class="form-inline">
                        <label class="text-light opacity-75 text-nowrap {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} d-none d-lg-block" for="order-sort">{{translate('sort_orders')}}:</label>
                        <select class="form-control custom-select" id="order-sort">
                            <option>{{translate('all')}}</option>
                            <option>{{translate('delivered')}}</option>
                            <option>{{translate('in_Progress')}}</option>
                            <option>{{translate('delayed')}}</option>
                            <option>{{translate('canceled')}}</option>
                        </select>
                    </div><a class="btn btn--primary btn-sm d-none d-lg-inline-block" href="{{route('customer.auth.logout')}}"><i class="czi-sign-out {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>{{translate('sign_out')}}</a>
                </div>
                <!-- Orders list-->
                <div class="table-responsive font-size-md">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>{{translate('trans_ID')}}#</th>
                                <th>{{translate('transaction_Method')}}</th>
                                <th>{{translate('transaction_type')}} </th>
                                <th>{{translate('transaction_Amount')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wallerHistory as $wallerHistories)
                            <tr>
                                <td class="py-3"><a class="nav-link-style font-weight-medium font-size-sm" href="#order-details" data-toggle="modal">{{$wallerHistories['transaction_id']}}</a></td>
                                <td class="py-3">{{$wallerHistories['transaction_type']}}</td>
                                <td class="py-3"><span class="badge badge-info m-0">{{$wallerHistories['transaction_method']}}</span></td>
                                <td class="py-3">{{$wallerHistories['transaction_amount']}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                </div>
                <hr class="pb-4">
                <!-- Pagination-->
                <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#"><i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right ml-2' : 'left mr-2'}}"></i>{{translate('prev')}}</a></li>
                    </ul>
                    <ul class="pagination">
                        <li class="page-item d-sm-none"><span class="page-link page-link-static">1 / 5</span></li>
                        <li class="page-item active d-none d-sm-block" aria-current="page"><span class="page-link">1<span class="sr-only">({{translate('current')}})</span></span></li>
                        <li class="page-item d-none d-sm-block"><a class="page-link" href="#">2</a></li>
                        <li class="page-item d-none d-sm-block"><a class="page-link" href="#">3</a></li>
                        <li class="page-item d-none d-sm-block"><a class="page-link" href="#">4</a></li>
                        <li class="page-item d-none d-sm-block"><a class="page-link" href="#">5</a></li>
                    </ul>
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#" aria-label="Next">{{translate('next')}}<i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-2' : 'right ml-2'}}"></i></a></li>
                    </ul>
                </nav>
            </section>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>
@endpush
