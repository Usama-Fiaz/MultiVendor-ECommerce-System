@extends('layouts.back-end.app-seller')

@section('title', translate('product_add'))

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #dedede;
            border: 1px solid #dedede;
            border-radius: 2px;
            color: #222;
            display: flex;
            gap: 4px;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img src="http://localhost/6valley/public/assets/back-end/img/all-orders.png" alt="">
                {{ translate('add_new_product') }}
            </h2>
        </div>

        <!-- Form -->
        <form class="product-form text-start" action="{{ route('seller.product.add-new') }}" method="POST" enctype="multipart/form-data" id="product_form">
            @csrf
            <div class="card">
                <div class="px-4 pt-3">
                    @php($language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'en')

                    @php($default_lang = json_decode($language)[0])
                    <ul class="nav nav-tabs w-fit-content mb-4">
                        @foreach (json_decode($language) as $lang)
                            <li class="nav-item">
                                <a class="nav-link text-capitalize lang_link {{ $lang == $default_lang ? 'active' : '' }}"
                                    href="#"
                                    id="{{ $lang }}-link">{{ \App\CPU\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-body">
                    @foreach (json_decode($language) as $lang)
                        <div class="{{ $lang != $default_lang ? 'd-none' : '' }} lang_form"
                                id="{{ $lang }}-form">
                            <div class="form-group">
                                <label class="title-color"
                                        for="{{ $lang }}_name">{{ translate('product_name') }}
                                    ({{ strtoupper($lang) }})
                                </label>
                                <input type="text" {{ $lang == $default_lang ? 'required' : '' }} name="name[]"
                                        id="{{ $lang }}_name" class="form-control" placeholder="New Product">
                            </div>
                            <input type="hidden" name="lang[]" value="{{ $lang }}">
                            <div class="form-group pt-4">
                                <label class="title-color"
                                        for="{{ $lang }}_description">{{ translate('description') }}
                                    ({{ strtoupper($lang) }})</label>
                                <textarea name="description[]" class="textarea editor-textarea">{{ old('details') }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- general setup -->
            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('general_setup') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('category') }}</label>
                                <select class="js-select2-custom form-control" name="category_id"
                                        onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-category-select','select')"
                                        required>
                                    <option value="{{ old('category_id') }}" selected disabled>{{ translate('select_category') }}</option>
                                    @foreach ($cat as $c)
                                        <option value="{{ $c['id'] }}"
                                            {{ old('name') == $c['id'] ? 'selected' : '' }}>
                                            {{ $c['defaultName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Category') }}</label>
                                <select class="js-select2-custom form-control" name="sub_category_id"
                                        id="sub-category-select"
                                        onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
                                        <option value="{{ null }}" selected disabled>{{ translate('select_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label for="name" class="title-color">{{ translate('sub_Sub_Category') }}</label>
                                <select class="js-select2-custom form-control" name="sub_sub_category_id"
                                        id="sub-sub-category-select">
                                        <option value="{{ null }}" selected disabled>{{ translate('select_Sub_Sub_Category') }}</option>
                                </select>
                            </div>
                        </div>
                        @if($brand_setting)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color">{{ translate('brand') }}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="brand_id" required>
                                        <option value="{{ null }}" selected disabled>{{ translate('select_Brand') }}</option>
                                        @foreach ($br as $b)
                                            <option value="{{ $b['id'] }}">{{ $b['defaultName'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{ translate('product_type') }}</label>
                                <select name="product_type" id="product_type" class="form-control" required>
                                    <option value="physical" selected>{{ translate('physical') }}</option>
                                    @if($digital_product_setting)
                                        <option value="digital">{{ translate('digital') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="digital_product_type_show">
                            <div class="form-group">
                                <label for="digital_product_type" class="title-color">{{ translate("delivery_type") }}</label>
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"
                                    title="{{translate('for_“Ready_Product”_deliveries,_customers_can_pay_&_instantly_download_pre-uploaded_digital_products._For_“Ready_After_Sale”_deliveries,_customers_pay_first,_then_seller_uploads_the_digital_products_that_become_available_to_customers_for_download')}}">
                                    <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                                <select name="digital_product_type" id="digital_product_type" class="form-control" required>
                                    <option value="{{ old('category_id') }}" selected disabled>---{{ translate('select') }}---</option>
                                    <option value="ready_after_sell">{{ translate("ready_After_Sell") }}</option>
                                    <option value="ready_product">{{ translate("ready_Product") }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="digital_file_ready_show">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label for="digital_file_ready" class="title-color mb-0">{{ translate("upload_file") }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('upload_the_digital_products_from_here')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <div class="input-group">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" name="digital_file_ready" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                      <label class="custom-file-label" for="inputGroupFile01">{{translate('choose_file')}}</label>
                                    </div>
                                </div>

                                <div class="mt-2">{{translate('file_type')}}: jpg, jpeg, png, gif, zip, pdf</div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color d-flex justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        {{ translate('product_SKU') }}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('create_a_unique_product_code_by_clicking_on_the_“Generate_Code”_button')}}">
                                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <a class="style-one-pro cursor-pointer" onclick="document.getElementById('generate_number').value = getRndInteger()">
                                        {{ translate('generate_code') }}
                                    </a>
                                </label>
                                <input type="text" minlength="6" id="generate_number" name="code"
                                        class="form-control" value="{{ old('code') }}"
                                        placeholder="{{ translate('code') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show">
                            <div class="form-group">
                                <label class="title-color">{{ translate('unit') }}</label>
                                <select class="js-example-basic-multiple form-control" name="unit">
                                    @foreach (\App\CPU\Helpers::units() as $x)
                                        <option value="{{ $x }}" {{ old('unit') == $x ? 'selected' : '' }}>
                                            {{ $x }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="title-color d-flex align-items-center gap-2">
                                    {{ translate('search_tags') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_product_search_tag_for_this_product_that_customers_can_use_to_search_quickly')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="text" class="form-control" placeholder="{{translate('enter_tag')}}" name="tags" data-role="tagsinput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing & others --}}
            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('Pricing_&_others') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0">{{ translate('purchase_price') }} ({{ \App\CPU\BackEndHelper::currency_symbol() }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_purchase_price_for_this_product')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" step="0.01"
                                        placeholder="{{ translate('purchase_price') }}"
                                        value="{{ old('purchase_price') }}" name="purchase_price"
                                        class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0">{{ translate('unit_price') }} ({{ \App\CPU\BackEndHelper::currency_symbol() }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('set_the_selling_price_for_each_unit_of_this_product._This_Unit_Price_section_won’t_be_applied_if_you_set_a_variation_wise_price')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" step="0.01"
                                        placeholder="{{ translate('unit_price') }}" name="unit_price"
                                        value="{{ old('unit_price') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="minimum_order_qty">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0" for="minimum_order_qty">{{ translate('minimum_order_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('set_the_minimum_order_quantity_that_customers_must_choose._Otherwise,_the_checkout_process_won’t_start')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="1" value="1" step="1" placeholder="{{ translate('minimum_order_quantity') }}" name="minimum_order_qty" id="minimum_order_qty" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show" id="quantity">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0" for="current_stock_qty">{{ translate('current_stock_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_Stock_Quantity_of_this_product_that_will_be_visible_to_customers')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" value="0" step="1" placeholder="{{ translate('quantity') }}" name="current_stock" id="current_stock_qty" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2 mb-2">
                                    <label class="title-color mb-0" for="discount_Type">{{ translate('discount_Type') }} <span class="discount_type_symbol" style="display: none">(%)</span></label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('if_“Flat”,_discount_amount_will_be_set_as_fixed_amount._If_“Percentage”,_discount_amount_will_be_set_as_percentage.')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="flat">{{ translate('flat') }}</option>
                                    <option value="percent">{{ translate('percent') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="discount">{{ translate('discount_amount') }} <span class="discount_amount_symbol">({{\App\CPU\BackEndHelper::currency_symbol()}})</span></label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_discount_amount_in_percentage_or_a_fixed_value_here')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" value="0" step="0.01" placeholder="{{ translate('ex: 5') }}"
                                        name="discount" id="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="tax">{{ translate('tax_amount') }}(%)</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('set_the_Tax_Amount_in_percentage_here')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" value="0" step="0.01"
                                        placeholder="{{ translate('ex: 5') }}" name="tax" id="tax"
                                        value="{{ old('tax') }}" class="form-control">
                                <input name="tax_type" value="percent" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="tax_model">{{ translate('tax_calculation') }}</label>

                                    <span class="input-label-secondary cursor-pointer"  data-toggle="tooltip"  title="{{translate('set_the_tax_calculation_method_from_here._Select_“Include_with_product”_to_combine_product_price_and_tax_on_the_checkout._Pick_“Exclude_from_product”_to_display_product_price_and_tax_amount_separately.')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <select name="tax_model" id="tax_model" class="form-control" required>
                                    <option value="include">{{ translate("include_with_product") }}</option>
                                    <option value="exclude">{{ translate("exclude_with_product") }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show" id="shipping_cost">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color">{{ translate('shipping_cost') }} ({{\App\CPU\BackEndHelper::currency_symbol()}})</label>

                                    <span class="input-label-secondary cursor-pointer"  data-toggle="tooltip"  title="{{translate('set_the_shipping_cost_for_this_product_here._Shipping_cost_will_only_be_applicable_if_product-wise_shipping_is_enabled.')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" value="0" step="1" placeholder="{{ translate('shipping_cost') }}" name="shipping_cost" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6 physical_product_show" id="shipping_cost_multy">
                            <div class="form-group">
                                <div class="form-control h-auto min-form-control-height d-flex align-items-center flex-wrap justify-content-between gap-2">
                                    <div class="d-flex gap-2">
                                        <label class="title-color text-capitalize" for="shipping_cost">{{ translate('shipping_cost_multiply_with_quantity') }}</label>

                                        <span class="input-label-secondary cursor-pointer"  data-toggle="tooltip"  title="{{translate('if_enabled,_the_shipping_charge_will_increase_with_the_product_quantity')}}">
                                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>

                                    <div>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input" name="multiplyQTY">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- product variation setup -->
            <div class="card mt-3 rest-part physical_product_show">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('product_variation_setup') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <label for="colors" class="title-color mb-0">
                                    {{ translate('select_colors') }} :
                                </label>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input" id="color_switcher" value="{{ old('colors_active') }}"
                                            name="colors_active">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <select
                                class="js-example-basic-multiple js-states js-example-responsive form-control color-var-select"
                                name="colors[]" multiple="multiple" id="colors-selector" disabled>
                                @foreach (\App\Model\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option value="{{ $color->code }}">
                                        {{ $color['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="choice_attributes" class="title-color">
                                {{ translate('select_attributes') }} :
                            </label>
                            <select
                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                    <option value="{{ $a['id'] }}">
                                        {{ $a['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mt-2 mb-2">
                            <div class="row customer_choice_options mt-2" id="customer_choice_options"></div>

                            <div class="form-group sku_combination" id="sku_combination"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 rest-part">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{ translate('product_thumbnail') }}</label>
                                        <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('add_your_products_thumbnail_in')}} JPG, PNG or JPEG {{translate('format_within')}} 2MB">
                                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <div>


                                    <div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="image" class="custom-upload-input-file" id="" data-imgpreview="pre_img_viewer"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                onchange="uploadColorImage(this)">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_img_viewer" class="h-auto aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="row" id="thumbnail"></div> --}}
                                        <p class="text-muted mt-2">{{translate('image_format')}} : Jpg, png, jpeg <br>
                                            {{translate('image_size')}} : {{translate('max')}} 2 MB</p>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- color wise image -->
                    <div class="color_image_column col-md-9 d-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{ translate('colour_wise_product_image') }}</label>
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('add_color-wise_product_images_here')}}.">
                                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>
                                    <p class="text-muted">{{translate('must_upload_colour_wise_images_first._Colour_is_shown_in_the_image_section_top_right')}}. </p>

                                    <div id="color_wise_image" class="row g-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End color wise image -->
                    <!--other image -->
                    <div class="additional_image_column col-md-9">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{ translate('upload_additional_image') }}</label>
                                    <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('upload_any_additional_images_for_this_product_from_here')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <p class="text-muted">{{translate('upload_additional_product_images')}}</p>

                                {{-- <div class="coba-area">
                                    <div class="row" id="coba"></div>
                                </div> --}}

                                <div class="row g-2" id="additional_Image_Section">

                                    <div class="col-sm-12 col-md-4">
                                        <div class="custom_upload_input position-relative border-dashed-2">
                                            <input type="file" name="images[]" class="custom-upload-input-file" data-index="1" data-imgpreview="additional_Image_1"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                {{-- onchange="document.getElementById('pre_img_viewer_ad_01').src = window.URL.createObjectURL(this.files[0])" --}}
                                                onchange="addMoreImage(this, '#additional_Image_Section')"
                                                >

                                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                <img id="additional_Image_1" class="h-auto aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End other image -->
                </div>
            </div>
            {{-- product video --}}
            <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">{{ translate('product_video') }}</h4>
                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('add_the_YouTube_video_link_here._Only_the_YouTube-embedded_link_is_supported')}}.">
                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="title-color mb-0">{{ translate('youtube_video_link') }}</label>
                        <span class="text-info"> ({{ translate('optional_please_provide_embed_link_not_direct_link') }}.)</span>
                    </div>
                    <input type="text" name="video_link" placeholder="{{ translate('ex') }} : https://www.youtube.com/embed/5R06LRdUCSE" class="form-control" required>
                </div>
            </div>

             {{-- seo section --}}
             <div class="card mt-3 rest-part">
                <div class="card-header">
                    <div class="d-flex gap-2">
                        <i class="tio-user-big"></i>
                        <h4 class="mb-0">
                            {{ translate('seo_section') }}
                            <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{ translate('add_meta_titles_descriptions_and_images_for_products').', '.translate('this_will_help_more_people_to_find_them_on_search_engines_and_see_the_right_details_while_sharing_on_other_social_platforms') }}">
                                <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                            </span>
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="title-color">
                                    {{ translate('meta_Title') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{ translate('add_the_products_title_name_taglines_etc_here').' '.translate('this_title_will_be_seen_on_Search_Engine_Results_Pages_and_while_sharing_the_products_link_on_social_platforms') .' [ '. translate('character_Limit') }} : 100 ]">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="text" name="meta_title" placeholder="{{ translate('meta_Title') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="title-color">
                                    {{ translate('meta_Description') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{ translate('write_a_short_description_of_the_InHouse_shops_product').' '.translate('this_description_will_be_seen_on_Search_Engine_Results_Pages_and_while_sharing_the_products_link_on_social_platforms') .' [ '. translate('character_Limit') }} : 100 ]">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <textarea rows="4" type="text" name="meta_description" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="d-flex justify-content-center">
                                <div class="form-group w-100">
                                    <div class="d-flex gap-2">
                                        <label class="title-color" for="meta_Image">
                                            {{ translate('meta_Image') }}
                                        </label>
                                        <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Meta Thumbnail'] }}</span>
                                        <span class="input-label-secondary cursor-pointer"  data-toggle="tooltip"  title="{{translate('add_Meta_Image_in')}} JPG, PNG or JPEG {{translate('format_within')}} 2MB, {{translate('which_will_be_shown_in_search_engine_results')}}.">
                                            <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </div>

                                    <div>
                                        <div class="custom_upload_input">
                                            <input type="file" name="meta_image" class="custom-upload-input-file meta-img" id="" data-imgpreview="pre_meta_image_viewer"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="uploadColorImage(this)">

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_meta_image_viewer" class="h-auto aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                                            </div>
                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end gap-3 mt-3 mx-1">
                <button type="reset" class="btn btn-secondary px-5">{{ translate('reset') }}</button>
                <button type="button" onclick="check()" class="btn btn--primary px-5">{{ translate('submit') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('public/assets/back-end/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(function() {
            $('#color_switcher').click(function(){
                var checkBoxes = $("#color_switcher");
                if ($('#color_switcher').prop('checked')) {
                    $('.color_image_column').removeClass('d-none');
                    $('.additional_image_column').removeClass('col-md-9');
                    $('.additional_image_column').addClass('col-md-12');
                    $('#color_wise_image').show();
                    $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
                } else {
                    $('.color_image_column').addClass('d-none');
                    $('.additional_image_column').addClass('col-md-9');
                    $('.additional_image_column').removeClass('col-md-12');
                    $('#color_wise_image').hide();
                    $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
                }
            });

            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 15,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-md-4 col-lg-3 col-xl-2',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset("public/assets/back-end/img/400x400/img2.jpg") }}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ translate("please_only_input_png_or_jpg_type_file") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate("file_size_too_big") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        function getRequest(route, id, type) {
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

        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append(
                '<div class="col-md-6"><div class="form-group"><input type="hidden" name="choice_no[]" value="' + i + '"><label class="title-color">' + n + '</label><input type="text" name="choice[]" value="'+ n +'" hidden><div class=""><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="{{ translate('enter_choice_values') }}" data-role="tagsinput" onchange="update_sku()"></div></div></div>'
            );

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }
        $('#colors-selector').on('change', function() {
            update_sku();
            $('#color_switcher').prop('checked')
            {
                color_wise_image($('#colors-selector'));
            }
        });

        $('input[name="unit_price"]').on('keyup', function() {
            let product_type = $('#product_type').val();
            if(product_type === 'physical') {
                update_sku();
            }
        });

        function color_wise_image(t){
            let colors = t.val();
            $('#color_wise_image').html('')
            $.each(colors, function(key, value){
                let value_id = value.replace('#','');
                let color= "color_image_"+value_id;

                let html = `<div class='col-6 col-lg-4 col-xl-4'>
                                <div class="position-relative p-2 border-dashed-2">
                                    <label style='border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;'>
                                        <span class="upload--icon" style="background: ${value}">
                                            <i class="tio-edit"></i>
                                            <input type="file" name="`+color+`" id="`+value_id+`" class="d-none" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required="">
                                        </span>

                                        <div class="h-100 top-0 aspect-1 w-100 d-flex align-content-center justify-content-center overflow-hidden">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>`;
                $('#color_wise_image').append(html)

                $("#color_wise_image input[type='file']").each(function () {

                    var $this = $(this).closest('label');

                    function proPicURL(input) {
                        if (input.files && input.files[0]) {
                            var uploadedFile = new FileReader();
                            uploadedFile.onload = function (e) {
                                $this.find('img').attr('src', e.target.result);
                                $this.fadeIn(300);
                                $this.find('h3').hide();
                            };
                            uploadedFile.readAsDataURL(input.files[0]);
                        }
                    }
                    $(this)
                        .on("change", function () {
                            proPicURL(this);
                        });
                });
            });
        }

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ route('seller.product.sku-combination') }}',
                data: $('#product_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data.view);
                    $('#sku_combination').addClass('pt-4');
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        };

        $(document).ready(function() {
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function(m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state
                    .text;
            }
        });
    </script>

    <script>
        function check() {
            Swal.fire({
                title: '{{ translate("are_you_sure") }}?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: '{{ translate("no") }}',
                confirmButtonText: '{{ translate("yes") }}',
                reverseButtons: true
            }).then((result) => {

                let discount_value = parseFloat($('#discount').val());
                let submit_status = 1;
                $(".variation-price-input").each(function() {
                    let variation_price = parseFloat($(this).val());

                    if (variation_price < discount_value) {
                        toastr.error("the_discount_price_will_not_larger_then_Variant_Price");
                        submit_status = 0;
                        return false;
                    }
                });

                if(submit_status == 1)
                {
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    var formData = new FormData(document.getElementById('product_form'));
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{ route('seller.product.add-new') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success(
                                    '{{ translate("product_updated_successfully") }}!', {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                $('#product_form').submit();
                            }
                        }
                    });
                }

            })
        };
    </script>

    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })

        $(document).ready(function(){
            product_type();
            digital_product_type();

            $('#product_type').change(function(){
                product_type();
            });

            $('#digital_product_type').change(function(){
                digital_product_type();
            });
        });

        function product_type(){
            let product_type = $('#product_type').val();

            if(product_type === 'physical'){
                $('#digital_product_type_show').hide();
                $('#digital_file_ready_show').hide();
                $('.physical_product_show').show();
                $('#digital_product_type').val($('#digital_product_type option:first').val());
                $('#digital_file_ready').val('');
            }else if(product_type === 'digital'){
                $('#digital_product_type_show').show();
                $('.physical_product_show').hide();

            }
        }

        function digital_product_type(){
            let digital_product_type = $('#digital_product_type').val();
            if (digital_product_type === 'ready_product') {
                $('#digital_file_ready_show').show();
            } else if (digital_product_type === 'ready_after_sell') {
                $('#digital_file_ready_show').hide();
                $("#digital_file_ready").val('');
            }
        }

        $('#discount_type').on('change', function(){
            if ($(this).val() == 'flat') {
                $('.discount_amount_symbol').html("({{\App\CPU\BackEndHelper::currency_symbol()}})").fadeIn();
            }else{
                $('.discount_amount_symbol').html("(%)").fadeIn();
            }
        })
    </script>

    {{-- ck editor --}}
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
    {{-- ck editor --}}

    <script>
        $('.delete_file_input').click(function () {
            let $parentDiv = $(this).closest('div');
            $parentDiv.find('input[type="file"]').val('');
            $parentDiv.find('.img_area_with_preview img').attr("src", " ");
            $(this).hide();
        });

        $('.custom-upload-input-file').on('change', function(){
            if (parseFloat($(this).prop('files').length) != 0) {
                let $parentDiv = $(this).closest('div');
                $parentDiv.find('.delete_file_input').fadeIn();
            }
        })
    </script>

    <script>
        function addMoreImage(thisData, targetSection){

            let $fileInputs = $(targetSection +" input[type='file']");
            let nonEmptyCount = 0;

            $fileInputs.each(function() {
                if (parseFloat($(this).prop('files').length) == 0) {
                    nonEmptyCount++;
                }
            });

            // let input_id = thisData.id;
            document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
            document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');

            if (nonEmptyCount == 0) {

                let dataset_index = thisData.dataset.index + 1;

                let newHtmlData = `<div class="col-sm-12 col-md-4">
                        <div class="custom_upload_input position-relative border-dashed-2">
                            <input type="file" name="${thisData.name}" class="custom-upload-input-file" data-index="${dataset_index}" data-imgpreview="additional_Image_${dataset_index}"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="addMoreImage(this, '${targetSection}')">

                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn" style="display: none">
                                <i class="tio-delete"></i>
                            </span>

                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                <img id="additional_Image_${dataset_index}" class="h-auto aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
                            </div>
                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                    <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(targetSection).append(newHtmlData);
            }


            $('.custom-upload-input-file').on('change', function(){
                if (parseFloat($(this).prop('files').length) != 0) {
                    let $parentDiv = $(this).closest('div');
                    $parentDiv.find('.delete_file_input').fadeIn();
                }
            })

            $('.delete_file_input_section').click(function () {
                let $parentDiv = $(this).closest('div').parent().remove();
                // var filledInputs = $(targetSection +' input[type="file"]').length;
            });

            if ($('#color_switcher').prop('checked')) {
                $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
            } else {
                $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
            }
        }

        if ($('#color_switcher').prop('checked')) {
            $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
        } else {
            $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
        }

        function uploadColorImage(thisData = null){
            if(thisData){
                document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
                document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
            }
        }
    </script>
@endpush
