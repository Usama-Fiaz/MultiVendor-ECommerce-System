@extends('layouts.back-end.app')

@section('title', translate('software_update'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="border-bottom px-4 py-3">
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            <img width="20" src="{{asset('/public/assets/back-end/img/environment.png')}}" alt="">
                            {{translate('upload_the_updated_file')}}
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{translate('this_module_will_run_for_updates_after_version_13.1')}}">
                                <img class="info-img w-200" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.system-settings.software-update')}}" method="post"
                              enctype="multipart/form-data" id="software_update_form_">
                            @csrf
                            <div class="progress mb-5 d-none" style="height: 30px;">
                                <div class="progress-bar progress-bar-animated" style="width:0%">0%</div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="purchase_code">{{translate('codecanyon_username')}}</label>
                                        <input type="text" class="form-control" id="username"
                                               value="{{env('BUYER_USERNAME')}}"
                                               name="username" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="purchase_code">{{translate('purchase_code')}}</label>
                                        <input type="text" class="form-control" id="purchase_key"
                                               value="{{env('PURCHASE_CODE')}}" name="purchase_key" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="custom-file text-left">
                                        <input type="file" name="update_file" class="custom-file-input form-control"
                                               accept=".zip" required>
                                        <label class="custom-file-label"
                                               for="customFileUpload">{{translate('choose_updated_file')}}</label>
                                    </div>
                                </div>
                            </div>

                            @php($condition_one=str_replace('M','',ini_get('upload_max_filesize'))>=180 && str_replace('M','',ini_get('upload_max_filesize'))>=180)
                            @php($condition_two=str_replace('M','',ini_get('post_max_size'))>=200 && str_replace('M','',ini_get('post_max_size'))>=200)
                            @if($condition_one && $condition_two)
                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                    <button type="submit" class="btn btn--primary px-4">
                                        {{translate('upload_&_update')}}
                                    </button>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-soft-{{($condition_one)?'success':'danger'}}" role="alert">
                                            1. {{translate('please_make_sure')}}, {{translate('your_server_php')}} "{{translate('upload_max_filesize')}}"
                                            {{translate('value_is_greater_or_equal_to')}} 180M. {{translate('current_value_is')}} - {{ini_get('upload_max_filesize')}}
                                        </div>
                                        <div class="alert alert-soft-{{($condition_two)?'success':'danger'}}" role="alert">
                                            2. {{translate('please_make_sure')}}, {{translate('your_server_php')}}  "{{translate('post_max_size')}}"
                                            {{translate('value_is_greater_or_equal_to')}} 200M. {{translate('current_value_is')}}- {{ini_get('post_max_size')}}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#software_update_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(document.getElementById('software_update_form'));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('admin.system-settings.software-update')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.progress').removeClass('d-none');
                    $('#product_form').find('.submit').text('submitting...');
                },
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress",
                        (evt) => {
                            if (evt.lengthComputable) {
                                let percentage = (evt.loaded / evt.total) * 100
                                let percentageFormatted = percentage.toFixed(0)
                                $('.progress-bar').css('width', `${percentageFormatted}%`).text(`${percentageFormatted}%`);
                            }
                        }, false);
                    return xhr;
                },
                success: function (response) {

                },
                complete: function () {
                    location.href = '{{route('home')}}/' + $('#update_key').val()
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });
    </script>
@endpush
