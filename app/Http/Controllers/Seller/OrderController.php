<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminWallet;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\DeliveryZipCode;
use App\Model\Order;
use App\Model\Seller;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\SellerWallet;
use App\Model\ShippingAddress;
use App\Model\ShippingMethod;
use App\Traits\CommonTrait;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use Carbon\Carbon;

class OrderController extends Controller
{
    use CommonTrait;
     public function __construct(
        private DeliveryZipCode $delivery_zip_code,
        private Order $order,
        private User $user,
    ){

    }
    public function list(Request $request, $status)
    {
        $seller = auth('seller')->user();
        $sellerId = $seller->id;

        Order::where(['seller_id' => $sellerId,'checked' => 0])->update(['checked' => 1]);

        $seller_pos=\App\Model\BusinessSetting::where('type','seller_pos')->first()->value;

        $search = $request['search'];
        $filter = $request['filter'];
        $date_type  = $request['date_type'] ?? 'this_year';
        $from = $request['from'];
        $to = $request['to'];
        $status = $request['status'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $delivery_man_id = $request['delivery_man_id'];

        $orders = Order::with(['customer','shipping','shippingAddress','delivery_man','billingAddress'])
            ->where('seller_is','seller')
            ->where(['seller_id'=>$sellerId])
            ->when($filter == 'POS', function ($q){
                $q->where('order_type', 'POS');
            })
            ->when($filter == 'inhouse', function ($query){
                $query->where('order_type', 'default_type');
            })
            ->when($status !='all', function($q) use($status){
                $q->where(function($query) use ($status){
                    $query->orWhere('order_status',$status);
                });
            })
            ->when($request->has('date_type')&& $request->date_type == "this_year", function($dateQuery) {
                $current_start_year = date('Y-01-01');
                $current_end_year = date('Y-12-31');
                $dateQuery->whereDate('created_at', '>=',$current_start_year)
                    ->whereDate('created_at', '<=',$current_end_year);
            })
            ->when($request->has('date_type')&& $request->date_type == "this_month", function($dateQuery) {
                $current_month_start = date('Y-m-01');
                $current_month_end = date('Y-m-t');
                $dateQuery->whereDate('created_at', '>=',$current_month_start)
                    ->whereDate('created_at', '<=',$current_month_end);
            })
            ->when($request->has('date_type')&& $request->date_type == "this_week", function($dateQuery) {
                $start_week = Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d');
                $end_week =Carbon::now()->startOfWeek()->format('Y-m-d');
                $dateQuery->whereDate('created_at', '>=',$start_week)
                ->whereDate('created_at', '<=',$end_week );
            })
            ->when($request->has('date_type')&& $request->date_type == "custom_date" && !empty($from) && !empty($to), function($dateQuery) use($from, $to) {
                $dateQuery->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
            })
            ->when($delivery_man_id, function ($q) use($delivery_man_id){
                $q->where(['delivery_man_id'=> $delivery_man_id, 'seller_is'=>'seller']);
            })
            ->when($request->customer_id != 'all' && $request->has('customer_id') ,function($query)use($request){
                return $query->where('customer_id',$request->customer_id);
            })
            ->latest()
            ->paginate(Helpers::pagination_limit())
            ->appends([
                'search'=>$request['search'],
                'filter'=>$request['filter'],
                'date_type' =>$request['date_type'],
                'from'=>$request['from'],
                'to'=>$request['to'],
                'customer_id'=> $request->customer_id,
                'delivery_man_id'=>$request['delivery_man_id']]);

        $pending_query = Order::where(['seller_is'=>'seller','order_status'=>'pending','seller_id'=>$sellerId]);
        $pending = $this->common_query_status_count($pending_query, $request);

        $confirmed_query = Order::where(['seller_is'=>'seller','order_status'=>'confirmed','seller_id'=>$sellerId]);
        $confirmed = $this->common_query_status_count($confirmed_query, $request);

        $processing_query = Order::where(['seller_is'=>'seller','order_status'=>'processing','seller_id'=>$sellerId]);
        $processing = $this->common_query_status_count($processing_query, $request);

        $out_for_delivery_query = Order::where(['seller_is'=>'seller','order_status'=>'out_for_delivery','seller_id'=>$sellerId]);
        $out_for_delivery = $this->common_query_status_count($out_for_delivery_query, $request);

        $delivered_query = Order::where(['seller_is'=>'seller','order_status'=>'delivered','seller_id'=>$sellerId]);
        $delivered = $this->common_query_status_count($delivered_query, $request);

        $canceled_query = Order::where(['seller_is'=>'seller','order_status'=>'canceled','seller_id'=>$sellerId]);
        $canceled = $this->common_query_status_count($canceled_query, $request);

        $returned_query = Order::where(['seller_is'=>'seller','order_status'=>'returned','seller_id'=>$sellerId]);
        $returned = $this->common_query_status_count($returned_query, $request);

        $failed_query = Order::where(['seller_is'=>'seller','order_status'=>'failed','seller_id'=>$sellerId]);
        $failed = $this->common_query_status_count($failed_query, $request);

        $customer = "all";
        if($request->customer_id != 'all' && !is_null($request->customer_id) && $request->has('customer_id')){
            $customer = $this->user->find($request->customer_id);
        }

        $seller_id = $request->seller_id;
        $customer_id = $request->customer_id;

        return view(
            'seller-views.order.list',
            compact(
                'orders',
                'date_type',
                'search','from','to',
                'status','sellerId',
                'filter',
                'pending',
                'confirmed',
                'processing',
                'out_for_delivery',
                'delivered',
                'canceled',
                'returned',
                'failed',
                'seller_pos',
                'seller',
                'customer',
                'seller_id',
                'customer_id',
            )
        );
    }

    public function common_query_status_count($query, $request){
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';

        return $query->when($filter == 'POS', function ($q){
                $q->where('order_type', 'POS');
            })
            ->when(!empty($from) && !empty($to),function($query) use($from,$to){
                $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
            })->count();
    }

    public function details($id)
    {
        /*
        *   for edit  address
        */
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');

        $countries = $country_restrict_status ? $this->get_delivery_country_array() : COUNTRIES;

        $zip_codes = $zip_restrict_status ? $this->delivery_zip_code->all() : 0;

        /*
        *   End
        */
        $sellerId = auth('seller')->id();
        $order = $this->order->with(['delivery_man','verification_images','details' => function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        }])->with('customer', 'shipping', 'offline_payments')
            ->where('id', $id)->first();

        $physical_product = false;
        foreach($order->details as $product){
            if(isset($product->product) && $product->product->product_type == 'physical'){
                $physical_product = true;
            }
        }
        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
        ->whereNotIn('order_group_id', ['def-order-group'])
        ->whereNotIn('id', [$order['id']])
        ->get();

        $total_delivered = Order::where(['seller_id' => $sellerId, 'order_status' => 'delivered'])->count();

        $shipping_method = Helpers::get_business_settings('shipping_method');
        $delivery_men = DeliveryMan::where('is_active',1)->when($shipping_method == 'inhouse_shipping', function ($query) {
            $query->where(['seller_id' => 0]);
        })->when($shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => $order['seller_id']]);
        })->get();

        $shipping_address = ShippingAddress::find($order->shipping_address);

        if($order->order_type == 'default_type') {
            return view('seller-views.order.order-details', compact('shipping_address', 'order', 'delivery_men','linked_orders',
             'shipping_method', 'total_delivered', 'physical_product',
             'country_restrict_status','zip_restrict_status','countries','zip_codes'));
        }else{
            return view('seller-views.pos.order.order-details', compact('order', 'physical_product'));
        }
    }

    /**
     *  Digital file upload after sell
     */
    public function digital_file_upload_after_sell(Request $request)
    {
        $request->validate([
            'digital_file_after_sell'    => 'required|mimes:jpg,jpeg,png,gif,zip,pdf'
        ], [
            'digital_file_after_sell.required' => 'Digital file upload after sell is required',
            'digital_file_after_sell.mimes' => 'Digital file upload after sell upload must be a file of type: pdf, zip, jpg, jpeg, png, gif.',
        ]);

        $order_details = OrderDetail::find($request->order_id);
        $order_details->digital_file_after_sell = ImageManager::update('product/digital-product/', $order_details->digital_file_after_sell, $request->digital_file_after_sell->getClientOriginalExtension(), $request->file('digital_file_after_sell'), 'file');

        if($order_details->save()){
            Toastr::success(translate('Digital_file_upload_successfully').'!');
        }else{
            Toastr::error(translate('Digital_file_upload_failed').'!');
        }
        return back();
    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }
        $order = Order::where(['seller_id' => auth('seller')->id(), 'id' => $order_id])->first();
        if($order->order_status == 'delivered') {
            return response()->json(['status' => false], 200);
        }
        $order->delivery_man_id = $delivery_man_id;
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = null;
        $order->third_party_delivery_tracking_id = null;
        $order->save();

        Helpers::send_order_notification('new_order_assigned_message','delivery_man',$order);
        return response()->json(['status' => true], 200);
    }

    public function generate_invoice($id)
    {
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $sellerId = auth('seller')->id();
        $seller = Seller::find($sellerId)->gst;

        $order = Order::with(['details' => function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        }])->with('customer', 'shipping')
            ->with('seller')
            ->where('id', $id)->first();

        $data["email"] = $order->customer !=null?$order->customer["email"]:json_decode($order->billing_address_data)->contact_person_name ?? translate('email_not_found');
        $data["client_name"] = $order->customer !=null ? $order->customer["f_name"] . ' ' . $order->customer["l_name"]: json_decode($order->billing_address_data)->email ?? translate('customer_not_found');
        $data["order"] = $order;

