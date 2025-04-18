@extends('layouts.back-end.app')
@section('title', translate('social_media'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/social media.png')}}" width="20" alt="">
                {{translate('social_media')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('social_media_form')}}</h5>
                    </div>
                    <div class="card-body">
                        <form style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="title-color">{{translate('name')}}</label>
                                        <select class="form-control w-100" name="name" id="name">
                                            <option>---{{translate('select')}}---</option>
                                            <option value="instagram">{{translate('instagram')}}</option>
                                            <option value="facebook">{{translate('facebook')}}</option>
                                            <option value="twitter">{{translate('twitter')}}</option>
                                            <option value="linkedin">{{translate('linkedIn')}}</option>
                                            <option value="pinterest">{{translate('pinterest')}}</option>
                                            <option value="google-plus">{{translate('google_plus')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <input type="hidden" id="id">
                                        <label for="link" class="title-color">{{ translate('social_media_link')}}</label>
                                        <input type="text" name="link" class="form-control" id="link"
                                               placeholder="{{translate('enter_Social_Media_Link')}}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" id="id">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-10 justify-content-end flex-wrap">
                                <a id="add" class="btn btn--primary px-4">{{ translate('save')}}</a>
                                <a id="update" class="btn btn--primary px-4 d--none">{{ translate('update')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <h5 class="mb-0 d-flex">{{ translate('social_media_table')}}</h5>
                    </div>
                    <div class="pb-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" id="dataTable" cellspacing="0"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ translate('sl')}}</th>
                                        <th>{{ translate('name')}}</th>
                                        <th>{{ translate('link')}}</th>
                                        <th>{{ translate('status')}}</th>
                                        {{-- <th>{{ translate('icon')}}</th> --}}
                                        <th>{{ translate('action')}}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        fetch_social_media();

        function fetch_social_media() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.fetch')}}",
                method: 'GET',
                success: function (data) {

                    if (data.length != 0) {
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<tr>';
                            html += '<td class="column_name" data-column_name="sl" data-id="' + data[count].id + '">' + (count + 1) + '</td>';
                            html += '<td class="column_name" data-column_name="name" data-id="' + data[count].id + '">' + data[count].name + '</td>';
                            html += '<td class="column_name" data-column_name="slug" data-id="' + data[count].id + '">' + data[count].link + '</td>';

                            html += `<td class="column_name" data-column_name="status" data-id="${data[count].id}">
                                <form action="{{route('admin.business-settings.social-media-status-update')}}" method="post" id="social_media_status${data[count].id}_form" class="social_media_status_form">
                                    @csrf
                                    <input type="hidden" name="id" value="${data[count].id}">
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input" id="social_media_status${data[count].id}" name="status" value="1" ${data[count].active_status == 1 ? "checked" : ""} onclick="toogleStatusModal(event,'social_media_status${data[count].id}','category-status-on.png','category-status-off.png','{{translate('Want_to_Turn_ON')}} ${data[count].name} {{translate('status')}}','{{translate('Want_to_Turn_OFF')}} ${data[count].name} {{translate('status')}}','<p>{{translate('if_enabled_this_icon_will_be_available_on_the_website_and_customer_app')}}</p>','<p>{{translate('if_disabled_this_icon_will_be_hidden_from_the_website_and_customer_app')}}</p>')">
                                        <span class="switcher_control"></span>
                                    </label>
                                </form>
                            </td>`;

                            // html += '<td><a type="button" class="btn btn--primary btn-xs edit" id="' + data[count].id + '"><i class="fa fa-edit text-white"></i></a> <a type="button" class="btn btn-danger btn-xs delete" id="' + data[count].id + '"><i class="fa fa-trash text-white"></i></a></td></tr>';
                            html += '<td><a type="button" class="btn btn-outline--primary btn-xs edit square-btn" id="' + data[count].id + '"><i class="tio-edit"></i></a> </td></tr>';
                        }
                        $('tbody').html(html);
                    }
                }
            });
        }

        $('#add').on('click', function () {
            $('#add').attr("disabled", true);
            var name = $('#name').val();
            var link = $('#link').val();
            if (name == "") {
                toastr.error("{{translate('social_Name_Is_Requeired.')}}");
                return false;
            }
            if (link == "") {
                toastr.error("{{translate('social_Link_Is_Requeired.')}}");
                return false;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media-store')}}",
                method: 'POST',
                data: {
                    name: name,
                    link: link
                },
                success: function (response) {
                    if (response.error == 1) {
                        toastr.error("{{translate('social_Media_Already_taken')}}");
                    } else {
                        toastr.success("{{translate('social_Media_inserted_Successfully.')}}");
                    }
                    $('#name').val('');
                    $('#link').val('');
                    fetch_social_media();
                }
            });
        });
        $('#update').on('click', function () {
            $('#update').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#name').val();
            var link = $('#link').val();
            var icon = $('#icon').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    name: name,
                    link: link,
                    icon: icon,
                },
                success: function (data) {
                    $('#name').val('');
                    $('#link').val('');
                    $('#icon').val('');

                    toastr.success("{{translate('social_info_updated_successfully.')}}");
                    $('#update').hide();
                    $('#add').show();
                    fetch_social_media();

                }
            });
            $('#save').hide();
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            if (confirm("{{translate('are_you_sure_delete_this_social_media')}}?")) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.business-settings.social-media-delete')}}",
                    method: 'POST',
                    data: {id: id},
                    success: function (data) {
                        fetch_social_media();
                        toastr.success("{{translate('social_media_deleted_successfully.')}}");
                    }
                });
            }
        });
        $(document).on('click', '.edit', function () {
            $('#update').show();
            $('#add').hide();
            var id = $(this).attr("id");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media-edit')}}",
                method: 'POST',
                data: {id: id},
                success: function (data) {
                    $(window).scrollTop(0);
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#link').val(data.link);
                    $('#icon').val(data.icon);
                    fetch_social_media()
                }
            });
        });

        $('.social_media_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success("{{translate('status_updated_successfully')}}");
                }
            });
        });

    </script>
@endpush
