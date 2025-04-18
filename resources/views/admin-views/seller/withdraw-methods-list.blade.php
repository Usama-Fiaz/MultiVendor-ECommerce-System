@extends('layouts.back-end.app')

@section('title', translate('withdraw_method_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdraw_method_list')}}
                </h2>
                <a href="{{route('admin.sellers.withdraw-method.create')}}" class="btn btn--primary">+ {{translate('add_method')}}</a>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ translate('methods')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1"> {{ $withdrawal_methods->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{translate('search_Method_Name')}}" aria-label="Search orders"
                                               value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('method_name')}}</th>
                                <th>{{ translate('method_fields') }}</th>
                                <th class="text-center">{{translate('active_status')}}</th>
                                <th class="text-center">{{translate('default_method')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdrawal_methods as $key=>$withdrawal_method)
                                <tr>
                                    <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                                    <td>{{$withdrawal_method['method_name']}}</td>
                                    <td>
                                        @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                                            <span class="badge badge-success opacity-75 fz-12 border border-white">
                                                <b>{{translate('name')}}:</b> {{translate($method_field['input_name'])}} |
                                                <b>{{translate('type')}}:</b> {{ $method_field['input_type'] }} |
                                                <b>{{translate('placeholder')}}:</b> {{ $method_field['placeholder'] }} |
                                                <b>{{translate('is_Required')}}:</b> {{ $method_field['is_required'] ? translate('yes') : translate('no') }}
                                            </span><br/>
                                        @endforeach
                                    </td>
                                    <td>

                                        <form action="{{route('admin.sellers.withdraw-method.status-update')}}" method="post" id="withdrawal_method_status{{$withdrawal_method['id']}}_form" class="withdrawal_method_status_form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$withdrawal_method['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input" id="withdrawal_method_status{{$withdrawal_method['id']}}" name="status" value="1" {{ $withdrawal_method['is_active'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'withdrawal_method_status{{$withdrawal_method['id']}}','wallet-on.png','wallet-off.png','{{translate('want_to_Turn_ON_This_Withdraw_Method')}}','{{translate('want_to_Turn_OFF_This_Withdraw_Method')}}',`<p>{{translate('if_you_enable_this_Withdraw_method_will_be_shown_in_the_seller_app_and_seller_panel')}}</p>`,`<p>{{translate('if_you_disable_this_Withdraw_method_will_not_be_shown_in_the_seller_app_and_seller_panel')}}</p>`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>

                                    </td>
                                    <td>
                                        <form action="{{route('admin.sellers.withdraw-method.default-status-update')}}" method="post" id="withdrawal_method_default{{$withdrawal_method['id']}}_form" class="withdrawal_method_default_form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$withdrawal_method['id']}}">
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="switcher_input" id="withdrawal_method_default{{$withdrawal_method['id']}}" name="status" value="1" {{ $withdrawal_method['is_default'] == 1 ? 'checked':'' }} onclick="toogleStatusModal(event,'withdrawal_method_default{{$withdrawal_method['id']}}','wallet-on.png','wallet-off.png','{{translate('want_to_Turn_ON_This_Withdraw_Method')}}','{{translate('want_to_Turn_OFF_This_Withdraw_Method')}}',`<p>{{translate('if_you_enable_this_Withdraw_method_will_be_set_as_Default_Withdraw_Method_in_the_seller_app_and_seller_panel')}}</p>`,`<p>{{translate('if_you_disable_this_Withdraw_method_will_be_remove_as_Default_Withdraw_Method_in_the_seller_app_and_seller_panel')}}</p>`)">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('admin.sellers.withdraw-method.edit',[$withdrawal_method->id])}}"
                                               class="btn btn-outline--primary btn-sm square-btn">
                                                <i class="tio-edit"></i>
                                            </a>

                                            @if(!$withdrawal_method->is_default)
                                                <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                                   title="{{translate('delete')}}"
                                                   onclick="form_alert('delete-{{$withdrawal_method->id}}','{{translate('want_to_delete_this_item?')}}')">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('admin.sellers.withdraw-method.delete',[$withdrawal_method->id])}}"
                                                      method="post" id="delete-{{$withdrawal_method->id}}">
                                                    @csrf @method('delete')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($withdrawal_methods)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-160"
                                        src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                        alt="Image Description">
                                <p class="mb-0">{{translate('no_data_to_show')}}</p>
                            </div>
                       @endif
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {{$withdrawal_methods->links()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script_2')
  <script>

        $('.withdrawal_method_default_form').on('submit', function(event){
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
                    if(data.success == true) {
                        toastr.success('{{translate("default_Method_updated_successfully")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                    else if(data.success == false) {
                        toastr.error('{{translate("default_Method_updated_failed")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });

        $('.withdrawal_method_status_form').on('submit', function(event){
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
                    if(data.success == true) {
                        toastr.success('{{translate("status_updated_successfully")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                    else if(data.success == false) {
                        toastr.error('{{translate("status_update_failed")}}');
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });
  </script>
@endpush
