@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
    use Carbon\Carbon;

    $staff = \App\Models\Staff::where('user_id', auth()->id())->first();

    // Selected month (Y-m)
    try {
        $current = Carbon::createFromFormat('Y-m', request('month', now()->format('Y-m')))->startOfMonth();
    } catch (\Exception $e) {
        $current = now()->startOfMonth();
    }
    $prevMonth = $current->copy()->subMonth()->format('Y-m');
    $nextMonth = $current->copy()->addMonth()->format('Y-m');

    // Attendance rows for the selected month
    $rows = collect();
    if ($staff) {
        $rows = DB::table('hrm_employee_attendances')
            ->where('employee_id', $staff->id)
            ->whereNotNull('check_in')
            ->whereYear('attendance_date', $current->year)
            ->whereMonth('attendance_date', $current->month)
            ->orderBy('attendance_date')
            ->get();
    }

    $totalHours  = (float) $rows->sum('worked_hours');
    $totalDays   = $rows->filter(fn($r) => !empty($r->check_out))->count();
    $avgPerDay   = $totalDays > 0 ? $totalHours / $totalDays : 0;

    $t = fn($v) => $v ? Carbon::parse($v)->format('h:i A') : '--:--';
    $n = fn($v) => rtrim(rtrim(number_format((float) $v, 1, '.', ''), '0'), '.');
@endphp

