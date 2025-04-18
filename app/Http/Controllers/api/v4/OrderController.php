<?php

namespace App\Http\Controllers\api\v4;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Cart;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Traits\CommonTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\CPU\ImageManager;
use App\Model\DeliveryMan;
use App\CPU\CustomerManager;
use App\Model\OfflinePaymentMethod;

class OrderController extends Controller
{
    use CommonTrait;
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request['order_id']), 200);
    }
    public function order_cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::where(['id' => $request->order_id])->first();

        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $request->order_id])->update([
                'order_status' => 'canceled'
            ]);

            return response()->json(translate('order_canceled_successfully'), 200);
        }

        return response()->json(translate('status_not_changable_now'), 302);
    }

    public function place_order(Request $request)
    {
        $user = Helpers::get_customer($request);
        $encrypted_data = json_decode(base64_decode($request->encrypted_data), true);
        $request = $request->merge($encrypted_data);

        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock){
            return response()->json(['message' => 'The following items in your cart are currently out of stock'], 403);
        }

        $physical_product = false;
        foreach($carts as $cart){
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }
        }

        if($physical_product) {
            $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
            $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

            if ($request->has('billing_address_id')) {
                if ($user == 'offline') {
                    $shipping_address = ShippingAddress::where(['customer_id' => $request->guest_id,'is_guest'=>1, 'id' => $request->input('billing_address_id')])->first();
                }else{
                    $shipping_address = ShippingAddress::where(['customer_id' => $user->id,'is_guest'=>'0', 'id' => $request->input('billing_address_id')])->first();
                }

                if (!$shipping_address) {
                    return response()->json(['status'=>'fail', 'message' => translate('address_not_found')], 403);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                    return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_country')], 403);

                } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                    return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
                }
            }
        }

        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
            ];
            $order_id = OrderManager::generate_order($data);

            $order = Order::find($order_id);
            $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
            $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
            $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
            $order->save();

            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(['status'=>'success', 'message' => translate('order_placed_successfully')], 200);
    }

    public function place_order_by_offline_payment(Request $request)
    {
        $user = Helpers::get_customer($request);

        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock){
            return response()->json(['message' => 'The following items in your cart are currently out of stock'], 403);
        }

        $physical_product = false;
        foreach($carts as $cart){
            if($cart->product_type == 'physical'){
                $physical_product = true;
            }
        }

        if($physical_product) {
            $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
            $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

            if ($request->has('billing_address_id')) {
                if ($user == 'offline') {
                    $shipping_address = ShippingAddress::where(['customer_id' => $request->guest_id,'is_guest'=>1, 'id' => $request->input('billing_address_id')])->first();
                }else{
                    $shipping_address = ShippingAddress::where(['customer_id' => $user->id,'is_guest'=>'0', 'id' => $request->input('billing_address_id')])->first();
                }

                if (!$shipping_address) {
                    return response()->json(['status'=>'fail', 'message' => translate('address_not_found')], 403);
                }
                elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                    return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_country')], 403);

                } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                    return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
                }
            }
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

        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach ($cart_group_ids as $group_id) {
            $data = [
                'payment_method' => 'offline_payment',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_note' => $request->payment_note,
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
                'offline_payment_info' => $offline_payment_info,
            ];
            $order_id = OrderManager::generate_order($data);

            $order = Order::find($order_id);
            $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
            $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
            $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
            $order->save();

            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(['status'=>'success', 'message' => translate('order_placed_successfully')], 200);
    }

    public function place_order_by_wallet(Request $request)
    {
        $encrypted_data = json_decode(base64_decode($request->encrypted_data), true);
        $request = $request->merge($encrypted_data);

        $cart_group_ids = CartManager::get_cart_group_ids($request);
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();

        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock){
            return response()->json(['message' => 'The following items in your cart are currently out of stock'], 403);
        }

        $cartTotal = 0;
        foreach($cart_group_ids as $cart_group_id){
            $cartTotal += CartManager::cart_grand_total($cart_group_id);
        }
        $user = Helpers::get_customer($request);
        if( $cartTotal > $user->wallet_balance)
        {
            return response()->json('inefficient balance in your wallet to pay for this order', 403);
        }else{
            $physical_product = false;
            foreach($carts as $cart){
                if($cart->product_type == 'physical'){
                    $physical_product = true;
                }
            }

            if($physical_product) {
                $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
                $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');

                if ($request->has('billing_address_id')) {
                    $shipping_address = ShippingAddress::where(['customer_id' => $user->id, 'id' => $request->input('billing_address_id')])->first();

                    if (!$shipping_address) {
                        return response()->json(['status'=>'fail', 'message' => translate('address_not_found')], 200);
                    }
                    elseif ($country_restrict_status && !self::delivery_country_exist_check($shipping_address->country)) {
                        return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_country')], 403);

                    } elseif ($zip_restrict_status && !self::delivery_zipcode_exist_check($shipping_address->zip)) {
                        return response()->json(['status'=>'fail', 'message' => translate('Delivery_unavailable_for_this_zip_code_area')], 403);
                    }
                }
            }

            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach ($cart_group_ids as $group_id) {
                $data = [
                    'payment_method' => 'pay_by_wallet',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id,
                    'request' => $request,
                ];
                $order_id = OrderManager::generate_order($data);

                $order = Order::find($order_id);
                $order->billing_address = ($request['billing_address_id'] != null) ? $request['billing_address_id'] : $order['billing_address'];
                $order->billing_address_data = ($request['billing_address_id'] != null) ?  ShippingAddress::find($request['billing_address_id']) : $order['billing_address_data'];
                $order->order_note = ($request['order_note'] != null) ? $request['order_note'] : $order['order_note'];
                $order->save();

                array_push($order_ids, $order_id);
            }

            CustomerManager::create_wallet_transaction($user->id, Convert::default($cartTotal), 'order_place','order payment');

            CartManager::cart_clean($request);

            return response()->json(['status'=>'success', 'message' => translate('order_placed_successfully')], 200);
        }
    }

    public function refund_request(Request $request)
    {
        $order_details = OrderDetail::find($request->order_details_id);

        $user = $request->user();


        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        if($loyalty_point_status == 1)
        {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($request->order_details_id);

            if($user->loyalty_point < $loyalty_point)
            {
                return response()->json(['message'=>translate('you have not sufficient loyalty point to refund this order!!')], 202);
            }
        }

        if($order_details->delivery_status == 'delivered')
        {
            $order = Order::find($order_details->order_id);
            $total_product_price = 0;
            $refund_amount = 0;
            $data = [];
            foreach ($order->details as $key => $or_d) {
                $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
            }

            $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;
            $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;
            $refund_amount = $subtotal - $coupon_discount;

            $product_format = Helpers::product_data_formatting(json_decode($order_details['product'], true));

            $name = $product_format['name'];
            $thumbnail = $product_format['thumbnail'];
            $variant = isset($product_format['variation']['sku']) ? $product_format['variation']['sku'] : '';

            $data['product_price'] = $order_details->price;
            $data['quntity'] = $order_details->qty;
            $data['product_total_discount'] = $order_details->discount;
            $data['product_total_tax'] = $order_details->tax;
            $data['subtotal'] = $subtotal;
            $data['coupon_discount'] = $coupon_discount;
            $data['refund_amount'] = $refund_amount;

            $refund_day_limit = Helpers::get_business_settings('refund_day_limit');
            $order_details_date = $order_details->created_at;
            $current = \Carbon\Carbon::now();
            $length = $order_details_date->diffInDays($current);
            $expired = false;
            $already_requested = false;
            if($order_details->refund_request != 0)
            {
                $already_requested = true;
            }
            if($length > $refund_day_limit )
            {
                $expired = true;
            }
            return response()->json([
                'name'=>$name,
                'thumbnail'=>$thumbnail,
                'variant'=>$variant,
                'already_requested'=>$already_requested,
                'expired'=>$expired,
                'refund'=>$data
            ], 200);
        }else{
            return response()->json(['message'=>translate('You_can_request_for_refund_after_order_delivered')], 200);
        }

    }
    public function store_refund(Request $request)
    {

        $order_details = OrderDetail::find($request->order_details_id);
        $user = $request->user();

        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        if($loyalty_point_status == 1)
        {
            $loyalty_point = CustomerManager::count_loyalty_point_for_amount($request->order_details_id);

            if($user->loyalty_point < $loyalty_point)
            {
                return response()->json(translate('you have not sufficient loyalty point to refund this order!!'), 200);
            }
        }

        if($order_details->refund_request == 0){

            $validator = Validator::make($request->all(), [
                'order_details_id' => 'required',
                'amount' => 'required',
                'refund_reason' => 'required'

            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }
            $refund_request = new RefundRequest;
            $refund_request->order_details_id = $request->order_details_id;
            $refund_request->customer_id = $request->user()->id;
            $refund_request->status = 'pending';
            $refund_request->amount = $request->amount;
            $refund_request->product_id = $order_details->product_id;
            $refund_request->order_id = $order_details->order_id;
            $refund_request->refund_reason = $request->refund_reason;

            $product_images = [];
            if ($request->file('images')) {
                foreach ($request->file('images') as $img) {
                    $product_images[] = ImageManager::upload('refund/', 'webp', $img);
                }
                $refund_request->images = json_encode($product_images);
            }
            $refund_request->save();

            $order_details->refund_request = 1;
            $order_details->save();

            return response()->json(translate('refunded_request_updated_successfully!!'), 200);
        }else{
            return response()->json(translate('already_applied_for_refund_request!!'), 302);
        }

    }
    public function refund_details(Request $request)
    {
        $order_details = OrderDetail::find($request->id);
        $refund = RefundRequest::where('customer_id',$request->user()->id)
            ->where('order_details_id',$order_details->id )->get();
        $refund = $refund->map(function($query){
            $query['images'] = json_decode($query['images']);
            return $query;
        });

        $order = Order::find($order_details->order_id);

        $total_product_price = 0;
        $refund_amount = 0;
        $data = [];
        foreach ($order->details as $key => $or_d) {
            $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
        }
        $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;
        $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

        $refund_amount = $subtotal - $coupon_discount;
        $product_format = Helpers::product_data_formatting(json_decode($order_details['product'], true));

        $data['name'] = $product_format['name'];
        $data['thumbnail'] = $product_format['thumbnail'];
        $data['variant'] = isset($product_format['variation']['sku']) ? $product_format['variation']['sku'] : '';
        $data['product_price'] = $order_details->price;
        $data['quntity'] = $order_details->qty;
        $data['product_total_discount'] = $order_details->discount;
        $data['product_total_tax'] = $order_details->tax;
        $data['subtotal'] = $subtotal;
        $data['coupon_discount'] = $coupon_discount;
        $data['refund_amount'] = $refund_amount;
        $data['refund_request']=$refund;

        return response()->json($data, 200);
    }

    public function digital_product_download(Request $request, $id)
    {
        $order_data = OrderDetail::with('order.customer')->find($id);
        $customer_id = $request->user()->id;
        if($order_data->order->customer->id != $customer_id){
            return response()->json(['message'=>translate('Invalid customer')], 202);
        }

        if( $order_data->product->digital_product_type == 'ready_product' && $order_data->product->digital_file_ready) {
            $file_path = storage_path('app/public/product/digital-product/' .$order_data->product->digital_file_ready);
        }else{
            $file_path = storage_path('app/public/product/digital-product/' . $order_data->digital_file_after_sell);
        }

        return \response()->download($file_path);
    }

    public function common_track_order(Request $request)
    {
        $user = $request->user();
        $order_details = [];
        if (!isset($user)) {
            $user_id = User::where('phone', $request->phone_or_email)
                ->orWhere('email', $request->phone_or_email)->first()->id;

            $order = Order::where('id', $request['order_id'])->whereHas('details', function ($query) use ($user_id) {
                $query->where('customer_id', $user_id);
            })->first();
            $order_details = $order ? OrderManager::track_order($order->id) : [];

        } else {
            if ($user->phone == $request->phone_or_email || $user->email == $request->phone_or_email) {
                $order = Order::where('id', $request['order_id'])->whereHas('details', function ($query) use($user) {
                    $query->where('customer_id', $user->id);
                })->first();

                $order_details = $order ? OrderManager::track_order($order->id) : [];
            }

        }

        return response()->json($order_details, 200);
    }

    public function offline_payment_method_list(Request $request)
    {
        $data = OfflinePaymentMethod::where('status', 1)->get();
        return response()->json($data, 200);
    }

    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->where('id', $id)->first();
        $data["email"] = $order->customer["email"];
        $data["order"] = $order;

        $mpdf_view = \View::make(VIEW_FILE_NAMES['order_invoice'], compact('order'));
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function order_again(Request $request){
        $data = OrderManager::order_again($request);
        $order_product_count = $data['order_product_count'];
        $add_to_cart_count = $data['add_to_cart_count'];

        if($order_product_count == $add_to_cart_count){
            return response()->json(['message' => 'Added to cart successfully'], 200);
        }elseif($add_to_cart_count>0){
            return response()->json(['message' => $add_to_cart_count.' item added to cart successfully!'], 200);

        }{
            return response()->json(['message' => 'All items were not added to cart as they are currently unavailable for purchase'], 403);
        }
    }
}
