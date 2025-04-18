@extends('layouts.back-end.app')
@section('title', translate('FAQ'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/Pages.png')}}" width="20" alt="">
                {{translate('pages')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('admin-views.business-settings.pages-inline-menu')
    <!-- End Inlile Menu -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('help_topic_Table')}} </h5>
                        <button class="btn btn--primary btn-icon-split for-addFaq" data-toggle="modal"
                                data-target="#addModal">
                            <i class="tio-add"></i>
                            <span class="text">{{translate('add_FAQ')}}  </span>
                        </button>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100"
                                id="dataTable" cellspacing="0"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('question')}}</th>
                                    <th class="min-w-200">{{translate('answer')}}</th>
                                    <th class="text-center">{{translate('ranking')}}</th>
                                    <th class="text-center">{{translate('status')}} </th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($helps as $k=>$help)
                                    <tr id="data-{{$help->id}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$help['question']}}</td>
                                        <td>{{$help['answer']}}</td>
                                        <td class="text-center">{{$help['ranking']}}</td>

                                        <td>
                                            <form action="{{ route('admin.helpTopic.status', ['id'=>$help['id']])}}" method="get" id="helpTopic_status{{$help['id']}}_form" class="helpTopic_status_form">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input" id="helpTopic_status{{$help['id']}}" {{ $help['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'helpTopic_status{{$help['id']}}','category-status-on.png','category-status-off.png','{{translate('want_to_Turn_ON_This_FAQ')}}','{{translate('want_to_Turn_OFF_This_FAQ')}}',`<p>{{translate('if_you_enable_this_FAQ_will_be_shown_in_the_user_app_and_website')}}</p>`,`<p>{{translate('if_you_disable_this_FAQ_will_not_be_shown_in_the_user_app_and_website')}}</p>`)">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-10">
                                                <a class="btn btn-outline--primary btn-sm edit"
                                                   data-toggle="modal" data-target="#editModal"
                                                   title="{{ translate('edit')}}"
                                                   data-id="{{ $help->id }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete"
                                                   title="{{ translate('delete')}}"
                                                   id="{{$help['id']}}">
                                                    <i class="tio-delete"></i>
                                                </a>
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
        </div>

        {{-- add modal --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('add_Help_Topic')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.helpTopic.add-new') }}" method="post" id="addForm">
                        @csrf
                        <div class="modal-body"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                            <div class="form-group">
                                <label>{{translate('question')}}</label>
                                <input type="text" class="form-control" name="question"
                                       placeholder="{{translate('type_Question')}}">
                            </div>


                            <div class="form-group">
                                <label>{{translate('answer')}}</label>
                                <textarea class="form-control" name="answer" cols="5"
                                          rows="5" placeholder="{{translate('type_Answer')}}"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-label">{{translate('status')}}</div>
                                        <label class="mt-2">
                                            <input type="checkbox" name="status" id="e_status" value="1"
                                                   class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span
                                                class="custom-switch-description">{{translate('active')}}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="ranking">{{translate('ranking')}}</label>
                                    <input type="number" name="ranking" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button class="btn btn--primary">{{translate('save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}

    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('edit_Modal_Help_Topic')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="editForm"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    @csrf
                    {{-- @method('put') --}}
                    <div class="modal-body">

                        <div class="form-group">
                            <label>{{translate('question')}}</label>
                            <input type="text" class="form-control" name="question"
                                   placeholder="{{translate('type_Question')}}"
                                   id="e_question" class="e_name">
                        </div>


                        <div class="form-group">
                            <label>{{translate('answer')}}</label>
                            <textarea class="form-control" name="answer" cols="5"
                                      rows="5" placeholder="{{translate('type_Answer')}}"
                                      id="e_answer"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ranking">{{translate('ranking')}}</label>
                                <input type="number" name="ranking" class="form-control" id="e_ranking" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{translate('close')}}</button>
                        <button class="btn btn--primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('public/assets/back-end')}}/js/demo/datatables-demo.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $('.helpTopic_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success(data.success);
                }
            });
        });


        $(document).on('click', '.edit', function () {
            let id = $(this).attr("data-id");
            console.log(id);
            $.ajax({
                url: "edit/" + id,
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    $("#e_question").val(data.question);
                    $("#e_answer").val(data.answer);
                    $("#e_ranking").val(data.ranking);


                    $("#editForm").attr("action", "update/" + data.id);


                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{translate("are_you_sure_delete_this_FAQ")}}?',
                text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate("yes_delete_it")}}!',
                cancelButtonText: '{{ translate("cancel") }}',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.helpTopic.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate("FAQ_deleted_successfully")}}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
    </script>
@endpush
