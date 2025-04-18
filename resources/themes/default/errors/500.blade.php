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
                <img style="" src="{{asset("storage/app/public/png/500.png")}}" alt="">
            </div>
            <h2 class="page-not-found">{{ translate('server_Error') }}</h2>
            <p style="text-align: center;">{{ translate('we_are_sorry_server_is_not_responding.')}} <br> {{ translate('try_after_sometime')}}.</p>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection
