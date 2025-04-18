<?php

namespace App\Http\Controllers\api\v4;

use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Seller;
use App\Model\Shop;
use Illuminate\Http\Request;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;

class SellerController extends Controller
{

    public function __construct(
        private Product $product,
        private Seller $seller,
        private Coupon $coupon,
        private Review $review,
        private OrderDetail $order_details,
    ){}
    public function get_seller_info(Request $request)
    {
        $data=[];
        if($request['seller_id'] == 0) {

        }else{
            $seller = $this->seller::with(['shop'])->where(['id' => $request['seller_id']])->first(['id', 'f_name', 'l_name', 'phone', 'image']);
        }

        $product_ids = $this->product::when($request['seller_id'] == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($request['seller_id'] != 0, function ($query) use ($request) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $request['seller_id']);
            })
            ->active()->pluck('id')->toArray();

        $avg_rating = $this->review::whereIn('product_id', $product_ids)->avg('rating');
        $total_review = $this->review::whereIn('product_id', $product_ids)->count();
        $total_order = $this->order_details::whereIn('product_id', $product_ids)->groupBy('order_id')->count();
        $total_product = $this->product::active()
            ->when($request['seller_id'] == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($request['seller_id'] != 0, function ($query) use ($request) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $request['seller_id']);
            })
            ->count();

        $coupons = $this->coupon::when($request['seller_id'] != 0, function ($query) use($request){
                return $query->with('seller')
                    ->whereIn('seller_id', [0, $request['seller_id']]);
            })
            ->when($request['seller_id'] == 0, function ($query){
                return $query->whereIn('seller_id', [0, null]);
            })
            ->where(['status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->inRandomOrder()->get();

        $customer = $request->user();
        $popular_product = $this->product::active()
            ->with(['wish_list'=>function($query) use($customer){
                return $query->where('customer_id', $customer->id ?? 0);
            }, 'compare_list'=>function($query) use($customer){
                return $query->where('user_id', $customer->id ?? 0);
            }])
            ->whereHas('order_delivered', function($query){
                return $query;
            })->get();

        $popular_product_final = Helpers::product_data_formatting($popular_product, true);

        $data['seller']= $seller;
        $data['avg_rating']= round($avg_rating);
        $data['positive_review']= round(($avg_rating*100)/5);
        $data['total_review']=  $total_review;
        $data['total_order']= $total_order;
        $data['total_product']= $total_product;
        $data['coupons']= $coupons;
        $data['popular_product']= $popular_product_final;

        return response()->json($data, 200);
    }

    public function get_seller_products($seller_id, Request $request)
    {
        $data = ProductManager::get_seller_products($seller_id, $request);
        $data['products'] = Helpers::product_data_formatting($data['products'], true);
        return response()->json($data, 200);
    }

    public function get_seller_all_products($seller_id, Request $request)
    {
        $products = Product::with(['rating','tags'])
            ->where(['user_id' => $seller_id, 'added_by' => $request->added_by])
            ->when($request->search, function ($query) use($request){
                $key = explode(' ', $request->search);
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%");
                }
            })
            ->latest()
            ->paginate($request->limit, ['*'], 'page', $request->offset);


        $products_final = Helpers::product_data_formatting($products->items(), true);

        return [
            'total_size' => $products->total(),
            'limit' => (int)$request->limit,
            'offset' => (int)$request->offset,
            'products' => $products_final
        ];
    }

    public function get_top_sellers()
    {
        $top_sellers = Shop::whereHas('seller',function ($query){return $query->approved();})
            ->withCount('product')
            ->take(15)->get();
        $top_sellers = $top_sellers->map(function($data){
            $data['seller_id'] = (int)$data['seller_id'];
            return $data;
        });
        return response()->json($top_sellers, 200);
    }

    public function get_all_sellers()
    {
        $top_sellers = Shop::whereHas('seller',function ($query){return $query->approved();})->get();
        return response()->json($top_sellers, 200);
    }

    public function get_recent_ordered_shops(Request $request)
    {
        $customer = $request->user();

        $sellers = $this->seller->with(['shop', 'product'])
            ->whereHas('orders', function ($query) use($customer) {
                $query->where(['customer_id' => $customer->id, 'seller_is' => 'seller']);
            })
            ->inRandomOrder()->take(12)->get();

        $sellers->map(function($seller){
            $seller->product->map(function($product){
                $product['average_rating'] = $product->reviews->pluck('rating')->avg();
                $product['rating_count'] = $product->reviews->count();
            });
            $seller->shop['avg_rating'] = $seller->product->pluck('average_rating')->filter()->avg();
            $seller->shop['total_rating'] = $seller->product->pluck('rating_count')->filter()->count();
        });

        return response()->json($sellers, 200);
    }
}
