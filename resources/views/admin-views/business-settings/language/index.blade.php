@extends('layouts.back-end.app')

@section('title', translate('language'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.system-settings-inline-menu')
        <!-- End Inlile Menu -->

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger mb-3" role="alert">
                    {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
                </div>

                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 d-flex">
                                    {{translate('language_table')}}
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div class="d-flex gap-10 justify-content-sm-end">
                                    <button class="btn btn--primary btn-icon-split" data-toggle="modal" data-target="#lang-modal">
                                        <i class="tio-add"></i>
                                        <span class="text">{{translate('add_new_language')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive pb-3">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL')}}</th>
                                <th>{{translate('ID')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('code')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('default_status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($language=App\Model\BusinessSetting::where('type','language')->first())
                            @foreach(json_decode($language['value'],true) as $key =>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data['id']}}</td>
                                    <td>{{$data['name']}} ( {{isset($data['direction'])?$data['direction']:'ltr'}}
                                        )
                                    </td>
                                    <td>{{$data['code']}}</td>
                                    <td>


                                        @if (array_key_exists('default', $data) && $data['default']==true)
                                            <label class="switcher mx-auto" onclick="default_language_status_alert()">
                                                <input type="checkbox" class="switcher_input" checked disabled>
                                                <span class="switcher_control"></span>
                                            </label>
                                        @else
                                            <form action="{{ route('admin.business-settings.language.update-status') }}" method="GET" id="language_id_{{$data['id']}}_form" class="language_id_form">
                                                @csrf
                                                <input type="hidden" name="code" value="{{$data['code']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input"
                                                    {{$data['status']==1?'checked':''}}
                                                    id="language_id_{{$data['id']}}" name="status" class="toggle-switch-input" onclick="toogleStatusModal(event,'language_id_{{$data['id']}}','language-on.png','language-off.png','{{translate('Want_to_Turn_ON_Language_Status')}}','{{translate('Want_to_Turn_OFF_Language_Status')}}',`<p>{{translate('if_enabled_this_language_will_be_available_throughout_the_entire_system')}}</p>`,`<p>{{translate('if_disabled_this_language_will_be_hidden_from_the_entire_system')}}</p>`)">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if (array_key_exists('default', $data) && $data['default']==true)
                                        <label class="switcher mx-auto" onclick="default_language_status_alert()">
                                            <input type="checkbox" class="switcher_input" checked disabled>
                                            <span class="switcher_control"></span>
                                        </label>
                                        @elseif(array_key_exists('default', $data) && $data['default']==false)
                                        <form action="{{route('admin.business-settings.language.update-default-status', ['code'=>$data['code']])}}" method="GET" id="language_default_id_{{$data['id']}}_form" class="language_default_id_form">
                                            @csrf
                                            <input type="hidden" name="code" value="{{$data['code']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input" id="language_default_id_{{$data['id']}}" name="default" class="toggle-switch-input" onclick="toogleStatusModal(event,'language_default_id_{{$data['id']}}','language-on.png','language-off.png','{{translate('Want_to_Change_Default_Language_Status')}}','{{translate('Want_to_Turn_OFF_Language_Status')}}',`<p>{{translate('if_enabled_this_language_will_be_set_as_default_for_the_entire_system')}}</p>`,`<p>{{translate('if_disabled_this_language_will_be_unset_as_default_for_the_entire_system')}}</p>`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                        @endif


                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-seconary btn-sm dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($data['code']!='en')
                                                    <a class="dropdown-item" data-toggle="modal"
                                                        data-target="#lang-modal-update-{{$data['code']}}">{{translate('update')}}</a>
                                                    @if ($data['default']==true)
                                                    <a class="dropdown-item"
                                                    href="javascript:" onclick="default_language_delete_alert()">{{translate('delete')}}</a>
                                                    @else
                                                        <a class="dropdown-item delete"
                                                            id="{{route('admin.business-settings.language.delete',[$data['code']])}}">{{translate('delete')}}</a>

                                                    @endif
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{route('admin.business-settings.language.translate',[$data['code']])}}">{{translate('translate')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="lang-modal" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('admin.business-settings.language.add-new')}}" method="post"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label">{{translate('language')}} </label>
                                        <input type="text" class="form-control" id="recipient-name" name="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="message-text"
                                               class="col-form-label">{{translate('country_code')}}</label>
                                        <select class="form-control country-var-select w-100" name="code">
                                            @foreach(\Illuminate\Support\Facades\File::files(base_path('public/assets/front-end/img/flags')) as $path)
                                                @if(pathinfo($path)['filename'] !='en')
                                                    <option value="{{ pathinfo($path)['filename'] }}"
                                                            title="{{ asset('public/assets/front-end/img/flags/'.pathinfo($path)['filename'].'.png') }}">
                                                        {{ strtoupper(pathinfo($path)['filename']) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">{{translate('direction')}} :</label>
                                        <select class="form-control" name="direction">
                                            <option value="ltr">LTR</option>
                                            <option value="rtl">RTL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('add')}} <i
                                    class="fa fa-plus"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach(json_decode($language['value'],true) as $key =>$data)
            <div class="modal fade" id="lang-modal-update-{{$data['code']}}" tabindex="-1" role="dialog"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('new_language')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('admin.business-settings.language.update')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                   class="col-form-label">{{translate('language')}} </label>
                                            <input type="text" class="form-control" value="{{$data['name']}}"
                                                   name="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="message-text"
                                                   class="col-form-label">{{translate('country_code')}}</label>
                                            <select class="form-control country-var-select w-100" name="code">
                                                @foreach(\Illuminate\Support\Facades\File::files(base_path('public/assets/front-end/img/flags')) as $path)
                                                    @if(pathinfo($path)['filename'] !='en' && $data['code']==pathinfo($path)['filename'])
                                                        <option value="{{ pathinfo($path)['filename'] }}"
                                                                title="{{ asset('public/assets/front-end/img/flags/'.pathinfo($path)['filename'].'.png') }}">
                                                            {{ strtoupper(pathinfo($path)['filename']) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">{{translate('direction')}} :</label>
                                            <select class="form-control" name="direction">
                                                <option
                                                    value="ltr" {{isset($data['direction'])?$data['direction']=='ltr'?'selected':'':''}}>
                                                    LTR
                                                </option>
                                                <option
                                                    value="rtl" {{isset($data['direction'])?$data['direction']=='rtl'?'selected':'':''}}>
                                                    RTL
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('update')}} <i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $('.language_id_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.business-settings.language.update-status') }}",
                method: 'GET',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success('{{translate("status_updated_successfully")}}');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // color select select2
            $('.country-var-select').select2({
                templateResult: codeSelect,
                templateSelection: codeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function codeSelect(state) {
                var code = state.title;
                if (!code) return state.text;
                return "<img class='image-preview' src='" + code + "'>" + state.text;
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $(".delete").click(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '{{translate("are_you_sure_to_delete_this")}}?',
                    text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                    showCancelButton: true,
                    confirmButtonColor: 'primary',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{translate("yes_delete_it")}}!',
                    cancelButtonText: '{{ translate("cancel") }}',
                }).then((result) => {
                    if (result.value) {
                        window.location.href = $(this).attr("id");
                    }
                })
            });
        });

    </script>
    <script>
        function default_language_delete_alert()
        {
            toastr.warning('{{translate("default_language_can_not_be_deleted") }}! {{translate("to_delete_change_the_default_language_first") }}!');
        }
        function default_language_status_alert()
        {
            toastr.warning('{{translate("default_language_can_not_be_deactive") }}!');
        }
    </script>
@endpush
