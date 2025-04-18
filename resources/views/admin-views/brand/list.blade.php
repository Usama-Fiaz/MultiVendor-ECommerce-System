@extends('layouts.back-end.app')

@section('title', translate('brand_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/brand.png')}}" alt="">
                {{translate('brand_List')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $br->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <!-- Data Table Top -->
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('search_by_name')}}" aria-label="Search by ID or name" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary input-group-text">{{ translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.brand.export',['search'=>request('search')]) }}">
                                            <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                            {{ translate('excel') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('SL')}}</th>
                                    <th>{{ translate('brand_Logo')}}</th>
                                    <th>{{ translate('name')}}</th>
                                    <th>{{ translate('total_Product')}}</th>
                                    <th>{{ translate('total_Order')}}</th>
                                    <th class="text-center">{{translate('status')}}</th>
                                    <th class="text-center">
                                        {{ translate('action')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($br as $k=>$b)
                                    <tr>
                                        <td>{{$br->firstItem()+$k}}</td>
                                        <td>
                                            <img class="rounded avatar-60"
                                                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                                 src="{{asset('storage/app/public/brand')}}/{{$b['image']}}">
                                        </td>
                                        <td>{{$b['defaultname']}}</td>
                                        <td>{{ $b['brand_all_products_count'] }}</td>
                                        <td>{{ $b['brandAllProducts']->sum('order_details_count') }}</td>
                                        <td>
                                            <form action="{{route('admin.brand.status-update')}}" method="post" id="brand_status{{$b['id']}}_form" class="brand_status_form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$b['id']}}">
                                                <label class="switcher mx-auto">
                                                    <input type="checkbox" class="switcher_input" id="brand_status{{$b['id']}}" name="status" value="1" {{ $b['status'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'brand_status{{$b['id']}}','brand-status-on.png','brand-status-off.png','{{translate('Want_to_Turn_ON')}} {{$b['defaultname']}} {{translate('status')}}','{{translate('Want_to_Turn_OFF')}} {{$b['defaultname']}} {{translate('status')}}',`<p>{{translate('if_enabled_this_brand_will_be_available_on_the_website_and_customer_app')}}</p>`,`<p>{{translate('if_disabled_this_brand_will_be_hidden_from_the_website_and_customer_app')}}</p>`)">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn" title="{{ translate('edit')}}"
                                                href="{{route('admin.brand.update',[$b['id']])}}">
                                                <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete square-btn" title="{{ translate('delete')}}"
                                                id="{{$b['id']}}">
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
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$br->links()}}
                        </div>
                    </div>
                    @if(count($br)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{ translate('no_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ translate('are_you_sure_delete_this_brand')}}?",
                text: "{{ translate('you_will_not_be_able_to_revert_this')}}!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ translate('yes_delete_it')}}!",
                cancelButtonText: "{{ translate('cancel')}}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.brand.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success("{{ translate('brand_deleted_successfully')}}");
                            location.reload();
                        }
                    });
                }
            })
        });

        $('.brand_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.brand.status-update')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success == true) {
                        toastr.success("{{translate('status_updated_successfully')}}");
                    } else {
                        toastr.error('{{translate("status_updated_failed.")}} {{translate("Product_must_be_approved")}}');
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