<style>
    .wh-page { --blue:#2563eb; --navy:#0f1f6b; --muted:#64748b; --line:#e6ecf5; }

    /* Profile */
    .wh-page .profile {
        display:flex; align-items:center; gap:14px; background:#fff; border:1px solid var(--line);
        border-radius:16px; padding:12px 14px; margin-bottom:12px; box-shadow:0 6px 18px rgba(15,31,107,.05);
    }
    .wh-page .profile img {
        width:70px; height:70px; border-radius:50%; object-fit:cover; background:#e2e8f0;
        border:2px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,.12); flex-shrink:0;
    }
    .wh-page .profile .info { flex:1; min-width:0; }
    .wh-page .profile h5 { margin:0; font-weight:800; color:var(--navy); text-transform:uppercase; font-size:17px; }
    .wh-page .profile .line { font-size:13px; color:#334155; display:flex; align-items:center; gap:8px; margin-top:3px; }
    .wh-page .profile .line i { color:var(--navy); width:14px; }
    .wh-page .profile .go { color:var(--muted); font-size:16px; }

    /* Month switcher */
    .wh-page .month-nav { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .wh-page .month-nav a.arrow { color:var(--navy); font-size:18px; padding:6px 10px; text-decoration:none; }
    .wh-page .month-pill {
        background:#eaf2ff; border-radius:14px; padding:9px 22px; color:var(--navy);
        font-weight:800; font-size:17px; display:flex; align-items:center; gap:10px; position:relative;
    }
    .wh-page .month-pill input {
        position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%;
    }

    /* Summary cards */
    .wh-page .sum-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:16px; }
    .wh-page .sum-card {
        border-radius:14px; text-align:center; padding:12px 6px; border:1px solid transparent;
    }
    .wh-page .sum-card i { font-size:22px; margin-bottom:4px; display:block; }
    .wh-page .sum-card small { display:block; font-size:12px; color:#334155; font-weight:600; line-height:1.2; }
    .wh-page .sum-card strong { display:block; font-size:26px; font-weight:800; color:var(--navy); line-height:1.15; margin-top:4px; }
    .wh-page .sum-card span { font-size:13px; color:#334155; font-weight:600; }
    .wh-page .c-blue   { background:#eaf2ff; border-color:#d5e5ff; } .wh-page .c-blue i   { color:#2563eb; }
    .wh-page .c-green  { background:#e8faf1; border-color:#ccf3e0; } .wh-page .c-green i  { color:#16a34a; }
    .wh-page .c-orange { background:#fff4e6; border-color:#ffe3c2; } .wh-page .c-orange i { color:#f97316; }

    /* Daily table */
    .wh-page .daily-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
    .wh-page .daily-head h6 { margin:0; font-weight:800; color:var(--navy); font-size:19px; }
    .wh-page .daily-head a { color:var(--blue); font-weight:600; font-size:14px; text-decoration:none; display:flex; align-items:center; gap:6px; }
    .wh-page .tbl-wrap { border:1px solid var(--line); border-radius:12px; overflow:hidden; background:#fff; }
    .wh-page table.daily { width:100%; border-collapse:collapse; font-size:13px; }
    .wh-page table.daily th {
        background:#eaf2ff; color:var(--navy); font-weight:700; text-align:center; padding:10px 4px;
    }
    .wh-page table.daily td {
        text-align:center; padding:11px 4px; border-top:1px solid var(--line); color:#1e293b;
    }
    .wh-page table.daily td:first-child { padding-left:8px; }
    .wh-page .badge-st {
        display:inline-block; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700;
    }
    .wh-page .st-done    { background:#d9f7e5; color:#15803d; }
    .wh-page .st-working { background:#fff0d6; color:#b45309; }
    .wh-page .row-go { color:var(--navy); text-decoration:none; }

    @media (max-width:380px) {
        .wh-page table.daily { font-size:12px; }
        .wh-page .sum-card strong { font-size:22px; }
    }
</style>

<div class="container py-4">
    <div class="app-container position-relative wh-page">

        <!-- Header -->
        @include('hrm.dashboard.header')

       
        <!-- Month switcher -->
        <div class="month-nav marginbody">
            <a class="arrow" href="?month={{ $prevMonth }}"><i class="fas fa-chevron-left"></i></a>

            <form method="GET" class="month-pill">
                <i class="far fa-calendar-check"></i>
                {{ $current->format('F Y') }}
                <i class="fas fa-chevron-down" style="font-size:13px"></i>
                <input type="month" name="month" value="{{ $current->format('Y-m') }}" onchange="this.form.submit()">
            </form>

            <a class="arrow" href="?month={{ $nextMonth }}"><i class="fas fa-chevron-right"></i></a>
        </div>

        <!-- Summary cards -->
        <div class="sum-grid marginbody">
            <div class="sum-card c-blue">
                <i class="far fa-calendar-check"></i>
                <small>Total Working Days</small>
                <strong>{{ $totalDays }}</strong>
                <span>Days</span>
            </div>
            <div class="sum-card c-green">
                <i class="far fa-clock"></i>
                <small>Total Working Hours</small>
                <strong>{{ $n($totalHours) }}</strong>
                <span>Hours</span>
            </div>
            <div class="sum-card c-orange">
                <i class="fas fa-chart-simple"></i>
                <small>Average Per Day</small>
                <strong>{{ $n($avgPerDay) }}</strong>
                <span>Hours</span>
            </div>
        </div>

        <!-- Daily Working Hours -->
        <div class="daily-head marginbody">
            <h6>Daily Working Hours</h6>
            <a href="{{ url('admin/my-payment-report') }}">
                <i class="far fa-calendar-check"></i> View Calendar
            </a>
        </div>

        <div class="tbl-wrap marginbody">
            <table class="daily">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td>{{ Carbon::parse($r->attendance_date)->format('d M') }}</td>
                            <td>{{ $t($r->check_in) }}</td>
                            <td>{{ $t($r->check_out) }}</td>
                            <td>{{ number_format((float) ($r->worked_hours ?? 0), 2) }}</td>
                            <td>
                                @if($r->check_out)
                                    <span class="badge-st st-done">Completed</span>
                                @else
                                    <span class="badge-st st-working">Working</span>
                                @endif
                            </td>
                            <td><a class="row-go" href="#"><i class="fas fa-chevron-right"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted py-4">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br><br><br><br>
        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection