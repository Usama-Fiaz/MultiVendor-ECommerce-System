@extends('layouts.back-end.app-seller')
@section('title', translate('coupon_Edit'))
@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img src="{{asset('/public/assets/back-end/img/coupon_setup.png')}}" class="mb-1 mr-1" alt="">
                {{translate('coupon_update')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <form action="{{route('seller.coupon.update',[$coupon['id']])}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('coupon_type')}}</label>
                                    <select class="form-control" id="coupon_type" name="coupon_type" required>
                                        <option disabled selected>{{translate('select_Coupon_Type')}}</option>
                                        <option value="discount_on_purchase" {{$coupon['coupon_type']=='discount_on_purchase'?'selected':''}}>{{translate('discount_on_purchase')}}</option>
                                        <option value="free_delivery" {{$coupon['coupon_type']=='free_delivery'?'selected':''}}>{{translate('free_Delivery')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('coupon_title')}}</label>
                                    <input type="text" name="title" class="form-control" id="title" value="{{$coupon['title']}}"
                                           placeholder="{{translate('title')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('coupon_code')}}</label>
                                    <a href="javascript:void(0)" class="float-right" onclick="generateCode()">{{translate('generate_code')}}</a>
                                    <input type="text" name="code" value="{{$coupon['code']}}"
                                           class="form-control" id="code"
                                           placeholder="{{translate('ex')}}: EID100" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group coupon_type">
                                    <label for="name" class="title-color font-weight-medium d-flex">{{translate('customer')}}</label>
                                    <select class="js-example-basic-multiple js-states js-example-responsive form-control" name="customer_id" >
                                        <option disabled selected>{{translate('select_customer')}}</option>
                                        <option value="0" {{$coupon['customer_id']=='0'?'selected':''}}>{{translate('all_customer')}}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{$coupon['customer_id']==$customer->id ? 'selected':''}}>{{ $customer->f_name. ' '. $customer->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label  for="exampleFormControlInput1" class="title-color text-capitalize">{{translate('limit_for_same_user')}}</label>
                                    <input type="number" name="limit" min="0" value="{{ $coupon['limit'] }}" id="coupon_limit" class="form-control" placeholder="{{translate('ex')}}: {{translate('10')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label  for="name" class="title-color text-capitalize">{{translate('discount_type')}}</label>
                                    <select id="discount_type" class="form-control" name="discount_type"
                                            onchange="checkDiscountType(this.value)">
                                        <option value="amount" {{$coupon['discount_type']=='amount'?'selected':''}}>{{translate('amount')}}</option>
                                        <option value="percentage" {{$coupon['discount_type']=='percentage'?'selected':''}}>{{translate('percentage')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name" class="title-color text-capitalize">{{translate('discount_Amount')}} <span id="discount_percent"> (%)</span></label>
                                    <input type="number" min="0" max="1000000" step=".01" name="discount" class="form-control" id="discount" value="{{$coupon['discount_type']=='amount'?\App\CPU\Convert::default($coupon['discount']):$coupon['discount']}}"
                                           placeholder="{{translate('ex')}}: 500" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('minimum_purchase')}}</label>
                                    <input type="number" min="0" max="1000000" step=".01" name="min_purchase" class="form-control" id="minimum purchase" value="{{\App\CPU\Convert::default($coupon['min_purchase'])}}"
                                           placeholder="{{translate('minimum purchase')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery" id="max-discount">
                                    <label for="name" class="title-color text-capitalize">{{translate('maximum_discount')}}</label>
                                    <input type="number" min="0" max="1000000" step=".01" name="max_discount" class="form-control" id="maximum discount" value="{{\App\CPU\Convert::default($coupon['max_discount'])}}"
                                           placeholder="{{translate('maximum discount')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('start_date')}}</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date" value="{{date('Y-m-d',strtotime($coupon['start_date']))}}"
                                           placeholder="{{translate('start date')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color text-capitalize">{{translate('expire_date')}}</label>
                                    <input type="date" name="expire_date" class="form-control" id="expire_date" value="{{date('Y-m-d',strtotime($coupon['expire_date']))}}"
                                           placeholder="{{translate('expire date')}}" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset" class="btn btn-secondary px-4">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{translate('Update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let discount_type = $('#discount_type').val();
            if (discount_type == 'amount') {
                $('#max-discount').hide()
            } else if (discount_type == 'percentage') {
                $('#max-discount').show()
            }
            $('#start_date').attr('min',(new Date()).toISOString().split('T')[0]);
            $('#expire_date').attr('min',(new Date()).toISOString().split('T')[0]);

            field_show_hide();
        });

        $("#start_date").on("change", function () {
            $('#expire_date').attr('min',$(this).val());
        });

        $("#expire_date").on("change", function () {
            $('#start_date').attr('max',$(this).val());
        });


        function checkDiscountType(val) {
            if (val == 'amount') {
                $('#max-discount').hide()
            } else if (val == 'percentage') {
                $('#max-discount').show()
            }
        }

        function  generateCode(){
            let code = Math.random().toString(36).substring(2,12);
            $('#code').val(code)
        }

        $('#discount_type').on('change', function(){
            let discount_type = $('#discount_type').val();
            if(discount_type === 'percentage'){
                $('#discount').attr("max","100");
            }else{
                $('#discount').attr("max","1000000");
            }
        })

    </script>
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $('#discount_type').on('change', function (){
            let type = $('#discount_type').val();
            if(type === 'amount'){
                $('#discount').attr('placeholder', 'Ex: 500');
            }else if(type === 'percentage'){
                $('#discount').attr('placeholder', 'Ex: 10%');
            }
        });

        $('#coupon_type').on('change', function (){
            field_show_hide();
        });

        function field_show_hide(){
            let discount_type = $('#discount_type').val();
            let type = $('#coupon_type').val();

            if(type === 'free_delivery'){
                if (discount_type === 'amount') {
                    $('.free_delivery').hide();
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').hide();
                }
            }else{
                if (discount_type === 'amount') {
                    $('.free_delivery').show();
                    $('#max-discount').hide()
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').show();
                    $('#max-discount').show()
                }
            }
        }
    </script>
@endpush
