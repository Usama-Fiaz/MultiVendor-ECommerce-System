<?php

namespace App\Http\Controllers\ThemeFeatures;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;

class AllPagesBannerController extends Controller
{
    // All Pages Banner - New
    public function all_pages_banner(Request $request)
    {
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = BusinessSetting::whereIN('type',['banner_privacy_policy','banner_terms_conditions','banner_refund_policy','banner_return_policy','banner_cancellation_policy'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('type', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $banners = BusinessSetting::whereIN('type',['banner_privacy_policy','banner_terms_conditions','banner_refund_policy','banner_return_policy','banner_cancellation_policy','banner_about_us','banner_faq_page']);
        }

        if (theme_root_path() == 'theme_fashion')
        {
            $banners = $banners->orWhere('type', 'banner_product_list_page');
        }

        $page_banners = $banners->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.theme-features.all-pages-banner.view', compact('page_banners', 'search'));
    }

    public function all_pages_banner_store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'image' => 'required',
        ]);

        $image = ImageManager::upload('banner/', 'webp', $request->file('image'));

        BusinessSetting::insert([
            'type' => $request->type,
            'value' => json_encode([
                'status'=>0,
                'image'=>$image,
            ]),
            'created_at' => now()
        ]);

        Toastr::success(translate('banner_added_successfully'));
        return redirect()->back();
    }

    public function all_pages_banner_edit($id)
    {
        $banner = BusinessSetting::where('id', $id)->first();
        return view('admin-views.theme-features.all-pages-banner.edit', compact('banner'));
    }

    public function all_pages_banner_update(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $banner = BusinessSetting::find($request->id);
        if($banner){
            if ($request->file('image')) {
                ImageManager::delete("/banner/" . json_decode($banner['value'])->image);
                $image = ImageManager::upload('banner/', 'webp', $request->file('image'));
                BusinessSetting::where('id', $request->id)->update([
                    'type' => $request->type,
                    'value' => json_encode([
                        'status'=>0,
                        'image'=>$image,
                    ]),
                ]);
            } else {
                BusinessSetting::where('id', $request->id)->update([
                    'type' => $request->type,
                ]);
            }

            Toastr::success(translate('banner_update_successfully') );
        }

        return redirect()->back();
    }


    public function all_pages_banner_status(Request $request)
    {
        $banner = BusinessSetting::find($request->id);
        if($request->status == 1)
        {
            $others_banners = BusinessSetting::where('id', "!=",$request->id)->where('type',$banner->type)->get();
            foreach ($others_banners as $q) {
                BusinessSetting::where(['id'=>$q->id,'type'=>$q->type])->update([
                    'value' => json_encode([
                        'status'=>0,
                        'image'=>json_decode($q['value'])->image,
                    ]),
                ]);
            }
        }

        BusinessSetting::where('id', $request->id)->update([
            'value' => json_encode([
                'status'=>$request->status,
                'image'=>json_decode($banner['value'])->image,
            ]),
        ]);

        $data = $request->status;
        return response()->json($data);
    }

    public function all_pages_banner_delete(Request $request)
    {
        $banner = BusinessSetting::find($request->id);
        ImageManager::delete("/banner/" . json_decode($banner['value'])->image);
        BusinessSetting::where('id', $request->id)->delete();
        return response()->json();
    }
}
