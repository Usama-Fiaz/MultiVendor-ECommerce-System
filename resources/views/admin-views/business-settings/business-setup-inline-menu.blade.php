<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config') ?'active':'' }}"><a href="{{route('admin.business-settings.web-config.index')}}">{{translate('business_settings')}}</a></li>
        <li class="{{ Request::is('admin/product-settings/inhouse-shop') ?'active':'' }}"><a href="{{ route('admin.product-settings.inhouse-shop') }}">{{translate('in-House_Shop')}}</a></li>
        <li class="{{ Request::is('admin/product-settings') ?'active':'' }}"><a href="{{ route('admin.product-settings.index') }}">{{translate('Product')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/order-settings/index') ?'active':'' }}"><a href="{{route('admin.business-settings.order-settings.index')}}">{{translate('Order')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/seller-settings') ?'active':'' }}"><a href="{{route('admin.business-settings.seller-settings.index')}}">{{translate('seller')}}</a></li>
        <li class="{{ Request::is('admin/customer/customer-settings') ?'active':'' }}"><a href="{{route('admin.customer.customer-settings')}}">{{translate('customer')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/delivery-man-settings') ?'active':'' }}"><a href="{{route('admin.business-settings.delivery-man-settings.index')}}">{{translate('delivery_Man')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/shipping-method/setting') ?'active':'' }}"><a href="{{route('admin.business-settings.shipping-method.setting')}}">{{translate('shipping_Method')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/delivery-restriction') ? 'active':'' }}"><a href="{{ route('admin.business-settings.delivery-restriction.index') }}">{{translate('delivery_Restriction')}}</a></li>
    </ul>
</div>
