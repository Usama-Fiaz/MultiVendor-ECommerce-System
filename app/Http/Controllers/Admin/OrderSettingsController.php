<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class OrderSettingsController extends Controller
{
    public function order_settings()
    {
        return view('admin-views.business-settings.order-settings.index');
    }

    public function update_order_settings(Request $request)
    {
        BusinessSetting::updateOrInsert(['type' => 'billing_input_by_customer'], [
            'value' => $request['billing_input_by_customer'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'minimum_order_amount_status'], [
            'value' => $request['minimum_order_amount_status'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'refund_day_limit'], [
            'value' => $request['refund_day_limit'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'order_verification'], [
            'value' => $request['order_verification'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'free_delivery_status'], [
            'value' => $request['free_delivery_status'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'free_delivery_responsibility'], [
            'value' => $request['free_delivery_responsibility']
        ]);

        BusinessSetting::updateOrInsert(['type' => 'free_delivery_over_amount_seller'], [
            'value' => BackEndHelper::currency_to_usd($request['free_delivery_over_amount_seller']) ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'guest_checkout'], [
            'value' => $request['guest_checkout'] ?? 0,
            'updated_at' => now()
        ]);

        Toastr::success(translate('successfully_updated'));
        return back();
    }
}
