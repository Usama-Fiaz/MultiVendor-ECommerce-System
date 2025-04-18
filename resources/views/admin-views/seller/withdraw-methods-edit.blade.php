@extends('layouts.back-end.app')

@section('title', translate('Withdrawal_Methods'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="page-title">
                    <img width="20" src="{{asset('/public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('Withdrawal_Methods')}}
                </h2>
                <button class="btn btn--primary" id="add-more-field">
                    <i class="tio-add"></i> {{translate('add_Fields')}}
                </button>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.sellers.withdraw-method.update')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{$withdrawal_method['id']}}" name="id">
                    <div class=" p-30">
                        <div class="card card-body">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="method_name" id="method_name"
                                       placeholder="Select method name"
                                       value="{{$withdrawal_method['method_name']}}" required>
                                <label>{{translate('method_name')}} *</label>
                            </div>
                        </div>

                        @if($withdrawal_method['method_fields'][0])
                            @php($field = $withdrawal_method['method_fields'][0])
                            <div class="card card-body">
                                <div class="row gy-4 align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="">
                                            <select class="form-control" name="field_type[]" required>
                                                <option value="string" {{$field=='string'?'selected':''}}>{{translate('string')}}</option>
                                                <option value="number" {{$field=='number'?'selected':''}}>{{translate('number')}}</option>
                                                <option value="date" {{$field=='date'?'selected':''}}>{{translate('date')}}</option>
                                                <option value="password" {{$field=='password'?'selected':''}}>{{translate('password')}}</option>
                                                <option value="email" {{$field=='email'?'selected':''}}>{{translate('email')}}</option>
                                                <option value="phone" {{$field=='phone'?'selected':''}}>{{translate('phone')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="field_name[]"
                                                   placeholder="Select field name"
                                                   value="{{$field['input_name']??''}}"
                                                   required>
                                            <label>{{translate('field_name')}} *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="placeholder_text[]"
                                                   placeholder="Select placeholder text"
                                                   value="{{$field['placeholder']??''}}"
                                                   required>
                                            <label>{{translate('placeholder_text')}} *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                   name="is_required[0]" id="flexCheckDefault"
                                                {{$field['is_required'] ? 'checked' : ''}}>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{translate('this_field_required')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- HERE CUSTOM FIELDS WILL BE ADDED -->
                        <div id="custom-field-section" class="mt-3">
                            @foreach($withdrawal_method['method_fields'] as $key=>$field)
                                @if($key>0)
                                    <div class="card card-body mb-30" id="field-row--{{$key}}">
                                        <div class="row gy-4 align-items-center">
                                            <div class="col-md-6 col-12">
                                                <div>
                                                    <select class="form-control" name="field_type[]" required>
                                                        <option value="string" {{$field['input_type']=='string'?'selected':''}}>{{translate('string')}}</option>
                                                        <option value="number" {{$field['input_type']=='number'?'selected':''}}>{{translate('number')}}</option>
                                                        <option value="date" {{$field['input_type']=='date'?'selected':''}}>{{translate('date')}}</option>
                                                        <option value="password" {{$field['input_type']=='password'?'selected':''}}>{{translate('password')}}</option>
                                                        <option value="email" {{$field['input_type']=='email'?'selected':''}}>{{translate('email')}}</option>
                                                        <option value="phone" {{$field['input_type']=='phone'?'selected':''}}>{{translate('phone')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="field_name[]"
                                                           placeholder="Select field name"
                                                           value="{{$field['input_name']??''}}"
                                                           required>
                                                    <label>{{translate('field_name')}} *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="placeholder_text[]"
                                                           placeholder="Select placeholder text"
                                                           value="{{$field['placeholder']??''}}"
                                                           required>
                                                    <label>{{translate('placeholder_text')}} *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="is_required[{{$key}}]" id="flexCheckDefault__0"
                                                        {{$field['is_required'] ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="flexCheckDefault__0">
                                                        {{translate('this_field_required')}}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <span class="btn btn-danger" onclick="remove_field({{$key}})">
                                                <i class="tio-delete"></i>
                                                    {{translate('remove')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="is_default" id="flexCheckDefaultMethod" {{$withdrawal_method['is_default'] == 1 ? 'checked' : ''}}>
                                <label class="form-check-label" for="flexCheckDefaultMethod">
                                    {{translate('default_method')}}
                                </label>
                            </div>
                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn--secondary mx-2">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary demo_check">{{translate('submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


@push('script_2')
    <script>
        function remove_field(fieldRowId) {
            $( `#field-row--${fieldRowId}` ).remove();
            counter--;
        }

        jQuery(document).ready(function ($) {
            counter = 1;

            $('#add-more-field').on('click', function (event) {
                if(counter < 15) {
                    event.preventDefault();

                    $('#custom-field-section').append(
                        `<div class="card card-body mt-3" id="field-row--${counter}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-6 col-12">
                                    <select class="form-control js-select" name="field_type[]" required>
                                        <option value="" selected disabled>{{translate('input_Field_Type')}} *</option>
                                        <option value="string">{{translate('string')}}</option>
                                        <option value="number">{{translate('number')}}</option>
                                        <option value="date">{{translate('date')}}</option>
                                        <option value="password">{{translate('password')}}</option>
                                        <option value="email">{{translate('email')}}</option>
                                        <option value="phone">{{translate('phone')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="field_name[]"
                                               placeholder="Select field name" value="" required>
                                        <label>{{translate('field_name')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="placeholder_text[]"
                                               placeholder="Select placeholder text" value="" required>
                                        <label>{{translate('placeholder_text')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flexCheckDefault__${counter}" checked>
                                        <label class="form-check-label" for="flexCheckDefault__${counter}">
                                            {{translate('this_field_required')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end">
                                    <span class="btn btn-danger" onclick="remove_field(${counter})">
                                    <i class="tio-delete"></i>
                                        {{translate('remove')}}
                                    </span>
                                </div>
                            </div>
                        </div>`
                        );

                    $(".js-select").select2();

                    counter++;
                } else {
                    Swal.fire({
                        title: '{{translate("reached_maximum")}}',
                        confirmButtonText: '{{translate("ok")}}',
                    });
                }
            })

            $('form').on('reset', function (event) {
                if(counter > 1) {
                    $('#custom-field-section').html("");
                    $('#method_name').val("");
                }

                counter = 1;
            })
        });
    </script>
@endpush
