@extends('layouts.back-end.app')

@section('title', translate('add_Offline_Payment_Method'))

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

        <form action="{{ route('admin.business-settings.payment-method.offline.store') }}" method="POST" id="payment-method-offline">
            @csrf
            <div class="card mt-3">
                <div class="card-header gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/payment-card.png')}}" alt="">
                        <h5 class="mb-0">{{ translate('payment_Information') }}</h5>
                    </div>
                    <a href="javascript:" onclick="add_input_fields_group()" class="btn btn--primary"><i class="tio-add"></i> {{ translate('Add_New_Field') }} </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <label for="method_name" class="title_color">{{ translate('payment_Method_Name') }}</label>
                                <input type="text" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('bkash') }}" name="method_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="input-fields-section" id="input-fields-section">
                        @php($aRandomNumber = rand())
                        <div class="row align-items-end" id="{{ $aRandomNumber }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_name" class="title_color">{{ translate('input_field_Name') }}</label>
                                    <input type="text" name="input_name[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('bank_Name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_data" class="title_color">{{ translate('input_Data') }}</label>
                                    <input type="text" name="input_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('AVC') }} {{ translate('bank') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <a href="javascript:" class="btn btn-outline-danger btn-sm delete square-btn" title="Delete" onclick="remove_input_fields_group('{{ $aRandomNumber }}')">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/payment-card-fill.png')}}" alt="">
                        <h5 class="mb-0">{{ translate('required_Information_from_Customer') }}</h5>
                    </div>
                    <a href="javascript:" onclick="add_customer_input_fields_group()" class="btn btn--primary"><i class="tio-add"></i> {{ translate('Add_New_Field') }} </a>
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group">
                                <label for="payment_note" class="title_color">Payment Note</label>
                                <textarea name="payment_note" class="form-control" id="payment_note" rows="6"></textarea>
                            </div>
                        </div>
                    </div> --}}
                    @php($cRandomNumber = rand())
                    <div class="customer-input-fields-section" id="customer-input-fields-section">
                        <div class="row align-items-end" id="{{ $cRandomNumber }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title_color">{{ translate('input_field_Name') }}</label>
                                    <input type="text" name="customer_input[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('payment_By') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_placeholder" class="title_color">{{ translate('place_Holder') }}</label>
                                    <input type="text" name="customer_placeholder[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('enter_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="form-check text-start mb-3">

                                            <label class="form-check-label text-dark" for="{{ $cRandomNumber+1 }}">
                                                <input type="checkbox" class="form-check-input" id="{{ $cRandomNumber+1 }}" name="is_required[0]"> {{ translate('is_Required') }} ?
                                            </label>
                                        </div>

                                        <a class="btn btn-outline-danger btn-sm delete square-btn" title="Delete"  onclick="remove_input_fields_group('{{ $cRandomNumber }}')">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-3">
                <button type="reset" class="btn btn-secondary px-5">{{ translate('reset') }}</button>
                <button type="submit" class="btn btn--primary px-5">{{ translate('submit') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
<script>
    jQuery(document).ready(function ($) {
        counter = 1;
    });

    function remove_input_fields_group(id)
    {
        $('#'+id).remove();
    }

    function add_input_fields_group()
    {
        let id = Math.floor((Math.random() + 1 )* 9999);
        let new_field = `<div class="row align-items-end" id="`+id+`" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_name" class="title_color">{{ translate('input_field_Name') }}</label>
                                    <input type="text" name="input_name[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('bank_Name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input_data" class="title_color">{{ translate('input_Data') }}</label>
                                    <input type="text" name="input_data[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('AVC') }} {{ translate('bank') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="d-flex justify-content-end">
                                        <a href="javascript:" class="btn btn-outline-danger btn-sm delete square-btn" title="Delete" onclick="remove_input_fields_group('`+id+`')">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        $('#input-fields-section').append(new_field);
        $('#'+id).fadeIn();
    }


    function add_customer_input_fields_group()
    {
        let id = Math.floor((Math.random() + 1 )* 9999);
        if(counter < 100) {
            $('#customer-input-fields-section').append(
                `<div class="row align-items-end" id="`+id+`" style="display: none;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="title_color">{{ translate('input_field_Name') }}</label>
                            <input type="text" name="customer_input[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('payment_By') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer_placeholder" class="title_color">{{ translate('place_Holder') }}</label>
                            <input type="text" name="customer_placeholder[]" class="form-control" placeholder="{{ translate('ex') }}: {{ translate('enter_name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="form-check text-start mb-3">

                                    <label class="form-check-label text-dark" for="`+id+1+`">
                                        <input type="checkbox" class="form-check-input" id="`+id+1+`" name="is_required[${counter}]"> {{ translate('is_Required') }} ?
                                    </label>
                                </div>

                                <a class="btn btn-outline-danger btn-sm delete square-btn" title="Delete"  onclick="remove_input_fields_group('`+id+`')">
                                    <i class="tio-delete"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`
            );
            console.log(counter)

            counter++;
        }
        $('#'+id).fadeIn();
    }
</script>

<script>
    $('#payment-method-offline').on('submit', function(event){
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
                if(data.status) {
                    toastr.success(data.message);
                    location.href = data.redirect_url;
                }else {
                    toastr.error(data.message);
                }
            }
        });
    });
</script>

@endpush
