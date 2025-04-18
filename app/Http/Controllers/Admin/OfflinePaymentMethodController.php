<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\OfflinePaymentMethod;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class OfflinePaymentMethodController extends Controller
{

    protected OfflinePaymentMethod $OfflinePaymentMethod;

    public function __construct(OfflinePaymentMethod $OfflinePaymentMethod)
    {
        $this->OfflinePaymentMethod = $OfflinePaymentMethod;
    }

    public function index(Request $request)
    {
        if (request()->has('status') && (request('status') == 'active' || request('status') == 'inactive'))
        {
            $methods = OfflinePaymentMethod::when(request('status') == 'active', function($query){
                return $query->where('status', 1);
            })->when(request('status') == 'inactive', function($query){
                return $query->where('status', 0);
            })->latest()->paginate(10);
        } else if(request()->has('search')) {
            $methods = OfflinePaymentMethod::where(function ($query) {
                $query->orWhere('method_name', 'like', "%".request('search')."%");
            })->latest()->paginate(10);
        }else{
            $methods = OfflinePaymentMethod::latest()->paginate(10);
        }

        return view('admin-views.business-settings.payment-method.offline-payment.index', compact('methods'));
    }


    public function create()
    {
        return view('admin-views.business-settings.payment-method.offline-payment.new');
    }


    public function store(Request $request)
    {
        $request->validate([
            'method_name' => 'required',
            'input_name' => 'required|array',
            'input_data' => 'required|array',
            'is_required' => '',
        ]);

        $method_fields = [];
        if($request->has('input_name'))
        {
            foreach ($request->input_name as $key => $field_name) {
                $method_fields[] = [
                    'input_name' => strtolower(str_replace("'", '', preg_replace('/[^a-zA-Z0-9\']/', '_', $request->input_name[$key]))),
                    'input_data' => $request->input_data[$key],
                ];
            }
        }

        $method_informations = [];
        if($request->has('customer_input'))
        {
            foreach ($request->customer_input as $key => $field_name) {
                $input_key = strtolower(str_replace("'", '', preg_replace('/[^a-zA-Z0-9\']/', '_', $request->customer_input[$key])));

                $keyExists = false;
                foreach ($method_informations as $info) {
                    if ($info['customer_input'] === $input_key) {
                        $keyExists = true;
                        break;
                    }
                }

                if (!$keyExists) {
                    if (!array_key_exists($request->customer_input[$key], $method_informations)) {
                        $method_informations[] = [
                            'customer_input' => $input_key,
                            'customer_placeholder' => $request->customer_placeholder[$key],
                            'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
                        ];
                    }
                }else {
                    if($request->ajax()) {
                        return response()->json([
                            'status' => 0,
                            'message' => translate('information_Input_Field_Name_must_be_unique'),
                            'redirect_url' => '',
                        ]);
                    }
                    Toastr::error(translate('information_Input_Field_Name_must_be_unique'));
                    return back();
                }
            }
        }

        $this->OfflinePaymentMethod->insert([
            'method_name' => $request->method_name,
            'method_fields' => json_encode($method_fields),
            'method_informations' => json_encode($method_informations),
            'created_at' => Carbon::now(),
        ]);

        if($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => translate('offline_payment_method_added_successfully'),
                'redirect_url' => route('admin.business-settings.payment-method.offline'),
            ]);
        }

        Toastr::success(translate('offline_payment_method_added_successfully'));
        return redirect()->route('admin.business-settings.payment-method.offline');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = $this->OfflinePaymentMethod->where('id', $id)->first();

        if($data)
        {
            return view('admin-views.business-settings.payment-method.offline-payment.edit', compact('data'));
        }else{
            Toastr::error(translate('offline_payment_method_not_found'));
            return redirect()->route('admin.business-settings.payment-method.offline');
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'method_name' => 'required',
            'input_name' => 'required|array',
            'input_data' => 'required|array',
            'is_required' => '',
        ]);

        $method_fields = [];
        if($request->has('input_name'))
        {
            foreach ($request->input_name as $key => $field_name) {
                $method_fields[] = [
                    'input_name' => strtolower(str_replace(' ', "_", $request->input_name[$key])),
                    'input_data' => $request->input_data[$key],
                ];
            }
        }

        $method_informations = [];
        if($request->has('customer_input'))
        {
            foreach ($request->customer_input as $key => $field_name) {

                $input_key = strtolower(str_replace("'", '', preg_replace('/[^a-zA-Z0-9\']/', '_', $request->customer_input[$key])));

                $keyExists = false;
                foreach ($method_informations as $info) {
                    if ($info['customer_input'] === $input_key) {
                        $keyExists = true;
                        break;
                    }
                }

                if (!$keyExists) {
                    $method_informations[] = [
                        'customer_input' => $input_key,
                        'customer_placeholder' => $request->customer_placeholder[$key],
                        'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
                    ];
                }else {
                    if($request->ajax()) {
                        return response()->json([
                            'status' => 0,
                            'message' => translate('information_Input_Field_Name_must_be_unique'),
                            'redirect_url' => '',
                        ]);
                    }
                    Toastr::error(translate('information_Input_Field_Name_must_be_unique'));
                    return back();
                }
            }
        }

        $this->OfflinePaymentMethod->where('id', $request->id)->update([
            'method_name' => $request->method_name,
            'method_fields' => json_encode($method_fields),
            'method_informations' => json_encode($method_informations),
            'created_at' => Carbon::now(),
        ]);

        if($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => translate('offline_payment_method_update_successfully'),
                'redirect_url' => route('admin.business-settings.payment-method.offline'),
            ]);
        }

        Toastr::success(translate('offline_payment_method_update_successfully'));
        return redirect()->route('admin.business-settings.payment-method.offline');
    }


    public function delete(Request $request)
    {
        $this->OfflinePaymentMethod->where('id', $request->id)->delete();

        Toastr::success(translate('offline_payment_method_delete_successfully'));
        return redirect()->route('admin.business-settings.payment-method.offline');
    }

    public function status(Request $request)
    {
        $data = $this->OfflinePaymentMethod->where('id', $request->id)->first();

        $success_status = 0;
        $message = '';

        if (isset($data)) {
            $data->update([
                'status' => $data->status == 1 ? 0:1,
            ]);
            $success_status = 1;
            $message = translate("status_updated_successfully");
        } else {
            $message = translate("status_update_failed");
        }

        return response()->json([
            'success_status' => $success_status,
            'message' => $message,
        ]);
    }
}
