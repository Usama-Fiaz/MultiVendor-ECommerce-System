@extends('layouts.front-end.app')

@section('title', translate('all_Brands'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Brands of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Brands of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">
@endpush

@section('content')

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="bg-primary-light rounded-10 my-4 p-3 p-sm-4" data-bg-img="{{ asset('public/assets/front-end/img/media/bg.png') }}">
            <div class="d-flex flex-column gap-1 text-primary">
                <h4 class="mb-0 text-start fw-bold text-primary text-uppercase">{{ translate('brands') }}</h4>
                <p class="fs-14 fw-semibold mb-0">{{translate('Find your favourite brands and products')}}</p>
            </div>
        </div>
        
        <!-- Products grid-->
        <div class="brand_div-wrap mb-4">
            @foreach($brands as $brand)
                <a href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}" class="brand_div" data-toggle="tooltip" title="{{$brand->name}}">
                    <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{asset("storage/app/public/brand/$brand->image")}}" alt="{{$brand->name}}">
                </a>
            @endforeach
        </div>
        
        <div class="row mx-n2">
            <div class="col-md-12">
                <center>
                    {!! $brands->links() !!}
                </center>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js"></script>
@endpush
