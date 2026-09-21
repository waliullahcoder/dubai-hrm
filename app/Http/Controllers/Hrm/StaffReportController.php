<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Staff;
use App\Models\Hotel;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class StaffReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function staffAdvance()
    {
        $paymentdata = $this->paymentData();
        return view('hrm.staff-reports.advance',compact('paymentdata'));
    }

   
    public function staffEarning()
    {
        $paymentdata = $this->paymentData();
        return view('hrm.staff-reports.earning',compact('paymentdata'));
    }


    public function workingHours()
    {
        $paymentdata = $this->paymentData();
        return view('hrm.staff-reports.working_hour',compact('paymentdata'));
    }
    
    public function staffPayment()
    {
        $paymentdata = $this->paymentData();
        return view('hrm.staff-reports.payment',compact('paymentdata'));
    }



     public function paymentData()
    {
        $staff = Staff::where('user_id',Auth::user()->id)->first();
        $totalpayments = DB::table('hrm_payments')->where('employee_id',$staff->id)->sum('payment_amount');
        $payments = DB::table('hrm_payments')->where('employee_id',$staff->id)->where('status','Payment')->sum('payment_amount');
        $advance = DB::table('hrm_payments')->where('employee_id',$staff->id)->where('status','Advance')->sum('payment_amount');
         $expense = DB::table('hrm_expense')->where('employee_id',$staff->id)->where('status','Approved')->sum('expense_amount');
        $totalWorkedHours = DB::table('hrm_employee_attendances')->where('employee_id',$staff->id)->where('attendance_status','Present')->sum('worked_hours');
        // $hours = floor($totalWorkedHours);
        // $minutes = round(($totalWorkedHours - $hours) * 100);

        // if ($minutes >= 60) {
        //     $hours += floor($minutes / 60);
        //     $minutes = $minutes % 60;
        // }

        // $totalWorkedTime = $hours . 'h ' . $minutes . 'm';
        // dd($totalWorkedHours,$hours,$minutes);

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
          'net_payable' => $net_payable
       ];
    }



    
    


}
