<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/payment-method') ?'active':'' }}"><a href="{{route('admin.business-settings.payment-method.index')}}">{{translate('Payment_Methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/payment-method/offline-payment*') ?'active':'' }}"><a href="{{route('admin.business-settings.payment-method.offline')}}">{{translate('offline_Payment_Methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/mail') ?'active':'' }}"><a href="{{route('admin.business-settings.mail.index')}}">{{translate('Mail_Config')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/sms-module') ?'active':'' }}"><a href="{{route('admin.business-settings.sms-module')}}">{{translate('SMS_Config')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/captcha') ?'active':'' }}"><a href="{{route('admin.business-settings.captcha')}}">{{translate('Recaptcha')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/map-api') ?'active':'' }}"><a href="{{route('admin.business-settings.map-api')}}">{{translate('Google_Map_APIs')}}</a></li>
        {{-- <li class="{{ Request::is('admin/business-settings/fcm-index') ?'active':'' }}"><a href="{{route('admin.business-settings.fcm-index')}}">{{translate('Push_Notification_Setup')}}</a></li> --}}
        <li class="{{ Request::is('admin/social-login/view') ?'active':'' }}"><a href="{{route('admin.social-login.view')}}">{{translate('Social_Media_Login')}}</a></li>
        <li class="{{ Request::is('admin/social-media-chat/view') ?'active':'' }}"><a href="{{route('admin.social-media-chat.view')}}">{{translate('Social_Media_Chat')}}</a></li>
    </ul>
</div>
