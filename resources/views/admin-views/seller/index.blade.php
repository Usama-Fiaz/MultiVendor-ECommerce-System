@extends('layouts.back-end.app')

@section('title', translate('seller_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('seller_List')}}
                <span class="badge badge-soft-dark radius-50 fz-12">{{ $sellers->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center">
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{translate('search_by_Name_or_Phone_or_Email')}}" aria-label="Search orders" value="{{ $search }}">
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <div class="dropdown text-nowrap">
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a type="submit" class="dropdown-item d-flex align-items-center gap-2 " href="{{route('admin.sellers.export',['search' => $search])}}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{route('admin.sellers.seller-add')}}" type="button" class="btn btn--primary text-nowrap">
                                    <i class="tio-add"></i>
                                    {{translate('add_new_seller')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('shop_name')}}</th>
                                <th>{{translate('seller_name')}}</th>
                                <th>{{translate('contact_info')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('total_products')}}</th>
                                <th class="text-center">{{translate('total_orders')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sellers as $key=>$seller)
                                <tr>
                                    <td>{{$sellers->firstItem()+$key}}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-10 w-max-content">
                                            <img width="50"
                                            class="avatar rounded-circle"
                                                onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'"
                                                src="{{asset('storage/app/public/shop')}}/{{$seller->shop->image}}"
                                                alt="">
                                            <div>
                                                <a class="title-color" href="{{ route('admin.sellers.view', ['id' => $seller->id]) }}">{{ \Str::limit($seller->shop->name, 20)}}</a>
                                                <br>
                                                <span class="text-danger">
                                                    @if($seller->shop->temporary_close)
                                                        {{ translate('temporary_closed') }}
                                                    @elseif($seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date)))
                                                        {{ translate('on_vacation') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a title="{{translate('view')}}"
                                           class="title-color"
                                           href="{{route('admin.sellers.view',$seller->id)}}">
                                            {{$seller->f_name}} {{$seller->l_name}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <strong><a class="title-color hover-c1" href="mailto:{{$seller->email}}">{{$seller->email}}</a></strong>
                                        </div>
                                        <a class="title-color hover-c1" href="tel:{{$seller->phone}}">{{$seller->phone}}</a>
                                    </td>
                                    <td>
                                        {!! $seller->status=='approved'?'<label class="badge badge-success">'.translate('active').'</label>':'<label class="badge badge-danger">'.translate('inactive').'</label>' !!}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.sellers.product-list',[$seller['id']])}}"
                                           class="btn text--primary bg-soft--primary font-weight-bold px-3 py-1 mb-0 fz-12">
                                            {{$seller->product->count()}}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.sellers.order-list',[$seller['id']])}}"
                                            class="btn text-info bg-soft-info font-weight-bold px-3 py-1 fz-12 mb-0">
                                            {{$seller->orders->where('seller_is','seller')->where('order_type','default_type')->count()}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a title="{{translate('view')}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                href="{{route('admin.sellers.view',$seller->id)}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {!! $sellers->links() !!}
                        </div>
                    </div>

                    @if(count($sellers)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{translate('no_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
