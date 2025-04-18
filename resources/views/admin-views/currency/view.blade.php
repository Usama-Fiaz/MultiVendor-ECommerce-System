@extends('layouts.back-end.app')

@section('title', translate('currency'))

@push('css_or_js')

@endpush

@section('content')
    @php($currency_model=\App\CPU\Helpers::get_business_settings('currency_model'))
    @php($default=\App\CPU\Helpers::get_business_settings('system_default_currency'))
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('system_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inline Menu -->
        @include('admin-views.business-settings.system-settings-inline-menu')
        <!-- End Inline Menu -->


        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <img width="20 " src="{{asset('/public/assets/back-end/img/currency-1.png')}}" alt="">
                    {{translate('default-currency_setup')}}
                </h5>
            </div>
            <div class="card-body">
                <form class="form-inline_ text-start" action="{{route('admin.currency.system-currency-update')}}" method="post">
                    @csrf
                    @php($default=\App\Model\BusinessSetting::where('type', 'system_default_currency')->first())
                    <div class="form-group">
                        <label for="currency_id" class="title-color">{{translate('currency')}}</label>
                        <select class="form-control js-select2-custom" name="currency_id">
                            @foreach (App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                <option
                                    value="{{ $currency->id }}" {{$default->value == $currency->id?'selected':''}} >
                                    {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap mt-3">
                        <button type="submit" class="btn btn--primary px-5">{{translate('save')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <img width="18" src="{{asset('/public/assets/back-end/img/currency-1.png')}}" alt="">
                    {{translate('add_currency')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.currency.store')}}" method="post">
                    @csrf
                    <div class="">
                        <div class="row">
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label for="name" class="title-color mb-0">{{translate('currency_name')}}</label>
                                        <i class="tio-info-outined" data-toggle="tooltip" title="{{translate('add_the_name_of_the_currency_you_want_to_add')}}"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="{{translate('ex')}} : United States Dollar" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label for="symbol" class="title-color mb-0">{{translate('currency_symbol')}}</label>
                                        <i class="tio-info-outined" data-toggle="tooltip" title="{{translate('add_the_symbol_of_the_currency_you_want_to_add')}}"></i>
                                    </div>
                                    <input type="text" name="symbol" class="form-control" id="symbol" placeholder="{{translate('ex')}} : $" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label for="symbol" class="title-color mb-0">{{translate('currency_code')}}</label>
                                        <i class="tio-info-outined" data-toggle="tooltip" title="{{translate('add_the_code_of_the_currency_you_want_to_add')}}"></i>
                                    </div>
                                    <input type="text" name="code" class="form-control" id="code" placeholder="{{translate('ex')}} : USD" required>
                                </div>
                            </div>
                            @if($currency_model=='multi_currency')
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <label for="exchange_rate" class="title-color mb-0">{{translate('exchange_rate')}}</label>
                                        <i class="tio-info-outined" data-toggle="tooltip" title="{{translate('based_on_your_region_set_the_exchange_rate_of_the_currency_you_want_to_add')}}"></i>
                                    </div>
                                    <input type="number" min="0" max="1000000" name="exchange_rate" step="0.00000001" class="form-control" id="exchange_rate" placeholder="{{translate('ex')}} : 120" required>
                                </div>
                            </div>
                            @endif
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-secondary px-5">{{translate('reset')}}</button>
                                    <button type="submit" id="add" class="btn btn--primary px-5">{{translate('submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-4">
                    <div class="table-responsive">
                        <table
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-start">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('currency_name')}}</th>
                                    <th>{{translate('currency_symbol')}}</th>
                                    <th>{{translate('currency_code')}}</th>
                                    @if($currency_model=='multi_currency')
                                        <th>{{translate('exchange_rate')}}
                                            (1 {{App\Model\Currency::where('id', $default->value)->first()->code}}= ?)
                                        </th>
                                    @endif
                                    <th>{{translate('status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($currencies as $key =>$data)
                                <tr>
                                    <td>{{$currencies->firstitem()+ $key }}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->symbol}}</td>
                                    <td>{{$data->code}}</td>
                                    @if($currency_model=='multi_currency')
                                        <td>{{$data->exchange_rate}}</td>
                                    @endif
                                    <td>
                                        @if($default['value']!=$data->id)

                                            <form action="{{route('admin.currency.status')}}" method="post" id="currency_status{{$data['id']}}_form" class="currency_status_form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$data['id']}}">
                                                <label class="switcher">
                                                    <input type="checkbox" class="switcher_input" id="currency_status{{$data['id']}}" name="status" class="toggle-switch-input" {{$data->status?'checked':''}} onclick="toogleStatusModal(event,'currency_status{{$data['id']}}','currency-on.png','currency-off.png','{{translate('Want_to_Turn_ON_Currency_Status')}}','{{translate('Want_to_Turn_OFF_Currency_Status')}}',`<p>{{translate('if_enabled_this_currency_will_be_available_throughout_the_entire_system')}}</p>`,`<p>{{translate('if_disabled_this_currency_will_be_hidden_from_the_entire_system')}}</p>`)">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>

                                        @else
                                            <label class="badge badge-primary-light">{{translate('default')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            @if($data->code!='USD')
                                                <a  title="{{translate('edit')}}"
                                                    type="button" class="btn btn-outline--primary btn-sm btn-xs edit"
                                                    href="{{route('admin.currency.edit',[$data->id])}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                @if ($default['value']!=$data->id)
                                                <a  title="{{translate('delete')}}"
                                                    type="button" class="btn btn-outline-danger btn-sm btn-xs delete"
                                                    id="{{$data->id}}"
                                                    >
                                                    <i class="tio-delete"></i>
                                                </a>
                                                @else
                                                    <a href="javascript:" title="{{translate('delete')}}"
                                                        type="button" class="btn btn-outline-danger btn-sm btn-xs"
                                                        onclick="default_currency_delete_alert()"
                                                        >
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <button title="{{translate('edit')}}"
                                                        class="btn btn-outline--primary btn-sm btn-xs edit" disabled>
                                                    <i class="tio-edit"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$currencies->links()}}
                        </div>
                    </div>

                    @if(count($currencies)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                    alt="Image Description">
                            <p class="mb-0">{{translate('no_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <!-- Page level custom scripts -->
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $('.currency_status_form').on('submit', function(event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.currency.status')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status === 1) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.delete', function () {
        var id = $(this).attr("id");
        Swal.fire({
            title: "{{translate('are_you_sure_delete_this')}} ?",
            text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{translate('yes_delete_it')}}!",
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
                    url: "{{route('admin.currency.delete')}}",
                    method: 'POST',
                    data: {id: id},
                    success: function (data) {

                        if(data.status ==1){
                            toastr.success("{{translate('currency_removed_successfully')}} !");
                            location.reload();
                        }else{
                            toastr.warning("{{translate('this_Currency_cannot_be_removed_due_to_payment_gateway_dependency')}} !");
                            location.reload();
                        }
                    }
                });
            }
        })
    });
    </script>
    <script>
        function default_currency_delete_alert()
        {
            toastr.warning('{{translate('default currency can not be deleted!to delete change the default currency first!')}}');
        }
    </script>
@endpush
