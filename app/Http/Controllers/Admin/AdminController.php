<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\User;
use App\Models\Region;
use App\Models\Client;
use App\Models\Product;
use App\Models\RetailSale;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 1) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 0) {
                return redirect()->route('customer.profile');
            } elseif (Auth::user()->role == 3) {
                return redirect()->route('investor.dashboard');
            }
        } else {
            if (!session()->has('intended_url')) {
                session(['intended_url' => url()->previous()]);
            }
            return view('admin.auth.login');
        }
    }

    public function login(Request $request)
    {
        $user = User::where('user_name', $request->user_name)->where('status', 0)->first();
        if ($user) {
            return redirect()->back()->with('error','User not Exists!');
        }

        $input = $request->all();
        if (auth()->attempt(array('user_name' => $input['user_name'], 'password' => $input['password']))) {
            return redirect()->route('admin.dashboard')->with('success', 'Logged in Successfully!');
        } else {
            return redirect()->back()->with('error', 'Invalid Email or Password!');
        }
    }

    public function dashboard()
    {

        if( Auth::user()->role_status==4){
           return view('hrm.dashboard.staff-dashboard');
        }else{
        // ==============================
        // BASIC SUMMARY
        // ==============================

        // Total Staff
        $total_staff = DB::table('staff')->count();

        // Total Hotel
        $total_hotel = DB::table('hrm_hotels')->count();

        // Total Worked Hours
        $total_hours = DB::table('hrm_employee_attendances')
            ->where('attendance_status', 'Present')
            ->sum('worked_hours');

        // Total Earning
        // Staff total salary
        $total_earning = DB::table('staff')
            ->sum('total_salary');

        // Total Payments
        $total_payments = DB::table('hrm_payments')
            ->sum('payment_amount');

            // Advance Payments
        $advance_payments = DB::table('hrm_payments')->where('status', 'Advance')
            ->sum('payment_amount');

        // Total Expense
        $total_expense = DB::table('hrm_expense')
            ->where('status', 'Approved')
            ->sum('expense_amount');

        // Total Outstanding
        $total_outstanding = $total_earning - $total_payments;

        if ($total_outstanding < 0) {
            $total_outstanding = 0;
        }


        // ==============================
        // MONTHLY CHART DATA
        // ==============================

        $monthly_payments = [];
        $monthly_expense = [];

        for ($month = 1; $month <= 12; $month++) {

            // Monthly Payments
            $monthly_payments[] = DB::table('hrm_payments')
                ->whereMonth('payment_date', $month)
                ->whereYear('payment_date', now()->year)
                ->sum('payment_amount');

            // Monthly Expense
            $monthly_expense[] = DB::table('hrm_expense')
                ->where('status', 'Approved')
                ->whereMonth('expense_date', $month)
                ->whereYear('expense_date', now()->year)
                ->sum('expense_amount');
        }

        
        return view('hrm.dashboard.dashboard', compact(
            'total_staff',
            'total_hotel',
            'total_hours',
            'total_earning',
            'total_payments',
            'total_expense',
            'total_outstanding',
            'monthly_payments',
            'monthly_expense',
            'advance_payments'
        ));
        }
        
    }

    /**
     * Manage Sidebar
     */
    public function sidebar()
    {
        if (!Session::has('sidebar-collapse')) {
            Session()->put('sidebar-collapse', 'active');
        } else {
            Session::forget('sidebar-collapse');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    public function changeImages(Request $request)
    {
        $images = User::findOrFail(Auth::user()->id);
        $cover = $request->file('cover_image');
        if (isset($cover)) {
            $path = 'backend/images/avatar/';
            $file_name = 'cover-' . Str::random(40) . '.' . $cover->getClientOriginalExtension();
            $path_file_name = $path . $file_name;
            $cover->move($path, $file_name);
            if (file_exists($images->cover_image)) {
                unlink($images->cover_image);
            }
            $images->cover_image = $path_file_name;
        }

        $profile = $request->file('profile_image');
        if (isset($profile)) {
            $path = 'backend/images/avatar/';
            $file_name = 'profile-' . Str::random(40) . '.' . $profile->getClientOriginalExtension();
            $path_file_name = $path . $file_name;
            $profile->move($path, $file_name);
            if (file_exists($images->image)) {
                unlink($images->image);
            }
            $images->image = $path_file_name;
        }
        $images->save();
        return redirect()->back()->withSuccessMessage('Image Changed Successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'unique:users,email,' . Auth::user()->id,
            'name' => 'required',
        ]);
        $admin = User::findOrFail(Auth::user()->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->save();
        return redirect()->back()->withSuccessMessage('Information Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->old_password, $admin->password)) {
            $admin->password = bcrypt($request->new_password);
            $admin->save();
            return redirect()->back()->withSuccessMessage('Updated Successfully!');
        } else {
            return redirect()->back()->withErrors('Old Password Does not Matched!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login.index');
    }
}
