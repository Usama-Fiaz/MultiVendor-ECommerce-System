<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\DeliveryManTransaction;
use App\Model\DeliverymanWallet;
use App\Model\DeliveryZipCode;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Seller;
use App\Traits\CommonTrait;
use App\Model\ShippingAddress;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Ramsey\Uuid\Uuid;
use function App\CPU\translate;
use App\CPU\CustomerManager;
use App\CPU\Convert;
use App\Exports\OrderExport;
use App\Model\Customer;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{
    use CommonTrait;
    public function __construct(
        private DeliveryZipCode $delivery_zip_code,
        private Order $order,
        private Seller $seller,
        private User $user,
    ){

    }

    public function list(Request $request, $status)
    {

        $search = $request['search'];
        $filter = $request['filter'];
        $date_type  = $request['date_type'] ?? null;
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $delivery_man_id = $request['delivery_man_id'];

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'seller.shop'])
            ->when($status != 'all', function ($q) use($status){
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->when($filter,function($q) use($filter){
                $q->when($filter == 'all', function($q){
                    return $q;
                })
                    ->when($filter == 'POS', function ($q){
                        $q->whereHas('details', function ($q){
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                        $q->whereHas('details', function ($query) use ($filter){
                            $query->whereHas('product', function ($query) use ($filter){
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }});
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
            ->when($delivery_man_id, function ($q) use($delivery_man_id){
                $q->where(['delivery_man_id'=> $delivery_man_id]);
            })
            ->when($request->customer_id != 'all' && $request->has('customer_id') ,function($query)use($request){
                return $query->where('customer_id',$request->customer_id);
            })
            ->when($request->seller_id != 'all' && $request->has('seller_id') && $request->seller_id != 0 ,function($query)use($request){
                return $query->where(['seller_is'=>'seller','seller_id'=>$request->seller_id]);
            })
            ->when($request->seller_id != 'all' && $request->has('seller_id') && $request->seller_id == 0 ,function($query)use($request){
                return $query->where(['seller_is'=>'admin']);
            })
            ->latest('id')
            ->paginate(Helpers::pagination_limit())
            ->appends([
                'search'=>$request['search'],
                'filter'=>$request['filter'],'from'=>$request['from'],
                'to'=>$request['to'],
                'date_type' =>$request['date_type'],
                'customer_id'=> $request->customer_id,
                'seller_id' => $request->seller_id,
                'delivery_man_id'=>$request['delivery_man_id'],
                ]);

            $pending_query = Order::where(['order_status' => 'pending']);
            $pending_count = $this->common_query_status_count($pending_query, $status, $request);

            $confirmed_query = Order::where(['order_status' => 'confirmed']);
            $confirmed_count = $this->common_query_status_count($confirmed_query, $status, $request);

            $processing_query = Order::where(['order_status' => 'processing']);
            $processing_count = $this->common_query_status_count($processing_query, $status, $request);

            $out_for_delivery_query = Order::where(['order_status' => 'out_for_delivery']);
            $out_for_delivery_count = $this->common_query_status_count($out_for_delivery_query, $status, $request);

            $delivered_query = Order::where(['order_status' => 'delivered']);
            $delivered_count = $this->common_query_status_count($delivered_query, $status, $request);

            $canceled_query = Order::where(['order_status' => 'canceled']);
            $canceled_count = $this->common_query_status_count($canceled_query, $status, $request);

            $returned_query = Order::where(['order_status' => 'returned']);
            $returned_count = $this->common_query_status_count($returned_query, $status, $request);

            $failed_query = Order::where(['order_status' => 'failed']);
            $failed_count = $this->common_query_status_count($failed_query, $status, $request);

            $sellers = $this->seller->with('shop')->where('status','!=','pending')->get();

            $customer = "all";
            if($request->customer_id != 'all' && !is_null($request->customer_id) && $request->has('customer_id')){
                $customer = $this->user->find($request->customer_id);
            }

            $seller_id = $request->seller_id;
            $customer_id = $request->customer_id;

        return view(
                'admin-views.order.list',
                compact(
                    'date_type',
                    'orders',
                    'search',
                    'from', 'to', 'status',
                    'filter',
                    'pending_count',
                    'confirmed_count',
                    'processing_count',
                    'out_for_delivery_count',
                    'delivered_count',
                    'returned_count',
                    'failed_count',
                    'canceled_count',
                    'sellers',
                    'customer',
                    'seller_id',
                    'customer_id',
                )
            );
    }

    public function common_query_status_count($query, $status, $request){
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $key = $request['search'] ? explode(' ', $request['search']) : '';

            return $query->when($status != 'all', function ($q) use($status){
                $q->where(function ($query) use ($status) {
                    $query->orWhere('order_status', $status);
                });
            })
            ->when($filter,function($q) use($filter) {
                $q->when($filter == 'all', function ($q) {
                    return $q;
                })
                ->when($filter == 'POS', function ($q){
                    $q->whereHas('details', function ($q){
                        $q->where('order_type', 'POS');
                    });
                })
                ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                    $q->whereHas('details', function ($query) use ($filter){
                        $query->whereHas('product', function ($query) use ($filter){
                            $query->where('added_by', $filter);
                        });
                    });
                });
            })
            ->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }});
            })->when(!empty($from) && !empty($to), function($dateQuery) use($from, $to) {
                $dateQuery->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })->count();
    }

    public function details($id)
    {
        //for edit  address
        $country_restrict_status = Helpers::get_business_settings('delivery_country_restriction');
        $zip_restrict_status = Helpers::get_business_settings('delivery_zip_code_area_restriction');
        $countries = $country_restrict_status ? $this->get_delivery_country_array() : COUNTRIES;
        $zip_codes = $zip_restrict_status ? $this->delivery_zip_code->all() : 0;

        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = $this->order->with('details.product_all_status', 'verification_images' ,'shipping', 'seller.shop', 'offline_payments','delivery_man')->where(['id' => $id])->first();

        $physical_product = false;
        if(isset($order->details)){
            foreach($order->details as $product){
                if(isset($product->product) && $product->product->product_type == 'physical'){
                    $physical_product = true;
                }
            }
        }

        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
            ->whereNotIn('order_group_id', ['def-order-group'])
            ->whereNotIn('id', [$order['id']])
            ->get();

        $total_delivered = Order::where(['seller_id' => $order->seller_id, 'order_status' => 'delivered', 'order_type' => 'default_type'])->count();

        $shipping_method = Helpers::get_business_settings('shipping_method');
        $delivery_men = DeliveryMan::where('is_active', 1)->when($order->seller_is == 'admin', function ($query) {
            $query->where(['seller_id' => 0]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'sellerwise_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => $order['seller_id']]);
        })->when($order->seller_is == 'seller' && $shipping_method == 'inhouse_shipping', function ($query) use ($order) {
            $query->where(['seller_id' => 0]);
        })->get();

        $shipping_address = ShippingAddress::find($order->shipping_address);
        if($order->order_type == 'default_type')
        {
            return view('admin-views.order.order-details', compact('shipping_address','order', 'linked_orders',
                'delivery_men', 'total_delivered', 'company_name', 'company_web_logo', 'physical_product',
                'country_restrict_status','zip_restrict_status','countries','zip_codes'));
        }else{
            return view('admin-views.pos.order.order-details', compact('order', 'company_name', 'company_web_logo'));
        }

    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }
        $order = Order::find($order_id);
        $order->delivery_man_id = $delivery_man_id;
        $order->delivery_type = 'self_delivery';
        $order->delivery_service_name = null;
        $order->third_party_delivery_tracking_id = null;
        $order->save();

        Helpers::send_order_notification('new_order_assigned_message','delivery_man',$order);
        /* for seller product send notification */
        if($order->seller_is == 'seller'){
            Helpers::send_order_notification('delivery_man_assign_by_admin_message','seller',$order);
        }
        /* end */

        return response()->json(['status' => true], 200);
    }

    public function status(Request $request)
    {
        $user_id = auth('admin')->id();

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

        Helpers::send_order_notification($request->order_status,'customer',$order);

        if ($request->order_status == 'canceled'){
            Helpers::send_order_notification('canceled','delivery_man',$order);
        }
        if($order->seller_is == 'seller'){
            if ($request->order_status == 'canceled'){
                Helpers::send_order_notification('canceled','seller',$order);
            }elseif($request->order_status == 'delivered'){
                Helpers::send_order_notification('delivered','seller',$order);
            }
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);
        $order->save();

        if($loyalty_point_status == 1 && !$order->is_guest)
        {
            if($request->order_status == 'delivered' && $order->payment_status =='paid'){
                CustomerManager::create_loyalty_point_transaction($order->customer_id, $order->id, Convert::default($order->order_amount-$order->shipping_cost), 'order_place');
            }
        }

        $ref_earning_status = BusinessSetting::where('type', 'ref_earning_status')->first()->value ?? 0;
        $ref_earning_exchange_rate = BusinessSetting::where('type', 'ref_earning_exchange_rate')->first()->value ?? 0;

        if(!$order->is_guest && $ref_earning_status == 1 && $request->order_status == 'delivered' && $order->payment_status =='paid'){

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
                    'user_id' => 0,
                    'user_type' => 'admin',
                    'credit' => BackEndHelper::currency_to_usd($order->deliveryman_charge) ?? 0,
                    'transaction_id' => Uuid::uuid4(),
                    'transaction_type' => 'deliveryman_charge'
                ]);
            }
        }

        self::add_order_status_history($request->id, 0, $request->order_status, 'admin');

        $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        if (isset($transaction) && $transaction['status'] == 'disburse') {
            return response()->json($request->order_status);
        }

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'admin');
            OrderDetail::where('order_id', $order->id)->update(
                ['delivery_status'=>'delivered']
            );
        }

        return response()->json($request->order_status);
    }

    public function amount_date_update(Request $request){
        $field_name = $request->field_name;
        $field_val = $request->field_val;
        $user_id = 0;

        $order = Order::find($request->order_id);
        $order->$field_name = $field_val;

        try {
            DB::beginTransaction();

            if($field_name == 'expected_delivery_date'){
                self::add_expected_delivery_date_history($request->order_id, $user_id, $field_val, 'admin');
            }
            $order->save();

            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status' => false], 403);
        }

        if($field_name == 'expected_delivery_date') {
            Helpers::send_order_notification('expected_delivery_date','delivery_man',$order);

        }elseif($field_name == 'deliveryman_charge'){
            Helpers::send_order_notification('delivery_man_charge','delivery_man',$order);
        }

        return response()->json(['status' => true], 200);
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

    public function generate_invoice($id)
    {
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $seller = Seller::find($order->details->first()->seller_id);
        $data["email"] = $order->customer !=null?$order->customer["email"]:json_decode($order->billing_address_data)->contact_person_name ?? translate('email_not_found');
        $data["client_name"] = $order->customer !=null? $order->customer["f_name"] . ' ' . $order->customer["l_name"]:json_decode($order->billing_address_data)->email ?? translate('customer_not_found');
        $data["order"] = $order;
        $mpdf_view = View::make('admin-views.order.invoice',
            compact('order', 'seller', 'company_phone', 'company_name', 'company_email', 'company_web_logo')
        );
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    /*
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
            Toastr::success(translate('digital_file_upload_successfully'));
        }else{
            Toastr::error(translate('digital_file_upload_failed'));
        }
        return back();
    }

    public function inhouse_order_filter()
    {
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            session()->put('show_inhouse_orders', 0);
        } else {
            session()->put('show_inhouse_orders', 1);
        }
        return back();
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

        Toastr::success(translate('updated_successfully'));
        return back();
    }

    public function bulk_export_data(Request $request, $status)
    {
        $search = $request['search'];
        $filter = $request['filter'];
        $from = $request['from'];
        $to = $request['to'];
        $delivery_man_id = $request['delivery_man_id'];

        if ($status != 'all') {
            $orders = Order::when($filter,function($q) use($filter){
                $q->when($filter == 'all', function($q){
                    return $q;
                })
                    ->when($filter == 'POS', function ($q){
                        $q->whereHas('details', function ($q){
                            $q->where('order_type', 'POS');
                        });
                    })
                    ->when($filter == 'admin' || $filter == 'seller', function($q) use($filter){
                        $q->whereHas('details', function ($query) use ($filter){
                            $query->whereHas('product', function ($query) use ($filter){
                                $query->where('added_by', $filter);
                            });
                        });
                    });
            })
                ->with(['customer'])->where(function($query) use ($status){
                    $query->orWhere('order_status',$status)
                        ->orWhere('payment_status',$status);
                });
        } else {
            $orders = Order::with(['customer'])
                ->when($filter,function($q) use($filter){
                    $q->when($filter == 'all', function($q){
                        return $q;
                    })
                        ->when($filter == 'POS', function ($q){
                            $q->whereHas('details', function ($q){
                                $q->where('order_type', 'POS');
                            });
                        })
                        ->when(($filter == 'admin' || $filter == 'seller'), function($q) use($filter){
                            $q->whereHas('details', function ($query) use ($filter){
                                $query->whereHas('product', function ($query) use ($filter){
                                    $query->where('added_by', $filter);
                                });
                            });
                        });
                });
        }

        $key = $request['search'] ? explode(' ', $request['search']) : '';
        $orders = $orders->when($request->has('search') && $search!=null,function ($q) use ($key) {
                $q->where(function($qq) use ($key){
                    foreach ($key as $value) {
                        $qq->where('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }});
            })
            ->when($request->has('delivery_man_id') && $delivery_man_id, function($query) use($delivery_man_id){
                $query->where('delivery_man_id', $delivery_man_id);
            })
            ->when(!empty($from) && !empty($to), function($dateQuery) use($from, $to) {
                $dateQuery->whereDate('created_at', '>=',$from)
                    ->whereDate('created_at', '<=',$to);
            })
            ->when($request->seller_id != 'all' && $request->has('seller_id') && $request->seller_id != 0 ,function($query)use($request){
                return $query->where(['seller_is'=>'seller','seller_id'=>$request->seller_id]);
            })
            ->when($request->seller_id != 'all' && $request->has('seller_id') && $request->seller_id == 0 ,function($query)use($request){
                return $query->where(['seller_is'=>'admin']);
            })
            ->when($request->customer_id != 'all' && $request->has('customer_id') ,function($query)use($request){
                return $query->where('customer_id',$request->customer_id);
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
            ->orderBy('id', 'DESC')->get();

        if ($orders->count()==0) {
            Toastr::warning(translate('data_is_not_available'));
            return back();
        }
        /** order status count  */
        $status_array = [
            'pending' => 0,
            'confirmed' => 0,
            'processing' => 0,
            'out_for_delivery' => 0,
            'delivered' => 0,
            'returned' => 0,
            'failed' => 0,
            'canceled' => 0,
        ];
        $orders->map(function ($order) use (&$status_array) { // Pass by reference using &
            if (isset($status_array[$order->order_status])) {
                $status_array[$order->order_status]++;
            }
            $order?->order_details->map(function($details)use ($order){
                $order['total_qty']+= $details->qty;
                $order['total_price']+= $details->qty*$details->price+($details->tax_model == 'include' ? $details->qty*$details->tax : 0);
                $order['total_discount']+= $details->discount;
                $order['total_tax']+= $details->tax_model == 'exclude' ? $details->tax : 0;
            });

        });
        /** order status count  */

        /** date */
        $date_type = $request->date_type ?? '';
        $from = match ($date_type) {
            'this_year' => date('Y-01-01'),
            'this_month' => date('Y-m-01'),
            'this_week' => Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d'),
            default => $request['from'] ?? '',
        };
        $to = match ($date_type) {
            'this_year' => date('Y-12-31'),
            'this_month' => date('Y-m-t'),
            'this_week' => Carbon::now()->startOfWeek()->format('Y-m-d'),
            default => $request['to'] ?? '',
        };
        /** end  */
        $seller = [];
        if($request->seller_id != 'all' && $request->has('seller_id') && $request->seller_id != 0){
            $seller = $this->seller->find($request->seller_id);
        }
        $customer = [];
        if($request->customer_id != 'all' && $request->has('customer_id')){
            $customer = User::find($request->customer_id);
        }
        $data = [
            'orders'=>$orders,
            'status'=>$status,
            'seller'=>$seller,
            'customer'=>$customer,
            'status_array'=>$status_array,
            'search'=>$search,
            'order_type'=>$filter ?? 'all',
            'from'=>$from ,
            'to'=>$to,
            'date_type' =>$date_type,


        ];
        return Excel::download(new OrderExport($data), 'Orders.xlsx');
    }

    /**
     * Update Address From Order Details (Shipping and Billing)
     */
    public function address_update(Request $request){
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

        if($order->seller_is == 'seller'){
            Helpers::send_order_notification('order_edit_message','seller',$order);
        }

        if($order->delivery_man_id){
            Helpers::send_order_notification('order_edit_message','delivery_man',$order);
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
