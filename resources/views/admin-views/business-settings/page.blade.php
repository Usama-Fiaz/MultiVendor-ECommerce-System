@extends('layouts.back-end.app')

@section('title', translate(str_replace('-',' ',$page)))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/Pages.png')}}" alt="">
                {{translate('pages')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.pages-inline-menu')
        <!-- End Inlile Menu -->

        @php( $page_data= json_decode($data->value, true))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('admin.business-settings.page-update', [$page])}}" method="post">
                        @csrf

                        <div class="card-header">
                            <h5 class="mb-0">{{translate(str_replace('-',' ',$page))}}</h5>

                            <label class="switcher show-status-text justify-content-end" for="seller_pos">
                                <input type="checkbox" class="switcher_input" value="1" name="status" id="seller_pos" {{ $page_data['status'] == 1 ? 'checked':'' }} onclick="toogleModal(event,'seller_pos','pos-seller-on.png','pos-seller-off.png','{{translate('want_to_Turn_ON')}} {{ translate(str_replace('-','_',$page)) }} {{ translate('status') }}?','{{translate('want_to_Turn_OFF')}} {{ translate(str_replace('-','_',$page)) }} {{ translate('status') }}?',`<p>{{translate('if_you_enable_this_option_'.str_replace('-','_',$page).'_page_will_be_shown_in_the_user_app_and_website')}}</p>`,`<p>{{translate('if_you_disable_this_option_'.str_replace('-','_',$page).'_page_will_not_be_shown_in_the_user_app_and_website')}}</p>`)">
                                <span class="switcher_control" data-ontitle="{{ translate('on') }}" data-offtitle="{{ translate('off') }}"></span>
                            </label>

                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control" id="editor"
                                    name="value">{{ $page_data['content'] }}</textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control btn--primary" type="submit" value="{{translate('submit')}}" name="btn">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{--ck editor--}}
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
    {{--ck editor--}}
@endpush
