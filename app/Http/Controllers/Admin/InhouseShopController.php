<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InhouseShopController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $temporary_close = Helpers::get_business_settings('temporary_close');
        $vacation = Helpers::get_business_settings('vacation_add');

        if($request->has('action') && $request->action == 'edit'){
            return view('admin-views.product-settings.inhouse-shop-edit', compact('temporary_close', 'vacation'));
        }else{
            return view('admin-views.product-settings.inhouse-shop', compact('temporary_close', 'vacation'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request['email_verification'] == 1) {
            $request['phone_verification'] = 0;
        } elseif ($request['phone_verification'] == 1) {
            $request['email_verification'] = 0;
        }

        //comapy shop banner
        $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first();
        if ($request->has('shop_banner')) {
            $imgBanner = ImageManager::update('shop/', $imgBanner, 'webp', $request->file('shop_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
                'value' => $imgBanner
            ]);
        }
        $bottom_banner = BusinessSetting::where(['type' => 'bottom_banner'])->first();
        if ($request->has('bottom_banner')) {
            $bottom_banner = ImageManager::update('shop/', $bottom_banner, 'webp', $request->file('bottom_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'bottom_banner'], [
                'value' => $bottom_banner
            ]);
        }
        // === Start Offer Banner For Theme fashion ===
        $offer_banner = BusinessSetting::where(['type' => 'offer_banner'])->first();
        if ($request->has('offer_banner')) {
            $offer_banner = ImageManager::update('shop/', $offer_banner, 'webp', $request->file('offer_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'offer_banner'], [
                'value' => $offer_banner
            ]);
        }

        if ($request->has('minimum_order_amount')) {
            DB::table('business_settings')->updateOrInsert(['type' => 'minimum_order_amount'], [
                'value' => BackEndHelper::currency_to_usd($request['minimum_order_amount']) ?? 0
            ]);
        }

        if ($request->has('free_delivery_over_amount')) {
            DB::table('business_settings')->updateOrInsert(['type' => 'free_delivery_over_amount'], [
                'value' => BackEndHelper::currency_to_usd($request['free_delivery_over_amount']) ?? 0
            ]);
        }

        // End  Offer Banner For Theme Fashion ===
        Toastr::success(translate('Updated_successfully'));
        return back();
    }

    public function temporary_close(Request $request)
    {
        $status = $request->status == 'checked' ? 1 : 0;

        DB::table('business_settings')->updateOrInsert(['type' => 'temporary_close'], [
            'value' => json_encode([
                'status' => $status,
            ]),
        ]);
        return response()->json(['status' => true], 200);
    }

    public function vacation_add(Request $request){
        DB::table('business_settings')->updateOrInsert(['type' => 'vacation_add'], [
            'value' => json_encode([
                'status' => $request->status == 'on' ? 1 : 0,
                'vacation_start_date' => $request->vacation_start_date,
                'vacation_end_date' => $request->vacation_end_date,
                'vacation_note' => $request->vacation_note
            ]),
        ]);

        Toastr::success(translate('vacation_mode_updated_successfully'));
        return redirect()->back();
    }

}
