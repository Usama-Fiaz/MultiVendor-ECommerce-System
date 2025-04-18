@extends('errors::minimal')

{{--@section('title', __('Not Found'))
@section('code', '404')--}}

@section('message')
    <style>
        .for-margin {
            margin: auto;

            margin-bottom: 10%;
        }

        .for-margin {

        }

        .page-not-found {
            margin-top: 30px;
            font-weight: 600;
            text-align: center;
        }
    </style>
    <div class="container ">
        <div class="col-md-3"></div>
        <div class="col-md-6 for-margin">
            <div class="for-image">
                <img style="" src="{{asset("storage/app/public/png/404.png")}}" alt="">
            </div>
            <h2 class="page-not-found">{{ translate('page_not_found')}}</h2>

            <p style="text-align: center;">{{ translate('we_are_sorry_the_page_you_requested_could_not_be_found')}} <br> {{ translate('please_go_back_to_the_homepage')}}</p>
            <div style="text-align: center;">
                <a class="btn btn--primary" href="{{ route('home') }}"> {{ translate('home')}}</a>
            </div>

        </div>
        <div class="col-md-3"></div>
    </div>
@endsection
