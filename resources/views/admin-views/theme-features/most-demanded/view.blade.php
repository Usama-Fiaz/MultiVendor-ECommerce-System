@extends('layouts.back-end.app')

@section('title', translate('most_demanded'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/most_demnaded.png')}}" alt="">
            {{translate('most_demanded')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.most-demanded.store')}}" method="post" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" enctype="multipart/form-data">
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
                                            <option value="{{ $product->id }}">
                                                {{$product['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group md-2">
                                    <label for="name" class="title-color font-weight-medium">{{translate('banner')}}</label>
                                    <span class="text-info ml-1">( {{translate('ratio')}} {{translate('4')}}:{{translate('1')}} )</span>
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
                                            src="{{asset('public/assets/front-end/img/placeholder.png')}}" alt="banner image"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                            <h5 class="mb-0 text-capitalize d-flex gap-2">
                                {{ translate('most_demanded_table')}}
                                <span
                                    class="badge badge-soft-dark radius-50 fz-12">{{ $most_demanded_products->total() }}</span>
                            </h5>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div
                                class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                <!-- Search -->
                                <form action="{{route('admin.most-demanded.index')}}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search"
                                               class="form-control" value="{{ request('search') }}"
                                               placeholder="{{ translate('search_by_product_name')}}"
                                               aria-label="Search orders" >
                                        <button type="submit" class="btn btn--primary">
                                            {{ translate('search')}}
                                        </button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($most_demanded_products)>0)
                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th class="pl-xl-5">{{translate('SL')}}</th>
                                <th>{{translate('banner')}}</th>
                                <th>{{translate('product')}}</th>
                                <th class="text-center">{{translate('published')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($most_demanded_products as $key=>$most_demanded)
                                <tr id="data-{{$most_demanded->id}}">
                                    <td class="pl-xl-5">{{$most_demanded_products->firstItem()+ $key}}</td>
                                    <td>
                                        <img class="ratio-4:1" width="80"
                                            onerror="this.src='{{asset('public/assets/front-end/img/placeholder.png')}}'"
                                            src="{{asset('storage/app/public/most-demanded')}}/{{$most_demanded['banner']}}">
                                    </td>
                                    <td>{{ isset($most_demanded->product->name) ? $most_demanded->product->name : translate('no_product_found')}}</td>
                                    <td class="d-flex justify-content-center">
                                            @if(isset($most_demanded->product->status ) && $most_demanded->product->status == 1)
                                            <form action="{{route('admin.most-demanded.status-update')}}" method="post" id="most_demanded{{$most_demanded['id']}}_form" class="most_demanded_form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$most_demanded['id']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input" id="most_demanded{{$most_demanded['id']}}" name="status" value="1" {{ $most_demanded['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'most_demanded{{$most_demanded['id']}}','most-demanded-on.png','most-demanded-off.png','{{translate('Want_to_Turn_ON_Most_Demanded_Product_Status')}}','{{translate('Want_to_Turn_OFF_Most_Demanded_Product_Status')}}',`<p>{{translate('if_enabled_this_most_demanded_product_will_be_available_on_the_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_most_demanded_product_will_be_hidden_from_the_website_and_customer_app')}}</p>`)">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                            @else
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input status" name="status" id="{{$most_demanded->id}}" disabled>
                                                <span class="switcher_control opacity--40"></span>
                                            </label>
                                            @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                            title="{{ translate('edit')}}"
                                            href="{{route('admin.most-demanded.edit',[$most_demanded['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                            title="{{ translate('delete')}}"
                                            id="{{$most_demanded['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$most_demanded_products->links()}}
                    </div>
                </div>
                @endif
                @if(count($most_demanded_products)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160"
                             src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                             alt="Image Description">
                        <p class="mb-0 text-capitalize">{{ translate('no_data_to_show')}}</p>
                    </div>
                @endif
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

        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $('.most_demanded_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success("{{translate('status_updated_successfully')}}");
                    setTimeout(function (){
                        location.reload()
                    },1000);
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{translate('are_you_sure_delete_this_most_demanded_product')}}?",
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate("yes_delete_it")}}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.most-demanded.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function (response) {
                            console.log(response)
                            toastr.success('{{translate("most-demanded_product_deleted_successfully")}}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });

    </script>
@endpush
