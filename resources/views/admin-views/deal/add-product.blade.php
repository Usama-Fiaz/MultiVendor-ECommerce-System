@extends('layouts.back-end.app')
@section('title', translate('deal_Product'))
@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize">
            <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" class="mb-1 mr-1" alt="">
            {{translate('add_new_product')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-capitalize">{{$deal['title']}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.deal.add-product',[$deal['id']])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="name" class="title-color">{{ translate('products')}}</label>
                                    <div class="dropdown select-product-search w-100">
                                        <input type="text" class="product_id" name="product_id" hidden>
                                        <button class="form-control text-start dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{translate('select_Product')}}

                                        </button>
                                        <div class="dropdown-menu w-100 px-2">
                                            <div class="search-form mb-3">
                                                <button type="button" class="btn"><i class="tio-search"></i></button>
                                                <input type="text" class="js-form-search form-control search-bar-input" onkeyup="search_product()" placeholder="{{translate('search menu')}}...">
                                            </div>
                                            <div class="d-flex flex-column gap-3 max-h-200 overflow-y-auto overflow-x-hidden search-result-box">
                                                @foreach ($products as $key => $product)
                                                    <div class="select-product-item media gap-3 border-bottom pb-2 cursor-pointer">
                                                        <img class="avatar avatar-xl border" width="75"
                                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                                         alt="">
                                                        <div class="media-body d-flex flex-column gap-1">
                                                            <h6 class="product-id" hidden>{{$product['id']}}</h6>
                                                            <h6 class="fz-13 mb-1 text-truncate custom-width product-name">{{$product['name']}}</h6>
                                                            <div class="fz-10">{{translate('category')}} : {{isset($product->category) ? $product->category->name : translate('category_not_found') }}</div>
                                                            <div class="fz-10">{{translate('brand')}} : {{isset($product->brand) ? $product->brand->name : translate('brands_not_found') }}</div>
                                                            @if ($product->added_by == "seller")
                                                                <div class="fz-10">{{translate('shop')}} : {{isset($product->seller) ? $product->seller->shop->name : translate('shop_not_found') }}</div>
                                                            @else
                                                                <div class="fz-10">{{translate('shop')}} : {{$web_config['name']->value}}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn--primary px-4">{{ translate('add')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <h5 class="mb-0 text-capitalize">
                        {{ translate('product_Table')}}
                        <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $deal_products->total() }}</span>
                    </h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" cellspacing="0">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{ translate('name')}}</th>
                                <th>{{ translate('price')}}</th>
                                <th class="text-center">{{ translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach($deal_products as $k=>$product)
                            <tr>
                                <td>{{$deal_products->firstitem()+$k}}</td>
                                <td><a href="#" target="_blank" class="font-weight-semibold title-color hover-c1">{{$product['name']}}</a></td>
                                <td>{{\App\CPU\BackEndHelper::usd_to_currency($product['unit_price'])}}</td>

                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a  title="{{ translate ('delete')}}"
                                            class="btn btn-outline-danger btn-sm delete"
                                            id="{{$product['id']}}">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table>
                        <tfoot>
                            {!! $deal_products->links() !!}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
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

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{translate("status_updated_successfully")}}');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{translate('are_you_sure_remove_this_product')}}?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{translate('yes_delete_it')}}!",
                cancelButtonText: '{{ translate("cancel") }}',
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
                        url: "{{route('admin.deal.delete-product')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function (data) {
                            toastr.success("{{translate('product_removed_successfully')}}");
                            location.reload();
                        }
                    });
                }
            })
        });


        /*Serach product */
        function search_product(){
            let name = $(".search-bar-input").val();
            if (name.length >0) {
                $.get("{{route('admin.deal.search-product')}}",{name:name},(response)=>{
                    $('.search-result-box').empty().html(response.result);
                })
            }
        }
        </script>
         <script>
            let selectProductSearch = $('.select-product-search');
            selectProductSearch.on('click', '.select-product-item', function () {
                let productName = $(this).find('.product-name').text();
                let productId = $(this).find('.product-id').text();
                selectProductSearch.find('button.dropdown-toggle').text(productName);
                $('.product_id').val(productId);
            })
        </script>
@endpush
