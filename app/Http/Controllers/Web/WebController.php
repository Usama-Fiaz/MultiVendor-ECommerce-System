<?php

namespace App\Http\Controllers\Web;

use App\User;
use Carbon\Carbon;
use App\Model\Cart;
use App\Model\Shop;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Model\Admin;
use App\Model\Brand;
use App\Model\Order;
use App\Model\Banner;
use App\Model\Coupon;
use App\Model\Review;
use App\Model\Seller;
use App\Model\Contact;
use App\Model\Product;
use App\Model\Setting;
use App\Model\Category;
use App\Model\Currency;
use App\Model\Wishlist;
use App\CPU\CartManager;
use App\Model\FlashDeal;
use App\Model\HelpTopic;
use App\CPU\OrderManager;
use App\Model\OrderDetail;
use App\Model\Transaction;
use App\Model\Translation;
use Carbon\CarbonInterval;
use App\CPU\ProductManager;
use App\Model\CartShipping;
use App\Model\DealOfTheDay;
use App\Model\ShippingType;
use App\Model\ShopFollower;
use App\Model\Subscription;
use App\Traits\CommonTrait;
use Illuminate\Support\Arr;
use App\CPU\CustomerManager;
use Illuminate\Http\Request;
use App\Model\ProductCompare;
use App\Model\ShippingMethod;
use App\Model\BusinessSetting;
use App\Model\DeliveryZipCode;
use App\Model\ShippingAddress;
use App\Model\FlashDealProduct;
use function App\CPU\translate;
use App\Model\DeliveryCountryCode;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\DB;
use App\Model\OfflinePaymentMethod;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Facade\FlareClient\Http\Response;
use function App\CPU\payment_gateways;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Model\DigitalProductOtpVerification;
use App\Traits\SmsGateway;
use App\CPU\SMS_module;
use Illuminate\Support\Facades\File;

class WebController extends Controller
{
    use CommonTrait;

    public function __construct(
        private OrderDetail $order_details,
        private Product $product,
        private Wishlist $wishlist,
        private Order $order,
        private Category $category,
        private Brand $brand,
        private Seller $seller,
        private ProductCompare $compare,
    ) {

    }

    public function maintenance_mode()
    {
        $maintenance_mode = Helpers::get_business_settings('maintenance_mode') ?? 0;
        if ($maintenance_mode) {
            return view(VIEW_FILE_NAMES['maintenance_mode']);
        }
        return redirect()->route('home');
    }

    public function flash_deals($id)
    {
        $deal = FlashDeal::with(['products.product.reviews', 'products.product' => function($query){
                $query->active();
            }])
            ->where(['id' => $id, 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->first();

            $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
                $query->active();
            })->get()->map(function ($data) {
                return [
                    'discount' => $data->discount,
                    'sellPrice' => isset($data->product->unit_price) ? $data->product->unit_price : 0,
                    'discountedPrice' => isset($data->product->unit_price) ? $data->product->unit_price - $data->discount : 0,

                ];
            })->toArray();


        if (isset($deal)) {
            return view(VIEW_FILE_NAMES['flash_deals'], compact('deal', 'discountPrice'));
        }
        Toastr::warning(translate('not_found'));
        return back();
    }

