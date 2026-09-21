blade
@extends('layouts.admin.app')

@section('content')

<style>
    /* =========================================================
       HRM DASHBOARD
    ========================================================== */

    .hrm {
        --bg: #f4f6fb;
        --surface: #ffffff;
        --border: #e6eaf2;
        --text: #1f2a44;
        --muted: #64748b;

        --indigo: #4f46e5;
        --indigo-2: #7c3aed;

        --emerald: #059669;
        --emerald-2: #10b981;

        --amber: #f59e0b;
        --amber-2: #f97316;

        --sky: #0ea5e9;
        --sky-2: #2563eb;

        --pink: #ec4899;

        --radius: 14px;
        --shadow: 0 4px 16px rgba(31, 42, 68, .06);

        background: var(--bg);
        color: var(--text);
        padding: 16px 12px 28px;
        font-size: 13px;
        min-height: calc(100vh - 60px);
    }

    .hrm *,
    .hrm *::before,
    .hrm *::after {
        box-sizing: border-box;
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .hrm-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 14px;
    }

    .hrm-title {
        font-size: 20px;
        font-weight: 800;
        margin: 0;

        background: linear-gradient(
            90deg,
            var(--indigo),
            var(--pink)
        );

        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hrm-subtitle {
        margin: 3px 0 0;
        color: var(--muted);
        font-size: 12px;
    }


    /* =========================================================
       FILTER
    ========================================================== */

    .filter-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-top: 4px solid var(--indigo);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 14px;
        margin-bottom: 16px;
    }

    .filter-label {
        display: block;
        margin-bottom: 5px;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
    }

    .filter-control {
        width: 100%;
        height: 38px;
        padding: 0 10px;

        border: 1px solid #d8dee9;
        border-radius: 8px;

        background: #fff;
        color: var(--text);

        font-size: 13px;
        outline: none;

        transition:
            border-color .15s,
            box-shadow .15s;
    }

    .filter-control:focus {
        border-color: var(--indigo);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
    }

    .filter-button {
        height: 38px;
        border: 0;
        border-radius: 8px;

        background: linear-gradient(
            135deg,
            var(--indigo),
            var(--indigo-2)
        );

        color: #fff;
        font-size: 13px;
        font-weight: 700;

        transition: .2s;
    }

    .filter-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(79, 70, 229, .25);
        color: #fff;
    }

    .reset-button {
        height: 38px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-card {
        display: flex;
        align-items: center;
        gap: 12px;

        min-height: 88px;
        padding: 14px;

        border-radius: var(--radius);
        color: #fff;

        box-shadow: var(--shadow);

        position: relative;
        overflow: hidden;
    }

    .summary-card::after {
        content: "";

        position: absolute;
        right: -24px;
        top: -24px;

        width: 90px;
        height: 90px;

        border-radius: 50%;

        background: rgba(255,255,255,.14);
    }

    .summary-icon {
        flex: 0 0 auto;

        width: 46px;
        height: 46px;

        display: grid;
        place-items: center;

        border-radius: 12px;

        background: rgba(255,255,255,.22);

        font-size: 20px;
    }

    .summary-value {
        font-size: 26px;
        line-height: 1.1;
        font-weight: 800;
    }

    .summary-title {
        margin-top: 3px;
        font-size: 12px;
        line-height: 1.3;
        font-weight: 600;
        opacity: .95;
    }

    .bg-grad-indigo {
        background: linear-gradient(
            135deg,
            var(--indigo),
            var(--indigo-2)
        );
    }

    .bg-grad-emerald {
        background: linear-gradient(
            135deg,
            var(--emerald),
            var(--emerald-2)
        );
    }

    .bg-grad-amber {
        background: linear-gradient(
            135deg,
            var(--amber-2),
            var(--amber)
        );
    }

    .bg-grad-sky {
        background: linear-gradient(
            135deg,
            var(--sky-2),
            var(--sky)
        );
    }


    /* =========================================================
       PANELS
    ========================================================== */

    .panel {
        height: 100%;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: var(--radius);

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 8px;

        padding: 12px 14px;

        border-bottom: 1px solid var(--border);
    }

    .panel-dot {
        width: 10px;
        height: 10px;

        flex: 0 0 auto;

        border-radius: 50%;

        background: var(
            --dot,
            var(--indigo)
        );
    }

    .panel-title {
        margin: 0;

        font-size: 14px;
        font-weight: 800;
    }

    .panel-body {
        padding: 12px 14px;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 650px;

        border-collapse: separate;
        border-spacing: 0;

        font-size: 12.5px;
    }

    .data-table th {
        padding: 10px 12px;

        background: #eef2ff;

        color: #3730a3;

        font-weight: 700;

        text-align: left;

        white-space: nowrap;
    }

    .data-table th:first-child {
        border-top-left-radius: 8px;
    }

    .data-table th:last-child {
        border-top-right-radius: 8px;
    }

    .data-table td {
        padding: 10px 12px;

        border-bottom: 1px solid var(--border);

        white-space: nowrap;
    }

    .data-table tbody tr:hover {
        background: #f8faff;
    }

    .data-table .num {
        text-align: right;
    }

    .data-table .ctr {
        text-align: center;
    }

    .data-table tfoot td {
        background: linear-gradient(
            90deg,
            #e0e7ff,
            #fce7f3
        );

        color: #312e81;

        font-weight: 800;

        border-bottom: 0;
    }

    .data-table tr.is-today td {
        background: #fff7e6;
        font-weight: 700;
    }


    /* =========================================================
       STAFF
    ========================================================== */

    .staff {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .avatar {
        width: 30px;
        height: 30px;

        display: grid;
        place-items: center;

        flex: 0 0 auto;

        border-radius: 50%;

        background: var(--c);

        color: #fff;

        font-size: 10px;
        font-weight: 700;
    }

    .badge-hotel {
        display: inline-block;

        padding: 4px 10px;

        border-radius: 999px;

        background: color-mix(
            in srgb,
            var(--c) 14%,
            #fff
        );

        color: var(--c);

        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-hours {
        display: inline-block;

        min-width: 52px;

        padding: 4px 8px;

        border-radius: 8px;

        background: #dcfce7;

        color: #166534;

        font-weight: 700;

        text-align: center;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 35px 15px !important;

        text-align: center;

        color: var(--muted);
    }

    .empty-state i {
        display: block;

        margin-bottom: 8px;

        font-size: 28px;

        color: #cbd5e1;
    }


    /* =========================================================
       CHARTS
    ========================================================== */

    .chart-box {
        position: relative;
        height: 220px;
    }

    .chart-box-lg {
        position: relative;
        height: 270px;
    }

    .chart-section {
        margin-top: 18px;

        padding-top: 14px;

        border-top: 1px dashed var(--border);
    }

    .chart-section-title {
        margin: 0 0 8px;

        font-size: 13px;
        font-weight: 800;
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;

        gap: 6px 14px;

        margin-bottom: 8px;

        font-size: 11px;
        font-weight: 600;

        color: var(--muted);
    }

    .chart-legend span {
        display: inline-flex;

        align-items: center;

        gap: 5px;
    }

    .chart-legend i {
        width: 10px;
        height: 10px;

        border-radius: 3px;

        background: var(--c);
    }


    /* =========================================================
       MONTHLY STATS
    ========================================================== */

    .monthly-stat {
        height: 100%;

        padding: 12px 8px;

        border-radius: 12px;

        text-align: center;

        background: var(--tint);

        color: var(--ink);
    }

    .monthly-stat i {
        font-size: 20px;

        margin-bottom: 4px;
    }

    .monthly-stat-value {
        font-size: 22px;

        line-height: 1.1;

        font-weight: 800;
    }

    .monthly-stat-title {
        margin-top: 3px;

        font-size: 11px;

        font-weight: 600;

        color: var(--muted);
    }

    .tint-indigo {
        --tint: #eef2ff;
        --ink: #4338ca;
    }

    .tint-emerald {
        --tint: #dcfce7;
        --ink: #15803d;
    }

    .tint-amber {
        --tint: #fff1dc;
        --ink: #c2410c;
    }

    .tint-pink {
        --tint: #fce7f3;
        --ink: #be185d;
    }


    /* =========================================================
       FILTER INFO
    ========================================================== */

    .filter-info {
        display: flex;
        flex-wrap: wrap;

        gap: 6px;

        margin-top: 10px;
    }

    .filter-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 5px 9px;

        border-radius: 999px;

        background: #f1f5f9;

        color: #475569;

        font-size: 11px;

        font-weight: 600;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767px) {

        .hrm {
            padding: 10px 6px 20px;
        }

        .hrm-title {
            font-size: 18px;
        }

        .summary-card {
            min-height: 78px;
            padding: 10px;
            gap: 8px;
        }

        .summary-icon {
            width: 38px;
            height: 38px;
            font-size: 16px;
        }

        .summary-value {
            font-size: 21px;
        }

        .summary-title {
            font-size: 11px;
        }

        .chart-box,
        .chart-box-lg {
            height: 220px;
        }

    }
</style>


<div class="container-fluid hrm">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="hrm-header">

        <div>

            <h4 class="hrm-title">
                Staff Attendance Dashboard
            </h4>

            <p class="hrm-subtitle">

                Staff attendance and working hours

                @if($hotelId)

                    @php
                        $selectedHotel = $hotels->firstWhere('id', $hotelId);
                    @endphp

                    • {{ $selectedHotel?->name ?? 'Selected Hotel' }}

                @else

                    • All Hotels

                @endif

                • {{ $selectedMonth->format('F Y') }}

            </p>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}

    <form method="GET"
          action="{{ route('admin.attendance.dashboard') }}"
          class="filter-card">

        <div class="row g-3 align-items-end">

            {{-- HOTEL --}}
            <div class="col-12 col-md-3">

                <label class="filter-label"
                       for="filterHotel">

                    <i class="fas fa-building me-1"></i>
                    Select Hotel

                </label>

                <select name="hotel_id"
                        id="filterHotel"
                        class="filter-control">

                    <option value="">
                        All Hotels
                    </option>

                    @foreach($hotels as $hotel)

                        <option value="{{ $hotel->id }}"
                            {{ (string)$hotelId === (string)$hotel->id ? 'selected' : '' }}>

                            {{ $hotel->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- DATE --}}
            <div class="col-12 col-md-3">

                <label class="filter-label"
                       for="filterDate">

                    <i class="fas fa-calendar-day me-1"></i>
                    Select Date

                </label>

                <input type="date"
                       name="date"
                       id="filterDate"
                       class="filter-control"
                       value="{{ $selectedDate->format('Y-m-d') }}">

            </div>


            {{-- MONTH --}}
            <div class="col-12 col-md-3">

                <label class="filter-label"
                       for="filterMonth">

                    <i class="fas fa-calendar-alt me-1"></i>
                    Select Month

                </label>

                <input type="month"
                       name="month"
                       id="filterMonth"
                       class="filter-control"
                       value="{{ $selectedMonth->format('Y-m') }}">

            </div>


            {{-- BUTTONS --}}
            <div class="col-12 col-md-3">

                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn filter-button flex-grow-1">

                        <i class="fas fa-filter me-1"></i>
                        Apply Filter

                    </button>

                    <a href="{{ route('admin.attendance.dashboard') }}"
                       class="btn btn-light border reset-button"
                       title="Reset Filter">

                        <i class="fas fa-sync-alt"></i>

                    </a>

                </div>

            </div>

        </div>


        {{-- ACTIVE FILTER INFO --}}

        <div class="filter-info">

            <span class="filter-badge">

                <i class="fas fa-calendar-day"></i>

                Date:
                {{ $selectedDate->format('d F Y') }}

            </span>


            <span class="filter-badge">

                <i class="fas fa-calendar-alt"></i>

                Month:
                {{ $selectedMonth->format('F Y') }}

            </span>


            <span class="filter-badge">

                <i class="fas fa-building"></i>

                @if($hotelId)

                    {{ $selectedHotel?->name ?? 'Selected Hotel' }}

                @else

                    All Hotels

                @endif

            </span>

        </div>

    </form>


    {{-- =========================================================
         TOP SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-3">

        {{-- STAFF --}}
        <div class="col-6 col-xl-3">

            <div class="summary-card bg-grad-indigo">

                <div class="summary-icon">
                    <i class="fas fa-users"></i>
                </div>

                <div>

                    <div class="summary-value">
                        {{ number_format($todayStaff) }}
                    </div>

                    <div class="summary-title">
                        Staff Worked
                        {{ $selectedDate->format('d M') }}
                    </div>

                </div>

            </div>

        </div>


        {{-- HOURS --}}
        <div class="col-6 col-xl-3">

            <div class="summary-card bg-grad-emerald">

                <div class="summary-icon">
                    <i class="fas fa-clock"></i>
                </div>

                <div>

                    <div class="summary-value">
                        {{ number_format($todayHours, 2) }}
                    </div>

                    <div class="summary-title">
                        Total Hours
                    </div>

                </div>

            </div>

        </div>


        {{-- HOTELS --}}
        <div class="col-6 col-xl-3">

            <div class="summary-card bg-grad-amber">

                <div class="summary-icon">
                    <i class="fas fa-building"></i>
                </div>

                <div>

                    <div class="summary-value">
                        {{ number_format($activeHotels) }}
                    </div>

                    <div class="summary-title">
                        Hotels Active
                    </div>

                </div>

            </div>

        </div>


        {{-- UNIQUE STAFF --}}
        <div class="col-6 col-xl-3">

            <div class="summary-card bg-grad-sky">

                <div class="summary-icon">
                    <i class="fas fa-user-friends"></i>
                </div>

                <div>

                    <div class="summary-value">
                        {{ number_format($uniqueStaffThisMonth) }}
                    </div>

                    <div class="summary-title">
                        Unique Staff This Month
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TODAY'S ATTENDANCE + HOTEL CHARTS
    ========================================================== --}}

    <div class="row g-3 mb-3">


        {{-- =====================================================
             ATTENDANCE TABLE
        ====================================================== --}}

        <div class="col-12 col-xl-7">

            <div class="panel">

                <div class="panel-header"
                     style="--dot: var(--indigo)">

                    <span class="panel-dot"></span>

                    <h6 class="panel-title">

                        Attendance
                        ({{ $selectedDate->format('d F Y') }})

                    </h6>

                </div>


                <div class="panel-body">

                    <div class="table-scroll">

                        <table class="data-table">

                            <thead>

                                <tr>

                                    <th class="ctr">
                                        #
                                    </th>

                                    <th>
                                        Staff Name
                                    </th>

                                    <th>
                                        Hotel
                                    </th>

                                    <th>
                                        In Time
                                    </th>

                                    <th>
                                        Out Time
                                    </th>

                                    <th class="num">
                                        Total Hours
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($attendance as $row)

                                    @php

                                        $hotelColor =
                                            $chartData['colors'][
                                                $loop->index %
                                                max(count($chartData['colors']), 1)
                                            ]
                                            ?? '#4f46e5';


                                        $initials = collect(
                                            preg_split(
                                                '/\s+/',
                                                trim($row->staff_name)
                                            )
                                        )
                                        ->filter()
                                        ->map(
                                            fn($word) =>
                                                mb_substr($word, 0, 1)
                                        )
                                        ->take(2)
                                        ->implode('');

                                    @endphp


                                    <tr>

                                        <td class="ctr">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            <div class="staff">

                                                <span class="avatar"
                                                      style="--c: {{ $hotelColor }}">

                                                    {{ strtoupper($initials) }}

                                                </span>

                                                <span>
                                                    {{ $row->staff_name }}
                                                </span>

                                            </div>

                                        </td>


                                        <td>

                                            <span class="badge-hotel"
                                                  style="--c: {{ $hotelColor }}">

                                                {{ $row->hotel_name ?? 'N/A' }}

                                            </span>

                                        </td>


                                        <td>

                                            @if($row->check_in)

                                                {{ \Carbon\Carbon::parse($row->check_in)->format('h:i A') }}

                                            @else

                                                --

                                            @endif

                                        </td>


                                        <td>

                                            @if($row->check_out)

                                                {{ \Carbon\Carbon::parse($row->check_out)->format('h:i A') }}

                                            @else

                                                --

                                            @endif

                                        </td>


                                        <td class="num">

                                            <span class="badge-hours">

                                                {{ number_format((float)$row->worked_hours, 2) }}

                                            </span>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="empty-state">

                                            <i class="fas fa-calendar-times"></i>

                                            No attendance found for

                                            <strong>
                                                {{ $selectedDate->format('d F Y') }}
                                            </strong>

                                            @if($hotelId)

                                                <div class="mt-1">
                                                    for selected hotel.
                                                </div>

                                            @endif

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>


                            <tfoot>

                                <tr>

                                    <td colspan="4">
                                        Total
                                    </td>

                                    <td>
                                        {{ number_format($todayStaff) }}
                                        Staff
                                    </td>

                                    <td class="num">

                                        {{ number_format($todayHours, 2) }}

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             HOTEL CHARTS
        ====================================================== --}}

        <div class="col-12 col-xl-5">

            <div class="d-flex flex-column gap-3 h-100">


                {{-- STAFF BY HOTEL --}}

                <div class="panel">

                    <div class="panel-header"
                         style="--dot: var(--emerald)">

                        <span class="panel-dot"></span>

                        <h6 class="panel-title">

                            Staff Count by Hotel

                        </h6>

                    </div>


                    <div class="panel-body">

                        @if(count($chartData['hotels']) > 0)

                            <div class="chart-box">

                                <canvas id="staffHotelChart"></canvas>

                            </div>

                        @else

                            <div class="empty-state">

                                <i class="fas fa-chart-bar"></i>

                                No hotel attendance data

                            </div>

                        @endif

                    </div>

                </div>


                {{-- HOURS BY HOTEL --}}

                <div class="panel">

                    <div class="panel-header"
                         style="--dot: var(--amber)">

                        <span class="panel-dot"></span>

                        <h6 class="panel-title">

                            Total Hours by Hotel

                        </h6>

                    </div>


                    <div class="panel-body">

                        @if(count($chartData['hotels']) > 0)

                            <div class="chart-box">

                                <canvas id="hoursHotelChart"></canvas>

                            </div>

                        @else

                            <div class="empty-state">

                                <i class="fas fa-chart-pie"></i>

                                No hotel hours data

                            </div>

                        @endif

                    </div>

                </div>


            </div>

        </div>

    </div>


    {{-- =========================================================
         DAILY + MONTHLY
    ========================================================== --}}

    <div class="row g-3">


        {{-- =====================================================
             DAILY SUMMARY
        ====================================================== --}}

        <div class="col-12 col-xl-5">

            <div class="panel">

                <div class="panel-header"
                     style="--dot: var(--pink)">

                    <span class="panel-dot"></span>

                    <h6 class="panel-title">

                        Daily Summary
                        ({{ $selectedMonth->format('F Y') }})

                    </h6>

                </div>


                <div class="panel-body">

                    <div class="table-scroll">

                        <table class="data-table"
                               style="min-width: 420px">

                            <thead>

                                <tr>

                                    <th>
                                        Date
                                    </th>

                                    <th class="num">
                                        Total Staff
                                    </th>

                                    <th class="num">
                                        Total Hours
                                    </th>

                                    <th class="num">
                                        Hotels
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($daily as $day)

                                    <tr class="{{
                                        $day['date'] ===
                                        $selectedDate->format('Y-m-d')
                                        ? 'is-today'
                                        : ''
                                    }}">

                                        <td>

                                            {{ \Carbon\Carbon::parse(
                                                $day['date']
                                            )->format('d M Y') }}

                                        </td>

                                        <td class="num">

                                            {{ number_format(
                                                $day['staff']
                                            ) }}

                                        </td>

                                        <td class="num">

                                            {{ number_format(
                                                $day['hours'],
                                                2
                                            ) }}

                                        </td>

                                        <td class="num">

                                            {{ number_format(
                                                $day['hotels']
                                            ) }}

                                        </td>

                                    </tr>

                                @endforeach


                                @if(count($daily) === 0)

                                    <tr>

                                        <td colspan="4"
                                            class="empty-state">

                                            <i class="fas fa-calendar-times"></i>

                                            No monthly attendance data

                                        </td>

                                    </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             MONTHLY SUMMARY
        ====================================================== --}}

        <div class="col-12 col-xl-7">

            <div class="panel">

                <div class="panel-header"
                     style="--dot: var(--sky)">

                    <span class="panel-dot"></span>

                    <h6 class="panel-title">

                        Monthly Summary
                        ({{ $selectedMonth->format('F Y') }})

                    </h6>

                </div>


                <div class="panel-body">


                    {{-- MONTHLY STATS --}}

                    <div class="row g-2 mb-3">


                        {{-- STAFF --}}

                        <div class="col-6 col-md-3">

                            <div class="monthly-stat tint-indigo">

                                <i class="fas fa-users"></i>

                                <div class="monthly-stat-value">

                                    {{ number_format(
                                        $uniqueStaffThisMonth
                                    ) }}

                                </div>

                                <div class="monthly-stat-title">

                                    Unique Staff

                                </div>

                            </div>

                        </div>


                        {{-- HOURS --}}

                        <div class="col-6 col-md-3">

                            <div class="monthly-stat tint-emerald">

                                <i class="fas fa-clock"></i>

                                <div class="monthly-stat-value">

                                    {{ number_format(
                                        $monthHours,
                                        2
                                    ) }}

                                </div>

                                <div class="monthly-stat-title">

                                    Total Hours

                                </div>

                            </div>

                        </div>


                        {{-- HOTELS --}}

                        <div class="col-6 col-md-3">

                            <div class="monthly-stat tint-amber">

                                <i class="fas fa-building"></i>

                                <div class="monthly-stat-value">

                                    {{ number_format(
                                        $activeHotels
                                    ) }}

                                </div>

                                <div class="monthly-stat-title">

                                    Active Hotels

                                </div>

                            </div>

                        </div>


                        {{-- DAYS --}}

                        <div class="col-6 col-md-3">

                            <div class="monthly-stat tint-pink">

                                <i class="fas fa-calendar-alt"></i>

                                <div class="monthly-stat-value">

                                    {{ number_format(
                                        $workingDays
                                    ) }}

                                </div>

                                <div class="monthly-stat-title">

                                    Working Days

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- MONTHLY HOURS CHART --}}

                    <div class="chart-section">

                        <h6 class="chart-section-title">

                            Monthly Hours
                            ({{ number_format($monthHours, 2) }} hrs total)

                        </h6>


                        <div class="chart-legend">

                            <span style="--c: #10b981">

                                <i></i>
                                Above Average

                            </span>


                            <span style="--c: #38bdf8">

                                <i></i>
                                Below Average

                            </span>


                            <span style="--c: #f59e0b">

                                <i></i>
                                Selected Date

                            </span>


                            <span style="--c: #ef4444">

                                <i></i>

                                Average
                                ({{ number_format(
                                    $avgHours,
                                    2
                                ) }} hrs/day)

                            </span>

                        </div>


                        <div class="chart-box">

                            <canvas id="monthlyHoursChart"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     CHART.JS
