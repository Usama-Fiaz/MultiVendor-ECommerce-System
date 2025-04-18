@extends('layouts.back-end.app-seller')

@section('title', translate('product_edit'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
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
        <!-- Page Title -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/products.png')}}" alt="">
                {{translate('product_Edit')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <form class="product-form text-start" action="{{route('seller.product.update',$product->id)}}" method="post" enctype="multipart/form-data" id="product_form">
            @csrf

            <div class="card">
                <div class="px-4 pt-3">
                    @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'en')

                    @php($default_lang = json_decode($language)[0])
                    <ul class="nav nav-tabs w-fit-content mb-4">
                        @foreach(json_decode($language) as $lang)
                            <li class="nav-item text-capitalize">
                                <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#"
                                    id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-body">
                    @foreach(json_decode($language) as $lang)
                        <?php
                        if (count($product['translations'])) {
                            $translate = [];
                            foreach ($product['translations'] as $t) {

                                if ($t->locale == $lang && $t->key == "name") {
                                    $translate[$lang]['name'] = $t->value;
                                }
                                if ($t->locale == $lang && $t->key == "description") {
                                    $translate[$lang]['description'] = $t->value;
                                }

                            }
                        }
                        ?>
                        <div class="{{$lang != 'en'? 'd-none':''}} lang_form" id="{{$lang}}-form">
                            <div class="form-group">
                                <label class="title-color" for="{{$lang}}_name">{{translate('product_name')}} ({{strtoupper($lang)}})</label>
                                <input type="text" {{$lang == 'en'? 'required':''}} name="name[]"
                                        id="{{$lang}}_name"
                                        value="{{$translate[$lang]['name']??$product['name']}}"
                                        class="form-control" placeholder="{{translate('new_Product')}}" required>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                            <div class="form-group pt-4">
                                <label class="title-color">{{translate('description')}}
                                    ({{strtoupper($lang)}})</label>
                                <textarea name="description[]" class="textarea editor-textarea"
                                            >{!! $translate[$lang]['description']??$product['details'] !!}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- general setup --}}
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
                                <label for="name" class="title-color">{{translate('category')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="category_id"
                                    id="category_id"
                                    onchange="getRequest('{{url('/')}}/seller/product/get-categories?parent_id='+this.value,'sub-category-select','select')">
                                    <option value="0" selected disabled>---{{translate('select')}}---</option>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category['id']}}" {{ $category->id==$product_category[0]->id ? 'selected' : ''}} >{{$category['defaultName']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{translate('sub_Category')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="sub_category_id" id="sub-category-select"
                                    data-id="{{count($product_category)>=2?$product_category[1]->id:''}}"
                                    onchange="getRequest('{{url('/')}}/seller/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{translate('sub_Sub_Category')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    data-id="{{count($product_category)>=3?$product_category[2]->id:''}}"
                                    name="sub_sub_category_id" id="sub-sub-category-select">
                                </select>
                            </div>
                        </div>
                        @if($brand_setting)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label class="title-color">{{translate('brand')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="brand_id">
                                        <option value="{{null}}" selected disabled>---{{translate('select')}}---</option>
                                        @foreach($br as $b)
                                            <option
                                                value="{{$b['id']}}" {{ $b->id==$product->brand_id ? 'selected' : ''}} >{{$b['defaultName']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <label class="title-color">{{ translate('product_type') }}</label>
                                <select name="product_type" id="product_type" class="form-control" required>
                                    <option value="physical" {{ $product->product_type=='physical' ? 'selected' : ''}}>{{ translate('physical') }}</option>
                                    @if($digital_product_setting)
                                    <option value="digital" {{ $product->product_type=='digital' ? 'selected' : ''}}>{{ translate('digital') }}</option>
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
                                    <option value="{{ old('category_id') }}" {{ !$product->digital_product_type ? 'selected' : ''}} disabled>---{{translate('select')}}---</option>
                                    <option value="ready_after_sell" {{ $product->digital_product_type=='ready_after_sell' ? 'selected' : ''}}>{{ translate("ready_After_Sell") }}</option>
                                    <option value="ready_product" {{ $product->digital_product_type=='ready_product' ? 'selected' : ''}}>{{ translate("ready_Product") }}</option>
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
                                      <input type="file" class="custom-file-input" name="digital_file_ready" id="digital_file_ready" aria-describedby="inputGroupFileAddon01">
                                      <label class="custom-file-label" for="digital_file_ready">{{translate('choose_file')}}</label>
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

                                <input type="text" id="generate_number" name="code" class="form-control"  value="{{ $product->code  }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show">
                            <div class="form-group">
                                <label class="title-color">{{translate('unit')}}</label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="unit">
                                    @foreach(\App\CPU\Helpers::units() as $x)
                                        <option
                                            value={{$x}} {{ $product->unit==$x ? 'selected' : ''}}>{{$x}}</option>
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
                                {{-- <input type="text" class="form-control" placeholder="{{translate('enter_tag')}}" name="tags" data-role="tagsinput"> --}}
                                <input type="text" class="form-control" name="tags" value="@foreach($product->tags as $c) {{$c->tag.','}} @endforeach" data-role="tagsinput">
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
                                <div class="d-flex gap-2">
                                    <label class="title-color">{{ translate('purchase_price') }} ({{ \App\CPU\BackEndHelper::currency_symbol() }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_purchase_price_for_this_product')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" step="0.01"
                                        placeholder="{{translate('purchese_price') }}"
                                        name="purchase_price" class="form-control"
                                        value={{ \App\CPU\Convert::default($product->purchase_price) }} required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color">{{ translate('unit_price') }} ({{ \App\CPU\BackEndHelper::currency_symbol() }})</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('set_the_selling_price_for_each_unit_of_this_product._This_Unit_Price_section_won’t_be_applied_if_you_set_a_variation_wise_price')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0" step="0.01"
                                        placeholder="{{translate('unit_price') }}"
                                        name="unit_price" class="form-control"
                                        value={{\App\CPU\Convert::default($product->unit_price)}} required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3" id="minimum_order_qty">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="minimum_order_qty">{{ translate('minimum_order_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('set_the_minimum_order_quantity_that_customers_must_choose._Otherwise,_the_checkout_process_won’t_start')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="1" value={{ $product->minimum_order_qty }} step="1"
                                        placeholder="{{translate('minimum_order_quantity') }}"
                                        name="minimum_order_qty" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3 physical_product_show" id="quantity">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="current_stock">{{ translate('current_stock_qty') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_Stock_Quantity_of_this_product_that_will_be_visible_to_customers')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <input type="number" min="0" value={{ $product->current_stock }} step="1"
                                        placeholder="{{translate('quantity') }}"
                                        name="current_stock" id="current_stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="discount_Type">{{ translate('discount_Type') }}</label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('if_“Flat”,_discount_amount_will_be_set_as_fixed_amount._If_“Percentage”,_discount_amount_will_be_set_as_percentage.')}}">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <select class="form-control" name="discount_type" id="discount_type">
                                    <option value="flat" {{$product['discount_type']=='flat'?'selected':''}}>{{translate('flat')}}</option>
                                    <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>{{translate('percent')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="form-group">
                                <div class="d-flex gap-2">
                                    <label class="title-color" for="discount">
                                        {{ translate('discount_amount') }}
                                        <span class="discount_amount_symbol">({{ $product->discount_type=='flat'? \App\CPU\BackEndHelper::currency_symbol() : '%' }})</span>
                                    </label>

                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip"  title="{{translate('add_the_discount_amount_in_percentage_or_a_fixed_value_here')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <input type="number" min="0"
                                        value={{ $product->discount_type=='flat'?\App\CPU\Convert::default($product->discount): $product->discount}} step="0.01"
                                        placeholder="{{translate('ex: 5') }}" name="discount" id="discount"
                                        class="form-control" required>
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

                                <input type="number" min="0" value={{ $product->tax }} step="0.01"
                                        placeholder="{{translate('tax') }}" name="tax" id="tax"
                                        class="form-control" required>
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
                                    <option value="include" {{ $product->tax_model == 'include' ? 'selected':'' }}>{{ translate("include_with_product") }}</option>
                                    <option value="exclude" {{ $product->tax_model == 'exclude' ? 'selected':'' }}>{{ translate("exclude_with_product") }}</option>
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

                                <input type="number" min="0" value="{{\App\CPU\Convert::default($product->shipping_cost)}}" step="1"
                                        placeholder="{{translate('shipping_cost')}}"
                                        name="shipping_cost" class="form-control" required>
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
                                            <input class="switcher_input" type="checkbox" name="multiplyQTY"
                                                    id="" {{$product->multiply_qty == 1?'checked':''}}>
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
                            <div class="form-group">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <label class="mb-0 title-color">
                                        {{translate('select_colors')}} :
                                    </label>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" id="color_switcher"
                                            name="colors_active" {{count($product['colors'])>0?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control color-var-select"
                                    name="colors[]" multiple="multiple"
                                    id="colors-selector" {{count($product['colors'])>0?'':'disabled'}}>
                                    @foreach (\App\Model\Color::orderBy('name', 'asc')->get() as $key => $color)
                                        <option
                                            value={{ $color->code }} {{in_array($color->code,$product['colors'])?'selected':''}}>
                                            {{$color['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="choice_attributes" class="pb-1 title-color">
                                    {{translate('select_attributes')}} :
                                </label>
                                <select
                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                    name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                    @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                        @if($product['attributes']!='null')
                                            <option
                                                value="{{ $a['id']}}" {{in_array($a->id,json_decode($product['attributes'],true))?'selected':''}}>
                                                {{$a['name']}}
                                            </option>
                                        @else
                                            <option value="{{ $a['id']}}">{{$a['name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2 mb-2">
                            <div class="row customer_choice_options mt-2" id="customer_choice_options">
                                @include('seller-views.product.partials._choices',['choice_no'=>json_decode($product['attributes']),'choice_options'=>json_decode($product['choice_options'],true)])
                            </div>

                            <div class="sku_combination table-responsive form-group mt-2" id="sku_combination">
                                @include('seller-views.product.partials._edit_sku_combinations',['combinations'=>json_decode($product['variation'],true)])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 rest-part">
                <div class="row g-2">
                    <!--thumbnail-->
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{ translate('product_thumbnail') }}</label>
                                    <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Product Image'] }}</span>
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('add_your_product’s_thumbnail_in')}} JPG, PNG or JPEG {{translate('format_within')}} 2MB">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>

                                <div>
                                    <div class="custom_upload_input">
                                        <input type="file" name="image" class="custom-upload-input-file" id="" data-imgpreview="pre_img_viewer"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                            onchange="uploadColorImage(this)">

                                        <span class="delete_file_input btn btn-outline-danger btn-sm square-btn"
                                        style="display: @if (File::exists(base_path('storage/app/public/product/thumbnail/'. $product->thumbnail))) flex @else none @endif">
                                            <i class="tio-delete"></i>
                                        </span>

                                        <div class="img_area_with_preview position-absolute z-index-2">
                                            <img id="pre_img_viewer" class="h-auto aspect-1 bg-white" src="{{\App\CPU\ProductManager::product_image_path('thumbnail').'/'.$product->thumbnail}}" onerror="this.classList.add('d-none')">
                                        </div>
                                        <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                <h3 class="text-muted">{{ translate('Upload_Image') }}</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-muted mt-2">{{translate('image_format')}} : Jpg, png, jpeg <br>
                                        {{translate('image_size')}} : {{translate('max')}} 2 MB</p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--End thumbnail-->
                    <!--color wise image -->
                    <div class="col-md-9 color_image_column d-none">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label for="name" class="title-color text-capitalize font-weight-bold mb-0">{{ translate('colour_wise_product_image') }}</label>
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" title="{{translate('add_color-wise_product_images_here')}}.">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </div>
                                <p class="text-muted">{{translate('must_upload_colour_wise_images_first._Colour_is_shown_in_the_image_section_top_right.')}} </p>

                                <div id="color_wise_image" class="row g-2 mb-4">
                                    <div class="col-12">
                                        <div class="row g-2" id="color_wise_existing_image"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row g-2" id="color_wise_image_field"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end color wise image -->
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

                                <div class="row g-2" id="additional_Image_Section">

                                    @if(count($product->colors) == 0)
                                        @foreach (json_decode($product->images) as $key => $photo)
                                            @php($unique_id = rand(1111,9999))
                                            <div class="col-sm-6 col-md-4">
                                                <div class="custom_upload_input custom-upload-input-file-area position-relative border-dashed-2">
                                                    <a class="delete_file_input_css btn btn-outline-danger btn-sm square-btn" href="{{route('seller.product.remove-image',['id'=>$product['id'],'name'=>$photo])}}">
                                                        <i class="tio-delete"></i>
                                                    </a>

                                                    <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                        <img id="additional_Image_{{ $unique_id }}" class="h-auto aspect-1 bg-white" src="{{asset("storage/app/public/product/$photo")}}" onerror="this.classList.add('d-none')">
                                                    </div>

                                                    <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                            <h3 class="text-muted">{{ translate('Image_Not_found') }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @if($product->color_image)
                                            @foreach (json_decode($product->color_image) as $photo)
                                                @if($photo->color == null)
                                                @php($unique_id = rand(1111,9999))
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="custom_upload_input custom-upload-input-file-area position-relative border-dashed-2">
                                                            <a class="delete_file_input_css btn btn-outline-danger btn-sm square-btn" href="{{route('seller.product.remove-image',['id'=>$product['id'],'name'=>$photo->image_name,'color'=>'null'])}}">
                                                                <i class="tio-delete"></i>
                                                            </a>

                                                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                                <img id="additional_Image_{{ $unique_id }}" class="h-auto aspect-1 bg-white" src="{{asset("storage/app/public/product/".$photo->image_name)}}" onerror="this.classList.add('d-none')">
                                                            </div>
                                                            <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                                    <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                                    <h3 class="text-muted">{{ translate('Image_Not_found') }}</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach (json_decode($product->images) as $key => $photo)
                                            @php($unique_id = rand(1111,9999))

                                                <div class="col-sm-6 col-md-4">
                                                    <div class="custom_upload_input custom-upload-input-file-area position-relative border-dashed-2">
                                                        <a class="delete_file_input_css btn btn-outline-danger btn-sm square-btn" href="{{route('seller.product.remove-image',['id'=>$product['id'],'name'=>$photo])}}">
                                                            <i class="tio-delete"></i>
                                                        </a>

                                                        <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                            <img id="additional_Image_{{ $unique_id }}" class="h-auto aspect-1 bg-white" src="{{asset("storage/app/public/product/".$photo)}}" onerror="this.classList.add('d-none')">
                                                        </div>
                                                        <div class="position-absolute h-100 top-0 w-100 d-flex align-content-center justify-content-center">
                                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                                <img src="{{asset('public/assets/back-end/img/icons/product-upload-icon.svg')}}" class="w-50">
                                                                <h3 class="text-muted">{{ translate('Image_Not_found') }}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif

                                    <div class="col-sm-6 col-md-4">
                                        <div class="custom_upload_input position-relative border-dashed-2">
                                            <input type="file" name="images[]" class="custom-upload-input-file" data-index="101" data-imgpreview="additional_Image_101"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="addMoreImage(this, '#additional_Image_Section')">

                                            <span class="delete_file_input delete_file_input_section btn btn-outline-danger btn-sm square-btn" style="display: none">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2 border-0">
                                                <img id="additional_Image_101" class="h-auto aspect-1 bg-white" src="img" onerror="this.classList.add('d-none')">
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

                <input type="hidden" id="color_image" value="{{ $product->color_image }}">
                <input type="hidden" id="images" value="{{ $product->images }}">
                <input type="hidden" id="product_id" value="{{ $product->id }}">
                <input type="hidden" id="remove_url" value="{{ route('seller.product.remove-image') }}">
            </div>


            <!-- product video -->
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
                        <label class="title-color mb-0">{{translate('youtube_video_link')}}</label>
                        <span class="text-info"> ( {{translate('optional_please_provide_embed_link_not_direct_link')}}. )</span>
                    </div>
                    <input type="text" value="{{$product['video_url']}}" name="video_link"
                            placeholder="{{translate('ex')}} : https://www.youtube.com/embed/5R06LRdUCSE"
                            class="form-control" required>
                </div>
            </div>

            <!-- seo section -->
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
                                <input type="text" name="meta_title" value="{{$product['meta_title']}}" placeholder="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="title-color">
                                    {{ translate('meta_Description') }}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{ translate('write_a_short_description_of_the_InHouse_shops_product').' '.translate('this_description_will_be_seen_on_Search_Engine_Results_Pages_and_while_sharing_the_products_link_on_social_platforms') .' [ '. translate('character_Limit') }} : 100 ]">
                                        <img src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <textarea rows="4" type="text" name="meta_description" class="form-control">{{$product['meta_description']}}</textarea>
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

                                            <span class="delete_file_input btn btn-outline-danger btn-sm square-btn"
                                            style="display: @if (File::exists(base_path('storage/app/public/product/meta/'. $product['meta_image']))) flex @else none @endif">
                                                <i class="tio-delete"></i>
                                            </span>

                                            <div class="img_area_with_preview position-absolute z-index-2">
                                                <img id="pre_meta_image_viewer" class="h-auto aspect-1 bg-white" src="{{ asset("storage/app/public/product/meta/". $product['meta_image'])}}" onerror="this.classList.add('d-none')">
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

            <div class="d-flex justify-content-end mt-3">
                @if($product->request_status == 2)
                    <button type="button" onclick="check()" class="btn btn--primary px-5">{{translate('resubmit')}}</button>
                @else
                    <button type="button" onclick="check()" class="btn btn--primary px-5">{{translate('update')}}</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script>


       $(document).ready(function() {
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




        var colors = {{ count($product->colors) }};
        var imageCount = {{10-count(json_decode($product->images))}};
        var thumbnail = '{{\App\CPU\ProductManager::product_image_path('thumbnail').'/'.$product->thumbnail??asset('public/assets/back-end/img/400x400/img2.jpg')}}';
        $(function () {
            if (imageCount > 0) {
                $("#coba").spartanMultiImagePicker({
                    fieldName: 'images[]',
                    maxCount: colors===0 ? 15:imageCount,
                    rowHeight: 'auto',
                    groupClassName: 'col-6 col-md-4 col-xl-3 col-xxl-2',
                    maxFileSize: '',
                    placeholderImage: {
                        image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
                        width: '100%',
                    },
                    dropFileLabel: "Drop Here",
                    onAddRow: function (index, file) {

                    },
                    onRenderedPreview: function (index) {

                    },
                    onRemoveRow: function (index) {

                    },
                    onExtensionErr: function (index, file) {
                        toastr.error('{{translate("please_only_input_png_or_jpg_type_file")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    onSizeErr: function (index, file) {
                        toastr.error('{{translate("file_size_too_big")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                });
            }
              $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-12 ',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{\App\CPU\ProductManager::product_image_path('thumbnail').'/'.$product->thumbnail??asset('public/assets/back-end/img/400x400/img2.jpg')}}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate("please_only_input_png_or_jpg_type_file")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate("file_size_too_big")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

        });

        $('#color_switcher').click(function(){
            let checkBoxes = $("#color_switcher");
            if ($('#color_switcher').prop('checked')) {
                $('#color_wise_image').show();
                $('.color_image_column').removeClass('d-none');
                $('.additional_image_column').removeClass('col-md-9');
                $('.additional_image_column').addClass('col-md-12');
                $('#additional_Image_Section .col-md-4').addClass('col-lg-2');
            } else {
                $('#color_wise_image').hide();
                $('.color_image_column').addClass('d-none');
                $('.additional_image_column').addClass('col-md-9');
                $('.additional_image_column').removeClass('col-md-12');
                $('#additional_Image_Section .col-md-4').removeClass('col-lg-2');
            }
        });

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
    </script>

    <script>
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

        $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                //console.log($(this).val());
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

        setInterval(function () {
            $('.call-update-sku').on('change', function () {
                update_sku();
            });
        }, 2000)

        $('#colors-selector').on('change', function () {
            update_sku();
            let checkBoxes = $("#color_switcher");
            if ($('#color_switcher').prop('checked')) {
                $('#color_wise_image').show();
                color_wise_image($('#colors-selector'));
            }else{
                $('#color_wise_image').hide();
            }
        });

        $('input[name="unit_price"]').on('keyup', function () {
            let product_type = $('#product_type').val();
            if(product_type === 'physical') {
                update_sku();
            }
        });

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('seller.product.sku-combination')}}',
                data: $('#product_form').serialize(),
                success: function (data) {
                    $('#sku_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        function color_wise_image(t){
            let colors = t.val();
            let color_image = $('#color_image').val() ? $.parseJSON($('#color_image').val()):[];
            let images = $.parseJSON($('#images').val());
            var product_id = $('#product_id').val();
            let remove_url = $('#remove_url').val();

            let color_image_value = $.map(color_image, function(item) {
                return item.color;
            });

            $('#color_wise_existing_image').html('')
            $('#color_wise_image_field').html('')

            $.each(colors, function(key, value){
                let value_id = value.replace('#','');
                let in_array_image = $.inArray(value_id, color_image_value);
                let input_image_name = "color_image_"+value_id;

                $.each(color_image, function (color_key, color_value){
                    if((in_array_image !== -1) && (color_value['color'] === value_id) ){
                        let image_name = color_value['image_name'];
                        let exist_image_html = `
                            <div class="col-6 col-md-4 col-xl-4">
                                <div class="position-relative p-2 border-dashed-2">
                                    <div class="upload--icon-btns d-flex gap-2 position-absolute z-index-2 p-2" >
                                        <button type="button" class="btn btn-square text-white btn-sm" style="background: #${color_value['color']}"><i class="tio-done"></i></button>
                                        <a href="`+remove_url+`?id=`+product_id+`&name=`+image_name+`&color=`+color_value['color']+`"
                                    class="btn btn-outline-danger btn-sm square-btn"><i class="tio-delete"></i></a>
                                    </div>
                                    <img class="w-100" height="auto"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/product/`+image_name+`')}}"
                                        alt="Product image">
                                </div>
                            </div>`;
                        $('#color_wise_existing_image').append(exist_image_html)
                    }
                });
            });

            $.each(colors, function(key, value){
                let value_id = value.replace('#','');
                let in_array_image = $.inArray(value_id, color_image_value);
                let input_image_name = "color_image_"+value_id;

                if(in_array_image === -1) {
                    let html = ` <div class='col-6 col-md-4 col-xl-4'>
                                <div class="position-relative p-2 border-dashed-2">
                                    <label style='cursor: pointer; text-align: center; overflow: hidden; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;'>
                                    <span class="upload--icon" style="background: ${value}">
                                    <i class="tio-edit"></i>
                                        <input type="file" name="` + input_image_name + `" id="` + value_id + `" class="d-none" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required="">
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
                    $('#color_wise_image_field').append(html)

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
                }
            });
        }

        $(document).ready(function () {
            let category = $("#category_id").val();
            let sub_category = $("#sub-category-select").attr("data-id");
            let sub_sub_category = $("#sub-sub-category-select").attr("data-id");
            getRequest('{{url('/')}}/seller/product/get-categories?parent_id=' + category + '&sub_category=' + sub_category, 'sub-category-select', 'select');
            getRequest('{{url('/')}}/seller/product/get-categories?parent_id=' + sub_category + '&sub_category=' + sub_sub_category, 'sub-sub-category-select', 'select');
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            let checkBoxes = $("#color_switcher");
            if ($('#color_switcher').prop('checked')) {
                $('#color_wise_image').show();
                color_wise_image($('#colors-selector'));
            } else {
                $('#color_wise_image').hide();
            }

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>

    {{--ck editor--}}
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
    {{--ck editor--}}

    <script>
        function check(){
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
                    url: '{{route('seller.product.update',$product->id)}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{translate("product_updated_successfully")}}!', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            $('#product_form').submit();
                        }
                    }
                });
            }

        };
    </script>

    <script>
        update_qty();

        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }

        $('input[name^="qty_"]').on('keyup', function () {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            $('input[name="current_stock"]').val(total_qty);
        });
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
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        });

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
                $("#digital_product_type").val($("#digital_product_type option:first").val());
                $("#digital_file_ready").val('');
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

        function uploadColorImage(thisData = null){
            if(thisData){
                document.getElementById(thisData.dataset.imgpreview).setAttribute("src", window.URL.createObjectURL(thisData.files[0]));
                document.getElementById(thisData.dataset.imgpreview).classList.remove('d-none');
            }
        }

    </script>
@endpush
