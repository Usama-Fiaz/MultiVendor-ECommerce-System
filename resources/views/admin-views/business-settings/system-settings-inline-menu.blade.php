<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/web-config/environment-setup') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.environment-setup')}}">{{translate('Environment_Setup')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/app-settings') ?'active':'' }}"><a href="{{route('admin.business-settings.web-config.app-settings')}}">{{translate('app_Settings')}}</a></li>

        <li class="{{ Request::is('admin/business-settings/cookie-settings') ? 'active':'' }}"><a href="{{ route('admin.business-settings.cookie-settings') }}">{{translate('cookies')}}</a></li>

        <li class="{{ Request::is('admin/business-settings/otp-setup') ? 'active':'' }}"><a href="{{ route('admin.business-settings.otp-setup') }}">{{translate('OTP_&_Login')}}</a></li>

        <li class="{{ Request::is('admin/business-settings/language') ?'active':'' }}"><a
                href="{{route('admin.business-settings.language.index')}}">{{translate('language')}}</a></li>

        <li class="{{ Request::is('admin/currency/view') ?'active':'' }}"><a href="{{route('admin.currency.view')}}">{{translate('Currency')}}</a></li>
        <li class="{{ Request::is('admin/system-settings/software-update') ?'active':'' }}">
                <a href="{{route('admin.system-settings.software-update')}}">{{translate('software_update')}}</a>
        </li>

        <li class="{{ Request::is('admin/business-settings/web-config/mysitemap') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.mysitemap')}}">{{translate('Generate_Site_Map')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/analytics-index') ?'active':'' }}"><a
                href="{{route('admin.business-settings.analytics-index')}}">{{translate('Analytic_Script')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/login-url-setup') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.login-url-setup')}}">{{translate('login_Url_Setup')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/web-config/theme/setup') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.theme.setup')}}">{{translate('theme_setup')}}</a>
        </li>
        <li class="{{ Request::is('admin/business-settings/web-config/db-index') ?'active':'' }}"><a
                href="{{route('admin.business-settings.web-config.db-index')}}">{{translate('Clean_Database')}}</a>
        </li>
        <li class="{{ Request::is('admin/addon') ?'active':'' }}"><a
                href="{{route('admin.addon.index')}}">{{translate('system_Addons')}}</a>
        </li>
    </ul>
</div>
