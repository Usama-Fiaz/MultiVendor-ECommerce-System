<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\SupportTicket;
use App\Model\SupportTicketConv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use function App\CPU\translate;
use Brian2694\Toastr\Facades\Toastr;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $tickets = SupportTicket::orderBy('id', 'desc')
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('subject', 'like', "%{$value}%")
                            ->orWhere('type', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%");
                    }
                })
                ->when($request->has('priority') && $request['priority'] != 'all', function ($query) use ($request) {
                    $query->where('priority', $request['priority']);
                })
                ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                    $query->where('status', $request['status']);
                });
            $query_param = ['search' => $request['search']];
        } else {
            $tickets = SupportTicket::orderBy('id', 'desc')
                ->when($request->has('priority') && $request['priority'] != 'all', function ($query) use ($request) {
                    $query->where('priority', $request['priority']);
                })
                ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                    $query->where('status', $request['status']);
                });
        }
        $tickets = $tickets->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.support-ticket.view', compact('tickets', 'search'));
    }

    public function status(Request $request)
    {
        $currency = SupportTicket::find($request->id);
        $currency->status = $currency->status == 'open' ? 'close':'open';
        $currency->save();

        return response()->json([
            $currency
        ], 200);
    }

    public function single_ticket($id)
    {
        $supportTicket = SupportTicket::where('id', $id)->get();
        return view('admin-views.support-ticket.singleView', compact('supportTicket'));
    }

    public function replay_submit(Request $request)
    {
        if ($request->image == null && $request->replay == '') {
            Toastr::warning(translate('type_something').'!');
        }

        $image = [] ;
        if ($request->file('image')) {
            $validator = Validator::make($request->all(), [
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:6000'
            ]);
            if ($validator->fails()) {
                Toastr::warning(translate('The_file_must_be_an_image').'!');
            }

            foreach ($request->image as $key=>$value) {
                $image_name = ImageManager::upload('support-ticket/', 'webp', $value);
                $image[] = $image_name;
            }
        }

        $reply = [
            'admin_message' => $request->replay,
            'admin_id' => $request->adminId,
            'support_ticket_id' => $request->id,
            'attachment' =>json_encode($image),
            'created_at' => now(),
            'updated_at' => now()
        ];
        SupportTicketConv::insert($reply);
        return redirect()->back();
    }

}
