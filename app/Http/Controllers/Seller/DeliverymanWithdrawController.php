<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\DeliveryMan;
use App\Model\DeliverymanWallet;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use function App\CPU\translate;

class DeliverymanWithdrawController extends Controller
{
    private $shippingMethod;
    public function __construct()
    {
        $this->shippingMethod = Helpers::get_business_settings('shipping_method');
    }
    public function withdraw()
    {
        if ($this->shippingMethod == 'inhouse_shipping') {
            Toastr::warning(translate('access_denied!!'));
            return redirect()->route('seller.auth.login');
        }
        $all = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['delivery_men'])
            ->where('seller_id', auth('seller')->user()->id)
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

        return view('seller-views.delivery-man.withdraw.withdraw_list', compact('withdraw_req'));
    }

    public function withdraw_view($withdraw_id)
    {
        $details = WithdrawRequest::with(['delivery_men'])->where('delivery_man_id', '<>', null)->where(['seller_id'=>auth('seller')->user()->id])->find($withdraw_id);
        return view('seller-views.delivery-man.withdraw.withdraw-view', compact('details'));
    }

    public function status_filter(Request $request)
    {
        session()->put('delivery_withdraw_status_filter', $request['delivery_withdraw_status_filter']);
        return response()->json(session('delivery_withdraw_status_filter'));
    }

    public function withdrawStatus(Request $request, $id)
    {
        $withdraw = WithdrawRequest::with('delivery_men')->where(['seller_id' => auth('seller')->user()->id])->find($id);
        if(!$withdraw){
            Toastr::warning(translate('Invalid_withdraw'));
            return redirect()->route('seller.delivery-man.withdraw-list');
        }
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

        return redirect()->route('seller.delivery-man.withdraw-list');

    }
    /**
     * Product wishlist report export by excel
     */
    public function export(Request $request)
    {
        if ($this->shippingMethod == 'inhouse_shipping') {
            Toastr::warning(translate('access_denied!!'));
            return redirect()->route('seller.auth.login');
        }
        $all = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('delivery_withdraw_status_filter') && session('delivery_withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['delivery_men'])
            ->where('seller_id', auth('seller')->user()->id)
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
            Toastr::warning(\App\CPU\translate('No_data_available!'));
            return back();
        }

        $data = array();

        foreach ($withdraw_req as $withdraw) {
            $status = '';
            if ($withdraw->approved == 0) {
                $status = 'Pending';
            } elseif ($withdraw->approved == 1) {
                $status = 'Approved';
            } elseif ($withdraw->approved == 2) {
                $status = 'Denied';
            }

            if($withdraw->delivery_men){
                $delivery_men_name = $withdraw->delivery_men->f_name . ' ' .$withdraw->delivery_men->l_name;
                $delivery_men_phone = $withdraw->delivery_men->phone;
            }else{
                $delivery_men_name = 'Not Found';
                $delivery_men_phone = 'Not Found';
            }

            $data[] = array(
                'Name' => $delivery_men_name,
                'Phone' => $delivery_men_phone,
                'Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($withdraw->amount)),
                'Submitted Date' => $withdraw->created_at->format('d/m/y h:i:s A'),
                'Status' => $status,
            );
        }

        return (new FastExcel($data))->download('withdraw_requests_of_delivery_men.xlsx');
    }
}
