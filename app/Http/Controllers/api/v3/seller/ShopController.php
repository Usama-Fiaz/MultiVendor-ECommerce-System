<?php

namespace App\Http\Controllers\api\v3\seller;

use Carbon\Carbon;
use App\Model\Shop;
use App\CPU\Helpers;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;
use App\Model\NotificationSeen;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function vacation_add(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->vacation_status = $request->vacation_status;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        return response()->json(['status' => true], 200);
    }

    public function temporary_close(Request $request){
        $seller = $request->seller;

        $shop = Shop::where('seller_id',$seller->id)->first();
        $shop->temporary_close = $request->status;
        $shop->save();

        return response()->json(['status' => true], 200);
    }


    public function notification_index(Request $request){

        $seller = $request->seller;

        $notification_data = Notification::whereBetween('created_at', [$seller->created_at, Carbon::now()])->where('sent_to', 'seller');

        $notification = $notification_data->with('notification_seen_by')
                            ->select('id', 'title', 'description', 'image', 'created_at')
                            ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $notification->map(function($data){
            $data['notification_seen_status'] = $data->notification_seen_by == null ? 0 : 1;
            unset($data->notification_seen_by);
        });

        return [
            'total_size' => $notification->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'new_notification' => $notification_data->whereDoesntHave('notification_seen_by')->count(),
            'notification' => $notification->items()
        ];
    }

    public function seller_notification_view(Request $request){

        $seller = $request->seller;

        NotificationSeen::updateOrInsert(['seller_id' => $seller->id, 'notification_id' => $request->id],[
            'created_at' => Carbon::now(),
        ]);

        $notification_count = Notification::whereBetween('created_at', [$seller->created_at, Carbon::now()])
                                ->where('sent_to', 'seller')
                                ->whereDoesntHave('notification_seen_by')
                                ->count();
                                
        return [
            'notification_count' => $notification_count,
        ];
    }


}