========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATA FROM CONTROLLER
    |--------------------------------------------------------------------------
    */

    const data = @json($chartData);

    const selectedMonthName =
        @json($selectedMonth->format('F Y'));

    const selectedDate =
        @json($selectedDate->format('Y-m-d'));


    /*
    |--------------------------------------------------------------------------
    | COMMON
    |--------------------------------------------------------------------------
    */

    const font = {
        size: 11
    };

    const gridCol = '#edf0f6';

    const muted = '#64748b';

    const cleanGrid = {
        display: false
    };

    const softGrid = {
        color: gridCol
    };

    const ticks = {
        font: font,
        color: muted
    };


    /*
    |--------------------------------------------------------------------------
    | STAFF COUNT BY HOTEL
    |--------------------------------------------------------------------------
    */

    const staffHotelCanvas =
        document.getElementById('staffHotelChart');

    if (staffHotelCanvas && data.hotels.length > 0) {

        new Chart(staffHotelCanvas, {

            type: 'bar',

            data: {

                labels: data.hotels,

                datasets: [{

                    label: 'Staff',

                    data: data.staffCounts,

                    backgroundColor: data.colors,

                    borderRadius: 8,

                    maxBarThickness: 48

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ' Staff: ' +
                                    Number(
                                        context.raw
                                    ).toLocaleString();

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: cleanGrid,

                        ticks: ticks

                    },

                    y: {

                        beginAtZero: true,

                        grid: softGrid,

                        ticks: {

                            ...ticks,

                            stepSize: 1

                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL HOURS BY HOTEL
    |--------------------------------------------------------------------------
    */

    const hoursHotelCanvas =
        document.getElementById('hoursHotelChart');

    if (hoursHotelCanvas && data.hotels.length > 0) {

        new Chart(hoursHotelCanvas, {

            type: 'bar',

            data: {

                labels: data.hotels,

                datasets: [{

                    label: 'Hours',

                    data: data.hourTotals,

                    backgroundColor: data.colors,

                    borderRadius: 8,

                    maxBarThickness: 28

                }]

            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ' Hours: ' +
                                    Number(
                                        context.raw
                                    ).toFixed(2);

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        beginAtZero: true,

                        grid: softGrid,

                        ticks: ticks

                    },

                    y: {

                        grid: cleanGrid,

                        ticks: ticks

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | MONTHLY HOURS
    |--------------------------------------------------------------------------
    */

    const monthlyHoursCanvas =
        document.getElementById(
            'monthlyHoursChart'
        );


    if (monthlyHoursCanvas) {

        const hourBarColors =
            data.dailyHours.map(function (
                hours,
                index
            ) {

                const day =
                    data.days[index];

                const fullDate =
                    selectedMonthName;

                if (
                    data.todayIndex !== null &&
                    data.todayIndex !== undefined &&
                    index === data.todayIndex
                ) {

                    return '#f59e0b';

                }

                return Number(hours) >=
                    Number(data.avgHours)

                    ? '#10b981'

                    : '#38bdf8';

            });


        new Chart(monthlyHoursCanvas, {

            type: 'bar',

            data: {

                labels: data.days,

                datasets: [

                    {

                        type: 'bar',

                        label: 'Total Hours',

                        data: data.dailyHours,

                        backgroundColor:
                            hourBarColors,

                        borderRadius: 6,

                        maxBarThickness: 34,

                        order: 2

                    },


                    {

                        type: 'line',

                        label: 'Average',

                        data:
                            data.dailyHours.map(
                                function () {
                                    return data.avgHours;
                                }
                            ),

                        borderColor: '#ef4444',

                        borderWidth: 2,

                        borderDash: [
                            6,
                            4
                        ],

                        pointRadius: 0,

                        pointHoverRadius: 0,

                        fill: false,

                        order: 1

                    }

                ]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    mode: 'index',

                    intersect: false

                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            title: function (
                                tooltipItems
                            ) {

                                if (
                                    !tooltipItems.length
                                ) {
                                    return '';
                                }

                                return 'Day ' +
                                    tooltipItems[0]
                                        .label +
                                    ' ' +
                                    selectedMonthName;

                            },

                            label: function (
                                context
                            ) {

                                return context.dataset
                                    .label +
                                    ': ' +
                                    Number(
                                        context.raw
                                    ).toFixed(2) +
                                    ' hrs';

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: cleanGrid,

                        ticks: ticks,

                        title: {

                            display: true,

                            text:
                                'Date (' +
                                selectedMonthName +
                                ')',

                            font: font,

                            color: muted

                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: softGrid,

                        ticks: ticks,

                        title: {

                            display: true,

                            text: 'Hours',

                            font: font,

                            color: muted

                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER DATE VALIDATION
    |--------------------------------------------------------------------------
    */

    const dateInput =
        document.getElementById('filterDate');

    const monthInput =
        document.getElementById('filterMonth');


    /*
    |--------------------------------------------------------------------------
    | WHEN MONTH CHANGES
    |--------------------------------------------------------------------------
    | Automatically keep selected date inside selected month
    |--------------------------------------------------------------------------
    */

    if (monthInput && dateInput) {

        monthInput.addEventListener(
            'change',
            function () {

                if (!this.value) {
                    return;
                }

                const month =
                    this.value;

                const currentDate =
                    dateInput.value;

                if (
                    currentDate &&
                    currentDate.substring(0, 7) === month
                ) {
                    return;
                }

                /*
                | Set date to first day
                | of selected month
                */

                dateInput.value =
                    month + '-01';

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATE -> MONTH SYNC
    |--------------------------------------------------------------------------
    */

    if (dateInput && monthInput) {

        dateInput.addEventListener(
            'change',
            function () {

                if (!this.value) {
                    return;
                }

                /*
                | When date is changed,
                | automatically update month
                */

                monthInput.value =
                    this.value.substring(0, 7);

            }
        );

    }


});

</script>

@endsection

