<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\DealOfTheDay;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DealController extends Controller
{
    public function __construct(
        private Product $product,
    ){

    }
    public function flash_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $flash_deal = FlashDeal::withCount('products')
                ->where('deal_type', 'flash_deal')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('title', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $flash_deal = FlashDeal::withCount('products')->where('deal_type', 'flash_deal');
        }
        $flash_deal = $flash_deal->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.deal.flash-index', compact('flash_deal', 'search'));
    }

    public function flash_submit(Request $request)
    {
        $flash_deal_id = DB::table('flash_deals')->insertGetId([
            'title' => $request['title'][array_search('en', $request->lang)],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'background_color' => $request['background_color'],
            'text_color' => $request['text_color'],
            'banner' => $request->has('image') ? ImageManager::upload('deal/', 'webp', $request->file('image')) : 'def.webp',
            'slug' => Str::slug($request['title'][array_search('en', $request->lang)]),
            'featured' => $request['featured'] == 1 ? 1 : 0,
            'deal_type' => $request['deal_type'] == 'flash_deal' ? 'flash_deal' : 'feature_deal',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if($flash_deal_id) {
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\FlashDeal',
                            'translationable_id' => $flash_deal_id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('deal_added_successfully'));
        return back();
    }

    public function edit($deal_id)
    {
        $deal = FlashDeal::withoutGlobalScope('translate')->find($deal_id);
        return view('admin-views.deal.flash-update', compact('deal'));
    }

    public function feature_edit($deal_id)
    {
        $deal = FlashDeal::withoutGlobalScope('translate')->find($deal_id);
        return view('admin-views.deal.feature-update', compact('deal'));
    }

    public function update(Request $request, $deal_id)
    {
        $deal = FlashDeal::find($deal_id);
        if ($request->image) {
            $deal['banner'] = ImageManager::update('deal/', $deal['banner'], 'webp', $request->file('image'));
        }

        DB::table('flash_deals')->where(['id' => $deal_id])->update([
            'title' => $request['title'][array_search('en', $request->lang)],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'background_color' => $request['background_color'],
            'text_color' => $request['text_color'],
            'banner' => $deal['banner'],
            'slug' => Str::slug($request['title'][array_search('en', $request->lang)]),
            'featured' => $request['featured'] == 'on' ? 1 : 0,
            'deal_type' => $request['deal_type'] == 'flash_deal' ? 'flash_deal' : 'feature_deal',
            'status' => $deal['status'],
            'updated_at' => now(),
        ]);

        foreach ($request->lang as $index => $key) {
            if ($request->title[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\FlashDeal',
                        'translationable_id' => $deal_id,
                        'locale' => $key,
                        'key' => 'title'],
                    ['value' => $request->title[$index]]
                );
            }
        }

        Toastr::success(translate('deal_updated_successfully'));
        return back();
    }

    public function status_update(Request $request)
    {

        FlashDeal::where(['status' => 1])->where(['deal_type' => 'flash_deal'])->update(['status' => 0]);
        FlashDeal::where(['id' => $request['id']])->update([
            'status' => $request['status'] ?? 0,
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function feature_status(Request $request)
    {

        FlashDeal::where(['status' => 1])->where(['deal_type' => 'feature_deal'])->update(['status' => 0]);
        FlashDeal::where(['id' => $request['id']])->update([
            'status' => $request['status'] ?? 0,
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function featured_update(Request $request)
    {
        FlashDeal::where(['id' => $request['id']])->update([
            'featured' => $request['featured'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }


    // Feature Deal
    public function feature_index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $flash_deals = FlashDeal::where('deal_type', 'feature_deal')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('title', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $flash_deals = FlashDeal::where('deal_type', 'feature_deal');
        }
        $flash_deals = $flash_deals->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.deal.feature-index', compact('flash_deals', 'search'));
    }

    public function add_product($deal_id)
    {
        $flash_deal_products = FlashDealProduct::where('flash_deal_id', $deal_id)->pluck('product_id');

        $products = $this->product->active()->with(['brand','category','seller.shop'])->get();

        $deal_products = Product::whereIn('id', $flash_deal_products)->paginate(Helpers::pagination_limit());

        $deal = FlashDeal::with(['products.product'])->where('id', $deal_id)->first();

        return view('admin-views.deal.add-product', compact('deal', 'products','flash_deal_products','deal_products'));
    }

    public function add_product_submit(Request $request, $deal_id)
    {
        $this->validate($request, [
            'product_id' => 'required'
        ]);
        $flash_deal_products = FlashDealProduct::where('flash_deal_id', $deal_id)->where('product_id',$request['product_id'])->first();

        if(!isset($flash_deal_products))
        {
            DB::table('flash_deal_products')->insertOrIgnore([
                'product_id' => $request['product_id'],
                'flash_deal_id' => $deal_id,
                'discount' => $request['discount'],
                'discount_type' => $request['discount_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Toastr::success(translate('product_added_successfully'));
            return back();
        }else{
            Toastr::info(translate('product_already_added'));
            return back();
        }

    }

    public function delete_product(Request $request)
    {
        FlashDealProduct::where('product_id', $request->id)->delete();

        return response()->json();
    }

    public function deal_of_day(Request $request)
    {
        $products = $this->product->active()->with(['brand','category','seller.shop'])->get();
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $deals = DealOfTheDay::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $deals = new DealOfTheDay();
        }
        $deals = $deals->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.deal.day-index', compact('deals', 'search','products'));
    }

    public function deal_of_day_submit(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ], [
            'product_id.required' => 'Product is required!',
        ]);

        $product = Product::find($request['product_id']);
        $deal_id = DB::table('deal_of_the_days')->insertGetId([
            'title' => $request['title'][array_search('en', $request->lang)],
            'discount' => $product['discount_type'] == 'amount' ? BackEndHelper::currency_to_usd($product['discount']) : $product['discount'],
            'discount_type' => $product['discount_type'],
            'product_id' => $request['product_id'],
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if($deal_id) {
            foreach ($request->lang as $index => $key) {
                if ($request->title[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\DealOfTheDay',
                            'translationable_id' => $deal_id,
                            'locale' => $key,
                            'key' => 'title'],
                        ['value' => $request->title[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('deal_added_successfully'));
        return back();
    }

    public function day_status_update(Request $request)
    {
        DealOfTheDay::where(['status' => 1])->update(['status' => 0]);
        DealOfTheDay::where(['id' => $request['id']])->update([
            'status' => $request['status'] ?? 0,
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function day_edit($deal_id)
    {
        $deal = DealOfTheDay::withoutGlobalScope('translate')->with('product')->where('id',$deal_id)->first();
        $products = $this->product->active()->with(['brand','category','seller.shop'])->orderBy('id','desc')->get();
        return view('admin-views.deal.day-update', compact('deal','products'));
    }

    public function day_update(Request $request, $deal_id)
    {
        $product = Product::find($request['product_id']);
        DB::table('deal_of_the_days')->where(['id' => $deal_id])->update([
            'title' => $request['title'][array_search('en', $request->lang)],
            'discount' => $product['discount_type'] == 'amount' ? BackEndHelper::currency_to_usd($product['discount']) : $product['discount'],
            'discount_type' => $product['discount_type'],
            'product_id' => $request['product_id'],
            'status' => 0,
            'updated_at' => now(),
        ]);

        foreach ($request->lang as $index => $key) {
            if ($request->title[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\DealOfTheDay',
                        'translationable_id' => $deal_id,
                        'locale' => $key,
                        'key' => 'title'],
                    ['value' => $request->title[$index]]
                );
            }
        }

        Toastr::success(translate('deal_updated_successfully'));
        return redirect()->route('admin.deal.day');
    }

    public function day_delete(Request $request)
    {
        DealOfTheDay::destroy($request->id);

        return response()->json();
    }

    /**
     * search product
     */
    public function search_product(Request $request){
        $key = explode(' ', $request['name']);
        $products = $this->product->active()->with(['brand','category','seller.shop'])->where(function ($query) use ($key) {
            foreach ($key as $value) {
                $query->where('name', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'result' => view('admin-views.partials._search-product', compact('products'))->render(),
        ]);
    }
}