    public function search_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);
        return view(VIEW_FILE_NAMES['all_stores_page'], compact('sellers'));
    }

    public function all_categories()
    {
        $categories = Category::all();
        return view('web-views.categories', compact('categories'));
    }

    public function categories_by_category($id)
    {
        $category = Category::with(['childes.childes'])->where('id', $id)->first();
        return response()->json([
            'view' => view('web-views.partials._category-list-ajax', compact('category'))->render(),
        ]);
    }

    public function all_brands(Request $request)
    {
        $brand_status = BusinessSetting::where(['type' => 'product_brand'])->value('value');
        session()->put('product_brand', $brand_status);
        if($brand_status == 1){
            $order_by = $request->order_by ?? 'desc';
            $brands = Brand::active()->withCount('brandProducts')->orderBy('name', $order_by)
                                    ->when($request->has('search'), function($query) use($request){
                                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                                })->latest()->paginate(15)->appends(['order_by'=>$order_by, 'search'=>$request->search]);

            return view(VIEW_FILE_NAMES['all_brands'], compact('brands'));
        }else{
            return redirect()->route('home');
        }
    }

    public function all_sellers(Request $request)
    {
        $business_mode=Helpers::get_business_settings('business_mode');
        if(isset($business_mode) && $business_mode=='single')
        {
            Toastr::warning(translate('access_denied!!'));
            return back();
        }
        $sellers = Shop::active()->with(['seller.product'])
        ->withCount(['product'=> function($query){
            $query->active();
        }])
        ->when($request->has('order_by') && ($request->order_by == 'asc' || $request->order_by == 'desc'), function($query) use ($request){
            $query->orderBy('name', $request->order_by);
        })->when($request->has('order_by') && $request->order_by == 'highest-products', function($query) {
            $query->orderBy('product_count', 'desc');
        })->when($request->has('order_by') && $request->order_by == 'lowest-products', function($query) {
            $query->orderBy('product_count', 'asc');
        })->get();
        if(theme_root_path() == 'theme_fashion'){

            $sellers?->map(function ($seller) {
                $rating = 0;
                $count = 0;
                foreach ($seller->seller->product as $item) {
                    foreach ($item->reviews as $review) {
                        if($review->status == 1){
                            $rating += $review->rating;
                            $count++;
                        }
                    }
                }
                $avg_rating = $rating / ($count == 0 ? 1 : $count);
                $rating_count = $count;
                $seller['average_rating'] = $avg_rating;
                $seller['rating_count'] = $rating_count;
                return $seller;
            });
            if($request->has('order_by') && ($request->order_by == 'rating-high-to-low' || $request->order_by == 'rating-low-to-high'))
            {
                if ($request->order_by == 'rating-high-to-low') {
                    $sellers = $sellers->sortByDesc('average_rating');
                } else {
                    $sellers = $sellers->sortBy('rating_count');
                }
            }
        }

        $sellers = $sellers->paginate(12);

        $sellers?->map(function($seller){
            $seller->product?->map(function($product){
                $product['rating'] = $product?->reviews->pluck('rating')->sum();
                $product['review_count'] = $product->reviews->count();
            });
            $seller['total_rating'] = $seller?->product->pluck('rating')->sum();
            $seller['review_count'] = $seller->product->pluck('review_count')->sum();
            $seller['average_rating'] = $seller['total_rating'] / ($seller['review_count'] == 0 ? 1 : $seller['review_count']);
        });

        $order_by = $request->order_by;

        return view(VIEW_FILE_NAMES['all_stores_page'], compact('sellers','order_by'));
    }

    public function seller_profile($id)
    {
        $seller_info = Seller::find($id);
        return view('web-views.seller-profile', compact('seller_info'));
    }

    public function searched_products(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);

        $result = ProductManager::search_products_web($request['name'], $request['category_id'] ?? 'all');
        $products = $result['products'];

        if ($products == null) {
            $result = ProductManager::translated_product_search_web($request['name'], $request['category_id'] ?? 'all');
            $products = $result['products'];
        }

        $sellers = Shop::where(function ($q) use ($request) {
            $q->orWhere('name', 'like', "%{$request['name']}%");
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->with('product', function($query){
            return $query->active()->where('added_by', 'seller');
        })->get();

        $product_ids = [];
        foreach($sellers as $seller){
            if(isset($seller->product) && $seller->product->count() > 0)
            {
                $ids = $seller->product->pluck('id');
                array_push($product_ids, ...$ids);
            }
        }

        $inhouse_product = [];
        $company_name = Helpers::get_business_settings('company_name');

        if (strpos($request['name'], $company_name) !== false) {
            $ids = Product::active()->Where('added_by', 'admin')->pluck('id');
            array_push($product_ids, ...$ids);
        }

        $seller_products = Product::active()->whereIn('id', $product_ids)->get();

        return response()->json([
            'result' => view(VIEW_FILE_NAMES['product_search_result'], compact('products','seller_products'))->render(),
            'seller_products'=>$seller_products->count(),
        ]);
    }

    // global search for theme fashion compare list
    public function searched_products_for_compare_list(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);
        $compare_id = $request['compare_id'];
        $result = ProductManager::search_products_web($request['name']);
        $products = $result['products'];
        if ($products == null) {
            $result = ProductManager::translated_product_search_web($request['name']);
            $products = $result['products'];
        }
        return response()->json([
            'result' => view(VIEW_FILE_NAMES['product_search_result_for_compare_list'], compact('products','compare_id'))->render(),
        ]);

    }

    public function checkout_details(Request $request)
    {

        if (
            (!auth('customer')->check() || Cart::where(['customer_id' => auth('customer')->id()])->count() < 1)
            && (!Helpers::get_business_settings('guest_checkout') || !session()->has('guest_id') || !session('guest_id'))
        ){
            Toastr::error(translate('invalid_access'));
            return redirect('/');
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        $shippingMethod = Helpers::get_business_settings('shipping_method');

        $verify_status = OrderManager::minimum_order_amount_verify($request);

        if($verify_status['status'] == 0){
            Toastr::info(translate('check_Minimum_Order_Amount_Requirment'));
            return redirect()->route('shop-cart');
        }

        $cartItems = Cart::where(['customer_id' => auth('customer')->id()])->withCount(['all_product'=>function($query){
            return $query->where('status', 0);
        }])->get();
        foreach($cartItems as $cart)
        {
            if(isset($cart->all_product_count) && $cart->all_product_count != 0)
            {
                Toastr::info(translate('check_Cart_List_First'));
                return redirect()->route('shop-cart');
            }
        }


        $physical_product_view = false;
        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product_view = true;
                }
            }
        }

        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }
            if($physical_product) {
                foreach ($carts as $cart) {
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart->seller_is == 'admin') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }

                    if ($physical_product && $shipping_type == 'order_wise') {
                        $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                        if (!isset($cart_shipping)) {
                            Toastr::info(translate('select_shipping_method_first'));
                            return redirect('shop-cart');
                        }
                    }
                }
            }
        }

        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        if ($country_restrict_status) {
            $countries = $this->get_delivery_country_array();
        } else {
            $countries = COUNTRIES;
        }

        if ($zip_restrict_status) {
            $zip_codes = DeliveryZipCode::all();
        } else {
            $zip_codes = 0;
        }

        $billing_input_by_customer=Helpers::get_business_settings('billing_input_by_customer');
        $default_location=Helpers::get_business_settings('default_location');

        $user = Helpers::get_customer($request);
        $shipping_addresses = ShippingAddress::where([
            'customer_id' => $user=='offline' ? session('guest_id') : auth('customer')->id(),
            'is_guest'=> $user=='offline' ? 1:'0',
            'is_billing'=>0,
        ])->get();

        $billing_addresses = ShippingAddress::where([
            'customer_id' => $user=='offline' ? session('guest_id') : auth('customer')->id(),
            'is_guest'=> $user=='offline' ? 1:'0',
            'is_billing'=>1,
        ])->get();

        if (count($cart_group_ids) > 0) {
            return view(VIEW_FILE_NAMES['order_shipping'], compact('physical_product_view', 'zip_codes', 'country_restrict_status',
                'zip_restrict_status', 'countries','billing_input_by_customer','default_location','shipping_addresses','billing_addresses'));

        }

        Toastr::info(translate('no_items_in_basket'));
        return redirect('/');
    }

    public function checkout_payment(Request $request)
    {
        if (
            (!auth('customer')->check() || Cart::where(['customer_id' => auth('customer')->id()])->count() < 1)
            && (!Helpers::get_business_settings('guest_checkout') || !session()->has('guest_id') || !session('guest_id'))
        ){
            Toastr::error(translate('invalid_access'));
            return redirect('/');
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        $shippingMethod = Helpers::get_business_settings('shipping_method');


        $verify_status = OrderManager::minimum_order_amount_verify($request);

        if($verify_status['status'] == 0){
            Toastr::info(translate('check_Minimum_Order_Amount_Requirment'));
            return redirect()->route('shop-cart');
        }

        $cartItems = Cart::where(['customer_id' => auth('customer')->id()])->withCount(['all_product'=>function($query){
            return $query->where('status', 0);
        }])->get();
        foreach($cartItems as $cart)
        {
            if(isset($cart->all_product_count) && $cart->all_product_count != 0)
            {
                Toastr::info(translate('check_Cart_List_First'));
                return redirect()->route('shop-cart');
            }
        }

        $physical_products[] = false;
        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();
            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }
            $physical_products[] = $physical_product;
        }
        unset($physical_products[0]);

        $cod_not_show = in_array(false, $physical_products);

        foreach($cart_group_ids as $group_id) {
            $carts = Cart::where('cart_group_id', $group_id)->get();

            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }
            }

            if($physical_product) {
                foreach ($carts as $cart) {
                    if ($shippingMethod == 'inhouse_shipping') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        if ($cart->seller_is == 'admin') {
                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                        } else {
                            $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                            $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                        }
                    }
                    if ($shipping_type == 'order_wise') {
                        $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                        if (!isset($cart_shipping)) {
                            Toastr::info(translate('select_shipping_method_first'));
                            return redirect('shop-cart');
                        }
                    }
                }
            }
        }

        $order = Order::find(session('order_id'));
        $coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $order_wise_shipping_discount = CartManager::order_wise_shipping_discount();
        $get_shipping_cost_saved_for_free_delivery = CartManager::get_shipping_cost_saved_for_free_delivery();
        $amount = CartManager::cart_grand_total() - $coupon_discount - $order_wise_shipping_discount - $get_shipping_cost_saved_for_free_delivery;
        $inr=Currency::where(['symbol'=>'₹'])->first();
        $usd=Currency::where(['code'=>'USD'])->first();
        $myr=Currency::where(['code'=>'MYR'])->first();

        $cash_on_delivery = Helpers::get_business_settings('cash_on_delivery');
        $digital_payment = Helpers::get_business_settings('digital_payment');
        $wallet_status = Helpers::get_business_settings('wallet_status');
        $offline_payment = Helpers::get_business_settings('offline_payment');

        $payment_gateways_list = payment_gateways();

        $offline_payment_methods = OfflinePaymentMethod::where('status', 1)->get();
        $payment_published_status = config('get_payment_publish_status');
        $payment_gateway_published_status = isset($payment_published_status[0]['is_published']) ? $payment_published_status[0]['is_published'] : 0;

        if (session()->has('address_id') && session()->has('billing_address_id') && count($cart_group_ids) > 0) {
            return view(
                VIEW_FILE_NAMES['payment_details'],
                compact(
                    'cod_not_show','order','cash_on_delivery','digital_payment','offline_payment',
                    'wallet_status','coupon_discount','amount','inr','usd','myr','payment_gateway_published_status','payment_gateways_list','offline_payment_methods'
                ));
        }

        Toastr::error(translate('incomplete_info'));
        return back();
    }

    public function checkout_complete(Request $request)
    {
        if($request->payment_method != 'cash_on_delivery'){
            return back()->with('error', 'Something went wrong!');
        }
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $cart_group_ids = CartManager::get_cart_group_ids();
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock){
            Toastr::error(translate('the_following_items_in_your_cart_are_currently_out_of_stock'));
            return redirect()->route('shop-cart');
        }

        $physical_product = false;
        foreach($carts as $cart){
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }
        }

        if($physical_product) {
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => 'cash_on_delivery',
                    'order_status' => 'pending',
                    'payment_status' => 'unpaid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }

            CartManager::cart_clean();


            return view(VIEW_FILE_NAMES['order_complete'], compact('order_ids'));
        }

        return back()->with('error', 'Something went wrong!');
    }

    public function offline_payment_checkout_complete(Request $request)
    {
        if($request->payment_method != 'offline_payment'){
            return back()->with('error', 'Something went wrong!');
        }
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $cart_group_ids = CartManager::get_cart_group_ids();
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock){
            Toastr::error(translate('the_following_items_in_your_cart_are_currently_out_of_stock'));
            return redirect()->route('shop-cart');
        }

        $offline_payment_info = [];
        $method = OfflinePaymentMethod::where(['id'=>$request->method_id,'status'=>1])->first();

        if(isset($method))
        {
            $fields = array_column($method->method_informations, 'customer_input');
            $values = $request->all();

            $offline_payment_info['method_id'] = $request->method_id;
            $offline_payment_info['method_name'] = $method->method_name;
            foreach ($fields as $field) {
                if(key_exists($field, $values)) {
                    $offline_payment_info[$field] = $values[$field];
                }
            }
        }

        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'offline_payment',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_note' => $request->payment_note,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'offline_payment_info' => $offline_payment_info,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean();


        return view(VIEW_FILE_NAMES['order_complete'], compact('order_ids'));
    }
    public function checkout_complete_wallet(Request $request = null)
    {
        $cartTotal = CartManager::cart_grand_total();
        $user = Helpers::get_customer($request);
        if( $cartTotal > $user->wallet_balance)
        {
            Toastr::warning(translate('inefficient balance in your wallet to pay for this order!!'));
            return back();
        }else{
            $unique_id = OrderManager::gen_unique_id();
            $cart_group_ids = CartManager::get_cart_group_ids();
            $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

            $product_stock = CartManager::product_stock_check($carts);
            if(!$product_stock){
                Toastr::error(translate('the_following_items_in_your_cart_are_currently_out_of_stock'));
                return redirect()->route('shop-cart');
            }

            $order_ids = [];
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => 'pay_by_wallet',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }

            CustomerManager::create_wallet_transaction($user->id, Convert::default($cartTotal), 'order_place','order payment');
            CartManager::cart_clean();
        }

        if (session()->has('payment_mode') && session('payment_mode') == 'app') {
            return redirect()->route('payment-success');
        }
        return view(VIEW_FILE_NAMES['order_complete'], compact('order_ids'));
    }

    public function order_placed()
    {
        return view(VIEW_FILE_NAMES['order_complete']);
    }

    public function shop_cart(Request $request)
    {
        $top_rated_shops = [];
        $new_sellers = [] ;
        $current_date = date('Y-m-d H:i:s');
        if(theme_root_path()==="theme_fashion"){
             /*
            * Top rated store and new seller
            */
            $seller_list = $this->seller->approved()->with(['shop','product.reviews'])
                ->withCount(['product' => function ($query) {
                    $query->active();
                }])->get();
                $seller_list?->map(function ($seller) {
                    $rating = 0;
                    $count = 0;
                    foreach ($seller->product as $item) {
                        foreach ($item->reviews as $review) {
                            $rating += $review->rating;
                            $count++;
                        }
                    }
                    $avg_rating = $rating / ($count == 0 ? 1 : $count);
                    $rating_count = $count;
                    $seller['average_rating'] = $avg_rating;
                    $seller['rating_count'] = $rating_count;

                    $product_count = $seller->product->count();
                    $random_product = Arr::random($seller->product->toArray(), $product_count < 3 ? $product_count : 3);
                    $seller['product'] = $random_product;
                    return $seller;
                });
            $new_sellers     =  $seller_list->sortByDesc('id')->take(12);
            $top_rated_shops =  $seller_list->where('rating_count', '!=', 0)->sortByDesc('average_rating')->take(12);

            /*
            * end Top Rated store and new seller
            */
        }
        return view(VIEW_FILE_NAMES['cart_list'],compact('top_rated_shops','new_sellers','current_date','request'));
    }

    //ajax filter (category based)
    public function seller_shop_product(Request $request, $id)
    {
        $products = Product::active()->with('shop')->where(['added_by' => 'seller'])
        ->where('user_id', $id)
        ->whereJsonContains('category_ids', [
            ['id' => strval($request->category_id)],
            ])
            ->paginate(12);
        $shop = Shop::where('seller_id', $id)->first();
        if ($request['sort_by'] == null) {
            $request['sort_by'] = 'latest';
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products'))->render(),
            ], 200);

        }

        return view(VIEW_FILE_NAMES['shop_view_page'], compact('products', 'shop'))->with('seller_id', $id);
    }

    public function quick_view(Request $request)
    {
        $product = ProductManager::get_product($request->product_id);
        $order_details = OrderDetail::where('product_id', $product->id)->get();
        $wishlists = Wishlist::where('product_id', $product->id)->get();
        $wishlist_status = Wishlist::where(['product_id'=>$product->id, 'customer_id'=>auth('customer')->id()])->count();
        $countOrder = count($order_details);
        $countWishlist = count($wishlists);
        $relatedProducts = Product::with(['reviews'])->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
        $current_date = date('Y-m-d');
        $seller_vacation_start_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_start_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_start_date)) : null;
        $seller_vacation_end_date = ($product->added_by == 'seller' && isset($product->seller->shop->vacation_end_date)) ? date('Y-m-d', strtotime($product->seller->shop->vacation_end_date)) : null;
        $seller_temporary_close = ($product->added_by == 'seller' && isset($product->seller->shop->temporary_close)) ? $product->seller->shop->temporary_close : false;

        $temporary_close = Helpers::get_business_settings('temporary_close');
        $inhouse_vacation = Helpers::get_business_settings('vacation_add');
        $inhouse_vacation_start_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_start_date'] : null;
        $inhouse_vacation_end_date = $product->added_by == 'admin' ? $inhouse_vacation['vacation_end_date'] : null;
        $inhouse_vacation_status = $product->added_by == 'admin' ? $inhouse_vacation['status'] : false;
        $inhouse_temporary_close = $product->added_by == 'admin' ? $temporary_close['status'] : false;

        // Newly Added From Blade
        $overallRating = ProductManager::get_overall_rating($product->reviews);
        $rating = ProductManager::get_rating($product->reviews);
        $reviews_of_product = Review::where('product_id',$product->id)->latest()->paginate(2);
        $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
        $more_product_from_seller = Product::active()->where('added_by',$product->added_by)->where('id','!=',$product->id)->where('user_id',$product->user_id)->latest()->take(5)->get();

        return response()->json([
            'success' => 1,
            'product' => $product,
            'view' => view(VIEW_FILE_NAMES['product_quick_view_partials'], compact('product', 'countWishlist', 'countOrder',
                'relatedProducts', 'current_date', 'seller_vacation_start_date', 'seller_vacation_end_date', 'seller_temporary_close',
                'inhouse_vacation_start_date', 'inhouse_vacation_end_date','inhouse_vacation_status', 'inhouse_temporary_close','wishlist_status','overallRating','rating'))->render(),
        ]);
    }

    public function discounted_products(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews']);

        if ($request['data_from'] == 'category') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['id']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if ($request['data_from'] == 'latest') {
            $query = $porduct_data->orderBy('id', 'DESC');
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy("count", 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'featured') {
            $query = Product::with(['reviews'])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $query = $porduct_data->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        if ($request['data_from'] == 'discounted_products') {
            $query = Product::with(['reviews'])->active()->where('discount', '!=', 0);
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            return "low";
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query;
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(5)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view(VIEW_FILE_NAMES['products__ajax_partials'], compact('products'))->render()
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $data['brand_name'] = Category::find((int)$request['id'])->name;
        }
        if ($request['data_from'] == 'brand') {
            $data['brand_name'] = Brand::active()->find((int)$request['id'])->name;
        }

        return view(VIEW_FILE_NAMES['products_view_page'], compact('products', 'data'), $data);

    }

    public function viewWishlist(Request $request)
    {
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;

        $wishlists = Wishlist::with([
            'product_full_info',
            'product_full_info.compare_list'=>function($query){
                return $query->where('user_id', auth('customer')->id() ?? 0);
            }
        ])
        ->whereHas('wishlistProduct', function ($q) use ($request) {
            $q->when($request['search'],function ($query) use ($request) {
                $query->where('name', 'like', "%{$request['search']}%")
                    ->orWhereHas('category', function ($qq) use ($request) {
                        $qq->where('name', 'like', "%{$request['search']}%");
                    });
            });
        })
        ->where('customer_id', auth('customer')->id())->paginate(15);

        return view(VIEW_FILE_NAMES['account_wishlist'], compact('wishlists', 'brand_setting'));
    }

    public function storeWishlist(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $wishlist = Wishlist::where('customer_id', auth('customer')->id())->where('product_id', $request->product_id)->first();
                if ($wishlist) {
                    $wishlist->delete();

                    $countWishlist = Wishlist::whereHas('wishlistProduct',function($q){
                        return $q;
                    })->where('customer_id', auth('customer')->id())->count();
                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json([
                        'error' => translate("wishlist_Removed"),
                        'value' => 2,
                        'count' => $countWishlist,
                        'product_count' => $product_count
                    ]);

                } else {
                    $wishlist = new Wishlist;
                    $wishlist->customer_id = auth('customer')->id();
                    $wishlist->product_id = $request->product_id;
                    $wishlist->save();

                    $countWishlist = Wishlist::whereHas('wishlistProduct',function($q){
                        return $q;
                    })->where('customer_id', auth('customer')->id())->count();

                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json([
                        'success' => translate("Product has been added to wishlist"),
                        'value' => 1, 'count' => $countWishlist,
                        'id' => $request->product_id,
                        'product_count' => $product_count
                    ]);
                }

            } else {
                return response()->json(['error' => translate('login_first'), 'value' => 0]);
            }
        }
    }

    public function deleteWishlist(Request $request)
    {
        $this->wishlist->where(['product_id' => $request['id'], 'customer_id' => auth('customer')->id()])->delete();
        $data = translate('product_has_been_remove_from_wishlist').'!';
        $wishlists = $this->wishlist->where('customer_id', auth('customer')->id())->paginate(15);
        $brand_setting = BusinessSetting::where('type', 'product_brand')->first()->value;
        session()->put('wish_list', $this->wishlist->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return response()->json([
            'success' => $data,
            'count' => count($wishlists),
            'id' => $request->id,
            'wishlist' => view(VIEW_FILE_NAMES['account_wishlist_partials'], compact('wishlists', 'brand_setting'))->render(),
        ]);
    }

    public function delete_wishlist_all(){
        $this->wishlist->where('customer_id', auth('customer')->id())->delete();
        session()->put('wish_list', $this->wishlist->where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());
        return redirect()->back();
    }

    //order Details

    public function orderdetails()
    {
        return view('web-views.orderdetails');
    }

    public function chat_for_product(Request $request)
    {
        return $request->all();
    }

    public function supportChat()
    {
        return view('web-views.users-profile.profile.supportTicketChat');
    }

    public function error()
    {
        return view('web-views.404-error-page');
    }

    public function contact_store(Request $request)
    {
        //recaptcha validation
        $recaptcha = Helpers::get_business_settings('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {

            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                            }
                        },
                    ],
                ]);

            } catch (\Exception $exception) {
                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
            }
        } else {
            if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
                Session::forget('default_captcha_code');
                return back()->withErrors(\App\CPU\translate('Captcha Failed'))->withInput($request->input());
            }
        }

        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'email' => 'email',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',
        ]);
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        Toastr::success(translate('Your Message Send Successfully'));
        return back();
    }

    public function captcha($tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if(Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    public function order_note(Request $request)
    {
        if ($request->has('order_note')) {
            session::put('order_note', $request->order_note);
        }
        return response()->json();
    }

    public function digital_product_download($id, Request $request)
    {
        $order_details_data = OrderDetail::with('order.customer')->find($id);
        if($order_details_data) {
            if($order_details_data->order->payment_status !== "paid") {
                return response()->json([
                    'status' => 0,
                    'message' => translate('Payment_must_be_confirmed_first').' !!',
                ]);
            };

            if($order_details_data->order->is_guest) {
                $customer_email = $order_details_data->order->shipping_address_data ? json_decode($order_details_data->order->shipping_address_data)->email : ($order_details_data->order->billing_address_data ? json_decode($order_details_data->order->billing_address_data)->email : '');

                $customer_phone = $order_details_data->order->shipping_address_data ? json_decode($order_details_data->order->shipping_address_data)->phone : ($order_details_data->order->billing_address_data ? json_decode($order_details_data->order->billing_address_data)->phone : '');

                $customer_data = ['email' =>$customer_email, 'phone' =>$customer_phone];
                return self::digital_product_download_process($order_details_data, $customer_data);
            }else {
                if(auth('customer')->check() && auth('customer')->user()->id == $order_details_data->order->customer->id) {
                    $file_name = '';
                    if( $order_details_data->product->digital_product_type == 'ready_product' && $order_details_data->product->digital_file_ready) {
                        $file_path = asset('storage/app/public/product/digital-product/' .$order_details_data->product->digital_file_ready);
                        $file_name = $order_details_data->product->digital_file_ready;
                    }else{
                        $file_path = asset('storage/app/public/product/digital-product/' . $order_details_data->digital_file_after_sell);
                        $file_name = $order_details_data->digital_file_after_sell;
                    }

                    if(File::exists(base_path('storage/app/public/product/digital-product/'. $file_name))) {
                        return response()->json([
                            'status' => 1,
                            'file_path' => $file_path,
                            'file_name' => $file_name,
                        ]);
                    }else {
                        return response()->json([
                            'status' => 0,
                            'message' => translate('file_not_found'),
                        ]);
                    }
                }else {
                    $customer_data = ['email' =>$order_details_data->order->customer->email ?? '', 'phone' =>$order_details_data->order->customer->phone ?? ''];
                    return self::digital_product_download_process($order_details_data, $customer_data);
                }
            }
        }else{
            return response()->json([
                'status' => 0,
                'message' => translate('order_Not_Found').' !',
            ]);
        }
    }

    public function digital_product_download_otp_verify(Request $request)
    {
        $verification = DigitalProductOtpVerification::where(['token' => $request->otp, 'order_details_id' => $request->order_details_id])->first();
        $order_details_data = OrderDetail::with('order.customer')->find($request->order_details_id);

        if($verification) {
            if($order_details_data){
                $file_name = '';
                if( $order_details_data->product->digital_product_type == 'ready_product' && $order_details_data->product->digital_file_ready) {
                    $file_path = asset('storage/app/public/product/digital-product/' .$order_details_data->product->digital_file_ready);
                    $file_name = $order_details_data->product->digital_file_ready;
                }else{
                    $file_path = asset('storage/app/public/product/digital-product/' . $order_details_data->digital_file_after_sell);
                    $file_name = $order_details_data->digital_file_after_sell;
                }
            }

            DigitalProductOtpVerification::where(['token' => $request->otp, 'order_details_id' => $request->order_details_id])->delete();

            if(File::exists(base_path('storage/app/public/product/digital-product/'. $file_name))) {
                return response()->json([
                    'status' => 1,
                    'file_path' => $file_path ?? '',
                    'file_name' => $file_name ?? '',
                    'message' => translate('successfully_verified'),
                ]);
            }else {
                return response()->json([
                    'status' => 0,
                    'message' => translate('file_not_found'),
                ]);
            }
        }else{
            return response()->json([
                'status' => 0,
                'message' => translate('the_OTP_is_incorrect').' !',
            ]);
        }
    }

    public function digital_product_download_otp_reset(Request $request)
    {
        $token_info = DigitalProductOtpVerification::where(['order_details_id'=> $request->order_details_id])->first();
        $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 1; //minute
        if(isset($token_info) &&  Carbon::parse($token_info->created_at)->diffInSeconds() < $otp_interval_time){
            $time_count = $otp_interval_time - Carbon::parse($token_info->created_at)->diffInSeconds();

            return response()->json([
                'status'=>0,
                'time_count'=> CarbonInterval::seconds($time_count)->cascade()->forHumans(),
                'message'=> 'Please try again after '. CarbonInterval::seconds($time_count)->cascade()->forHumans()
            ]);
        }else {
            $guest_email = '';
            $guest_phone = '';
            $token = rand(1000, 9999);

            $order_details_data = OrderDetail::with('order.customer')->find($request->order_details_id);

            try {
                if($order_details_data->order->shipping_address_data){
                    $guest_email = $order_details_data->order->shipping_address_data ? json_decode($order_details_data->order->shipping_address_data)->email : null;
                    $guest_phone = $order_details_data->order->shipping_address_data ? json_decode($order_details_data->order->shipping_address_data)->phone : null;
                }else{
                    $guest_email = $order_details_data->order->billing_address_data ? json_decode($order_details_data->order->billing_address_data)->email : null;
                    $guest_phone = $order_details_data->order->billing_address_data ? json_decode($order_details_data->order->billing_address_data)->phone : null;
                }
            } catch (\Throwable $th) {

            }

            $verify_data = [
                'order_details_id' => $order_details_data->id,
                'token' => $token,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DigitalProductOtpVerification::updateOrInsert(['identity' => $guest_email, 'order_details_id' => $order_details_data->id], $verify_data);
            DigitalProductOtpVerification::updateOrInsert(['identity' => $guest_phone, 'order_details_id' => $order_details_data->id], $verify_data);

            $emailServices_smtp = Helpers::get_business_settings('mail_config');
            if ($emailServices_smtp['status'] == 0) {
                $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
            }
            if ($emailServices_smtp['status'] == 1) {
                try{
                    Mail::to($guest_email)->send(new \App\Mail\DigitalProductOtpVerificationMail($token));
                    $mail_status = 1;
                } catch (\Exception $exception) {
                    $mail_status = 0;
                }
            } else {
                $mail_status = 0;
            }

            $published_status = 0;
            $payment_published_status = config('get_payment_publish_status');
            if (isset($payment_published_status[0]['is_published'])) {
                $published_status = $payment_published_status[0]['is_published'];
            }

            $response = '';
            if($published_status == 1){
                $response = SmsGateway::send($guest_phone, $token);
            }else{
                $response = SMS_module::send($guest_phone, $token);
            }

            $sms_status = $response == "not_found" ? 0 : 1;

            return response()->json([
                'mail_status'=> $mail_status,
                'sms_status'=> $sms_status,
                'status' => ($mail_status || $sms_status) ? 1 : 0,
                'new_time' => $otp_interval_time,
                'message'=>'OTP sent successfully',
            ]);

        }
    }

    public function digital_product_download_process($order_details_data, $customer)
    {
        $status = 2;
        $emailServices_smtp = Helpers::get_business_settings('mail_config');
        if ($emailServices_smtp['status'] == 0) {
            $emailServices_smtp = Helpers::get_business_settings('mail_config_sendgrid');
        }

        $payment_published_status = config('get_payment_publish_status');
        $published_status = isset($payment_published_status[0]['is_published']) ? $payment_published_status[0]['is_published'] : 0;

        if($published_status == 1){
            $sms_config_status = Setting::where(['settings_type'=>'sms_config', 'is_active'=>1])->count() > 0 ? 1:0;
        }else{
            $sms_config_status = Setting::where(['settings_type'=>'sms_config', 'is_active'=>1])->whereIn('key_name', Helpers::default_sms_gateways())->count() > 0 ? 1:0;
        }

        if($emailServices_smtp['status'] || $sms_config_status)
        {
            $token = rand(1000, 9999);
            if($customer['email'] == '' && $customer['phone'] == ''){
                return response()->json([
                    'status' => $status,
                    'file_path' => '',
                    'view'=> view(VIEW_FILE_NAMES['digital_product_order_otp_verify_failed'])->render(),
                ]);
            }

            $verification_data = DigitalProductOtpVerification::where('identity', $customer['email'])->orWhere('identity', $customer['phone'])->where('order_details_id', $order_details_data->id)->latest()->first();
            $otp_interval_time = Helpers::get_business_settings('otp_resend_time') ?? 1; //second

            if(isset($verification_data) &&  Carbon::parse($verification_data->created_at)->diffInSeconds() < $otp_interval_time){
                $time_count = $otp_interval_time - Carbon::parse($verification_data->created_at)->diffInSeconds();
                return response()->json([
                    'status' => $status,
                    'file_path' => '',
                    'view'=> view(VIEW_FILE_NAMES['digital_product_order_otp_verify'], ['orderDetailID'=>$order_details_data->id, 'time_count'=>$time_count])->render(),
                ]);
            }else {
                $verify_data = [
                    'order_details_id' => $order_details_data->id,
                    'token' => $token,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DigitalProductOtpVerification::updateOrInsert(['identity' => $customer['email'], 'order_details_id' => $order_details_data->id], $verify_data);
                DigitalProductOtpVerification::updateOrInsert(['identity' => $customer['phone'], 'order_details_id' => $order_details_data->id], $verify_data);

                $reset_data = DigitalProductOtpVerification::where('identity', $customer['email'])->orWhere('identity', $customer['phone'])->where('order_details_id', $order_details_data->id)->latest()->first();
                $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;
                $token_time = Carbon::parse($reset_data->created_at);
                $convert_time = $token_time->addSeconds($otp_resend_time);
                $time_count = $convert_time > Carbon::now() ? Carbon::now()->diffInSeconds($convert_time) : 0;
                $mail_status = 0;

                if ($emailServices_smtp['status'] == 1) {
                    try{
                        Mail::to($customer['email'])->send(new \App\Mail\DigitalProductOtpVerificationMail($token));
                        $mail_status = 1;
                    } catch (\Exception $exception) {
                    }
                }

                $response = '';
                if($sms_config_status && $published_status == 1){
                    $response = SmsGateway::send($customer['phone'], $token);
                }else if($sms_config_status && $published_status == 0){
                    $response = SMS_module::send($customer['phone'], $token);
                }

                $sms_status = ($response == "not_found" || $sms_config_status == 0) ? 0 : 1;
                if($mail_status || $sms_status){
                    return response()->json([
                        'status' => $status,
                        'file_path' => '',
                        'view'=> view(VIEW_FILE_NAMES['digital_product_order_otp_verify'], ['orderDetailID'=>$order_details_data->id, 'time_count'=>$time_count])->render(),
                    ]);
                }else{
                    return response()->json([
                        'status' => $status,
                        'file_path' => '',
                        'view'=> view(VIEW_FILE_NAMES['digital_product_order_otp_verify_failed'])->render(),
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => $status,
                'file_path' => '',
                'view'=> view(VIEW_FILE_NAMES['digital_product_order_otp_verify_failed'])->render(),
            ]);
        }
    }


    public function subscription(Request $request)
    {
        $subscription_email = Subscription::where('email',$request->subscription_email)->first();
        if(isset($subscription_email))
        {
            Toastr::info(translate('You already subscribed this site!!'));
            return back();
        }else{
            $new_subcription = new Subscription;
            $new_subcription->email = $request->subscription_email;
            $new_subcription->save();

            Toastr::success(translate('Your subscription successfully done!!'));
            return back();

        }

    }
    public function review_list_product(Request $request)
    {
        $reviews_of_product =Review::where('product_id',$request->product_id)->latest()->paginate(2, ['*'], 'page', $request->offset+1);
        $checkReviews =Review::where('product_id',$request->product_id)->latest()->paginate(2, ['*'], 'page', ($request->offset+1));
        return response()->json([
            'productReview'=> view(VIEW_FILE_NAMES['product_reviews_partials'],compact('reviews_of_product'))->render(),
            'not_empty'=>$reviews_of_product->count(),
            'checkReviews'=>$checkReviews->count(),
        ]);
    }
    public function review_list_shop(Request $request)
    {
        $seller_id = 0;
        if($request->shop_id != 0)
        {
            $seller_id = Shop::where('id',$request->shop_id)->first()->seller_id;
        }
        $product_ids = Product::when($request->shop_id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($request->shop_id != 0, function ($query) use ($seller_id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $seller_id);
            })
            ->pluck('id')->toArray();

        $reviews_of_product =Review::active()->whereIn('product_id',$product_ids)->latest()->paginate(4, ['*'], 'page', $request->offset+1);
        $checkReviews =Review::active()->whereIn('product_id',$product_ids)->latest()->paginate(4, ['*'], 'page', ($request->offset+1));

        return response()->json([
            'productReview'=> view(VIEW_FILE_NAMES['product_reviews_partials'],compact('reviews_of_product'))->render(),
            'not_empty'=>$reviews_of_product->count(),
            'checkReviews'=>$checkReviews->count(),
        ]);
    }
    public function product_view_style(Request $request)
    {
        Session::put('product_view_style', $request->value);
        return response()->json([
            'message'=>translate('View_style_updated')."!",
        ]);
    }


    public function pay_offline_method_list(Request $request)
    {

        $method = OfflinePaymentMethod::where(['id'=>$request->method_id,'status'=>1])->first();

        return response()->json([
            'methodHtml'=> view(VIEW_FILE_NAMES['pay_offline_method_list_partials'],compact('method'))->render(),
        ]);
    }

}
