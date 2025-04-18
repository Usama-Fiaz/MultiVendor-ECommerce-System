@extends('layouts.back-end.app')

@section('title', translate('offline_Payment_Method'))

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

        <nav>
            <div class="nav nav-tabs mb-3 border-0" role="tablist">
              <a class="nav-link {{ !request()->has('status') ? 'active':'' }}" href="{{route('admin.business-settings.payment-method.offline')}}">{{ translate('all') }}</a>
              <a class="nav-link {{ request('status') == 'active' ? 'active':'' }}" href="{{route('admin.business-settings.payment-method.offline')}}?status=active">{{ translate('active') }}</a>
              <a class="nav-link {{ request('status') == 'inactive' ? 'active':'' }}" href="{{route('admin.business-settings.payment-method.offline')}}?status=inactive">{{ translate('inactive') }}</a>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                <div class="card">
                    <!-- Data Table Top -->
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ route('admin.business-settings.payment-method.offline') }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('search_by_name') }}" aria-label="Search by ID or name" value="{{ request('search') }}" required="">
                                        <button type="submit" class="btn btn--primary input-group-text">{{ translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <a href="{{route('admin.business-settings.payment-method.offline.new')}}" class="btn btn--primary"><i class="tio-add"></i> {{ translate('add_New_Method') }}</a>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('payment_Method_Name') }}</th>
                                        <th>{{ translate('payment_Info') }}</th>
                                        <th>{{ translate('required_Info_From_Customer') }}</th>
                                        <th class="text-center">{{ translate('status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($methods as $key=>$method)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{ $method->method_name }}</td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($method->method_fields as $key=>$item)
                                                        <div>{{ ucwords(str_replace('_',' ',$item['input_name'])) }} : {{ $item['input_data'] }}</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach ($method->method_informations as $key=>$item)
                                                    <div>
                                                        {{ ucwords(str_replace('_',' ',$item['customer_input'])) }}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>

                                                <form action="{{route('admin.business-settings.payment-method.offline.status')}}" method="post" id="method_status{{$method['id']}}_form" class="method_status_form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$method['id']}}">
                                                    <label class="switcher mx-auto">
                                                        <input type="checkbox" class="switcher_input" id="method_status{{$method['id']}}" name="status" {{ $method->status == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'method_status{{$method['id']}}','offline-payment-on.png','offline-payment-off.png','{{translate('Want_to_Turn_ON_Offline_Payment_Methods')}}','{{translate('Want_to_Turn_OFF_Offline_Payment_Methods')}}',`<p>{{translate('if_enabled_customers_can_pay_through_different_payment_methods_outside_your_system')}}</p>`,`<p>{{translate('if_disabled_customers_can_only_pay_through_the_system_supported_payment_methods')}}</p>`)">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>

                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-info btn-sm square-btn" title="Edit" href="{{route('admin.business-settings.payment-method.offline.edit', ['id'=>$method->id])}}">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger btn-sm delete square-btn" title="Delete" onclick="form_alert('delete-method_name-{{ $method->id }}', '{{ translate('Want_to_delete_this_item') }} ?')">
                                                        <i class="tio-delete"></i>
                                                    </button>

                                                    <form action="{{route('admin.business-settings.payment-method.offline.delete')}}" method="post" id="delete-method_name-{{ $method->id }}">
                                                        @csrf
                                                        <input type="hidden" value="{{ $method->id }}" name="id" required>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($methods->count() > 0)
                                <div class="p-3 d-flex justify-content-end">
                                    @php
                                        if (request()->has('status')) {
                                            $paginationLinks = $methods->links();
                                            $modifiedLinks = preg_replace('/href="([^"]*)"/', 'href="$1&status='.request('status').'"', $paginationLinks);
                                        } else {
                                            $modifiedLinks = $methods->links();
                                        }
                                    @endphp

                                    {!! $modifiedLinks !!}
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                    <p class="mb-0">{{ translate('no_data_to_show')}}</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $('.method_status_form').on('submit', function(event){
        event.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.business-settings.payment-method.offline.status')}}",
            method: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                if(data.success_status == 1) {
                    toastr.success(data.message);
                }else if(data.success_status == 0) {
                    toastr.error(data.message);
                }
                setTimeout(function(){
                    location.reload();
                }, 1000);
            }
        });
    });
</script>
@endpush
