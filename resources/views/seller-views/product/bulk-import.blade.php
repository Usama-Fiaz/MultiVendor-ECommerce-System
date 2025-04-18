@extends('layouts.back-end.app-seller')

@section('title', translate('product_Bulk_Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-1 text-capitalize">
                <img src="{{asset('/public/assets/back-end/img/bulk-import.png')}}" class="mb-1 mr-1" alt="">
                {{translate('bulk_Import')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-12">
                <div class="card card-body">
                    <h1 class="display-4">{{translate('instructions')}} : </h1>
                    <p>{{ translate('1') }}. {{translate('download_the_format_file_and_fill_it_with_proper_data')}}.</p>
                    <p>{{ translate('2') }}. {{translate('you_can_download_the_example_file_to_understand_how_the_data_must_be_filled.')}}</p>
                    <p>{{ translate('3') }}. {{translate('once_you_have-downloaded_and_filled_the_format_file_upload_it_in_the_form_below_and_submit.')}}</p>
                    <p>{{ translate('4') }}. {{translate('after_uploading_products_you_need_to_edit_them_and_set_products_images_and_choices.')}}</p>
                    <p>{{ translate('5') }}. {{translate('you_can_get_brand_and_category_id_from_their_list_please_input_the_right_ids.')}}</p>
                    <p>{{ translate('6') }}. {{translate('you_can_upload_your_product_images_in_product_folder_from_gallery_and_copy_images_path.')}}</p>
                </div>
            </div>

            <div class="col-12 mt-2">
                <form class="product-form" action="{{route('seller.product.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card rest-part">
                        <div class="px-3 py-4 d-flex flex-wrap align-items-center gap-10 justify-content-center">
                            <h4 class="mb-0">{{translate('import_Products_File')}}</h4>
                            <a href="{{asset('public/assets/product_bulk_format.xlsx')}}" download=""
                               class="btn-link text-capitalize fz-16 font-weight-medium">{{translate('download_Format')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="upload-file">
                                            <input type="file" name="products_file" accept=".xlsx, .xls" class="upload-file__input">
                                            <div class="upload-file__img upload-file__img_drag">
                                                <img src="{{asset('/public/assets/back-end/img/drag-upload-file.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-10 align-items-center justify-content-end">
                                <button type="reset" id="reset" onclick="resetImg();" class="btn btn-secondary px-4">{{translate('reset')}}</button>
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
        "use strict";

        $('.upload-file__input').on('change', function() {
            $(this).siblings('.upload-file__img').find('img').attr({
                'src': '{{asset('/public/assets/back-end/img/excel.png')}}',
                'width': 80
            });
        });

        function resetImg() {
            $('.upload-file__img img').attr({
                'src': '{{asset('/public/assets/back-end/img/drag-upload-file.png')}}',
                'width': 'auto'
            });
        }
    </script>
@endpush
