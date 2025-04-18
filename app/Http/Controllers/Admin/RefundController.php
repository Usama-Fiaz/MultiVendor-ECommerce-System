<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use App\Model\RefundRequest;
use App\Model\Order;
use App\Model\AdminWallet;
use App\Model\SellerWallet;
use App\Model\RefundTransaction;
use App\CPU\Helpers;
use App\Model\OrderDetail;
Use App\Model\RefundStatus;
use App\CPU\CustomerManager;
use App\User;
use App\CPU\Convert;
use App\Exports\RefundRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class RefundController extends Controller
{
    public function list(Request $request, $status)
    {
        $search = $request->search;
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $refund_list = RefundRequest::whereHas('order', function ($query) {
                $query->where('seller_is', 'admin');
            });
        }else if (session()->has('show_seller_orders') && session('show_seller_orders') == 1) {
            $refund_list = RefundRequest::whereHas('order', function ($query) {
                $query->where('seller_is', 'seller');
            });
        }else{
            $refund_list = new RefundRequest;
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $refund_list = $refund_list->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%")
                        ->orWhere('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $refund_list = $refund_list->where('status',$status)->latest()->paginate(Helpers::pagination_limit());

        return view('admin-views.refund.list',compact('refund_list','search'));
    }
    public function details($id)
    {
        $refund = RefundRequest::find($id);

        return view('admin-views.refund.details',compact('refund'));
    }
    public function refund_status_update(Request $request)
    {
        $refund = RefundRequest::find($request->id);
        $user = User::find($refund->customer_id);

        if(!isset($user))
        {
            Toastr::warning(translate('this_account_has_been_deleted_you_can_not_modify_the_status'));
            return back();
        }

        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        $loyalty_point = CustomerManager::count_loyalty_point_for_amount($refund->order_details_id);

        if( $loyalty_point_status == 1)
        {

            if($user->loyalty_point < $loyalty_point && ($request->refund_status == 'refunded' || $request->refund_status == 'approved'))
            {
                Toastr::warning(translate('customer_has_not_sufficient_loyalty_point_to_take_refund_for_this_order'));
                return back();
            }
        }
        $order = Order::find($refund->order_id);
        if($request->refund_status == 'refunded' && $refund->status != 'refunded')
        {

            if($order->seller_is == 'admin')
            {
                $admin_wallet = AdminWallet::where('admin_id',$order->seller_id)->first();
                $admin_wallet->inhouse_earning = $admin_wallet->inhouse_earning - $refund->amount;
                $admin_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();

            }else{
                $seller_wallet = SellerWallet::where('seller_id',$order->seller_id)->first();
                $seller_wallet->total_earning = $seller_wallet->total_earning - $refund->amount;
                $seller_wallet->save();

                $transaction = new RefundTransaction;
                $transaction->order_id = $refund->order_id;
                $transaction->payment_for = 'Refund Request';
                $transaction->payer_id = $order->seller_id;
                $transaction->payment_receiver_id = $refund->customer_id;
                $transaction->paid_by = $order->seller_is;
                $transaction->paid_to = 'customer';
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_status = $request->payment_method !=null?'paid':'unpaid';
                $transaction->amount = $refund->amount;
                $transaction->transaction_type = 'Refund';
                $transaction->order_details_id = $refund->order_details_id;
                $transaction->refund_id = $refund->id;
                $transaction->save();
            }


        }
        if($refund->status != 'refunded')
        {
            $order_details = OrderDetail::find($refund->order_details_id);

            $refund_status = new RefundStatus;
            $refund_status->refund_request_id = $refund->id;
            $refund_status->change_by = 'admin';
            $refund_status->change_by_id = auth('admin')->id();
            $refund_status->status = $request->refund_status;

            if($request->refund_status == 'pending')
            {
                $order_details->refund_request = 1;
            }
            elseif($request->refund_status == 'approved')
            {
                $order_details->refund_request = 2;
                $refund->approved_note = $request->approved_note;

                $refund_status->message = $request->approved_note;

            }
            elseif($request->refund_status == 'rejected')
            {
                $order_details->refund_request = 3;
                $refund->rejected_note = $request->rejected_note;

                $refund_status->message = $request->rejected_note;
            }
            elseif($request->refund_status == 'refunded')
            {
                $order_details->refund_request = 4;
                $refund->payment_info = $request->payment_info;
                $refund_status->message = $request->payment_info;

                if($loyalty_point > 0 && $loyalty_point_status == 1)
                {
                    CustomerManager::create_loyalty_point_transaction($refund->customer_id, $refund->order_id, $loyalty_point, 'refund_order');
                }

                $wallet_add_refund = Helpers::get_business_settings('wallet_add_refund');

                if($wallet_add_refund==1 && $request->payment_method == 'customer_wallet')
                {
                    CustomerManager::create_wallet_transaction($refund->customer_id, Convert::default($refund->amount), 'order_refund','order_refund');
                }
            }
            $order_details->save();

            $refund->status = $request->refund_status;
            $refund->change_by = 'admin';
            $refund->save();
            $refund_status->save();

            /** send notification */
            if ($order->seller_is == 'seller') {
                if ($request->refund_status != 'rejected' && $request->refund_status != 'refunded') {
                    Helpers::send_order_notification('refund_request_status_changed_by_admin', 'seller', $order);
                } elseif ($request->refund_status == 'rejected') {
                    Helpers::send_order_notification('refund_request_canceled_message', 'seller', $order);
                } else {
                    Helpers::send_order_notification('order_refunded_message', 'seller', $order);
                }
            }
            if ($request->refund_status == 'refunded') {
                Helpers::send_order_notification('order_refunded_message', 'customer', $order);
            } elseif ($request->refund_status == 'rejected') {
                Helpers::send_order_notification('refund_request_canceled_message', 'customer', $order);
            }
            /** end  */

            Toastr::success(translate('refund_status_updated'));
            return back();

        }else{
            Toastr::warning(translate('refunded status can not be changed'));
            return back();
        }



    }
    public function index()
    {
        return view('admin-views.refund.index');
    }
    public function update(Request $request)
    {
        $request->validate([
            'refund_day_limit' => 'required',
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'refund_day_limit'], [
            'value' => $request['refund_day_limit']
        ]);
        Toastr::success(translate('refund_day_limit_updated'));
        return back();
    }
    public function inhouse_order_filter(Request $request)
    {
        if($request->has('type') && $request->type == 'all') {
            session()->put('show_inhouse_and_seller_orders', 1);
            session()->put('show_inhouse_orders', 0);
            session()->put('show_seller_orders', 0);
        }

        if($request->has('type') && $request->type == 'inhouse') {
            session()->put('show_inhouse_and_seller_orders', 0);
            session()->put('show_inhouse_orders', 1);
            session()->put('show_seller_orders', 0);
        }

        if($request->has('type') && $request->type == 'seller') {
            session()->put('show_seller_orders', 1);
            session()->put('show_inhouse_and_seller_orders', 0);
            session()->put('show_inhouse_orders', 0);
        }
        return back();
    }

    /** export */
    public function export(Request $request ,$status){
        $search = $request->search;
        $refund_list = RefundRequest::with(['order','order.seller','order.delivery_man', 'product'])
            ->when(session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1, function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('seller_is', 'admin');
                });
            })
            ->when(session()->has('show_seller_orders') && session('show_seller_orders') == 1, function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('seller_is', 'seller');
                });
            })->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $keywords = explode(' ', $search);
                    $query->where(function ($subquery) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $subquery->orWhere('order_id', 'like', "%{$keyword}%")
                            ->orWhere('id', 'like', "%{$keyword}%");
                        }
                    });
                }
            })
            ->where('status',$status)->latest()->get();
        $data = [
            'refund_list' => $refund_list,
            'search' => $search,
            'status' => $status,
            'filter_By' => session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1 ? 'inhouse_request' : (session()->has('show_seller_orders') && session('show_seller_orders') == 1 ? 'seller_request' : 'all') ,
        ];

        return Excel::download(new RefundRequestExport($data), 'refund-request.xlsx');
    }
    /** end export */

}
