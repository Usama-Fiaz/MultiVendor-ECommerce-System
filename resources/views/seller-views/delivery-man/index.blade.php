@extends('layouts.back-end.app-seller')

@section('title',translate('add_new_delivery_man'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/deliveryman.png')}}" alt="">
                {{translate('add_new_deliveryman')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <form action="{{route('seller.delivery-man.store')}}" method="post" enctype="multipart/form-data">
        @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('first_name')}}</label>
                                <input type="text" value="{{old('f_name')}}" name="f_name" class="form-control" placeholder="{{translate('first_name')}}"
                                        required>
                            </div>
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('last_name')}}</label>
                                <input type="text" value="{{old('l_name')}}" name="l_name" class="form-control" placeholder="{{translate('last_name')}}"
                                        required>
                            </div>
                            <div class="form-group">
                                <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('phone')}}</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                name="country_code" id="colors-selector" required>
                                            @foreach($telephone_codes as $code)
                                                <option value="{{ $code['code'] }}">{{ $code['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" value="{{old('phone')}}" name="phone" class="form-control" placeholder="{{translate('ex')}} : 017********"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_type')}}</label>
                                <select name="identity_type" class="form-control">
                                    <option value="passport">{{translate('passport')}}</option>
                                    <option value="driving_license">{{translate('driving_license')}}</option>
                                    <option value="nid">{{translate('nid')}}</option>
                                    <option value="company_id">{{translate('company_id')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_number')}}</label>
                                <input value="{{old('identity_number')}}" type="text" name="identity_number" class="form-control"
                                        placeholder="Ex : DH-23434-LS"
                                        required>
                            </div>
                            <div class="form-group">
                                <label class="title-color d-flex" for="exampleFormControlInput1">{{translate('address')}}</label>
                                <div class="input-group mb-3">
                                    <textarea name="address" class="form-control" id="address" rows="1" placeholder="Address">{{old('address')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="title-color">{{translate('deliveryman_image')}}</label>
                                <span class="text-info">* ( {{translate('ratio')}} 1:1 )</span>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="title-color custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label title-color" for="customFileEg1">
                                        {{translate('choose_File')}}
                                    </label>
                                </div>
                                <center class="mt-4">
                                    <img class="upload-img-view" id="viewer"
                                            src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="delivery-man image"/>
                                </center>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="title-color" for="exampleFormControlInput1">{{translate('identity_image')}}</label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color">{{translate('email')}}</label>
                                <input type="email" value="{{old('email')}}" name="email" class="form-control" placeholder="{{translate('ex')}} : ex@example.com" autocomplete="off"
                                        required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color">{{translate('password')}}</label>
                                <input type="password" name="password" class="form-control" placeholder="{{translate('password_minimum_8_characters')}}" autocomplete="off"
                                        required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="title-color">{{translate('confirm_password')}}</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="{{translate('password_minimum_8_characters')}}" autocomplete="off"
                                        required>
                            </div>
                        </div>
                    </div>
                    <span class="d-none" id="placeholderImg" data-img="{{asset('public/assets/back-end/img/400x400/img3.png')}}"></span>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" id="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script>
        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>

    <script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '248px',
                groupClassName: 'col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset("public/assets/back-end/img/400x400/img3.png")}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{ translate("please_only_input_png_or_jpg_type_file") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ translate("file_size_too_big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
