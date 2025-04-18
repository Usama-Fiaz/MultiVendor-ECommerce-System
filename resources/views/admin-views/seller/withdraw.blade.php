@extends('layouts.back-end.app')

@section('title', translate('Withdraw_Request'))

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
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ translate('Withdraw_Request_Table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $withdraw_req->total() }}</span>
                                </h5>
                            </div>
                            <div class="d-flex col-auto gap-3">
                                <select name="withdraw_status_filter" onchange="status_filter(this.value)" class="custom-select min-w-120">
                                    <option value="all" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all'?'selected':''}}>{{translate('all')}}</option>
                                    <option value="approved" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved'?'selected':''}}>{{translate('approved')}}</option>
                                    <option value="denied" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied'?'selected':''}}>{{translate('denied')}}</option>
                                    <option value="pending" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending'?'selected':''}}>{{translate('pending')}}</option>
                                </select>
                                <div>
                                    <button type="button" class="btn btn-outline--primary text-nowrap btn-block"
                                            data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        {{translate('export')}}
                                        <i class="tio-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.sellers.withdraw-list-export-excel') }}">
                                                <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                                {{translate('excel')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                                <th>{{translate('amount')}}</th>
                                <th>{{ translate('name') }}</th>
                                <th>{{translate('request_time')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdraw_req as $k=>$wr)
                                <tr>
                                    <td scope="row">{{$withdraw_req->firstItem()+$k}}</td>
                                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($wr['amount']))}}</td>

                                    <td>
                                        @if (isset($wr->seller))
                                            <a href="{{route('admin.sellers.view',$wr->seller_id)}}" class="title-color hover-c1">{{ $wr->seller->f_name . ' ' . $wr->seller->l_name }}</a>
                                        @else
                                        <a href="#">{{translate('not_found')}}</a>
                                        @endif
                                    </td>
                                    <td>{{$wr->created_at}}</td>
                                    <td class="text-center">
                                        @if($wr->approved==0)
                                            <label class="badge badge-soft-primary">{{translate('pending')}}</label>
                                        @elseif($wr->approved==1)
                                            <label class="badge badge-soft-success">{{translate('approved')}}</label>
                                        @else
                                            <label class="badge badge-soft-danger">{{translate('denied')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            @if (isset($wr->seller))
                                            <a href="{{route('admin.sellers.withdraw_view',[$wr['id'],$wr->seller['id']])}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                title="{{translate('view')}}">
                                                <i class="tio-invisible"></i>
                                                </a>
                                            @else
                                            <a href="#">
                                                {{translate('action_disabled')}}
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($withdraw_req)==0)
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
                            {{$withdraw_req->links()}}
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
              url: '{{route('admin.withdraw.status-filter')}}',
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
@endpush
