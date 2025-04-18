<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/delivery-man/earning-statement-overview/*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $delivery_man['id']]) }}">{{translate('overview')}}</a></li>
        <li class="{{ Request::is('admin/delivery-man/order-history-log*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.order-history-log', ['id' => $delivery_man['id']]) }}">{{translate('order_History_Log')}}</a></li>
        <li class="{{ Request::is('admin/delivery-man/order-wise-earning*') ?'active':'' }}"><a href="{{ route('admin.delivery-man.order-wise-earning', ['id' => $delivery_man['id']]) }}">{{translate('earning')}}</a></li>
    </ul>
</div>
