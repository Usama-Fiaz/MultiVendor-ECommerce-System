@extends('layouts.back-end.app-seller')
@section('title', translate('withdraw_information_View'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">

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

        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-2 mb-4">
                            <h3 class="text-capitalize">
                                {{translate('delivery_Man_Withdraw_Information')}}
                            </h3>

                            <i class="tio-wallet-outlined fz-30"></i>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start flex-wrap">
                                    <div><h5 class="text-capitalize">{{translate('amount')}} : </h5></div>
                                    <div class="mx-1"><h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\Convert::default($details->amount))}}</h5></div>
                                </div>
                                <div class="flex-start flex-wrap">
                                    <div><h5>{{translate('request_time')}} : </h5></div>
                                    <div class="mx-1">{{ date_format( $details->created_at, 'd-M-Y, h:i:s A') }}</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="flex-start">
                                    <div class="title-color">{{translate('note')}} :</div>
                                    <div class="mx-1">{{$details->transaction_note}}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if ($details->approved== 0)
                                    <button type="button" class="btn btn-success float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" data-toggle="modal"
                                            data-target="#exampleModal">{{translate('proceed')}}
                                        <i class="tio-arrow-forward"></i>
                                    </button>
                                @else
                                    <div class="text-center float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        @if($details->approved==1)
                                            <label class="badge badge-success p-2 rounded-bottom">
                                                {{translate('approved')}}
                                            </label>
                                        @else
                                            <label class="badge badge-danger p-2 rounded-bottom">
                                                {{translate('denied')}}
                                            </label>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                            <h3 class="h3 mb-0">{{translate('my_bank_info')}} </h3>
                            <i class="tio tio-dollar-outlined"></i>
                        </div>

                        <div class="mt-2">
                            <div class="flex-start">
                                <div><h4 class="text-nowrap">{{translate('bank_name')}} : </h4></div>
                                <div class="mx-1"><h4>{{$details->delivery_men->bank_name ?? translate('no_data_found')}}</h4></div>
                            </div>
                            <div class="flex-start">
                                <div><h6 class="text-nowrap">{{translate('branch')}} : </h6></div>
                                <div class="mx-1"><h6>{{$details->delivery_men->branch ?? translate('no_data_found') }}</h6></div>
                            </div>
                            <div class="flex-start">
                                <div><h6 class="text-nowrap">{{translate('holder_name')}} : </h6></div>
                                <div class="mx-1"><h6>{{$details->delivery_men->holder_name ?? translate('no_data_found') }}</h6></div>
                            </div>
                            <div class="flex-start">
                                <div><h6 class="text-nowrap">{{translate('account_no')}} : </h6></div>
                                <div class="mx-1"><h6>{{$details->delivery_men->account_no ??  translate('no_data_found') }}</h6></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="text-capitalize d-flex align-items-center justify-content-between gap-2 border-bottom pb-3 mb-4">
                            <h3 class="h3 mb-0">{{translate('delivery_man_info')}} </h3>
                            <i class="tio tio-user-big-outlined"></i>
                        </div>
                        <div class="flex-start">
                            <div><h5 class="text-nowrap">{{translate('name')}} : </h5></div>
                            <div class="mx-1"><h5>{{$details->delivery_men->f_name}} {{$details->delivery_men->l_name}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5 class="text-nowrap">{{translate('email')}} : </h5></div>
                            <div class="mx-1"><h5>{{$details->delivery_men->email}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5 class="text-nowrap">{{translate('phone')}} : </h5></div>
                            <div class="mx-1"><h5>{{$details->delivery_men->phone}}</h5></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('withdraw_request_process')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{route('seller.delivery-man.withdraw_status',[$details['id']])}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">{{translate('request')}}:</label>
                                    <select name="approved" class="custom-select" id="inputGroupSelect02">
                                        <option value="1">{{translate('approve')}}</option>
                                        <option value="2">{{translate('deny')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">{{translate('note_about_transaction_or_request')}}:</label>
                                    <textarea class="form-control" name="note" id="message-text"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')

@endpush
