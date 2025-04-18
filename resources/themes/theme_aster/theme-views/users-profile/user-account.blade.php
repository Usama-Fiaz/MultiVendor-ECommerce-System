@extends('theme-views.layouts.app')

@section('title', translate('Personal_Details').' | '.$web_config['name']->value.' '.translate('ecommerce'))
@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')

                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('Edit_Personal_Details')}}</h5>
                                <a href="{{ route('user-profile') }}" class="btn-link text-secondary d-flex align-items-baseline">
                                    <i class="bi bi-chevron-left fs-12"></i> {{translate('Go_back')}}
                                </a>
                            </div>

                            <div class="mt-4">
                                <form  action="{{route('user-update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row gy-4">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="f_name2">{{translate('First_Name')}}</label>
                                                <input type="text" id="f_name" class="form-control" value="{{$customerDetail['f_name']}}" name="f_name" placeholder="{{translate('Contact_Person_Name')}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="l_name2">{{translate('Last_Name')}}</label>
                                                <input type="text" id="l_name" class="form-control" value="{{$customerDetail['l_name']}}" name="l_name" placeholder="{{translate('Contact_Person_Name')}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="phone2">{{translate('Phone')}}</label>
                                                <input type="text" id="phone" name="phone" class="form-control" value="{{$customerDetail['phone']}}" placeholder="{{translate('Ex:  01xxxxxxxxx')}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email2">{{translate('Email')}}</label>
                                                <input type="email" id="email2" class="form-control" value="{{$customerDetail['email']}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="password2">{{translate('Password')}}</label>
                                                <div class="input-inner-end-ele">
                                                    <input type="password" minlength="6" id="password" class="form-control" name="password" placeholder="{{translate('Ex:_7+ character')}}">
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="confirm_password2">{{translate('Confirm_Password')}}</label>
                                                <div class="input-inner-end-ele">
                                                    <input type="password" minlength="6" id="confirm_password" name="confirm_password" class="form-control" placeholder="{{translate('Ex:_7+_character')}}">
                                                    <i class="bi bi-eye-slash-fill togglePassword"></i>
                                                </div>
                                            </div>
                                            <div id='message'></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>{{translate('Attachment')}}</label>
                                                <div class="d-flex flex-column gap-3">
                                                    <div class="upload-file">
                                                        <input type="file" class="upload-file__input"  name="image" multiple aria-required="true" accept="image/*">
                                                        <div class="upload-file__img">
                                                            <div class="temp-img-box">
                                                                <div class="d-flex align-items-center flex-column gap-2">
                                                                    <i class="bi bi-upload fs-30"></i>
                                                                    <div class="fs-12 text-muted">{{translate('change_your_profile')}}</div>
                                                                </div>
                                                            </div>
                                                            <img src="#" class="dark-support img-fit-contain border" alt="" hidden="">
                                                        </div>
                                                    </div>

                                                    <div class="text-muted">{{translate('Image_ratio_should_be')}} 1:1</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                                                <button type="submit" class="btn btn-primary">{{translate('Update_Profile')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        function checkPasswordMatch() {
            var password = $("#password").val();
            var confirmPassword = $("#confirm_password").val();
            $("#message").removeAttr("style");
            $("#message").html("");
            if (confirmPassword == "") {
                $("#message").attr("style", "color:black");
                $("#message").html("{{translate('Please_ReType_Password')}}");

            } else if (password == "") {
                $("#message").removeAttr("style");
                $("#message").html("");

            } else if (password != confirmPassword) {
                $("#message").html("{{translate('Passwords_do_not_match')}}!");
                $("#message").attr("style", "color:red");
            } else if (confirmPassword.length <= 7) {
                $("#message").html("{{translate('password_Must_Be_8_Character')}}");
                $("#message").attr("style", "color:red");
            } else {

                $("#message").html("{{translate('Passwords_match')}}.");
                $("#message").attr("style", "color:green");
            }
        }
        $(document).ready(function () {
            $("#confirm_password").keyup(checkPasswordMatch);
        });
        $( "#password" ).on("keyup", function() {
            if ($( "#confirm_password" ).val() != '') {
                checkPasswordMatch();
            }
        } );
    </script>
@endpush

