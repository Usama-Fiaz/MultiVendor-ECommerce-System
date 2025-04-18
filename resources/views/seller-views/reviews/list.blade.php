@extends('layouts.back-end.app-seller')
@section('title', translate('review_List'))
@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img width="20" src="{{asset('/public/assets/back-end/img/product-review.png')}}" class="mb-1 mr-1" alt="">
                {{translate('product_reviews')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card card-body">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                    <h5 class="text-capitalize mb-0 d-flex gap-1">
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product" class="title-color">{{ translate('choose_Product') }}</label>
                            <select class="form-control" name="product_id">
                                <option value="" selected>
                                    --{{ translate('select_product') }}--</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product_id == $product->id ? 'selected' : '' }}>
                                        {{ Str::limit($product->name, 20) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer" class="title-color">{{ translate('choose_Customer') }}</label>
                            <select class="form-control" name="customer_id">
                                <option value="" selected>
                                    --{{ translate('select_customer') }}--</option>
                                @foreach ($customers as $item)
                                    <option value="{{ isset($item->id) ? $item->id : $customer_id }}"
                                        {{ $customer_id != null && $customer_id == $item->id ? 'selected' : '' }}>
                                        {{ Str::limit($item->f_name) }}
                                        {{ Str::limit($item->l_name) }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <label for="status" class="title-color">{{ translate('choose_Status') }}</label>
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
                        <div class="form-group">
                            <label for="from" class="title-color">{{ translate('from') }}</label>
                            <input type="date" name="from" id="from_date" value="{{ $from }}"
                                class="form-control"
                                title="{{ translate('from_Date') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to" class="title-color">{{ translate('to') }}</label>
                            <input type="date" name="to" id="to_date" value="{{ $to }}"
                                class="form-control"
                                title="{{ ucfirst(translate('to_date')) }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button id="filter" type="submit" class="btn btn--primary btn-block mt-5 filter">
                                <i class="tio-filter-list nav-icon"></i>{{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-outline--primary mt-5" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item" href="{{ route('seller.reviews.export', ['product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to]) }}">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-20">
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
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
                            @if ($review->product)
                                <tr>
                                    <td>
                                        {{ $reviews->firstItem()+$key }}
                                    </td>
                                    <td>
                                        <a class="title-color hover-c1" href="{{ route('seller.product.view', [$review['product_id']]) }}">
                                            {{ Str::limit($review->product['name'], 25) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($review->customer)
                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                        @else
                                            <label class="badge badge-soft-danger">{{ translate('customer_removed') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="badge badge-soft-info mb-0">
                                            <span class="fz-12 d-flex align-items-center gap-1">{{ $review->rating }} <i class="tio-star"></i>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            {{ $review->comment ? Str::limit($review->comment, 35) : 'No Comment Found' }}
                                        </div>
                                        <div class="gap-1">
                                        @if($review->attachment)
                                            @foreach (json_decode($review->attachment) as $img)
                                                <a class=""
                                                    href="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                    data-lightbox="mygallery">
                                                    <img clsss="p-2" width="60" height="60"
                                                        onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                                        src="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                        alt="Image">
                                                </a>
                                            @endforeach
                                        @endif
                                        </div>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($review->created_at)) }}</td>
                                    <td>
                                        <form action="{{ route('seller.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}" method="get" id="reviews_status{{$review['id']}}_form" class="reviews_status_form">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input" id="reviews_status{{$review['id']}}" {{ $review->status ? 'checked' : '' }} onclick="toogleStatusModal(event,'reviews_status{{$review['id']}}','customer-reviews-on.png','customer-reviews-off.png','{{translate('Want_to_Turn_ON_Customer_Reviews')}}','{{translate('Want_to_Turn_OFF_Customer_Reviews')}}',`<p>{{translate('if_enabled_anyone_can_see_this_review_on_the_user_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_review_will_be_hidden_from_the_user_website_and_customer_app')}}</p>`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $reviews->links() !!}
                </div>
            </div>
            <!-- End Pagination -->
        </div>
    </div>
@endsection
@push('script_2')
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
                    toastr.error('{{ translate("invalid_date_range") }}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

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
