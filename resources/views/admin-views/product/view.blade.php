@extends('layouts.back-end.app')

@section('title', translate('product_Preview'))

@push('css_or_js')
<style>
    .pair-list .key {
        min-width: 100px;
        --flex-basis: 100px;
        flex-basis: var(--flex-basis);
        text-wrap: nowrap;
    }
</style>
@endpush

@section('content')
    <div class="content container-fluid text-start">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-10 mb-3">
            <!-- Page Title -->
            <div class="">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
                    {{translate('product_details')}}
                </h2>
            </div>
            <!-- End Page Title -->
        </div>


        <!-- Card -->
        <div class="card card-top-bg-element">
            <div class="card-body">
                <div class="d-flex flex-wrap flex-lg-nowrap gap-3 justify-content-between">
                    <div class="media flex-wrap flex-sm-nowrap gap-3">
                        <a class="aspect-1 float-left overflow-hidden"

                            @if(file_exists(base_path("storage/app/public/product/thumbnail/".$product['thumbnail'])))
                                href="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                            @else
                                href="{{asset("public/assets/front-end/img/image-place-holder.png")}}"
                            @endif


                            data-lightbox="mygallery">
                            <img class="avatar avatar-170 rounded-0" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}" alt="Image Description">
                        </a>
                        <div class="d-block">
                            <div class="d-flex flex-wrap flex-sm-nowrap align-items-start gap-2 mb-2 min-h-50">

                                @if ($product->product_type == 'physical' && $product->color_image)
                                    @foreach (json_decode($product->color_image) as $key => $photo)
                                        <a class="aspect-1 float-left overflow-hidden"
                                            @if(file_exists(base_path("storage/app/public/product/".$photo->image_name)))
                                                href="{{asset("storage/app/public/product/$photo->image_name")}}"
                                            @else
                                                href="{{asset("public/assets/front-end/img/image-place-holder.png")}}"
                                            @endif data-lightbox="mygallery">

                                            <img width="50" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset("storage/app/public/product/$photo->image_name")}}" alt="Product image">
                                        </a>
                                    @endforeach
                                @else
                                    @foreach (json_decode($product->images) as $key => $photo)
                                        <a class="aspect-1 float-left overflow-hidden" href="{{asset("storage/app/public/product/$photo")}}" data-lightbox="mygallery">
                                            <img width="50" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset("storage/app/public/product/$photo")}}" alt="Product image">
                                        </a>
                                    @endforeach
                                @endif

                                @if ($product->denied_note && $product['request_status'] == 2)
                                    <div class="alert alert-danger bg-danger-light py-2" role="alert">
                                        <strong>{{translate('note')}} :</strong> {{$product->denied_note}}
                                    </div>
                                @endif
                            </div>

                            <div class="d-block">
                                <div class="d-flex">
                                    <h2 class="mb-2 pb-1 text-gulf-blue">{{$product['name']}}</h2>
                                    <a class="btn btn-outline--primary btn-sm square-btn mx-2 w-auto h-25"
                                        title="{{translate('edit')}}"
                                        href="{{route('admin.product.edit',[$product['id']])}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                </div>
                                <div class="d-flex gap-3 flex-wrap mb-3 lh-1">
                                    <a href="#" class="text-dark">{{count($product->order_details)}} {{translate('orders')}}</a>
                                    <span class="border-left"></span>
                                    <div class="review-hover position-relative cursor-pointer d-flex gap-2 align-items-center">
                                        <i class="tio-star"></i>
                                        <span>{{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}</span>

                                        <div class="review-details-popup">
                                            <h6 class="mb-2">{{translate('rating')}}</h6>
                                            <div class="">
                                                <ul class="list-unstyled list-unstyled-py-2 mb-0">
                                                    @php($total = $product->reviews->count())
                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($five=\App\CPU\Helpers::rating_count($product['id'],5))
                                                        <span
                                                            class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('5')}} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{$total==0?0:($five/$total)*100}}%;"
                                                                    aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($four=\App\CPU\Helpers::rating_count($product['id'],4))
                                                        <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('4')}} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{$total==0?0:($four/$total)*100}}%;"
                                                                    aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($three=\App\CPU\Helpers::rating_count($product['id'],3))
                                                        <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('3')}} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{$total==0?0:($three/$total)*100}}%;"
                                                                    aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span
                                                            class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($two=\App\CPU\Helpers::rating_count($product['id'],2))
                                                        <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('2')}} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{$total==0?0:($two/$total)*100}}%;"
                                                                    aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        @php($one=\App\CPU\Helpers::rating_count($product['id'],1))
                                                        <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{translate('1')}} {{ translate('star') }}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar"
                                                                    style="width: {{$total==0?0:($one/$total)*100}}%;"
                                                                    aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="border-left"></span>
                                    <a href="javascript:" class="text-dark">{{$product->reviews_count}} {{translate('ratings')}}</a>
                                    <span class="border-left"></span>
                                    <a href="javascript:" class="text-dark">{{$product->reviews->whereNotNull('comment')->count()}} {{translate('reviews')}}</a>
                                </div>

                                @if ($product_active)
                                    <a href="{{route('product',$product['slug'])}}" class="btn btn-outline--primary mr-1" target="_blank">
                                        <i class="tio-globe"></i>
                                        {{ translate('view_live') }}
                                    </a>
                                @endif
                                @if($product->digital_file_ready && file_exists(base_path('storage/app/public/product/digital-product/'.$product->digital_file_ready)))
                                <a href="{{ asset('storage/app/public/product/digital-product/'.$product->digital_file_ready) }}" class="btn btn-outline--primary mr-1" title="Download" download>
                                    <i class="tio-download"></i>
                                    {{ translate('download') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- approve btns --}}
                    @if($product['added_by'] == 'seller' && ($product['request_status'] == 0 || $product['request_status'] == 1))
                    <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                        <div>
                            <button class="btn btn-danger px-5" data-toggle="modal" data-target="#publishNoteModal">
                                {{translate('reject')}}
                            </button>

                        </div>
                        <div>
                            @if($product['request_status'] == 0)
                                <a href="{{route('admin.product.approve-status', ['id'=>$product['id']])}}" class="btn btn-success px-5">
                                    {{translate('approve')}}
                                </a>
                            @endif

                        </div>
                    </div>
                    @endif
                    @if($product['added_by'] == 'seller' && ($product['request_status'] == 2))
                    <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                        <div>
                            <button class="btn btn-danger px-5">
                                {{translate('rejected')}}
                            </button>

                        </div>
                    </div>
                    @endif
                </div>
                <hr>

                <div class="d-flex gap-3 flex-wrap">
                    <div class="border p-3 w-170">
                        <div class="d-flex flex-column mb-1">
                            <h6 class="font-weight-normal">{{translate('total_sold')}} :</h6>
                            <h3 class="text-primary fs-18">{{$product->order_delivered_sum_qty ?? 0}}</h3>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="font-weight-normal">{{translate('total_sold_amount')}} :</h6>
                            <h3 class="text-primary fs-18">{{\App\CPU\Helpers::currency_converter(($product->order_delivered_sum_price * $product->order_delivered_sum_qty) - $product->order_delivered_sum_discount)}}</h3>
                        </div>
                    </div>

                    <div class="row gy-3 flex-grow-1">
                        <div class="col-sm-6 col-xl-4">
                            <h4 class="mb-3">{{translate('general_information')}}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{translate('brand')}}</span>
                                    <span>:</span>
                                    <span class="value">
                                        {{isset($product->brand) ? $product->brand->default_name : translate('brand_not_found')}}
                                    </span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('category')}}</span>
                                    <span>:</span>
                                    <span class="value">
                                        {{isset($product->category) ? $product->category->default_name : translate('category_not_found')}}
                                    </span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('product_type')}}</span>
                                    <span>:</span>
                                    <span class="value">{{translate($product->product_type)}}</span>
                                </div>
                                @if($product->product_type == 'physical')
                                    <div>
                                        <span class="key text-nowrap">{{translate('current_Stock')}}</span>
                                        <span>:</span>
                                        <span class="value">{{$product->current_stock}}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="key text-nowrap">{{translate('SKU')}}</span>
                                    <span>:</span>
                                    <span class="value">{{$product->code}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <h4 class="mb-3">{{translate('price_information')}}</h4>

                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{translate('purchase_price')}}</span>
                                    <span>:</span>
                                    <span class="value">{{\App\CPU\Helpers::currency_converter($product->purchase_price)}}</span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('unit_price')}}</span>
                                    <span>:</span>
                                    <span class="value">{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('tax')}}</span>
                                    <span>:</span>
                                    @if ($product->tax_type =='percent')
                                        <span class="value">{{$product->tax}}% ({{$product->tax_model}})</span>
                                    @else
                                        <span class="value">{{\App\CPU\Helpers::currency_converter($product->tax)}} ({{$product->tax_model}})</span>
                                    @endif
                                </div>
                                @if($product->product_type == 'physical')
                                    <div>
                                        <span class="key text-nowrap">{{translate('shipping_cost')}}</span>
                                        <span>:</span>
                                        <span class="value">
                                            {{\App\CPU\Helpers::currency_converter($product->shipping_cost)}}
                                            @if ($product->multiply_qty == 1)
                                                ({{translate('multiply_with_quantity')}})
                                            @endif

                                        <span>
                                    </div>
                                @endif
                                @if($product->discount > 0)
                                    <div>
                                        <span class="key text-nowrap">{{translate('discount')}}</span>
                                        <span>:</span>
                                        @if ($product->discount_type == 'percent')
                                            <span class="value">{{$product->discount}}%</span>
                                        @else
                                            <span class="value">{{\App\CPU\Helpers::currency_converter($product->discount)}}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($product->product_type == 'physical' && count(json_decode($product->choice_options)) >0 || count(json_decode($product->colors)) >0 )
                            <div class="col-sm-6 col-xl-4">
                                <h4 class="mb-3">{{translate('available_variations')}}</h4>

                                <div class="pair-list">
                                    @if (json_decode($product->choice_options) != null)
                                        @foreach (json_decode($product->choice_options) as $key => $value)
                                        <div>
                                            @if (array_filter($value->options) != null)
                                                <span class="key text-nowrap">{{translate($value->title)}}</span>
                                                <span>:</span>
                                                <span class="value">
                                                    @foreach ($value->options as $key =>$option)
                                                        {{$option}}
                                                        @if ($key === array_key_last(($value->options)))@break @endif
                                                        ,
                                                    @endforeach
                                                </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    @endif

                                    @if (json_decode($product->colors) != null)
                                        <div>
                                            <span class="key text-nowrap">{{translate('color')}}</span>
                                            <span>:</span>
                                            <span class="value">
                                                @foreach (json_decode($product->colors) as $key => $value)
                                                    {{ \App\CPU\get_color_name($value) }}
                                                    @if ($key === array_key_last(json_decode($product->colors)))@break @endif
                                                    ,
                                                @endforeach
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="card mt-3">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('reviewer')}}</th>
                        <th>{{translate('rating')}}</th>
                        <th>{{translate('review')}}</th>
                        <th>{{translate('date')}}</th>
                        <th>{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($reviews as $key=>$review)
                        @if(isset($review->customer))
                        <tr>
                            <td>{{$reviews->firstItem()+$key}}</td>
                            <td>
                                <a class="d-flex align-items-center"
                                   href="{{route('admin.customer.view',[$review['customer_id']])}}">
                                    <div class="avatar rounded">
                                        <img
                                            class="avatar-img"
                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                            src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                            alt="Image Description">
                                    </div>
                                    <div class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{$review->customer->email??""}}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 text-primary">
                                    <i class="tio-star"></i>
                                    <span>{{$review->rating}}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-wrap max-w-400 min-w-200">
                                    <p>
                                        {{$review['comment']}}
                                    </p>
                                    @if(json_decode($review->attachment))
                                        @foreach (json_decode($review->attachment) as $img)
                                            <a class="aspect-1 float-left overflow-hidden" href="{{asset('storage/app/public/review')}}/{{$img}}" data-lightbox="mygallery">
                                                <img class="p-2" width="60" height="60" src="{{asset('storage/app/public/review')}}/{{$img}}" alt="" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'">
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{date('d M Y H:i:s',strtotime($review['updated_at']))}}
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}" method="get" id="reviews_status{{$review['id']}}_form" class="reviews_status_form">
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
            <!-- End Table -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $reviews->links() !!}
                </div>
            </div>

            @if(count($reviews)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                    <p class="mb-0">{{translate('no_data_to_show')}}</p>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="publishNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('denied_note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-group" action="{{ route('admin.product.deny', ['id'=>$product['id']]) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <textarea class="form-control" name="denied_note" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}
                        </button>
                        <button type="submit" class="btn btn--primary">{{translate('save_changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
@endsection

@push('script_2')
    <script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });
        $(document).ready(function () {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>
@endpush
