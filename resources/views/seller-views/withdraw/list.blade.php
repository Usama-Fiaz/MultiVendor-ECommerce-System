@extends('layouts.back-end.app-seller')

@section('title', translate('withdraw_Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{translate('withdraw')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header flex-wrap gap-2">
                        <h5 class="mb-0 text-capitalize">{{ translate('withdraw_Request_Table')}}
                            <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $withdraw_requests->total() }}</span>
                        </h5>
                        <select name="withdraw_status_filter" onchange="status_filter(this.value)" class="custom-select max-w-200">
                            <option value="all" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all'?'selected':''}}>{{translate('all')}}</option>
                            <option value="approved" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved'?'selected':''}}>{{translate('approved')}}</option>
                            <option value="denied" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied'?'selected':''}}>{{translate('denied')}}</option>
                            <option value="pending" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending'?'selected':''}}>{{translate('pending')}}</option>
                        </select>
                    </div>

                    <td class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('amount')}}</th>
                                    <th>{{translate('request_time')}}</th>
                                    <th>{{translate('status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($withdraw_requests->count() > 0)
                                @foreach($withdraw_requests as $key=>$withdraw_request)
                                    <tr>
                                        <td>{{$withdraw_requests->firstitem()+$key}}</td>
                                        <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($withdraw_request['amount']))}}</td>
                                        <td>{{date("F jS, Y", strtotime($withdraw_request->created_at))}}</td>
                                        <td>
                                            @if($withdraw_request->approved==0)
                                                <label class="badge badge-soft--primary">{{translate('pending')}}</label>
                                            @elseif($withdraw_request->approved==1)
                                                <label class="badge badge-soft-success">{{translate('approved')}}</label>
                                            @else
                                                <label class="badge badge-soft-danger">{{translate('denied')}}</label>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($withdraw_request->approved==0)
                                                <button id="{{route('seller.business-settings.withdraw.cancel', [$withdraw_request['id']])}}"
                                                        onclick="close_request('{{ route('seller.business-settings.withdraw.cancel', [$withdraw_request['id']]) }}')"
                                                    class="btn btn--primary btn-sm">
                                                    {{translate('close')}}
                                                </button>
                                            @else
                                                <span class="btn btn--primary btn-sm disabled">
                                                    {{translate('close')}}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="5" class="text-center">
                                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                    <p class="mb-0">{{translate('no_data_to_show')}}</p>
                                </td>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$withdraw_requests->links()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection


@push('script_2')
  <script>
      function status_filter(type) {
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.post({
              url: '{{route('seller.business-settings.withdraw.status-filter')}}',
              data: {
                  withdraw_status_filter: type
              },
              beforeSend: function () {
                  $('#loading').fadeIn();
              },
              success: function (data) {
                 location.reload();
              },
              complete: function () {
                  $('#loading').fadeOut();
              }
          });
      }
  </script>

  <script>
      function close_request(route_name) {
          swal({
              title: "{{translate('are_you_sure')}}?",
              text: "{{translate('once_deleted_you_will_not_be_able_to_recover_this')}}",
              icon: "{{translate('warning')}}",
              buttons: true,
              dangerMode: true,
              confirmButtonText: "{{translate('ok')}}",
          })
              .then((willDelete) => {
                  if (willDelete.value) {
                      window.location.href = (route_name);
                  }
              });
      }
  </script>
@endpush
