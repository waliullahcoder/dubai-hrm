@extends('layouts.admin.app')

@section('content')

{{-- =========================================================
     DATA (replace with controller data later)
========================================================== --}}
@php
    $today = '2025-09-10';

    $hotels = [
        'Hilton Dubai'   => '#4f46e5',
        'Marriott Hotel' => '#0d9488',
        'Rixos Hotel'    => '#f59e0b',
    ];

    $attendance = [
        ['name' => 'Rahim Uddin',     'hotel' => 'Hilton Dubai',   'in' => '08:00', 'out' => '17:00', 'hours' => 9.0],
        ['name' => 'Karim Ali',       'hotel' => 'Hilton Dubai',   'in' => '09:00', 'out' => '18:00', 'hours' => 9.0],
        ['name' => 'Salman Khan',     'hotel' => 'Marriott Hotel', 'in' => '08:30', 'out' => '17:30', 'hours' => 9.0],
        ['name' => 'Jahid Hasan',     'hotel' => 'Rixos Hotel',    'in' => '10:00', 'out' => '18:00', 'hours' => 8.0],
        ['name' => 'Mohammed Imran',  'hotel' => 'Rixos Hotel',    'in' => '08:00', 'out' => '16:30', 'hours' => 7.5],
    ];

    $daily = [
        ['date' => '2025-09-01', 'staff' => 6, 'hours' => 54.0, 'hotels' => 3],
        ['date' => '2025-09-02', 'staff' => 4, 'hours' => 36.5, 'hotels' => 2],
        ['date' => '2025-09-03', 'staff' => 7, 'hours' => 61.0, 'hotels' => 3],
        ['date' => '2025-09-04', 'staff' => 5, 'hours' => 42.0, 'hotels' => 3],
        ['date' => '2025-09-05', 'staff' => 8, 'hours' => 68.5, 'hotels' => 3],
        ['date' => '2025-09-06', 'staff' => 6, 'hours' => 50.0, 'hotels' => 2],
        ['date' => '2025-09-07', 'staff' => 5, 'hours' => 44.0, 'hotels' => 3],
        ['date' => '2025-09-08', 'staff' => 4, 'hours' => 38.0, 'hotels' => 2],
        ['date' => '2025-09-09', 'staff' => 5, 'hours' => 52.0, 'hotels' => 3],
        ['date' => '2025-09-10', 'staff' => 5, 'hours' => 42.5, 'hotels' => 3],
    ];

    $uniqueStaffThisMonth = 28;

    // Derived values
    $attendanceCollection = collect($attendance);
    $todayStaff   = $attendanceCollection->count();
    $todayHours   = $attendanceCollection->sum('hours');
    $activeHotels = $attendanceCollection->pluck('hotel')->unique()->count();

    $staffByHotel = $attendanceCollection->groupBy('hotel')->map->count();
    $hoursByHotel = $attendanceCollection->groupBy('hotel')->map(fn ($rows) => $rows->sum('hours'));

    $monthHours   = collect($daily)->sum('hours');
    $workingDays  = count($daily);

    $hotelLabels  = array_keys($hotels);
    $hotelColors  = array_values($hotels);

    $chartData = [
        'hotels'      => $hotelLabels,
        'colors'      => $hotelColors,
        'staffCounts' => collect($hotelLabels)->map(fn ($h) => $staffByHotel[$h] ?? 0)->all(),
        'hourTotals'  => collect($hotelLabels)->map(fn ($h) => $hoursByHotel[$h] ?? 0)->all(),
        'days'        => collect($daily)->map(fn ($d) => substr($d['date'], 8, 2))->all(),
        'dailyStaff'  => collect($daily)->pluck('staff')->all(),
        'dailyHours'  => collect($daily)->pluck('hours')->all(),
    ];

    $initials = fn ($name) => collect(explode(' ', $name))
        ->map(fn ($w) => mb_substr($w, 0, 1))
        ->take(2)
        ->implode('');
@endphp


