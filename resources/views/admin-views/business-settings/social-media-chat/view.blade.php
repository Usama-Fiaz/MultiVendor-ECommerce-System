@extends('layouts.back-end.app')

@section('title', translate('social_Media_Chatting'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{translate('3rd_party')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.third-party-inline-menu')
        <!-- End Inlile Menu -->

        @php($messenger = \App\CPU\Helpers::get_business_settings('messenger'))
{{--                    <div class="card mb-3">--}}
{{--                        <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">--}}
{{--                            <form--}}
{{--                                action="{{route('admin.social-media-chat.update',['messenger'])}}"--}}
{{--                                method="post">--}}
{{--                                @csrf--}}
{{--                                <div class="d-flex flex-column align-items-center gap-2 mb-3">--}}
{{--                                    <h4 class="text-center">{{translate('messenger')}}</h4>--}}
{{--                                </div>--}}
{{--                                @if($messenger)--}}
{{--                                    <label class="switcher position-absolute right-3 top-3">--}}
{{--                                        <input class="switcher_input" type="checkbox" value="1" name="status" {{$messenger['status']==1?'checked':''}}>--}}
{{--                                        <span class="switcher_control"></span>--}}
{{--                                    </label>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="title-color font-weight-bold text-capitalize">{{translate('paste_script')}}</label>--}}
{{--                                        <textarea class="form-control" rows="8" name="script">--}}
{{--                                            {{ $messenger['script'] }}--}}
{{--                                        </textarea>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex justify-content-end flex-wrap">--}}
{{--                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-4">{{translate('save')}}</button>--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-10">--}}
{{--                                        <button type="submit" class="btn btn--primary px-4 text-uppercase">{{translate('Configure')}}</button>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                @php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
                <div class="card overflow-hidden">
                    <form action="{{route('admin.social-media-chat.update',['whatsapp'])}}" method="post">
                        @csrf
                        <div class="card-header mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <img width="16" src="{{asset('/public/assets/back-end/img/whatsapp.png')}}" alt="">
                                <h4 class="text-center mb-0">{{translate('whatsApp')}}</h4>
                            </div>

                            <label class="switcher">
                                <input class="switcher_input" type="checkbox" value="1"
                                onclick="toogleStatusModal(event,'whatsapp_id','social/whatsapp-on.png','social/whatsapp-off.png',
                                '{{translate('want_to_turn_ON_WhatsApp_as_social_media_chat_option')}}?','{{translate('want_to_turn_OFF_WhatsApp_as_social_media_chat_option')}}?',
                                `<p>{{translate('if_enabled,WhatsApp_chatting_option_will_be_available_in_the_system')}}</p>`,
                                `<p>{{translate('if_disabled,_WhatsApp_chatting_option_will_be_hidden_from_the_system ')}}</p>`)"

                                id="whatsapp_id" name="status" {{$whatsapp['status']==1?'checked':''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>

                        <div class="card-body text-start">
                            @if($whatsapp)
                                <div class="form-group">
                                    <label class="title-color font-weight-bold text-capitalize">{{translate('whatsapp_number')}}</label>
                                    <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{translate('provide_a_WhatsApp_number_without_country_code')}}">
                                        <img class="info-img" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="img">
                                    </span>
                                    <input type="text" class="form-control form-ellipsis" name="phone" value="{{ $whatsapp['phone'] }}" placeholder="{{translate('Ex: 1234567890')}}">
                                </div>
                                <div class="d-flex justify-content-end flex-wrap gap-3">
                                    <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-5">{{translate('save')}}</button>
                                </div>
                            @else
                                <div class="mt-3 d-flex flex-wrap justify-content-center gap-10">
                                    <button type="submit" class="btn btn--primary px-5 text-uppercase">{{translate('configure')}}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
