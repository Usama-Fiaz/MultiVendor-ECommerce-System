@extends('layouts.back-end.app')

@section('title', translate('company_Reliability'))

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
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row gy-4">
                        @foreach (json_decode($company_reliability_data->value) as  $key=>$value)
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="card">
                                <form action="{{ route('admin.business-settings.company-reliability.store',['item'=>$value->item]) }}" method="POST"   enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header align-items-center justify-content-between">
                                        <span class="title-color">
                                            {{translate($value->item)}}
                                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{translate('if_enabled,_the_'.$value->item.'_will_be_available_on_the_system.')}}.">
                                                <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                            </span>
                                        </span>
                                        <label class="switcher" for="{{$value->item}}">
                                            <input type="checkbox" class="switcher_input" name="status"
                                            onclick="toogleStatusModal(event,'{{$value->item}}','','',
                                            '{{translate('want_to_Turn_ON_the_'.$value->item.'_option')}}?','{{translate('want_to_Turn_OFF_the_'.$value->item.'_option')}}?',
                                            `<p>{{translate('if_enabled_customers_can_see_'.$value->item)}}</p>`,
                                            `<p>{{translate('if_disabled_the_'.$value->item.'_will_be_hidden_from_customer')}}</p>`)"
                                            id="{{$value->item}}" value="1" {{$value->status ==1 ? 'checked' : ''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="title">{{ translate('title') }}</label>
                                            <input type="text" class="form-control" name="title" value="{{$value->title}}"
                                            placeholder="{{ translate('type_your_title_text') }}">
                                        </div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="image" class="custom-upload-input-file aspect-ratio-3-15" data-imgpreview="pre_img_header_logo{{$key}}" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="uploadColorImage(this)">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_img_header_logo{{$key}}" class="h-auto aspect-ratio-3-15 bg-white" src="{{asset('storage/app/public/company-reliability')}}/{{$value->image}}" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                    <h3 class="text-muted">{{ translate('Upload_Icon') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn--primary mb-3 mx-4 px-3 text-uppercase">{{translate('save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        function uploadColorImage(thisData = null){
            if(thisData){
                document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
                document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
            }
        }
    </script>

@endpush
