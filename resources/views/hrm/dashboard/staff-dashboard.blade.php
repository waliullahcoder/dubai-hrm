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
                    
                    @endif

                    <!-- Check In -->
                    @if($staff->location_varified == date('Y-m-d'))
                    <input type="hidden" name="employee_id[]" value="{{ $staff->id }}">
                    <input type="hidden" name="attendance_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="attendance_status" value="Present">
                    <input type="hidden" name="check_in_latitude" id="check_in_latitude" value="23.41">
                    <input type="hidden" name="check_in_longitude" id="check_in_longitude" value="91.42">

                    <div class="status-box">
                        <div class="status-info">
                            <h6 style="text-align:center">
                                <div class="status-icon">
                                    <i class="far fa-circle" style="color:white"></i>
                                </div> Not yet Checked In
                            </h6>
                            <p style="text-align:center"><strong> Location Varified Successfully!</strong></p>
                        </div>
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

                                <div class="col-md-12">
                                    <label class="form-label">AM / PM</label>
                                    <select name="check_in_ampm" id="check_in_ampm" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
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
                                </div><strong> Checked In Successfully!</strong>
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
                      {{-- Check Out --}}
                    <div class="col-md-12">
    <label class="form-label">
        <i class="fas fa-sign-out-alt"></i> Check Out
    </label>

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

    <div class="row g-2 mt-2">
        <div class="col-6">
            <select name="check_out_ampm"
                    id="check_out_ampm"
                    class="form-control">

                <option value="AM">AM</option>
                <option value="PM">PM</option>

            </select>
        </div>
    </div>

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
                            <h6>
                                <div class="status-icon">
                                    <i class="fas fa-check"></i>
                                </div><strong> Checked Out Successfully!</strong>
                            </h6>
                            <p class="subtitle">
                                Today Working Summary
                            </p>
                            <!-- Check In Time -->
                            @if($todayAttendancecheck && $todayAttendancecheck?->check_in)
                            <i class="far fa-clock" style="font-size:2em"></i>
                            <div class="checkin-time">
                                <table class="attendance-table">
                                    <tr>
                                        <td>Check In</td>
                                        <td><strong>{{ $todayAttendancecheck?->check_in ?? '--:--' }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Check Out</td>
                                        <td><strong>{{ $todayAttendancecheck?->check_out ?? '--:--' }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td>Worked Hours</td>
                                        <td><strong>{{ $todayAttendancecheck?->worked_hours ?? '0' }}</strong></td>
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
</script>
<script>
// user's device GPS coordinate auto display
function getCurrentLocation() {

    if (!navigator.geolocation) {

        alert('GPS is not supported by your browser.');
        return;

    }

    navigator.geolocation.getCurrentPosition(

        function(position) {

            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;

            document.getElementById('check_in_latitude').value =
                latitude.toFixed(7);

            document.getElementById('check_in_longitude').value =
                longitude.toFixed(7);

            // Checkout-এর জন্যও current location রাখা
            document.getElementById('check_out_latitude').value =
                latitude.toFixed(7);

            document.getElementById('check_out_longitude').value =
                longitude.toFixed(7);

        },

        function(error) {

            if (error.code === error.PERMISSION_DENIED) {

              //  alert('Please allow GPS/Location permission.');

            } else if (error.code === error.POSITION_UNAVAILABLE) {

                alert('Location information is unavailable.');

            } else if (error.code === error.TIMEOUT) {

                alert('Location request timed out.');

            } else {

                alert('Unable to get your location.');

            }

        },

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }

    );

}


// Page load হলে GPS নেওয়া হবে
document.addEventListener('DOMContentLoaded', function() {

    getCurrentLocation();

});
</script>
<script>
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


    function updateCheckIn() {

        let hour = $('#check_in_hour').val();
        let ampm = $('#check_in_ampm').val();

        $('#check_in').val(
            convertTo24Hour(hour, ampm)
        );
    }


    function updateCheckOut() {

        let hour = $('#check_out_hour').val();
        let ampm = $('#check_out_ampm').val();

        $('#check_out').val(
            convertTo24Hour(hour, ampm)
        );
    }


    $('#check_in_hour, #check_in_ampm').on('change', function () {
        updateCheckIn();
    });


    $('#check_out_hour, #check_out_ampm').on('change', function () {
        updateCheckOut();
    });


    // Initial value
    updateCheckIn();
    updateCheckOut();
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const clock = document.getElementById('checkInClock');
    const hourHand = document.getElementById('checkInHourHand');
    const hourInput = document.getElementById('check_in_hour');
    const selectedHour = document.getElementById('checkInSelectedHour');

    const hours = document.querySelectorAll('#checkInClock .hour-number');

    function setHour(hour) {

        hour = parseInt(hour);

        // 12 = 0 degree
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
    }

    hours.forEach(item => {

        // Mouse click
        item.addEventListener('click', function () {
            setHour(this.dataset.hour);
        });

        // Mobile touch
        item.addEventListener('touchstart', function (e) {
            e.preventDefault();
            setHour(this.dataset.hour);
        });
    });

    // Default 12
    setHour(12);

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const hours = document.querySelectorAll(
        '#checkOutClock .hour-number'
    );

    const hourHand = document.getElementById(
        'checkOutHourHand'
    );

    const hourInput = document.getElementById(
        'check_out_hour'
    );

    const selectedHour = document.getElementById(
        'checkOutSelectedHour'
    );

    const ampm = document.getElementById(
        'check_out_ampm'
    );

    const checkOut = document.getElementById(
        'check_out'
    );


    function setHour(hour) {

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


    function updateCheckOut() {

        const hour = hourInput.value;
        const period = ampm.value;

        checkOut.value = hour + ':00 ' + period;
    }


    hours.forEach(item => {

        item.addEventListener('click', function () {

            setHour(this.dataset.hour);

        });

        item.addEventListener('touchstart', function (e) {

            e.preventDefault();

            setHour(this.dataset.hour);

        });

    });


    ampm.addEventListener('change', function () {

        updateCheckOut();

    });


    // Default
    setHour(12);

});
</script>
@endpush