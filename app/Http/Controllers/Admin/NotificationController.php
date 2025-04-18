<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\Translation;
use App\Models\NotificationMessage;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationMessage $notification_message,
    ){

    }
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $notifications = Notification::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $notifications = new Notification();
        }
        $notifications = $notifications->where('sent_to', 'customer')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.notification.index', compact('notifications','search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = new Notification;
        $notification->title = $request->title;
        $notification->description = $request->description;

        if ($request->has('image')) {
            $notification->image = ImageManager::upload('notification/', 'webp', $request->file('image'));
        } else {
            $notification->image = 'null';
        }

        $notification->status             = 1;
        $notification->notification_count = 1;
        $notification->save();

        try {
            Helpers::send_push_notif_to_topic($notification);
        } catch (\Exception $e) {
            Toastr::warning(translate('push_notification_failed'));
        }

        Toastr::success(translate('notification_sent_successfully'));
        return back();
    }

    public function edit($id)
    {
        $notification = Notification::find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $request->has('image')? ImageManager::update('notification/', $notification->image, 'webp', $request->file('image')):$notification->image;
        $notification->save();

        Toastr::success(translate('notification_updated_successfully'));
        return redirect('/admin/notification/add-new');
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::find($request->id);
            $notification->status = $request->status;
            $notification->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function resendNotification(Request $request){
        $notification = Notification::find($request->id);

        $data = array();
        try {
            Helpers::send_push_notif_to_topic($notification);
            $notification->notification_count += 1;
            $notification->save();

            $data['success'] = true;
            $data['message'] = translate("push_notification_successfully");
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = translate("push_notification_failed");
        }

        return $data;
    }

    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
        ImageManager::delete('/notification/' . $notification['image']);
        $notification->delete();
        return response()->json();
    }

    public function push_notification()
    {
        /** for customer */
        $user_type_customer = $this->notification_message->where('user_type','customer')->get();
        $array_for_customer_message_key = [
            'order_pending_message',
            'order_confirmation_message',
            'order_processing_message',
            'out_for_delivery_message',
            'order_delivered_message',
            'order_returned_message',
            'order_failed_message',
            'order_canceled',
            'order_refunded_message',
            'refund_request_canceled_message',
            'message_from_delivery_man',
            'message_from_seller',
            'fund_added_by_admin_message',
        ];
            foreach ($array_for_customer_message_key as $key=>$value ){
                $key_check = $user_type_customer->where('key',$value)->first();
                if($key_check == null){
                    DB::table('notification_messages')->insert([
                        'user_type'=>'customer',
                        'key'=>$value,
                        'message'=>'customize your'.' '.str_replace('_',' ', $value).' '.'message',
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ]);
                }
            }
            foreach ($user_type_customer as $key=>$value ){
                if (!in_array($value['key'], $array_for_customer_message_key)) {
                    $value->delete();
                }
            }
            $user_type_customer = $this->notification_message->where('user_type','customer')->get();

        /**end for customer*/
        $user_type_seller = $this->notification_message->where('user_type','seller')->get();

        $array_for_seller_message_key = [
            'new_order_message',
            'refund_request_message',
            'order_edit_message',
            'withdraw_request_status_message',
            'message_from_customer',
            'message_from_delivery_man',
            'delivery_man_assign_by_admin_message',
            'order_delivered_message',
            'order_canceled',
            'order_refunded_message',
            'refund_request_canceled_message',
            'refund_request_status_changed_by_admin',

        ];
        foreach ($array_for_seller_message_key as $key=>$value ){
            $key_check = $user_type_seller->where('key',$value)->first();
            if($key_check == null){
                DB::table('notification_messages')->insert([
                    'user_type'=>'seller',
                    'key'=>$value,
                    'message'=>'customize your'.' '.str_replace('_',' ', $value).' '.'message',
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
            }
        }
        foreach ($user_type_seller as $key=>$value ){
            if (!in_array($value['key'], $array_for_seller_message_key)) {
                $value->delete();
            }
        }
        $user_type_seller = $this->notification_message->where('user_type','seller')->get();
        /**end for seller*/
        /**end for delivery man*/
        $user_type_delivery_man = $this->notification_message->where('user_type','delivery_man')->get();
        $array_for_delivery_man_message_key = [
            'new_order_assigned_message',
            'expected_delivery_date',
            'delivery_man_charge',
            'order_canceled',
            'order_rescheduled_message',
            'order_edit_message',
            'message_from_seller',
            'message_from_admin',
            'message_from_customer',
            'cash_collect_by_admin_message',
            'cash_collect_by_seller_message',
            'withdraw_request_status_message',

        ];
        foreach ($array_for_delivery_man_message_key as $key=>$value ){
            $key_check = $user_type_delivery_man->where('key',$value)->first();
            if($key_check == null){
                DB::table('notification_messages')->insert([
                    'user_type'=>'delivery_man',
                    'key'=>$value,
                    'message'=>'customize your'.' '.str_replace('_',' ', $value).' '.'message',
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
            }
        }
        foreach ($user_type_delivery_man as $key=>$value ){
            if (!in_array($value['key'], $array_for_delivery_man_message_key)) {
                $value->delete();
            }
        }
        $user_type_delivery_man = $this->notification_message->where('user_type','delivery_man')->get();
        /**end for delivery man*/
        $language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first();
        return view('admin-views.notification.push-notification',compact('language','user_type_customer','user_type_seller','user_type_delivery_man'));
    }
    public function update_push_notification(Request $request){
        $push_notificaton = $this->notification_message->where('user_type',$request->type)->get();
        foreach($push_notificaton as $key=>$value){
            $message = 'message'.$value['id'];
            $status = 'status'.$value['id'];
            $lang = 'lang'.$value['id'];
            DB::table('notification_messages')->where(['id'=>$value['id'],'user_type'=>$request->type])->update([
                'message'=>$request->$message[array_search('en', $request->$lang)],
                'status'=>$request->$status ?? false,
                'updated_at'=>now(),
            ]);
            /* langauage wise message*/
            foreach ($request->$lang as $index => $val) {
                if ($request->$message[$index] && $val != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Models\NotificationMessage',
                            'translationable_id' => $value['id'],
                            'locale' => $val,
                            'key' => $value['key']
                        ],
                        ['value' => $request->$message[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('update_successfully'));
        return back();
    }
}
