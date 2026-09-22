<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use App\Models\Hotel;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Staff::with(['company', 'branch', 'store']);
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $checkbox = '<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                    return $checkbox;
                })
                ->addColumn('joining_date', function ($row) {
                    return date('d-m-Y', strtotime($row->joining_date));
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . route('admin.employee.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    return ActionButtons::actions($data);
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }

        $title = "Staff/Employee Setup";
        return view('hrm.employee.index', compact('title'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            $branches = Branch::where('company_id', request('company_id'))->orderBy('name')->get();
            return response()->json(['status' => 'success', 'branches' => $branches]);
        }

        $title = 'Add New Staff/Employee';
        $companies = Company::orderBy('name')->get();
        $hotels = Hotel::orderBy('id','desc')->where('status','Active')->get();
        $branches = Branch::where('company_id', Auth::user()->company_id)->orderBy('name')->get();
        return view('hrm.employee.create', compact('title', 'companies', 'branches','hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'code' => 'required',
            'name' => 'required',
            'type' => 'required',
            'joining_date' => 'required',
        ]);

         if (!is_null($request->phone)) {
            $request->validate([
                'phone' => 'unique:users,phone',
            ]);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'company_id' => $request->company_id ?? Auth::user()->company_id,
                'role' => 1,
                'role_status' => 4,
                'name' => $request->name,
                'user_name' => $request->phone,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => 1,
                'password' => Hash::make($request->phone),
                'created_by' => Auth::user()->id,
            ]);

            Staff::create([
            'company_id' => $request->company_id ?? Auth::user()->company_id,
            'branch_id' => $request->branch_id,
            'hotel_id' => $request->hotel_id,
            'user_id' => $user->id,
            'code' => $request->code,
            'name' => $request->name,
            'currency_code' => $request->currency_code,
            'short_name' => $request->short_name,
            'designation' => $request->designation,
            'joining_date' => date('Y-m-d', strtotime($request->joining_date)),
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'ac_no' => $request->ac_no,
            'ac_branch' => $request->ac_branch,
            'address' => $request->address,
            'basic_salary' => $request->basic_salary,
            'house_rent' => $request->house_rent,
            'medical_allowance' => $request->medical_allowance,
            'others' => $request->others,
            'deducted' => $request->deducted,
            'increment_percent' => $request->increment_percent,
            'increment_amount' => $request->increment_amount,
            'total_salary' => $request->total_salary,
            'type' => $request->type,
            'created_by' => Auth::user()->id,
            ]);

             $role = Role::findByName('staff');
            $user->assignRole($role);
        });

        return redirect()->route('admin.employee.index')->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax() && request('status')) {
            $data = Staff::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        if (request()->ajax()) {
            $branches = Branch::where('company_id', request('company_id'))->orderBy('name')->get();
            return response()->json(['status' => 'success', 'branches' => $branches]);
        }

        $title = 'Update Staff/Employee';
        $data = Staff::findOrFail($id);
        $link = route('admin.employee.update', $id);
        $companies = Company::orderBy('name')->get();
        $hotels = Hotel::orderBy('id','desc')->where('status','Active')->get();
        $branches = Branch::where('company_id', $data->company_id)->orderBy('name')->get();
        return view('hrm.employee.edit', compact('title', 'data', 'link', 'companies', 'branches', 'id','hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'branch_id' => 'required',
            'code' => 'required',
            'type' => 'required',
            'name' => 'required',
            'joining_date' => 'required',
        ]);
        DB::transaction(function () use ($request,$id) {
             $data = Staff::findOrFail($id);

            $user = User::where('phone',$request->phone)->first();
            if($user){
               $userid = $user->id;
            }else{
            $user = User::create([
                            'company_id' => $request->company_id ?? Auth::user()->company_id,
                            'role' => 1,
                            'name' => $request->name,
                            'user_name' => $request->phone,
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'status' => 1,
                            'password' => Hash::make($request->phone),
                            'created_by' => Auth::user()->id,
                        ]); 
               $userid = $user->id;         
            }
            $user->update([
                'name' => $request->name,
                'user_name' => $request->phone,
                'email' => $request->email,
                'phone' => $request->phone
            ]);
           
              $role = Role::findByName('staff');
            $user->assignRole($role);

            $data->update([
                'company_id' => $request->company_id ?? Auth::user()->company_id,
                'branch_id' => $request->branch_id,
                'hotel_id' => $request->hotel_id,
                'user_id' => $userid,
                'role_status' => 4,
                'code' => $request->code,
                'name' => $request->name,
                'currency_code' => $request->currency_code,
                'short_name' => $request->short_name,
                'designation' => $request->designation,
                'joining_date' => date('Y-m-d', strtotime($request->joining_date)),
                'email' => $request->email,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'ac_no' => $request->ac_no,
                'ac_branch' => $request->ac_branch,
                'address' => $request->address,
                'basic_salary' => $request->basic_salary,
                'house_rent' => $request->house_rent,
                'medical_allowance' => $request->medical_allowance,
                'others' => $request->others,
                'deducted' => $request->deducted,
                'increment_percent' => $request->increment_percent,
                'increment_amount' => $request->increment_amount,
                'total_salary' => $request->total_salary,
                'type' => $request->type,
                'updated_by' => Auth::user()->id,
            ]);

        });

       
        return redirect()->route('admin.employee.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = Staff::onlyTrashed()->findOrFail($id);
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = Staff::onlyTrashed()->findOrFail($id);
                $data->forceDelete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = Staff::onlyTrashed()->findOrFail($id);
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = Staff::findOrFail($id);
                $data->update(['deleted_by' => Auth::user()->id]);
                $data->delete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = Staff::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
