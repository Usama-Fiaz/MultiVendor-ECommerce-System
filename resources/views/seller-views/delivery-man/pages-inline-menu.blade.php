<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('seller/delivery-man/earning-statement*') ?'active':'' }}"><a href="{{ route('seller.delivery-man.earning-statement', ['id' => $delivery_man['id']]) }}">{{translate('overview')}}</a></li>
        <li class="{{ Request::is('seller/delivery-man/order-history-log*') ?'active':'' }}"><a href="{{ route('seller.delivery-man.order-history-log', ['id' => $delivery_man['id']]) }}">{{translate('order_History_Log')}}</a></li>
        <li class="{{ Request::is('seller/delivery-man/order-wise-earning*') ?'active':'' }}"><a href="{{ route('seller.delivery-man.order-wise-earning', ['id' => $delivery_man['id']]) }}">{{translate('earning')}}</a></li>
    </ul>
</div>
