@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
$staff = \App\Models\Staff::where('user_id', auth()->id())->first();

@endphp
<style>
    .text-right{
        text-align:right;
    }
   .trhead{
    background: #85f3d2 !important;
   }
</style>
<div class="container py-4">
    <div class="app-container position-relative">

        <!-- Header -->
        @include('hrm.dashboard.header')

<div class="advance-section">

    <!-- Section Header -->
    <div class="advance-title">
        <h3>
            <i class="fas fa-arrow-right"></i>
            Earnings
        </h3>
    </div>


    <div class="advance-title">
       <table class="dataTable table" style="width:100%">
        <tr><td>Total Working Hours</td><th class="text-right">Hours {{$paymentdata['worked_hours']}}</th></tr>
        <tr><td>Hourly Rate</td><th class="text-right">{{$staff->currency_code??'BDT'}} {{ number_format($staff->basic_salary, 2, '.', ',') }}</th></tr>
        <tr class="trhead"><td><h6>Gross Earning</h6></td><th class="text-right"><h5> {{$staff->currency_code??'BDT'}} {{ number_format($paymentdata['earnings'], 2, '.', ',') }}</h5></th></tr>
        
        <tr><td>Transport (Approved)</td><th class="text-right">{{$staff->currency_code??'BDT'}} {{ number_format($paymentdata['expense'], 2, '.', ',') }}</th></tr>
        <tr style="color:#ff6f6f;"><td>Advance Recovery</td><th class="text-right">{{$staff->currency_code??'BDT'}} {{ number_format($paymentdata['totalpayments'], 2, '.', ',') }}</th></tr>
        <tr style="color:#ff6f6f;"><td>Others Deduction</td><th class="text-right">{{$staff->currency_code??'BDT'}} {{ number_format($staff->others, 2, '.', ',') }}</th></tr>
        <tr class="trhead"><td><h6>Net Payable</h6></td><th class="text-right"><h4> {{$staff->currency_code??'BDT'}} {{ number_format($paymentdata['net_payable'], 2, '.', ',') }}</h3></th></tr>

       </table>
    </div>


   <div class="row g-3">

    



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



</div>

</div>


        <br><br><br><br>
        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection