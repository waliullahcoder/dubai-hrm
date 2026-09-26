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
use Carbon\Carbon;
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
                ->leftJoin('hrm_hotels as h', 'h.id', '=', 'atd.hotel_id')
                ->select(
                    'atd.id',
                    'atd.hotel_id', // FIXED
                    'h.name as hotel_name',
                    's.code as employee_code',
                    's.name as employee_name',
                    'atd.attendance_date',
                    'atd.check_in',
                    'atd.check_out',
                    'atd.worked_hours',
                    'atd.remarks'
                );

            // ================= HOTEL FILTER =================
            if (request()->filled('hotel_id')) {
                $model->where(
                    'atd.hotel_id',
                    request('hotel_id')
                );
            }

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

                // ================= HOTEL =================
                ->editColumn('hotel_name', function ($row) {
                    return $row->hotel_name ?? '-';
                })

                // ================= ATTENDANCE DATE =================
                ->editColumn('attendance_date', function ($row) {

                    return $row->attendance_date
                        ? date('F j, Y', strtotime($row->attendance_date))
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

        // ================= HOTELS =================
        $hotels = DB::table('hrm_hotels')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'hrm.reports.workinghour',
            compact('employees', 'hotels')
        );
    }

  
public function monthlyReport(Request $request)
{
    $employees = DB::table('staff')
        ->orderBy('name')
        ->get();

    $report = null;

    if ($request->filled('employee_id') && $request->filled('month') && $request->filled('year')) {

        $employeeId = $request->employee_id;
        $month      = (int) $request->month;
        $year       = (int) $request->year;

        // Employee
        $employee = DB::table('staff')
            ->where('id', $employeeId)
            ->first();

        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }

        // Monthly Attendance
    $attendances = DB::table('hrm_employee_attendances as atd')
    ->leftJoin('staff as s', 's.id', '=', 'atd.employee_id')
    ->leftJoin('hrm_hotels as h', 'h.id', '=', 'atd.hotel_id')

    // Date-wise expense total
    ->leftJoin(
        DB::raw('(
            SELECT
                employee_id,
                DATE(expense_date) as expense_day,

                SUM(
                    CASE
                        WHEN expense_head_id = 314
                        THEN expense_amount
                        ELSE 0
                    END
                ) as from_bus_amount,

                SUM(
                    CASE
                        WHEN expense_head_id = 313
                        THEN expense_amount
                        ELSE 0
                    END
                ) as to_bus_amount

            FROM hrm_expense

            GROUP BY
                employee_id,
                DATE(expense_date)

        ) as exp'),
        function ($join) {
            $join->on('exp.employee_id', '=', 'atd.employee_id')
                ->on(
                    'exp.expense_day',
                    '=',
                    DB::raw('DATE(atd.attendance_date)')
                );
        }
    )

    ->select(
        'atd.id',
        'atd.attendance_date',
        'atd.check_in',
        'atd.check_out',
        'atd.worked_hours',
        'h.name as hotel_name',

        /*
        |--------------------------------------------------------------------------
        | Expense শুধু একই date-এর প্রথম attendance-এ দেখাবে
        |--------------------------------------------------------------------------
        */
        DB::raw("
            CASE
                WHEN atd.id = (
                    SELECT MIN(a2.id)
                    FROM hrm_employee_attendances as a2
                    WHERE a2.employee_id = atd.employee_id
                    AND DATE(a2.attendance_date) = DATE(atd.attendance_date)
                )
                THEN COALESCE(exp.from_bus_amount, 0)
                ELSE 0
            END as from_bus_amount
        "),

        DB::raw("
            CASE
                WHEN atd.id = (
                    SELECT MIN(a2.id)
                    FROM hrm_employee_attendances as a2
                    WHERE a2.employee_id = atd.employee_id
                    AND DATE(a2.attendance_date) = DATE(atd.attendance_date)
                )
                THEN COALESCE(exp.to_bus_amount, 0)
                ELSE 0
            END as to_bus_amount
        ")
    )

    ->where('atd.employee_id', $employeeId)
    ->whereMonth('atd.attendance_date', $month)
    ->whereYear('atd.attendance_date', $year)

    ->orderBy('atd.attendance_date', 'asc')
    ->orderBy('atd.id', 'asc')
    ->get();

        // Total Working Hours
        $totalHours = $attendances->sum(function ($attendance) {
            return (float) ($attendance->worked_hours ?? 0);
        });

        // Rate
        $rate = (float) ($employee->basic_salary ?? 0);

        // Total Earning
        $totalEarning = $totalHours * $rate;

        // From Bus + To Bus
        $totalFromBus = $attendances->sum(function ($attendance) {
            return (float) ($attendance->from_bus_amount ?? 0);
        });

        $totalToBus = $attendances->sum(function ($attendance) {
            return (float) ($attendance->to_bus_amount ?? 0);
        });

        $totalTransport = $totalFromBus + $totalToBus;

        /*
        |--------------------------------------------------------------------------
        | Advance Amount
        |--------------------------------------------------------------------------
        */

        $advanceAmount = DB::table('hrm_payments')
            ->where('employee_id', $employeeId)
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->where(function ($query) {
                $query->where('status', 'Advance');
            })
            ->sum('payment_amount');

        $paymentAmount = DB::table('hrm_payments')
            ->where('employee_id', $employeeId)
            ->whereMonth('payment_date', $month)
            ->whereYear('payment_date', $year)
            ->where(function ($query) {
                $query->where('status', 'Payment');
            })
            ->sum('payment_amount');

        /*
        |--------------------------------------------------------------------------
        | Expense
        |--------------------------------------------------------------------------
        | Employee related approved expenses for this month.
        | If your expense table uses another status field, change here.
        |--------------------------------------------------------------------------
        */
    
        $expenseAmount = DB::table('hrm_expense')
            ->where('employee_id', $employeeId)
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->where(function ($query) {
                $query->where('status', 'Approved');
            })
            ->sum('expense_amount');

        /*
        |--------------------------------------------------------------------------
        | Net Amount
        |--------------------------------------------------------------------------
        */

        $grossAmount = $totalEarning + $totalTransport;

        $netAmount = $grossAmount - $advanceAmount - $paymentAmount + $expenseAmount;

        $report = [
            'employee'       => $employee,
            'attendances'    => $attendances,
            'month'          => $month,
            'year'           => $year,
            'total_hours'    => $totalHours,
            'rate'           => $rate,
            'total_earning'  => $totalEarning,
            'from_bus'       => $totalFromBus,
            'to_bus'         => $totalToBus,
            'transport'      => $totalTransport,
            'advance'        => $advanceAmount,
            'payment'        => $paymentAmount,
            'expense'        => $expenseAmount,
            'gross_amount'   => $grossAmount,
            'net_amount'     => $netAmount,
        ];
    }

    return view('hrm.staff-reports.monthly-report', compact(
        'employees',
        'report'
    ));
}







}
