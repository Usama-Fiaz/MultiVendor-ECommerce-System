<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between flex-grow-1 d-lg-block">
        <h5 class="">{{translate('Order')}} #{{$order['id']}} </h5>
        <p class="fs-12">{{date('d M, Y h:i A',strtotime($order->created_at))}}</p>
    </div>

    <div class="">
        <div class="d-none d-lg-flex gap-3 justify-content-end">
            @if($order->order_status=='delivered' &&  $order->order_type == 'default_type')
                <a href="javascript:" onclick="order_again({{ $order->id }})" class="btn btn-primary rounded-pill">{{ translate('reorder') }}</a>
            @endif
        </div>

        <div class="d-none d-lg-flex gap-3 align-items-center mt-2 justify-content-between">
            <h6>{{translate('Order_Status')}}</h6>

            @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                <span class="badge bg-danger rounded-pill">
                    {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                </span>
            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                <span class="badge bg-success rounded-pill">
                    {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                </span>
            @else
                <span class="badge bg-info rounded-pill">
                    {{translate($order['order_status'])}}
                </span>
            @endif
        </div>

        <div class="d-none d-lg-flex gap-3 align-items-center mt-2 justify-content-between">
            <h6>{{translate('Payment_Status')}}</h6>
            <div class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
        </div>
        @if($order->order_type == 'default_type' && \App\CPU\Helpers::get_business_settings('order_verification'))
            <div class="d-none d-lg-flex gap-3 align-items-center mt-2 justify-content-between">
                <h6>{{translate('Verification_Code')}}</h6>
                <div class="badge bg-primary rounded-pill"> {{ $order['verification_code'] }}</div>
            </div>
        @endauth

        <!-- offline_payment -->
        @if($order->payment_method == 'offline_payment' && isset($order->offline_payments))
        @foreach (json_decode($order->offline_payments->payment_info) as $key=>$item)
            @if ($key != 'method_id' && $key != 'method_name')
                <div class="d-none d-lg-flex gap-2 align-items-center mt-2">
                    <h6>{{translate($key)}} :</h6>
                    <div class="text-dark">{{ $item }}</div>
                </div>
            @endif
        @endforeach
        @endif
    </div>
</div>

<div class="mt-4">
    <nav>
        <div class="nav nav-nowrap gap-3 gap-xl-4 nav--tabs hide-scrollbar">
            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="{{Request::is('account-order-details')  ? 'active' :''}}">{{translate('Order_Summary')}}</a>
            <a href="{{ route('account-order-details-seller-info', ['id'=>$order->id]) }}" class="{{Request::is('account-order-details-seller-info')  ? 'active' :''}}">{{translate('Seller_Info')}}</a>
            <a href="{{ route('account-order-details-delivery-man-info', ['id'=>$order->id]) }}" class="{{Request::is('account-order-details-delivery-man-info')  ? 'active' :''}}" >{{translate('Delivery_Man_Info')}}</a>
            <a href="{{route('track-order.order-wise-result-view',['order_id'=>$order['id']])}}" class="{{Request::is('track-order/order-wise-result-view*')  ? 'active' :''}}" >{{translate('Track_Order')}}</a>
        </div>
    </nav>
</div>
