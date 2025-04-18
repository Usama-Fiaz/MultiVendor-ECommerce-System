@extends('layouts.back-end.app')

@section('title', translate('login_Url_Setup'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
            {{translate('system_Settings')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inline Menu -->
    @include('admin-views.business-settings.system-settings-inline-menu')
    <!-- End Inline Menu -->

    <div class="row my-3 gy-3">
        <div class="col-md-12">
            <form action="{{route('admin.business-settings.web-config.login-url-setup-post')}}" method="post">
                @csrf
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="text-capitalize mb-0">
                            {{translate('admin_Login_Page')}}
                        </h5>
                    </div>
                    <div class="card-body"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="mb-3">
                            <label class="form-label">
                                {{translate('admin_login_url')}}
                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                    data-placement="right"
                                    data-original-title="{{ translate('Add_dynamic_url_to_secure_admin_login_access.') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            @php($admin_login_url = \App\CPU\Helpers::get_business_settings('admin_login_url'))
                            <div class="input-group mb-3">
                                <span class="input-group-text radius-0 border-right-0">{{ url('/') }}/login/</span>
                                <input type="text" class="form-control" name="url" value="{{ $admin_login_url }}">
                                <input type="hidden" class="form-control" name="type" value="admin_login_url">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit"
                                class="btn btn--primary px-4">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12">
            <form action="{{route('admin.business-settings.web-config.login-url-setup-post')}}" method="post">
                @csrf
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="text-capitalize mb-0">
                            {{translate('employee_Login_Page')}}
                        </h5>
                    </div>
                    <div class="card-body"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="mb-3">
                            <label class="form-label">
                                {{translate('employee_login_url')}}
                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                    data-placement="right"
                                    data-original-title="{{ translate('Add_dynamic_url_to_secure_employee_login_access.') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            @php($employee_login_url = \App\CPU\Helpers::get_business_settings('employee_login_url'))
                            <div class="input-group mb-3">
                                <span class="input-group-text radius-0 border-right-0">{{ url('/') }}/login/</span>
                                <input type="text" class="form-control" name="url" value="{{ $employee_login_url }}">
                                <input type="hidden" class="form-control" name="type" value="employee_login_url">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submit"
                                class="btn btn--primary px-4">{{translate('submit')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
