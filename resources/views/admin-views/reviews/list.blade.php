@extends('layouts.back-end.app')

@section('title', translate('review_List'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{asset('/public/assets/back-end/img/customer_review.png')}}" alt="">
                {{translate('customer_reviews')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card card-body">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                    <h5 class="text-capitalize d-flex gap-2 align-items-center">
                        {{ translate('review_table') }}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $reviews->total() }}</span>
                    </h5>
                </div>
                <div class="col-sm-8 col-md-6 col-lg-4">
                    <!-- Search -->
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                placeholder="{{ translate('search_by_Product_or_Customer') }}"
                                aria-label="Search orders" value="{{ $search }}" required>
                            <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                        </div>
                    </form>
                    <!-- End Search -->
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET">
                <div class="row gy-3 align-items-end">
                    <div class="col-md-4">

                        <label for="name" class="title-color">{{ translate('products')}}</label>
                        <div class="dropdown select-product-search w-100">
                            <input type="text" class="product_id" name="product_id" value="{{request('product_id')}}" hidden>
                            <button class="form-control text-start dropdown-toggle text-truncate " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{request('product_id') !=null ? $product['name']: translate('select_Product')}}
                            </button>
                            <div class="dropdown-menu w-100 px-2">
                                <div class="search-form mb-3">
                                    <button type="button" class="btn"><i class="tio-search"></i></button>
                                    <input type="text" class="js-form-search form-control search-bar-input" onkeyup="search_product()" placeholder="{{translate('search menu')}}...">
                                </div>
                                <div class="d-flex flex-column gap-3 max-h-40vh overflow-y-auto overflow-x-hidden search-result-box">
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
                    <div class="col-md-4">
                        <label class="title-color" for="customer">{{translate('customer')}}</label>

                        <input type="hidden" id='customer_id'  name="customer_id" value="{{request('customer_id') ? request('customer_id') : 'all'}}">
                        <select  onchange="customer_id_value(this.value)"
                        data-placeholder="@if($customer == 'all')
                                            {{translate('all_customer')}}
                                        @else
                                            {{$customer->name ? $customer->name : $customer->f_name.' '.$customer->l_name.' '.'('.$customer->phone.')'}}
                                        @endif"
                        class="js-data-example-ajax form-control form-ellipsis">
                            <option value="all">{{translate('all_customer')}}</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div>
                            <label for="status" class="title-color d-flex">{{ translate('choose') }}
                                {{ translate('status') }}</label>
                            <select class="form-control" name="status">
                                <option value="" selected>
                                    --{{ translate('select_status') }}--</option>
                                <option value="1" {{ $status != null && $status == 1 ? 'selected' : '' }}>
                                    {{ translate('active') }}</option>
                                <option value="0" {{ $status != null && $status == 0 ? 'selected' : '' }}>
                                    {{ translate('inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="from" class="title-color d-flex">{{ translate('from') }}</label>
                            <input type="date" name="from" id="from_date" value="{{ $from }}"
                                class="form-control"
                                title="{{ translate('from_date') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div>
                            <label for="to" class="title-color d-flex">{{ translate('to') }}</label>
                            <input type="date" name="to" id="to_date" value="{{ $to }}"
                                class="form-control"
                                title="{{ ucfirst(translate('to_date')) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button id="filter" type="submit" class="btn btn--primary btn-block filter">
                                <i class="tio-filter-list nav-icon"></i>
                                {{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <button type="button" class="btn btn-outline--primary text-nowrap btn-block" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{ translate('export') }}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.reviews.export', ['search'=>request('search'), 'product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to]) }}">
                                        <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                        {{ translate('excel') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- End Page Header -->
        <div class="card mt-20">
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('product') }}</th>
                            <th>{{ translate('customer') }}</th>
                            <th>{{ translate('rating') }}</th>
                            <th>{{ translate('review') }}</th>
                            <th>{{ translate('date') }}</th>
                            <th class="text-center">{{ translate('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $key => $review)
                            <tr>
                                <td>
                                    {{ $reviews->firstItem()+$key }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.product.view', [$review['product_id']]) }}" class="title-color hover-c1">
                                        {{isset($review->product) ?Str::limit($review->product['name'], 25) : translate('product_not_found') }}
                                    </a>
                                </td>
                                <td>
                                    @if ($review->customer)
                                        <a href="{{ route('admin.customer.view', [$review->customer_id]) }}" class="title-color hover-c1">
                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                        </a>
                                    @else
                                        <label
                                            class="badge badge-soft-danger">{{ translate('customer_removed') }}</label>
                                    @endif
                                </td>
                                <td>
                                    <label class="badge badge-soft-info mb-0">
                                        <span class="fz-12 d-flex align-items-center gap-1">{{ $review->rating }} <i class="tio-star"></i>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="gap-1">
                                        <div>{{ $review->comment ? Str::limit($review->comment, 35) : translate('no_comment_found') }}</div>
                                        <br>
                                        @if($review->attachment)
                                            <div class="d-flex flex-wrap">
                                                @foreach (json_decode($review->attachment) as $img)
                                                    <a href="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                        data-lightbox="mygallery">
                                                        <img width="60" height="60" src="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                        onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'"
                                                                alt="Image">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ date('d M Y', strtotime($review->created_at)) }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}" method="get" id="reviews_status{{$review['id']}}_form" class="reviews_status_form">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input" id="reviews_status{{$review['id']}}" {{ $review->status ? 'checked' : '' }} onclick="toogleStatusModal(event,'reviews_status{{$review['id']}}','customer-reviews-on.png','customer-reviews-off.png','{{translate('Want_to_Turn_ON_Customer_Reviews')}}','{{translate('Want_to_Turn_OFF_Customer_Reviews')}}',`<p>{{translate('if_enabled_anyone_can_see_this_review_on_the_user_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_review_will_be_hidden_from_the_user_website_and_customer_app')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
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
                    {!! $reviews->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function customer_id_value(id){
            $('#customer_id').empty().val(id);
        }
        $('.js-data-example-ajax').select2({
        // Need Add a initial option
            data: [{ id: '', text: 'Select your option', disabled: true, selected: true }],
            ajax: {
                url: '{{route('admin.reviews.customer-list-search')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    return {
                    results: data

                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }

            }
        });
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
    <script>
        $(document).on('change', '#from_date', function() {
            from_date = $(this).val();
            if (from_date) {
                $("#to_date").prop('required', true);
            }
        });
    </script>
    <script>
        $('#from_date , #to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error("{{translate('invalid_date_range')}}!", Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
        function search_product(){
            let name = $(".search-bar-input").val();
            if (name.length >0) {
                $.get("{{route('admin.deal.search-product')}}",{name:name},(response)=>{
                    $('.search-result-box').empty().html(response.result);
                })
            }
        }

        let selectProductSearch = $('.select-product-search');
            selectProductSearch.on('click', '.select-product-item', function () {
                let productName = $(this).find('.product-name').text();
                let productId = $(this).find('.product-id').text();
                selectProductSearch.find('button.dropdown-toggle').text(productName);
                $('.product_id').val(productId);
            })
    </script>
    <script>
        $('.reviews_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success(data.message);
                }
            });
        });
    </script>
@endpush
