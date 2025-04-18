<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Exports\DeliveryManWithdrawRequest;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;
use App\Model\DeliverymanWallet;
use App\Model\Product;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class DeliverymanWithdrawController extends Controller
{
    public function withdraw()
    {
        $all = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['delivery_men'])
            ->where('admin_id', 0)
            ->whereNotNull('delivery_man_id')
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->latest()
            ->paginate(Helpers::pagination_limit());

        return view('admin-views.delivery-man.withdraw.withdraw_list', compact('withdraw_req'));
    }

    public function withdraw_view($withdraw_id)
    {
        $details = WithdrawRequest::with(['delivery_men'])->where('delivery_man_id', '<>', null)->where(['id' => $withdraw_id])->first();
        return view('admin-views.delivery-man.withdraw.withdraw-view', compact('details'));
    }

    public function status_filter(Request $request)
    {
        session()->put('delivery_withdraw_status_filter', $request['delivery_withdraw_status_filter']);
        return response()->json(session('delivery_withdraw_status_filter'));
    }

    public function withdraw_status(Request $request, $id)
    {
        $withdraw = WithdrawRequest::with('delivery_men')->find($id);
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request['note'];

        $delivery_man_fcm_token = $withdraw->delivery_men?->fcm_token;

        if(!empty($delivery_man_fcm_token)) {
            $lang = $withdraw->delivery_men?->app_language ?? Helpers::default_lang();
            $value_delivery_man = Helpers::push_notificatoin_message('withdraw_request_status_message','delivery_man', $lang);
            if ($value_delivery_man != null) {
                $data = [
                    'title' => translate('withdraw_request_' . ($request->approved == 1 ? 'approved' : 'denied')),
                    'description' => $value_delivery_man,
                    'image' => '',
                    'type' => 'notification'
                ];
                Helpers::send_push_notif_to_device($delivery_man_fcm_token, $data);
            }
        }
        $wallet = DeliverymanWallet::where('delivery_man_id', $withdraw->delivery_man_id)->first();
        if ($request->approved == 1) {
            $wallet->total_withdraw   += Convert::usd($withdraw['amount']);
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->current_balance  -= Convert::usd($withdraw['amount']);
            $wallet->save();

            $withdraw->save();
            Toastr::success(translate('Delivery_man_payment_has_been_approved_successfully'));
        }else{
            $wallet->pending_withdraw -= Convert::usd($withdraw['amount']);
            $wallet->save();
            $withdraw->save();
            Toastr::info(translate('Delivery_man_payment_request_has_been_Denied_successfully'));
        }

        return redirect()->route('admin.delivery-man.withdraw-list');

    }

    /**
     * Product wishlist report export by excel
     */
    public function export(Request $request)
    {
        $all = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['delivery_men'])
            ->where('admin_id', 0)
            ->whereNotNull('delivery_man_id')
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->latest()
            ->get();

        if ($withdraw_req->count() == 0) {
            Toastr::warning(translate('no_data_available'));
            return back();
        }
        $pending_request = $withdraw_req->where('approved',0)->count();
        $approved_request = $withdraw_req->where('approved',1)->count();
        $denied_request = $withdraw_req->where('approved',2)->count();
        $data = [
            'withdraw_request'=>$withdraw_req,
            'filter' => session('delivery_withdraw_status_filter'),
            'pending_request'=>$pending_request,
            'approved_request'=>$approved_request,
            'denied_request'=>$denied_request,
        ];
        return Excel::download(new DeliveryManWithdrawRequest($data), 'Delivery-Man-Withdraw-Request.xlsx');
    }
}
