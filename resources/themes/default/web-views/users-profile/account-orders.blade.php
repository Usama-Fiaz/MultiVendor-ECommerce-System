@extends('layouts.front-end.app')

@section('title', translate('my_Order_List'))


@section('content')

    <!-- Page Content-->
    <div class="container py-4 rtl">
        <div class="row">
            <!-- Sidebar-->
            @include('web-views.partials._profile-aside')

            <!-- Content  -->
            <section class="col-lg-9 __customer-profile">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                    <h5 class="mb-0">{{translate('my_order')}}</h5>

                    <button class="profile-aside-btn btn btn--primary px-2 rounded px-2 py-1 d-lg-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.81219C7 9.41419 6.842 9.03269 6.5605 8.75169C6.2795 8.47019 5.898 8.31219 5.5 8.31219C4.507 8.31219 2.993 8.31219 2 8.31219C1.602 8.31219 1.2205 8.47019 0.939499 8.75169C0.657999 9.03269 0.5 9.41419 0.5 9.81219V13.3122C0.5 13.7102 0.657999 14.0917 0.939499 14.3727C1.2205 14.6542 1.602 14.8122 2 14.8122H5.5C5.898 14.8122 6.2795 14.6542 6.5605 14.3727C6.842 14.0917 7 13.7102 7 13.3122V9.81219ZM14.5 9.81219C14.5 9.41419 14.342 9.03269 14.0605 8.75169C13.7795 8.47019 13.398 8.31219 13 8.31219C12.007 8.31219 10.493 8.31219 9.5 8.31219C9.102 8.31219 8.7205 8.47019 8.4395 8.75169C8.158 9.03269 8 9.41419 8 9.81219V13.3122C8 13.7102 8.158 14.0917 8.4395 14.3727C8.7205 14.6542 9.102 14.8122 9.5 14.8122H13C13.398 14.8122 13.7795 14.6542 14.0605 14.3727C14.342 14.0917 14.5 13.7102 14.5 13.3122V9.81219ZM12.3105 7.20869L14.3965 5.12269C14.982 4.53719 14.982 3.58719 14.3965 3.00169L12.3105 0.915687C11.725 0.330188 10.775 0.330188 10.1895 0.915687L8.1035 3.00169C7.518 3.58719 7.518 4.53719 8.1035 5.12269L10.1895 7.20869C10.775 7.79419 11.725 7.79419 12.3105 7.20869ZM7 2.31219C7 1.91419 6.842 1.53269 6.5605 1.25169C6.2795 0.970186 5.898 0.812187 5.5 0.812187C4.507 0.812187 2.993 0.812187 2 0.812187C1.602 0.812187 1.2205 0.970186 0.939499 1.25169C0.657999 1.53269 0.5 1.91419 0.5 2.31219V5.81219C0.5 6.21019 0.657999 6.59169 0.939499 6.87269C1.2205 7.15419 1.602 7.31219 2 7.31219H5.5C5.898 7.31219 6.2795 7.15419 6.5605 6.87269C6.842 6.59169 7 6.21019 7 5.81219V2.31219Z" fill="white"/>
                            </svg>
                    </button>
                </div>
                <div class="card __card d-none d-lg-flex" dir="{{Session::get('direction') === "rtl" ? 'rtl' : 'ltr'}}">
                    <div class="card-body">
                        @if($orders->count()>0)
                        <div class="table-responsive">
                            <table class="table __table __table-2 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <td class="tdBorder">
                                            <div><span
                                                    class="d-block spandHeadO text-start text-capitalize">{{translate('order_list')}}</span></div>
                                        </td>

                                        <td class="tdBorder">
                                            <div><span
                                                    class="d-block spandHeadO"> {{translate('status')}}</span></div>
                                        </td>
                                        <td class="tdBorder">
                                            <div><span
                                                    class="d-block spandHeadO"> {{translate('total')}}</span></div>
                                        </td>
                                        <td class="tdBorder">
                                            <div><span
                                                    class="d-block spandHeadO"> {{translate('action')}}</span></div>
                                        </td>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="bodytr">
                                            <div class="media-order">
                                                <a href="{{ route('account-order-details', ['id'=>$order->id])}}" class="d-block position-relative">
                                                @if($order->seller_is == 'seller')
                                                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                    src="{{ asset('storage/app/public/shop/'.(isset($order->seller->shop) ? $order->seller->shop->image:''))}}" alt="img/products">
                                                @elseif($order->seller_is == 'admin')
                                                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                    src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}" alt="img/products">
                                                @endif
                                                </a>
                                                <div class="cont text-start">
                                                <h6 class="font-weight-bold m-0">
                                                    <a href="{{ route('account-order-details', ['id'=>$order->id])}}">
                                                        {{translate('ID')}}: {{$order['id']}}
                                                    </a>
                                                </h6>
                                                    <span>{{ $order->order_details_sum_qty }} {{translate('items')}}</span>
                                                    <div>{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="bodytr">
                                            @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                <span class="badge badge-danger text-capitalize">
                                                    {{translate($order['order_status'] =='failed' ? 'failed_to_deliver' : $order['order_status'])}}
                                                </span>
                                            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                <span class="badge badge-success text-capitalize">
                                                    {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                </span>
                                            @else
                                                <span class="badge badge-info text-capitalize">
                                                    {{translate($order['order_status'])}}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="bodytr">
                                            <div class="text-dark">
                                                {{\App\CPU\Helpers::currency_converter($order['order_amount'])}}
                                            </div>
                                        </td>
                                        <td class="bodytr">
                                            <div class="__btn-grp-sm flex-nowrap">
                                                <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                                class="btn-outline--info text-base __action-btn btn-shadow rounded-full" title="{{translate('view_order_details')}}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{route('generate-invoice',[$order->id])}}" title="{{translate('download_invoice')}}"
                                                    class="btn-outline-success text-success __action-btn btn-shadow rounded-full">
                                                        <i class="tio-download-to"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <div class="text-center pt-5 text-capitalize">
                                <img src="{{asset('public/assets/front-end/img/icons/order.svg')}}" alt="" width="70">
                                <h5 class="mt-1 fs-14">{{translate('no_order_found')}}!</h5>
                            </div>
                        @endif
                        <div class="card-footer border-0">
                            {{$orders->links()}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <!-- Mobile View -->
    <div class="bg-white d-lg-none" dir="{{Session::get('direction') === "rtl" ? 'rtl' : 'ltr'}}">
        <div class="card-body d-flex flex-column gap-3">
            @foreach($orders as $order)
                <div class="d-flex border rounded p-2 justify-content-between gap-2">
                    <div class="">
                        <div class="media-order">
                            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="d-block position-relative">
                                @if($order->seller_is == 'seller')
                                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{ asset('storage/app/public/shop/'.(isset($order->seller->shop) ? $order->seller->shop->image:''))}}" class="border" alt="img/products">
                                @elseif($order->seller_is == 'admin')
                                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" class="border"
                                    src="{{asset("storage/app/public/company")}}/{{$web_config['fav_icon']->value}}" alt="img/products">
                                @endif
                            </a>
                            <div class="cont text-start">
                                <h6 class="font-weight-bold mb-1 fs-14">
                                    <a href="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                        {{translate('ID')}}: {{$order['id']}}
                                    </a>
                                </h6>
                                <div class="d-flex flex-column gap-1 fs-12">
                                    <span>{{ $order->order_details_sum_qty }} {{translate('items')}}</span>
                                    <div>{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="text-nowrap">{{('total')}} :</div>
                                        <div class="text-dark font-weight-bold">{{\App\CPU\Helpers::currency_converter($order['order_amount'])}}</div>
                                    </div>
                                    <div>
                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                            <span class="badge badge-danger text-capitalize">
                                                {{translate($order['order_status'] =='failed' ? 'failed_to_deliver' : $order['order_status'])}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                            <span class="badge badge-success text-capitalize">
                                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                            </span>
                                        @else
                                            <span class="badge badge-info text-capitalize">
                                                {{translate($order['order_status'])}}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="__btn-grp-sm ">
                        <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                        class="btn-outline--info text-base __action-btn btn-shadow rounded-full" title="{{translate('view_order_details')}}">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{route('generate-invoice',[$order->id])}}" title="{{translate('download_invoice')}}"
                            class="btn-outline-success text-success __action-btn btn-shadow rounded-full">
                                <i class="tio-download-to"></i>
                        </a>
                    </div>
                </div>
            @endforeach

            @if($orders->count()==0)
                <div class="text-center pt-5 text-capitalize">
                    <img src="{{asset('public/assets/front-end/img/icons/order.svg')}}" alt="" width="70">
                    <h5 class="fs-14 mt-1">{{translate('no_order_found')}}!</h5>
                </div>
            @endif

            <div class="card-footer border-0">
                {{$orders->links()}}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function cancel_message() {
            toastr.info('{{translate('order_can_be_canceled_only_when_pending.')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endpush
