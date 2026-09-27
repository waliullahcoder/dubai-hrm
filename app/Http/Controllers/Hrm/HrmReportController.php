<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Hotel;
use App\Models\Staff;
use App\Models\Category;
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


public function payslipReport(Request $request){

 return view('hrm.staff-reports.payslip');
}
public function certificateReport(Request $request){
    return view('hrm.staff-reports.certificate');
}

public function sheetReport(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Hotels & Departments
    |--------------------------------------------------------------------------
    */

    $hotels = Hotel::orderBy('name')->get();

    $departments = Category::orderBy('name')->get();


    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    */

    $employees = collect();

    $selectedMonth = $request->get(
        'payroll_month',
        now()->format('F')
    );

    $selectedYear = (int) $request->get(
        'payroll_year',
        now()->year
    );

    $selectedHotel = $request->get('hotel_id');

    $selectedDepartment = $request->get('department_id');


    /*
    |--------------------------------------------------------------------------
    | Load Attendance Data
    |--------------------------------------------------------------------------
    */

    if ($request->isMethod('post') || $request->has('payroll_month')) {

        /*
        |--------------------------------------------------------------------------
        | Month Number
        |--------------------------------------------------------------------------
        */

        $monthNumber = Carbon::parse(
            "1 {$selectedMonth} {$selectedYear}"
        )->month;


        /*
        |--------------------------------------------------------------------------
        | Expense Sub Query
        |--------------------------------------------------------------------------
        |
        | Employee wise monthly expense.
        | This is aggregated BEFORE joining with attendance.
        |
        */

        $expenseQuery = DB::table('hrm_expense')
            ->select(
                'employee_id',

                DB::raw('
                    SUM(
                        COALESCE(expense_amount, 0)
                    ) as expense_amount
                ')
            )
            ->whereMonth(
                'expense_date',
                $monthNumber
            )
            ->whereYear(
                'expense_date',
                $selectedYear
            )
            ->groupBy('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Payment Sub Query
        |--------------------------------------------------------------------------
        |
        | Advance:
        | status = Advance
        |
        | Other Deduction:
        | status = Payment
        |
        */

        $paymentQuery = DB::table('hrm_payments')
            ->select(
                'employee_id',

                /*
                |--------------------------------------------------------------------------
                | Advance Recovery
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'Advance'
                            THEN COALESCE(payment_amount, 0)
                            ELSE 0
                        END
                    ) as advance_recovery
                "),

                /*
                |--------------------------------------------------------------------------
                | Other Deduction
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'Payment'
                            THEN COALESCE(payment_amount, 0)
                            ELSE 0
                        END
                    ) as other_deduction
                ")
            )
            ->whereMonth(
                'payment_date',
                $monthNumber
            )
            ->whereYear(
                'payment_date',
                $selectedYear
            )
            ->groupBy('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Main Attendance Query
        |--------------------------------------------------------------------------
        |
        | One employee = One row
        |
        */

        $query = DB::table('hrm_employee_attendances as atd')


            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'staff as s',
                's.id',
                '=',
                'atd.employee_id'
            )


            /*
            |--------------------------------------------------------------------------
            | User / Profile Image
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'users as u',
                'u.id',
                '=',
                's.user_id'
            )


            /*
            |--------------------------------------------------------------------------
            | Hotel
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'hrm_hotels as h',
                'h.id',
                '=',
                'atd.hotel_id'
            )


            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'categories as c',
                'c.id',
                '=',
                's.department_id'
            )


            /*
            |--------------------------------------------------------------------------
            | Employee Wise Expense
            |--------------------------------------------------------------------------
            */

            ->leftJoinSub(
                $expenseQuery,
                'exp',
                function ($join) {

                    $join->on(
                        'exp.employee_id',
                        '=',
                        's.id'
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | Employee Wise Payments
            |--------------------------------------------------------------------------
            */

            ->leftJoinSub(
                $paymentQuery,
                'pmnt',
                function ($join) {

                    $join->on(
                        'pmnt.employee_id',
                        '=',
                        's.id'
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | Attendance Month / Year
            |--------------------------------------------------------------------------
            */

            ->whereMonth(
                'atd.attendance_date',
                $monthNumber
            )

            ->whereYear(
                'atd.attendance_date',
                $selectedYear
            );


        /*
        |--------------------------------------------------------------------------
        | Hotel Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hotel_id')) {

            $query->where(
                'atd.hotel_id',
                $request->hotel_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {

            $query->where(
                's.department_id',
                $request->department_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Employee Wise Select
        |--------------------------------------------------------------------------
        */

        $employees = $query

            ->select(

                /*
                |--------------------------------------------------------------------------
                | Employee Information
                |--------------------------------------------------------------------------
                */

                's.id',

                's.user_id',

                's.code as employee_id',

                's.name',

                's.department_id',

                'u.image as user_image',

                'c.name as department',


                /*
                |--------------------------------------------------------------------------
                | Hotel
                |--------------------------------------------------------------------------
                |
                | Multiple hotels will appear in one row.
                |
                */

                DB::raw("
                    GROUP_CONCAT(
                        DISTINCT h.name
                        ORDER BY h.name
                        SEPARATOR ', '
                    ) as hotel
                "),


                /*
                |--------------------------------------------------------------------------
                | Working Days
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    COUNT(
                        DISTINCT DATE(atd.attendance_date)
                    ) as working_days
                "),


                /*
                |--------------------------------------------------------------------------
                | Total Working Hours
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    SUM(
                        COALESCE(
                            atd.worked_hours,
                            0
                        )
                    ) as total_hours
                "),


                /*
                |--------------------------------------------------------------------------
                | Display Hourly Rate
                |--------------------------------------------------------------------------
                |
                | If different rates exist during the month,
                | highest rate will be displayed.
                |
                */

                DB::raw("
                    MAX(
                        COALESCE(
                            atd.hour_rate,
                            0
                        )
                    ) as rate_per_hour
                "),


                /*
                |--------------------------------------------------------------------------
                | Total Salary Amount
                |--------------------------------------------------------------------------
                |
                | Every attendance:
                |
                | worked_hours × hour_rate
                |
                */

                DB::raw("
                    SUM(
                        COALESCE(
                            atd.worked_hours,
                            0
                        )
                        *
                        COALESCE(
                            atd.hour_rate,
                            0
                        )
                    ) as total_amount
                "),


                /*
                |--------------------------------------------------------------------------
                | Expense Amount
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    COALESCE(
                        exp.expense_amount,
                        0
                    ) as expense_amount
                "),


                /*
                |--------------------------------------------------------------------------
                | Advance Recovery
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    COALESCE(
                        pmnt.advance_recovery,
                        0
                    ) as advance_recovery
                "),


                /*
                |--------------------------------------------------------------------------
                | Other Deduction
                |--------------------------------------------------------------------------
                */

                DB::raw("
                    COALESCE(
                        pmnt.other_deduction,
                        0
                    ) as other_deduction
                ")
            )


            /*
            |--------------------------------------------------------------------------
            | Group By
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Do NOT group by:
            |
            | h.name
            | atd.hour_rate
            |
            | Otherwise same employee will appear multiple times.
            |
            */

            ->groupBy(

                's.id',

                's.user_id',

                's.code',

                's.name',

                's.department_id',

                'u.image',

                'c.name',

                'exp.expense_amount',

                'pmnt.advance_recovery',

                'pmnt.other_deduction'
            )


            /*
            |--------------------------------------------------------------------------
            | Employee Name Sorting
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                's.name',
                'asc'
            )


            /*
            |--------------------------------------------------------------------------
            | Execute Query
            |--------------------------------------------------------------------------
            */

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Final Payroll
        |--------------------------------------------------------------------------
        */

        $employees = $employees->map(
            function ($employee) {

                /*
                |--------------------------------------------------------------------------
                | Working Days
                |--------------------------------------------------------------------------
                */

                $employee->working_days = (int)
                    $employee->working_days;


                /*
                |--------------------------------------------------------------------------
                | Total Hours
                |--------------------------------------------------------------------------
                */

                $employee->total_hours = round(
                    (float) $employee->total_hours,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Hourly Rate
                |--------------------------------------------------------------------------
                */

                $employee->rate_per_hour = round(
                    (float) $employee->rate_per_hour,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Salary Amount
                |--------------------------------------------------------------------------
                */

                $employee->total_amount = round(
                    (float) $employee->total_amount,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Expense
                |--------------------------------------------------------------------------
                |
                | Expense will be added to earning.
                |
                */

                $employee->expense_amount = round(
                    (float) $employee->expense_amount,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Advance Recovery
                |--------------------------------------------------------------------------
                |
                | Payment table:
                | status = Advance
                |
                */

                $employee->advance_recovery = round(
                    (float) $employee->advance_recovery,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Other Deduction
                |--------------------------------------------------------------------------
                |
                | Payment table:
                | status = Payment
                |
                */

                $employee->other_deduction = round(
                    (float) $employee->other_deduction,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Gross Earning
                |--------------------------------------------------------------------------
                |
                | Salary + Expense
                |
                */

                $employee->gross_earning = round(
                    $employee->total_amount
                    + $employee->expense_amount,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Final Payable
                |--------------------------------------------------------------------------
                |
                | Salary
                | + Expense
                | - Advance Recovery
                | - Other Deduction
                |
                */

                $employee->final_payable = round(
                    $employee->gross_earning
                    - $employee->advance_recovery
                    - $employee->other_deduction,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Profile Image
                |--------------------------------------------------------------------------
                */

                if (!empty($employee->user_image)) {

                    $employee->photo_url = asset(
                        $employee->user_image
                    );

                } else {

                    $employee->photo_url = asset(
                        'images/avatar-placeholder.png'
                    );
                }


                return $employee;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'hrm.staff-reports.sheet',
        [

            'hotels' => $hotels,

            'departments' => $departments,

            'employees' => $employees,

            'selectedMonth' => $selectedMonth,

            'selectedYear' => $selectedYear,

            'selectedHotel' => $selectedHotel,

            'selectedDepartment' => $selectedDepartment,
        ]
    );
}







}
