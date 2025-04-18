@extends('layouts.back-end.app')

@section('title', translate('SMS_Module_Setup'))

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

        <div class="row gy-3" id="sms-gatway-cards">

            @foreach($sms_gateways as $key=>$sms_config)
                <div class="col-md-6">
                    <div class="card h-100">
                        <form action="{{route('admin.business-settings.addon-sms-set')}}" method="POST"
                              id="{{$sms_config->key_name}}-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header d-flex flex-wrap align-content-around">
                                <h5>
                                    <span class="text-uppercase">{{str_replace('_',' ',$sms_config->key_name)}}</span>
                                </h5>

                                <?php
                                    $img_path = asset('/public/assets/back-end/img/modal/sms/'.$sms_config->key_name.'.png');
                                ?>

                                <label class="switcher show-status-text">
                                    <input class="switcher_input" type="checkbox" name="status" value="1"
                                    onclick="smsMethodStatusModal(event,'{{$sms_config->key_name}}','{{ $img_path }}',
                                    '{{translate('want_to_Turn_ON_')}}{{ucwords(str_replace('_',' ',$sms_config->key_name))}}{{translate('_as_the_SMS_Gateway')}}?','{{translate('want_to_Turn_OFF_')}}{{ucwords(str_replace('_',' ',$sms_config->key_name))}}{{translate('_as_the_SMS_Gateway')}}??',
                                    `<p>{{translate('if_enabled_system_can_use_this_SMS_Gateway')}}</p>`,
                                    `<p>{{translate('if_disabled_system_cannot_use_this_SMS_Gateway')}}</p>`)"
                                        id="{{$sms_config->key_name}}" {{$sms_config['is_active']==1?'checked':''}}>

                                    <span class="switcher_control" data-ontitle="{{ translate('on') }}" data-offtitle="{{ translate('off') }}"></span>
                                </label>
                            </div>

                            <div class="card-body">

                                <input name="gateway" value="{{$sms_config->key_name}}" class="d-none">
                                <input name="mode" value="live" class="d-none">

                                @php($skip=['gateway','mode','status'])
                                @foreach($sms_config->live_values as $key=>$value)
                                    @if(!in_array($key,$skip))
                                        <div class="form-group" style="margin-bottom: 10px">
                                            <label for="exampleFormControlInput1"
                                                   class="form-label">{{ucwords(str_replace('_',' ',$key))}}
                                                   <span class="text-danger">*</span>
                                                </label>
                                            <input type="text" class="form-control"
                                                   name="{{$key}}"
                                                   placeholder="{{ucwords(str_replace('_',' ',$key))}}"
                                                   value="{{env('APP_ENV')=='demo'?'':$value}}">
                                        </div>
                                    @endif
                                @endforeach

                                <div class="text-right" style="margin-top: 20px">
                                    <button type="submit" class="btn btn-primary px-5">{{translate('save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection

@push('script')
    <script>
        function smsMethodStatusModal(e, toggle_id, image, on_title, off_title, on_message, off_message) {
            e.preventDefault();

            $('#toggle-status-image').attr('src', image);
            if ($('#'+toggle_id).is(':checked')) {
                $('#toggle-status-title').empty().append(on_title);
                $('#toggle-status-message').empty().append(on_message);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
                $('.toggle-modal-img-box .status-icon').attr('src', '{{ asset("/public/assets/back-end/img/modal/status-green.png") }}');
            } else {
                $('#toggle-status-title').empty().append(off_title);
                $('#toggle-status-message').empty().append(off_message);
                $('#toggle-status-ok-button').attr('toggle-ok-button', toggle_id);
                $('.toggle-modal-img-box .status-icon').attr('src', '{{ asset("/public/assets/back-end/img/modal/status-warning.png") }}');
            }
            $('#toggle-status-modal').modal('show');
        }

        @if($payment_gateway_published_status == 1)
            $('#sms-gatway-cards').find('input').each(function(){
                $(this).attr('disabled', true);
            });
            $('#sms-gatway-cards').find('select').each(function(){
                $(this).attr('disabled', true);
            });
            $('#sms-gatway-cards').find('.switcher_input').each(function(){
                $(this).removeAttr('checked', true);
            });
            $('#sms-gatway-cards').find('button').each(function(){
                $(this).attr('disabled', true);
            });
        @endif
    </script>
@endpush
