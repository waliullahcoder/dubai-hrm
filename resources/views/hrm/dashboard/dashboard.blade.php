@extends('layouts.admin.app')

@section('content')

<style>

    /* ==============================
       DASHBOARD
    ============================== */

    .dashboard-wrapper {
        padding: 20px 0;
    }

    .dashboard-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        padding: 22px;
        min-height: 145px;
        color: #fff;
        border: 0;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .08);
        transition: all .3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, .15);
    }

    .dashboard-card::before {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
        right: -40px;
        top: -40px;
    }

    .dashboard-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        right: 35px;
        bottom: -55px;
    }

    .card-content {
        position: relative;
        z-index: 2;
    }

    .card-title {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 8px;
        opacity: .9;
    }

    .card-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
    }

    .card-icon {
        position: absolute;
        right: 20px;
        bottom: 18px;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255,255,255,.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        z-index: 2;
    }

    /* Card Colors */

    .staff-card {
        background: linear-gradient(135deg, #2563eb, #60a5fa);
    }

    .hotel-card {
        background: linear-gradient(135deg, #7c3aed, #a78bfa);
    }

    .hours-card {
        background: linear-gradient(135deg, #0891b2, #22d3ee);
    }

    .earning-card {
        background: linear-gradient(135deg, #059669, #34d399);
    }

    .payment-card {
        background: linear-gradient(135deg, #16a34a, #4ade80);
    }

    .expense-card {
        background: linear-gradient(135deg, #ea580c, #fb923c);
    }

    .outstanding-card {
        background: linear-gradient(135deg, #dc2626, #f87171);
    }

    /* ==============================
       CHART CARD
    ============================== */

    .chart-card {
        background: #fff;
        border: 0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .06);
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .chart-subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .chart-wrapper {
        position: relative;
        height: 360px;
        margin-top: 20px;
    }

    /* ==============================
       RESPONSIVE
    ============================== */

    @media(max-width: 767px) {

        .dashboard-wrapper {
            padding: 10px 0;
        }

        .dashboard-card {
            min-height: 130px;
        }

        .card-value {
            font-size: 23px;
        }

        .chart-wrapper {
            height: 280px;
        }
    }

</style>


<div class="container-fluid dashboard-wrapper">

    {{-- ==========================================
         DASHBOARD SUMMARY
    =========================================== --}}

    <div class="row g-3">


        {{-- TOTAL STAFF --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card staff-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Staff
                    </div>

                    <div class="card-value">
                        {{ number_format($total_staff) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL HOTEL --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card hotel-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Hotel
                    </div>

                    <div class="card-value">
                        {{ number_format($total_hotel) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-hotel"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL HOURS --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card hours-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Hours
                    </div>

                    <div class="card-value">
                        {{ number_format($total_hours, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL EARNING --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card earning-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Earning
                    </div>

                    <div class="card-value">
                        ৳{{ number_format($total_earning, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>

            </div>

        </div>

        {{-- Advance PAYMENTS --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card payment-card">

                <div class="card-content">

                    <div class="card-title">
                        Advance Payments
                    </div>

                    <div class="card-value">
                        ৳{{ number_format($advance_payments, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL PAYMENTS --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card payment-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Payments
                    </div>

                    <div class="card-value">
                        ৳{{ number_format($total_payments, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL EXPENSE --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card expense-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Expense
                    </div>

                    <div class="card-value">
                        ৳{{ number_format($total_expense, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>

            </div>

        </div>


        {{-- TOTAL OUTSTANDING --}}
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">

            <div class="dashboard-card outstanding-card">

                <div class="card-content">

                    <div class="card-title">
                        Total Outstanding
                    </div>

                    <div class="card-value">
                        ৳{{ number_format($total_outstanding, 2) }}
                    </div>

                </div>

                <div class="card-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

            </div>

        </div>


    </div>


    {{-- ==========================================
         CHART
    =========================================== --}}

    <div class="row mt-4">

        <div class="col-12">

            <div class="chart-card">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div>

                        <div class="chart-title">
                            Payment & Expense Overview
                        </div>

                        <div class="chart-subtitle">
                            Monthly payment and expense summary
                            - {{ now()->year }}
                        </div>

                    </div>

                </div>


                <div class="chart-wrapper">

                    <canvas id="hrmDashboardChart"></canvas>

                </div>

            </div>

        </div>

    </div>


</div>


{{-- ==========================================
     CHART JS
=========================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const ctx = document
        .getElementById('hrmDashboardChart')
        .getContext('2d');


    const monthlyPayments = @json($monthly_payments);

    const monthlyExpense = @json($monthly_expense);


    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ],

            datasets: [

                {
                    label: 'Payments',

                    data: monthlyPayments,

                    borderWidth: 1,

                    borderRadius: 6,

                    backgroundColor: 'rgba(22, 163, 74, 0.75)',

                    borderColor: '#16a34a'
                },

                {
                    label: 'Expense',

                    data: monthlyExpense,

                    borderWidth: 1,

                    borderRadius: 6,

                    backgroundColor: 'rgba(234, 88, 12, 0.75)',

                    borderColor: '#ea580c'
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

                    display: true,

                    position: 'top',

                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }

                },

                tooltip: {

                    callbacks: {

                        label: function(context) {

                            let value = context.raw || 0;

                            return context.dataset.label +
                                ': ৳' +
                                Number(value).toLocaleString(
                                    'en-BD',
                                    {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }
                                );

                        }

                    }

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: function(value) {

                            return '৳' +
                                Number(value).toLocaleString('en-BD');

                        }

                    }

                },

                x: {

                    grid: {
                        display: false
                    }

                }

            }

        }

    });

});

</script>

@endsection