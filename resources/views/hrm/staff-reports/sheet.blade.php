@extends('layouts.admin.app')

@section('title', 'Generate Payroll')

@section('content')
<style>
     /* PRINT */

    @media print {

       

        .no-print,
        .sidebar,
        .navbar,
        .app-header,
        .main-header,
        footer,.navbar-header,.formsec {
            display: none !important;
        }

        
    }

</style>

<div class="container-fluid py-4">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 text-muted small mb-3">
        <i class="fa-regular fa-calendar"></i>
        <span>Payroll Management</span>
        <span>›</span>
        <span class="fw-semibold text-dark">Generate Payroll Sheet</span>
    </div>


    {{-- Page Header --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">

                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="fa-regular fa-calendar-days fs-4"></i>
                </div>

                <div>
                    <h4 class="fw-bold mb-1">Generate Payroll Sheet</h4>
                    <p class="text-muted mb-0">
                        Create salary for your staff based on attendance and hourly rate
                    </p>
                </div>

            </div>
        </div>
    </div>


    {{-- Filters --}}
    <form method="GET" action="{{route('admin.sheet.report')}}" class="card border-0 shadow-sm mb-4 formsec">
        @csrf

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fa-solid fa-filter text-primary me-2"></i>
                Payroll Filter
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Month --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Payroll Month <span class="text-danger">*</span>
                    </label>

                    <select name="payroll_month" class="form-select">
                        @foreach([
                            'January','February','March','April',
                            'May','June','July','August',
                            'September','October','November','December'
                        ] as $month)

                            <option value="{{ $month }}"
                                {{ (old('payroll_month', $selectedMonth ?? 'September') == $month) ? 'selected' : '' }}>
                                {{ $month }}
                            </option>

                        @endforeach
                    </select>
                </div>


                {{-- Year --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Payroll Year <span class="text-danger">*</span>
                    </label>

                    <select name="payroll_year" class="form-select">

                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)

                            <option value="{{ $y }}"
                                {{ (old('payroll_year', $selectedYear ?? date('Y')) == $y) ? 'selected' : '' }}>
                                {{ $y }}
                            </option>

                        @endfor

                    </select>
                </div>


                {{-- Hotel --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Hotel / Outlet
                    </label>

                    <select name="hotel_id" class="form-select">

                        <option value="">All Hotels</option>

                        @foreach($hotels ?? [] as $hotel)

                            <option value="{{ $hotel->id }}">
                                {{ $hotel->name }}
                            </option>

                        @endforeach

                    </select>
                </div>


                {{-- Department --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        Department
                    </label>

                    <select name="department_id" class="form-select">

                        <option value="">All Departments</option>

                        @foreach($departments ?? [] as $dept)

                            <option value="{{ $dept->id }}">
                                {{ $dept->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>


            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    Load Attendance Data
                </button>
            </div>

        </div>
    </form>


    {{-- Employee Salary List --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                <i class="fa-regular fa-calendar-check text-primary me-2"></i>
                Employee Salary List
            </h5>
        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">SL</th>
                            <th>Employee</th>

                            <th>ID</th>

                            <th>Hotel</th>

                            <th>Department</th>

                            <th class="text-end">Days</th>

                            <th class="text-end">Hours</th>

                            <th class="text-end">Rate</th>

                            <th class="text-end">Amount</th>

                            <th class="text-end">Advance</th>

                            <th class="text-end">Deduction</th>

                            <th class="text-end bg-success-subtle text-success">
                                Final Payable
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($employees ?? [] as $index => $emp)

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Employee --}}
                                <td>

                                    <div class="d-flex align-items-center">
                                        @if(!empty($emp->user_image))
                                        <img src="{{ !empty($emp->user_image)
                                                ? asset($emp->user_image)
                                                : asset('images/avatar-placeholder.png') }}"
                                            class="rounded-circle me-2"
                                            width="38"
                                            height="38"
                                            style="object-fit: cover;"
                                            alt="{{ $emp->name }}">
                                            @else
                                            -
                                            @endif

                                        <span class="fw-semibold">
                                            {{ $emp->name }}
                                        </span>

                                    </div>

                                </td>


                                <td>
                                    {{ $emp->employee_id }}
                                </td>


                                <td>
                                    {{ $emp->hotel }}
                                </td>


                                <td>
                                    {{ $emp->department }}
                                </td>


                                <td class="text-end">
                                    {{ $emp->working_days }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($emp->total_hours, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($emp->rate_per_hour, 2) }}
                                </td>


                                <td class="text-end fw-semibold">
                                    {{ number_format($emp->total_amount, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($emp->advance_recovery, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($emp->other_deduction, 2) }}
                                </td>


                                <td class="text-end fw-bold text-success bg-success-subtle">

                                    {{ number_format($emp->final_payable, 2) }}

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="12"
                                    class="text-center text-muted py-5">

                                    <i class="fa-regular fa-folder-open fs-3 d-block mb-2"></i>

                                    No attendance data loaded yet.
                                    Select month/year and click
                                    <strong>"Load Attendance Data"</strong>.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- Total --}}
                    @if(!empty($employees))

                        <tfoot>

                            <tr class="table-primary fw-bold">

                                <td colspan="1"></td>

                                <td colspan="2">
                                    Total
                                </td>

                                <td></td>

                                <td></td>

                                <td class="text-end">
                                    {{ $employees->sum('working_days') }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($employees->sum('total_hours'), 2) }}
                                </td>

                                <td class="text-end">
                                    -
                                </td>

                                <td class="text-end">
                                    {{ number_format($employees->sum('total_amount'), 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($employees->sum('advance_recovery'), 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($employees->sum('other_deduction'), 2) }}
                                </td>

                                <td class="text-end text-success">

                                    {{ number_format($employees->sum('final_payable'), 2) }}

                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">


        {{-- Employees --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-primary small fw-semibold mb-2">
                        <i class="fa-solid fa-users me-1"></i>
                        Total Employees
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ $employees->count() ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Working Days --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-success small fw-semibold mb-2">
                        <i class="fa-regular fa-calendar me-1"></i>
                        Total Working Days
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ $employees->sum('working_days') ?? 0 }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Hours --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-info small fw-semibold mb-2">
                        <i class="fa-regular fa-clock me-1"></i>
                        Total Hours
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ number_format($employees->sum('total_hours') ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Earnings --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-warning small fw-semibold mb-2">
                        <i class="fa-solid fa-coins me-1"></i>
                        Total Earnings (AED)
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ number_format($employees->sum('total_amount') ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Advance --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-danger small fw-semibold mb-2">
                        <i class="fa-solid fa-circle-minus me-1"></i>
                        Advance Recovery (AED)
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ number_format($employees->sum('advance_recovery') ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Other Deduction --}}
        <div class="col-6 col-md-4 col-lg">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-danger small fw-semibold mb-2">
                        <i class="fa-solid fa-circle-minus me-1"></i>
                        Other Deduction (AED)
                    </div>

                    <h4 class="fw-bold mb-0">
                        {{ number_format($employees->sum('other_deduction') ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Final Payable --}}
        <div class="col-12 col-md-12 col-lg">

            <div class="card border-0 bg-success-subtle shadow-sm h-100">

                <div class="card-body">

                    <div class="text-success small fw-semibold mb-2">
                        <i class="fa-solid fa-wallet me-1"></i>
                        Total Final Payable (AED)
                    </div>

                    <h4 class="fw-bold text-success mb-0">
                        {{ number_format($employees->sum('final_payable') ?? 0, 2) }}
                    </h4>

                </div>

            </div>

        </div>

       

     
    


    {{-- Action Buttons --}}
    <div class="d-flex flex-column flex-sm-row gap-2 mb-4 formsec">

        <!-- <button type="submit"
                form="payrollForm"
                class="btn btn-success px-4">

            <i class="fa-solid fa-calculator me-1"></i>

            GENERATE PAYROLL

        </button> -->


        <button type="button"
                onclick="window.print()"
                class="btn btn-primary px-4">

            <i class="fa-solid fa-print me-1"></i>

            PRINT SALARY SHEET

        </button>

    </div>

</div>

@endsection
