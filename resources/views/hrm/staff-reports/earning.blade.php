@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
    $staff = \App\Models\Staff::where('user_id', auth()->id())->first();
    $cur   = $staff->currency_code ?? 'BDT';
    $fmt   = fn($n) => number_format((float) $n, 2, '.', ',');

    $hours      = (float) ($paymentdata['worked_hours'] ?? 0);
    $rate       = (float) ($staff->basic_salary ?? 0);
    $gross      = (float) ($paymentdata['earnings'] ?? 0);
    $transport  = (float) ($paymentdata['expense'] ?? 0);
    $advance    = (float) ($paymentdata['totalpayments'] ?? 0);
    $other      = (float) ($staff->others ?? 0);
    $totalAmt   = $gross + $transport;
    $net        = (float) ($paymentdata['net_payable'] ?? ($totalAmt - $advance - $other));

    // Selected month (Y-m). Pass your own controller value if you have one.
    $month = request('month', now()->format('Y-m'));

    // Daily rows: [ ['date' => '2026-09-01', 'hours' => 8, 'rate' => 10, 'earning' => 80], ... ]
    $daily = $paymentdata['daily'] ?? [];
@endphp

<style>
    .earn-page { --blue:#2563eb; --navy:#0f1f6b; --muted:#64748b; --line:#e6ecf5; }
    .earn-page .top-card {
        display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;
        margin-bottom:14px;
    }
    .earn-page .who { display:flex; align-items:center; gap:12px; }
    .earn-page .avatar {
        width:58px; height:58px; border-radius:50%; object-fit:cover;
        background:#e2e8f0; border:2px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,.12);
    }
    .earn-page .who h5 { margin:0; font-weight:800; color:var(--navy); text-transform:uppercase; font-size:17px; }
    .earn-page .who small { display:block; color:var(--muted); font-size:13px; line-height:1.3; }
    .earn-page .who .dept::before {
        content:""; display:inline-block; width:8px; height:8px; border-radius:50%;
        background:#22c55e; margin-right:6px;
    }
    .earn-page .month-box {
        display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--line);
        border-radius:12px; padding:6px 12px; color:var(--navy); font-weight:600;
    }
    .earn-page .month-box i { color:var(--navy); }
    .earn-page .month-box input {
        border:0; outline:0; background:transparent; font-weight:600; color:var(--navy); font-size:14px;
    }

    /* Summary cards */
    .earn-page .sum-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }
    .earn-page .sum-card {
        border-radius:14px; padding:12px 10px; display:flex; align-items:center; gap:8px;
        border:1px solid transparent; min-width:0;
    }
    .earn-page .sum-card .ic {
        width:38px; height:38px; border-radius:10px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:16px; color:#fff;
    }
    .earn-page .sum-card small { display:block; font-size:11px; color:#334155; line-height:1.2; }
    .earn-page .sum-card strong { display:block; font-size:15px; font-weight:800; color:var(--navy); white-space:nowrap; }
    .earn-page .sum-blue   { background:#eaf2ff; border-color:#d5e5ff; }
    .earn-page .sum-blue .ic   { background:#2563eb; border-radius:50%; }
    .earn-page .sum-green  { background:#e8faf1; border-color:#ccf3e0; }
    .earn-page .sum-green .ic  { background:#22c55e; }
    .earn-page .sum-green strong { color:#047857; }
    .earn-page .sum-purple { background:#f1ebff; border-color:#e2d6ff; }
    .earn-page .sum-purple .ic { background:#7c3aed; }
    .earn-page .sum-purple strong { color:#5b21b6; }

    /* Panels */
    .earn-page .panel {
        background:#fff; border:1px solid var(--line); border-radius:16px; padding:14px;
        margin-bottom:16px; box-shadow:0 6px 18px rgba(15,31,107,.05);
    }
    .earn-page .panel-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .earn-page .panel-head h6 { margin:0; font-weight:800; color:var(--navy); font-size:17px; display:flex; align-items:center; gap:10px; }
    .earn-page .panel-head h6 .pi {
        width:34px; height:34px; border-radius:9px; background:var(--blue); color:#fff;
        display:flex; align-items:center; justify-content:center; font-size:15px;
    }
    .earn-page .view-link { color:var(--blue); font-weight:600; font-size:13px; text-decoration:none; }

    /* Calculation rows */
    .earn-page .calc-row {
        display:flex; align-items:center; justify-content:space-between; gap:8px;
        padding:10px 8px; border-bottom:1px solid var(--line); font-size:14px; color:#1e293b;
    }
    .earn-page .calc-row .lbl { display:flex; align-items:center; gap:10px; }
    .earn-page .calc-row .sym {
        width:26px; height:26px; border-radius:7px; color:#fff; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:12px;
    }
    .earn-page .sym-plus  { background:#047857; }
    .earn-page .sym-eq    { background:#e0e9fb; color:var(--navy); }
    .earn-page .sym-minus { background:#ef4444; }
    .earn-page .sym-wallet{ background:#16a34a; width:32px; height:32px; }
    .earn-page .calc-row b { font-weight:800; color:var(--navy); white-space:nowrap; }
    .earn-page .calc-row.total-amt { background:#eaf2ff; border-radius:10px; border-bottom:0; }
    .earn-page .calc-row.deduct { color:#334155; }
    .earn-page .calc-row.deduct b { color:#ef2b2b; }
    .earn-page .calc-row.net {
        background:#e8faf1; border-radius:12px; border-bottom:0; margin-top:6px; padding:12px 8px;
    }
    .earn-page .calc-row.net .lbl { font-weight:800; color:#065f46; font-size:16px; }
    .earn-page .calc-row.net b { color:#065f46; font-size:20px; }

    /* Details table */
    .earn-page .det-wrap { overflow-x:auto; border:1px solid var(--line); border-radius:10px; }
    .earn-page table.det { width:100%; border-collapse:collapse; font-size:13px; min-width:420px; }
    .earn-page table.det th {
        background:#eaf2ff; color:var(--navy); font-weight:700; padding:10px 8px;
        border-right:1px solid #fff; text-align:center;
    }
    .earn-page table.det th:first-child, .earn-page table.det td:first-child { text-align:left; padding-left:12px; }
    .earn-page table.det td { padding:10px 8px; text-align:center; border-top:1px solid var(--line); color:#1e293b; }
    .earn-page table.det tfoot td {
        background:#eaf2ff; font-weight:800; color:var(--navy); font-size:15px; border-top:0;
    }

    @media (max-width:420px) {
        .earn-page .sum-card { flex-direction:column; text-align:center; }
        .earn-page .sum-card strong { font-size:13px; }
    }
    
</style>
<div class="container py-4">
    <div class="app-container position-relative earn-page">

        <!-- Header -->
        @include('hrm.dashboard.header')

       

        <!-- Summary cards -->
        <div class="sum-grid marginbody">
            <div class="sum-card sum-blue">
                <span class="ic"><i class="far fa-clock"></i></span>
                <div>
                    <small>Total Working Hours</small>
                    <strong>{{ rtrim(rtrim(number_format($hours, 1), '0'), '.') }} Hours</strong>
                </div>
            </div>

            <div class="sum-card sum-green">
                <span class="ic"><i class="fas fa-coins"></i></span>
                <div>
                    <small>Hourly Rate</small>
                    <strong>{{ $cur }} {{ $fmt($rate) }}</strong>
                </div>
            </div>

            <div class="sum-card sum-purple">
                <span class="ic"><i class="fas fa-layer-group"></i></span>
                <div>
                    <small>Total Earning<br>(Hours × Rate)</small>
                    <strong>{{ $cur }} {{ $fmt($gross) }}</strong>
                </div>
            </div>
        </div>

        <!-- Earnings Calculation -->
        <div class="panel marginbody">
            <div class="panel-head">
                <h6><span class="pi"><i class="fas fa-calculator"></i></span> Earnings Calculation</h6>
            </div>

            <div class="calc-row">
                <span class="lbl">
                    <span><strong>Total Earning</strong>
                        ({{ rtrim(rtrim(number_format($hours, 1), '0'), '.') }} Hours × {{ $cur }} {{ $fmt($rate) }})</span>
                </span>
                <b>{{ $cur }} {{ $fmt($gross) }}</b>
            </div>

            <div class="calc-row">
                <span class="lbl"><span class="sym sym-plus"><i class="fas fa-plus"></i></span> Transport Allowance</span>
                <b>{{ $cur }} {{ $fmt($transport) }}</b>
            </div>
            <div class="calc-row">
                <span class="lbl"><span class="sym sym-plus"><i class="fas fa-plus"></i></span> Advance Recovery</span>
                <b>{{ $cur }} {{ $paymentdata['loans']->sum('total_installments') }}</b>
            </div>

            <div class="calc-row total-amt">
                <span class="lbl"><span class="sym sym-eq"><i class="fas fa-equals"></i></span> <strong>Total Amount</strong></span>
                <b>{{ $cur }} {{ $fmt($totalAmt+$paymentdata['loans']->sum('total_installments')) }}</b>
            </div>

            <div class="calc-row deduct">
                <span class="lbl"><span class="sym sym-minus"><i class="fas fa-minus"></i></span> Advance Loan</span>
                <b>{{ $cur }} {{ $paymentdata['advance'] }}</b>
            </div>
            <div class="calc-row deduct">
                <span class="lbl"><span class="sym sym-minus"><i class="fas fa-minus"></i></span> Payments </span>
                <b>{{ $cur }} {{ $paymentdata['payments'] }}</b>
            </div>
            
            
            <div class="calc-row deduct">
                <span class="lbl"><span class="sym sym-minus"><i class="fas fa-minus"></i></span> Other Deduction</span>
                <b>{{ $cur }} {{ $fmt($other) }}</b>
            </div>

            <div class="calc-row net">
                <span class="lbl"><span class="sym sym-wallet"><i class="fas fa-wallet"></i></span> Net Payable</span>
                <b>{{ $cur }} {{ $paymentdata['net_payable'] }}</b>
            </div>
        </div>

        <!-- Earnings Details -->
        <div class="panel marginbody">
            <div class="panel-head">
                <h6><span class="pi"><i class="fas fa-list-ul"></i></span> Earnings Details</h6>
                <a href="{{ url('admin/payment-report') }}" class="view-link">
                    <i class="far fa-calendar-alt"></i> View Monthly
                </a>
            </div>

            <div class="det-wrap">
                <table class="det">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Working Hours</th>
                            <th>Rate ({{ $cur }})</th>
                            <th>Earning ({{ $cur }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daily as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y (D)') }}</td>
                                <td>{{ number_format((float) $row['hours'], 1) }}</td>
                                <td>{{ $fmt($row['rate'] ?? $rate) }}</td>
                                <td>{{ $fmt($row['earning']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No records found</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td>{{ number_format($hours, 1) }}</td>
                            <td></td>
                            <td>{{ $fmt($gross) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <br><br><br><br>
        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection