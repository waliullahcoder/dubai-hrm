@extends('layouts.admin.app')

@section('content')
@if(auth()->user()->role_status==4)
@include('hrm.dashboard.app_style')

@php

    $staff = \App\Models\Staff::where('user_id', auth()->id())->first();
    $cur   = $staff->currency_code ?? 'BDT';
    $fmt   = fn($n) => number_format((float) $n, 2, '.', ',');

    $year = (int) request('year', now()->year);

    /*
     |------------------------------------------------------------------
     | Monthly payment rows – pass from controller as $paymentdata['history']
     | Each row: [
     |   'month'        => '2026-09',        // Y-m  (or any date in that month)
     |   'net_payable'  => 550,
     |   'paid_amount'  => 0,
     |   'payment_date' => '2026-08-05',     // null if unpaid
     |   'method'       => 'Bank Transfer',  // null if unpaid
     | ]
     |------------------------------------------------------------------
    */
    $all = collect($paymentdata['history'] ?? [])->map(function ($r) {
        $r = (array) $r;
        $r['date'] = \Carbon\Carbon::parse(($r['month'] ?? now()->format('Y-m')) . (strlen($r['month'] ?? '') === 7 ? '-01' : ''));
        $r['balance'] = max(0, (float) ($r['net_payable'] ?? 0) - (float) ($r['paid_amount'] ?? 0));
        return $r;
    });

    $years   = $all->map(fn($r) => $r['date']->year)->push(now()->year)->unique()->sortDesc()->values();
    $rows    = $all->filter(fn($r) => $r['date']->year === $year)->sortByDesc('date')->values();

    $totalSalary  = (float) $rows->sum('net_payable');
    $totalPaid    = (float) $rows->sum('paid_amount');
    $totalPending = (float) $rows->sum('balance');
    $totalMonths  = $rows->count();
    $tillLabel    = $rows->isNotEmpty() ? $rows->first()['date']->format('M Y') : now()->format('M Y');
@endphp

<style>
    .ph-page { --blue:#2563eb; --navy:#0f1f6b; --muted:#64748b; --line:#e6ecf5; }

    .ph-page .top-card { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:14px; }
    .ph-page .who { display:flex; align-items:center; gap:12px; }
    .ph-page .avatar {
        width:58px; height:58px; border-radius:50%; object-fit:cover; background:#e2e8f0;
        border:2px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,.12);
    }
    .ph-page .who h5 { margin:0; font-weight:800; color:var(--navy); text-transform:uppercase; font-size:17px; }
    .ph-page .who small { display:block; color:var(--muted); font-size:13px; line-height:1.3; }
    .ph-page .who .dept::before {
        content:""; display:inline-block; width:8px; height:8px; border-radius:50%;
        background:#22c55e; margin-right:6px;
    }
    .ph-page .pill-select {
        display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--line);
        border-radius:12px; padding:6px 12px; color:var(--navy); font-weight:700;
    }
    .ph-page .pill-select select {
        border:0; outline:0; background:transparent; font-weight:700; color:var(--navy);
        font-size:14px; cursor:pointer;
    }

    /* Summary */
    .ph-page .sum-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; margin-bottom:16px; }
    @media (min-width:520px) { .ph-page .sum-grid { grid-template-columns:repeat(4,1fr); } }
    .ph-page .sum-card { border-radius:14px; padding:12px; border:1px solid transparent; min-width:0; }
    .ph-page .sum-card .ic {
        width:38px; height:38px; border-radius:10px; display:flex; align-items:center;
        justify-content:center; font-size:17px; margin-bottom:6px;
    }
    .ph-page .sum-card small { display:block; font-size:12px; color:#334155; }
    .ph-page .sum-card strong { display:block; font-size:16px; font-weight:800; white-space:nowrap; }
    .ph-page .sum-card em { font-style:normal; font-size:11px; color:var(--muted); }
    .ph-page .s-blue   { background:#eaf2ff; border-color:#d5e5ff; } .ph-page .s-blue .ic   { background:#d6e6ff; color:#2563eb; } .ph-page .s-blue strong   { color:var(--navy); }
    .ph-page .s-green  { background:#e8faf1; border-color:#ccf3e0; } .ph-page .s-green .ic  { background:#cdf3df; color:#16a34a; } .ph-page .s-green strong  { color:#047857; }
    .ph-page .s-yellow { background:#fff8e6; border-color:#ffecb8; } .ph-page .s-yellow .ic { background:#ffe9b3; color:#f59e0b; } .ph-page .s-yellow strong { color:#b45309; }
    .ph-page .s-purple { background:#f1ebff; border-color:#e2d6ff; } .ph-page .s-purple .ic { background:#e4d8ff; color:#7c3aed; } .ph-page .s-purple strong { color:#5b21b6; }

    /* Panel */
    .ph-page .panel {
        background:#fff; border:1px solid var(--line); border-radius:16px; padding:12px;
        box-shadow:0 6px 18px rgba(15,31,107,.05);
    }
    .ph-page .panel-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; gap:8px; }
    .ph-page .panel-head h6 { margin:0; font-weight:800; color:var(--navy); font-size:18px; display:flex; align-items:center; gap:10px; }
    .ph-page .panel-head .pi {
        width:34px; height:34px; border-radius:9px; background:var(--blue); color:#fff;
        display:flex; align-items:center; justify-content:center; font-size:15px;
    }

    /* Payment item */
    .ph-page .pay-item {
        display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit;
        border:1px solid var(--line); border-radius:14px; padding:10px 12px; margin-bottom:10px;
        background:#fff; transition:.2s;
    }
    .ph-page .pay-item:hover { box-shadow:0 8px 20px rgba(15,31,107,.1); transform:translateY(-1px); color:inherit; }
    .ph-page .mon-box {
        width:62px; min-height:62px; border-radius:12px; flex-shrink:0; text-align:center;
        display:flex; flex-direction:column; justify-content:center; font-weight:800;
        color:var(--navy); line-height:1.25; font-size:14px;
    }
    .ph-page .mon-blue  { background:#e3eeff; }
    .ph-page .mon-green { background:#dcf7e8; }
    .ph-page .pay-mid { flex:1; min-width:0; }
    .ph-page .pay-mid h6 { margin:0 0 4px; font-weight:800; color:var(--navy); font-size:15px; }
    .ph-page .kv { display:flex; font-size:12.5px; color:var(--muted); line-height:1.6; }
    .ph-page .kv span:first-child { width:88px; flex-shrink:0; }
    .ph-page .kv span:nth-child(2) { width:12px; }
    .ph-page .kv b { color:#1e293b; font-weight:600; }
    .ph-page .kv b.red   { color:#dc2626; font-weight:800; }
    .ph-page .kv b.green { color:#16a34a; font-weight:800; }

    .ph-page .pay-right { flex-shrink:0; font-size:12px; color:var(--muted); min-width:110px; }
    .ph-page .badge-st {
        display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px;
        font-weight:800; font-size:13px; margin-bottom:6px;
    }
    .ph-page .st-paid    { background:#d9f7e5; color:#15803d; }
    .ph-page .st-pending { background:#ffefc2; color:#b45309; }
    .ph-page .kv2 { display:flex; gap:10px; line-height:1.5; }
    .ph-page .kv2 .k { width:56px; }
    .ph-page .kv2 .v { color:#1e293b; font-weight:600; }
    .ph-page .chev { color:var(--navy); flex-shrink:0; }

    @media (max-width:460px) {
        .ph-page .pay-item { flex-wrap:wrap; }
        .ph-page .pay-right { order:3; width:100%; padding-left:74px; }
        .ph-page .chev { margin-left:auto; }
    }
</style>

<div class="container py-4">
    <div class="app-container position-relative ph-page">

        <!-- Header -->
        @include('hrm.dashboard.header')

        <!-- Profile + Year -->
        <div class="top-card marginbody">
            <div class="who">
               
                <div>
                    <h5>{{ $staff->name ?? auth()->user()->name }}</h5>
                    <small>{{ $staff->employee_id ?? $staff->emp_id ?? '' }}</small>
                    <small class="dept">{{ $staff->department_id }}</small>
                </div>
            </div>

            <form method="GET" class="pill-select">
                <i class="far fa-calendar-alt"></i>
                <select name="year" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $y === $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Summary -->
        <div class="sum-grid marginbody">
            <div class="sum-card s-blue">
                <div class="ic"><i class="fas fa-sack-dollar"></i></div>
                <small>Total Salary</small>
                <strong>{{ $cur }} {{ $fmt($totalSalary) }}</strong>
                <em>(Till {{ $tillLabel }})</em>
            </div>
            <div class="sum-card s-green">
                <div class="ic"><i class="fas fa-wallet"></i></div>
                <small>Total Paid</small>
                <strong>{{ $cur }} {{ $fmt($totalPaid) }}</strong>
            </div>
            <div class="sum-card s-yellow">
                <div class="ic"><i class="far fa-clock"></i></div>
                <small>Total Pending</small>
                <strong>{{ $cur }} {{ $fmt($totalPending) }}</strong>
            </div>
            <div class="sum-card s-purple">
                <div class="ic"><i class="fas fa-file-lines"></i></div>
                <small>Total Months</small>
                <strong>{{ $totalMonths }} Months</strong>
            </div>
        </div>

        <!-- Payment History -->
        <div class="panel marginbody">
            <div class="panel-head">
                <h6><span class="pi"><i class="fas fa-list-ul"></i></span> Payment History</h6>

                <div class="pill-select">
                    <select id="monthFilter">
                        <option value="all">All Months</option>
                        @foreach($rows->sortBy('date') as $r)
                            <option value="{{ $r['date']->format('Y-m') }}">{{ $r['date']->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @forelse($rows as $i => $r)
                @php
                    $paid = (float) ($r['paid_amount'] ?? 0) > 0 && $r['balance'] <= 0;
                @endphp
                <a class="pay-item" data-month="{{ $r['date']->format('Y-m') }}"
                   href="{{ url('admin/my-payment-report') }}?month={{ $r['date']->format('Y-m') }}">

                    <div class="mon-box {{ $i % 2 === 0 ? 'mon-blue' : 'mon-green' }}">
                        <span>{{ strtoupper($r['date']->format('M')) }}</span>
                        <span>{{ $r['date']->format('Y') }}</span>
                    </div>

                    <div class="pay-mid">
                        <h6>{{ $r['date']->format('F Y') }}</h6>
                        <div class="kv"><span>Net Payable</span><span>:</span><b>{{ $cur }} {{ $fmt($r['net_payable']) }}</b></div>
                        <div class="kv"><span>Paid Amount</span><span>:</span><b>{{ $cur }} {{ $fmt($r['paid_amount'] ?? 0) }}</b></div>
                        <div class="kv"><span>Balance</span><span>:</span>
                            <b class="{{ $r['balance'] > 0 ? 'red' : 'green' }}">{{ $cur }} {{ $fmt($r['balance']) }}</b>
                        </div>
                    </div>

                    <div class="pay-right">
                        @if($paid)
                            <span class="badge-st st-paid"><i class="fas fa-circle-check"></i> Paid</span>
                        @else
                            <span class="badge-st st-pending"><i class="far fa-clock"></i> Pending</span>
                        @endif
                        <div class="kv2">
                            <span class="k">Payment Date</span>
                            <span class="v">{{ !empty($r['payment_date']) ? \Carbon\Carbon::parse($r['payment_date'])->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="kv2">
                            <span class="k">Method</span>
                            <span class="v">{{ $r['method'] ?? '-' }}</span>
                        </div>
                    </div>

                    <i class="fas fa-chevron-right chev"></i>
                </a>
            @empty
                <p class="text-center text-muted py-4 mb-0">No payment records found</p>
            @endforelse
        </div>

        <br><br><br><br>
        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>

<script>
document.getElementById('monthFilter')?.addEventListener('change', function () {
    const v = this.value;
    document.querySelectorAll('.pay-item').forEach(function (el) {
        el.style.display = (v === 'all' || el.dataset.month === v) ? '' : 'none';
    });
});
</script>
@endif
@endsection