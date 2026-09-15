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

class StaffPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {

        if (request()->ajax()) {
            $model = DB::table('hrm_payments as pmnt')
                ->leftJoin('coa_setups as c', 'c.id', '=', 'pmnt.payment_head_id')
                ->leftJoin('staff as stf', 'stf.id', '=', 'pmnt.employee_id')
                ->select(
                    'pmnt.id',
                    'c.head_name',
                    'stf.name as staff_name',
                    'pmnt.payment_month',
                    'pmnt.payment_year',
                    'pmnt.payment_amount',
                    'pmnt.payment_date',
                    'pmnt.status',
                    'pmnt.remarks'
                )->orderBy('id','desc');

            return DataTables::of($model)
    
                ->editColumn('payment_month', function ($row) {
                
                    return date('F', mktime(0, 0, 0, $row->payment_month, 1));
                })

                ->editColumn('payment_date', function ($row) {
                    return date('d M, Y', strtotime($row->payment_date));
                })

                ->editColumn('payment_amount', function ($row) {
                    return number_format($row->payment_amount, 2);
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'Payment') {
                        return '<span class="badge bg-info">Payment</span>';
                    }

                    return '<span class="badge bg-success">Advance</span>';
                })

                ->addColumn('actions', function ($row) {
                    $btn = '';

                    if(auth()->user()->can('admin.staff-payment.show')){
                        $btn .= '<a href="'.route('admin.staff-payment.show',$row->id).'"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if(auth()->user()->can('admin.staff-payment.edit')){
                        $btn .= '<a href="'.route('admin.staff-payment.edit',$row->id).'"
                            class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>';
                    }

                    if(auth()->user()->can('admin.staff-payment.destroy')){
                        $btn .= '<button
                            class="btn btn-sm btn-danger link-delete"
                            data-url="'.route('admin.staff-payment.destroy',$row->id).'">
                            <i class="fas fa-trash"></i>
                        </button>';
                    }

                    return '<div class="btn-group">'.$btn.'</div>';
                })

                ->rawColumns([
                    'status',
                    'actions'
                ])

                ->make(true);
        }

        return view('hrm.staff-payment.index');
    }

  public function create()
    {
        $coas = DB::table('coa_setups')
            ->where('parent_id', 4)
            ->orderBy('head_name')
            ->get();
        $staffs = DB::table('staff')
                 ->get();

        return view('hrm.staff-payment.create', compact('coas','staffs'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'payment_head_id'    => 'required',
            'payment_month'  => 'required|integer|between:1,12',
            'payment_year'   => 'required|digits:4',
            'payment_amount'    => 'required|numeric|min:0',
            'payment_date'      => 'required|date',
            'status'         => 'nullable|string',
            'remarks'        => 'nullable|string',
        ]);
        DB::table('hrm_payments')->insert([
            'payment_head_id'   => $request->payment_head_id,
            'employee_id'   => $request->employee_id,
            'payment_month' => $request->payment_month,
            'payment_year'  => $request->payment_year,
            'hours'   => $request->hours,
            'hour_rate'   => $request->hour_rate,
            'payment_amount'        => $request->payment_amount,
            'payment_date'  => $request->payment_date,
            'remarks'       => $request->remarks,
            'status'        => $request->status,
            'created_by'    => auth()->id(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()
            ->route('admin.staff-payment.index')
            ->withSuccessMessage('Payment added successfully.');
    }

    public function edit($id)
    {
        $payment=DB::table('hrm_payments')->find($id);

        $coas = DB::table('coa_setups')
            ->where('parent_id', 4)
            ->orderBy('head_name')
            ->get();
        $staffs = DB::table('staff')
                 ->get();

        return view('hrm.staff-payment.edit', compact('payment', 'coas','staffs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_head_id'   => 'required',
            'payment_month' => 'required|integer|between:1,12',
            'payment_year'  => 'required|digits:4',
            'payment_amount'        => 'required|numeric|min:0',
            'payment_date'  => 'required|date',
            'status'        => 'nullable|string',
            'remarks'       => 'nullable|string|max:1000',
        ]);
      
        DB::table('hrm_payments')
            ->where('id', $id)
            ->update([
                'employee_id'   => $request->employee_id,
                'payment_head_id'   => $request->payment_head_id,
                'payment_month' => $request->payment_month,
                'payment_year'  => $request->payment_year,
                'hours'   => $request->hours,
                'hour_rate'   => $request->hour_rate,
                'payment_amount'   => $request->payment_amount,
                'payment_date'  => $request->payment_date,
                'remarks'       => $request->remarks,
                'status'        => $request->status,
                'updated_by'    => auth()->id(),
                'updated_at'    => now(),
            ]);

        return redirect()
            ->route('admin.staff-payment.index')
            ->withSuccessMessage('Payment updated successfully.');
    }

    
}