@extends('layouts.front-end.app')

@section('title',translate('terms_and_Condition'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Terms & conditions of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Terms & conditions of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{{ substr(strip_tags(str_replace('&nbsp;', ' ', $web_config['about']->value)),0,160) }}">

@endpush

@section('content')
    <div class="container py-5 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <h2 class="text-center mb-3 headerTitle">{{translate('terms_and_Condition')}}</h2>
        <div class="card __card">
            <div class="card-body text-justify">
                {!! $terms_condition['value'] !!}
            </div>
        </div>
    </div>
@endsection
