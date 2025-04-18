<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Exports\EmployeeListExport;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;


class EmployeeController extends Controller
{
    public function __construct(
        private Admin $admin,
        private AdminRole $admin_role,
    ){

    }

    public function add_new()
    {
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.add-new', compact('rls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'image' => 'required',
            'email' => 'required|email|unique:admins',
            'password'=>'required',
            'phone'=>'required'

        ], [
            'name.required' => translate('role_name_is_required').'!',
            'role_name.required' => translate('role_id_is_required').'!',
            'email.required' => translate('email_id_is_Required').'!',
            'image.required' => translate('image_is_required').'!',

        ]);

        if ($request->role_id == 1) {
            Toastr::warning(translate('access_denied'));
            return back();
        }
        $identity_images = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                array_push($identity_images, ImageManager::upload('admin/', 'webp', $img));
            }
            $identity_images = json_encode($identity_images);
        } else {
            $identity_images = json_encode([]);
        }

        DB::table('admins')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'identify_type' => $request->identify_type,
            'identify_number' => $request->identify_number,
            'identify_image' => $identity_images,
            'password' => bcrypt($request->password),
            'status'=>1,
            'image' => ImageManager::upload('admin/', 'webp', $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('employee_added_successfully'));
        return redirect()->route('admin.employee.list');
    }

    function list(Request $request)
    {
        $employee_roles = $this->admin_role->whereNotIn('id', [1])->get();
        $search = $request['search'];
        $key = explode(' ', $request['search']);
        $em = Admin::with(['role'])->whereNotIn('id', [1])
                    ->when($search!=null, function($query) use($key){
                        foreach ($key as $value) {
                            $query->where('name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                        }
                    })->when($request->has('employee_role_id') && $request->employee_role_id !='all', function($query)use($request){
                        $query->where('admin_role_id',$request->employee_role_id);
                    })
                    ->paginate(Helpers::pagination_limit());
        return view('admin-views.employee.list', compact('em','search','employee_roles'));
    }

    public function edit($id)
    {
        $e = Admin::where(['id' => $id])->first();
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.edit', compact('rls', 'e'));
    }
    /**
     *  start employee details/view
     */
    public function view(Request $request){
        $employee =$this->admin->with('role')->where(['id' => $request->id])->first();
        return view('admin-views.employee.view', compact('employee'));
    }
    /**
     *  end employee details/view
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:admins,email,'.$id,
        ], [
            'name.required' => translate('role_name_is_required').'!',
        ]);

        if ($request->role_id == 1) {
            Toastr::warning(translate('access_denied'));
            return back();
        }

        $e = Admin::find($id);
        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {
            if (strlen($request['password']) < 7) {
                Toastr::warning(translate('password_length_must_be_8_character.'));
                return back();
            }
            $pass = bcrypt($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = ImageManager::update('admin/', $e['image'], 'webp', $request->file('image'));
        }

        if (!empty($request->file('identity_image'))) {
            if($e['identity_image']){
                foreach (json_decode($e['identity_image'], true) as $img) {
                    if (Storage::disk('public')->exists('admin/' . $img)) {
                        Storage::disk('public')->delete('admin/' . $img);
                    }
                }
            }
            $img_keeper = [];
            foreach ($request->identity_image as $img) {
                array_push($img_keeper, ImageManager::upload('admin/', 'webp', $img));
            }
            $identity_image = json_encode($img_keeper);
        } else {
            $identity_image = $e['identify_image'];
        }

        DB::table('admins')->where(['id' => $id])->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'admin_role_id' => $request->role_id,
            'password' => $pass,
            'image' => $e['image'],
            'identify_image' => $identity_image,
            'identify_type' => $request->identify_type,
            'identify_number' => $request->identify_number,
            'updated_at' => now(),
        ]);

        Toastr::success(translate('employee_updated_successfully'));
        return redirect()->route('admin.employee.list');
    }
    public function status(Request $request)
    {
        $employee = Admin::find($request->id);
        $employee->status = $request->status ?? 0;
        $employee->save();

        if($request->ajax())
        {
            return response()->json([
                'status' => 'success',
                'message' => translate('employee_status_updated'),
            ]);
        }

        Toastr::success(translate('employee_status_updated'));
        return back();
    }
     /**
     * employee list export
     */

     public function export(Request $request){
        $key = $request->search;
        $employees = Admin::with(['role'])->whereNotIn('id', [1])
                    ->when($request->has('search'), function($query) use($key){
                        $key = explode(' ', $key);
                        foreach ($key as $value) {
                            $query->where('name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                        }
                    })
                    ->when($request->has('role') && $request->role !='all', function($query)use($request){
                        $query->where('admin_role_id',$request->role);
                    })
                    ->get();
        $active = $employees->where('status',1)->count();
        $inactive = $employees->where('status',0)->count();
        $filter = $request->has('role') &&  $request->role != 'all' ? $this->admin_role->where('id',$request->role)->value('name') : 'all';
        $data = [
            'employees' => $employees,
            'search' => $key,
            'active' => $active,
            'inactive' => $inactive,
            'filter' => $filter
        ];
        return Excel::download(new EmployeeListExport($data), 'Employees.xlsx');
    }
}
