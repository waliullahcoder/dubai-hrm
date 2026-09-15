<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Staff;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HrmReportController extends Controller
{
   

    public function paymentReport()
    {
        if (request()->ajax()) {

            $model = DB::table('hrm_payments as p')
                ->leftJoin('staff as s', 's.id', '=', 'p.employee_id')
                ->select(
                    'p.id',
                    's.code as employee_code',
                    's.name as employee_name',
                    'p.payment_month',
                    'p.payment_year',
                    'p.payment_amount',
                    'p.payment_date',
                    'p.status',
                    'p.remarks'
                );

            if (request()->filled('employee_id')) {
                $model->where('p.employee_id', request('employee_id'));
            }
            if (request()->filled('status')) {
                $model->where('p.status', request('status'));
            }

            if (request()->filled('from_date')) {
                $model->whereDate('p.payment_date', '>=', request('from_date'));
            }

            if (request()->filled('to_date')) {
                $model->whereDate('p.payment_date', '<=', request('to_date'));
            }

            $model->orderByDesc('p.id');

            return DataTables::of($model)

                ->addIndexColumn()

                ->editColumn('payment_month', function ($row) {
                    return $row->payment_month
                        ? date('F', mktime(0, 0, 0, $row->payment_month, 1))
                        : '-';
                })

                ->editColumn('payment_amount', function ($row) {
                    return number_format($row->payment_amount, 2);
                })

               

                ->editColumn('payment_date', function ($row) {
                    return $row->payment_date
                        ? date('F j, Y', strtotime($row->payment_date))
                        : '-';
                })

                ->editColumn('status', function ($row) {

                        $status = strtolower(trim($row->status ?? ''));

                        if ($status === 'payment') {

                            $class = 'payment';

                        } elseif ($status === 'advance') {

                            $class = 'advance';

                        } else {

                            $class = 'default';

                        }

                        return '<span class="payment-badge ' . $class . '">
                                    ' . ucfirst($row->status ?? '-') . '
                                </span>';
                    })

                ->rawColumns([
                    'status'
                ])

                ->make(true);
        }

        $employees = DB::table('staff')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('hrm.reports.payment', compact('employees'));
    }




    public function expenseReport()
    {
        if (request()->ajax()) {

            $model = DB::table('hrm_expense as exp')
                ->leftJoin('staff as s', 's.id', '=', 'exp.employee_id')
                ->select(
                    'exp.id',
                    's.code as employee_code',
                    's.name as employee_name',
                    'exp.expense_month',
                    'exp.expense_year',
                    'exp.expense_amount',
                    'exp.expense_date',
                    'exp.status',
                    'exp.remarks'
                );

            if (request()->filled('employee_id')) {
                $model->where('exp.employee_id', request('employee_id'));
            }
            if (request()->filled('status')) {
                $model->where('exp.status', request('status'));
            }

            if (request()->filled('from_date')) {
                $model->whereDate('exp.expense_date', '>=', request('from_date'));
            }

            if (request()->filled('to_date')) {
                $model->whereDate('exp.expense_date', '<=', request('to_date'));
            }

            $model->orderByDesc('exp.id');

            return DataTables::of($model)

                ->addIndexColumn()

                ->editColumn('expense_month', function ($row) {
                    return $row->expense_month
                        ? date('F', mktime(0, 0, 0, $row->expense_month, 1))
                        : '-';
                })

                ->editColumn('expense_amount', function ($row) {
                    return number_format($row->expense_amount, 2);
                })

               

                ->editColumn('expense_date', function ($row) {
                    return $row->expense_date
                        ? date('F j, Y', strtotime($row->expense_date))
                        : '-';
                })

                ->editColumn('status', function ($row) {

                        $status = strtolower(trim($row->status ?? ''));

                        if ($status === 'Approved') {

                            $class = 'Approved';

                        } elseif ($status === 'Paid') {

                            $class = 'Paid';

                        } else {

                            $class = 'pending';

                        }

                        return '<span class="payment-badge ' . $class . '">
                                    ' . ucfirst($row->status ?? '-') . '
                                </span>';
                    })

                ->rawColumns([
                    'status'
                ])

                ->make(true);
        }

        $employees = DB::table('staff')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('hrm.reports.expense', compact('employees'));
    }

    public function workinghourReport()
    {
        if (request()->ajax()) {

            $model = DB::table('hrm_employee_attendances as atd')
                ->leftJoin('staff as s', 's.id', '=', 'atd.employee_id')
                ->select(
                    'atd.id',
                    's.code as employee_code',
                    's.name as employee_name',
                    'atd.attendance_date',
                    'atd.check_in',
                    'atd.check_out',
                    'atd.worked_hours',
                    'atd.remarks'
                );

            // ================= EMPLOYEE FILTER =================
            if (request()->filled('employee_id')) {
                $model->where(
                    'atd.employee_id',
                    request('employee_id')
                );
            }

            // ================= FROM DATE =================
            if (request()->filled('from_date')) {
                $model->whereDate(
                    'atd.attendance_date',
                    '>=',
                    request('from_date')
                );
            }

            // ================= TO DATE =================
            if (request()->filled('to_date')) {
                $model->whereDate(
                    'atd.attendance_date',
                    '<=',
                    request('to_date')
                );
            }

            $model->orderByDesc('atd.attendance_date')
                ->orderByDesc('atd.id');

            return DataTables::of($model)

                ->addIndexColumn()

                // ================= ATTENDANCE DATE =================
                ->editColumn('attendance_date', function ($row) {

                    return $row->attendance_date
                        ? date(
                            'F j, Y',
                            strtotime($row->attendance_date)
                        )
                        : '-';
                })

                // ================= CHECK IN =================
                ->editColumn('check_in', function ($row) {

                    if (!$row->check_in) {
                        return '<span class="text-muted">-</span>';
                    }

                    return date(
                        'h:i A',
                        strtotime($row->check_in)
                    );
                })

                // ================= CHECK OUT =================
                ->editColumn('check_out', function ($row) {

                    if (!$row->check_out) {
                        return '<span class="text-muted">-</span>';
                    }

                    return date(
                        'h:i A',
                        strtotime($row->check_out)
                    );
                })

                // ================= WORKING HOUR =================
                ->editColumn('worked_hours', function ($row) {

                    if (!$row->worked_hours) {
                        return '<span class="text-muted">-</span>';
                    }

                    return '<span class="working-hour-badge">
                                <i class="fas fa-clock me-1"></i>
                                ' . $row->worked_hours . '
                            </span>';
                })

                // ================= REMARKS =================
                ->editColumn('remarks', function ($row) {

                    return $row->remarks
                        ? e($row->remarks)
                        : '-';
                })

                ->rawColumns([
                    'check_in',
                    'check_out',
                    'worked_hours'
                ])

                ->make(true);
        }

        // ================= EMPLOYEES =================
        $employees = DB::table('staff')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'hrm.reports.workinghour',
            compact('employees')
        );
    }







}
