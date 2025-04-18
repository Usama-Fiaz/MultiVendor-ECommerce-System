@extends('layouts.back-end.app')

@section('title', translate('general_settings'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{translate('business_Setup')}}
            </h2>

            <div class="btn-group">
                <div class="ripple-animation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                        <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                    </svg>
                </div>


                <div class="dropdown-menu dropdown-menu-right bg-aliceblue border border-color-primary-light p-4 dropdown-w-lg">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img width="20" src="{{asset('/public/assets/back-end/img/note.png')}}" alt="">
                        <h5 class="text-primary mb-0">{{translate('note')}}</h5>
                    </div>
                    <p class="title-color font-weight-medium mb-0">{{ translate('please_click_save_information_button_below_to_save_all_the_changes') }}</p>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="alert alert-danger d-none mb-3" role="alert">
            {{translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
        </div>

        {{-- maintenance mode --}}
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.maintenance-mode')}}" method="get" id="maintenance_mode_form">
                    @csrf
                <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                    @php($config=\App\CPU\Helpers::get_business_settings('maintenance_mode'))
                    <h5 class="mb-0 d-flex gap-1 c1">
                        {{translate('maintenance_mode')}}
                    </h5>
                    <div class="position-relative">
                        <label class="switcher">
                            <input type="checkbox" class="switcher_input" id="maintenance_mode" {{isset($config) && $config?'checked':''}} onclick="toogleStatusModal(event,'maintenance_mode','maintenance_mode-on.png','maintenance_mode-off.png','{{translate('Want_to_enable_the_Maintenance_Mode')}}','{{translate('Want_to_disable_the_Maintenance_Mode')}}',`<p>{{translate('if_enabled_all_your_apps_and_customer_website_will_be_temporarily_off')}}</p>`,`<p>{{translate('if_disabled_all_your_apps_and_customer_website_will_be_functional')}}</p>`)">
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                </div>
                </form>
                <p>*{{translate('by_turning_the').', "'.
                translate('Maintenance_Mode')}}" {{translate('ON')}}, {{translate('all_your_apps_and_customer_website_will_be_disabled_until_you_turn_this_mode_OFF')}}.{{translate('only_the_Admin_Panel_&_Seller_Panel_will_be_functional')}}</p>
            </div>
        </div>

        <form action="{{ route('admin.business-settings.update-info') }}" method="POST"
                enctype="multipart/form-data">
            @csrf
            <!-- Company Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-user-big"></i>
                        {{translate('company_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{translate('company_Name')}}</label>
                                <input class="form-control" type="text" name="company_name"
                                    value="{{ $business_setting['company_name'] }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('phone')}}</label>
                                <input class="form-control" type="text" name="company_phone"
                                    value="{{ $business_setting['company_phone'] }}"
                                    placeholder="New Business">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="title-color d-flex">{{translate('email')}}</label>
                                <input class="form-control" type="text" name="company_email"
                                    value="{{ $business_setting['company_email'] }}"
                                    placeholder="New Business">
                            </div>
                        </div>

                        @php($cc=\App\Model\BusinessSetting::where('type','country_code')->first())
                        @php($cc=$cc?$cc->value:0)
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('country')}} </label>
                                <select id="country" name="country" class="form-control  js-select2-custom">
                                    <option value="AF" {{ $cc?($cc=='AF'?'selected':''):'' }} >Afghanistan</option>
                                    <option value="AX" {{ $cc?($cc=='AX'?'selected':''):'' }} >Åland Islands</option>
                                    <option value="AL" {{ $cc?($cc=='AL'?'selected':''):'' }} >Albania</option>
                                    <option value="DZ" {{ $cc?($cc=='DZ'?'selected':''):'' }}>Algeria</option>
                                    <option value="AS" {{ $cc?($cc=='AS'?'selected':''):'' }}>American Samoa</option>
                                    <option value="AD" {{ $cc?($cc=='AD'?'selected':''):'' }}>Andorra</option>
                                    <option value="AO" {{ $cc?($cc=='AO'?'selected':''):'' }}>Angola</option>
                                    <option value="AI" {{ $cc?($cc=='AI'?'selected':''):'' }}>Anguilla</option>
                                    <option value="AQ" {{ $cc?($cc=='AQ'?'selected':''):'' }}>Antarctica</option>
                                    <option value="AG" {{ $cc?($cc=='AG'?'selected':''):'' }}>Antigua and Barbuda</option>
                                    <option value="AR" {{ $cc?($cc=='AR'?'selected':''):'' }}>Argentina</option>
                                    <option value="AM" {{ $cc?($cc=='AM'?'selected':''):'' }}>Armenia</option>
                                    <option value="AW" {{ $cc?($cc=='AW'?'selected':''):'' }}>Aruba</option>
                                    <option value="AU" {{ $cc?($cc=='AU'?'selected':''):'' }}>Australia</option>
                                    <option value="AT" {{ $cc?($cc=='AT'?'selected':''):'' }}>Austria</option>
                                    <option value="AZ" {{ $cc?($cc=='AZ'?'selected':''):'' }}>Azerbaijan</option>
                                    <option value="BS" {{ $cc?($cc=='BS'?'selected':''):'' }}>Bahamas</option>
                                    <option value="BH" {{ $cc?($cc=='BH'?'selected':''):'' }}>Bahrain</option>
                                    <option value="BD" {{ $cc?($cc=='BD'?'selected':''):'' }}>Bangladesh</option>
                                    <option value="BB" {{ $cc?($cc=='BB'?'selected':''):'' }}>Barbados</option>
                                    <option value="BY" {{ $cc?($cc=='BY'?'selected':''):'' }}>Belarus</option>
                                    <option value="BE" {{ $cc?($cc=='BE'?'selected':''):'' }}>Belgium</option>
                                    <option value="BZ" {{ $cc?($cc=='BZ'?'selected':''):'' }}>Belize</option>
                                    <option value="BJ" {{ $cc?($cc=='BJ'?'selected':''):'' }}>Benin</option>
                                    <option value="BM" {{ $cc?($cc=='BM'?'selected':''):'' }}>Bermuda</option>
                                    <option value="BT" {{ $cc?($cc=='BT'?'selected':''):'' }}>Bhutan</option>
                                    <option value="BO" {{ $cc?($cc=='BO'?'selected':''):'' }}>Bolivia, Plurinational State
                                        of
                                    </option>
                                    <option value="BQ" {{ $cc?($cc=='BQ'?'selected':''):'' }}>Bonaire, Sint Eustatius and
                                        Saba
                                    </option>
                                    <option value="BA" {{ $cc?($cc=='BA'?'selected':''):'' }}>Bosnia and Herzegovina
                                    </option>
                                    <option value="BW" {{ $cc?($cc=='BW'?'selected':''):'' }}>Botswana</option>
                                    <option value="BV" {{ $cc?($cc=='BV'?'selected':''):'' }}>Bouvet Island</option>
                                    <option value="BR" {{ $cc?($cc=='BR'?'selected':''):'' }}>Brazil</option>
                                    <option value="IO" {{ $cc?($cc=='IO'?'selected':''):'' }}>British Indian Ocean
                                        Territory
                                    </option>
                                    <option value="BN" {{ $cc?($cc=='BN'?'selected':''):'' }}>Brunei Darussalam</option>
                                    <option value="BG" {{ $cc?($cc=='BG'?'selected':''):'' }}>Bulgaria</option>
                                    <option value="BF" {{ $cc?($cc=='BF'?'selected':''):'' }}>Burkina Faso</option>
                                    <option value="BI" {{ $cc?($cc=='BI'?'selected':''):'' }}>Burundi</option>
                                    <option value="KH" {{ $cc?($cc=='KH'?'selected':''):'' }}>Cambodia</option>
                                    <option value="CM" {{ $cc?($cc=='CM'?'selected':''):'' }}>Cameroon</option>
                                    <option value="CA" {{ $cc?($cc=='CA'?'selected':''):'' }}>Canada</option>
                                    <option value="CV" {{ $cc?($cc=='CV'?'selected':''):'' }}>Cape Verde</option>
                                    <option value="KY" {{ $cc?($cc=='KY'?'selected':''):'' }}>Cayman Islands</option>
                                    <option value="CF" {{ $cc?($cc=='CF'?'selected':''):'' }}>Central African Republic
                                    </option>
                                    <option value="TD" {{ $cc?($cc=='TD'?'selected':''):'' }}>Chad</option>
                                    <option value="CL" {{ $cc?($cc=='CL'?'selected':''):'' }}>Chile</option>
                                    <option value="CN" {{ $cc?($cc=='CN'?'selected':''):'' }}>China</option>
                                    <option value="CX" {{ $cc?($cc=='CX'?'selected':''):'' }}>Christmas Island</option>
                                    <option value="CC" {{ $cc?($cc=='CC'?'selected':''):'' }}>Cocos (Keeling) Islands
                                    </option>
                                    <option value="CO" {{ $cc?($cc=='CO'?'selected':''):'' }}>Colombia</option>
                                    <option value="KM" {{ $cc?($cc=='KM'?'selected':''):'' }}>Comoros</option>
                                    <option value="CG" {{ $cc?($cc=='CG'?'selected':''):'' }}>Congo</option>
                                    <option value="CD" {{ $cc?($cc=='CD'?'selected':''):'' }}>Congo, the Democratic Republic
                                        of the
                                    </option>
                                    <option value="CK" {{ $cc?($cc=='CK'?'selected':''):'' }}>Cook Islands</option>
                                    <option value="CR" {{ $cc?($cc=='CR'?'selected':''):'' }}>Costa Rica</option>
                                    <option value="CI" {{ $cc?($cc=='CI'?'selected':''):'' }}>Côte d'Ivoire</option>
                                    <option value="HR" {{ $cc?($cc=='HR'?'selected':''):'' }}>Croatia</option>
                                    <option value="CU" {{ $cc?($cc=='CU'?'selected':''):'' }}>Cuba</option>
                                    <option value="CW" {{ $cc?($cc=='CW'?'selected':''):'' }}>Curaçao</option>
                                    <option value="CY" {{ $cc?($cc=='CY'?'selected':''):'' }}>Cyprus</option>
                                    <option value="CZ" {{ $cc?($cc=='CZ'?'selected':''):'' }}>Czech Republic</option>
                                    <option value="DK" {{ $cc?($cc=='DK'?'selected':''):'' }}>Denmark</option>
                                    <option value="DJ" {{ $cc?($cc=='DJ'?'selected':''):'' }}>Djibouti</option>
                                    <option value="DM" {{ $cc?($cc=='DM'?'selected':''):'' }}>Dominica</option>
                                    <option value="DO" {{ $cc?($cc=='DO'?'selected':''):'' }}>Dominican Republic</option>
                                    <option value="EC" {{ $cc?($cc=='EC'?'selected':''):'' }}>Ecuador</option>
                                    <option value="EG" {{ $cc?($cc=='EG'?'selected':''):'' }}>Egypt</option>
                                    <option value="SV" {{ $cc?($cc=='SV'?'selected':''):'' }}>El Salvador</option>
                                    <option value="GQ" {{ $cc?($cc=='GQ'?'selected':''):'' }}>Equatorial Guinea</option>
                                    <option value="ER" {{ $cc?($cc=='ER'?'selected':''):'' }}>Eritrea</option>
                                    <option value="EE" {{ $cc?($cc=='EE'?'selected':''):'' }}>Estonia</option>
                                    <option value="ET" {{ $cc?($cc=='ET'?'selected':''):'' }}>Ethiopia</option>
                                    <option value="FK" {{ $cc?($cc=='FK'?'selected':''):'' }}>Falkland Islands (Malvinas)
                                    </option>
                                    <option value="FO" {{ $cc?($cc=='FO'?'selected':''):'' }}>Faroe Islands</option>
                                    <option value="FJ" {{ $cc?($cc=='FJ'?'selected':''):'' }}>Fiji</option>
                                    <option value="FI" {{ $cc?($cc=='FI'?'selected':''):'' }}>Finland</option>
                                    <option value="FR" {{ $cc?($cc=='FR'?'selected':''):'' }}>France</option>
                                    <option value="GF" {{ $cc?($cc=='GF'?'selected':''):'' }}>French Guiana</option>
                                    <option value="PF" {{ $cc?($cc=='PF'?'selected':''):'' }}>French Polynesia</option>
                                    <option value="TF" {{ $cc?($cc=='TF'?'selected':''):'' }}>French Southern Territories
                                    </option>
                                    <option value="GA" {{ $cc?($cc=='GA'?'selected':''):'' }}>Gabon</option>
                                    <option value="GM" {{ $cc?($cc=='GM'?'selected':''):'' }}>Gambia</option>
                                    <option value="GE" {{ $cc?($cc=='GE'?'selected':''):'' }}>Georgia</option>
                                    <option value="DE" {{ $cc?($cc=='DE'?'selected':''):'' }}>Germany</option>
                                    <option value="GH" {{ $cc?($cc=='GH'?'selected':''):'' }}>Ghana</option>
                                    <option value="GI" {{ $cc?($cc=='GI'?'selected':''):'' }}>Gibraltar</option>
                                    <option value="GR" {{ $cc?($cc=='GR'?'selected':''):'' }}>Greece</option>
                                    <option value="GL" {{ $cc?($cc=='GL'?'selected':''):'' }}>Greenland</option>
                                    <option value="GD" {{ $cc?($cc=='GD'?'selected':''):'' }}>Grenada</option>
                                    <option value="GP" {{ $cc?($cc=='GP'?'selected':''):'' }}>Guadeloupe</option>
                                    <option value="GU" {{ $cc?($cc=='GU'?'selected':''):'' }}>Guam</option>
                                    <option value="GT" {{ $cc?($cc=='GT'?'selected':''):'' }}>Guatemala</option>
                                    <option value="GG" {{ $cc?($cc=='GG'?'selected':''):'' }}>Guernsey</option>
                                    <option value="GN" {{ $cc?($cc=='GN'?'selected':''):'' }}>Guinea</option>
                                    <option value="GW" {{ $cc?($cc=='GW'?'selected':''):'' }}>Guinea-Bissau</option>
                                    <option value="GY" {{ $cc?($cc=='GY'?'selected':''):'' }}>Guyana</option>
                                    <option value="HT" {{ $cc?($cc=='HT'?'selected':''):'' }}>Haiti</option>
                                    <option value="HM" {{ $cc?($cc=='HM'?'selected':''):'' }}>Heard Island and McDonald
                                        Islands
                                    </option>
                                    <option value="VA" {{ $cc?($cc=='VA'?'selected':''):'' }}>Holy See (Vatican City
                                        State)
                                    </option>
                                    <option value="HN" {{ $cc?($cc=='HN'?'selected':''):'' }}>Honduras</option>
                                    <option value="HK" {{ $cc?($cc=='HK'?'selected':''):'' }}>Hong Kong</option>
                                    <option value="HU" {{ $cc?($cc=='HU'?'selected':''):'' }}>Hungary</option>
                                    <option value="IS" {{ $cc?($cc=='IS'?'selected':''):'' }}>Iceland</option>
                                    <option value="IN" {{ $cc?($cc=='IN'?'selected':''):'' }}>India</option>
                                    <option value="ID" {{ $cc?($cc=='ID'?'selected':''):'' }}>Indonesia</option>
                                    <option value="IR" {{ $cc?($cc=='IR'?'selected':''):'' }}>Iran, Islamic Republic of
                                    </option>
                                    <option value="IQ" {{ $cc?($cc=='IQ'?'selected':''):'' }}>Iraq</option>
                                    <option value="IE" {{ $cc?($cc=='IE'?'selected':''):'' }}>Ireland</option>
                                    <option value="IM" {{ $cc?($cc=='IM'?'selected':''):'' }}>Isle of Man</option>
                                    <option value="IL" {{ $cc?($cc=='IL'?'selected':''):'' }}>Israel</option>
                                    <option value="IT" {{ $cc?($cc=='IT'?'selected':''):'' }}>Italy</option>
                                    <option value="JM" {{ $cc?($cc=='JM'?'selected':''):'' }}>Jamaica</option>
                                    <option value="JP" {{ $cc?($cc=='JP'?'selected':''):'' }}>Japan</option>
                                    <option value="JE" {{ $cc?($cc=='JE'?'selected':''):'' }}>Jersey</option>
                                    <option value="JO" {{ $cc?($cc=='JO'?'selected':''):'' }}>Jordan</option>
                                    <option value="KZ" {{ $cc?($cc=='KZ'?'selected':''):'' }}>Kazakhstan</option>
                                    <option value="KE" {{ $cc?($cc=='KE'?'selected':''):'' }}>Kenya</option>
                                    <option value="KI" {{ $cc?($cc=='KI'?'selected':''):'' }}>Kiribati</option>
                                    <option value="KP" {{ $cc?($cc=='KP'?'selected':''):'' }}>Korea, Democratic People's
                                        Republic of
                                    </option>
                                    <option value="KR" {{ $cc?($cc=='KR'?'selected':''):'' }}>Korea, Republic of</option>
                                    <option value="KW" {{ $cc?($cc=='KW'?'selected':''):'' }}>Kuwait</option>
                                    <option value="KG" {{ $cc?($cc=='KG'?'selected':''):'' }}>Kyrgyzstan</option>
                                    <option value="LA" {{ $cc?($cc=='LA'?'selected':''):'' }}>Lao People's Democratic
                                        Republic
                                    </option>
                                    <option value="LV" {{ $cc?($cc=='LV'?'selected':''):'' }}>Latvia</option>
                                    <option value="LB" {{ $cc?($cc=='LB'?'selected':''):'' }}>Lebanon</option>
                                    <option value="LS" {{ $cc?($cc=='LS'?'selected':''):'' }}>Lesotho</option>
                                    <option value="LR" {{ $cc?($cc=='LR'?'selected':''):'' }}>Liberia</option>
                                    <option value="LY" {{ $cc?($cc=='LY'?'selected':''):'' }}>Libya</option>
                                    <option value="LI" {{ $cc?($cc=='LI'?'selected':''):'' }}>Liechtenstein</option>
                                    <option value="LT" {{ $cc?($cc=='LT'?'selected':''):'' }}>Lithuania</option>
                                    <option value="LU" {{ $cc?($cc=='LU'?'selected':''):'' }}>Luxembourg</option>
                                    <option value="MO" {{ $cc?($cc=='MO'?'selected':''):'' }}>Macao</option>
                                    <option value="MK" {{ $cc?($cc=='MK'?'selected':''):'' }}>Macedonia, the former Yugoslav
                                        Republic of
                                    </option>
                                    <option value="MG" {{ $cc?($cc=='MG'?'selected':''):'' }}>Madagascar</option>
                                    <option value="MW" {{ $cc?($cc=='MW'?'selected':''):'' }}>Malawi</option>
                                    <option value="MY" {{ $cc?($cc=='MY'?'selected':''):'' }}>Malaysia</option>
                                    <option value="MV" {{ $cc?($cc=='MV'?'selected':''):'' }}>Maldives</option>
                                    <option value="ML" {{ $cc?($cc=='ML'?'selected':''):'' }}>Mali</option>
                                    <option value="MT" {{ $cc?($cc=='MT'?'selected':''):'' }}>Malta</option>
                                    <option value="MH" {{ $cc?($cc=='MH'?'selected':''):'' }}>Marshall Islands</option>
                                    <option value="MQ" {{ $cc?($cc=='MQ'?'selected':''):'' }}>Martinique</option>
                                    <option value="MR" {{ $cc?($cc=='MR'?'selected':''):'' }}>Mauritania</option>
                                    <option value="MU" {{ $cc?($cc=='MU'?'selected':''):'' }}>Mauritius</option>
                                    <option value="YT" {{ $cc?($cc=='YT'?'selected':''):'' }}>Mayotte</option>
                                    <option value="MX" {{ $cc?($cc=='MX'?'selected':''):'' }}>Mexico</option>
                                    <option value="FM" {{ $cc?($cc=='FM'?'selected':''):'' }}>Micronesia, Federated States
                                        of
                                    </option>
                                    <option value="MD" {{ $cc?($cc=='MD'?'selected':''):'' }}>Moldova, Republic of</option>
                                    <option value="MC" {{ $cc?($cc=='MC'?'selected':''):'' }}>Monaco</option>
                                    <option value="MN" {{ $cc?($cc=='MN'?'selected':''):'' }}>Mongolia</option>
                                    <option value="ME" {{ $cc?($cc=='ME'?'selected':''):'' }}>Montenegro</option>
                                    <option value="MS" {{ $cc?($cc=='MS'?'selected':''):'' }}>Montserrat</option>
                                    <option value="MA" {{ $cc?($cc=='MA'?'selected':''):'' }}>Morocco</option>
                                    <option value="MZ" {{ $cc?($cc=='MZ'?'selected':''):'' }}>Mozambique</option>
                                    <option value="MM" {{ $cc?($cc=='MM'?'selected':''):'' }}>Myanmar</option>
                                    <option value="NA" {{ $cc?($cc=='NA'?'selected':''):'' }}>Namibia</option>
                                    <option value="NR" {{ $cc?($cc=='NR'?'selected':''):'' }}>Nauru</option>
                                    <option value="NP" {{ $cc?($cc=='NP'?'selected':''):'' }}>Nepal</option>
                                    <option value="NL" {{ $cc?($cc=='NL'?'selected':''):'' }}>Netherlands</option>
                                    <option value="NC" {{ $cc?($cc=='NC'?'selected':''):'' }}>New Caledonia</option>
                                    <option value="NZ" {{ $cc?($cc=='NZ'?'selected':''):'' }}>New Zealand</option>
                                    <option value="NI" {{ $cc?($cc=='NI'?'selected':''):'' }}>Nicaragua</option>
                                    <option value="NE" {{ $cc?($cc=='NE'?'selected':''):'' }}>Niger</option>
                                    <option value="NG" {{ $cc?($cc=='NG'?'selected':''):'' }}>Nigeria</option>
                                    <option value="NU" {{ $cc?($cc=='NU'?'selected':''):'' }}>Niue</option>
                                    <option value="NF" {{ $cc?($cc=='NF'?'selected':''):'' }}>Norfolk Island</option>
                                    <option value="MP" {{ $cc?($cc=='MP'?'selected':''):'' }}>Northern Mariana Islands
                                    </option>
                                    <option value="NO" {{ $cc?($cc=='NO'?'selected':''):'' }}>Norway</option>
                                    <option value="OM" {{ $cc?($cc=='OM'?'selected':''):'' }}>Oman</option>
                                    <option value="PK" {{ $cc?($cc=='PK'?'selected':''):'' }}>Pakistan</option>
                                    <option value="PW" {{ $cc?($cc=='PW'?'selected':''):'' }}>Palau</option>
                                    <option value="PS" {{ $cc?($cc=='PS'?'selected':''):'' }}>Palestinian Territory,
                                        Occupied
                                    </option>
                                    <option value="PA" {{ $cc?($cc=='PA'?'selected':''):'' }}>Panama</option>
                                    <option value="PG" {{ $cc?($cc=='PG'?'selected':''):'' }}>Papua New Guinea</option>
                                    <option value="PY" {{ $cc?($cc=='PY'?'selected':''):'' }}>Paraguay</option>
                                    <option value="PE" {{ $cc?($cc=='PE'?'selected':''):'' }}>Peru</option>
                                    <option value="PH" {{ $cc?($cc=='PH'?'selected':''):'' }}>Philippines</option>
                                    <option value="PN" {{ $cc?($cc=='PN'?'selected':''):'' }}>Pitcairn</option>
                                    <option value="PL" {{ $cc?($cc=='PL'?'selected':''):'' }}>Poland</option>
                                    <option value="PT" {{ $cc?($cc=='PT'?'selected':''):'' }}>Portugal</option>
                                    <option value="PR" {{ $cc?($cc=='PR'?'selected':''):'' }}>Puerto Rico</option>
                                    <option value="QA" {{ $cc?($cc=='QA'?'selected':''):'' }}>Qatar</option>
                                    <option value="RE" {{ $cc?($cc=='RE'?'selected':''):'' }}>Réunion</option>
                                    <option value="RO" {{ $cc?($cc=='RO'?'selected':''):'' }}>Romania</option>
                                    <option value="RU" {{ $cc?($cc=='RU'?'selected':''):'' }}>Russian Federation</option>
                                    <option value="RW" {{ $cc?($cc=='RW'?'selected':''):'' }}>Rwanda</option>
                                    <option value="BL" {{ $cc?($cc=='BL'?'selected':''):'' }}>Saint Barthélemy</option>
                                    <option value="SH" {{ $cc?($cc=='SH'?'selected':''):'' }}>Saint Helena, Ascension and
                                        Tristan da Cunha
                                    </option>
                                    <option value="KN" {{ $cc?($cc=='KN'?'selected':''):'' }}>Saint Kitts and Nevis</option>
                                    <option value="LC" {{ $cc?($cc=='LC'?'selected':''):'' }}>Saint Lucia</option>
                                    <option value="MF" {{ $cc?($cc=='MF'?'selected':''):'' }}>Saint Martin (French part)
                                    </option>
                                    <option value="PM" {{ $cc?($cc=='PM'?'selected':''):'' }}>Saint Pierre and Miquelon
                                    </option>
                                    <option value="VC" {{ $cc?($cc=='VC'?'selected':''):'' }}>Saint Vincent and the
                                        Grenadines
                                    </option>
                                    <option value="WS" {{ $cc?($cc=='WS'?'selected':''):'' }}>Samoa</option>
                                    <option value="SM" {{ $cc?($cc=='SM'?'selected':''):'' }}>San Marino</option>
                                    <option value="ST" {{ $cc?($cc=='ST'?'selected':''):'' }}>Sao Tome and Principe</option>
                                    <option value="SA" {{ $cc?($cc=='SA'?'selected':''):'' }}>Saudi Arabia</option>
                                    <option value="SN" {{ $cc?($cc=='SN'?'selected':''):'' }}>Senegal</option>
                                    <option value="RS" {{ $cc?($cc=='RS'?'selected':''):'' }}>Serbia</option>
                                    <option value="SC" {{ $cc?($cc=='SC'?'selected':''):'' }}>Seychelles</option>
                                    <option value="SL" {{ $cc?($cc=='SL'?'selected':''):'' }}>Sierra Leone</option>
                                    <option value="SG" {{ $cc?($cc=='SG'?'selected':''):'' }}>Singapore</option>
                                    <option value="SX" {{ $cc?($cc=='SX'?'selected':''):'' }}>Sint Maarten (Dutch part)
                                    </option>
                                    <option value="SK" {{ $cc?($cc=='SK'?'selected':''):'' }}>Slovakia</option>
                                    <option value="SI" {{ $cc?($cc=='SI'?'selected':''):'' }}>Slovenia</option>
                                    <option value="SB" {{ $cc?($cc=='SB'?'selected':''):'' }}>Solomon Islands</option>
                                    <option value="SO" {{ $cc?($cc=='SO'?'selected':''):'' }}>Somalia</option>
                                    <option value="ZA" {{ $cc?($cc=='ZA'?'selected':''):'' }}>South Africa</option>
                                    <option value="GS" {{ $cc?($cc=='GS'?'selected':''):'' }}>South Georgia and the South
                                        Sandwich Islands
                                    </option>
                                    <option value="SS" {{ $cc?($cc=='SS'?'selected':''):'' }}>South Sudan</option>
                                    <option value="ES" {{ $cc?($cc=='ES'?'selected':''):'' }}>Spain</option>
                                    <option value="LK" {{ $cc?($cc=='LK'?'selected':''):'' }}>Sri Lanka</option>
                                    <option value="SD" {{ $cc?($cc=='SD'?'selected':''):'' }}>Sudan</option>
                                    <option value="SR" {{ $cc?($cc=='SR'?'selected':''):'' }}>Suriname</option>
                                    <option value="SJ" {{ $cc?($cc=='SJ'?'selected':''):'' }}>Svalbard and Jan Mayen
                                    </option>
                                    <option value="SZ" {{ $cc?($cc=='SZ'?'selected':''):'' }}>Swaziland</option>
                                    <option value="SE" {{ $cc?($cc=='SE'?'selected':''):'' }}>Sweden</option>
                                    <option value="CH" {{ $cc?($cc=='CH'?'selected':''):'' }}>Switzerland</option>
                                    <option value="SY" {{ $cc?($cc=='SY'?'selected':''):'' }}>Syrian Arab Republic</option>
                                    <option value="TW" {{ $cc?($cc=='TW'?'selected':''):'' }}>Taiwan, Province of China
                                    </option>
                                    <option value="TJ" {{ $cc?($cc=='TJ'?'selected':''):'' }}>Tajikistan</option>
                                    <option value="TZ" {{ $cc?($cc=='TZ'?'selected':''):'' }}>Tanzania, United Republic of
                                    </option>
                                    <option value="TH" {{ $cc?($cc=='TH'?'selected':''):'' }}>Thailand</option>
                                    <option value="TL" {{ $cc?($cc=='TL'?'selected':''):'' }}>Timor-Leste</option>
                                    <option value="TG" {{ $cc?($cc=='TG'?'selected':''):'' }}>Togo</option>
                                    <option value="TK" {{ $cc?($cc=='TK'?'selected':''):'' }}>Tokelau</option>
                                    <option value="TO" {{ $cc?($cc=='TO'?'selected':''):'' }}>Tonga</option>
                                    <option value="TT" {{ $cc?($cc=='TT'?'selected':''):'' }}>Trinidad and Tobago</option>
                                    <option value="TN" {{ $cc?($cc=='TN'?'selected':''):'' }}>Tunisia</option>
                                    <option value="TR" {{ $cc?($cc=='TR'?'selected':''):'' }}>Turkey</option>
                                    <option value="TM" {{ $cc?($cc=='TM'?'selected':''):'' }}>Turkmenistan</option>
                                    <option value="TC" {{ $cc?($cc=='TC'?'selected':''):'' }}>Turks and Caicos Islands
                                    </option>
                                    <option value="TV" {{ $cc?($cc=='TV'?'selected':''):'' }}>Tuvalu</option>
                                    <option value="UG" {{ $cc?($cc=='UG'?'selected':''):'' }}>Uganda</option>
                                    <option value="UA" {{ $cc?($cc=='UA'?'selected':''):'' }}>Ukraine</option>
                                    <option value="AE" {{ $cc?($cc=='AE'?'selected':''):'' }}>United Arab Emirates</option>
                                    <option value="GB" {{ $cc?($cc=='GB'?'selected':''):'' }}>United Kingdom</option>
                                    <option value="US" {{ $cc?($cc=='US'?'selected':''):'' }}>United States</option>
                                    <option value="UM" {{ $cc?($cc=='UM'?'selected':''):'' }}>United States Minor Outlying
                                        Islands
                                    </option>
                                    <option value="UY" {{ $cc?($cc=='UY'?'selected':''):'' }}>Uruguay</option>
                                    <option value="UZ" {{ $cc?($cc=='UZ'?'selected':''):'' }}>Uzbekistan</option>
                                    <option value="VU" {{ $cc?($cc=='VU'?'selected':''):'' }}>Vanuatu</option>
                                    <option value="VE" {{ $cc?($cc=='VE'?'selected':''):'' }}>Venezuela, Bolivarian Republic
                                        of
                                    </option>
                                    <option value="VN" {{ $cc?($cc=='VN'?'selected':''):'' }}>Viet Nam</option>
                                    <option value="VG" {{ $cc?($cc=='VG'?'selected':''):'' }}>Virgin Islands, British
                                    </option>
                                    <option value="VI" {{ $cc?($cc=='VI'?'selected':''):'' }}>Virgin Islands, U.S.</option>
                                    <option value="WF" {{ $cc?($cc=='WF'?'selected':''):'' }}>Wallis and Futuna</option>
                                    <option value="EH" {{ $cc?($cc=='EH'?'selected':''):'' }}>Western Sahara</option>
                                    <option value="YE" {{ $cc?($cc=='YE'?'selected':''):'' }}>Yemen</option>
                                    <option value="ZM" {{ $cc?($cc=='ZM'?'selected':''):'' }}>Zambia</option>
                                    <option value="ZW" {{ $cc?($cc=='ZW'?'selected':''):'' }}>Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                        @php($tz=\App\Model\BusinessSetting::where('type','timezone')->first())
                        @php($tz=$tz?$tz->value:0)
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('time_zone')}}</label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    <option value="UTC" {{$tz?($tz==''?'selected':''):''}}>UTC</option>
                                    <option value="Etc/GMT+12" {{$tz?($tz=='Etc/GMT+12'?'selected':''):''}}>(GMT-12:00)
                                        International Date Line West
                                    </option>
                                    <option value="Pacific/Midway" {{$tz?($tz=='Pacific/Midway'?'selected':''):''}}>
                                        (GMT-11:00)
                                        Midway Island, Samoa
                                    </option>
                                    <option value="Pacific/Honolulu" {{$tz?($tz=='Pacific/Honolulu'?'selected':''):''}}>
                                        (GMT-10:00)
                                        Hawaii
                                    </option>
                                    <option value="US/Alaska" {{$tz?($tz=='US/Alaska'?'selected':''):''}}>(GMT-09:00) Alaska
                                    </option>
                                    <option
                                        value="America/Los_Angeles" {{$tz?($tz=='America/Los_Angeles'?'selected':''):''}}>
                                        (GMT-08:00) Pacific Time (US & Canada)
                                    </option>
                                    <option value="America/Tijuana" {{$tz?($tz=='America/Tijuana'?'selected':''):''}}>
                                        (GMT-08:00)
                                        Tijuana, Baja California
                                    </option>
                                    <option value="US/Arizona" {{$tz?($tz=='US/Arizona'?'selected':''):''}}>(GMT-07:00)
                                        Arizona
                                    </option>
                                    <option value="America/Chihuahua" {{$tz?($tz=='America/Chihuahua'?'selected':''):''}}>
                                        (GMT-07:00) Chihuahua, La Paz, Mazatlan
                                    </option>
                                    <option value="US/Mountain" {{$tz?($tz=='US/Mountain'?'selected':''):''}}>(GMT-07:00)
                                        Mountain
                                        Time (US & Canada)
                                    </option>
                                    <option value="America/Managua" {{$tz?($tz=='America/Managua'?'selected':''):''}}>
                                        (GMT-06:00)
                                        Central America
                                    </option>
                                    <option value="US/Central" {{$tz?($tz=='US/Central'?'selected':''):''}}>(GMT-06:00)
                                        Central Time
                                        (US & Canada)
                                    </option>
                                    <option
                                        value="America/Mexico_City" {{$tz?($tz=='America/Mexico_City'?'selected':''):''}}>
                                        (GMT-06:00) Guadalajara, Mexico City, Monterrey
                                    </option>
                                    <option
                                        value="Canada/Saskatchewan" {{$tz?($tz=='Canada/Saskatchewan'?'selected':''):''}}>
                                        (GMT-06:00) Saskatchewan
                                    </option>
                                    <option value="America/Bogota" {{$tz?($tz=='America/Bogota'?'selected':''):''}}>
                                        (GMT-05:00)
                                        Bogota, Lima, Quito, Rio Branco
                                    </option>
                                    <option value="US/Eastern" {{$tz?($tz=='US/Eastern'?'selected':''):''}}>(GMT-05:00)
                                        Eastern Time
                                        (US & Canada)
                                    </option>
                                    <option value="US/East-Indiana" {{$tz?($tz=='US/East-Indiana'?'selected':''):''}}>
                                        (GMT-05:00)
                                        Indiana (East)
                                    </option>
                                    <option value="Canada/Atlantic" {{$tz?($tz=='Canada/Atlantic'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Atlantic Time (Canada)
                                    </option>
                                    <option value="America/Caracas" {{$tz?($tz=='America/Caracas'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Caracas, La Paz
                                    </option>
                                    <option value="America/Manaus" {{$tz?($tz=='America/Manaus'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Manaus
                                    </option>
                                    <option value="America/Santiago" {{$tz?($tz=='America/Santiago'?'selected':''):''}}>
                                        (GMT-04:00)
                                        Santiago
                                    </option>
                                    <option
                                        value="Canada/Newfoundland" {{$tz?($tz=='Canada/Newfoundland'?'selected':''):''}}>
                                        (GMT-03:30) Newfoundland
                                    </option>
                                    <option value="America/Sao_Paulo" {{$tz?($tz=='America/Sao_Paulo'?'selected':''):''}}>
                                        (GMT-03:00) Brasilia
                                    </option>
                                    <option
                                        value="America/Argentina/Buenos_Aires" {{$tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''}}>
                                        (GMT-03:00) Buenos Aires, Georgetown
                                    </option>
                                    <option value="America/Godthab" {{$tz?($tz=='America/Godthab'?'selected':''):''}}>
                                        (GMT-03:00)
                                        Greenland
                                    </option>
                                    <option value="America/Montevideo" {{$tz?($tz=='America/Montevideo'?'selected':''):''}}>
                                        (GMT-03:00) Montevideo
                                    </option>
                                    <option value="America/Noronha" {{$tz?($tz=='America/Noronha'?'selected':''):''}}>
                                        (GMT-02:00)
                                        Mid-Atlantic
                                    </option>
                                    <option
                                        value="Atlantic/Cape_Verde" {{$tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''}}>
                                        (GMT-01:00) Cape Verde Is.
                                    </option>
                                    <option value="Atlantic/Azores" {{$tz?($tz=='Atlantic/Azores'?'selected':''):''}}>
                                        (GMT-01:00)
                                        Azores
                                    </option>
                                    <option value="Africa/Casablanca" {{$tz?($tz=='Africa/Casablanca'?'selected':''):''}}>
                                        (GMT+00:00) Casablanca, Monrovia, Reykjavik
                                    </option>
                                    <option value="Etc/Greenwich" {{$tz?($tz=='Etc/Greenwich'?'selected':''):''}}>
                                        (GMT+00:00)
                                        Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London
                                    </option>
                                    <option value="Europe/Amsterdam" {{$tz?($tz=='Europe/Amsterdam'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna
                                    </option>
                                    <option value="Europe/Belgrade" {{$tz?($tz=='Europe/Belgrade'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Belgrade, Bratislava, Budapest, Ljubljana, Prague
                                    </option>
                                    <option value="Europe/Brussels" {{$tz?($tz=='Europe/Brussels'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Brussels, Copenhagen, Madrid, Paris
                                    </option>
                                    <option value="Europe/Sarajevo" {{$tz?($tz=='Europe/Sarajevo'?'selected':''):''}}>
                                        (GMT+01:00)
                                        Sarajevo, Skopje, Warsaw, Zagreb
                                    </option>
                                    <option value="Africa/Lagos" {{$tz?($tz=='Africa/Lagos'?'selected':''):''}}>(GMT+01:00)
                                        West
                                        Central Africa
                                    </option>
                                    <option value="Asia/Amman" {{$tz?($tz=='Asia/Amman'?'selected':''):''}}>(GMT+02:00)
                                        Amman
                                    </option>
                                    <option value="Europe/Athens" {{$tz?($tz=='Europe/Athens'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Athens, Bucharest, Istanbul
                                    </option>
                                    <option value="Asia/Beirut" {{$tz?($tz=='Asia/Beirut'?'selected':''):''}}>(GMT+02:00)
                                        Beirut
                                    </option>
                                    <option value="Africa/Cairo" {{$tz?($tz=='Africa/Cairo'?'selected':''):''}}>(GMT+02:00)
                                        Cairo
                                    </option>
                                    <option value="Africa/Harare" {{$tz?($tz=='Africa/Harare'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Harare, Pretoria
                                    </option>
                                    <option value="Europe/Helsinki" {{$tz?($tz=='Europe/Helsinki'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius
                                    </option>
                                    <option value="Asia/Jerusalem" {{$tz?($tz=='Asia/Jerusalem'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Jerusalem
                                    </option>
                                    <option value="Europe/Minsk" {{$tz?($tz=='Europe/Minsk'?'selected':''):''}}>(GMT+02:00)
                                        Minsk
                                    </option>
                                    <option value="Africa/Windhoek" {{$tz?($tz=='Africa/Windhoek'?'selected':''):''}}>
                                        (GMT+02:00)
                                        Windhoek
                                    </option>
                                    <option value="Asia/Kuwait" {{$tz?($tz=='Asia/Kuwait'?'selected':''):''}}>(GMT+03:00)
                                        Kuwait,
                                        Riyadh, Baghdad
                                    </option>
                                    <option value="Europe/Moscow" {{$tz?($tz=='Europe/Moscow'?'selected':''):''}}>
                                        (GMT+03:00)
                                        Moscow, St. Petersburg, Volgograd
                                    </option>
                                    <option value="Africa/Nairobi" {{$tz?($tz=='Africa/Nairobi'?'selected':''):''}}>
                                        (GMT+03:00)
                                        Nairobi
                                    </option>
                                    <option value="Asia/Tbilisi" {{$tz?($tz=='Asia/Tbilisi'?'selected':''):''}}>(GMT+03:00)
                                        Tbilisi
                                    </option>
                                    <option value="Asia/Tehran" {{$tz?($tz=='Asia/Tehran'?'selected':''):''}}>(GMT+03:30)
                                        Tehran
                                    </option>
                                    <option value="Asia/Muscat" {{$tz?($tz=='Asia/Muscat'?'selected':''):''}}>(GMT+04:00)
                                        Abu Dhabi,
                                        Muscat
                                    </option>
                                    <option value="Asia/Baku" {{$tz?($tz=='Asia/Baku'?'selected':''):''}}>(GMT+04:00) Baku
                                    </option>
                                    <option value="Asia/Yerevan" {{$tz?($tz=='Asia/Yerevan'?'selected':''):''}}>(GMT+04:00)
                                        Yerevan
                                    </option>
                                    <option value="Asia/Kabul" {{$tz?($tz=='Asia/Kabul'?'selected':''):''}}>(GMT+04:30)
                                        Kabul
                                    </option>
                                    <option value="Asia/Yekaterinburg" {{$tz?($tz=='Asia/Yekaterinburg'?'selected':''):''}}>
                                        (GMT+05:00) Yekaterinburg
                                    </option>
                                    <option value="Asia/Karachi" {{$tz?($tz=='Asia/Karachi'?'selected':''):''}}>(GMT+05:00)
                                        Islamabad, Karachi, Tashkent
                                    </option>
                                    <option value="Asia/Calcutta" {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>
                                        (GMT+05:30)
                                        Chennai, Kolkata, Mumbai, New Delhi
                                    </option>
                                    <!-- <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                    <option value="Asia/Katmandu" {{$tz?($tz=='Asia/Katmandu'?'selected':''):''}}>
                                        (GMT+05:45)
                                        Kathmandu
                                    </option>
                                    <option value="Asia/Almaty" {{$tz?($tz=='Asia/Almaty'?'selected':''):''}}>(GMT+06:00)
                                        Almaty,
                                        Novosibirsk
                                    </option>
                                    <option value="Asia/Dhaka" {{$tz?($tz=='Asia/Dhaka'?'selected':''):''}}>(GMT+06:00)
                                        Astana,
                                        Dhaka
                                    </option>
                                    <option value="Asia/Rangoon" {{$tz?($tz=='Asia/Rangoon'?'selected':''):''}}>(GMT+06:30)
                                        Yangon
                                        (Rangoon)
                                    </option>
                                    <option value="Asia/Bangkok" {{$tz?($tz=='"Asia/Bangkok'?'selected':''):''}}>(GMT+07:00)
                                        Bangkok, Hanoi, Jakarta
                                    </option>
                                    <option value="Asia/Krasnoyarsk" {{$tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''}}>
                                        (GMT+07:00)
                                        Krasnoyarsk
                                    </option>
                                    <option value="Asia/Hong_Kong" {{$tz?($tz=='Asia/Hong_Kong'?'selected':''):''}}>
                                        (GMT+08:00)
                                        Beijing, Chongqing, Hong Kong, Urumqi
                                    </option>
                                    <option value="Asia/Kuala_Lumpur" {{$tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''}}>
                                        (GMT+08:00) Kuala Lumpur, Singapore
                                    </option>
                                    <option value="Asia/Irkutsk" {{$tz?($tz=='Asia/Irkutsk'?'selected':''):''}}>(GMT+08:00)
                                        Irkutsk,
                                        Ulaan Bataar
                                    </option>
                                    <option value="Australia/Perth" {{$tz?($tz=='Australia/Perth'?'selected':''):''}}>
                                        (GMT+08:00)
                                        Perth
                                    </option>
                                    <option value="Asia/Taipei" {{$tz?($tz=='Asia/Taipei'?'selected':''):''}}>(GMT+08:00)
                                        Taipei
                                    </option>
                                    <option value="Asia/Tokyo" {{$tz?($tz=='Asia/Tokyo'?'selected':''):''}}>(GMT+09:00)
                                        Osaka,
                                        Sapporo, Tokyo
                                    </option>
                                    <option value="Asia/Seoul" {{$tz?($tz=='Asia/Seoul'?'selected':''):''}}>(GMT+09:00)
                                        Seoul
                                    </option>
                                    <option value="Asia/Yakutsk" {{$tz?($tz=='Asia/Yakutsk'?'selected':''):''}}>(GMT+09:00)
                                        Yakutsk
                                    </option>
                                    <option value="Australia/Adelaide" {{$tz?($tz=='Australia/Adelaide'?'selected':''):''}}>
                                        (GMT+09:30) Adelaide
                                    </option>
                                    <option value="Australia/Darwin" {{$tz?($tz=='Australia/Darwin'?'selected':''):''}}>
                                        (GMT+09:30)
                                        Darwin
                                    </option>
                                    <option value="Australia/Brisbane" {{$tz?($tz=='Australia/Brisbane'?'selected':''):''}}>
                                        (GMT+10:00) Brisbane
                                    </option>
                                    <option value="Australia/Canberra" {{$tz?($tz=='Australia/Canberra'?'selected':''):''}}>
                                        (GMT+10:00) Canberra, Melbourne, Sydney
                                    </option>
                                    <option value="Australia/Hobart" {{$tz?($tz=='Australia/Hobart'?'selected':''):''}}>
                                        (GMT+10:00)
                                        Hobart
                                    </option>
                                    <option value="Pacific/Guam" {{$tz?($tz=='Pacific/Guam'?'selected':''):''}}>(GMT+10:00)
                                        Guam,
                                        Port Moresby
                                    </option>
                                    <option value="Asia/Vladivostok" {{$tz?($tz=='Asia/Vladivostok'?'selected':''):''}}>
                                        (GMT+10:00)
                                        Vladivostok
                                    </option>
                                    <option value="Asia/Magadan" {{$tz?($tz=='Asia/Magadan'?'selected':''):''}}>(GMT+11:00)
                                        Magadan,
                                        Solomon Is., New Caledonia
                                    </option>
                                    <option value="Pacific/Auckland" {{$tz?($tz=='Pacific/Auckland'?'selected':''):''}}>
                                        (GMT+12:00)
                                        Auckland, Wellington
                                    </option>
                                    <option value="Pacific/Fiji" {{$tz?($tz=='Pacific/Fiji'?'selected':''):''}}>(GMT+12:00)
                                        Fiji,
                                        Kamchatka, Marshall Is.
                                    </option>
                                    <option value="Pacific/Tongatapu" {{$tz?($tz=='Pacific/Tongatapu'?'selected':''):''}}>
                                        (GMT+13:00) Nuku'alofa
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex" for="language">{{translate('language')}}</label>
                                <select name="language" class="form-control js-select2-custom">
                                    @if (isset($business_setting['language']))
                                    @foreach (json_decode($business_setting['language']) as $item)
                                        <option value="{{ $item->code }}" {{ $item->default == 1?'selected':'' }}>{{ $item->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('company_address')}}</label>
                                <input type="text" value="{{ $business_setting['shop_address'] }}"
                                    name="shop_address" class="form-control"
                                    placeholder="{{translate('your_shop_address')}}"
                                    required>
                            </div>
                        </div>
                        @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">
                                    {{translate('latitude')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('copy_the_latitude_of_your_business_location_from_Google_Maps_and_paste_it_here')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input class="form-control" type="text" name="latitude"
                                    value="{{ isset($default_location)?$default_location['lat']:'' }}"
                                    placeholder="{{translate('latitude')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">
                                    {{translate('longitude')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('copy_the_longitude_of_your_business_location_from_Google_Maps_and_paste_it_here')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input class="form-control" type="text" name="longitude"
                                    value="{{ isset($default_location)?$default_location['lng']:'' }}"
                                    placeholder="{{translate('longitude')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-1">
                        <i class="tio-briefcase"></i>
                        {{translate('business_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex" for="currency">{{translate('currency')}} </label>
                                <select name="currency_id" class="form-control js-select2-custom">
                                    @foreach ($CurrencyList as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $business_setting['system_default_currency'] ?'selected':'' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            @php($config=\App\CPU\Helpers::get_business_settings('currency_symbol_position'))
                            <label class="title-color d-flex">{{translate('currency_Position')}}</label>
                            <div class="form-control form-group d-flex gap-2">
                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="left" name="currency_symbol_position" id="currency_position_left" {{ $business_setting['currency_symbol_position'] == 'left' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="currency_position_left">({{\App\CPU\BackEndHelper::currency_symbol()}}) {{translate('left')}}</label>
                                </div>
                                <!-- End Custom Radio -->

                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="right" name="currency_symbol_position" id="currency_position_right" {{ $business_setting['currency_symbol_position'] == 'right' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="currency_position_right">{{translate('right')}} ({{\App\CPU\BackEndHelper::currency_symbol()}})</label>
                                </div>
                                <!-- End Custom Radio -->
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="title-color d-flex">
                                {{translate('forgot_password_verification_by')}}
                                <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('set_how_users_of_recover_their_forgotten_password')}}">
                                    <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                </span>
                            </label>
                            <div class="form-control form-group d-flex gap-2">
                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="email" name="forgot_password_verification" id="verification_by_email" {{ $business_setting['forgot_password_verification'] == 'email' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="verification_by_email">{{translate('email')}}</label>
                                </div>
                                <!-- End Custom Radio -->

                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="phone" name="forgot_password_verification" id="verification_by_phone" {{ $business_setting['forgot_password_verification'] == 'phone' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="verification_by_phone">{{translate('phone (OTP)')}}</label>
                                </div>
                                <!-- End Custom Radio -->
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <label class="title-color d-flex">{{translate('business_model')}}</label>
                            <div class="form-control form-group d-flex gap-2">
                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="single" name="business_mode" id="single_vendor" {{ $business_setting['business_mode'] == 'single' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="single_vendor">{{translate('single_vendor')}}</label>
                                </div>
                                <!-- End Custom Radio -->

                                <!-- Custom Radio -->
                                <div class="custom-control custom-radio flex-grow-1">
                                    <input type="radio" class="custom-control-input" value="multi" name="business_mode" id="multi_vendor" {{ $business_setting['business_mode'] == 'multi' ? 'checked':'' }}>
                                    <label class="custom-control-label" for="multi_vendor">{{translate('multi_vendor')}}</label>
                                </div>
                                <!-- End Custom Radio -->
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('email_Verification')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_users_can_receive_verification_codes_on_their_registered_email_addresses')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="email_verification"
                                    onclick="toogleModal(event,'email_verification','email-verification-off.png','email-verification-on.png','{{translate('want_to_Turn_OFF_the_Email_Verification')}}','{{translate('want_to_Turn_ON_the_Email_Verification')}}',`<p>{{translate('if_disabled_users_would_not_receive_verification_codes_on_their_registered_email_addresses')}}</p>`,`<p>{{translate('if_enabled_users_will_receive_verification_codes_on_their_registered_email_addresses')}}</p>`)">
                                        <input type="checkbox" class="switcher_input" name="email_verification" id="email_verification" value="1" {{ $business_setting['email_verification'] == 1 ? 'checked':'' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4">
                            @php($phone_verification=\App\CPU\Helpers::get_business_settings('phone_verification'))
                            <!-- phone verification is otp verification -->
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                    <span class="title-color">
                                        {{translate('OTP_Verification')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_users_can_receive_verification_codes_via_OTP_messages_on_their_registered_phone_numbers')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="otp_verification"onclick="toogleModal(event,'otp_verification','otp-verification-off.png','otp-verification-on.png','{{translate('want_to_Turn_OFF_the_OTP_Verification')}}','{{translate('want_to_Turn_ON_the_OTP_Verification')}}',`<p>{{translate('if_disabled_users_would_not_receive_verification_codes_on_their_registered_phone_numbers')}}</p>`,`<p>{{translate('if_enabled_users_will_receive_verification_codes_on_their_registered_phone_numbers')}}</p>`)">
                                        <input type="checkbox" class="switcher_input" name="phone_verification" id="otp_verification" value="1" {{ $phone_verification == 1 ? 'checked':'' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">
                                    {{translate('pagination')}}
                                    <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('this_number_indicates_how_much_data_will_be_shown_in_the_list_or_table')}}">
                                        <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                    </span>
                                </label>
                                <input type="number" value="{{ $business_setting['pagination_limit'] }}"
                                    name="pagination_limit" class="form-control" placeholder="25">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label class="title-color d-flex">{{translate('Company_Copyright_Text')}}</label>
                                <input class="form-control" type="text" name="company_copyright_text"
                                    value="{{ $business_setting['company_copyright_text'] }}"
                                    placeholder="{{translate('company_copyright_text')}}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label
                                    class="input-label text-capitalize">{{translate('digit_after_decimal_point')}}( {{translate('ex')}} : 0.00)</label>
                                <input type="number" value="{{ $business_setting['decimal_point_settings'] }}"
                                       name="decimal_point_settings" class="form-control" min="0" placeholder="{{translate('4')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- App Downlaod Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                        <i class="tio-briefcase"></i>
                        {{translate('app_download_info')}}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <div class="d-flex gap-2 align-items-center text-capitalize mb-3">
                                <img width="22" src="{{asset('/public/assets/back-end/img/apple.png')}}" alt="">
                                {{translate('apple_store')}}:
                            </div>

                            @php($app_store_download = \App\CPU\Helpers::get_business_settings('download_app_apple_stroe'))

                            <div class="bg-aliceblue p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <span class="title-color text-capitalize">
                                        {{translate('download_link')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_the_download_button_from_the_App_Store_will_be_visible_in_the_Footer_section')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="app_store_download_status"
                                    onclick="toogleModal(event,'app_store_download_status','app-store-download-off.png','app-store-download-on.png','{{translate('want_to_Turn_OFF_the_App_Store_button')}}','{{translate('want_to_Turn_ON_the_App_Store_button')}}',`<p>{{translate('if_disabled_the_App_Store_button_will_be_hidden_from_the_website_footer')}}</p>`,`<p>{{translate('if_enabled_everyone_can_see_the_App_Store_button_in_the_website_footer')}}</p>`)">
                                        <input type="checkbox" value="1" class="switcher_input" name="app_store_download_status" id="app_store_download_status" {{ $app_store_download['status'] == 1 ? 'checked':''  }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <input type="url" name="app_store_download_url" class="form-control" value="{{ $app_store_download['link'] ?? '' }}" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex gap-2 align-items-center text-capitalize mb-3">
                                <img width="22" src="{{asset('/public/assets/back-end/img/play_store.png')}}" alt="">
                                {{translate('google_play_store')}}:
                            </div>

                            @php($play_store_download = \App\CPU\Helpers::get_business_settings('download_app_google_stroe'))

                            <div class="bg-aliceblue p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <span class="title-color text-capitalize">
                                        {{translate('download_link')}}
                                        <span class="input-label-secondary cursor-pointer" data-toggle="tooltip" data-placement="right" title="{{translate('if_enabled_the_Google_Play_Store_will_be_visible_in_the_website_footer_section')}}">
                                            <img width="16" src="{{asset('/public/assets/back-end/img/info-circle.svg')}}" alt="">
                                        </span>
                                    </span>

                                    <label class="switcher" for="play_store_download_status"
                                        onclick="toogleModal(event,'play_store_download_status','play-store-download-off.png','app-store-download-on.png','{{translate('want_to_Turn_OFF_the_Google_Play_Store_button')}}','{{translate('want_to_Turn_ON_the_Google_Play_Store_button')}}',`<p>{{translate('if_disabled_the_Google_Play_Store_button_will_be_hidden_from_the_website_footer')}}</p>`,`<p>{{translate('if_enabled_everyone_can_see_the_Google_Play_Store_button_in_the_website_footer')}}</p>`)"
                                        >
                                        <input type="checkbox" value="1" class="switcher_input" name="play_store_download_status" id="play_store_download_status" {{ $play_store_download['status'] == 1 ? 'checked':'' }}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>

                                <input type="url" name="play_store_download_url" class="form-control" value="{{ $play_store_download['link'] ?? '' }}" placeholder="{{translate('Ex: https://play.google.com/store/apps')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/website-color.png')}}" alt="">
                                {{translate('website_Color')}}
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-4 justify-content-around">
                            @php($colors=\App\Model\BusinessSetting::where(['type'=>'colors'])->first())
                            @if(isset($colors))
                                @php($data=json_decode($colors['value']))
                            @else
                                @php(\Illuminate\Support\Facades\DB::table('business_settings')->insert([
                                        'type'=>'colors',
                                        'value'=>json_encode(
                                            [
                                                'primary'=>null,
                                                'secondary'=>null,
                                            ])
                                    ]))
                                @php($colors=\App\Model\BusinessSetting::where(['type'=>'colors'])->first())
                                @php($data=json_decode($colors['value']))
                            @endif
                            <div class="form-group">
                                <input type="color" name="primary" value="{{ $business_setting['primary_color'] }}"
                                class="form-control form-control_color">
                                <div class="text-center">
                                    <div class="title-color mb-4 mt-3">{{ strtoupper($business_setting['primary_color']) }}</div>
                                    <label class="title-color text-capitalize">{{translate('primary_Color')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="color" name="secondary" value="{{ $business_setting['secondary_color'] }}"
                                class="form-control form-control_color">
                                <div class="text-center">
                                    <div class="title-color mb-4 mt-3">{{ strtoupper($business_setting['secondary_color']) }}</div>
                                    <label class="title-color text-capitalize">
                                        {{translate('secondary_Color')}}
                                    </label>
                                </div>
                            </div>

                            @if(theme_root_path() == 'theme_aster')
                            <div class="form-group">
                                <input type="color" name="primary_light" value="{{ $business_setting['primary_color_light'] ?? '#CFDFFB' }}"
                                       class="form-control form-control_color">
                                <div class="text-center">
                                    <div class="title-color mb-4 mt-3">{{ $business_setting['primary_color_light'] ?? '#CFDFFB' }}</div>
                                    <label class="title-color text-capitalize">{{translate('primary_Light_Color')}}</label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/header-logo.png')}}" alt="">
                                {{translate('website_Header_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Main website Logo'] }}</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerWL"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_web_logo" id="customFileUploadWL"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadWL">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('website_Footer_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">{{ THEME_RATIO[theme_root_path()]['Main website Logo'] }}</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerWFL"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/app/public/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_footer_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_footer_logo" id="customFileUploadWFL"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadWFL">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('website_Favicon')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{translate('ratio')}} 1:1)</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerFI"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_fav_icon'])->pluck('value')[0]}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="company_fav_icon" id="customFileUploadFI"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadFI">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('loading_Gif')}}
                            </h5>
                            <span class="badge badge-soft-info">( {{translate('ratio')}} 1:1 )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerLoader"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="loader_gif" id="customFileUploadLoader"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadLoader">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/footer-logo.png')}}" alt="">
                                {{translate('App_Logo')}}
                            </h5>
                            <span class="badge badge-soft-info">( 100X60 px )</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-around">
                            <center>
                                <img height="60" id="viewerML"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/company')}}/{{\App\Model\BusinessSetting::where(['type' => 'company_mobile_logo'])->pluck('value')[0]}}">
                            </center>
                            <div class="mt-4 position-relative">
                                <input type="file" name="company_mobile_logo" id="customFileUploadML"
                                        class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label"
                                        for="customFileUploadML">{{translate('choose_File')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn--primary text-capitalize px-4">{{translate('save_information')}}</button>
            </div>
        </form>
    </div>

@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>

        $("#customFileUploadShop").change(function () {
            read_image(this, 'viewerShop');
        });

        $("#customFileUploadWL").change(function () {
            read_image(this, 'viewerWL');
        });

        $("#customFileUploadWFL").change(function () {
            read_image(this, 'viewerWFL');
        });

        $("#customFileUploadML").change(function () {
            read_image(this, 'viewerML');
        });

        $("#customFileUploadFI").change(function () {
            read_image(this, 'viewerFI');
        });

        $("#customFileUploadLoader").change(function () {
            read_image(this, 'viewerLoader');
        });

        function read_image(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    </script>
    <script>
        $(document).ready(function () {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>

    <script>
        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
        @php($language = $language->value ?? null)
        let language = {{$language}};
        $('#language').val(language);
    </script>

    <script>
        $('#maintenance_mode_form').on('submit', function(e){
            e.preventDefault();
            @if(env('APP_MODE')=='demo')
                call_demo();
                setTimeout(() => {
                    location.reload();
                }, 3000);
            @else
                $.get({
                    url: '{{route('admin.maintenance-mode')}}',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loading').fadeIn();
                    },
                    success: function (data) {
                        toastr.success(data.message);
                        location.reload();
                    },
                    complete: function () {
                        $('#loading').fadeOut();
                    },
                });
            @endif
        });

        function currency_symbol_position(route) {
            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').fadeIn();
                },
                success: function (data) {
                    toastr.success(data.message);
                },
                complete: function () {
                    $('#loading').fadeOut();
                },
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#phone_verification_on").click(function () {
                @if(env('APP_MODE')!='demo')
                if ($('#email_verification_on').prop("checked") == true) {
                    $('#email_verification_off').prop("checked", true);
                    $('#email_verification_on').prop("checked", false);
                    const message = "{{translate('both_Phone_&_Email_verification_can_not_be_active_at_a_time')}}";
                    toastr.info(message);
                }
                @else
                call_demo();
                @endif
            });
            $("#email_verification_on").click(function () {
                if ($('#phone_verification_on').prop("checked") == true) {
                    $('#phone_verification_off').prop("checked", true);
                    $('#phone_verification_on').prop("checked", false);
                    const message = "{{translate('both_Phone_&_Email_verification_can_not_be_active_at_a_time')}}";
                    toastr.info(message);
                }
            });
        });
    </script>
@endpush
