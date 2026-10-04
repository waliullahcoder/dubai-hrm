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
use Illuminate\Support\Facades\Schema;
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
    return view('hrm.staff-reports.payment', compact('paymentdata'));
}
 
 
public function paymentData()
{
    $staff = Staff::where('user_id', Auth::user()->id)->first();
 
    $totalpayments = DB::table('hrm_payments')->where('employee_id', $staff->id)->sum('payment_amount');
    $payments      = DB::table('hrm_payments')->where('employee_id', $staff->id)->where('status', 'Payment')->sum('payment_amount');
    $loans       = DB::table('hrm_employee_loan')->where('employee_id', $staff->id)->where('status', 'Approved')->get();
    $advance       = DB::table('hrm_payments')->where('employee_id', $staff->id)->where('status', 'Advance')->sum('payment_amount')+ $loans->sum('loan_amount');
    $expense       = DB::table('hrm_expense')->where('expense_head_id',313)->where('employee_id', $staff->id)->where('status', 'Approved')->sum('expense_amount');
    $totalWorkedHours = DB::table('hrm_employee_attendances')
        ->where('employee_id', $staff->id)
        ->where('attendance_status', 'Present')
        ->whereNotNull('check_out')
        ->sum('worked_hours');
    $earnings    = $staff->basic_salary * $totalWorkedHours - $staff->others;
    $net_payable = $earnings + $loans->sum('total_installments') +  $expense - ($advance + $payments);
    return [
        'staff'         => $staff,
        'totalpayments' => $totalpayments,
        'payments'      => $payments,
        'advance'       => $advance,
        'worked_hours'  => $totalWorkedHours,
        'expense'       => $expense,
        'earnings'      => $earnings,
        'loans'      => $loans,
        'net_payable'   => $net_payable,
        'history'       => $this->paymentHistory($staff),   // NEW: month-wise rows
    ];
}
 
 
/**
 * Month-wise payment history.
 *
 * net_payable  = (hours * rate) + approved transport - advance (oi mash e newa)
 * paid_amount  = oi mash e deya 'Payment' status er sum
 */
protected function paymentHistory($staff)
{
    $rate = (float) $staff->basic_salary;
 
    // Column name gulo table e na thakle fallback (jate error na hoy)
    $payDate   = Schema::hasColumn('hrm_payments', 'payment_date') ? 'payment_date' : 'created_at';
    $payMethod = Schema::hasColumn('hrm_payments', 'payment_method') ? 'payment_method'
               : (Schema::hasColumn('hrm_payments', 'method') ? 'method' : null);
    $expDate   = Schema::hasColumn('hrm_expense', 'expense_date') ? 'expense_date' : 'created_at';
 
    // 1) Mash-wise worked hours
    $hours = DB::table('hrm_employee_attendances')
        ->where('employee_id', $staff->id)
        ->where('attendance_status', 'Present')
        ->whereNotNull('check_out')
        ->selectRaw("DATE_FORMAT(attendance_date, '%Y-%m') as ym, SUM(worked_hours) as total")
        ->groupBy('ym')
        ->pluck('total', 'ym');
 
    // 2) Mash-wise approved transport/expense
    $expenses = DB::table('hrm_expense')
        ->where('employee_id', $staff->id)
        ->where('expense_head_id', 313)
        ->where('status', 'Approved')
        ->selectRaw("DATE_FORMAT($expDate, '%Y-%m') as ym, SUM(expense_amount) as total")
        ->groupBy('ym')
        ->pluck('total', 'ym');
 
    // 3) Mash-wise payments & advances
    $paid = $adv = $lastDate = $lastMethod = [];
 
    $rows = DB::table('hrm_payments')->where('employee_id', $staff->id)->get();
 
    foreach ($rows as $p) {
        $date = Carbon::parse($p->$payDate);
        $ym   = $date->format('Y-m');
 
        if ($p->status === 'Advance') {
            $adv[$ym] = ($adv[$ym] ?? 0) + (float) $p->payment_amount;
        } elseif ($p->status === 'Payment') {
            $paid[$ym] = ($paid[$ym] ?? 0) + (float) $p->payment_amount;
 
            if (!isset($lastDate[$ym]) || $date->gt($lastDate[$ym])) {
                $lastDate[$ym]   = $date;
                $lastMethod[$ym] = $payMethod ? $p->$payMethod : null;
            }
        }
    }
 
    // 4) Sob mash ekshathe
    $months = collect(array_merge(
        $hours->keys()->all(),
        $expenses->keys()->all(),
        array_keys($paid),
        array_keys($adv)
    ))->unique()->sort()->values();
 
    return $months->map(function ($ym) use ($rate, $hours, $expenses, $paid, $adv, $lastDate, $lastMethod) {
        $gross = $rate * (float) ($hours[$ym] ?? 0);
        $net   = $gross + (float) ($expenses[$ym] ?? 0) - (float) ($adv[$ym] ?? 0);
        
        return [
            'month'        => $ym,                                   // 2026-09
            'net_payable'  => $net,
            'paid_amount'  => (float) ($paid[$ym] ?? 0),
            'payment_date' => isset($lastDate[$ym]) ? $lastDate[$ym]->format('Y-m-d') : null,
            'method'       => $lastMethod[$ym] ?? null,
        ];
    })->all();
}
 



    
    


}
