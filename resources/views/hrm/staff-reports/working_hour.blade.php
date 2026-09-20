@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
$staff = \App\Models\Staff::where('user_id', auth()->id())->first();
$todayAttendance = null;

if ($staff) {
$todayAttendancecheckin = DB::table('hrm_employee_attendances')
->where('employee_id', $staff->id)
->whereNotNull('check_in')
->whereDate('attendance_date', today())
->first();
$todayAttendancecheckout = DB::table('hrm_employee_attendances')
->where('employee_id', $staff->id)
->whereNotNull('check_out')
->whereDate('attendance_date', today())
->first();
}
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
            Working Hours
        </h3>
    </div>

      @if($todayAttendancecheckin != null && $todayAttendancecheckout != null)
                    <div class="status-box">
                        <!-- Big Status Icon -->
                        <div class="status-info" style="text-align:center">
                           
                            <p class="subtitle">
                                Today Working Summary
                            </p>
                            <!-- Check In Time -->
                            @if($todayAttendancecheckin && $todayAttendancecheckin->check_in)
                            <i class="far fa-clock" style="font-size:2em"></i>
                            <div class="checkin-time">
                                <table class="attendance-table">
                                    <tr>
                                        <td>Check In</td>
                                        <td><strong>{{ $todayAttendancecheckin->check_in ?? '--:--' }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Check Out</td>
                                        <td><strong>{{ $todayAttendancecheckin->check_out ?? '--:--' }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Worked Hours</td>
                                        <td><strong>{{ $todayAttendancecheckin->worked_hours ?? '0' }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Status</td>
                                        <td><strong>Working</strong></td>
                                    </tr>
                                </table>
                            </div>

                            @endif
                        </div>
                    </div>
                    @endif
    <div class="advance-title">
       <table class="dataTable table" style="width:100%">
        <tr><td>Total Working Hours</td><th class="text-right"> Hours {{number_format($paymentdata['worked_hours'], 2, '.', ',')}}</th></tr>
        <tr><td>Hourly Rate</td><th class="text-right">{{$staff->currency_code??'BDT'}} {{$staff->basic_salary}}</th></tr>
        <tr class="trhead"><td><h6>Gross Earning</h6></td><th class="text-right"><h5> {{$staff->currency_code??'BDT'}} {{number_format($paymentdata['earnings'], 2, '.', ',')}}</h5></th></tr>
        
       </table>
    </div>


   <div class="row g-3">

    



    <!-- Advance History -->
    <div class="col-xl-12 col-md-12">

        <a href="{{url('admin/my-payment-report')}}" class="advance-card advance-history">

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