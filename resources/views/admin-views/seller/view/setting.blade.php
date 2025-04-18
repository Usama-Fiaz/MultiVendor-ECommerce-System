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
                    <div class="mt-4 pr-2">
                        <div class="flex-start">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div><h4>{{translate('seller_request_for_open_a_shop')}}.</h4></div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn--primary btn-sm">{{translate('approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger btn-sm">{{translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between mx-1 row">
                <div>
                    <h1 class="page-header-title">{{ $seller->shop ? $seller->shop->name : translate("shop_Name")." : ".translate("update_Please") }}</h1>
                </div>

            </div>
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.sellers.view',$seller->id) }}">{{translate('shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{translate('order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{translate('product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
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
        <!-- End Page Header -->

        <div class="row g-3">
            <div class="col-md-6">
                <form action="{{ url()->current() }}"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      method="GET">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"> {{translate('sales_Commission')}} : </h5>

                            <label class="switcher" for="commission_status">
                                <input type="checkbox" class="switcher_input" value="1" name="commission_status" id="commission_status" {{ $seller['sales_commission_percentage'] !=null ? 'checked':'' }} onclick="toogleModal(event,'commission_status','general-icon.png','general-icon.png','{{translate('want_to_Turn_ON_Sales_Commission_For_This_Seller')}}?','{{translate('want_to_Turn_OFF_Sales_Commission_For_This_Seller')}}?',`<p>{{translate('if_sales_commission_is_enabled_here_the_this_commission_will_be_applied')}}</p>`,`<p>{{translate('if_sales_commission_is_disabled_here_the_system_default_commission_will_be_applied')}}</p>`)">
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <small class="badge badge-soft-info text-wrap mb-3">
                                {{translate('if_sales_commission_is_disabled_here_the_system_default_commission_will_be_applied')}}.
                            </small>
                            <div class="form-group">
                                <label>{{translate('commission')}} ( % )</label>
                                <input type="number" value="{{$seller['sales_commission_percentage']}}"
                                       class="form-control" name="commission">
                            </div>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <form action="{{ url()->current() }}"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"> {{translate('GST_Number')}} : </h5>

                            <label class="switcher" for="gst_status">
                                <input type="checkbox" class="switcher_input" value="1" name="gst_status" id="gst_status" {{ $seller['gst'] !=null ? 'checked':'' }} onclick="toogleModal(event,'gst_status','general-icon.png','general-icon.png','{{translate('want_to_Turn_ON_GST_Number_For_This_Seller')}}?','{{translate('want_to_Turn_OFF_GST_Number_For_This_Seller')}}?',`<p>{{translate('if_GST_number_is_enabled_here_it_will_be_show_in_invoice')}}</p>`,`<p>{{translate('if_GST_number_is_disabled_here_it_will_not_show_in_invoice')}}</p>`)">
                                <span class="switcher_control"></span>
                            </label>

                        </div>
                        <div class="card-body">
                            <small class="badge text-wrap badge-soft-info mb-3">
                                {{translate('if_GST_number_is_disabled_here_it_will_not_show_in_invoice')}}.
                            </small>
                            <div class="form-group">
                                <label> {{translate('number')}}  </label>
                                <input type="text" value="{{$seller['gst']}}"
                                       class="form-control" name="gst">
                            </div>
                            <button type="submit" class="btn btn--primary">{{translate('update')}} </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('seller_POS')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ url()->current() }}" method="GET">
                            <input type="hidden" name="seller_pos_update" value="1">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('Seller_POS_Permission')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_this_seller_can_access_POS_from_the_website_and_seller_app') }}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="seller_pos">
                                        <input type="checkbox" class="switcher_input" value="1" name="seller_pos" id="seller_pos" {{ $seller['pos_status'] == 1 ? 'checked':'' }} onclick="toogleModal(event,'seller_pos','pos-seller-on.png','pos-seller-off.png','{{translate('want_to_Turn_ON_POS_For_This_Seller')}}?','{{translate('want_to_Turn_OFF_POS_For_This_Seller')}}?',`<p>{{translate('if_enabled_this_seller_can_access_POS_from_the_website_and_seller_app')}}</p>`,`<p>{{translate('if_disabled_this_seller_cannot_access_POS_from_the_website_and_seller_app')}}</p>`)">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn--primary">{{translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
