@extends('layouts.back-end.app')

@section('title', translate('cookie_settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.system-settings-inline-menu')
        <!-- End Inlile Menu -->

        <form action="{{ route('admin.business-settings.cookie-settings-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="card">
                <div class="border-bottom py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center gap-10">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            <img width="20" src="{{asset('/public/assets/back-end/img/cookie.png')}}" alt="">
                            {{translate('cookie_settings')}}:
                        </h5>
                        <label class="switcher" for="cookie_setting_status"
                        onclick="toogleModal(event,'cookie_setting_status','cookie-off.png','cookie-on.png','{{translate('by_Turning_OFF_Cookie_Settings')}}','{{translate('by_Turning_ON_Cookie_Settings')}}',`<p>{{translate('If_you_disable_it_customers_cannot_see_Cookie_Settings_in_frontend')}}</p>`,`<p>{{translate('If_you_enable_it_customers_will_see_Cookie_Settings_in_frontend')}}</p>`)">
                            <input type="checkbox" class="switcher_input"
                                    name="status" id="cookie_setting_status"
                                    data-section="cookie_setting_status"
                                    value="1" {{isset($data['cookie_setting'])&&$data['cookie_setting']['status']==1?'checked':''}}>
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="loyalty-point-section" id="cookie_setting_status_section">
                        <div class="form-group">
                            <label class="title-color d-flex"
                                    for="loyalty_point_exchange_rate">{{translate('cookie_text')}}</label>
                            <textarea name="cookie_text" id="" cols="30" rows="6" class="form-control">{{isset($data['cookie_setting']) ? $data['cookie_setting']['cookie_text'] : ''}}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit" class="btn px-5 btn--primary">{{translate('save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

@endsection
