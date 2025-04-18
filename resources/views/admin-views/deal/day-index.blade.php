@extends('layouts.back-end.app')

@section('title', translate('deal_Of_The_Day'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/deal_of_the_day.png')}}" alt="">
            {{translate('deal_of_the_day')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.deal.day')}}" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" method="post">
                        @csrf
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs w-fit-content mb-4">
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item text-capitalize">
                                    <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}"
                                       href="#"
                                       id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="form-group">
                            @foreach(json_decode($language) as $lang)
                                <div class="row {{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="col-md-12">
                                        <label for="name">{{ translate('title')}} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="title[]" class="form-control" id="title"
                                               placeholder="{{translate('ex')}} : {{translate('LUX')}}"
                                               {{$lang == $default_lang? 'required':''}}>
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                            @endforeach
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="name" class="title-color">{{ translate('products')}}</label>
                                    <input type="text" class="product_id" name="product_id" hidden>
                                    <div class="dropdown select-product-search w-100">
                                        <button class="form-control text-start dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{translate('select_Product')}}

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
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-5">{{ translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-5">{{ translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                            <h5 class="d-flex align-items-center gap-2">
                                {{ translate('deal_of_the_day')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{ $deals->total() }}</span>
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
                                        placeholder="{{translate('search_by_Title')}}" aria-label="Search orders" value="{{ $search }}" required>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ translate('SL')}}</th>
                            <th>{{ translate('title')}}</th>
                            <th>{{ translate('product_info')}}</th>
                            <th>{{ translate('status')}}</th>
                            <th class="text-center">{{ translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($deals as $k=>$deal)
                            <tr>
                                <th>{{$deals->firstItem()+ $k}}</th>
                                <td><a href="#" target="_blank" class="font-weight-semibold title-color hover-c1">{{$deal['title']}}</a></td>

                                <td>{{isset($deal->product)==true?$deal->product->name:translate("not_selected")}}</td>

                                <td>
                                    <form action="{{route('admin.deal.day-status-update')}}" method="post" id="deal_of_the_day{{$deal['id']}}_form" class="deal_of_the_day_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$deal['id']}}">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input" id="deal_of_the_day{{$deal['id']}}" name="status" value="1" {{ $deal['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'deal_of_the_day{{$deal['id']}}','deal-of-the-day-status-on.png','deal-of-the-day-status-off.png','{{translate('Want_to_Turn_ON_Deal_of_the_Day_Status')}}','{{translate('Want_to_Turn_OFF_Deal_of_the_Day_Status')}}',`<p>{{translate('if_enabled_this_deal_of_the_day_will_be_available_on_the_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_deal_of_the_day_will_be_hidden_from_the_website_and_customer_app')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-10">
                                        <a  title="{{ trans ('Edit')}}"
                                            href="{{route('admin.deal.day-update',[$deal['id']])}}"
                                            class="btn btn-outline--primary btn-sm edit">

                                            <i class="tio-edit"></i>
                                        </a>
                                        <a  title="{{ trans ('Delete')}}"
                                            class="btn btn-outline-danger btn-sm delete"
                                            id="{{$deal['id']}}">
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
                        {{$deals->links()}}
                    </div>
                </div>

                @if(count($deals)==0)
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
    <script>
        let selectProductSearch = $('.select-product-search');
        selectProductSearch.on('click', '.select-product-item', function () {
            let productName = $(this).find('.product-name').text();
            let productId = $(this).find('.product-id').text();
            selectProductSearch.find('button.dropdown-toggle').text(productName);
            $('.product_id').val(productId);
        })
    </script>
    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>

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

        $('.deal_of_the_day_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.day-status-update')}}",
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
    </script>

    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{translate('are_you_sure_delete_this')}}?",
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate("yes_delete_it")}}!',
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
                        url: "{{route('admin.deal.day-delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate("banner_deleted_successfully")}}');
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
@endpush
