@extends('layouts.admin.app')

@section('content')

@include('hrm.dashboard.app_style')

@php
$staff = \App\Models\Staff::where('user_id', auth()->id())->first();

$todayAttendance = null;

if ($staff) {
$todayAttendancecheck = DB::table('hrm_employee_attendances')
->where('employee_id', $staff->id)
->whereDate('attendance_date', today())
->orderBy('id','desc')
->first();

}
@endphp
<div class="container py-4">
    <div class="app-container position-relative">

        <!-- Header -->
        @include('hrm.dashboard.header')

        <!-- Main Body -->
        <div class="app-body">

            <!-- Attendance Section -->
            <div class="checkin-card">
                <form action="{{ route('admin.employee-attendance.store') }}" method="POST">
                    @csrf

                    @if(($staff->location_varified == NULL || $staff->location_varified != date('Y-m-d')))
<button type="button" id="showDivBtn" class="btn btn-primary">
    Set Location
</button>

<div id="myDiv" style="display: none;">
                    <!-- Steps -->
                    <div class="steps">
                        <div class="step">
                            <div class="circle-num">1</div>
                            Location
                        </div>
                        <div class="step">
                            <div class="circle-num">2</div>
                            Department
                        </div>
                        <div class="step">
                            <div class="circle-num">3</div>
                            Checkin
                        </div>
                        <div class="step">
                            <div class="circle-num">4</div>
                            Checkout
                        </div>
                    </div>
                     <input type="hidden" name="employee_id" value="{{ $staff->id }}">
                    <input type="hidden" name="location_varified" value="{{ date('Y-m-d') }}">
                     <div class="col-lg-12 col-sm-12">
                        <label for="short_name" class="form-label"><b><i class="fad fa-hotel"></i> Hotel Name <span class="text-danger">*</span></b></label>
                        <select class="select form-select" id="hotel_id" name="hotel_id" required>
                            @foreach($hotels as $hotel)
                            <option value="{{$hotel->id}}" data-address="{{ $hotel->address }}">{{$hotel->name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="col-lg-12 col-sm-12">
                        <label for="type" class="form-label"><b><i class="fad fa-house"></i> Department <span class="text-danger">*</span></b></label>
                        <div class="custom-select">
                    <select name="department_id" id="department_id" class="select form-select" data-placeholder="Select Department" required>
                            
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') && old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>            
                    </div>
                    </div>
                    <br>
                     <div class="mt-3">
                            <label for="type" class="form-label">
                                <b>
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    Current Location
                                </b>
                            </label>

                            <span id="hotel_address" class="text-muted">
                               Select Hotel
                            </span>
                        </div> <br> <br>
                    <button class="btn btn-checkin">
                        <i class="fas fa-map-marker-alt"></i> Confirm Location
                    </button>
                    
                    
</div>
                    <!-- Check In -->
                    @elseif (
                            $staff->location_varified == date('Y-m-d')
                            &&
                            (
                                !$todayAttendancecheck
                                ||
                                (
                                    $todayAttendancecheck
                                    && $todayAttendancecheck->check_in
                                    && $todayAttendancecheck->check_out
                                )
                            )
                        )
                    <input type="hidden" name="employee_id[]" value="{{ $staff->id }}">
                    <input type="hidden" name="attendance_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="attendance_status" value="Present">
                    <input type="hidden" name="check_in_latitude" id="check_in_latitude" value="23.41">
                    <input type="hidden" name="check_in_longitude" id="check_in_longitude" value="91.42">


                 

                                            
                    <!-- Steps -->
                    <div class="steps">
                        <div class="step done">
                            <div class="circle-num"><i class="fas fa-check"></i></div>
                            Location
                        </div>
                        <div class="step done">
                            <div class="circle-num"><i class="fas fa-check"></i></div>
                            Department
                        </div>
                        <div class="step active">
                            <div class="circle-num">3</div>
                            Checkin
                        </div>
                        <div class="step">
                            <div class="circle-num">4</div>
                            Checkout
                        </div>
                    </div>

                    <!-- Heading -->
                    <div class="ci-head">
                        <div class="ci-icon"><i class="fas fa-clipboard-check"></i></div>
                        <div>
                            <h6>Select Check In Time</h6>
                            <p>Choose your check in hour</p>
                        </div>
                    </div>

                    <!-- AM / PM -->
                    <div class="ampm-toggle" data-target="check_in_ampm" data-label="#checkInSelectedAmPm">
                        <button type="button" data-val="AM" class="active">AM</button>
                        <button type="button" data-val="PM">PM</button>
                    </div>
                    <div class="col-md-12 d-none">
                        <select name="check_in_ampm" id="check_in_ampm" class="form-control">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>

                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="clock-wrapper">
                                        <div class="clock" id="checkInClock">
                                            <!-- Hour Numbers -->
                                            @for($i = 1; $i <= 12; $i++)
                                                <div class="hour-number hour-{{ $i }}"
                                                    data-hour="{{ $i }}">
                                                    {{ $i }}
                                                </div>
                                            @endfor

                                            <!-- Center -->
                                            <div class="clock-center"></div>

                                            <!-- Hour Hand -->
                                            <div class="hour-hand" id="checkInHourHand"></div>

                                        </div>

                                        <div class="selected-time">
                                            <span id="checkInSelectedHour">12</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="check_in_hour" id="check_in_hour" value="12">
                                </div>

                                <!-- <div class="col-md-12">
                                    <label class="form-label">AM / PM</label>
                                    <select name="check_in_ampm" id="check_in_ampm" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div> -->
                            </div>


                            {{-- Actual value that will be submitted --}}
                            <input type="hidden" name="check_in" id="check_in"><br>
                            <button class="btn btn-checkin">
                            <i class="fas fa-sign-in-alt"></i> Confirm Check In
                            </button>           
                    @endif

                    <!-- Check Out -->
                    @if($todayAttendancecheck?->check_in != null && $todayAttendancecheck?->check_out == null)
                    <input type="hidden" name="employee_id[]" value="{{ $staff->id }}">
                    <input type="hidden" name="attendance_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="attendance_status" value="Present">
                    <input type="hidden" name="check_out_latitude" id="check_out_latitude" value="23.41">
                    <input type="hidden" name="check_out_longitude" id="check_out_longitude" value="91.42">

                    <div class="status-box">
                        <!-- Big Status Icon -->
                        <div class="status-info" style="text-align:center">
                            <h6>
                                <div class="status-icon">
                                    <i class="fas fa-check"></i>
                                </div><strong> You are already Checked In</strong>
                            </h6>
                            <p class="subtitle">
                                Start your work by checking in
                            </p>

                            <!-- Location Status -->
                            <div class="location-status">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>
                                    You checked in {{number_format($todayAttendancecheck?->check_in_distance, 2)}}
                                    Meters • Allowed 200 meters
                                </span>
                            </div>
                            

                            <!-- Check In Time -->
                            @if($todayAttendancecheck && $todayAttendancecheck?->check_in)
                            <i class="far fa-clock" style="font-size:2em"></i>

                            <div class="checkin-time">

                                <h3>
                                    Check In Time<br>
                                    <strong>
                                        {{ $todayAttendancecheck?->check_in 
                                            ? \Carbon\Carbon::parse($todayAttendancecheck?->check_in)->format('h:i A') 
                                            : '--:--' 
                                        }}
                                    </strong>
                                </h3>
                            </div>

                            @else

                            <div class="checkin-time">
                                <i class="far fa-clock"></i>

                                <span>
                                    Check In Time
                                    <strong>--:--</strong>
                                </span>
                            </div>

                            @endif

                        </div>

                    </div>
                    <!-- Steps -->
                    <div class="steps">
                        <div class="step done">
                            <div class="circle-num"><i class="fas fa-check"></i></div>
                            Location
                        </div>
                        <div class="step done">
                            <div class="circle-num"><i class="fas fa-check"></i></div>
                            Department
                        </div>
                        <div class="step done">
                            <div class="circle-num"><i class="fas fa-check"></i></div>
                            Checkin
                        </div>
                        <div class="step">
                            <div class="circle-num">4</div>
                            Checkout
                        </div>
                    </div>
                                        <!-- AM / PM -->
                    <div class="ampm-toggle" data-target="check_in_ampm" data-label="#checkInSelectedAmPm">
                        <button type="button" data-val="AM" class="active">AM</button>
                        <button type="button" data-val="PM">PM</button>
                    </div>
                    <div class="col-md-12 d-none">
                        <select name="check_in_ampm" id="check_in_ampm" class="form-control">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                                        {{-- Check Out --}}
                                        <div class="col-md-12">

                        <div class="clock-wrapper">
                            <div class="clock" id="checkOutClock">

                                @for($i = 1; $i <= 12; $i++)
                                    <div class="hour-number hour-{{ $i }}"
                                        data-hour="{{ $i }}">
                                        {{ $i }}
                                    </div>
                                @endfor

                                <div class="clock-center"></div>

                                <div class="hour-hand" id="checkOutHourHand"></div>

                            </div>

                            <div class="selected-time">
                                <span id="checkOutSelectedHour">12</span>
                            </div>
                        </div>

                        <!-- <div class="row g-2 mt-2">
                            <div class="col-12">
                                <select name="check_out_ampm"
                                        id="check_out_ampm"
                                        class="form-control">
                                    <option value="PM">PM</option>
                                    <option value="AM">AM</option>
                                    

                                </select>
                            </div>
                        </div> -->

                        <input type="hidden"
                            name="check_out_hour"
                            id="check_out_hour"
                            value="12">

                        <input type="hidden"
                            name="check_out"
                            id="check_out">
                    </div>
                                        
                    
                    <br>

                    <button class="btn btn-checkout">
                        <i class="fas fa-sign-out-alt"></i> Confirm Check Out
                    </button>
                    @endif



                    <!-- Checked In and Checked Out Both Done -->

                    @if($todayAttendancecheck?->check_in != null && $todayAttendancecheck?->check_out != null)
                    
                    <div class="status-box">
                        <!-- Big Status Icon -->
                        <div class="status-info" style="text-align:center">
                            <!-- Steps -->
                            <div class="steps">
                                <div class="step done">
                                    <div class="circle-num"><i class="fas fa-check"></i></div>
                                    Location
                                </div>
                                <div class="step done">
                                    <div class="circle-num"><i class="fas fa-check"></i></div>
                                    Department
                                </div>
                                <div class="step done">
                                    <div class="circle-num"><i class="fas fa-check"></i></div>
                                    Checkin
                                </div>
                                <div class="step done">
                                    <div class="circle-num"><i class="fas fa-check"></i></div>
                                    Checkout
                                </div>
                            </div>
                            <h6>
                                <div class="status-icon">
                                    <i class="fas fa-check"></i>
                                </div><strong> Checked Out Successfully!</strong>
                            </h6>
                            <p class="subtitle">
                               Hotel : {{$staff->hotel?->name}}<br>
                               Department : {{\App\Models\Category::find($staff->department_id)?->name}}
                            </p>
                            <!-- Check In Time -->
                            @if($todayAttendancecheck && $todayAttendancecheck?->check_in)
                            <i class="far fa-clock" style="font-size:2em"></i>
                            <div class="checkin-time">
                                <table class="attendance-table">
                                    <tr>
                                        <td>Check In</td>
                                        <td><strong>
                                            {{ $todayAttendancecheck?->check_in
    ? Carbon\Carbon::parse($todayAttendancecheck->check_in)->format('g A')
    : '' }}
                                        </strong></td>
                                    </tr>

                                    <tr>
                                        <td>Check Out</td>
                                        <td><strong>
                                            {{ $todayAttendancecheck?->check_out
    ? Carbon\Carbon::parse($todayAttendancecheck->check_out)->format('g A')
    : '' }}
                                           </strong></td>
                                    </tr>

                                    <tr>
                                        <td>Worked Hours</td>
                                        <td><strong>{{ number_format($todayAttendancecheck?->worked_hours) ?? '0' }}</strong></td>
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



                </form>
            </div>

            <!-- Features Grid -->
            @include('hrm.dashboard.grid-dashboard')

        </div>

        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {

    // ==========================================
    // Convert 12 Hour -> 24 Hour
    // ==========================================
    function convertTo24Hour(hour, ampm) {

        hour = parseInt(hour);

        if (ampm === 'AM') {

            if (hour === 12) {
                hour = 0;
            }

        } else {

            if (hour !== 12) {
                hour += 12;
            }

        }

        return String(hour).padStart(2, '0') + ':00:00';
    }


    // ==========================================
    // CHECK IN
    // ==========================================
    function updateCheckIn() {

        let hour = $('#check_in_hour').val();
        let ampm = $('#check_in_ampm').val();

        let time = convertTo24Hour(hour, ampm);

        $('#check_in').val(time);

        console.log('Check In:', time);
    }


    // ==========================================
    // CHECK OUT
    // ==========================================
    function updateCheckOut() {

        let hour = $('#check_out_hour').val();
        let ampm = $('#check_out_ampm').val();

        let time = convertTo24Hour(hour, ampm);

        $('#check_out').val(time);

        console.log('Check Out:', time);
    }


    // ==========================================
    // Check In Clock
    // ==========================================
    const checkInClock = document.getElementById('checkInClock');

    if (checkInClock) {

        const hourHand = document.getElementById('checkInHourHand');
        const hourInput = document.getElementById('check_in_hour');
        const selectedHour = document.getElementById('checkInSelectedHour');

        const hours = document.querySelectorAll(
            '#checkInClock .hour-number'
        );


        function setCheckInHour(hour) {

            hour = parseInt(hour);

            let degree = hour === 12 ? 0 : hour * 30;

            hourHand.style.transform =
                `translateX(-50%) rotate(${degree}deg)`;

            hourInput.value = hour;

            selectedHour.textContent = hour;


            hours.forEach(item => {

                item.classList.remove('active');

                if (parseInt(item.dataset.hour) === hour) {
                    item.classList.add('active');
                }

            });


            updateCheckIn();
        }


        hours.forEach(item => {

            item.addEventListener('click', function () {
                setCheckInHour(this.dataset.hour);
            });


            item.addEventListener('touchstart', function (e) {

                e.preventDefault();

                setCheckInHour(this.dataset.hour);

            });

        });


        setCheckInHour(
            $('#check_in_hour').val() || 12
        );


        $('#check_in_ampm').on('change', function () {
            updateCheckIn();
        });

    }


    // ==========================================
    // Check Out Clock
    // ==========================================
    const checkOutClock = document.getElementById('checkOutClock');

    if (checkOutClock) {

        const hourHand = document.getElementById('checkOutHourHand');
        const hourInput = document.getElementById('check_out_hour');
        const selectedHour = document.getElementById('checkOutSelectedHour');

        const hours = document.querySelectorAll(
            '#checkOutClock .hour-number'
        );


        function setCheckOutHour(hour) {

            hour = parseInt(hour);

            let degree = hour === 12 ? 0 : hour * 30;

            hourHand.style.transform =
                `translateX(-50%) rotate(${degree}deg)`;

            hourInput.value = hour;

            selectedHour.textContent = hour;


            hours.forEach(item => {

                item.classList.remove('active');

                if (parseInt(item.dataset.hour) === hour) {
                    item.classList.add('active');
                }

            });


            updateCheckOut();
        }


        hours.forEach(item => {

            item.addEventListener('click', function () {
                setCheckOutHour(this.dataset.hour);
            });


            item.addEventListener('touchstart', function (e) {

                e.preventDefault();

                setCheckOutHour(this.dataset.hour);

            });

        });


        setCheckOutHour(
            $('#check_out_hour').val() || 12
        );


        $('#check_out_ampm').on('change', function () {
            updateCheckOut();
        });

    }


    // Initial values
    updateCheckIn();
    updateCheckOut();

});
</script>
<script>
$(document).ready(function () {

    $('#hotel_id').on('change', function () {

        let address = $(this)
            .find(':selected')
            .data('address');

        if (address) {

            $('#hotel_address').html(
                '<i class="fas fa-map-marker-alt text-danger"></i> ' +
                address
            );

        } else {

            $('#hotel_address').html(
                'Select a hotel'
            );
        }
    });

});


$(document).on('click', '.ampm-toggle button', function () {
    const $wrap = $(this).closest('.ampm-toggle');
    const val = $(this).data('val');

    $wrap.find('button').removeClass('active');
    $(this).addClass('active');

    $('#' + $wrap.data('target')).val(val).trigger('change'); // apnar JS ei change dhore
    $($wrap.data('label')).text(val);                         // "Selected Time" box e AM/PM
});
</script>

<script>
    $(document).ready(function () {

        $('#showDivBtn').click(function () {
            $('#myDiv').toggle();

            // Button text change
            if ($('#myDiv').is(':visible')) {
                $(this).text('Hide Location');
            } else {
                $(this).text('Show Location');
            }
        });

    });
</script>
@endpush