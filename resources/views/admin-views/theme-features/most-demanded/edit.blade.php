@extends('layouts.back-end.app')

@section('title', translate('edit_most_demanded'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2 text-capitalize">
            <img width="20" src="{{asset('/public/assets/back-end/img/most_demnaded.png')}}" alt="">
            {{translate('edit_most_demanded')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.most-demanded.update',['id'=>$most_demanded_product->id])}}" method="post" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="name" class="title-color font-weight-medium">{{ translate('products')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="product_id">
                                        <option value="" disabled selected>
                                            {{ translate('select_Product')}}
                                        </option>
                                        @foreach ($products as $key => $product)
                                            <option value="{{ $product->id }}"{{$most_demanded_product->product_id == $product->id ?'selected':''}}>
                                                {{$product['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group md-2">
                                    <label for="name" class="title-color font-weight-medium">{{translate('banner')}}</label>
                                    <span class="text-info ml-1">( {{translate('ratio')}} 5:1 )</span>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize" for="customFileUpload">{{translate('choose_File')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="text-center mt-lg-3">
                                        <img class="border radius-10 ratio-4:1 max-w-655px w-100" id="viewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'" src="{{asset('storage/app/public/most-demanded')}}/{{$most_demanded_product['banner']}}" alt="banner image"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
@endpush
