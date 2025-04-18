@extends('layouts.back-end.app')
@section('title', translate('employee_list'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/employee.png')}}" width="20" alt="">
                {{translate('employee_list')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card">
            <div class="card-header flex-wrap gap-10">
                <!-- start -->
                <div class="px-sm-3 py-4 flex-grow-1">
                    <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center">
                        <div class="">
                            <h5 class="mb-0 text-capitalize gap-2">
                                {{translate('employee_table')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{$em->total()}}</span>
                            </h5>
                        </div>
                        <div class="align-items-center d-flex gap-3 justify-content-lg-end flex-wrap flex-lg-nowrap flex-grow-1">
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input type="search" name="search" class="form-control"
                                                placeholder="{{translate('search_by_name_or_email_or_phone')}}"
                                                value="{{$search}}" required>
                                        <button type="submit"
                                                class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="d-flex gap-2 align-items-center text-left">
                                        <div class="">
                                            <select class="form-control text-ellipsis min-w-200" name="employee_role_id">
                                                <option value="all" {{ request('employee_role') == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                                    @foreach($employee_roles as $employee_role)
                                                    <option value="{{ $employee_role['id'] }}" {{ request('employee_role_id') == $employee_role['id'] ? 'selected' : '' }}>
                                                            {{$employee_role['name']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="">
                                            <button type="submit" class="btn btn--primary px-4 w-100 text-nowrap">
                                                {{ translate('filter')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.employee.export',['role'=>request('employee_role_id'),'search'=>request('search')])}}">
                                            <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                            {{translate('excel')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="">

                                <a href="{{route('admin.employee.add-new')}}" class="btn btn--primary text-nowrap">
                                    <i class="tio-add"></i>
                                    <span class="text ">{{translate('add_new')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end -->


            <div class="table-responsive">
                <table id="datatable"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('name')}}</th>
                        <th>{{translate('email')}}</th>
                        <th>{{translate('phone')}}</th>
                        <th>{{translate('role')}}</th>
                        <th>{{translate('status')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($em as $k=>$e)
                        @if($e->role)
                            <tr>
                                <th scope="row">{{$k+1}}</th>
                                <td class="text-capitalize">
                                    <div class="media align-items-center gap-10">
                                        <img class="rounded-circle avatar avatar-lg"
                                                onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                                                src="{{asset('storage/app/public/admin')}}/{{$e['image']}}">
                                        <div class="media-body">
                                            {{$e['name']}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{$e['email']}}
                                </td>
                                <td>{{$e['phone']}}</td>
                                <td>{{$e->role['name']}}</td>
                                <td>
                                    <form action="{{url('/')}}/admin/employee/status" method="post" id="employee_id_{{$e['id']}}_form" class="employee_id_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$e['id']}}">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input" id="employee_id_{{$e['id']}}" name="status" class="toggle-switch-input" {{$e->status?'checked':''}} onclick="toogleStatusModal(event,'employee_id_{{$e['id']}}','employee-on.png','employee-off.png','{{translate('Want_to_Turn_ON_Employee_Status')}}','{{translate('Want_to_Turn_OFF_Employee_Status')}}',`<p>{{translate('if_enabled_this_employee_can_log_in_to_the_system_and_perform_his_role')}}</p>`,`<p>{{translate('if_disabled_this_employee_can_not_log_in_to_the_system_and_perform_his_role')}}</p>`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-10 justify-content-center">
                                        <a href="{{route('admin.employee.update',[$e['id']])}}"
                                            class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{translate('edit')}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-info btn-sm square-btn" title="View" href="{{route('admin.employee.view',['id'=>$e['id']])}}">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {{$em->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.employee_id_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('/')}}/admin/employee/status",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success(data.message);
                }
            });
        });
    </script>
@endpush
