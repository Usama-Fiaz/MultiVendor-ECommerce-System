@extends('layouts.back-end.app')

@section('title', translate('OTP_setup'))

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
        <form action="{{ route('admin.business-settings.otp-setup-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/otp.png')}}" alt="">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">{{translate('OTP_&_login_settings')}}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{translate('maximum_OTP_hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('set_how_many_times_a_user_can_hit_the_wrong_OTPs.') }} {{ translate('after_reaching_this_limit_the_user_will_be_blocked_for_a_time') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_otp_hit}}" name="maximum_otp_hit" class="form-control"  placeholder="{{translate('ex: 5')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="otp_resend_time">{{translate('OTP_resend_time_')}}
                                    <small>({{translate('sec')}})</small>
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('set_the_time_for_requesting_a_new_OTP') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$otp_resend_time}}"
                                       name="otp_resend_time" class="form-control"  placeholder="{{translate('ex: 30 ')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{translate('temporary_block_time')}}
                                    <small>({{translate('sec')}})</small>
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('Within_this_time_users_can_not_make_OTP_requests_again') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$temporary_block_time}}" step="0.01"
                                       name="temporary_block_time" class="form-control"  placeholder="{{translate('ex: 120')}}" required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{translate('maximum Login hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('set_the_maximum_unsuccessful_login_attempts_users_can_make_using_wrong_passwords.') }} {{ translate('after_reaching_this_limit_they_will_be_blocked_for_a_time') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_login_hit}}" placeholder="{{translate('ex: 5')}}"
                                       name="maximum_login_hit" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{translate('temporary_login_block_time')}}
                                    <small>({{translate('sec')}})</small>
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ translate('set_a_time_duration_during_which_users_cannot_log_in_after_reaching_the_Maximum_Login_Hit_limit') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$temporary_login_block_time}}" placeholder="{{translate('ex: 1210')}}"
                                       name="temporary_login_block_time" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-5">
                            {{translate('save')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
