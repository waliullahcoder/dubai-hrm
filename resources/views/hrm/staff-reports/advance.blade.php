@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
$staff = \App\Models\Staff::where('user_id', auth()->id())->first();
@endphp
<div class="container py-4">
    <div class="app-container position-relative">

        <!-- Header -->
        @include('hrm.dashboard.header')

<div class="advance-section">

    <!-- Section Header -->
    <div class="advance-title">

        <h3>
            <i class="fas fa-arrow-right"></i>
            My Advance 
        </h3>

    </div>


   <div class="row g-3">

    <!-- Total Advance -->
    <div class="col-xl-12 col-md-12">

        <a href="#" class="advance-card advance-total">

            <span class="advance-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

            <div class="advance-icon">
                <i class="fas fa-wallet"></i>
            </div>

            <span class="menu-title">
                Total Advance
            </span>

            <h1>
                {{$staff->currency_code??'BDT'}} {{number_format($paymentdata['advance'], 2, '.', ',')}}
            </h1>

        </a>

    </div>


    <!-- Recovered Advance -->
    <div class="col-xl-12 col-md-12">

        <a href="#" class="advance-card advance-recovered">

            <span class="advance-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

            <div class="advance-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>

            <span class="menu-title">
                Recovered Advance
            </span>

            <h1>
                {{$staff->currency_code??'BDT'}} {{number_format($paymentdata['payments'], 2, '.', ',')}}
            </h1>

        </a>

    </div>


    <!-- Outstanding -->
    <div class="col-xl-12 col-md-12">

        <a href="#" class="advance-card advance-outstanding">

            <span class="advance-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

            <div class="advance-icon">
                <i class="fas fa-exclamation"></i>
            </div>

            <span class="menu-title">
                Outstanding
            </span>

            <h1>
               {{$staff->currency_code??'BDT'}} {{number_format($paymentdata['net_payable'], 2, '.', ',')}}
            </h1>

        </a>

    </div>


    <!-- Advance History -->
    <div class="col-xl-12 col-md-12">

        <a href="{{url('admin/payment-report')}}" class="advance-card advance-history">

            <span class="advance-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

            <div class="advance-icon">
                <i class="fas fa-history"></i>
            </div>

            <span class="menu-title">
                Advance History
            </span>

            <h1>
                View History
            </h1>

        </a>

    </div>


    <!-- Recovered History -->
    <div class="col-xl-12 col-md-12">

        <a href="{{url('admin/payment-report')}}" class="advance-card advance-history">

            <span class="advance-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

            <div class="advance-icon">
                <i class="fas fa-undo"></i>
            </div>

            <span class="menu-title">
                Recovered History
            </span>

            <h1>
                View History
            </h1>

        </a>

    </div>

</div>

</div>


        <br><br><br><br>
        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection