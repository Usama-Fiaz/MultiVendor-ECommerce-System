<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Currency;
use App\Model\SocialMedia;
use Brian2694\Toastr\Facades\Toastr;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\RSA\Formats\Keys\JWK;
use Carbon\Carbon;

class BusinessSettingsController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.general-settings');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('admin-views.business-settings.about-us', [
            'about_us' => $about_us,
        ]);

    }

    public function about_usUpdate(Request $data)
    {
        $validatedData = $data->validate([
            'about_us' => 'required',
        ]);
        BusinessSetting::where('type', 'about_us')->update(['value' => $data->about_us]);
        Toastr::success(translate('about_us_updated_successfully'));
        return back();
    }

    // Social Media
    public function social_media()
    {
        return view('admin-views.business-settings.social-media');
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = SocialMedia::where('status', 1)->orderBy('id', 'desc')->get();

            $data->map(function($social_media){
                $social_media['name'] = translate($social_media['name']);
            });

            return response()->json($data);
        }
    }

    public function social_media_store(Request $request)
    {
        $check = SocialMedia::where('name', $request->name)->first();
        if ($check != null) {
            return response()->json([
                'error' => 1,
            ]);
        }
        if ($request->name == 'google-plus') {
            $icon = 'fa fa-google-plus-square';
        }
        if ($request->name == 'facebook') {
            $icon = 'fa fa-facebook';
        }
        if ($request->name == 'twitter') {
            $icon = 'fa fa-twitter';
        }
        if ($request->name == 'pinterest') {
            $icon = 'fa fa-pinterest';
        }
        if ($request->name == 'instagram') {
            $icon = 'fa fa-instagram';
        }
        if ($request->name == 'linkedin') {
            $icon = 'fa fa-linkedin';
        }
        $social_media = new SocialMedia;
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->icon = $icon;
        $social_media->save();
        return response()->json([
            'success' => 1,
        ]);
    }

    public function social_media_edit(Request $request)
    {
        $data = SocialMedia::where('id', $request->id)->first();
        return response()->json($data);
    }

    public function social_media_update(Request $request)
    {
        $social_media = SocialMedia::find($request->id);
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->save();
        return response()->json();
    }

    public function social_media_delete(Request $request)
    {
        $br = SocialMedia::find($request->id);
        $br->delete();
        return response()->json();
    }

    public function social_media_status_update(Request $request)
    {
        SocialMedia::where(['id' => $request['id']])->update([
            'active_status' => $request['status'],
        ]);

        if($request->ajax()) {
            return response()->json([
                'success' => 1,
            ], 200);
        }

        Toastr::success(translate('status_updated_successfully'));
        return back();
    }

    public function page($page)
    {
        $pages = array(
            'refund-policy',
            'return-policy',
            'cancellation-policy',
        );

        if(in_array($page, $pages)){
            $data = BusinessSetting::where('type', $page)->first();
            return view('admin-views.business-settings.page', compact('page', 'data'));
        }

        Toastr::error(translate('invalid_page'));
        return redirect()->back();
    }

    public function page_update(Request $request, $page)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $pages = array(
            'refund-policy',
            'return-policy',
            'cancellation-policy',
        );

        if(in_array($page, $pages)){
            BusinessSetting::where('type', $page)->update([
                'value' => json_encode([
                    'status' => is_null($request->status) ? 0 : 1,
                    'content' => $request->value
                ])
            ]);
            Toastr::success(translate('updated_successfully'));
        }else{
            Toastr::error(translate('invalid_page'));
        }
        return redirect()->back();
    }

    public function terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('admin-views.business-settings.terms-condition', compact('terms_condition'));
    }

    public function updateTermsCondition(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'terms_condition')->update(['value' => $data->value]);
        Toastr::success(translate('Terms_and_Condition_Updated_successfully'));
        return redirect()->back();
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'privacy_policy')->update(['value' => $data->value]);
        Toastr::success(translate('Privacy_policy_Updated_successfully'));
        return redirect()->back();
    }

    public function companyInfo()
    {

        $web = BusinessSetting::all();
        $settings = Helpers::get_settings($web, 'colors');
        $data = json_decode($settings['value'], true);

        $business_setting = [
            'primary_color' => $data['primary'] ?? '',
            'secondary_color' => $data['secondary'] ?? '',
            'primary_color_light' => isset($data['primary_light']) ? $data['primary_light'] : '',
            'company_name' => Helpers::get_settings($web, 'company_name')->value ?? '',
            'company_email' => Helpers::get_settings($web, 'company_email')->value ?? '',
            'company_phone' => Helpers::get_settings($web, 'company_phone')->value ?? '',
            'language' => Helpers::get_settings($web, 'language')->value ?? '',
            'web_logo' => Helpers::get_settings($web, 'company_web_logo')->value ?? '',
            'mob_logo' => Helpers::get_settings($web, 'company_mobile_logo')->value ?? '',
            'fav_icon' => Helpers::get_settings($web, 'company_fav_icon')->value ?? '',
            'footer_logo' => Helpers::get_settings($web, 'company_footer_logo')->value ?? '',
            'shop_address' => Helpers::get_settings($web, 'shop_address')->value ?? '',
            'company_copyright_text' => Helpers::get_settings($web, 'company_copyright_text')->value ?? '',
            'system_default_currency' => Helpers::get_settings($web, 'system_default_currency')->value ?? '',
            'currency_symbol_position' => Helpers::get_settings($web, 'currency_symbol_position')->value ?? '',
            'forgot_password_verification' => Helpers::get_settings($web, 'forgot_password_verification')->value ?? '',
            'business_mode' => Helpers::get_settings($web, 'business_mode')->value ?? '',
            'email_verification' => Helpers::get_settings($web, 'email_verification')->value ?? '',
            'otp_verification' => Helpers::get_settings($web, 'otp_verification')->value ?? '',
            'guest_checkout' => Helpers::get_settings($web, 'guest_checkout')->value ?? '',
            'pagination_limit' => Helpers::get_settings($web, 'pagination_limit')->value ?? '',
            'copyright_text' => Helpers::get_settings($web, 'company_copyright_text')->value ?? '',
            'decimal_point_settings' => !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0,
        ];

        $CurrencyList = Currency::all();
        return view('admin-views.business-settings.website-info', [
            'CurrencyList' => $CurrencyList,
            'business_setting' => $business_setting,
        ]);
    }

    public function productSettings()
    {
        $company_name = BusinessSetting::where('type', 'company_name')->first();
        $company_email = BusinessSetting::where('type', 'company_email')->first();
        $company_phone = BusinessSetting::where('type', 'company_phone')->first();
        $digital_product = \App\Model\BusinessSetting::where('type','digital_product')->first()->value;
        $brand = \App\Model\BusinessSetting::where('type','product_brand')->first()->value;

        return view('admin-views.business-settings.product-settings', compact('company_name','company_email','company_phone','digital_product','brand'));
    }

    public function updateInfo(Request $request)
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        // comapny name
        BusinessSetting::updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);

        // company email
        BusinessSetting::updateOrInsert(['type' => 'company_email'], [
            'value' => $request['company_email']
        ]);

        // company Phone
        BusinessSetting::updateOrInsert(['type' => 'company_phone'], [
            'value' => $request['company_phone']
        ]);

        //company copy right text
        BusinessSetting::updateOrInsert(['type' => 'company_copyright_text'], [
            'value' => $request['company_copyright_text']
        ]);

        //company time zone
        BusinessSetting::updateOrInsert(['type' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        //country
        BusinessSetting::updateOrInsert(['type' => 'country_code'], [
            'value' => $request['country']
        ]);

        //phone verification
        BusinessSetting::updateOrInsert(['type' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);

        //email verification
        BusinessSetting::updateOrInsert(['type' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        BusinessSetting::updateOrInsert(['type' => 'forgot_password_verification'], [
            'value' => $request['forgot_password_verification']
        ]);

        BusinessSetting::updateOrInsert(['type' => 'decimal_point_settings'], [
            'value' => $request['decimal_point_settings']
        ]);

        BusinessSetting::updateOrInsert(['type' => 'shop_address'], [
            'value' => $request['shop_address']
        ]);

        BusinessSetting::updateOrInsert(['type' => 'colors'], [
            'value' => json_encode([
                'primary' => $request['primary'],
                'secondary' => $request['secondary'],
                'primary_light' => $request['primary_light'] ?? '#CFDFFB',
            ]),
        ]);

        BusinessSetting::updateOrInsert(['type' => 'default_location'], [
            'value' => json_encode([
                    'lat' => $request['latitude'],
                    'lng' => $request['longitude'],
                ]),
        ]);

        BusinessSetting::updateOrInsert(['type' => 'system_default_currency'], [
            'value' => $request['currency_id'],
        ]);

        BusinessSetting::updateOrInsert(['type' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position'],
        ]);

        BusinessSetting::updateOrInsert(['type' => 'business_mode'], [
            'value' => $request['business_mode'],
        ]);

        BusinessSetting::updateOrInsert(['type' => 'download_app_apple_stroe'], [
            'value' => json_encode([
                'status' => $request['app_store_download_status'] ?? 0,
                'link' => $request['app_store_download_url'],
            ]),
        ]);

        BusinessSetting::updateOrInsert(['type' => 'download_app_google_stroe'], [
            'value' => json_encode([
                'status' => $request['play_store_download_status'] ?? 0,
                'link' => $request['play_store_download_url'],
            ]),
        ]);

        //web logo
        $webLogo = BusinessSetting::where(['type' => 'company_web_logo'])->first();
        if ($request->has('company_web_logo')) {
            $webLogo = ImageManager::update('company/', $webLogo, 'webp', $request->file('company_web_logo'));
            BusinessSetting::where(['type' => 'company_web_logo'])->update([
                'value' => $webLogo,
            ]);
        }

        //mobile logo
        $mobileLogo = BusinessSetting::where(['type' => 'company_mobile_logo'])->first();
        if ($request->has('company_mobile_logo')) {
            $mobileLogo = ImageManager::update('company/', $mobileLogo, 'webp', $request->file('company_mobile_logo'));
            BusinessSetting::where(['type' => 'company_mobile_logo'])->update([
                'value' => $mobileLogo,
            ]);
        }

        //web footer logo
        $webFooterLogo = BusinessSetting::where(['type' => 'company_footer_logo'])->first();
        if ($request->has('company_footer_logo')) {
            $webFooterLogo = ImageManager::update('company/', $webFooterLogo, 'webp', $request->file('company_footer_logo'));
            BusinessSetting::where(['type' => 'company_footer_logo'])->update([
                'value' => $webFooterLogo,
            ]);
        }

        //fav icon
        $favIcon = BusinessSetting::where(['type' => 'company_fav_icon'])->first();
        if ($request->has('company_fav_icon')) {
            $favIcon = ImageManager::update('company/', $favIcon, 'webp', $request->file('company_fav_icon'));
            BusinessSetting::where(['type' => 'company_fav_icon'])->update([
                'value' => $favIcon,
            ]);
        }

        //loader gif
        $loader_gif = BusinessSetting::where(['type' => 'loader_gif'])->first();
        if ($request->has('loader_gif')) {
            $loader_gif = ImageManager::update('company/', $loader_gif, 'webp', $request->file('loader_gif'));
            BusinessSetting::updateOrInsert(['type' => 'loader_gif'], [
                'value' => $loader_gif,
            ]);
        }

        $language = BusinessSetting::where('type', 'language')->first();
        $lang_array = [];
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $request['language']) {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => 1,
                    'default' => true,
                ];
                $lang_array[] = $lang;
            } else {
                $lang = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'direction' => $data['direction'] ?? 'ltr',
                    'code' => $data['code'],
                    'status' => $data['status'],
                    'default' => false,
                ];
                $lang_array[] = $lang;
            }
        }
        BusinessSetting::where('type', 'language')->update([
            'value' => $lang_array
        ]);

        //pagination
        $request->validate([
            'pagination_limit' => 'numeric',
        ]);
        BusinessSetting::updateOrInsert(['type' => 'pagination_limit'], [
            'value' => $request['pagination_limit'],
        ]);

        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function announcement()
    {
        $announcement=\App\CPU\Helpers::get_business_settings('announcement');
        return view('admin-views.business-settings.website-announcement', compact('announcement'));
    }

    public function updateAnnouncement(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'announcement'], [
            'value' => json_encode(
                [   'status' => $request['announcement_status'],
                    'color' => $request['announcement_color'],
                    'text_color' => $request['text_color'],
                    'announcement' => $request['announcement'],
                ]),
        ]);

        Toastr::success(translate('announcement_updated_successfully'));
        return back();
    }

    public function app_settings()
    {
        $user_app_version_control = BusinessSetting::where(['type' => 'user_app_version_control'])->first();
        $seller_app_version_control = BusinessSetting::where(['type' => 'seller_app_version_control'])->first();
        $delivery_man_app_version_control = BusinessSetting::where(['type' => 'delivery_man_app_version_control'])->first();

        return view('admin-views.business-settings.apps-settings', compact('user_app_version_control', 'seller_app_version_control', 'delivery_man_app_version_control'));
    }

    public function app_settings_update(Request $request)
    {
        $types = array(
            'user_app_version_control',
            'seller_app_version_control',
            'delivery_man_app_version_control',
        );

        if(in_array($request->type, $types)){
            BusinessSetting::updateOrInsert(['type' => $request->type], [
                'value' => json_encode([
                    'for_android' => $request['for_android'],
                    'for_ios' => $request['for_ios'],
                ]),
            ]);
        }

        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function fcm_index()
    {
        return view('admin-views.business-settings.fcm-index');
    }

    public function update_fcm(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'fcm_project_id'], [
            'value' => $request['fcm_project_id'],
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'push_notification_key'], [
            'value' => $request['push_notification_key'],
        ]);

        Toastr::success(translate('Settings_updated'));
        return back();
    }

    public function update_fcm_messages(Request $request)
    {

        DB::table('business_settings')->updateOrInsert(['type' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'] ?? 0,
                'message' => $request['pending_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'] ?? 0,
                'message' => $request['confirm_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'] ?? 0,
                'message' => $request['processing_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'] ?? 0,
                'message' => $request['out_for_delivery_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivered_status'] ?? 0,
                'message' => $request['delivered_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_returned_message'], [
            'value' => json_encode([
                'status' => $request['returned_status'] ?? 0,
                'message' => $request['returned_message'],
            ]),
        ]);


        DB::table('business_settings')->updateOrInsert(['type' => 'order_failed_message'], [
            'value' => json_encode([
                'status' => $request['failed_status'] ?? 0,
                'message' => $request['failed_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_start_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_start_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'delivery_boy_expected_delivery_date_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_expected_delivery_date_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_expected_delivery_date_message'],
            ]),
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'order_canceled'], [
            'value' => json_encode([
                'status' => $request['order_canceled_status'] == 1 ? 1 : 0,
                'message' => $request['order_canceled_message'],
            ]),
        ]);

        Toastr::success(translate('Message_updated'));
        return back();
    }

    public function seller_settings()
    {
        $sales_commission = BusinessSetting::where('type', 'sales_commission')->first();
        if (!isset($sales_commission)) {
            DB::table('business_settings')->insert(['type' => 'sales_commission', 'value' => 0]);
        }

        $seller_registration = BusinessSetting::where('type', 'seller_registration')->first();
        if (!isset($seller_registration)) {
            DB::table('business_settings')->insert(['type' => 'seller_registration', 'value' => 1]);
        }

        return view('admin-views.business-settings.seller-settings');
    }

    public function update_language(Request $request)
    {
        $languages = $request['language'];
        if (in_array('en', $languages)) {
            unset($languages[array_search('en', $languages)]);
        }
        array_unshift($languages, 'en');

        DB::table('business_settings')->where(['type' => 'pnc_language'])->update([
            'value' => json_encode($languages),
        ]);
        Toastr::success(translate('Language_updated'));
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where(['type' => 'social_login'])->first();
        $apple = BusinessSetting::where(['type' => 'apple_login'])->first();
        return view('admin-views.business-settings.social-login.view', compact('data', 'apple'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('type', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'] ?? 0,
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('type', 'social_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate($service . '_credentials_updated'));
        return redirect()->back();

    }

    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('type', 'apple_login')->first();
        $credential_array = [];
        if ($request->hasfile('service_file')) {
            $fileName = ImageManager::file_upload('apple-login/', 'p8', $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName) ? $fileName : $data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('type', 'apple_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('credential_updated', ['service' => $service]));
        return redirect()->back();
    }

    public function view_social_media_chat()
    {
        return view('admin-views.business-settings.social-media-chat.view');
    }

    public function update_social_media_chat(Request $request, $service)
    {
        if($service == 'messenger'){
            DB::table('business_settings')->updateOrInsert(['type' => 'messenger'], [
                'type' => 'messenger',
                'value' => json_encode([
                    'status' => $request['status'] ?? 0,
                    'script' => $request['script']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }elseif($service == 'whatsapp'){
            DB::table('business_settings')->updateOrInsert(['type' => 'whatsapp'], [
                'type' => 'whatsapp',
                'value' => json_encode([
                    'status' => $request['status'] ?? 0,
                    'phone' => $request['phone']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }else{
            Toastr::warning(translate($service . '_information_update_fail'));
            return redirect()->back();
        }

        Toastr::success(translate($service . '_information_update_successfully'));
        return redirect()->back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        $config = (array)json_decode(BusinessSetting::where(['type' => 'recaptcha'])->first()->value);
        return view('admin-views.business-settings.recaptcha-index', compact('config'));
    }
    public function recaptcha_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'recaptcha'], [
            'type' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'] ?? 0,
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        Toastr::success(translate('Updated_Successfully'));
        return back();
    }
    public function map_api()
    {
        return view('admin-views.business-settings.map-api.index');
    }

    public function map_api_update(Request $request)
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(translate('config_data_updated'));
        return back();
    }

    public function analytics_index()
    {
        return view('admin-views.business-settings.analytics.index');
    }
    public function analytics_update(Request $request)
    {
        $request->validate([
            'pixel_analytics' => 'required'
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'pixel_analytics'], [
            'value' => $request['pixel_analytics']
        ]);

        Toastr::success(translate('config_data_updated'));
        return back();
    }
    public function google_tag_analytics_update(Request $request)
    {
        $request->validate([
            'google_tag_manager_id' => 'required'
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'google_tag_manager_id'], [
            'value' => $request['google_tag_manager_id']
        ]);

        Toastr::success(translate('google_tag_manager_id_updated'));
        return back();
    }

    public function updateProductSettings(Request $request){
        DB::table('business_settings')->updateOrInsert(['type' => 'stock_limit'], [
            'value' => $request['stock_limit']
        ]);
        BusinessSetting::updateOrInsert(['type' => 'product_brand'], [
            'value' => $request->product_brand ?? 0,
        ]);
        BusinessSetting::updateOrInsert(['type' => 'digital_product'], [
            'value' => $request->digital_product ?? 0,
        ]);

        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function countryRestrictionStatusChange(Request $request)
    {
        $delivery_country_restriction_status = BusinessSetting::where('type', 'delivery_country_restriction')->first();

        if (isset($delivery_country_restriction_status)) {
            BusinessSetting::where(['type' => 'delivery_country_restriction'])->update(['value' => $request->status ?? 0]);
        } else {
            BusinessSetting::insert([
                'type' => 'delivery_country_restriction',
                'value' => $request->status ?? 0,
                'updated_at' => now()
            ]);
        }

        if($request->ajax())
        {
            return response()->json([
                'message' => translate('delivery_country_restriction_status_changed_successfully'),
                'status' => true
            ]);
        }

        return back();
    }

    public function zipcodeRestrictionStatusChange(Request $request)
    {
        $zip_code_area_restriction_status = BusinessSetting::where('type', 'delivery_zip_code_area_restriction')->first();

        if (isset($zip_code_area_restriction_status)) {
            BusinessSetting::where(['type' => 'delivery_zip_code_area_restriction'])->update(['value' => $request->status ?? 0]);
        } else {
            BusinessSetting::insert([
                'type' => 'delivery_zip_code_area_restriction',
                'value' => $request->status ?? 0,
                'updated_at' => now()
            ]);
        }

        if($request->ajax())
        {
            return response()->json([
                'message' => translate('delivery_zip_code_restriction_status_changed_successfully'),
                'status' => true,
            ]);
        }

        return back();
    }

    public function cookie_settings(Request $request){
        $data['cookie_setting'] = Helpers::get_business_settings('cookie_setting');

        return view('admin-views.business-settings.cookie-settings', compact('data'));
    }

    public function cookie_setting_update(Request $request)
    {
        BusinessSetting::updateOrInsert(['type' => 'cookie_setting'], [
            'value' => json_encode([
                'status'=>$request->status ?? 0,
                'cookie_text'=>$request->cookie_text,
            ]),
            'updated_at' => now()
        ]);

        Toastr::success(translate('cookie_settings_updated_successfully'));
        return redirect()->back();
    }

    public function otp_setup()
    {
        $maximum_otp_hit = BusinessSetting::where('type','maximum_otp_hit')->first()->value ?? 0;
        $otp_resend_time = BusinessSetting::where('type','otp_resend_time')->first()->value ?? 0;
        $temporary_block_time = BusinessSetting::where('type','temporary_block_time')->first()->value ?? 0;
        $maximum_login_hit = BusinessSetting::where('type','maximum_login_hit')->first()->value ?? 0;
        $temporary_login_block_time = BusinessSetting::where('type','temporary_login_block_time')->first()->value ?? 0;

        return view('admin-views.business-settings.otp-setup', compact('maximum_otp_hit', 'otp_resend_time',
        'temporary_block_time', 'maximum_login_hit', 'temporary_login_block_time'));
    }

    public function otp_setup_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['type' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings_updated'));
        return back();
    }

    public function features_section()
    {
        $features_section_top = BusinessSetting::where('type', 'features_section_top')->first();
        $features_section_middle = BusinessSetting::where('type', 'features_section_middle')->first();
        $features_section_bottom = BusinessSetting::where('type', 'features_section_bottom')->first();
        return view('admin-views.business-settings.features-section.view', compact('features_section_top','features_section_middle','features_section_bottom'));
    }

    public function features_section_submit(Request $request)
    {
        // features_section_top
        BusinessSetting::updateOrInsert(['type' => 'features_section_top'], [
            'value' => json_encode($request['features_section_top']),
            'created_at' => Carbon::now(),
        ]);

        $section_middle = [];
        if($request->features_section_middle)
        {
            foreach($request->features_section_middle['title'] as $key => $value){
                $section_middle[] = [
                    'title' => $request->features_section_middle['title'][$key] ?? '',
                    'subtitle' => $request->features_section_middle['subtitle'][$key] ?? '',
                ];
            }
        }
        // features_section_middle
        BusinessSetting::updateOrInsert(['type' => 'features_section_middle'], [
            'value' => json_encode($section_middle),
            'created_at' => Carbon::now(),
        ]);

        if($request->features_section_bottom)
        {
            $features_section_bottom = BusinessSetting::where(['type' => 'features_section_bottom'])->first();
            if($features_section_bottom)
            {
                $section_bottom = json_decode($features_section_bottom->value);
            }else{
                $section_bottom = [];
            }
            foreach($request->features_section_bottom['title'] as $key => $value){

                if (!empty($request->features_section_bottom_icon) && isset($request->features_section_bottom_icon[$key]))
                {
                    $image = ImageManager::upload('banner/', 'webp', $request->features_section_bottom_icon[$key]);
                } else {
                    $image = '';
                }

                $section_bottom[] = [
                    'title' => $request->features_section_bottom['title'][$key],
                    'subtitle' => $request->features_section_bottom['subtitle'][$key],
                    'icon' => $image,
                ];
            }

            // features_section_bottom
            BusinessSetting::updateOrInsert(['type' => 'features_section_bottom'], [
                'value' => json_encode($section_bottom),
                'created_at' => Carbon::now(),
            ]);

        }

        return back();
    }

    public function features_section_icon_remove(Request $request)
    {
        $data = BusinessSetting::where(['type' => 'features_section_bottom'])->first();

        if($data){
            $new_arr = [];
            foreach(json_decode($data->value) as $item)
            {
                if($request->title != $item->title && $request->subtitle != $item->subtitle){
                    $new_arr[] = $item;
                }else{
                    ImageManager::delete("/banner/" . $item->icon);
                }
            }
            // features_section_bottom
            BusinessSetting::updateOrInsert(['type' => 'features_section_bottom'], [
                'value' => json_encode($new_arr),
            ]);
        }

        return response()->json([
            'status'=>'success'
        ]);
    }

    public function login_url_setup()
    {
        return view('admin-views.business-settings.login-url-setup');
    }

    public function login_url_setup_post(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'url' => 'required',
        ]);

        if($request->type == 'admin_login_url'){
            $employee_login_url = BusinessSetting::where(['type' => 'employee_login_url'])->first()->value ?? '';
            if($employee_login_url != $request->url){
                BusinessSetting::updateOrInsert(['type' => 'admin_login_url'], [
                    'value' => strtolower($request->url),
                ]);
                Toastr::success(translate('Updated_successfully'));
            }else{
                Toastr::error(translate('admin_and_Employee_URL_cannot_be_same'));
            }
        }

        if($request->type == 'employee_login_url'){
            $admin_login_url = BusinessSetting::where(['type' => 'admin_login_url'])->first()->value ?? '';
            if($admin_login_url != $request->url){
                BusinessSetting::updateOrInsert(['type' => 'employee_login_url'], [
                    'value' => strtolower($request->url),
                ]);
                Toastr::success(translate('Updated_successfully'));
            }else{
                Toastr::error(translate('admin_and_Employee_URL_cannot_be_same'));
            }
        }

        return back();
    }

    public function update_seller_settings(Request $request)
    {

        $request->validate([
            'commission' => 'required|min:0',
        ]);

        // sales_commission
        BusinessSetting::updateOrInsert(['type' => 'sales_commission'], [
            'value' => $request->commission ?? 0,
            'updated_at' => now()
        ]);

        // seller_pos
        BusinessSetting::updateOrInsert(['type' => 'seller_pos'], [
            'value' => $request->seller_pos ?? 0,
            'updated_at' => now()
        ]);

        // seller_registration
        BusinessSetting::updateOrInsert(['type' => 'seller_registration'], [
            'value' => $request->seller_registration ?? 0,
            'updated_at' => now()
        ]);

        // minimum_order_amount_by_seller
        DB::table('business_settings')->updateOrInsert(['type' => 'minimum_order_amount_by_seller'], [
            'value' => $request->minimum_order_amount_by_seller ?? 0,
            'updated_at' => now()
        ]);

        // new_product_approval
        DB::table('business_settings')->updateOrInsert(['type' => 'new_product_approval'], [
            'value' => $request->new_product_approval ?? 0,
            'updated_at' => now()
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'product_wise_shipping_cost_approval'], [
            'value' => $request->product_wise_shipping_cost_approval ?? 0,
            'updated_at' => now()
        ]);

        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }

    public function delivery_man_settings()
    {
        $data = BusinessSetting::where(['type' => 'upload_picture_on_delivery'])->first();
        return view('admin-views.business-settings.delivery-man-settings.index', compact('data'));
    }

    public function delivery_man_settings_update(Request $request)
    {
        BusinessSetting::updateOrInsert(['type' => 'upload_picture_on_delivery'], [
            'value' => $request['upload_picture_on_delivery'] ?? 0
        ]);

        Toastr::success(translate('Updated_successfully'));
        return redirect()->back();
    }

    /**delivery section related setion  dynamic*/
    public function company_reliability()
    {
        $company_reliability_data = BusinessSetting::where(['type' => 'company_reliability'])->first();
        return view('admin-views.business-settings.footer-delivery-section.index',compact('company_reliability_data'));
    }
    public function company_reliability_store(Request $request){
        if ($request->has('image'))
        {
            $image = ImageManager::upload('company-reliability/', 'webp', $request->file('image'));
        } else {
            $image = '';
        }
        $data = BusinessSetting::where(['type' => 'company_reliability'])->first();
        foreach (json_decode($data['value'], true) as $key => $data) {

            if ($data['item'] == $request['item']) {
                $item_data = [
                    'item' => $request['item'],
                    'title' => $request->title ?? '',
                    'image' => $image === '' ? $data['image'] : $image,
                    'status' => $request->status ?? 0,
                ];
                $item[] = $item_data;
            } else {
                $item_data = [
                    'item' => $data['item'],
                    'title' => $data['title'],
                    'image' => $data['image'] ,
                    'status' => $data['status'] ??0,
                ];
                $item[] = $item_data;
            }
        }
        BusinessSetting::updateOrInsert(['type' => 'company_reliability'], [
            'value' => json_encode($item),
        ]);
        return redirect()->back();
    }

}
