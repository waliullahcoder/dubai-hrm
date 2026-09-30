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

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {

        if (request()->ajax()) {
            $model = DB::table('hrm_expense as exp')
                ->leftJoin('coa_setups as c', 'c.id', '=', 'exp.expense_head_id')
                ->leftJoin('staff as s', 's.id', '=', 'exp.employee_id')
                ->select(
                    'exp.id',
                    's.name as employee_name',
                    'c.head_name',
                    'exp.expense_month',
                    'exp.expense_year',
                    'exp.expense_amount',
                    'exp.expense_date',
                    'exp.status',
                    'exp.remarks'
                )->orderBy('id','desc');

            return DataTables::of($model)
    
                ->editColumn('expense_month', function ($row) {
                
                    return date('F', mktime(0, 0, 0, $row->expense_month, 1));
                })

                ->editColumn('expense_date', function ($row) {
                    return date('d M, Y', strtotime($row->expense_date));
                })

                ->editColumn('expense_amount', function ($row) {
                    return number_format($row->expense_amount, 2);
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'Pending') {
                        return '<span class="badge bg-warning">Pending</span>';
                    }

                    if ($row->status == 'Approved') {
                        return '<span class="badge bg-info">Approved</span>';
                    }

                    return '<span class="badge bg-success">Paid</span>';
                })

                ->addColumn('actions', function ($row) {
                    $btn = '';

                    if(auth()->user()->can('admin.expense.show')){
                        $btn .= '<a href="'.route('admin.expense.show',$row->id).'"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>';
                    }

                    if(auth()->user()->can('admin.expense.edit')){
                        $btn .= '<a href="'.route('admin.expense.edit',$row->id).'"
                            class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>';
                    }

                    if(auth()->user()->can('admin.expense.destroy')){
                        $btn .= '<button
                            class="btn btn-sm btn-danger link-delete"
                            data-url="'.route('admin.expense.destroy',$row->id).'">
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

        return view('hrm.expense.index');
    }

  public function create()
    {
        $coas = DB::table('coa_setups')
            ->where('parent_id', 4)
            ->orderBy('head_name')
            ->get();
        $hotels = DB::table('staff')
                 ->get();

        return view('hrm.expense.create', compact('coas','hotels'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'expense_head_id'    => 'required|exists:coa_setups,id',
            'expense_month'  => 'required|integer|between:1,12',
            'expense_year'   => 'required|digits:4',
            'expense_amount'    => 'required|numeric|min:0',
            'expense_date'      => 'required|date',
            'status'         => 'required|in:Pending,Approved,Paid',
            'remarks'        => 'nullable|string',
        ]);
        DB::table('hrm_expense')->insert([
            'expense_head_id'   => $request->expense_head_id,
            'employee_id'   => $request->employee_id,
            'expense_month' => $request->expense_month,
            'expense_year'  => $request->expense_year,
            'expense_amount'        => $request->expense_amount,
            'expense_date'  => $request->expense_date,
            'remarks'       => $request->remarks,
            'status'        => $request->status,
            'created_by'    => auth()->id(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()
            ->route('admin.expense.index')
            ->withSuccessMessage('Expense added successfully.');
    }

     // RTA Bus expense head id (apnar dropdown e "313 - RTA Bus")
    private const RTA_HEAD_ID = 313;

    // Company Bus er head id. coa_setups e naam e "Company" ache emon head khuje,
    // na pele 313 e fallback kore. Apnar asol id jani thakle ekhane fixed number din.
    private function companyHeadId(): int
    {
        return (int) (DB::table('coa_setups')
            ->where('parent_id', 4)
            ->where('head_name', 'like', '%Company%')
            ->value('id') ?? self::RTA_HEAD_ID);
    }

    /**
     * Transport page (Step 1-5)
     */
    public function transport()
    {
        $staff = \App\Models\Staff::where('user_id', auth()->id())->firstOrFail();

        // Dropdown location list - nijer shohor/area onujayi edit korun
        $locations = [
            'Satwa',
            'Madinat Jumeirah',
            'Deira',
            'Bur Dubai',
            'Al Barsha',
            'Dubai Marina',
            'Al Qusais',
            'Karama',
        ];

        $todayTrips = DB::table('hrm_expense')
            ->where('employee_id', $staff->id)
            ->whereDate('expense_date', today())
            ->whereIn('expense_head_id', array_unique([self::RTA_HEAD_ID, $this->companyHeadId()]))
            ->orderBy('id')
            ->get();
           // dd($todayTrips);

        return view('hrm.expense.transport', compact('staff', 'locations', 'todayTrips'));
    }

    /**
     * Confirm Transport - 1 ta trip = 1 ta hrm_expense row
     */
    public function transportStore(Request $request)
    {
        $request->validate([
            'transport_type'  => 'required|in:company,rta',
            'trips'           => 'required_if:transport_type,rta|array',
            'trips.*.from'    => 'required_with:trips|string|max:100',
            'trips.*.to'      => 'required_with:trips|string|max:100|different:trips.*.from',
            'trips.*.amount'  => 'required_with:trips|numeric|min:0',
        ]);

        $staff = \App\Models\Staff::where('user_id', auth()->id())->firstOrFail();
        $today = now();

        $rows = [];
        if ($request->transport_type === 'company') {
            $headId = $this->companyHeadId();
            $rows[] = ['head' => $headId, 'amount' => 0, 'remarks' => 'Company Bus'];
            $typeLabel = 'Company Bus';
        } else {
            foreach ($request->trips as $t) {
                $rows[] = [
                    'head'    => self::RTA_HEAD_ID,
                    'amount'  => $t['amount'],
                    'remarks' => $t['from'] . ' → ' . $t['to'],
                ];
            }
            $typeLabel = 'RTA Bus';
        }

        DB::transaction(function () use ($rows, $staff, $today) {
            foreach ($rows as $r) {
                DB::table('hrm_expense')->insert([
                    'expense_head_id' => $r['head'],
                    'employee_id'     => $staff->id,
                    'expense_month'   => $today->month,
                    'expense_year'    => $today->year,
                    'expense_amount'  => $r['amount'],
                    'expense_date'    => $today->toDateString(),
                    'remarks'         => $r['remarks'],
                    'status'          => 'Pending',
                    'created_by'      => auth()->id(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        });

        return redirect()->route('admin.transport.index')->with('recorded', [
            'type'   => $typeLabel,
            'route'  => collect($rows)->pluck('remarks')->implode("\n"),
            'amount' => collect($rows)->sum('amount'),
            'date'   => $today->format('j F Y'),
        ]);
    }


    public function edit($id)
    {
        $expense = DB::table('hrm_expense')->find($id);

        $coas = DB::table('coa_setups')
            ->where('parent_id', 4)
            ->orderBy('head_name')
            ->get();
        $hotels = DB::table('staff')
                 ->get();

        return view('hrm.expense.edit', compact('expense', 'coas','hotels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_head_id'   => 'required|exists:coa_setups,id',
            'expense_month' => 'required|integer|between:1,12',
            'expense_year'  => 'required|digits:4',
            'expense_amount'        => 'required|numeric|min:0',
            'expense_date'  => 'required|date',
            'status'        => 'required|in:Pending,Approved,Paid',
            'remarks'       => 'nullable|string|max:1000',
        ]);
      
        DB::table('hrm_expense')
            ->where('id', $id)
            ->update([
                'employee_id'   => $request->employee_id,
                'expense_head_id'   => $request->expense_head_id,
                'expense_month' => $request->expense_month,
                'expense_year'  => $request->expense_year,
                'expense_amount'   => $request->expense_amount,
                'expense_date'  => $request->expense_date,
                'remarks'       => $request->remarks,
                'status'        => $request->status,
                'updated_by'    => auth()->id(),
                'updated_at'    => now(),
            ]);

        return redirect()
            ->route('admin.expense.index')
            ->withSuccessMessage('Expense updated successfully.');
    }

    
}