<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function view(Request $request)
    {
        $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        if (isset($shop) == false) {
            DB::table('shops')->insert([
                'seller_id' => auth('seller')->id(),
                'name' => auth('seller')->user()->f_name,
                'address' => '',
                'contact' => auth('seller')->user()->phone,
                'image' => 'def.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        }

        $minimum_order_amount= Helpers::get_business_settings('minimum_order_amount_status');
        $minimum_order_amount_by_seller=\App\CPU\Helpers::get_business_settings('minimum_order_amount_by_seller');
        $free_delivery_status= Helpers::get_business_settings('free_delivery_status');
        $free_delivery_responsibility= Helpers::get_business_settings('free_delivery_responsibility');

        if ($request->pagetype == 'order_settings' && (($minimum_order_amount && $minimum_order_amount_by_seller) || ($free_delivery_status && $free_delivery_responsibility == 'seller'))) {
            $seller = Seller::find($shop->seller_id);
            return view('seller-views.shop.order-settings', compact('seller'));
        }

        return view('seller-views.shop.shopInfo', compact('shop'));
    }

    public function edit($id)
    {
        $shop = Shop::where(['seller_id' =>  auth('seller')->id()])->first();
        return view('seller-views.shop.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banner'      => 'mimes:png,jpg,jpeg|max:2048',
            'image'       => 'mimes:png,jpg,jpeg|max:2048',
        ], [
            'banner.mimes'   => 'Banner image type jpg, jpeg or png',
            'banner.max'     => 'Banner Maximum size 2MB',
            'image.mimes'    => 'Image type jpg, jpeg or png',
            'image.max'      => 'Image Maximum size 2MB',
        ]);

        $shop = Shop::find($id);
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->contact = $request->contact;
        if ($request->image) {
            $shop->image = ImageManager::update('shop/', $shop->image, 'webp', $request->file('image'));
        }
        if ($request->banner) {
            $shop->banner = ImageManager::update('shop/banner/', $shop->banner, 'webp', $request->file('banner'));
        }
        if ($request->bottom_banner) {
            $shop->bottom_banner = ImageManager::update('shop/banner/', $shop->bottom_banner, 'webp', $request->file('bottom_banner'));
        }
        // offer Banner For Theme Fashion
        if ($request->offer_banner) {
            $shop->offer_banner = ImageManager::update('shop/banner/', $shop->offer_banner, 'webp', $request->file('offer_banner'));
        }
        $shop->save();

        Toastr::info(translate('Shop_updated_successfully'));
        return redirect()->route('seller.shop.view');
    }

    public function vacation_add(Request $request, $id){
        $shop = Shop::find($id);
        $shop->vacation_status = $request->vacation_status == 'on' ? 1 : 0;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        Toastr::success(translate('Vacation_mode_updated_successfully'));
        return redirect()->back();
    }

    public function temporary_close(Request $request){
        $shop = Shop::find($request->id);

        $shop->temporary_close = $request->get('status', 0);
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => $request->status ? translate("temporary_close_active_successfully") : translate("temporary_close_inactive_successfully"),
        ], 200);
    }

    public function order_settings(Request $request)
    {
        if($request->has('minimum_order_amount')){
            Seller::where('id',auth('seller')->id())->update([
                'minimum_order_amount' => BackEndHelper::currency_to_usd($request->minimum_order_amount),
            ]);
        }

        if($request->has('free_delivery_over_amount')){
            Seller::where('id',auth('seller')->id())->update([
                'free_delivery_status' => $request->free_delivery_status == 'on' ? 1:0,
            ]);
            Seller::where('id',auth('seller')->id())->update([
                'free_delivery_over_amount' => BackEndHelper::currency_to_usd($request->free_delivery_over_amount),
            ]);
        }

        Toastr::success(translate('updated_successfully'));
        return back();
    }

}
