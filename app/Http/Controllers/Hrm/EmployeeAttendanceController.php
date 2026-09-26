<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Staff;
use App\Models\Hotel;
use App\Models\Category;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function attendanceDashboard(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    $selectedDate = $request->date
        ? Carbon::parse($request->date)
        : Carbon::today();

    $selectedMonth = $request->month
        ? Carbon::createFromFormat('Y-m', $request->month)
        : Carbon::today();

    $hotelId = $request->hotel_id;
    $departmentid = $request->department_id;


    /*
    |--------------------------------------------------------------------------
    | MONTH RANGE
    |--------------------------------------------------------------------------
    */

    $monthStart = $selectedMonth->copy()->startOfMonth();
    $monthEnd   = $selectedMonth->copy()->endOfMonth();


    /*
    |--------------------------------------------------------------------------
    | HOTELS
    |--------------------------------------------------------------------------
    */

    $hotels = Hotel::where('status', 'Active')
        ->orderBy('name')
        ->get();

     $departments = Category::orderBy('id','desc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | TODAY / SELECTED DATE ATTENDANCE
    |--------------------------------------------------------------------------
    */

    $attendanceQuery = DB::table('hrm_employee_attendances as a')
        ->join('staff as s', 's.id', '=', 'a.employee_id')
        ->leftJoin('hrm_hotels as h', 'h.id', '=', 's.hotel_id')
        ->whereDate('a.attendance_date', $selectedDate->format('Y-m-d'))
        ->where('a.attendance_status', 'Present');

    if ($hotelId) {
        $attendanceQuery->where('s.hotel_id', $hotelId);
    }

    $attendance = $attendanceQuery
        ->select(
            'a.id',
            'a.employee_id',
            's.hotel_id',
            'a.check_in',
            'a.check_out',
            'a.worked_hours',
            's.name as staff_name',
            'h.name as hotel_name'
        )
        ->orderBy('a.check_in')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | SELECTED DATE SUMMARY
    |--------------------------------------------------------------------------
    */

    $todayStaff = $attendance->unique('employee_id')->count();

    $todayHours = $attendance->sum(function ($row) {
        return (float) $row->worked_hours;
    });

    $activeHotels = $attendance
        ->whereNotNull('hotel_id')
        ->pluck('hotel_id')
        ->unique()
        ->count();


    /*
    |--------------------------------------------------------------------------
    | MONTHLY ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

    $monthlyQuery = DB::table('hrm_employee_attendances as a')
        ->join('staff as s', 's.id', '=', 'a.employee_id')
        ->leftJoin('hrm_hotels as h', 'h.id', '=', 's.hotel_id')
        ->whereBetween('a.attendance_date', [
            $monthStart->format('Y-m-d'),
            $monthEnd->format('Y-m-d')
        ])
        ->where('a.attendance_status', 'Present');

    if ($hotelId) {
        $monthlyQuery->where('s.hotel_id', $hotelId);
    }

    $monthlyAttendance = $monthlyQuery
        ->select(
            'a.id',
            'a.employee_id',
            's.hotel_id',
            'a.attendance_date',
            'a.worked_hours',
            'a.check_in',
            'a.check_out',
            's.name as staff_name',
            'h.name as hotel_name'
        )
        ->orderBy('a.attendance_date')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | UNIQUE STAFF THIS MONTH
    |--------------------------------------------------------------------------
    */

    $uniqueStaffThisMonth = $monthlyAttendance
        ->pluck('employee_id')
        ->unique()
        ->count();


    /*
    |--------------------------------------------------------------------------
    | MONTH TOTAL HOURS
    |--------------------------------------------------------------------------
    */

    $monthHours = $monthlyAttendance->sum(function ($row) {
        return (float) $row->worked_hours;
    });


    /*
    |--------------------------------------------------------------------------
    | WORKING DAYS
    |--------------------------------------------------------------------------
    */

    $workingDays = $monthlyAttendance
        ->groupBy(function ($row) {
            return Carbon::parse($row->attendance_date)->format('Y-m-d');
        })
        ->count();


    /*
    |--------------------------------------------------------------------------
    | HOTEL COLORS
    |--------------------------------------------------------------------------
    */

    $hotelColors = [
        '#4f46e5',
        '#0d9488',
        '#f59e0b',
        '#ec4899',
        '#0ea5e9',
        '#8b5cf6',
        '#ef4444',
        '#10b981',
    ];


    /*
    |--------------------------------------------------------------------------
    | HOTEL WISE TODAY DATA
    |--------------------------------------------------------------------------
    */

    $staffByHotel = $attendance
        ->groupBy('hotel_name')
        ->map(function ($rows) {
            return $rows->pluck('employee_id')->unique()->count();
        });

    $hoursByHotel = $attendance
        ->groupBy('hotel_name')
        ->map(function ($rows) {
            return $rows->sum(function ($row) {
                return (float) $row->worked_hours;
            });
        });


    /*
    |--------------------------------------------------------------------------
    | HOTEL CHART DATA
    |--------------------------------------------------------------------------
    */

    $hotelChartLabels = $attendance
        ->pluck('hotel_name')
        ->filter()
        ->unique()
        ->values()
        ->all();

    $hotelChartColors = collect($hotelChartLabels)
        ->values()
        ->map(function ($hotel, $index) use ($hotelColors) {
            return $hotelColors[$index % count($hotelColors)];
        })
        ->all();

    $hotelStaffCounts = collect($hotelChartLabels)
        ->map(function ($hotel) use ($staffByHotel) {
            return $staffByHotel[$hotel] ?? 0;
        })
        ->all();

    $hotelHourTotals = collect($hotelChartLabels)
        ->map(function ($hotel) use ($hoursByHotel) {
            return round($hoursByHotel[$hotel] ?? 0, 2);
        })
        ->all();


    /*
    |--------------------------------------------------------------------------
    | DAILY SUMMARY
    |--------------------------------------------------------------------------
    */

    $daily = collect();

    $currentDay = $monthStart->copy();

    while ($currentDay <= $monthEnd) {

        $date = $currentDay->format('Y-m-d');

        $dayRows = $monthlyAttendance->filter(function ($row) use ($date) {
            return Carbon::parse($row->attendance_date)->format('Y-m-d') === $date;
        });

        $daily->push([
            'date'   => $date,
            'staff'  => $dayRows->pluck('employee_id')->unique()->count(),
            'hours'  => round(
                $dayRows->sum(function ($row) {
                    return (float) $row->worked_hours;
                }),
                2
            ),
            'hotels' => $dayRows
                ->pluck('hotel_id')
                ->filter()
                ->unique()
                ->count(),
        ]);

        $currentDay->addDay();
    }


    /*
    |--------------------------------------------------------------------------
    | AVERAGE HOURS
    |--------------------------------------------------------------------------
    */

    $avgHours = $workingDays > 0
        ? round($monthHours / $workingDays, 2)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | CHART DATA
    |--------------------------------------------------------------------------
    */

    $chartData = [
        'hotels'      => $hotelChartLabels,
        'colors'      => $hotelChartColors,
        'staffCounts' => $hotelStaffCounts,
        'hourTotals'  => $hotelHourTotals,

        'days' => $daily
            ->map(function ($row) {
                return Carbon::parse($row['date'])->format('d');
            })
            ->values()
            ->all(),

        'dailyStaff' => $daily
            ->pluck('staff')
            ->values()
            ->all(),

        'dailyHours' => $daily
            ->pluck('hours')
            ->values()
            ->all(),

        'avgHours' => $avgHours,

        'todayIndex' => $daily->search(function ($row) use ($selectedDate) {
            return $row['date'] === $selectedDate->format('Y-m-d');
        }),
    ];


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view('hrm.employee_attendance.dashboard', compact(
        'hotels',
        'attendance',
        'daily',
        'chartData',
        'todayStaff',
        'todayHours',
        'activeHotels',
        'uniqueStaffThisMonth',
        'monthHours',
        'workingDays',
        'avgHours',
        'selectedDate',
        'selectedMonth',
        'hotelId',
        'departmentid',
        'departments'
    ));
}
    public function index()
    {
        if (request()->ajax()) {

            $data = DB::table('hrm_employee_attendances as a')
                ->leftJoin('staff as e', 'e.id', '=', 'a.employee_id')
                ->select(
                    'a.*',
                    'e.code as emp_code',
                    'e.name'
                )
                ->orderBy('a.id', 'desc');

            return DataTables::of($data)

                    ->addIndexColumn()

                    ->addColumn('employee', function ($row) {
                        return $row->emp_code . ' - ' . $row->name;
                    })

                    ->editColumn('attendance_status', function ($row) {

                        switch ($row->attendance_status) {

                            case 'Present':
                                return '<span class="badge bg-success">Present</span>';

                            case 'Late':
                                return '<span class="badge bg-warning">Late</span>';

                            case 'Absent':
                                return '<span class="badge bg-danger">Absent</span>';

                            case 'Half Day':
                                return '<span class="badge bg-info">Half Day</span>';

                            case 'Leave':
                                return '<span class="badge bg-secondary">Leave</span>';

                            case 'Holiday':
                                return '<span class="badge bg-primary">Holiday</span>';

                            case 'Weekend':
                                return '<span class="badge bg-dark">Weekend</span>';

                            default:
                                return $row->attendance_status;
                        }

                    })

                ->addColumn('actions', function ($row) {

                    $data = [
                        'id'=>$row->id,
                        'edit'=>true,
                    ];

                    $actionBtn = NULL;

                    if(Auth::user()->can('admin.employee-attendance.show')){
                        $actionBtn .= '<a href="'.Route('admin.employee-attendance.show',$row->id).'" class="btn btn-sm btn-primary tt" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>';
                    }

                    return ActionButtons::actions($data,$actionBtn);

                })

                ->rawColumns([
                    'attendance_status',
                    'actions'
                ])

                ->make(true);
        }

        return view('hrm.employee_attendance.index');
    }

    public function create()
    {
        $employees = DB::table('staff')
            ->where('status',1)
            ->orderBy('name')
            ->get();

        return view('hrm.employee_attendance.create',compact('employees'));
    }
   
    public function store(Request $request)
    {
        //  $staff = Staff::where('id', $request->employee_id)->first();
        //                 if ($staff) {
        //                     $staff->update([
        //                         'location_varified' => null
        //                     ]);
        //                 }
        //                 dd("ddd");
        $request->validate([
            'employee_id' => 'required',
            // 'attendance_date' => 'required',
            // 'attendance_status' => 'required',
        ]);

        if(Auth::user()->role_status==4 && $request->location_varified){
            $staff = Staff::find($request->employee_id);
            $staff->update([
                 'location_varified' => $request->location_varified,
                 'hotel_id' => $request->hotel_id,
                 'department_id' => $request->department_id
            ]);
             return redirect()->back()->withSuccessMessage('Location varified successfully.');
        }

       
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            // Office time
            $officeStart = Carbon::parse($request->attendance_date . ' 09:00:00');
            $officeEnd   = Carbon::parse($request->attendance_date . ' 18:00:00');


            // =====================================
            // LATE MINUTES
            // =====================================

            $lateMinutes = 0;

            if ($checkIn->greaterThan($officeStart)) {

                $lateMinutes = $officeStart->diffInMinutes($checkIn);

            }


            // =====================================
            // WORKED MINUTES
            // =====================================

            $workedMinutes = $checkIn->diffInMinutes($checkOut);

            $workedHours = floor($workedMinutes / 60);

            $workedMinutesRemaining = $workedMinutes % 60;

            $workedHoursFormatted = sprintf(
                '%02d:%02d',
                $workedHours,
                $workedMinutesRemaining
            );


            // =====================================
            // OVERTIME MINUTES
            // =====================================

            $overtimeMinutes = 0;

            if ($checkOut->greaterThan($officeEnd)) {

                $overtimeMinutes = $officeEnd->diffInMinutes($checkOut);

            }


            //Distance
            // $officeLat = $request->check_in_latitude;
            // $officeLng = $request->check_in_longitude;

            $officeLat = 23.760570242255966;
            $officeLng = 90.41916917806215;

            $employeeLat = (float) $request->check_in_latitude;
            $employeeLng = (float) $request->check_in_longitude;

            $earthRadius = 200; // meters

            $latFrom = deg2rad($officeLat);
            $latTo   = deg2rad($employeeLat);

            $latDelta = deg2rad($employeeLat - $officeLat);
            $lngDelta = deg2rad($employeeLng - $officeLng);

            $a = sin($latDelta / 2) ** 2
                + cos($latFrom)
                * cos($latTo)
                * sin($lngDelta / 2) ** 2;

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadius * $c;

            // dd([
            //     'employee_lat' => $employeeLat,
            //     'employee_lng' => $employeeLng,
            //     'distance_meter' => round($distance, 2),
            // ]);
             

        if(isset($request->employee_id) && count($request->employee_id)>0){
              $attendexist= DB::table('hrm_employee_attendances')->where('attendance_date',$request->attendance_date)->whereIn('employee_id',$request->employee_id)->count();
              if($attendexist){
            $attendance= DB::table('hrm_employee_attendances')->where('attendance_date',$request->attendance_date)->whereIn('employee_id',$request->employee_id)->first();
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = Carbon::parse($request->check_out);

            // Office time
            $officeStart = Carbon::parse($request->attendance_date . ' 09:00:00');
            $officeEnd   = Carbon::parse($request->attendance_date . ' 18:00:00');


            // =====================================
            // LATE MINUTES
            // =====================================

            $lateMinutes = 0;

            if ($checkIn->greaterThan($officeStart)) {

                $lateMinutes = $officeStart->diffInMinutes($checkIn);

            }


            // =====================================
            // WORKED MINUTES
            // =====================================

            $workedMinutes = $checkIn->diffInMinutes($checkOut);

            $workedHours = floor($workedMinutes / 60);

            $workedMinutesRemaining = $workedMinutes % 60;

            $workedHoursFormatted = sprintf(
                '%02d:%02d',
                $workedHours,
                $workedMinutesRemaining
            );


            // =====================================
            // OVERTIME MINUTES
            // =====================================

            $overtimeMinutes = 0;

            if ($checkOut->greaterThan($officeEnd)) {

                $overtimeMinutes = $officeEnd->diffInMinutes($checkOut);

            }

              $todayAttendance = DB::table('hrm_employee_attendances')->where('employee_id', $request->employee_id ?? null)->whereDate('attendance_date', today())->orderBy('id','desc')->first();

            if (Auth::user()->role_status==4 && $todayAttendance && $todayAttendance->check_in != null && $todayAttendance->check_out == null) {
                  $staff = Staff::where('id', $request->employee_id)->first();
                        if ($staff) {
                            $staff->update([
                                'location_varified' => null
                            ]);
                        }
                    DB::table('hrm_employee_attendances')
                        ->where('id', $todayAttendance->id)
                        ->update([
                            'check_out'            => $request->check_out ?? now()->format('H:i:s'),
                            'check_out_latitude'   => $request->check_out_latitude,
                            'check_out_longitude'  => $request->check_out_longitude,
                            'check_out_distance'   => $distance,
                            'late_minutes'         => $lateMinutes,
                            'overtime_minutes'     => $overtimeMinutes,
                            'worked_hours'         => $workedHours,
                            'amount'         => $staff->basic_salary*$workedHours,
                        ]);

                      

                   return redirect()->back()->withSuccessMessage('Attendance Check Out successfully.');
                }

                // return redirect()->back()->withErrors('Already Exist attendance!');
              }


            
           foreach ($request->employee_id as $key => $employeeId) {
                $staff= Staff::find($employeeId);
                DB::table('hrm_employee_attendances')->insert([
                    'employee_id'       => $employeeId,
                    'hotel_id'       => $staff->hotel_id,
                    'department_id'       => $staff->department_id,
                    'hour_rate'       => $staff->basic_salary,
                    'employee_id'       => $employeeId,
                    'attendance_date'   => $request->attendance_date,
                    'check_in'          => $request->check_in,
                    'check_in_latitude'          => $request->check_in_latitude,
                    'check_in_longitude'          => $request->check_in_longitude,
                    'check_in_distance'          => $distance,
                    'check_out'         => Auth::user()->role_status==1 ? $request->check_out : NULL,
                    'check_out_latitude'          =>  Auth::user()->role_status==1 ? $request->check_out_latitude : NULL,
                    'check_out_longitude'          =>  Auth::user()->role_status==1 ? $request->check_out_longitude : NULL,
                    'check_out_distance'          =>  Auth::user()->role_status==1 ? $distance : NULL,
                    'late_minutes'      => Auth::user()->role_status==4 ? $lateMinutes : $request->late_minutes,
                    'overtime_minutes'  => Auth::user()->role_status==4 ? $overtimeMinutes : $request->overtime_minutes,
                    'worked_hours'      => Auth::user()->role_status==4 ? $workedHours : $request->worked_hours,
                    'attendance_status' => $request->attendance_status,
                    'remarks'           => $request->remarks,
                    'created_by'        => auth()->id(),
                    'created_at'        => now(),
                ]);
                }

                if(Auth::user()->role_status==4){
                      return redirect()->back()->withSuccessMessage('Attendance Check In successfully.');
                }
                
    return redirect()
        ->route('admin.employee-attendance.index')
        ->with('success', 'Attendance Check In successfully.');
            }else{
                return redirect()
                ->route('admin.employee-attendance.create')
                ->with('error', 'Ops! select employee');
            }
    }


    public function edit($id)
    {
        $data = DB::table('hrm_employee_attendances')
            ->where('id', $id)
            ->first();

        $employees = DB::table('staff')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('hrm.employee_attendance.edit', compact('data', 'employees'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
            'attendance_date' => 'required|date',
            'attendance_status' => 'required',
        ]);

        $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            // Office time
            $officeStart = Carbon::parse($request->attendance_date . ' 09:00:00');
            $officeEnd   = Carbon::parse($request->attendance_date . ' 18:00:00');


            // =====================================
            // LATE MINUTES
            // =====================================

            $lateMinutes = 0;

            if ($checkIn->greaterThan($officeStart)) {

                $lateMinutes = $officeStart->diffInMinutes($checkIn);

            }


            // =====================================
            // WORKED MINUTES
            // =====================================

            $workedMinutes = $checkIn->diffInMinutes($checkOut);

            $workedHours = floor($workedMinutes / 60);

            $workedMinutesRemaining = $workedMinutes % 60;

            $workedHoursFormatted = sprintf(
                '%02d:%02d',
                $workedHours,
                $workedMinutesRemaining
            );


            // =====================================
            // OVERTIME MINUTES
            // =====================================

            $overtimeMinutes = 0;

            if ($checkOut->greaterThan($officeEnd)) {

                $overtimeMinutes = $officeEnd->diffInMinutes($checkOut);

            }

          $officeLat = 23.760570242255966;
            $officeLng = 90.41916917806215;

            $employeeLat = (float) $request->check_in_latitude;
            $employeeLng = (float) $request->check_in_longitude;

            $earthRadius = 200; // meters

            $latFrom = deg2rad($officeLat);
            $latTo   = deg2rad($employeeLat);

            $latDelta = deg2rad($employeeLat - $officeLat);
            $lngDelta = deg2rad($employeeLng - $officeLng);

            $a = sin($latDelta / 2) ** 2
                + cos($latFrom)
                * cos($latTo)
                * sin($lngDelta / 2) ** 2;

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadius * $c;


        DB::table('hrm_employee_attendances')
            ->where('id', $id)
            ->update([

                'employee_id'       => $request->employee_id,
                'attendance_date'   => $request->attendance_date,
                'check_in'          => $request->check_in,
                'check_in_latitude'          => $request->check_in_latitude,
                'check_in_longitude'          => $request->check_in_longitude,
                'check_in_distance'          => $distance,
                'check_out'         => $request->check_out,
                'check_out_latitude'          => $request->check_out_latitude,
                'check_out_longitude'          => $request->check_out_longitude,
                'check_out_distance'          => $distance,
                'late_minutes'      => Auth::user()->role_status==4 ? $lateMinutes : $request->late_minutes,
                'overtime_minutes'  => Auth::user()->role_status==4 ? $overtimeMinutes : $request->overtime_minutes,
                'worked_hours'      => Auth::user()->role_status==4 ? $workedHours : $request->worked_hours,
                'attendance_status' => $request->attendance_status,
                'remarks'           => $request->remarks,
                'updated_by'        => auth()->id(),
                'updated_at'        => now(),

            ]);

        return redirect()
            ->route('admin.employee-attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }




     public function paymentData()
       {
            $staff = Staff::where('user_id',Auth::user()->id)->first();
            $totalpayments = DB::table('hrm_payments')->where('employee_id',$staff->id)->sum('payment_amount');
            $payments = DB::table('hrm_payments')->where('employee_id',$staff->id)->where('status','Payment')->sum('payment_amount');
            $advance = DB::table('hrm_payments')->where('employee_id',$staff->id)->where('status','Advance')->sum('payment_amount');
            $expense = DB::table('hrm_expense')->where('employee_id',$staff->id)->where('status','Approved')->sum('expense_amount');
            $totalWorkedHours = DB::table('hrm_employee_attendances')->where('employee_id',$staff->id)->where('attendance_status','Present')->sum('worked_hours');

            $hotelcount = Hotel::where('status','Active')->count();
            
            $earnings =  $staff->basic_salary * $totalWorkedHours - $staff->others;
            $net_payable = $earnings-$totalpayments;

            return [
                'staff' => $staff,
                'totalpayments' => $totalpayments,
                'payments' => $payments,
                'advance' => $advance,
                'worked_hours' => $totalWorkedHours,
                'expense' => $expense,
                'earnings' => $earnings,
                'net_payable' => $net_payable,
                'hotelcount' => $hotelcount
            ];
        }


}
