@extends('layouts.front-end.app')

@section('title',translate('payment_Incomplete'))

@push('css_or_js')
    <style>
        .spanTr {
            color: {{$web_config['primary_color']}};
        }


        .amount {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5 __inline-64">
        <div class="card">
            <div class="card-body py-5">
                <center>
                    <h3>{{translate('order_payment_is_incomplete')}}.</h3>
                    <div class="justify-content-center mt-4">
                        <a href="{{route('home')}}" class="btn btn--primary px-5">
                            {{translate('go_to_shopping')}}
                        </a>
                    </div>
                </center>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
