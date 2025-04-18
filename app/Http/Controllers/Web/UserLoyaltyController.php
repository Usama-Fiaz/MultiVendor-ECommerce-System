<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LoyaltyPointTransaction;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use App\CPU\CustomerManager;
use Illuminate\Support\Facades\Mail;

class UserLoyaltyController extends Controller
{

    public function index(Request $request)
    {
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');
        $wallet_status = Helpers::get_business_settings('wallet_status');

        if($loyalty_point_status==1)
        {
            $total_loyalty_point = auth('customer')->user()->loyalty_point;

        $loyalty_point_list = LoyaltyPointTransaction::where('user_id',auth('customer')->id())
                            ->when($request->has('type'), function ($query) use ($request) {
                                $query->when($request->type == 'order_place', function ($query) {
                                    $query->where('transaction_type', 'order_place');
                                })->when($request->type == 'point_to_wallet', function ($query) {
                                    $query->where('transaction_type', 'point_to_wallet');
                                })->when($request->type == 'refund_order', function ($query) {
                                    $query->where(['transaction_type' => 'refund_order']);
                                });
                            })
                            ->latest()
                            ->paginate(15);
        return view(VIEW_FILE_NAMES['user_loyalty'],compact('total_loyalty_point','loyalty_point_status','wallet_status','loyalty_point_list'));
        }else{
            Toastr::warning(\App\CPU\translate('access_denied!'));
            return back();
        }
    }

    public function loyalty_exchange_currency(Request $request)
    {
        $wallet_status = Helpers::get_business_settings('wallet_status');
        $loyalty_point_status = Helpers::get_business_settings('loyalty_point_status');

        if($wallet_status != 1 || $loyalty_point_status !=1)
        {
            Toastr::warning(\App\CPU\translate('transfer_loyalty_point_to_currency_is_not_possible_at_this_moment!'));
            return back();
        }

        $request->validate([
            'point' => 'required|integer|min:1'
        ]);


        $user = auth('customer')->user();
        if($request->point < (int)Helpers::get_business_settings('loyalty_point_minimum_point')
            || $request->point > $user->loyalty_point)
        {
            Toastr::warning(\App\CPU\translate('exchange_requirements_not_matched'));
            return back();
        }

        $wallet_transaction = CustomerManager::create_wallet_transaction($user->id,$request->point,'loyalty_point','point_to_wallet');
        CustomerManager::create_loyalty_point_transaction($user->id, $wallet_transaction->transaction_id, $request->point, 'point_to_wallet');

        try
        {

            Mail::to($user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));



        }catch(\Exception $ex){
            info($ex);
            //dd($ex);
        }

        Toastr::success(\App\CPU\translate('point_to_wallet_transfer_successfully'));
        return back();


    }
    public function ajax_loyalty_currency_amount(Request $request){
        return response()->json(Helpers::currency_converter($request->amount));
    }
}
