<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Exports\CustomerListExport;
use App\Exports\SubscriberListExport;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Subscription;
use App\Model\BusinessSetting;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomerController extends Controller
{
    public function customer_list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $customers = User::with(['orders'])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $customers = User::with(['orders']);
        }
        $customers = $customers->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.list', compact('customers', 'search'));
    }

    public function status_update(Request $request)
    {
        User::where(['id' => $request['id']])->update([
            'is_active' => $request['status'] ?? 0
        ]);

        DB::table('oauth_access_tokens')
            ->where('user_id', $request['id'])
            ->delete();

        return response()->json([], 200);
    }

    public function view(Request $request, $id)
    {

        $customer = User::find($id);
        if (isset($customer)) {
            $query_param = [];
            $search = $request['search'];
            $orders = Order::where(['customer_id' => $id, 'is_guest'=>'0']);
            if ($request->has('search')) {

                $orders = $orders->where('id', 'like', "%{$search}%");
                $query_param = ['search' => $request['search']];
            }
            $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
            return view('admin-views.customer.customer-view', compact('customer', 'orders', 'search'));
        }
        Toastr::error(translate('customer_not_found'));
        return back();
    }

    public function delete($id)
    {
        $customer = User::find($id);
        $customer->delete();
        Toastr::success(translate('customer_deleted_successfully'));
        return back();
    }

    public function subscriber_list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $subscription_list = Subscription::where('email','like', "%{$search}%");

            $query_param = ['search' => $request['search']];
        } else {
        $subscription_list = new Subscription;
        }
        $subscription_list = $subscription_list->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.subscriber-list',compact('subscription_list','search'));
    }

    public function customer_settings()
    {
        $data = BusinessSetting::where('type','like','wallet_%')->orWhere('type','like','loyalty_point_%')->orWhere('type','like','ref_earning_%')->get();
        $data = array_column($data->toArray(), 'value','type');

        return view('admin-views.customer.customer-settings', compact('data'));
    }

    public function customer_update_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('update_option_is_disable_for_demo'));
            return back();
        }

        $request->validate([
            'add_fund_bonus'=>'nullable|numeric|max:100|min:0',
            'loyalty_point_exchange_rate'=>'nullable|numeric|min:0',
            'ref_earning_exchange_rate'=>'nullable|numeric|min:0',
            'maximum_add_fund_amount'=>'nullable|numeric|min:0',
            'minimum_add_fund_amount'=>'nullable|numeric|min:0',
            'item_purchase_point'=>'nullable|numeric|min:0',
            'minimun_transfer_point'=>'nullable|numeric|min:0',
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_status'], [
            'value' => $request['customer_wallet'] ?? 0,
            'updated_at' => now()
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_status'], [
            'value' => $request['customer_loyalty_point'] ?? 0,
            'updated_at' => now()
        ]);
        BusinessSetting::updateOrInsert(['type' => 'wallet_add_refund'], [
            'value' => $request['refund_to_wallet'] ?? 0,
            'updated_at' => now()
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_exchange_rate'], [
            'value' => $request['loyalty_point_exchange_rate'] ?? 0,
            'updated_at' => now()
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_item_purchase_point'], [
            'value' => $request['item_purchase_point'] ?? 0,
            'updated_at' => now()
        ]);
        BusinessSetting::updateOrInsert(['type' => 'loyalty_point_minimum_point'], [
            'value' => $request['minimun_transfer_point'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'ref_earning_status'], [
            'value' => $request['ref_earning_status'] ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'ref_earning_exchange_rate'], [
            'value' => BackEndHelper::currency_to_usd($request['ref_earning_exchange_rate']) ?? 0,
            'updated_at' => now()
        ]);

        BusinessSetting::updateOrInsert(['type' => 'add_funds_to_wallet'], [
            'value' => $request['add_funds_to_wallet'] ?? 0,
            'updated_at' => now()
        ]);

        if($request->has('minimum_add_fund_amount') && $request->has('maximum_add_fund_amount'))
        {
            if($request['maximum_add_fund_amount'] > $request['minimum_add_fund_amount'])
            {
                BusinessSetting::updateOrInsert(['type' => 'minimum_add_fund_amount'], [
                    'value' => BackEndHelper::currency_to_usd($request['minimum_add_fund_amount']) ?? 0,
                    'updated_at' => now()
                ]);

                BusinessSetting::updateOrInsert(['type' => 'maximum_add_fund_amount'], [
                    'value' => BackEndHelper::currency_to_usd($request['maximum_add_fund_amount']) ?? 0,
                    'updated_at' => now()
                ]);
            }else{
                Toastr::error(translate('minimum_amount_cannot_be_greater_than_maximum_amount'));
                return back();
            }
        }

        Toastr::success(\App\CPU\translate('customer_settings_updated_successfully'));
        return back();
    }

    public function get_customers(Request $request){
        $key = explode(' ', $request['q']);
        $data = User::where('id','!=',0)->
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                ->orWhere('l_name', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
            }
        })
        ->limit(8)
        ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);
        if($request->all) $data[]=(object)['id'=>false, 'text'=>trans('messages.all')];


        return response()->json($data);
    }


    /**
     * Export product list by excel
     * @param Request $request
     * @param $type
     */
    public function export(Request $request){

        $key = $request['search'];
        $customers = User::withCount(['orders'])
                ->when($key!=null, function($query) use($key){
                    $key = explode(' ', $key);
                    foreach ($key as $value) {
                        $query->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                })->latest()->get();
        $active = $customers->where('is_active',1)->count();
        $inactive = $customers->where('is_active',0)->count();
        $data = [
            'customers' => $customers,
            'search' => $key,
            'active' => $active,
            'inactive' => $inactive,
        ];
        return Excel::download(new CustomerListExport($data), 'Customers.xlsx');
    }
    /**
     * Subscriber list export
     */

    public function subscriber_list_export(Request $request){
        $key = $request['search'];
        $subscription = Subscription::where(function ($query) use ($key) {
                    $key = explode(' ', $key);
                    foreach ($key as $value) {
                        $query->orWhere('email', 'like', "%{$value}%");
                    }
            })->latest()->get();

        $data = [
            'subscription' => $subscription,
            'search' => $key,
        ];
        return Excel::download(new SubscriberListExport($data), 'Subscriber-list.xlsx');
    }

}
