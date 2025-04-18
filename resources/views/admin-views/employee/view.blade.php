@extends('layouts.back-end.app')
@section('title', translate('employee_details'))
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
                {{translate('employee_details')}}
            </h2>
        </div>
        <!-- End Page Title -->
        <div class="card mt-3">
            <div class="card-body">
                <h3 class="mb-3 text-primary">#{{translate('EMP')}} {{$employee['id']}}</h3>

                <div class="row g-2">
                    <div class="col-lg-7 col-xl-8">
                        <div class="media align-items-center flex-wrap flex-sm-nowrap gap-3">
                            <img width="250" class="rounded" onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                            src="{{asset('storage/app/public/admin')}}/{{$employee['image']}}" alt="Image Description">
                            <div class="media-body">
                                <div class="text-capitalize mb-4">
                                    <h4 class="mb-2">{{$employee->name}}</h4>
                                    <p>{{isset($employee->role) ? $employee->role->name : translate('role_not_found')}}</p>
                                </div>

                                <ul class="d-flex flex-column gap-3 px-0">
                                    <li class="d-flex gap-2 align-items-center">
                                        <i class="tio-call"></i>
                                        <a href="tel:{{$employee->phone}}" class="text-dark">{{$employee->phone}}</a>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center">
                                        <i class="tio-email"></i>
                                        <a href="mailto:{{$employee->email}}" class="text-dark">{{$employee->email}}</a>
                                    </li>
                                    {{-- <li class="d-flex gap-2 align-items-center">
                                        <i class="tio-poi"></i>
                                        <span class="text-dark">Avenue-10, House# 12, Road# 12, Mirpur <br> DOHS, Dhaka - 1216</span>
                                    </li> --}}

                                    @if (!empty($employee->identify_type))
                                        <li class="d-flex gap-2 align-items-center">
                                            <i class="tio-credit-card"></i>
                                            <span class="text-dark text-uppercase">
                                                {{$employee->identify_type}} - {{isset($employee->identify_number)?$employee->identify_number: translate('identify_number_not_found')}}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4">
                        <div class="bg-primary-light rounded p-3">
                            <div class="bg-white rounded p-3">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="tio-calendar-month"></i>
                                    <span class="text-dark">{{translate('join')}}: {{date('d/m/Y',strtotime($employee->created_at))}}</span>
                                </div>
                            </div>
                            <div class="bg-white rounded p-3 mt-3">
                                <div class="d-flex justify-content-between gap-3">
                                    <div class="d-flex flex-column gap-4">
                                        <div class="d-flex gap-2 align-items-center">
                                            <i class="tio-account-square-outlined"></i>
                                            <h6 class="text-dark mb-0 text-capitalize">{{translate('access_abailable')}}:</h6>
                                        </div>
                                        @if (isset($employee->role))
                                            <div class="tags d-flex gap-2 flex-wrap">
                                                @foreach (json_decode($employee->role->module_access) as $key=>$value)
                                                    <span class="badge bg-primary-light text-capitalize">{{str_replace('_' ,' ',$value)}}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{route('admin.employee.update',[$employee['id']])}}">
                                        <i class="tio-edit"data-toggle="tooltip" data-placement="top" title="{{translate('you_can_create_or_edit_role_form_employee_role_setup')}}"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{route('admin.employee.update',[$employee['id']])}}" class="btn btn--primary px-5">
                                <i class="tio-edit"></i>
                                {{translate('edit')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('change', '.switcher_input', function () {
            let id = $(this).attr("id");

            let status = 0;
            if (jQuery(this).prop("checked") === true) {
                status = 1;
            }

            Swal.fire({
                title: '{{translate('are_you_sure')}}?',
                text: '{{translate('want_to_change_status')}}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{translate('no')}}',
                confirmButtonText: '{{translate('yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{url('/')}}/admin/employee/status",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function () {
                            toastr.success('{{translate('status_updated_successfully')}}');
                        }
                    });
                }
            })
        });
    </script>
@endpush
