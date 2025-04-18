<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Chatting;
use App\Model\DeliveryMan;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;
use Illuminate\Support\Facades\Validator;

class ChattingController extends Controller
{
    /**
     * chatting list
     */
    public function chat(Request $request)
    {
        $last_chat = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')->where('admin_id', 0)
            ->whereNotNull(['delivery_man_id', 'admin_id'])
            ->orderBy('chattings.created_at', 'DESC')
            ->first();

        if (isset($last_chat)) {
            Chatting::where(['admin_id'=>0, 'delivery_man_id'=> $last_chat->delivery_man_id])->update([
                'seen_by_admin' => 1
            ]);


            $chattings = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
                ->where('chattings.admin_id', 0)
                ->where('delivery_man_id', $last_chat->delivery_man_id)
                ->orderBy('chattings.created_at', 'desc')
                ->get();

            $chattings_user = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
                ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image', 'delivery_men.phone')
                ->where('chattings.admin_id', 0)
                ->orderBy('chattings.created_at', 'desc')
                ->get()
                ->unique('delivery_man_id');

            return view('admin-views.delivery-man.chat', compact('chattings', 'chattings_user', 'last_chat'));
        }

        return view('admin-views.delivery-man.chat', compact('last_chat'));
    }

    /**
     * ajax request - get message by delivery man
     */
    public function ajax_message_by_delivery_man(Request $request)
    {

        Chatting::where(['admin_id' => 0, 'delivery_man_id' => $request->delivery_man_id])
            ->update([
                'seen_by_admin' => 1
            ]);

        $sellers = Chatting::join('delivery_men', 'delivery_men.id', '=', 'chattings.delivery_man_id')
            ->select('chattings.*', 'delivery_men.f_name', 'delivery_men.l_name', 'delivery_men.image')
            ->where('chattings.admin_id', 0)
            ->where('chattings.delivery_man_id', $request->delivery_man_id)
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($sellers);
    }

    /**
     * ajax request - Store massage for deliveryman
     */
    public function ajax_admin_message_store(Request $request)
    {
        if ($request->image == null && $request->message == '') {
            Toastr::warning(translate('Type_Something'));
            return response()->json(['status'=>0,'message' => translate('Type_Something')]);
        }

        $image = [] ;
        if ($request->file('image')) {

            $validator = Validator::make($request->all(), [
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:6000'
            ]);
            if ($validator->fails()) {
                return response()->json(translate('The_file_must_be_an_image').'!', 403);
            }

            foreach ($request->image as $key=>$value) {
                $image_name = ImageManager::upload('chatting/', 'webp', $value);
                $image[] = $image_name;
            }
        }

        $message = $request->message;
        $time = now();

        Chatting::create([
            'delivery_man_id' => $request->delivery_man_id,
            'admin_id' => 0,
            'message' => $request->message,
            'attachment' => json_encode($image),
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
            'created_at' => now(),
        ]);
        $message_form = 'admin';
        $delivery_man = DeliveryMan::find($request->id);
        Helpers::chatting_notification('message_from_admin','delivery_man',$delivery_man,$message_form);

        return response()->json(['status'=>1,'message' => $message, 'time' => $time, 'image' => $image]);
    }
}
