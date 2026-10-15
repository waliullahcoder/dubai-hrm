@extends('layouts.admin.app')

@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-check text-success"></i>
                    Edit Employee Attendance
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.employee-attendance.update',$data->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-lg-4">
                            <label><b>Employee</b></label>

                            <select name="employee_id" id="employee_id"
                                class="select form-select"
                                data-placeholder="Select Employee"
                                required>

                                @foreach($employees as $employee)

                                <option value="{{ $employee->id }}"
                                    {{ $data->employee_id==$employee->id?'selected':'' }}>

                                    {{ $employee->id }} - {{ $employee->name }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-lg-4">

                            <label><b>Attendance Date</b></label>

                            <input type="date"
                                name="attendance_date"
                                class="form-control"
                                value="{{ $data->attendance_date }}"
                                required>

                        </div>

                        <div class="col-lg-4">

                            <label><b>Status</b></label>

                            <select name="attendance_status" class="form-control">

                                <option value="Present" {{ $data->attendance_status=='Present'?'selected':'' }}>Present</option>

                                <option value="Late" {{ $data->attendance_status=='Late'?'selected':'' }}>Late</option>

                                <option value="Absent" {{ $data->attendance_status=='Absent'?'selected':'' }}>Absent</option>

                                <option value="Half Day" {{ $data->attendance_status=='Half Day'?'selected':'' }}>Half Day</option>

                                <option value="Leave" {{ $data->attendance_status=='Leave'?'selected':'' }}>Leave</option>

                                <option value="Holiday" {{ $data->attendance_status=='Holiday'?'selected':'' }}>Holiday</option>

                                <option value="Weekend" {{ $data->attendance_status=='Weekend'?'selected':'' }}>Weekend</option>

                            </select>

                        </div>
                      
                        <div class="col-lg-3">

                            <label><b>Check In</b></label>

                            <input type="time"
                                name="check_in"
                                id="check_in"
                                class="form-control"
                                value="{{ $data->check_in }}">

                        </div>

                        <div class="col-lg-3">

                            <label><b>Check Out</b></label>

                            <input type="time"
                                name="check_out"
                                id="check_out"
                                class="form-control"
                                value="{{ $data->check_out }}">

                        </div>

                        <div class="col-lg-2">

                            <label><b>Late (Min)</b></label>

                            <input type="number"
                                name="late_minutes"
                                id="late_minutes"
                                class="form-control"
                                value="{{ $data->late_minutes }}"
                                readonly>

                        </div>

                        <div class="col-lg-2">

                            <label><b>OT (Min)</b></label>

                            <input type="number"
                                name="overtime_minutes"
                                id="overtime_minutes"
                                class="form-control"
                                value="{{ $data->overtime_minutes }}"
                                readonly>

                        </div>

                       <div class="col-lg-2">
                        <label><b>Worked Hours</b></label>

                        <input type="text"
                            name="worked_hours"
                            id="worked_hours"
                            class="form-control"
                            value="{{ $data->worked_hours }}"
                            readonly>
                    </div>

                    <div class="col-lg-6">
                        <label><b>Hour Rate</b></label>

                        <input type="text"
                            name="hour_rate"
                            id="hour_rate"
                            class="form-control"
                            value="{{ $data->hour_rate }}">
                    </div>

                    <div class="col-lg-6">
                        <label><b>Earning Amount</b></label>

                        <input type="text"
                            name="amount"
                            id="amount"
                            class="form-control"
                            value="{{ $data->amount }}"
                            readonly>
                    </div>


                        <div class="col-lg-12">

                            <label><b>Remarks</b></label>

                            <textarea
                                name="remarks"
                                class="form-control"
                                rows="3">{{ $data->remarks }}</textarea>

                        </div>

                        <div class="col-lg-12">

                            <button class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Update Attendance
                            </button>

                            <a href="{{ route('admin.employee-attendance.index') }}"
                                class="btn btn-secondary">
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
$(document).ready(function () {

    function calculateAttendance() {

        let inTime = $('#check_in').val();
        let outTime = $('#check_out').val();

        if (inTime && outTime) {

            // Office Time
            let officeIn = new Date('2000-01-01T09:00:00');
            let officeOut = new Date('2000-01-01T18:00:00');

            let checkIn = new Date('2000-01-01T' + inTime);
            let checkOut = new Date('2000-01-01T' + outTime);

            // Checkout next day হলে
            if (checkOut <= checkIn) {
                checkOut.setDate(checkOut.getDate() + 1);
            }

            // Worked Hours
            let workedMinutes = Math.max(
                0,
                (checkOut - checkIn) / 60000
            );

            $('#worked_hours').val(
                (workedMinutes / 60).toFixed(2)
            );

            // Late Minutes
            let lateMinutes = 0;

            if (checkIn > officeIn) {
                lateMinutes = Math.floor(
                    (checkIn - officeIn) / 60000
                );
            }

            $('#late_minutes').val(Math.max(0, lateMinutes));

            // Overtime Minutes
            let overtimeMinutes = 0;

            if (checkOut > officeOut) {
                overtimeMinutes = Math.floor(
                    (checkOut - officeOut) / 60000
                );
            }

            $('#overtime_minutes').val(
                Math.max(0, overtimeMinutes)
            );

        } else {
            $('#worked_hours').val('');
            $('#late_minutes').val('');
            $('#overtime_minutes').val('');
        }

        // Amount calculation
        calculateAmount();
    }

    function calculateAmount() {

        let workedHours =
            parseFloat($('#worked_hours').val()) || 0;

        let hourRate =
            parseFloat($('#hour_rate').val()) || 0;

        let amount = Math.max(0, workedHours * hourRate);

        $('#amount').val(amount.toFixed(2));
    }

    // Edit page load এবং time পরিবর্তন
    $(document).on(
        'change input',
        '#check_in, #check_out',
        calculateAttendance
    );

    // Hour rate পরিবর্তন
    $(document).on(
        'input change',
        '#hour_rate',
        calculateAmount
    );

    // Edit page-এ আগের data নিয়েও হিসাব হবে
    calculateAttendance();

});
</script>

@endpush