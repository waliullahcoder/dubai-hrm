@extends('layouts.admin.app')


@if(auth()->user()->role_status==4)
@section('content')

@php
    $staff = \App\Models\Staff::where('user_id', auth()->id())->first();

    $todayAttendance = null;

    if ($staff) {
        $todayAttendance = DB::table('hrm_employee_attendances')
            ->where('employee_id', $staff->id)
            ->whereDate('attendance_date', today())
            ->first();
    }
@endphp

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-check text-success"></i>
                    Employee Attendance
                </h5>
            </div>

            <div class="card-body">

                {{-- Staff not found --}}
                @if(!$staff)

                    <div class="alert alert-danger">
                        Employee information not found for this user.
                    </div>

                {{-- Attendance not completed --}}
                @elseif(!$todayAttendance || is_null($todayAttendance->check_out))

                    <form action="{{ route('admin.employee-attendance.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            {{-- Employee --}}
                            <div class="col-lg-4">
                                <label><b>Employee</b></label>

                                <select name="employee_id[]" id="employee_id" class="form-control" required>
                                    <option value="{{ $staff->id }}">
                                        {{ $staff->id }} - {{ $staff->name }}
                                    </option>
                                </select>
                            </div>

                            {{-- Attendance Date --}}
                            <div class="col-lg-4">
                                <label><b>Attendance Date</b></label>

                                <input type="date"
                                       name="attendance_date"
                                       class="form-control"
                                       value="{{ date('Y-m-d') }}"
                                       required
                                       readonly>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-4">
                                <label><b>Status</b></label>

                                <select name="attendance_status" class="form-control" required>
                                    <option value="Present">Present</option>
                                </select>
                            </div>

                            

                            {{-- Check In --}}
                            <div class="col-lg-3">
                                <label><b>Check In</b></label>

                                <input type="time"
                                       name="check_in"
                                       id="check_in"
                                       class="form-control"
                                       step="1"
                                       value="{{ $todayAttendance->check_in ?? date('H:i:s') }}"
                                       {{ $todayAttendance && $todayAttendance->check_in ? 'readonly' : '' }}>
                            </div>

                            {{-- Check Out --}}
                            <div class="col-lg-3">
                                <label><b>Check Out</b></label>

                                <input type="time"
                                       name="check_out"
                                       id="check_out"
                                       class="form-control"
                                       step="1"
                                       value="{{ $todayAttendance?->check_out ?? date('H:i:s') }}"
                                       {{ !$todayAttendance || !$todayAttendance->check_out ? 'readonly' : date('H:i:s') }}>
                            </div>

                            {{-- Check In Latitude --}}
                            <div class="col-lg-3">
                                <label><b>Check In Latitude</b></label>

                                <input type="text"
                                       name="check_in_latitude"
                                       id="check_in_latitude"
                                       class="form-control"
                                       value="{{ $todayAttendance->check_in_latitude ?? '' }}"
                                       placeholder="Getting location..."
                                       readonly>
                            </div>

                            {{-- Check In Longitude --}}
                            <div class="col-lg-3">
                                <label><b>Check In Longitude</b></label>

                                <input type="text"
                                       name="check_in_longitude"
                                       id="check_in_longitude"
                                       class="form-control"
                                       value="{{ $todayAttendance->check_in_longitude ?? '' }}"
                                       placeholder="Getting location..."
                                       readonly>
                            </div>

                            {{-- Check Out Latitude --}}
                            <div class="col-lg-3">
                                <label><b>Check Out Latitude</b></label>

                                <input type="text"
                                       name="check_out_latitude"
                                       id="check_out_latitude"
                                       class="form-control"
                                       value="{{ $todayAttendance->check_out_latitude ?? '' }}"
                                       placeholder="Getting location..."
                                       readonly>
                            </div>

                            {{-- Check Out Longitude --}}
                            <div class="col-lg-3">
                                <label><b>Check Out Longitude</b></label>

                                <input type="text"
                                       name="check_out_longitude"
                                       id="check_out_longitude"
                                       class="form-control"
                                       value="{{ $todayAttendance->check_out_longitude ?? '' }}"
                                       placeholder="Getting location..."
                                       readonly>
                            </div>

                            {{-- Remarks --}}
                            <div class="col-lg-12">
                                <label><b>Remarks</b></label>

                                <textarea name="remarks"
                                          class="form-control"
                                          rows="3">{{ $todayAttendance->remarks ?? '' }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-lg-12">

                                @if(!$todayAttendance)

                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Check In
                                    </button>

                                @elseif(is_null($todayAttendance->check_out))

                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Check Out
                                    </button>

                                @endif

                                <a href="{{ route('admin.employee-attendance.index') }}"
                                   class="btn btn-secondary">
                                    Back
                                </a>

                            </div>

                        </div>

                    </form>

                @else

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Already done attendance for today!!
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection

@push('js')

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

                alert('Please allow GPS/Location permission.');

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
document.addEventListener('DOMContentLoaded', function () {

    getCurrentLocation();

});

</script>

@endpush


@else

@section('content')
<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="fas fa-user-check text-success"></i>
                    Employee Attendance
                </h5>

            </div>

            <div class="card-body">

                <form action="{{ route('admin.employee-attendance.store') }}" method="POST">

                    @csrf

                    <div class="row g-3">

                        <div class="col-lg-4">
                            <label><b>Employee</b></label>
                             <select name="employee_id[]" id="employee_id" class="form-select select" data-placeholder="Select Employee" multiple>
                               @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->id }} - {{ $employee->name }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-lg-4">
                            <label><b>Attendance Date</b></label>

                            <input type="date" name="attendance_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>

                        </div>

                        <div class="col-lg-4">

                            <label><b>Status</b></label>

                            <select name="attendance_status" class="form-control">

                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                                <option value="Half Day">Half Day</option>
                                <option value="Leave">Leave</option>
                                <option value="Holiday">Holiday</option>
                                <option value="Weekend">Weekend</option>

                            </select>

                        </div>

                      
                         {{-- Check In --}}
                            <div class="col-lg-3">
                                <label><b>Check In</b></label>

                                <input type="time"
                                       name="check_in"
                                       id="check_in"
                                       class="form-control"
                                       step="1"
                                       value="{{ date('H:i:s') }}">
                            </div>

                           

                            {{-- Check In Latitude --}}
                            <div class="col-lg-3">
                                <label><b>Check In Latitude</b></label>

                                <input type="text"
                                       name="check_in_latitude"
                                       id="check_in_latitude"
                                       class="form-control"
                                       value="23.76453894488434"
                                       placeholder="Getting location...">
                            </div>

                            {{-- Check In Longitude --}}
                            <div class="col-lg-3">
                                <label><b>Check In Longitude</b></label>

                                <input type="text"
                                       name="check_in_longitude"
                                       id="check_in_longitude"
                                       class="form-control"
                                       value="90.42140253677894"
                                       placeholder="Getting location..."
                                       >
                            </div>

                             {{-- Check Out --}}
                            <div class="col-lg-3">
                                <label><b>Check Out</b></label>

                                <input type="time"
                                       name="check_out"
                                       id="check_out"
                                       class="form-control"
                                       step="1"
                                       value="{{ date('H:i:s') }}">
                            </div>

                            {{-- Check Out Latitude --}}
                            <div class="col-lg-3">
                                <label><b>Check Out Latitude</b></label>

                                <input type="text"
                                       name="check_out_latitude"
                                       id="check_out_latitude"
                                       class="form-control"
                                       value="23.76"
                                       placeholder="Getting location..."
                                       >
                            </div>

                            {{-- Check Out Longitude --}}
                            <div class="col-lg-3">
                                <label><b>Check Out Longitude</b></label>

                                <input type="text"
                                       name="check_out_longitude"
                                       id="check_out_longitude"
                                       class="form-control"
                                       value="90.41"
                                       placeholder="Getting location..."
                                       >
                            </div>

                       

                        <div class="col-lg-2">

                            <label><b>Late (Min)</b></label>

                            <input type="number" name="late_minutes" id="late_minutes" class="form-control" readonly>

                        </div>

                        <div class="col-lg-2">

                            <label><b>OT (Min)</b></label>

                            <input type="number" name="overtime_minutes" id="overtime_minutes" class="form-control"
                                readonly>

                        </div>

                        <div class="col-lg-2">

                            <label><b>Worked Hours</b></label>

                            <input type="text" name="worked_hours" id="worked_hours" class="form-control" readonly>

                        </div>

                        <div class="col-lg-12">

                            <label><b>Remarks</b></label>

                            <textarea name="remarks" class="form-control" rows="3"></textarea>

                        </div>

                        <div class="col-lg-12">

                            <button class="btn btn-success">

                                <i class="fas fa-save"></i>

                                Save Attendance

                            </button>

                            <a href="{{ route('admin.employee-attendance.index') }}" class="btn btn-secondary">

                                Back

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>



@endsection

@push('js')

<script>
$(document).on('change', '#check_in, #check_out', function () {

    let inTime = $('#check_in').val();
    let outTime = $('#check_out').val();

    if (inTime && outTime) {

        // Office Time
        let officeIn = new Date("2000-01-01 09:00");
        let officeOut = new Date("2000-01-01 18:00");

        let checkIn = new Date("2000-01-01 " + inTime);
        let checkOut = new Date("2000-01-01 " + outTime);

        // Worked Hours
        let workedMinutes = (checkOut - checkIn) / 1000 / 60;
        $('#worked_hours').val((workedMinutes / 60).toFixed(2));

        // Late Minutes
        let lateMinutes = 0;
        if (checkIn > officeIn) {
            lateMinutes = Math.floor((checkIn - officeIn) / 1000 / 60);
        }
        $('#late_minutes').val(lateMinutes);

        // Overtime Minutes
        let overtimeMinutes = 0;
        if (checkOut > officeOut) {
            overtimeMinutes = Math.floor((checkOut - officeOut) / 1000 / 60);
        }
        $('#overtime_minutes').val(overtimeMinutes);

    }

});

</script>

@endpush

@endif