@extends('layouts.back-end.app-seller')

@section('title',translate('product_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/products.png')}}" alt="">
                {{translate('products')}}
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $products->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->
        <!-- Filter -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('seller.product.list')}}"  method="GET">
                    <input type="hidden" value="{{ request('status') }}" name="status">
                    <div class="row gx-2">
                        <div class="col-12">
                            <h4 class="mb-3">{{translate('filter_Products')}}</h4>
                        </div>
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
                                <select class="js-select2-custom form-control" name="category_id"
                                        onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-category-select','select')">
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
                                        onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
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
                                <a href="{{route('seller.product.list')}}" class="btn btn-secondary px-5">
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
                                <form action="{{ url()->current() }}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{translate('search_by_Product_Name')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
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
                                            <a class="dropdown-item"
                                            href="{{ route('seller.product.bulk-export', ['brand_id'=>request('brand_id'),'category_id'=>request('category_id'),'sub_category_id'=>request('sub_category_id'),'sub_sub_category_id'=>request('sub_sub_category_id')]) }}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{route('seller.product.stock-limit-list',['in_house', ''])}}" class="btn btn-info">
                                    <i class="tio-add-circle"></i>
                                    <span class="text">{{translate('limited_Stocks')}}</span>
                                </a>
                                <a href="{{route('seller.product.add-new')}}" class="btn btn--primary">
                                    <i class="tio-add"></i>
                                    <span class="text">{{translate('add_new_product')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('product_Name')}}</th>
                                <th>{{translate('product_Type')}}</th>
                                <th>{{translate('purchase_price')}}</th>
                                <th>{{translate('selling_price')}}</th>
                                <th>{{translate('verify_status')}}</th>
                                <th>{{translate('active_Status')}}</th>
                                <th class="text-center __w-5px">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $k=>$p)
                                <tr>
                                    <th scope="row">{{$products->firstitem()+ $k}}</th>
                                    <td>
                                        <a href="{{route('seller.product.view',[$p['id']])}}" class="media align-items-center gap-2 w-max-content">
                                            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$p['thumbnail']}}"
                                                 onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'"class="avatar border" alt="">
                                            <span class="media-body title-color hover-c1">
                                                {{\Illuminate\Support\Str::limit($p['name'],30)}}
                                            </span>
                                        </a>
                                    </td>
                                    <td>{{ ucfirst($p['product_type']) }}</td>
                                    <td>
                                        {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price']))}}
                                    </td>
                                    <td>
                                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price']))}}
                                    </td>
                                    <td>
                                        @if($p->request_status == 0)
                                            <label class="badge badge-soft-warning">{{translate('new_Request')}}</label>
                                        @elseif($p->request_status == 1)
                                            <label class="badge badge-soft-success">{{translate('approved')}}</label>
                                        @elseif($p->request_status == 2)
                                            <label class="badge badge-soft-danger">{{translate('denied')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @php($product_name = str_replace("'",'`',$p['name']))
                                        <form action="{{route('seller.product.status-update')}}" method="post" id="product_status{{$p['id']}}_form" class="product_status_form">
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
                                        <div class="d-flex gap-10">
                                            <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('barcode') }}"
                                                href="{{ route('seller.product.barcode', [$p['id']]) }}">
                                                <i class="tio-barcode"></i>
                                            </a>

                                            <a class="btn btn-outline-info btn-sm square-btn"
                                                title="{{translate('view')}}"
                                                href="{{route('seller.product.view',[$p['id']])}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                            <a  class="btn btn-outline-primary btn-sm square-btn"
                                                title="{{translate('edit')}}"
                                                href="{{route('seller.product.edit',[$p['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a  class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                                title="{{translate('delete')}}"
                                                onclick="form_alert('product-{{$p['id']}}','{{translate('want_to_delete_this_item')}} ?')">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                        <form action="{{route('seller.product.delete',[$p['id']])}}"
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
                            {{$products->links()}}
                        </div>
                    </div>

                    @if(count($products)==0)
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
                url: "{{route('seller.product.status-update')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{translate("status_updated_successfully")}}');
                    }
                    else if(data.success == false) {
                        toastr.error('{{translate("status_updated_failed.")}}{{translate("product_must_be_approved")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
