<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use App\Model\Notification;
use App\Model\NotificationSeen;
use App\Model\Seller;
use App\Model\Shop;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request, $type)
    {
        $shop = Shop::where('seller_id', auth('seller')->id())->first();
        $shop_id = $shop->id;

        if ($type == 'delivery-man') {
            $last_chat = Chatting::where('seller_id', auth('seller')->id())
                ->whereNotNull(['delivery_man_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['seller_id'=> auth('seller')->id(), 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->where('delivery_man_id', $last_chat->delivery_man_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                    ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                    ->where('chattings.seller_id', auth('seller')->id())
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('delivery_man_id');

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop'));
            }

        }elseif($type == 'customer'){
            $last_chat = Chatting::where('shop_id', $shop_id)
                ->whereNotNull(['user_id', 'seller_id'])
                ->orderBy('created_at', 'DESC')
                ->first();

            if (isset($last_chat)) {
                Chatting::where(['shop_id' => $shop_id, 'user_id' => $last_chat->user_id])->update([
                    'seen_by_seller' => 1
                ]);

                $chattings = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                    ->where('chattings.shop_id', $shop_id)
                    ->where('user_id', $last_chat->user_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get();

                $chattings_user = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                    ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image', 'users.phone')
                    ->where('chattings.shop_id', $shop_id)
                    ->orderBy('chattings.created_at', 'desc')
                    ->get()
                    ->unique('user_id');

                return view('seller-views.chatting.chat', compact('chattings', 'chattings_user', 'last_chat', 'shop'));
            }
        }

        return view('seller-views.chatting.chat', compact('last_chat', 'shop'));
    }

    /**
     * ajax request - get message by delivery man and customer
     */
    public function ajax_message_by_user(Request $request)
    {
        if ($request->has('delivery_man_id')) {
            Chatting::where(['seller_id' => auth('seller')->id(), 'delivery_man_id' => $request->delivery_man_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.seller_id', auth('seller')->id())
                ->where('chattings.delivery_man_id', $request->delivery_man_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }
        elseif ($request->has('user_id')) {
            $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

            Chatting::where(['seller_id' => auth('seller')->id(), 'user_id' => $request->user_id])
                ->update([
                    'seen_by_seller' => 1
                ]);

            $sellers = Chatting::join('users', 'users.id', '=', 'chattings.user_id')
                ->select('chattings.*', 'users.f_name', 'users.l_name', 'users.image')
                ->where('chattings.shop_id', $shop_id)
                ->where('chattings.user_id', $request->user_id)
                ->orderBy('created_at', 'ASC')
                ->get();

        }

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage
     */
    public function ajax_seller_message_store(Request $request)
    {
        if ($request->image == null && $request->message == '') {
            return response()->json(translate('type_something').'!', 403);
        }

        $attachment = [] ;
        if ($request->file('image')) {
            $validator = Validator::make($request->all(), [
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:6000'
            ]);
            if ($validator->fails()) {
                return response()->json(translate('The_file_must_be_an_image').'!', 403);
            }
            foreach ($request->image as $key=>$value) {
                $image_name = ImageManager::upload('chatting/', 'webp', $value);
                $attachment[] = $image_name;
            }
        }

        $shop_id = Shop::where('seller_id', auth('seller')->id())->first()->id;

        $message = $request->message;
        $time = now();

        $message_form = Seller::find($shop_id);
        if ($request->has('delivery_man_id')) {

            Chatting::create([
                'delivery_man_id' => $request->delivery_man_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'attachment' =>json_encode($attachment),
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'created_at' => now(),
            ]);

            $delivery_man = DeliveryMan::find($request->delivery_man_id);
            Helpers::chatting_notification('message_from_seller','delivery_man',$delivery_man,$message_form);

        }elseif ($request->has('user_id')) {
            Chatting::create([
                'user_id' => $request->user_id,
                'seller_id' => auth('seller')->id(),
                'shop_id' => $shop_id,
                'message' => $request->message,
                'attachment' =>json_encode($attachment),
                'sent_by_seller' => 1,
                'seen_by_seller' => 1,
                'seen_by_customer' => 0,
                'created_at' => now(),
            ]);

            $customer = User::find($request->user_id);
            Helpers::chatting_notification('message_from_seller','customer',$customer,$message_form);

        }

        return response()->json(['message' => $message, 'time' => $time, 'image'=>$attachment]);
    }

    public function ajax_seller_notification_view(Request $request)
    {
        $request->validate([
            'id'=>'required',
        ]);

        $shop = Shop::where('seller_id', auth('seller')->id())->first();
        $company_name = BusinessSetting::where(['type'=>'company_name'])->first()->value ?? '';

        NotificationSeen::updateOrInsert(['seller_id' => auth('seller')->id(), 'notification_id' => $request->id],[
            'created_at' => Carbon::now(),
        ]);

        $data = Notification::where(['id' => $request->id])->first();

        $notification_count = Notification::whereBetween('created_at', [auth('seller')->user()->created_at, Carbon::now()])
                                            ->where('sent_to', 'seller')
                                            ->whereDoesntHave('notification_seen_by')->count();

        return response()->json([
            'notification_count' => $notification_count,
            'view' => view('seller-views.partials._system-notification-modal-data', compact('shop', 'company_name', 'data'))->render(),
        ]);
    }

}
