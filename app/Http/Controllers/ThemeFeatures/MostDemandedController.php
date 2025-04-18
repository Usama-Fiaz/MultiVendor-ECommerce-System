<?php

namespace App\Http\Controllers\ThemeFeatures;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\MostDemanded;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;


class MostDemandedController extends Controller
{
    //
    public function __construct(
        public Product $product,
        public MostDemanded $most_demanded,
    ){

    }
    public function index(Request $request){
        if(theme_root_path() !="theme_fashion"){
            return redirect('admin/dashboard');
        }
        $products = $this->product->orderBy('name', 'asc')->get();
        $query_param = [];
        if ($request->has('search')) {
            $query_param = $request->search;
        }
        $most_demanded_products = $this->most_demanded->with('product')->when($request->has('search'), function ($query) use ($request) {
                return $query->whereHas('product', function ($query) use ($request) {
                    return $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            })->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.theme-features.most-demanded.view',compact('products','most_demanded_products'));
    }
    public function store (Request $request){
        $validator = Validator::make($request->all(),[
            'product_id' => 'required',
            'image'      => 'required',
        ],[
            'product_id.required' => 'The product field is required'
        ]);
        if ($validator->fails()){
            return back()->withErrors($validator->errors())->withInput();
        }
        DB::table('most_demandeds')->insertGetId([
            'product_id'=>$request->product_id,
            'banner' => $request->has('image') ? ImageManager::upload('most-demanded/', 'webp', $request->file('image')) : 'def.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Toastr::success(translate('most_demanded_product_add_successfully'));
        return back();
    }
    public function status_update(Request $request)
    {

        $this->most_demanded->where(['status' => 1])->update(['status' => 0]);
        $this->most_demanded->where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function edit($id){
        if(theme_root_path() !="theme_fashion"){
            return redirect('admin/dashboard');
        }
        $products = $this->product->orderBy('name', 'asc')->get();
        $most_demanded_product = $this->most_demanded->find($id);
        return view('admin-views.theme-features.most-demanded.edit', compact('products','most_demanded_product'));
    }
    public function update(Request $request,$id){
        $most_demanded_product = $this->most_demanded->find($id);
        if ($request->image) {
            $most_demanded_product['banner'] = ImageManager::update('most-demanded/', $most_demanded_product['banner'], 'webp', $request->file('image'));

        }
        $most_demanded_product->update([
            'banner' => $most_demanded_product['banner'],
            'product_id' => $request['product_id'],
        ]);

        Toastr::success(translate('most_demanded_product_update_successfully'));
        return redirect('admin/most-demanded');

    }
    public function delete(Request $request)
    {
        $this->most_demanded->where('id', $request->id)->delete();
        return response()->json();
    }
}
