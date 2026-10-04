<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\User;
use App\Models\Region;
use App\Models\Client;
use App\Models\Product;
use App\Models\Hotel;
use App\Models\Category;
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
           $hotels= Hotel::where('status', 'Active')->get();
           $departments= Category::get();
           return view('hrm.dashboard.staff-dashboard', compact('hotels','departments'));
        }else{

       $staffs= DB::table('staff')->get();
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
            ->whereNotNull('check_out')
            ->sum('worked_hours');

        // Total Earning
        // Staff total salary
        $attendances = DB::table('hrm_employee_attendances')->whereNotNull('check_out')->get();

        $total_earning = $attendances->sum('amount');

        // Total Payments
        $total_payments = DB::table('hrm_payments')
            ->sum('payment_amount');

            // Advance Payments
        $advancepayment = DB::table('hrm_payments')->where('status', 'Advance')
            ->sum('payment_amount');
        $loans = DB::table('hrm_employee_loan')->where('status', 'Approved')->get();
        $advance_payments = $advancepayment + $loans->sum('loan_amount');

        // Total Expense
        $total_expense = DB::table('hrm_expense')
            ->where('status', 'Approved')
            ->sum('expense_amount');

        // Total Outstanding
        $total_outstanding = $total_earning - ($total_payments+$loans->sum('loan_amount'));

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
            'advance_payments',
            'staffs'
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
     * Profile page
     */
    public function edit()
    {
        $admin = Auth::user();

        // Profile completion (percentage + missing items)
        [$completion, $missing] = $this->profileCompletion($admin);

        return view('admin.profile.index', compact('admin', 'completion', 'missing'));
    }

    /**
     * Calculate profile completion.
     * Every item below is worth the same share of 100%.
     */
    private function profileCompletion($user): array
    {
        $items = [
            'name'          => 'Name',
            'email'         => 'Email address',
            'phone'         => 'Mobile number',
            'address'       => 'Address',
            'image'         => 'Profile photo',
            'id_card_front' => 'ID card (front side)',
            'id_card_back'  => 'ID card (back side)',
            'passport_image'=> 'Passport',
        ];

        $missing = [];
        foreach ($items as $column => $label) {
            if (empty($user->$column)) {
                $missing[$column] = $label;
            }
        }

        $filled = count($items) - count($missing);
        $percent = (int) round(($filled / count($items)) * 100);

        return [$percent, $missing];
    }

    /**
     * Upload / change profile photo, ID card (front/back) and passport.
     */
    public function changeImages(Request $request)
    {
        $request->validate([
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'id_card_front'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'id_card_back'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'passport_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        // input name => [db column, file prefix, folder]
        $uploads = [
            'profile_image'  => ['image',          'profile',  'backend/images/avatar/'],
            'id_card_front'  => ['id_card_front',  'id-front', 'backend/images/documents/'],
            'id_card_back'   => ['id_card_back',   'id-back',  'backend/images/documents/'],
            'passport_image' => ['passport_image', 'passport', 'backend/images/documents/'],
        ];

        foreach ($uploads as $input => [$column, $prefix, $path]) {
            if (!$request->hasFile($input)) {
                continue;
            }

            $file = $request->file($input);
            $file_name = $prefix . '-' . Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $file_name);

            // delete old file
            if (!empty($user->$column) && file_exists(public_path($user->$column))) {
                @unlink(public_path($user->$column));
            }

            $user->$column = $path . $file_name;
        }

        $user->save();
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
            'phone' => 'nullable|string|max:20',
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
