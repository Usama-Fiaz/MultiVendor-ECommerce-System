@extends('layouts.back-end.app')

@section('title', translate('features_Section'))

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/Pages.png')}}" alt="">
            {{ translate('pages') }}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.pages-inline-menu')
    <!-- End Inlile Menu -->
    <form action="{{ route('admin.business-settings.features-section.submit') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('top') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="title">{{ translate('title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[title]"
                                    placeholder="{{ translate('type_your_title_text') }}"
                                    value="{{ isset($features_section_top) ? json_decode($features_section_top->value)->title : '' }}">
                            </div>
                            <div class="col-sm-12 col-md-6 mb-3">
                                <label for="subtitle">{{ translate('sub_Title') }}</label>
                                <input type="text" class="form-control" name="features_section_top[subtitle]"
                                    placeholder="{{ translate('type_your_subtitle_text') }}"
                                    value="{{ isset($features_section_top) ? json_decode($features_section_top->value)->subtitle : '' }}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('middle') }}</h5>
                        <span onclick="addThisFeaturesCard_middle()" class="btn btn--primary"><i class="tio-add pr-2"></i>{{ translate('add_New') }}</span>
                    </div>
                    <div class="card-body">

                        <div class="row" id="features_Section_middle_row">
                            @if (isset($features_section_middle) && !empty($features_section_middle) )
                                @forelse (json_decode($features_section_middle->value) as $item)
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer removeThisFeaturesCard_class">
                                                <span class="btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="{{ $item->title }}" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('sub_Title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle  }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header justify-content-end">
                                            <div class="cursor-pointer removeThisFeaturesCard_class">
                                                <span class="btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_middle[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('sub_Title') }}</label>
                                                <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse

                            @endif

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('features_Section') }} - {{ translate('bottom') }}</h5>
                        <span onclick="addThisFeaturesCard_bottom()" class="btn btn--primary"><i class="tio-add pr-2"></i>{{ translate('add_New') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row" id="features_Section_bottom_row">

                            @if (isset($features_section_bottom) && !empty($features_section_bottom) )
                                @forelse (json_decode($features_section_bottom->value) as $key => $item)
                                @php($card_index = rand(1111, 9999))
                                <div class="col-sm-12 col-md-3 mb-4">
                                    <div class="card">
                                        <div class="card-header align-items-center justify-content-between">
                                            <h5 class="m-0 text-muted">{{ translate('icon_box') }}</h5>
                                            <span class="cursor-pointer text-danger remove_icon_box_with_titles btn btn-outline-danger btn-sm square-btn" data-title="{{ $item->title }}" data-subtitle="{{ $item->subtitle }}">
                                                <i class="tio-delete"></i>
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control" disabled value="{{ $item->title }}"
                                                name="icontitle"
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">Sub Title</label>
                                                <textarea class="form-control" disabled
                                                    placeholder="{{ translate('type_your_subtitle_text') }}">{{ $item->subtitle }}</textarea>
                                            </div>

                                            <div class="mb-2 d-flex">
                                                <div class="custom_img_upload aspect-ratio-3-15">
                                                    <img id="pre_img_header_logo{{ $card_index }}" src="{{asset('storage/app/public/banner')}}/{{$item->icon}}"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'" class="w-100">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                                    <div class="card">
                                        <div class="card-header align-items-center justify-content-between">
                                            <h5 class="m-0 text-muted">{{ translate('icon_box') }}</h5>
                                            <div class="cursor-pointer removeThisFeaturesCard_class">
                                                <span class="btn btn-outline-danger btn-sm square-btn btn btn-outline-danger btn-sm square-btn">
                                                    <i class="tio-delete"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="title">{{ translate('title') }}</label>
                                                <input type="text" class="form-control"
                                                    name="features_section_bottom[title][]"
                                                    value="" required
                                                    placeholder="{{ translate('type_your_title_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title">{{ translate('Sub_Title') }}</label>
                                                <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                                    placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                            </div>

                                            <div class="custom_upload_input">
                                                <input type="file" name="features_section_bottom_icon[]" class="custom-upload-input-file aspect-ratio-3-15" id="" data-imgpreview="pre_img_header_logo" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="uploadColorImage(this)">

                                                <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                    <i class="tio-delete"></i>
                                                </span>

                                                <div class="img_area_with_preview position-absolute z-index-2">
                                                    <img id="pre_img_header_logo" class="h-auto aspect-ratio-3-15 bg-white" src="img" onerror="this.classList.add('d-none')">
                                                </div>
                                                <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                        <h3 class="text-muted">{{ translate('Upload_Icon') }}</h3>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end py-2">
                <button type="submit" class="btn btn--primary px-5">{{ translate('submit') }}</button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('script')
<script>
    function clearSiteIMGInput(id) {
        $('#' + id).val('');
        // $('#pre_' + id).attr('onerror', '');
        $('#pre_' + id).attr('src', '');
    };

    $('.removeThisFeaturesCard_class').on('click', function() {
        Swal.fire({
                title: '{{translate("are_you_sure")}}?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $(this).closest('.removeThisFeaturesCard_div').remove();
                }
            })
    });

    $('.removeThisFeaturesIcon_btn').on('click', function() {
        $(this).closest('.featuresIcon_div').remove();
    });

    function addThisFeaturesCard_middle() {
        let index = Math.floor((Math.random() * 100)+1);

        let html = `<div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                        <div class="card">
                            <div class="card-header justify-content-end">
                                <div class="cursor-pointer removeThisFeaturesCard_class">
                                    <span class="btn btn-outline-danger btn-sm square-btn">
                                        <i class="tio-delete"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">{{ translate('title') }}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_middle[title][]"
                                        placeholder="{{ translate('type_your_title_text') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">{{ translate('sub_Title') }}</label>
                                    <textarea class="form-control" name="features_section_middle[subtitle][]" required
                                        placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>`;

        $('#features_Section_middle_row').append(html);

        $('.removeThisFeaturesCard_class').on('click', function() {
            Swal.fire({
                title: '{{translate("are_you_sure")}}?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $(this).closest('.removeThisFeaturesCard_div').remove();
                }
            })
        });
    }

    function addThisFeaturesCard_bottom() {
        let index = Math.floor((Math.random() * 100)+1);

        let html = `<div class="col-sm-12 col-md-3 mb-4 removeThisFeaturesCard_div">
                        <div class="card">
                            <div class="card-header align-items-center justify-content-between">
                                <h5 class="m-0 text-muted">{{ translate('icon_box') }}</h5>
                                <div class="cursor-pointer removeThisFeaturesCard_class">
                                    <span class="btn btn-outline-danger btn-sm square-btn">
                                        <i class="tio-delete"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title">{{ translate('title') }}</label>
                                    <input type="text" class="form-control" required
                                        name="features_section_bottom[title][]"
                                        placeholder="{{ translate('type_your_title_text') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="title">{{ translate('sub_Title') }}</label>
                                    <textarea class="form-control" name="features_section_bottom[subtitle][]" required
                                        placeholder="{{ translate('type_your_subtitle_text') }}"></textarea>
                                </div>


                                <div class="custom_upload_input">
                                    <input type="file" name="features_section_bottom_icon[]" class="custom-upload-input-file aspect-ratio-3-15" id="" data-imgpreview="pre_img_header_logo${index}" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="uploadColorImage(this)">

                                    <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                        <i class="tio-delete"></i>
                                    </span>

                                    <div class="img_area_with_preview position-absolute z-index-2">
                                        <img id="pre_img_header_logo${index}" class="h-auto aspect-ratio-3-15 bg-white" src="img" onerror="this.classList.add('d-none')">
                                    </div>
                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                            <h3 class="text-muted">{{ translate('Upload_Icon') }}</h3>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>`;

        $('#features_Section_bottom_row').append(html);

        $('.removeThisFeaturesCard_class').on('click', function() {
            Swal.fire({
                title: '{{translate("are_you_sure")}}?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $(this).closest('.removeThisFeaturesCard_div').remove();
                }
            })
        });

        function uploadColorImage(thisData = null){
            if(thisData){
                document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
                document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
            }
        }

        $('.delete_file_input').click(function () {
            let $parentDiv = $(this).parent().parent();
            $parentDiv.find('input[type="file"]').val('');
            $parentDiv.find('.img_area_with_preview img').attr("src", " ");
            $(this).hide();
        });

        $('.custom-upload-input-file').on('change', function(){
            if (parseFloat($(this).prop('files').length) != 0) {
                let $parentDiv = $(this).closest('div');
                $parentDiv.find('.delete_file_input').fadeIn();
            }
        });
    }
</script>

<script>
    $('.remove_icon_box_with_titles').on('click',function(){
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '{{ translate("no") }}',
            confirmButtonText: '{{ translate("yes") }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: `{{ route('admin.business-settings.features-section.icon-remove') }}`,
                    method: 'POST',
                    data: {
                        _token:$('meta[name="_token"]').attr('content'),
                        title:$(this).data('title'),
                        subtitle:$(this).data('subtitle'),
                    },
                    success: function (data) {
                        if (data.status =="success") {
                            location.reload();
                        }
                    },
                });
            }
        })
    })

    function uploadColorImage(thisData = null){
        if(thisData){
            document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
            document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
        }
    }

    $('.delete_file_input').click(function () {
        let $parentDiv = $(this).parent().parent();
        $parentDiv.find('input[type="file"]').val('');
        $parentDiv.find('.img_area_with_preview img').attr("src", " ");
        $(this).hide();
    });

    $('.custom-upload-input-file').on('change', function(){
        if (parseFloat($(this).prop('files').length) != 0) {
            let $parentDiv = $(this).closest('div');
            $parentDiv.find('.delete_file_input').fadeIn();
        }
    });
</script>
@endpush
