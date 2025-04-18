@extends('layouts.back-end.app-seller')

@section('title', translate('profile_Settings'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="mb-3">
            <div class="row gy-2 align-items-center">
                <div class="col-sm">
                    <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                        <img src="{{asset('/public/assets/back-end/img/support-ticket.png')}}" alt="">
                        {{translate('settings')}}
                    </h2>
                </div>
                <!-- End Page Title -->

                <div class="col-sm-auto">
                    <a class="btn btn--primary" href="{{route('seller.dashboard.index')}}">
                        <i class="tio-home mr-1"></i> {{translate('dashboard')}}
                    </a>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-3">
                <!-- Navbar -->
                <div class="navbar-vertical navbar-expand-lg mb-3 mb-lg-5">
                    <!-- Navbar Toggle -->
                    <button type="button" class="navbar-toggler btn btn-block btn-white mb-3"
                            aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu"
                            data-toggle="collapse" data-target="#navbarVerticalNavMenu">
                <span class="d-flex justify-content-between align-items-center">
                  <span class="h5 mb-0">{{translate('nav_menu')}}</span>

                  <span class="navbar-toggle-default">
                    <i class="tio-menu-hamburger"></i>
                  </span>

                  <span class="navbar-toggle-toggled">
                    <i class="tio-clear"></i>
                  </span>
                </span>
                    </button>
                    <!-- End Navbar Toggle -->

                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <!-- Navbar Nav -->
                        <ul id="navbarSettings"
                            class="js-sticky-block js-scrollspy navbar-nav navbar-nav-lg nav-tabs card card-navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:" id="generalSection">
                                    <i class="tio-user-outlined nav-icon"></i>{{translate('basic_Information')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:" id="passwordSection">
                                    <i class="tio-lock-outlined nav-icon"></i> {{translate('password')}}
                                </a>
                            </li>
                        </ul>
                        <!-- End Navbar Nav -->
                    </div>
                </div>
                <!-- End Navbar -->
            </div>

            <div class="col-lg-9">
                <form action="{{route('seller.profile.update',[$data->id])}}" method="post"
                      enctype="multipart/form-data" id="seller-profile-form">
                @csrf
                <!-- Card -->
                    <div class="card mb-3 mb-lg-5" id="generalDiv">
                        <!-- Profile Cover -->
                        <div class="profile-cover">
                        @php($shop_banners = $shop_banner ? asset('storage/app/public/shop/banner/'.$shop_banner) : 'https://images.pexels.com/photos/866398/pexels-photo-866398.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')

                            <div class="profile-cover-img-wrapper" style="background-image: url({{ $shop_banners }}); background-repeat: no-repeat; background-size: cover;">
                            </div>
                        </div>
                        <!-- End Profile Cover -->

                        <!-- Avatar -->
                        <label
                            class="avatar avatar-xxl avatar-circle avatar-border-lg avatar-uploader profile-cover-avatar"
                            for="avatarUploader">
                            <img id="viewer"
                                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                                 class="avatar-img"
                                 src="{{asset('storage/app/public/seller')}}/{{$data->image}}"
                                 alt="Image">
                        </label>
                        <!-- End Avatar -->
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h5 class="mb-0">{{translate('basic_Information')}}</h5>
                        </div>

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form -->
                            <!-- Form Group -->
                            <div class="row">
                                <label for="firstNameLabel"
                                       class="col-sm-3 col-form-label input-label">{{translate('full_Name')}}
                                    <i
                                        class="tio-help-outlined text-body ml-1" data-toggle="tooltip"
                                        data-placement="top"
                                        title="Display name"></i></label>

                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="title-color">{{translate('first_Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="f_name" value="{{$data->f_name}}" class="form-control"
                                                id="name"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="title-color">{{translate('last_Name')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="l_name" value="{{$data->l_name}}" class="form-control"
                                                id="name"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row">
                                <label for="phoneLabel"
                                       class="col-sm-3 col-form-label input-label">{{translate('phone')}} </label>

                                <div class="col-sm-9 mb-3">
                                    <div class="text-info mb-2">( * {{translate('country_code_is_must_like_for_BD_880')}} )</div>
                                    <input type="number" class="js-masked-input form-control" name="phone" id="phoneLabel"
                                           placeholder="+x(xxx)xxx-xx-xx" aria-label="+(xxx)xx-xxx-xxxxx"
                                           value="{{$data->phone}}"
                                           data-hs-mask-options='{
                                           "template": "+(880)00-000-00000"
                                         }' required>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="row form-group">
                                <label for="newEmailLabel"
                                       class="col-sm-3 col-form-label input-label">{{translate('email')}}</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="newEmailLabel"
                                           value="{{$data->email}}"
                                           placeholder="{{translate('enter_new_email_address')}}" aria-label="Enter new email address" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-form-label">
                                </div>
                                <div class="form-group col-md-9" id="select-img">
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label"
                                               for="customFileUpload">{{translate('image_Upload')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button"
                                        onclick="{{env('APP_MODE')!='demo'?"form_alert('seller-profile-form','Want to update seller info ?')":"call_demo()"}}"
                                        class="btn btn--primary">{{translate('save_changes')}}
                                </button>
                            </div>

                            <!-- End Form -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->
                </form>

                <!-- Card -->
                <div id="passwordDiv" class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('change_your_password')}}</h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form id="changePasswordForm" action="{{route('seller.profile.settings-password')}}"
                              method="post"
                              enctype="multipart/form-data">
                        @csrf

                        <!-- Form Group -->
                            <div class="row form-group">
                                <label for="newPassword"
                                       class="col-sm-3 col-form-label input-label"> {{translate('new_Password')}}</label>

                                <div class="col-sm-9">
                                    <input type="password" class="js-pwstrength form-control" name="password"
                                           id="newPassword" placeholder="{{translate('enter_new_password')}}"
                                           aria-label="Enter new password"
                                           data-hs-pwstrength-options='{
                                           "ui": {
                                             "container": "#changePasswordForm",
                                             "viewports": {
                                               "progress": "#passwordStrengthProgress",
                                               "verdict": "#passwordStrengthVerdict"
                                             }
                                           }
                                         }'>

                                    <p id="passwordStrengthVerdict" class="form-text mb-2"></p>

                                    <div id="passwordStrengthProgress"></div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="row form-group">
                                <label for="confirmNewPasswordLabel"
                                       class="col-sm-3 col-form-label input-label pt-0"> {{translate('confirm_Password')}} </label>

                                <div class="col-sm-9">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="confirm_password"
                                               id="confirmNewPasswordLabel" placeholder="{{translate('confirm_your_new_password')}}"
                                               aria-label="Confirm your new password">
                                    </div>
                                </div>
                            </div>
                            <!-- End Form Group -->

                            <div class="d-flex justify-content-end">
                                <button type="button"
                                        onclick="{{env('APP_MODE')!='demo'?"form_alert('changePasswordForm','Want to update admin password ?')":"call_demo()"}}"
                                        class="btn btn--primary">{{translate('save_changes')}}</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Sticky Block End Point -->
                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Content -->
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
    </script>

    <script>
        $("#generalSection").click(function () {
            $("#passwordSection").removeClass("active");
            $("#generalSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#generalDiv").offset().top
            }, 2000);
        });

        $("#passwordSection").click(function () {
            $("#generalSection").removeClass("active");
            $("#passwordSection").addClass("active");
            $('html, body').animate({
                scrollTop: $("#passwordDiv").offset().top
            }, 2000);
        });
    </script>
@endpush

@push('script')

@endpush
