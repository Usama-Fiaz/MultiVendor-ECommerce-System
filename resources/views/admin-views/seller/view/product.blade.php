@extends('layouts.back-end.app')

@section('title',$seller->shop ? $seller->shop->name : translate("shop_name_not_found"))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('seller_details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Heading -->
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4">
                        <div class="flex-start">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div>{{translate('seller_request_for_open_a_shop.')}}</div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                        class="btn btn--primary btn-sm">{{translate('approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        class="btn btn-danger btn-sm">{{translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">{{ $seller->shop ? $seller->shop->name : translate("shop_Name")." : ".translate("update_Please") }}</h1>
                </div>

            </div>
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.sellers.view',$seller->id) }}">{{translate('shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{translate('order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{translate('product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{translate('setting')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{translate('transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{translate('review')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header-->

        <div class="tab-content">
            <div class="tab-pane fade show active" id="product">
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="px-3 py-4">
                                <h5 class="mb-0 d-flex align-items-center gap-2">
                                    {{translate('products')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{$products->total()}}</span>
                                </h5>
                            </div>

                            <div class="table-responsive datatable-custom">
                                <table id="columnSearchDatatable"
                                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('product Name')}}</th>
                                        <th>{{translate('purchase_price')}}</th>
                                        <th>{{translate('selling_price')}}</th>
                                        <th class="text-center">{{translate('featured')}}</th>
                                        <th class="text-center">{{translate('active_status')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                    @foreach($products as $k=>$p)
                                        <tr>
                                            <td>{{$products->firstItem()+$k}}</td>
                                            <td>
                                                <a href="{{route('admin.product.view',[$p['id']])}}"
                                                   class="title-color hover-c1">
                                                    {{substr($p['name'],0,20)}}{{strlen($p['name'])>20?'...':''}}
                                                </a>
                                            </td>
                                            <td>
                                                {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price']))}}
                                            </td>
                                            <td>
                                                {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price']))}}
                                            </td>
                                            <td>
                                                <form action="{{route('admin.product.featured-status')}}" method="post" id="product_featured{{$p['id']}}_form" class="product_featured_form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$p['id']}}">
                                                    <label class="switcher mx-auto">
                                                        <input type="checkbox" class="switcher_input" id="product_featured{{$p['id']}}" name="status" value="1" {{ $p['featured'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'product_featured{{$p['id']}}','product-status-on.png','product-status-off.png','{{translate('Want_to_Add')}} {{$p['name']}} {{translate('to_the_featured_section')}}','{{translate('Want_to_Remove')}} {{$p['name']}} {{translate('to_the_featured_section')}}',`<p>{{translate('if_enabled_this_product_will_be_shown_in_the_featured_product_on_the_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_product_will_be_removed_from_the_featured_product_section_of_the_website_and_customer_app')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{route('admin.product.status-update')}}" method="post" id="product_status{{$p['id']}}_form" class="product_status_form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$p['id']}}">
                                                    <label class="switcher mx-auto">
                                                        <input type="checkbox" class="switcher_input" id="product_status{{$p['id']}}" name="status" value="1" {{ $p['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'product_status{{$p['id']}}','product-status-on.png','product-status-off.png','{{translate('Want_to_Turn_ON')}} {{$p['name']}} {{translate('status')}}','{{translate('Want_to_Turn_OFF')}} {{$p['name']}} {{translate('status')}}',`<p>{{translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-10">
                                                    <a class="btn btn-outline--primary btn-sm square-btn"
                                                       href="{{route('admin.product.edit',[$p['id']])}}">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger btn-sm square-btn"
                                                       href="javascript:"
                                                       onclick="form_alert('product-{{$p['id']}}','{{ translate('Want_to_delete_this_item') }} ?')">
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
                                    {{$products->links()}}
                                </div>
                            </div>

                            @if(count($products)==0)
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160"
                                         src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                         alt="Image Description">
                                    <p class="mb-0">{{translate('no_data_to_show')}}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
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