{{-- =========================================================
     STYLES
========================================================== --}}
<style>
    /* ---------- Design tokens ---------- */
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
    }

    .hrm *,
    .hrm *::before,
    .hrm *::after { box-sizing: border-box; }

    /* ---------- Page header ---------- */
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
        background: linear-gradient(90deg, var(--indigo), var(--pink));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hrm-subtitle {
        margin: 2px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    /* ---------- Filters ---------- */
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
        margin-bottom: 4px;
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
        transition: border-color .15s, box-shadow .15s;
    }

    .filter-control:focus {
        border-color: var(--indigo);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
    }

    /* ---------- Summary cards ---------- */
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
        background: rgba(255, 255, 255, .14);
    }

    .summary-icon {
        flex: 0 0 auto;
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, .22);
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

    .bg-grad-indigo  { background: linear-gradient(135deg, var(--indigo), var(--indigo-2)); }
    .bg-grad-emerald { background: linear-gradient(135deg, var(--emerald), var(--emerald-2)); }
    .bg-grad-amber   { background: linear-gradient(135deg, var(--amber-2), var(--amber)); }
    .bg-grad-sky     { background: linear-gradient(135deg, var(--sky-2), var(--sky)); }

    /* ---------- Panels ---------- */
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
        border-radius: 50%;
        background: var(--dot, var(--indigo));
    }

    .panel-title {
        margin: 0;
        font-size: 14px;
        font-weight: 800;
    }

    .panel-body { padding: 12px 14px; }

    /* ---------- Tables ---------- */
    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 520px;
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

    .data-table th:first-child { border-top-left-radius: 8px; }
    .data-table th:last-child  { border-top-right-radius: 8px; }

    .data-table td {
        padding: 10px 12px;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .data-table tbody tr:hover { background: #f8faff; }

    .data-table .num { text-align: right; }
    .data-table .ctr { text-align: center; }

    .data-table tfoot td {
        background: linear-gradient(90deg, #e0e7ff, #fce7f3);
        color: #312e81;
        font-weight: 800;
        border-bottom: 0;
    }

    .data-table tr.is-today td {
        background: #fff7e6;
        font-weight: 700;
    }

    /* ---------- Staff avatar + hotel badge ---------- */
    .staff {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .avatar {
        width: 28px;
        height: 28px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--c);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-hotel {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        background: color-mix(in srgb, var(--c) 14%, #fff);
        color: var(--c);
        font-size: 11.5px;
        font-weight: 700;
    }

    .badge-hours {
        display: inline-block;
        min-width: 46px;
        padding: 3px 8px;
        border-radius: 8px;
        background: #dcfce7;
        color: #166534;
        font-weight: 700;
        text-align: center;
    }

    /* ---------- Charts ---------- */
    .chart-box    { position: relative; height: 210px; }
    .chart-box-lg { position: relative; height: 260px; }

    /* ---------- Monthly stats ---------- */
    .monthly-stat {
        height: 100%;
        padding: 12px 8px;
        border-radius: 12px;
        text-align: center;
        background: var(--tint);
        color: var(--ink);
    }

    .monthly-stat i     { font-size: 20px; margin-bottom: 4px; }
    .monthly-stat-value { font-size: 22px; line-height: 1.1; font-weight: 800; }
    .monthly-stat-title { margin-top: 2px; font-size: 11px; font-weight: 600; color: var(--muted); }

    .tint-indigo  { --tint: #eef2ff; --ink: #4338ca; }
    .tint-emerald { --tint: #dcfce7; --ink: #15803d; }
    .tint-amber   { --tint: #fff1dc; --ink: #c2410c; }
    .tint-pink    { --tint: #fce7f3; --ink: #be185d; }

    /* ---------- Responsive ---------- */
    @media (max-width: 767px) {
        .hrm { padding: 10px 6px 20px; }
        .hrm-title { font-size: 18px; }
        .summary-card { min-height: 78px; padding: 10px; gap: 8px; }
        .summary-icon { width: 38px; height: 38px; font-size: 16px; }
        .summary-value { font-size: 21px; }
        .summary-title { font-size: 11px; }
        .chart-box, .chart-box-lg { height: 220px; }
    }
</style>


<div class="container-fluid hrm">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="hrm-header">
        <div>
            <h4 class="hrm-title">Staff Attendance Dashboard</h4>
            <p class="hrm-subtitle">Staff attendance and working hours across hotels</p>
        </div>
    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}
    <div class="filter-card">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <label class="filter-label" for="filterHotel">Select Hotel</label>
                <select id="filterHotel" class="filter-control">
                    <option>All Hotels</option>
                    @foreach ($hotels as $hotel => $color)
                        <option>{{ $hotel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="filter-label" for="filterDate">Select Date</label>
                <input id="filterDate" type="date" class="filter-control" value="{{ $today }}">
            </div>

            <div class="col-12 col-md-4">
                <label class="filter-label" for="filterMonth">Select Month</label>
                <select id="filterMonth" class="filter-control">
                    <option>September 2025</option>
                    <option>August 2025</option>
                    <option>July 2025</option>
                </select>
            </div>

        </div>
    </div>


    {{-- =========================================================
         TOP SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-3">

        @foreach ([
            ['class' => 'bg-grad-indigo',  'icon' => 'fa-users',    'value' => $todayStaff,                 'title' => 'Staff Worked Today (All Hotels)'],
            ['class' => 'bg-grad-emerald', 'icon' => 'fa-clock',    'value' => number_format($todayHours, 1), 'title' => 'Total Hours Today'],
            ['class' => 'bg-grad-amber',   'icon' => 'fa-building', 'value' => $activeHotels,               'title' => 'Hotels Active Today'],
            ['class' => 'bg-grad-sky',     'icon' => 'fa-user-friends', 'value' => $uniqueStaffThisMonth,   'title' => 'Total Staff This Month (Unique)'],
        ] as $card)
            <div class="col-6 col-xl-3">
                <div class="summary-card {{ $card['class'] }}">
                    <div class="summary-icon"><i class="fas {{ $card['icon'] }}"></i></div>
                    <div>
                        <div class="summary-value">{{ $card['value'] }}</div>
                        <div class="summary-title">{{ $card['title'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>


    {{-- =========================================================
         TODAY'S ATTENDANCE + HOTEL CHARTS
    ========================================================== --}}
    <div class="row g-3 mb-3">

        {{-- Attendance table --}}
        <div class="col-12 col-xl-7">
            <div class="panel">
                <div class="panel-header" style="--dot: var(--indigo)">
                    <span class="panel-dot"></span>
                    <h6 class="panel-title">
                        Today's Attendance ({{ \Carbon\Carbon::parse($today)->format('d F Y') }})
                    </h6>
                </div>

                <div class="panel-body">
                    <div class="table-scroll">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="ctr">#</th>
                                    <th>Staff Name</th>
                                    <th>Hotel</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th class="num">Total Hours</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($attendance as $row)
                                    <tr>
                                        <td class="ctr">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="staff">
                                                <span class="avatar" style="--c: {{ $hotels[$row['hotel']] }}">
                                                    {{ $initials($row['name']) }}
                                                </span>
                                                {{ $row['name'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-hotel" style="--c: {{ $hotels[$row['hotel']] }}">
                                                {{ $row['hotel'] }}
                                            </span>
                                        </td>
                                        <td>{{ $row['in'] }}</td>
                                        <td>{{ $row['out'] }}</td>
                                        <td class="num">
                                            <span class="badge-hours">{{ number_format($row['hours'], 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="4">Total</td>
                                    <td>{{ $todayStaff }} Staff</td>
                                    <td class="num">{{ number_format($todayHours, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="col-12 col-xl-5">
            <div class="d-flex flex-column gap-3 h-100">

                <div class="panel">
                    <div class="panel-header" style="--dot: var(--emerald)">
                        <span class="panel-dot"></span>
                        <h6 class="panel-title">Today's Staff Count by Hotel</h6>
                    </div>
                    <div class="panel-body">
                        <div class="chart-box"><canvas id="staffHotelChart"></canvas></div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header" style="--dot: var(--amber)">
                        <span class="panel-dot"></span>
                        <h6 class="panel-title">Today's Total Hours by Hotel</h6>
                    </div>
                    <div class="panel-body">
                        <div class="chart-box"><canvas id="hoursHotelChart"></canvas></div>
                    </div>
                </div>

            </div>
        </div>

    </div>


    {{-- =========================================================
         DAILY + MONTHLY SUMMARY
    ========================================================== --}}
    <div class="row g-3">

        {{-- Daily summary --}}
        <div class="col-12 col-xl-5">
            <div class="panel">
                <div class="panel-header" style="--dot: var(--pink)">
                    <span class="panel-dot"></span>
                    <h6 class="panel-title">Daily Summary (September 2025)</h6>
                </div>

                <div class="panel-body">
                    <div class="table-scroll">
                        <table class="data-table" style="min-width: 420px">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="num">Total Staff</th>
                                    <th class="num">Total Hours</th>
                                    <th class="num">Hotels Active</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daily as $day)
                                    <tr class="{{ $day['date'] === $today ? 'is-today' : '' }}">
                                        <td>{{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}</td>
                                        <td class="num">{{ $day['staff'] }}</td>
                                        <td class="num">{{ number_format($day['hours'], 2) }}</td>
                                        <td class="num">{{ $day['hotels'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly summary --}}
        <div class="col-12 col-xl-7">
            <div class="panel">
                <div class="panel-header" style="--dot: var(--sky)">
                    <span class="panel-dot"></span>
                    <h6 class="panel-title">Monthly Summary (September 2025)</h6>
                </div>

                <div class="panel-body">

                    <div class="row g-2 mb-3">
                        @foreach ([
                            ['tint' => 'tint-indigo',  'icon' => 'fa-users',        'value' => $uniqueStaffThisMonth,         'title' => 'Total Staff (Unique)'],
                            ['tint' => 'tint-emerald', 'icon' => 'fa-clock',        'value' => number_format($monthHours, 1), 'title' => 'Total Hours'],
                            ['tint' => 'tint-amber',   'icon' => 'fa-building',     'value' => count($hotels),                'title' => 'Total Hotels'],
                            ['tint' => 'tint-pink',    'icon' => 'fa-calendar-alt', 'value' => $workingDays,                  'title' => 'Working Days (So Far)'],
                        ] as $stat)
                            <div class="col-6 col-md-3">
                                <div class="monthly-stat {{ $stat['tint'] }}">
                                    <i class="fas {{ $stat['icon'] }}"></i>
                                    <div class="monthly-stat-value">{{ $stat['value'] }}</div>
                                    <div class="monthly-stat-title">{{ $stat['title'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="chart-box-lg"><canvas id="monthlySummaryChart"></canvas></div>

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

    const data = @json($chartData);

    const font    = { size: 11 };
    const gridCol = '#edf0f6';
    const muted   = '#64748b';

    /* ---------- Shared axis helpers ---------- */
    const cleanGrid = { display: false };
    const softGrid  = { color: gridCol };
    const ticks     = { font, color: muted };

    /* ---------- Staff count by hotel (bar) ---------- */
    new Chart(document.getElementById('staffHotelChart'), {
        type: 'bar',
        data: {
            labels: data.hotels,
            datasets: [{
                data: data.staffCounts,
                backgroundColor: data.colors,
                borderRadius: 8,
                maxBarThickness: 48,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: cleanGrid, ticks },
                y: { beginAtZero: true, grid: softGrid, ticks: { ...ticks, stepSize: 1 } },
            },
        },
    });

    /* ---------- Total hours by hotel (horizontal bar) ---------- */
    new Chart(document.getElementById('hoursHotelChart'), {
        type: 'bar',
        data: {
            labels: data.hotels,
            datasets: [{
                data: data.hourTotals,
                backgroundColor: data.colors,
                borderRadius: 8,
                maxBarThickness: 28,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: softGrid, ticks },
                y: { grid: cleanGrid, ticks },
            },
        },
    });

    /* ---------- Monthly summary (dual-axis line) ---------- */
    const monthlyCanvas = document.getElementById('monthlySummaryChart');
    const monthlyCtx    = monthlyCanvas.getContext('2d');

    const makeGradient = (rgb) => {
        const g = monthlyCtx.createLinearGradient(0, 0, 0, 260);
        g.addColorStop(0, `rgba(${rgb}, .30)`);
        g.addColorStop(1, `rgba(${rgb}, 0)`);
        return g;
    };

    const lineStyle = {
        tension: 0.35,
        borderWidth: 2.5,
        pointRadius: 3,
        pointHoverRadius: 6,
        pointBackgroundColor: '#fff',
        pointBorderWidth: 2,
        fill: true,
    };

    new Chart(monthlyCanvas, {
        type: 'line',
        data: {
            labels: data.days,
            datasets: [
                {
                    ...lineStyle,
                    label: 'Total Staff (Daily)',
                    data: data.dailyStaff,
                    yAxisID: 'staff',
                    borderColor: '#4f46e5',
                    pointBorderColor: '#4f46e5',
                    backgroundColor: makeGradient('79, 70, 229'),
                },
                {
                    ...lineStyle,
                    label: 'Total Hours (Daily)',
                    data: data.dailyHours,
                    yAxisID: 'hours',
                    borderColor: '#10b981',
                    pointBorderColor: '#10b981',
                    backgroundColor: makeGradient('16, 185, 129'),
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'start',
                    labels: { usePointStyle: true, boxWidth: 8, font: { size: 11, weight: '600' } },
                },
            },
            scales: {
                x: {
                    grid: cleanGrid,
                    border: { display: false },
                    ticks,
                    title: { display: true, text: 'Date (September 2025)', font, color: muted },
                },
                staff: {
                    position: 'left',
                    beginAtZero: true,
                    suggestedMax: 10,
                    grid: softGrid,
                    ticks: { ...ticks, stepSize: 2 },
                    title: { display: true, text: 'Staff Count', font, color: '#4f46e5' },
                },
                hours: {
                    position: 'right',
                    beginAtZero: true,
                    suggestedMax: 100,
                    grid: { drawOnChartArea: false },
                    ticks,
                    title: { display: true, text: 'Total Hours', font, color: '#10b981' },
                },
            },
        },
    });

});
</script>

@endsection