//        return view('seller-views.order.invoice',compact('order', 'seller', 'company_phone', 'company_name', 'company_email', 'company_web_logo'));
      $mpdf_view = \View::make('seller-views.order.invoice', compact('order', 'seller', 'company_phone', 'company_email', 'company_name', 'company_web_logo'));
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function payment_status(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::find($request->id);

            if($order->is_guest=='0' && !isset($order->customer))
            {
                return response()->json(['customer_status'=>0],200);
            }

            $order = Order::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            $data = $request->payment_status;
            return response()->json($data);
        }
    }

    public function status(Request $request)
    {
        $order = Order::find($request->id);

        if(!$order->is_guest && !isset($order->customer))
        {
            return response()->json(['customer_status'=>0],200);
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($request->order_status=='delivered' && $order->payment_status !='paid'){

            return response()->json(['payment_status'=>0],200);
        }
        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }

        Helpers::send_order_notification($request->order_status,'customer',$order);
        if ($request->order_status == 'canceled') {
            Helpers::send_order_notification('canceled','delivery_man',$order);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status'=>'delivered']
            );
        }

        $order->save();

        if($wallet_status == 1 && $loyalty_point_status == 1 && !$order->is_guest)
        {
            if($request->order_status == 'delivered' && $order->payment_status =='paid'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

        $ref_earning_status = BusinessSetting::where('type', 'ref_earning_status')->first()->value ?? 0;
        $ref_earning_exchange_rate = BusinessSetting::where('type', 'ref_earning_exchange_rate')->first()->value ?? 0;

        if(!$order->is_guest && $wallet_status == 1 && $ref_earning_status == 1 && $request->order_status == 'delivered' && $order->payment_status =='paid'){

            $customer = User::find($order->customer_id);
            $is_first_order = Order::where(['customer_id'=>$order->customer_id,'order_status'=>'delivered','payment_status'=>'paid'])->count();
            $referred_by_user = User::find($customer->referred_by);

            if ($is_first_order == 1 && isset($customer->referred_by) && isset($referred_by_user)){
                CustomerManager::create_wallet_transaction($referred_by_user->id, floatval($ref_earning_exchange_rate), 'add_fund_by_admin', 'earned_by_referral');
            }
        }

        if ($order->delivery_man_id && $request->order_status == 'delivered') {
            $dm_wallet = DeliverymanWallet::where('delivery_man_id', $order->delivery_man_id)->first();
            $cash_in_hand = $order->payment_method == 'cash_on_delivery' ? $order->order_amount : 0;

            if (empty($dm_wallet)) {
                DeliverymanWallet::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'current_balance' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'cash_in_hand' => BackEndHelper::currency_to_usd($cash_in_hand),
                    'pending_withdraw' => 0,
                    'total_withdraw' => 0,
                ]);
            } else {
                $dm_wallet->current_balance += BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0;
                $dm_wallet->cash_in_hand += BackEndHelper::currency_to_usd($cash_in_hand);
                $dm_wallet->save();
            }

            if($order->deliveryman_charge && $request->order_status == 'delivered'){
                DeliveryManTransaction::create([
                    'delivery_man_id' => $order->delivery_man_id,
                    'user_id' => auth('seller')->id(),
                    'user_type' => 'seller',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        CommonTrait::add_order_status_history($request->id, auth('seller')->id(), $request->order_status, 'seller');

        $data = $request->order_status;
        return response()->json($data);
    }

    public function amount_date_update(Request $request){
        $field_name = $request->field_name;
        $field_val = $request->field_val;
        $user_id = auth('seller')->id();

        $order = Order::find($request->order_id);
        $order->$field_name = $field_val;

        try {
            DB::beginTransaction();

            if($field_name == 'expected_delivery_date'){
                CommonTrait::add_expected_delivery_date_history($request->order_id, $user_id, $field_val, 'seller');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status' => false], 403);
        }
        if($field_name == 'expected_delivery_date') {
            Helpers::send_order_notification('expected_delivery_date','delivery_man',$order);
        }
        return response()->json(['status' => true], 200);
    }

    public function update_deliver_info(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delivery_type = 'third_party_delivery';
        $order->delivery_service_name = $request->delivery_service_name;
        $order->third_party_delivery_tracking_id = $request->third_party_delivery_tracking_id;
        $order->delivery_man_id = null;
        $order->deliveryman_charge = 0;
        $order->expected_delivery_date = null;
        $order->save();

        Toastr::success(\App\CPU\translate('updated_successfully!'));
        return back();
    }

    public function bulk_export_data(Request $request, $status)
    {
        $sellerId = auth('seller')->id();

        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $status = $request['status'];
        $delivery_man_id = $request['delivery_man_id'];

        $key = $request['search'] ? explode(' ', $request['search']) : '';

        $orders = Order::with(['customer','shipping','shippingAddress','delivery_man','billingAddress'])
            ->where('seller_is','seller')
            ->where(['seller_id'=>$sellerId])
            ->when($filter == 'POS', function ($q){
                $q->where('order_type', 'POS');
            })
            ->when($request->has('delivery_man_id') && $delivery_man_id, function($query) use($delivery_man_id){
                $query->where('delivery_man_id', $delivery_man_id);
            })
            ->when($status !='all', function($q) use($status){
                $q->where(function($query) use ($status){
                    $query->orWhere('order_status',$status)
                        ->orWhere('payment_status',$status);
                });
            })
            ->when(!empty($from) && !empty($to),function($query) use($from,$to){
                $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->get();

        if ($orders->count()==0) {
            Toastr::warning(\App\CPU\translate('Data is Not available !!!'));
            return back();
        }

        $storage = [];

        foreach ($orders as $item) {

            $order_amount = $item->order_amount;
            $discount_amount = $item->discount_amount;
            $shipping_cost = $item->shipping_cost;
            $extra_discount = $item->extra_discount;

            if($item->order_status == 'processing'){
                $order_status = 'packaging';
            }elseif($item->order_status == 'failed'){
                $order_status = 'Failed To Deliver';
            }else{
                $order_status = $item->order_status;
            }

            $storage[] = [
                'order_id'=>$item->id,
                'Customer Id' => $item->customer_id,
                'Customer Name'=> isset($item->customer) ? $item->customer->f_name. ' '.$item->customer->l_name:'not found',
                'Order Group Id' => $item->order_group_id,
                'Order Status' => $order_status,
                'Order Amount' => Helpers::currency_converter($order_amount),
                'Order Type' => $item->order_type,
                'Coupon Code' => $item->coupon_code,
                'Discount Amount' => Helpers::currency_converter($discount_amount),
                'Discount Type' => $item->discount_type,
                'Extra Discount' => Helpers::currency_converter($extra_discount),
                'Extra Discount Type' => $item->extra_discount_type,
                'Payment Status' => $item->payment_status,
                'Payment Method' => $item->payment_method,
                'Transaction_ref' => $item->transaction_ref,
                'Verification Code' => $item->verification_code,
                'Billing Address' => isset($item->billingAddress)? $item->billingAddress->address:'not found',
                'Billing Address Data' => $item->billing_address_data,
                'Shipping Type' => $item->shipping_type,
                'Shipping Address' => isset($item->shippingAddress)? $item->shippingAddress->address:'not found',
                'Shipping Method Id' => $item->shipping_method_id,
                'Shipping Method Name' => isset($item->shipping)? $item->shipping->title:'not found',
                'Shipping Cost' => Helpers::currency_converter($shipping_cost),
                'Seller Id' => $item->seller_id,
                'Seller Name' => isset($item->seller)? $item->seller->f_name. ' '.$item->seller->l_name:'not found',
                'Seller Email'  => isset($item->seller)? $item->seller->email:'not found',
                'Seller Phone'  => isset($item->seller)? $item->seller->phone:'not found',
                'Seller Is' => $item->seller_is,
                'Shipping Address Data' => $item->shipping_address_data,
                'Delivery Type' => $item->delivery_type,
                'Delivery Man Id' => $item->delivery_man_id,
                'Delivery Service Name' => $item->delivery_service_name,
                'Third Party Delivery Tracking Id' => $item->third_party_delivery_tracking_id,
                'Checked' => $item->checked,

            ];
        }


            return (new FastExcel($storage))->download('Order_All_details.xlsx');

    }
    public function address_update(Request $request){
        // return $request->all();
        $order = $this->order->find($request->order_id);
        $shipping_address_data = json_decode($order->shipping_address_data, true);
        $billing_address_data = json_decode($order->billing_address_data, true);

        $common_address_data = [
            'contact_person_name' => $request->name,
            'phone' => $request->phone_number,
            'city' => $request->city,
            'zip' => $request->zip,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
        ];

        if ($request->address_type == 'shipping') {
            $shipping_address_data = array_merge($shipping_address_data, $common_address_data);
        } elseif ($request->address_type == 'billing') {
            $billing_address_data = array_merge($billing_address_data, $common_address_data);
        }
        $update_data = [];

        if ($request->address_type == 'shipping') {
            $update_data['shipping_address_data'] = json_encode($shipping_address_data);
        } elseif ($request->address_type == 'billing') {
            $update_data['billing_address_data'] = json_encode($billing_address_data);
        }

        if (!empty($update_data)) {
            DB::table('orders')->where('id', $request->order_id)->update($update_data);
        }
        Toastr::success(translate('successfully_updated'));
        return back();

    }
    public function get_customers(Request $request){
        $key = explode(' ', $request['q']);
        $all_customer = ['id'=>'all','text'=>'All customer'];
        $data = DB::table('users')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            })
            ->where('id','!=',0)
            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->limit(20)
            ->get([DB::raw('id,IF(id <> "0", CONCAT(f_name, " ", l_name, " (", phone ,")"),CONCAT(f_name, " ", l_name)) as text')])->toArray();
            array_unshift($data, $all_customer);
        return response()->json($data);

    }
}
