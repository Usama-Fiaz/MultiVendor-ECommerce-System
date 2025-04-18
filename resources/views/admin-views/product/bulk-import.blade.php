@extends('layouts.back-end.app')

@section('title', translate('product_Bulk_Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-1 text-capitalize d-flex gap-2">
                <img src="{{asset('/public/assets/back-end/img/bulk-import.png')}}" alt="">
                {{translate('bulk_Import')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-12">
                <div class="card card-body">
                    <h1 class="display-5">{{translate('instructions')}} : </h1>
                    <p>{{ translate('1') }}. {{translate('download_the_format_file_and_fill_it_with_proper_data.')}}</p>

                    <p>{{ translate('2') }}. {{translate('you_can_download_the_example_file_to_understand_how_the_data_must_be_filled.')}}</p>

                    <p>{{ translate('3') }}. {{translate('once_you_have_downloaded_and_filled_the_format_file')}}, {{translate('upload_it_in_the_form_below_and_submit.')}}</p>

                    <p>4. {{translate('after_uploading_products_you_need_to_edit_them_and_set_product_images_and_choices.')}}</p>

                    <p>5. {{translate('you_can_get_brand_and_category_id_from_their_list_please_input_the_right_ids.')}}</p>

                    <p>6. {{translate('you_can_upload_your_product_images_in_product_folder_from_gallery_and_copy_image_path.')}}</p>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <form class="product-form" action="{{route('admin.product.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card rest-part">
                        <div class="px-3 py-4 d-flex flex-wrap align-items-center gap-10 justify-content-center">
                            <h4 class="mb-0">{{translate("do_not_have_the_template")}} ?</h4>
                            <a href="{{asset('public/assets/product_bulk_format.xlsx')}}" download=""
                               class="btn-link text-capitalize fz-16 font-weight-medium">{{translate('download_here')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-auto">

                                        <!-- Drag & Drop Upload -->
                                        <div class="uploadDnD">

                                                <div class="form-group inputDnD input_image input_image_edit" data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                                <input type="file" name="products_file" accept=".xlsx, .xls" class="form-control-file text--primary font-weight-bold" id="inputFile"
                                                    onchange="readUrl(this)">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-10 align-items-center justify-content-end">
                                <button type="reset" class="btn btn-secondary px-4" onclick="resetImg();">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    // File Upload
    "use strict";

    $('.upload-file__input').on('change', function() {
        $(this).siblings('.upload-file__img').find('img').attr({
            'src': '{{asset("/public/assets/back-end/img/excel.png")}}',
            'width': 80
        });
    });

    function resetImg() {
        $('.upload-file__img img').attr({
            'src': '{{asset("/public/assets/back-end/img/drag-upload-file.png")}}',
            'width': 'auto'
        });
    }

    function readUrl(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = (e) => {
                let imgData = e.target.result;
                let imgName = input.files[0].name;
                input.closest('[data-title]').setAttribute("data-title", imgName);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endpush
