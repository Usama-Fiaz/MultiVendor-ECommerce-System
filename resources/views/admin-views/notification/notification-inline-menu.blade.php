<div class="inline-page-menu">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/notification/push') ?'active':'' }}">
            <a href="{{route('admin.notification.push')}}">
                <i class="tio-notifications-on-outlined"></i>
                {{translate('Push_Notification')}}
            </a>
        </li>
        <li class="{{ Request::is('admin/business-settings/fcm-index') ?'active':'' }}">
            <a href="{{route('admin.business-settings.fcm-index')}}">
                <i class="tio-cloud-outlined"></i>
                {{translate('Firebase Configuration')}}
            </a>
        </li>
    </ul>
</div>
