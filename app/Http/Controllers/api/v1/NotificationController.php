<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\NotificationSeen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function list(Request $request)
    {

        $notification_data = Notification::active()->where(['sent_to'=>'customer']);

        $notification = $notification_data->with('notification_seen_by')
            ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        return [
            'total_size' => $notification->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'new_notification' => $notification_data->whereDoesntHave('notification_seen_by')->count(),
            'notification' => $notification->items()
        ];
    }

    public function notification_seen(Request $request)
    {
        $user = $request->user();
        NotificationSeen::updateOrInsert(['user_id' => $user->id, 'notification_id' => $request->id],[
            'created_at' => Carbon::now(),
        ]);

        $notification_count = Notification::active()
            ->where('sent_to', 'customer')
            ->whereDoesntHave('notification_seen_by')
            ->count();

        return [
            'notification_count' => $notification_count,
        ];
    }
}
