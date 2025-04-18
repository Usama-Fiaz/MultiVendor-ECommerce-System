<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Illuminate\Http\Request;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;

class SellerController extends Controller
{
    public function __construct(
        private Seller       $seller,
    )
    {
    }

    public function get_seller_info(Request $request)
    {
        $data=[];
        $seller = Seller::with(['shop'])->where(['id' => $request['seller_id']])->first(['id', 'f_name', 'l_name', 'phone', 'image', 'minimum_order_amount']);

        $product_ids = Product::where(['added_by' => 'seller', 'user_id' => $request['seller_id']])->pluck('id')->toArray();

        $total_order = OrderDetail::whereIn('product_id', $product_ids)->groupBy('order_id')->count();
        $total_product = Product::active()->where(['added_by' => 'seller', 'user_id' => $request['seller_id']])->count();

        $rating_status = Review::whereIn('product_id', $product_ids);
        $rating_count = $rating_status->count();
        $avg_rating = $rating_count != 0 ? $rating_status->avg('rating') : 0;
        $rating_percentage = round(($avg_rating * 100) / 5);

        $minimum_order_amount = 0;

        $minimum_order_amount_status = Helpers::get_business_settings('minimum_order_amount_status');
        $minimum_order_amount_by_seller = Helpers::get_business_settings('minimum_order_amount_by_seller');

        if($minimum_order_amount_status && $minimum_order_amount_by_seller)
        {
            $minimum_order_amount = $seller['minimum_order_amount'];
        }
        unset($seller['minimum_order_amount']);

        $data['seller']= $seller;
        $data['avg_rating']= number_format($avg_rating, 2);
        $data['total_review']=  $rating_count;
        $data['total_order']= $total_order;
        $data['total_product']= $total_product;
        $data['minimum_order_amount']= $minimum_order_amount;
        $data['rating_percentage']= $rating_percentage;

        return response()->json($data, 200);
    }

    public function get_seller_products($seller_id, Request $request)
    {
        $data = ProductManager::get_seller_products($seller_id, $request);
        $data['products'] = Helpers::product_data_formatting($data['products'], true) ?? [];
        return response()->json($data, 200);
    }

    public function get_top_sellers()
    {
        $top_sellers = $this->seller->approved()->with(['shop','orders','product.reviews'])
            ->whereHas('orders',function($query){
                $query->where('seller_is','seller');
            })
            ->withCount(['orders','product' => function ($query) {
                $query->active();
            }])->orderBy('orders_count', 'DESC')->take(12)->get();

        $top_sellers?->map(function($seller){
            $seller->product?->map(function($product){
                $product['rating'] = $product?->reviews->pluck('rating')->sum();
                $product['rating_count'] = $product->reviews->count();
            });
            $seller['total_rating'] = $seller?->product->pluck('rating')->sum();
            $seller['rating_count'] = $seller->product->pluck('rating_count')->sum();
            $seller['average_rating'] = $seller['total_rating'] / ($seller['rating_count'] == 0 ? 1 : $seller['rating_count']);
            unset($seller['product']);
            unset($seller['orders']);
        });

        return response()->json($top_sellers, 200);
    }

    public function get_all_sellers()
    {
        $top_sellers = Shop::whereHas('seller',function ($query){return $query->approved();})->get();
        return response()->json($top_sellers, 200);
    }

    public function more_sellers()
    {
        $more_seller = $this->seller->approved()->with(['shop'])
            ->inRandomOrder()
            ->take(10)->get();
        return response()->json($more_seller, 200);
    }

    public function get_seller_best_selling_products($seller_id, Request $request)
    {
        $products = ProductManager::get_seller_best_selling_products($request, $seller_id, $request['limit'], $request['offset']);
        $products['products'] = isset($products['products'][0]) ? Helpers::product_data_formatting($products['products'], true) : [];

        return response()->json($products, 200);
    }

    public function get_sellers_featured_product($seller_id, Request $request){

        $user = Helpers::get_customer($request);
        $featured_products = Product::withCount(['wish_list' => function($query) use($user){
                $query->where('customer_id', $user != 'offline' ? $user->id : '0');
            }])
            ->where(['featured'=>'1'])
            ->when($seller_id == '0', function ($query){
                return $query->where(['added_by' => 'admin'])->active();
            })
            ->when($seller_id != '0', function ($query) use ($seller_id) {
                return $query->where(['added_by' => 'seller', 'user_id'=>$seller_id])->active();
            })
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $featured_products->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'products' => $featured_products ? Helpers::product_data_formatting($featured_products, true) : []
        ];
    }

    public function get_sellers_recommended_products($seller_id, Request $request)
    {
        $products = Product::active()->with(['category'])
                    ->when($seller_id == '0', function ($query){
                        return $query->where(['added_by' => 'admin']);
                    })
                    ->when($seller_id != '0', function ($query) use ($seller_id) {
                        return $query->where(['added_by' => 'seller', 'user_id'=>$seller_id]);
                    })
                    ->withCount('order_delivered')
                    ->withSum('tags', 'visit_count')
                    ->orderBy('order_delivered_count', 'desc')
                    ->orderBy('tags_sum_visit_count', 'desc')
                    ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $products->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'products' => $products ? Helpers::product_data_formatting($products, true) : []
        ];
    }
}
