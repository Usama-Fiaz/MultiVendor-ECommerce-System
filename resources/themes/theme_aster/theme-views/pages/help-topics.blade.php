@extends('theme-views.layouts.app')

@section('title', translate('FAQ'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="FAQ of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="FAQ of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 pt-3 mb-sm-5">
        <div class="page-title overlay py-5 __opacity-half background-custom-fit" style="--opacity: .5"

        @if ($page_title_banner)
            @if (File::exists(base_path('storage/app/public/banner/'.json_decode($page_title_banner['value'])->image)))
            data-bg-img="{{ asset('storage/app/public/banner/'.json_decode($page_title_banner['value'])->image) }}"
            @else
            data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}"
            @endif
        @else
            data-bg-img="{{theme_asset('assets/img/media/page-title-bg.png')}}"
        @endif
        >
            <div class="container">
                <h1 class="absolute-white text-center">{{ translate('FAQ') }}</h1>
            </div>
        </div>
        @php $length=count($helps); @endphp
        @php if($length%2!=0){$first=($length+1)/2;}else{$first=$length/2;}@endphp
        <div class="container">
            <div class="my-4">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @php($i = 0)
                    @foreach($helps as $help)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading{{ $help['id'] }}">
                                <button class="accordion-button collapsed text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $help['id'] }}" aria-expanded="false" aria-controls="flush-collapse{{ $help['id'] }}">
                                    {{ $help['question'] }}
                                </button>
                            </h2>
                            <div id="flush-collapse{{ $help['id'] }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" aria-labelledby="flush-heading{{ $help['id'] }}" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {{ $help['answer'] }}
                                </div>
                            </div>
                        </div>
                        @php($i++)
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection

