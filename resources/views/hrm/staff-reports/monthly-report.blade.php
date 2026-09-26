@extends('layouts.admin.app')

@section('content')

<style>

    :root {
        --primary: #173b6c;
        --primary-dark: #102b50;
        --secondary: #00a67e;
        --light-bg: #f5f7fb;
        --border: #e5e7eb;
        --text: #1f2937;
        --muted: #6b7280;
    }

    body {
        background: var(--light-bg);
    }

    .report-wrapper {
        max-width: 1250px;
        margin: 0 auto;
    }

    /* Filter */

    .filter-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
    }

    .filter-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 43px;
        border-radius: 8px;
        border: 1px solid #d9dee7;
    }

    .btn-report {
        min-height: 43px;
        border: 0;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        padding: 0 22px;
        font-weight: 600;
    }

    .btn-report:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    /* Report */

    .report-paper {
        background: #fff;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 5px 25px rgba(0,0,0,.06);
    }

    .company-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 20px;
        margin-bottom: 22px;
    }

    .company-logo {
        width: 100%;
        height: 85px;
        object-fit: contain;
    }

    .company-info {
        flex: 1;
        text-align: center;
    }

    .company-name {
        font-size: 25px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
    }

    .report-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin: 5px 0 0;
    }

    .report-period {
        color: var(--muted);
        font-size: 13px;
        margin-top: 5px;
    }

    .header-right {
        width: 85px;
    }

    /* Employee */

    .employee-box {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 15px 18px;
        margin-bottom: 22px;
    }

    .employee-label {
        font-size: 11px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .employee-name {
        font-size: 20px;
        font-weight: 750;
        color: var(--text);
    }

    .employee-id {
        font-size: 13px;
        color: var(--muted);
    }

    /* Table */

    .table-responsive {
        border-radius: 8px;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table thead th {
        background: var(--primary);
        color: #fff;
        padding: 11px 9px;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
    }

    .report-table tbody td {
        padding: 10px 9px;
        border-bottom: 1px solid var(--border);
        font-size: 12px;
        color: #374151;
        text-align: center;
        white-space: nowrap;
    }

    .report-table tbody tr:hover {
        background: #f8fafc;
    }

    .hotel-name {
        font-weight: 600;
        color: var(--primary);
        text-align: left !important;
    }

    .amount {
        font-weight: 600;
    }

    /* Summary */

    .summary-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
        margin: 25px 0 12px;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table td {
        padding: 10px 13px;
        border: 1px solid var(--border);
        font-size: 13px;
    }

    .summary-table td:first-child {
        font-weight: 600;
        background: #f8fafc;
        width: 35%;
    }

    .summary-table td:last-child {
        text-align: right;
        font-weight: 700;
    }

    .net-row td {
        background: #eafaf5 !important;
        color: #087f5b;
        font-size: 15px !important;
    }

    .advance-row td {
        color: #dc3545;
    }

    .report-footer {
        margin-top: 28px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        color: var(--muted);
        font-size: 11px;
    }

    .print-btn {
        background: var(--secondary);
        border: none;
        color: #fff;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 600;
    }

    .print-btn:hover {
        color: #fff;
        opacity: .9;
    }

    @media(max-width: 768px) {

        .report-paper {
            padding: 18px;
        }

        .company-header {
            flex-direction: column;
            text-align: center;
        }

        .header-right {
            display: none;
        }

        .company-name {
            font-size: 21px;
        }
    }

    /* PRINT */

    @media print {

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            background: #fff !important;
        }

        .no-print,
        .sidebar,
        .navbar,
        .app-header,
        .main-header,
        footer,.navbar-header {
            display: none !important;
        }

        .content-wrapper,
        .content,
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .report-wrapper {
            max-width: 100%;
        }

        .report-paper {
            box-shadow: none !important;
            border: none !important;
            padding: 0;
        }

        .report-table thead th {
            background: #173b6c !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .net-row td {
            background: #eafaf5 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

</style>


<div class="container-fluid py-3">

    <div class="report-wrapper">

        {{-- FILTER --}}
        <div class="filter-card no-print">

            <div class="filter-title">
                <i class="fas fa-file-invoice me-1"></i>
                Employee Monthly Report
            </div>

            <form action="{{ route('admin.monthly.report') }}" method="GET">

                <div class="row g-3 align-items-end">

                    {{-- Employee --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Employee
                        </label>

                        <select name="employee_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Employee
                            </option>

                            @foreach($employees as $employee)

                                <option value="{{ $employee->id }}"
                                    {{ request('employee_id') == $employee->id ? 'selected' : '' }}>

                                    {{ $employee->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Month --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Month
                        </label>

                        <select name="month"
                                class="form-select"
                                required>

                            @foreach(range(1, 12) as $month)

                                <option value="{{ $month }}"
                                    {{ request('month', date('m')) == $month ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Year --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Year
                        </label>

                        <select name="year"
                                class="form-select"
                                required>

                            @for($year = date('Y') - 3; $year <= date('Y') + 1; $year++)

                                <option value="{{ $year }}"
                                    {{ request('year', date('Y')) == $year ? 'selected' : '' }}>

                                    {{ $year }}

                                </option>

                            @endfor

                        </select>

                    </div>


                    {{-- Button --}}
                    <div class="col-md-2">

                        <button type="submit"
                                class="btn-report w-100">

                            <i class="fas fa-search me-1"></i>
                            Generate

                        </button>

                    </div>

                </div>

            </form>

        </div>


        @if($report)

        {{-- REPORT --}}
        <div class="report-paper">

            {{-- Header --}}
            <div class="company-header">

                <div>

                    <img src="{{ asset($admin_setting->logo) }}"
                         class="company-logo"
                         alt="Company Logo">

                </div>


                <div class="company-info">

                    <h1 class="company-name">
                        {{ config('app.name') }}
                    </h1>

                    <div class="report-title">
                        Monthly Employee Report
                    </div>

                    <div class="report-period">

                        {{ \Carbon\Carbon::create(
                            $report['year'],
                            $report['month'],
                            1
                        )->format('F Y') }}

                    </div>

                </div>


                <div class="header-right"></div>

            </div>


            {{-- Employee Information --}}
            <div class="employee-box">

                <div class="row">

                    <div class="col-md-7">

                        <div class="employee-label">
                            Employee
                        </div>

                        <div class="employee-name">
                            {{ $report['employee']->name }}
                        </div>

                    </div>

                    <div class="col-md-5 text-md-end">

                        <div class="employee-label">
                            Monthly Report
                        </div>

                        <div class="employee-id">
                            Employee ID:
                            #{{ $report['employee']->id }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Attendance Table --}}
            <div class="table-responsive">

                <table class="report-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Hotel</th>

                            <th>Check In</th>

                            <th>Check Out</th>

                            <th>Working Hours</th>

                            <th>From Bus</th>

                            <th>To Bus</th>

                            <th>Total Transport</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($report['attendances'] as $key => $attendance)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                </td>

                                <td class="hotel-name">
                                    {{ $attendance->hotel_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $attendance->check_in
                                        ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $attendance->check_out
                                        ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A')
                                        : '-' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format((float) $attendance->worked_hours, 2) }}
                                    </strong>
                                </td>

                                <td class="amount">
                                    {{ number_format((float) $attendance->from_bus_amount, 2) }}
                                </td>

                                <td class="amount">
                                    {{ number_format((float) $attendance->to_bus_amount, 2) }}
                                </td>

                                <td class="amount">
                                    {{ number_format(
                                        (float)$attendance->from_bus_amount +
                                        (float)$attendance->to_bus_amount,
                                        2
                                    ) }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-4">

                                    No attendance records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Summary --}}
            <div class="summary-title">
                Payment Summary
            </div>

            <table class="summary-table">

                <tr>

                    <td>
                        Total Working Hours
                    </td>

                    <td>
                        {{ number_format($report['total_hours'], 2) }} Hours
                    </td>

                </tr>


                <tr>

                    <td>
                        Hourly Rate
                    </td>

                    <td>
                        {{ number_format($report['rate'], 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Total Earning Amount
                    </td>

                    <td>
                        {{ number_format($report['total_earning'], 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Total Transport Amount
                    </td>

                    <td>
                        {{ number_format($report['transport'], 2) }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Gross Amount
                    </td>

                    <td>
                        {{ number_format($report['gross_amount'], 2) }}
                    </td>

                </tr>


                <tr class="advance-row">

                    <td>
                        Advance Amount Deducted
                    </td>

                    <td>
                        - {{ number_format($report['advance'], 2) }}
                    </td>

                </tr>
                 <tr class="advance-row">

                    <td>
                        Payment Amount Deducted
                    </td>

                    <td>
                        - {{ number_format($report['payment'], 2) }}
                    </td>

                </tr>


                @if($report['expense'] > 0)

                <tr>

                    <td>
                        Approved Expense
                    </td>

                    <td>
                        + {{ number_format($report['expense'], 2) }}
                    </td>

                </tr>

                @endif


                <tr class="net-row">

                    <td>
                        NET AMOUNT
                    </td>

                    <td>
                        {{ number_format($report['net_amount'], 2) }}
                    </td>

                </tr>

            </table>


            {{-- Footer --}}
            <div class="report-footer">

                <div>
                    Generated on:
                    {{ now()->format('d M Y h:i A') }}
                </div>

                <div>
                    Monthly Employee Report
                </div>

            </div>


            {{-- Print --}}
            <div class="text-end mt-3 no-print">

                <button onclick="window.print()"
                        class="print-btn">

                    <i class="fas fa-print me-1"></i>
                    Print Report

                </button>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection