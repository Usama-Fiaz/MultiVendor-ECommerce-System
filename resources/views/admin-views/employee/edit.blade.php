@extends('layouts.back-end.app')
@section('title', translate('employee_Edit'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-employee.png')}}" alt="">
            {{translate('employee_Update')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.employee.update',[$e['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{translate('general_Information')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"
                                        class="title-color">{{translate('full_Name')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="{{translate('ex')}} : Jhon Doe"
                                        value="{{$e['name']}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('phone')}}</label>
                                    <input type="number" name="phone" value="{{$e['phone']}}" class="form-control"
                                        id="phone"
                                        placeholder="{{translate('ex')}} : +88017********" required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('role')}}</label>
                                    <select class="form-control" name="role_id">
                                        <option value="0" selected disabled>---{{translate('select')}}---
                                        </option>
                                        @foreach($rls as $r)
                                            <option value="{{$r->id}}" {{$r['id']==$e['admin_role_id']?'selected':''}}>{{$r->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- identify Type -->
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('identify_type')}}</label>
                                    <select class="form-control" name="identify_type">
                                        <option value="" selected disabled>{{translate('select_identify_type')}} </option>
                                        <option value="nid" {{$e->identify_type == 'nid' ?'selected' : ''}}>{{translate('NID')}}</option>
                                        <option value="passport" {{$e->identify_type == 'passport' ?'selected' : ''}}>{{translate('passport')}}</option>
                                    </select>
                                </div>
                                <!-- identify Type -->
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('identify_number')}}</label>
                                    <input type="number" name="identify_number" value="{{$e->identify_number}}" class="form-control"
                                        placeholder="{{translate('ex')}} : 9876123123">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="text-center mb-3">
                                        <img class="upload-img-view" id="viewer"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/admin')}}/{{$e['image']}}"
                                            alt="Product thumbnail"/>
                                    </div>
                                    <label for="name" class="title-color">{{translate('employee_image')}}</label>
                                    <span class="text-info">( {{translate('ratio')}} 1:1 )</span>
                                    <div class="form-group">
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                for="customFileUpload">{{translate('choose_File')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="title-color" for="exampleFormControlInput1">{{translate('identity_image')}}</label>
                                    <div>
                                        <div class="row" id="coba">
                                            @if ($e['identify_image'])
                                                @foreach(json_decode($e['identify_image'],true) as $img)
                                                    <div class="col-md-4 mb-3">
                                                        <img height="150"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{asset('storage/app/public/admin').'/'.$img}}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                            <i class="tio-user"></i>
                            {{translate('account_Information')}}
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('email')}}</label>
                                    <input type="email" name="email" value="{{$e['email']}}" class="form-control"
                                        id="email"
                                        placeholder="{{translate('ex')}} : ex@gmail.com" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password" class="title-color">{{translate('password')}}</label><small> ( {{translate('input_if_you_want_to_change')}} )</small>
                                    <input type="text" name="password" class="form-control" id="password"
                                        placeholder="{{translate('password')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="confirm_password"
                                        class="title-color">{{translate('confirm_password')}}</label>
                                    <input type="text" name="confirm_password" class="form-control"
                                        id="confirm_password"
                                        placeholder="{{translate('confirm_password')}}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="submit" class="btn btn--primary px-4">{{translate('update')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'employee-image-modal'])
    <!--modal-->
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
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


        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    </script>
    <script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-lg-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset("public/assets/back-end/img/400x400/img2.jpg")}}',
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

    @include('shared-partials.image-process._script',[
   'id'=>'employee-image-modal',
   'height'=>200,
   'width'=>200,
   'multi_image'=>false,
   'route'=>route('image-upload')
   ])
@endpush
