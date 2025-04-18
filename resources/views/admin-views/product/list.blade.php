@extends('layouts.back-end.app')

@section('title', translate('product_List'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
            @if($type == 'in_house')
                {{translate('in_House_Product_List')}}
            @elseif($type == 'seller')
                {{translate('seller_Product_List')}}
            @endif
            <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $pro->total() }}</span>
        </h2>
    </div>
    <!-- End Page Title -->
    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.product.list',['type'=>request('type')])}}"  method="GET">
                <input type="hidden" value="{{ request('status') }}" name="status">
                <div class="row gx-2">
                    <div class="col-12">
                        <h4 class="mb-3">{{translate('filter_Products')}}</h4>
                    </div>
                    @if (request('type') == 'seller')
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color" for="store">{{translate('store')}}</label>
                                <select name="seller_id"  class="form-control text-capitalize">
                                    <option value=""  selected>{{translate('all_store')}}</option>
                                    @foreach ($sellers as $seller)
                                        <option value="{{$seller->id}}"{{request('seller_id')==$seller->id ? 'selected' :''}}>
                                            {{ $seller->shop->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label class="title-color" for="store">{{translate('brand')}}</label>
                            <select name="brand_id"  class="js-select2-custom form-control text-capitalize">
                                <option value="" selected>{{translate('all_brand')}}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}" {{request('brand_id')==$brand->id ? 'selected' :''}}>{{ $brand->default_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name" class="title-color">{{ translate('category') }}</label>
                            <select class="js-select2-custom form-control" name="category_id" onchange="getRequest('{{ url('/') }}/admin/product/get-categories?parent_id='+this.value,'sub-category-select','select')">
                                <option value="{{ old('category_id') }}" selected disabled>{{ translate('select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category['id'] }}"
                                        {{ request('category_id') == $category['id'] ? 'selected' : '' }}>
                                        {{ $category['defaultName'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name" class="title-color">{{ translate('sub_Category') }}</label>
                            <select class="js-select2-custom form-control" name="sub_category_id"
                                    id="sub-category-select"
                                    onchange="getRequest('{{ url('/') }}/admin/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
                                    <option value="{{request('sub_category_id') != null ? request('sub_category_id') : null}}" selected {{request('sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_category_id') != null ? $sub_category['defaultName']: translate('select_Sub_Category') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name" class="title-color">{{ translate('sub_Sub_Category') }}</label>
                            <select class="js-select2-custom form-control" name="sub_sub_category_id"
                                    id="sub-sub-category-select">
                                    <option value="{{request('sub_sub_category_id') != null ? request('sub_sub_category_id') : null}}" selected {{request('sub_sub_category_id') != null ? '' : 'disabled'}}>{{request('sub_sub_category_id') != null ? $sub_sub_category['defaultName'] : translate('select_Sub_Sub_Category') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.product.list',['type'=>request('type')])}}" class="btn btn-secondary px-5">
                                {{translate('reset')}}
                            </a>
                            <button type="submit" class="btn btn--primary px-5" onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                {{translate('show_data')}}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Filter -->

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <!-- Search -->
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-custom input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                           placeholder="{{translate('search_Product_Name')}}" aria-label="Search orders"
                                           value="{{ request('search') }}" >
                                    <input type="hidden" value="{{ request('status') }}" name="status">
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                        <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">

                                <div>
                                    <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.product.export-excel',['type'=>request('type')])}}?brand_id={{request('brand_id')}}&search={{ request('search') }}&category_id={{request('category_id')}}&sub_category_id={{request('sub_category_id')}}&sub_sub_category_id={{request('sub_sub_category_id')}}&seller_id={{request('seller_id')}}&status={{request('status')}}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @if($type == 'in_house')
                                <a href="{{route('admin.product.stock-limit-list',['in_house'])}}" class="btn btn-info">
                                    <span class="text">{{translate('limited_Sotcks')}}</span>
                                </a>
                                <a href="{{route('admin.product.add-new')}}" class="btn btn--primary">
                                    <i class="tio-add"></i>
                                    <span class="text">{{translate('add_new_product')}}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('product Name')}}</th>
                                <th class="text-right">{{translate('product Type')}}</th>
                                <th class="text-right">{{translate('purchase_price')}}</th>
                                <th class="text-right">{{translate('selling_price')}}</th>
                                <th class="text-center">{{translate('show_as_featured')}}</th>
                                <th class="text-center">{{translate('active_status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pro as $k=>$p)
                            <tr>
                                <th scope="row">{{$pro->firstItem()+$k}}</th>
                                <td>
                                    <a href="{{route('admin.product.view',[$p['id']])}}" class="media align-items-center gap-2">
                                        <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$p['thumbnail']}}"
                                             onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'"class="avatar border" alt="">
                                        <span class="media-body title-color hover-c1">
                                            {{\Illuminate\Support\Str::limit($p['name'],20)}}
                                        </span>
                                    </a>
                                </td>
                                <td class="text-right">
                                    {{translate(str_replace('_',' ',$p['product_type']))}}
                                </td>
                                <td class="text-right">
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price']))}}
                                </td>
                                <td class="text-right">
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price']))}}
                                </td>
                                <td class="text-center">

                                    @php($product_name = str_replace("'",'`',$p['name']))
                                    <form action="{{route('admin.product.featured-status')}}" method="post" id="product_featured{{$p['id']}}_form" class="product_featured_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$p['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input" id="product_featured{{$p['id']}}" name="status" value="1" {{ $p['featured'] == 1 ? 'checked':'' }}
                                                onclick="toogleStatusModal(event,'product_featured{{$p['id']}}',
                                                'product-status-on.png','product-status-off.png',
                                                '{{translate('Want_to_Add')}} {{$product_name}} {{translate('to_the_featured_section')}}',
                                                '{{translate('Want_to_Remove')}} {{$product_name}} {{translate('to_the_featured_section')}}',
                                                `<p>{{translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app')}}</p>`,
                                                `<p>{{translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>

                                </td>
                                <td class="text-center">
                                    <form action="{{route('admin.product.status-update')}}" method="post" id="product_status{{$p['id']}}_form" class="product_status_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$p['id']}}">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input" id="product_status{{$p['id']}}" name="status" value="1" {{ $p['status'] == 1 ? 'checked':'' }}
                                                onclick="toogleStatusModal(event,'product_status{{$p['id']}}',
                                                'product-status-on.png','product-status-off.png',
                                                '{{translate('Want_to_Turn_ON')}} {{$product_name}} {{translate('status')}}',
                                                '{{translate('Want_to_Turn_OFF')}} {{$product_name}} {{translate('status')}}',
                                                `<p>{{translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app')}}</p>`,
                                                `<p>{{translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('barcode') }}"
                                            href="{{ route('admin.product.barcode', [$p['id']]) }}">
                                            <i class="tio-barcode"></i>
                                        </a>
                                        <a class="btn btn-outline-info btn-sm square-btn" title="View" href="{{route('admin.product.view',[$p['id']])}}">
                                            <i class="tio-invisible"></i>
                                        </a>
                                        <a class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{translate('edit')}}"
                                            href="{{route('admin.product.edit',[$p['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                            title="{{translate('delete')}}"
                                            onclick="form_alert('product-{{$p['id']}}','{{translate('want_to_delete_this_item?')}}')">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                    <form action="{{route('admin.product.delete',[$p['id']])}}"
                                            method="post" id="product-{{$p['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$pro->links()}}
                    </div>
                </div>

                @if(count($pro)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                        <p class="mb-0">{{translate('no_data_to_show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        function getRequest(route, id, type) {
            $('#sub-sub-category-select').empty().append('<option value="null" selected disabled>---{{ translate('select')}}---</option>');
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $('.product_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.product.status-update')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{translate("status_updated_successfully")}}');
                    }
                    else if(data.success == false) {
                        toastr.error('{{translate("Status_updated_failed.")}} {{translate("Product_must_be_approved")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        $('.product_featured_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.product.featured-status')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success('{{translate("featured_status_updated_successfully")}}');
                }
            });
        });
    </script>
@endpush
