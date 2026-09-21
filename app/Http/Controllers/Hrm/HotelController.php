<?php

namespace App\Http\Controllers\Hrm;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\CoaSetup;
use App\Models\coa_setups;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {

        if (request()->ajax()) {
            $model = DB::table('hrm_hotels')
                ->select(
                    'id',
                    'name',
                    'phone',
                    'address',
                    'entry_date',
                    'status',
                    'remarks'
                )->orderBy('id','desc');

            return DataTables::of($model)
    
               

                ->editColumn('entry_date', function ($row) {
                    return date('d M, Y', strtotime($row->entry_date));
                })

               

                ->editColumn('status', function ($row) {
                    if ($row->status == 'Inactive') {
                        return '<span class="badge bg-warning">Inactive</span>';
                    }

                    if ($row->status == 'Active') {
                        return '<span class="badge bg-success">Active</span>';
                    }
                })

                ->addColumn('actions', function ($row) {
                    $btn = '';

                    
                    if(auth()->user()->can('admin.hotel.edit')){
                        $btn .= '<a href="'.route('admin.hotel.edit',$row->id).'"
                            class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>';
                    }

                  

                    return '<div class="btn-group">'.$btn.'</div>';
                })

                ->rawColumns([
                    'status',
                    'actions'
                ])

                ->make(true);
        }

        return view('hrm.hotel.index');
    }

  public function create()
    {

        return view('hrm.hotel.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'phone'   => 'required',
            'address'    => 'required',
            'entry_date'      => 'required|date',
            'status'         => 'required|in:Active,Inactive',
            'remarks'        => 'nullable|string',
        ]);
        DB::table('hrm_hotels')->insert([
            'name'  => $request->name,
            'phone'   => $request->phone,
            'address'    => $request->address,
            'entry_date'  => $request->entry_date,
            'remarks'       => $request->remarks,
            'status'        => $request->status,
            'created_by'    => auth()->id(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()
            ->route('admin.hotel.index')
            ->withSuccessMessage('Hotel added successfully.');
    }

    public function edit($id)
    {
        $hotel = DB::table('hrm_hotels')->find($id);
        return view('hrm.hotel.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'name'  => 'required',
            'phone'   => 'required',
            'address'    => 'required',
            'entry_date'      => 'required|date',
            'status'         => 'required|in:Active,Inactive',
            'remarks'        => 'nullable|string',
        ]);
      
        DB::table('hrm_hotels')
            ->where('id', $id)
            ->update([
                'name'  => $request->name,
                'phone'   => $request->phone,
                'address'    => $request->address,
                'entry_date'  => $request->entry_date,
                'remarks'       => $request->remarks,
                'status'        => $request->status,
                'updated_by'    => auth()->id(),
                'updated_at'    => now(),
            ]);

        return redirect()
            ->route('admin.hotel.index')
            ->withSuccessMessage('Hotel updated successfully.');
    }

    
}