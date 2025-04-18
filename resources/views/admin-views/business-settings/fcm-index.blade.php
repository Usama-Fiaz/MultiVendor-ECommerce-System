@extends('layouts.back-end.app')

@section('title', translate('FCM_Settings'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('push_Notification_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
            <!-- Inlile Menu -->
            @include('admin-views.notification.notification-inline-menu')
            <!-- End Inlile Menu -->

            <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder text-capitalize">
                {{translate('where_to_get_this_information?')}}
                <div class="ripple-animation" data-toggle="modal" data-target="#getInformationModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.update-fcm')}}" method="post"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        enctype="multipart/form-data">
                    @csrf
                    @php($key=\App\Model\BusinessSetting::where('type','push_notification_key')->first()->value)
                    <div class="form-group">
                        <label class="title-color">{{translate('server_Key')}}</label>
                        <textarea name="push_notification_key" class="form-control" rows="2" placeholder="{{translate('Ex: abcd1234efgh5678ijklmnop90qrstuvwxYZ')}}"
                                    required>{{env('APP_MODE')=='demo'?'':$key}}</textarea>
                    </div>

                    <div class="row d--none">
                        @php($project_id=\App\Model\BusinessSetting::where('type','fcm_project_id')->first()->value)
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <label class="input-label"
                                        for="exampleFormControlInput1">{{translate('FCM_Project_ID')}}</label>
                                <input type="text" value="{{$project_id}}"
                                        name="fcm_project_id" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="title-color" for="api_key">{{translate('api_Key')}}</label>
                        <textarea name="api_key" id="api_key" class="form-control" rows="1" placeholder="{{translate('Ex: abcd1234efgh5678ijklmnop90qrstuvwxYZ')}}" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="fcm_project_id" class="title-color">{{translate('FCM_Project_ID')}}</label>
                                <input type="text" name="fcm_project_id" id="fcm_project_id" class="form-control" placeholder="{{translate('Ex: my-awesome-app-12345')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="auth_domain" class="title-color">{{translate('auth_domain')}}</label>
                                <input type="text" name="auth_domain" id="auth_domain" class="form-control" placeholder="{{translate('Ex: my-awesome-app.firebaseapp.com')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="storage_bucket" class="title-color">{{translate('storage_bucket')}}</label>
                                <input type="text" name="storage_bucket" id="storage_bucket" class="form-control" placeholder="{{translate('Ex: my-awesome-app.appspot.com')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="messaging_sender_id" class="title-color">{{translate('messaging_sender_id')}}</label>
                                <input type="text" name="messaging_sender_id" id="messaging_sender_id" class="form-control" placeholder="{{translate('Ex: 1234567890')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="api_id" class="title-color">{{translate('api_ID')}}</label>
                                <input type="text" name="api_id" id="api_id" class="form-control" placeholder="{{translate('Ex: 9876543210')}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label for="measurement_ID" class="title-color">{{translate('measurement_ID')}}</label>
                                <input type="text" name="measurement_ID" id="measurement_ID" class="form-control" placeholder="{{translate('Ex: F-12345678')}}">
                            </div>
                        </div>
                    </div> --}}


                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                        <button type="submit" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-5">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Where Get Information Modal -->
    <div class="modal fade" id="getInformationModal" tabindex="-1" aria-labelledby="getInformationModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{asset('/public/assets/back-end/img/firebase-console.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Go to Firebase Console</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>Open your web browser and go to the Firebase Console <br> ( <span class="text-decoration-underline">https://console.firebase.google.com/</span> ).</li>
                                        <li>Select the project for which you want to configure FCM from the Firebase Console dashboard.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{asset('/public/assets/back-end/img/navigate-settings.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Navigate to Project Settings</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>In the left-hand menu, click on the "Settings" gear icon, and then select "Project settings" from the dropdown.</li>
                                        <li>In the Project settings page, click on the "Cloud Messaging" tab from the top menu.</li>
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center text-center gap-2">
                                    <img width="80" class="mb-3" src="{{asset('/public/assets/back-end/img/file.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Please Visit the Docs to Set FCM on Mobile Apps</h4>
                                    <p>Please check the documentation below for detailed instructions on setting up your mobile app to receive Firebase Cloud Messaging (FCM) notifications.</p>

                                    <div class="d-flex justify-content-center">
                                        <a href="#" class="btn-link text-decoration-underline">{{translate('click_here')}}</a>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{asset('/public/assets/back-end/img/info-asked.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Obtain All The Information Asked!</h4>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>In the Firebase Project settings page, click on the "General" tab from the top menu.</li>
                                        <li>Under the "Your apps" section, click on the "Web" app for which you want to configure FCM.</li>
                                        <li>Then Obtain API Key, FCM Project ID, Auth Domain, Storage Bucket, Messaging Sender ID.</li>
                                    </ul>
                                    <p>Note: Please make sure to use the obtained information securely and in accordance with Firebase and FCM documentation, terms of service, and any applicable laws and regulations.</p>
                                    <button class="btn btn-primary px-10 mt-3" data-dismiss="modal">{{ translate('Got_It') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mb-2"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            autoHeight: true,
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
                clickable: true,
            },
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
