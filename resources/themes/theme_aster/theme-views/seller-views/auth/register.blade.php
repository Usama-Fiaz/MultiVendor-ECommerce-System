@extends('theme-views.layouts.app')

@section('title', translate('Seller_Apply').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card">
                <div class="card-body p-sm-4">
                    <div class="row justify-content-between gy-4">
                        <div class="col-lg-4">
                            <div class="bg-light p-3 p-sm-4 rounded h-100">
                                <div class="d-flex justify-content-center">
                                    <div class="ext-center">
                                        <h2 class="mb-2">{{translate('Seller_Registration')}}</h2>
                                        <p>{{translate('Create_your_own_store.')}} {{translate('Already_have_store')}}?  <a class="text-primary fw-bold" href="{{route('seller.auth.login')}}">{{translate('Login')}}</a></p>

                                        <div class="my-4 text-center">
                                            <img width="243" src="{{theme_asset('assets/img/media/seller-registration.png')}}" loading="lazy" alt="" class="dark-support">
                                        </div>
                                        <p class="text-primary">{{translate('Open_your_and_start_selling.')}} {{translate('Create_your_own_business')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xl-7">
                            <form id="seller-registration" action="{{route('shop.apply')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="wizard">
                                    <h3>{{translate('Seller_Info')}}</h3>
                                    <section>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="firstName">{{translate('First_Name')}} *</label>
                                                    <input class="form-control" type="text" id="firstName" name="f_name" value="{{old('f_name')}}" placeholder="{{translate('Ex')}} : Jhon" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="lastName">{{translate('Last_Name')}} *</label>
                                                    <input class="form-control" type="text" id="lastName" name="l_name" value="{{old('l_name')}}" placeholder="{{translate('Ex')}} : Doe" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="email2">{{translate('Email')}} *</label>
                                                    <input class="form-control" type="email" id="email2"  name="email" value="{{old('email')}}" placeholder="{{translate('Enter_email')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="tel">{{translate('Phone')}} *</label>
                                                    <input class="form-control" type="tel" id="tel" name="phone" value="{{old('phone')}}" placeholder="{{translate('Enter_phone_number')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="password">{{translate('Password')}} *</label>
                                                    <div class="input-inner-end-ele">
                                                        <input class="form-control" type="password" id="password"  name="password" value="{{old('password')}}" placeholder="{{translate('Enter_password')}}" required>
                                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="repeat_password">{{translate('Confirm_Password')}} *</label>
                                                    <div class="input-inner-end-ele">
                                                        <input class="form-control" type="password" id="repeat_password" name="repeat_password" placeholder="{{translate('repeat_password')}}" required>
                                                        <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="media gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <div class="upload-file__img">
                                                            <div class="temp-img-box">
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="#" class="dark-support img-fit-contain border" alt="" hidden>
                                                        </div>
                                                    </div>

                                                    <div class="media-body d-flex flex-column gap-1 upload-img-content">
                                                        <h5 class="text-uppercase mb-1">{{translate('Seller_Image')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ration')}} 1:1</div>
                                                        <div class="text-muted">
                                                            {{translate('NB')}}: {{translate('image_size_must_be_within')}} 2 MB <br>
                                                            {{translate('NB')}}: {{translate('image_type_must_be_within')}} .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <h3>{{translate('Shop_Info')}}</h3>
                                    <section>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="storeName">{{translate('Store_Name')}} *</label>
                                                    <input class="form-control" type="text" id="storeName" name="shop_name" placeholder="{{translate('Ex')}}: {{translate('halar')}}" value="{{old('shop_name')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="storeAddress">{{translate('Store_Address')}} *</label>
                                                    <input class="form-control" type="text" id="storeAddress" name="shop_address" value="{{old('shop_address')}}" placeholder="{{translate('Ex_:_Shop_-12_Road-8') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-4">
                                                <div class="d-flex flex-column gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <div class="upload-file__img style--two">
                                                            <div class="temp-img-box">
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="#" class="dark-support img-fit-contain border" alt="" hidden>
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('Store_Banner')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ratio')}} 3:1</div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if(theme_root_path() == "theme_aster")
                                            <div class="col-lg-6 mb-4">
                                                <div class="d-flex flex-column gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="bottom_banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <div class="upload-file__img style--two">
                                                            <div class="temp-img-box">
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="#" class="dark-support img-fit-contain border" alt="" hidden>
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('Store_Secondary_Banner')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ratio')}} 3:1</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-lg-6 mb-4">
                                                <div class="d-flex flex-column gap-3 align-items-center">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input" name="logo" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                        <div class="upload-file__img">
                                                            <div class="temp-img-box">
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('Upload_File')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="#" class="dark-support img-fit-contain border" alt="" hidden>
                                                        </div>
                                                    </div>

                                                    <div class="text-center">
                                                        <h5 class="text-uppercase mb-1">{{translate('Store_Logo')}}</h5>
                                                        <div class="text-muted">{{translate('Image_Ratio')}} 1:1</div>
                                                    </div>
                                                </div>
                                            </div>


                                            @if($web_config['recaptcha']['status'] == 1)
                                            <div class="col-12">
                                                <div id="recaptcha_element_seller_regi" class="w-100 mt-4" data-type="image"></div>
                                                <br/>
                                            </div>
                                            @else
                                            <div class="col-12">
                                                <div class="row py-2 mt-4">
                                                    <div class="col-6 pr-2">
                                                        <input type="text" class="form-control border __h-40" name="default_recaptcha_id_seller_regi" value=""
                                                            placeholder="{{ translate('Enter_captcha_value') }}" autocomplete="off" required>
                                                    </div>
                                                    <div class="col-6 input-icons mb-2 rounded bg-white">
                                                        <a onclick="re_captcha_seller_regi();" class="d-flex align-items-center align-items-center">
                                                            <img src="{{ URL('/seller/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_seller_regi') }}" class="input-field rounded __h-40" id="default_recaptcha_id_regi">
                                                            <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-12">
                                                <label class="custom-checkbox">
                                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" required>
                                                    {{translate('I_agree_with_the')}} <a target="_blank" href="{{route('terms')}}">{{translate('terms_and_condition')}}.</a>
                                                </label>
                                            </div>

                                        </div>
                                    </section>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')
    <!-- Page Level Scripts -->
    <script src="{{theme_asset('assets/plugins/jquery-step/jquery.validate.min.js')}}"></script>
    <script src="{{theme_asset('assets/plugins/jquery-step/jquery.steps.min.js')}}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

@if($web_config['recaptcha']['status'] == '1')
    <script>
        var onloadCallback = function () {
            let reg_id = grecaptcha.render('recaptcha_element_seller_regi', {'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'});
            let login_id = grecaptcha.render('recaptcha_element_seller_login', {'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'});

            $('#recaptcha_element_seller_regi').attr('data-reg-id', reg_id);
            $('#recaptcha_element_seller_login').attr('data-login-id', login_id);
        };
    </script>
@else
    <script>
        function re_captcha_seller_regi() {
            $url = "{{ URL('/seller/auth/code/captcha/') }}";
            $url = $url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_seller_regi';

            document.getElementById('default_recaptcha_id_regi').src = $url;
            console.log('url: '+ $url);
        }
    </script>
@endif

    <script>
        // Multi Step Form

        $(document).ready(function(){
            $('#seller-registration [href="#next"]').text("{{ translate('next') }}");
            $('#seller-registration [href="#previous"]').text("{{ translate('previous') }}");
        });

        var form = $("#seller-registration");
        form.validate({
            errorPlacement: function errorPlacement(error, element) { element.before(error); },
            rules: {
                repeat_password: {
                    equalTo: "#password"
                }
            }
        });

        // Form Wizard
        form.children(".wizard").steps({
            headerTag: "h3",
            bodyTag: "section",
            onStepChanging: function (event, currentIndex, newIndex)
            {
                $('[href="#next"]').text("{{ translate('next') }}");
                $('[href="#previous"]').text("{{ translate('previous') }}");
                $('[href="#finish"]').text("{{ translate('finish') }}");
                $('[href="#finish"]').addClass('disabled');

                $('#acceptTerms').click(function(){
                    if ($(this).is(':checked')) {
                        $('[href="#finish"]').removeClass('disabled');
                    }else{
                        $('[href="#finish"]').addClass('disabled');
                    }
                });

                if (currentIndex > newIndex) {
                    return true;
                }
                if (currentIndex < newIndex) {
                    form.find('.body:eq(' + newIndex + ') label.error').remove();
                    form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                @if($web_config['recaptcha']['status'] == '1')
                    if(currentIndex > 0){
                        var response = grecaptcha.getResponse($('#recaptcha_element_seller_regi').attr('data-reg-id'));
                        if (response.length === 0) {
                            toastr.error("{{translate('Please_check_the_recaptcha')}}");
                        }else{
                            $('#seller-registration').submit();
                        }
                    }
                @else
                    $('#seller-registration').submit();
                @endif
            }
        });
    </script>

@endpush
