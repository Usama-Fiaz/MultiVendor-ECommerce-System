@extends('layouts.back-end.app')

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{translate('shipping_Method_Update')}}</li>
        </ol>
    </nav> -->


    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
            {{translate('shipping_Method_Update')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-black-50">{{translate('shipping_method_update')}}</h1>
    </div> -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="card-header">
                    {{translate('shipping_method_form')}}
                </div> -->
                <div class="card-body">
                    <form action="{{route('admin.business-settings.shipping-method.update',[$method['id']])}}"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                          method="post">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="title">{{translate('title')}}</label>
                                    <input type="text" name="title" value="{{$method['title']}}" class="form-control" placeholder="{{translate('title')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="duration">{{translate('duration')}}</label>
                                    <input type="text" name="duration" value="{{$method['duration']}}" class="form-control" placeholder="{{translate('ex')}} : {{translate('4_to_6_days')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row ">
                                <div class="col-md-12">
                                    <label class="title-color" for="cost">{{translate('cost')}}</label>
                                    <input type="number" min="0" max="1000000" name="cost" value="{{\App\CPU\BackEndHelper::usd_to_currency($method['cost'])}}" class="form-control" placeholder="{{translate('ex')}} : {{translate('10')}}$">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-10 flex-wrap justify-content-end">
                            <button type="submit" class="btn btn--primary px-4">{{translate('update')}}</button>
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
