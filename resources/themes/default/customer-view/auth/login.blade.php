@extends('layouts.front-end.app')
@section('title', translate('login'))
@push('css_or_js')
    <style>
        .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush
@section('content')
    <div class="container py-4 py-lg-5 my-4"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
         <div class="login-card">
            <div class="mx-auto __max-w-360">
                <h2 class="text-center h4 mb-4 font-bold text-capitalize fs-18-mobile">{{ translate('login')}}</h2>
                <form class="needs-validation mt-2" autocomplete="off" action="{{route('customer.auth.login')}}"
                        method="post" id="form-id">
                    @csrf
                    <div class="form-group">
                        <label class="form-label font-semibold">{{ translate('email') }}
                            / {{ translate('phone')}}</label>
                        <input class="form-control" type="text" name="user_id" id="si-email"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                value="{{old('user_id')}}"
                                placeholder="{{ translate('enter_email_address_or_phone_number') }}"
                                required>
                        <div class="invalid-feedback">{{ translate('please_provide_valid_email_or_phone_number') }} .</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-semibold">{{ translate('password') }}</label>
                        <div class="password-toggle rtl">
                            <input class="form-control" name="password" type="password" id="si-password" placeholder="{{translate('password must be 7+ Character')}}"
                                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    required>
                            <label class="password-toggle-btn">
                                <input class="custom-control-input" type="checkbox">
                                    <i class="tio-hidden password-toggle-indicator"></i>
                                    <span class="sr-only">{{ translate('show_password') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-between">
                        <div class="rtl">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember"
                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label text-primary" for="remember">{{ translate('remember_me') }}</label>
                            </div>
                        </div>
                        <a class="font-size-sm text-primary text-underline" href="{{route('customer.auth.recover-password')}}">
                            {{ translate('forgot_password') }}?
                        </a>
                    </div>
                    @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <div id="recaptcha_element" class="w-100" data-type="image"></div>
                        <br/>
                    @else
                        <div class="row py-2">
                            <div class="col-6 pr-2">
                                <input type="text" class="form-control border __h-40" name="default_recaptcha_id_customer_login" value=""
                                    placeholder="{{ translate('enter_captcha_value') }}" autocomplete="off">
                            </div>
                            <div class="col-6 input-icons mb-2 w-100 rounded bg-white">
                                <a onclick="re_captcha();" class="d-flex align-items-center align-items-center">
                                    <img src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}" class="input-field rounded __h-40" id="customer_login_recaptcha_id">
                                    <i class="tio-refresh icon cursor-pointer p-2"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                    <button class="btn btn--primary btn-block btn-shadow"
                            type="submit">{{ translate('log_in') }}</button>
                </form>
                <div class="text-center m-3 text-black-50">
                    <small>{{ translate('or_continue_with') }}</small>
                </div>
                <div class="d-flex justify-content-center my-3 gap-2">
                @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                    @if (isset($socialLoginService) && $socialLoginService['status']==true)
                        <div>
                            <a class="d-block" href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                <img src="{{asset('/public/assets/front-end/img/icons/'.$socialLoginService['login_medium'].'.png')}}" alt="">
                            </a>
                        </div>
                    @endif
                @endforeach
                </div>
                <div class="text-black-50 text-center">
                    <small>
                        {{  translate('Enjoy_New_experience') }}
                        <a class="text-primary text-underline" href="{{route('customer.auth.sign-up')}}">
                            {{ translate('sign_up') }}
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    {{-- recaptcha scripts start --}}
    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            var onloadCallback = function () {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async
                defer></script>
        <script>
            $("#form-id").on('submit', function (e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ translate('please_check_the_recaptcha') }}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            function re_captcha() {
                $url = "{{ URL('/customer/auth/code/captcha') }}";
                $url = $url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_customer_login';
                document.getElementById('customer_login_recaptcha_id').src = $url;
                console.log('url: '+ $url);
            }
        </script>
    @endif
    {{-- recaptcha scripts end --}}
@endpush
