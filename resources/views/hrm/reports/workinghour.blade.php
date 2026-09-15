@extends('layouts.admin.app')

@section('content')

@include('hrm.reports.report_style')

<div class="row">

    <div class="col-12">

        <div class="card report-card shadow-sm">

            {{-- ================= HEADER ================= --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0 report-title">
                    <i class="fas fa-clock text-primary me-2"></i>
                    Working Hour Report
                </h5>

            </div>


            {{-- ================= FILTER ================= --}}
            <div class="card-body filter-section border-bottom">

                <div class="row g-3 align-items-end">

                    {{-- From Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-calendar-alt text-primary"></i>
                            From Date

                        </label>

                        <input type="date" id="from_date" class="form-control" value="{{ date('Y-m-01') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-calendar-alt text-primary"></i>
                            To Date

                        </label>

                        <input type="date" id="to_date" class="form-control" value="{{ date('Y-m-t') }}">

                    </div>


                    {{-- Employee --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-user text-success"></i>
                            Employee

                        </label>

                        <select id="employee_id" class="form-select">

                            <option value="">
                                All Employee
                            </option>

                            @foreach($employees as $employee)

                            <option value="{{ $employee->id }}">

                                {{ $employee->code }} -
                                {{ $employee->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="d-flex gap-2">

                            <button type="button" id="btnFilter" class="btn btn-primary">

                                <i class="fas fa-search me-1"></i>
                                Filter

                            </button>


                            <button type="button" id="btnReset" class="btn btn-outline-secondary">

                                <i class="fas fa-sync-alt me-1"></i>
                                Reset

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================= TABLE ================= --}}
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover dataTable align-middle" style="width:100%">

                        <thead class="table-dark text-nowrap">

                            <tr>

                                <th>SL</th>

                                <th>Employee Code</th>

                                <th>Employee Name</th>

                                <th>Attendance Date</th>

                                <th>Check In</th>

                                <th>Check Out</th>

                                <th class="text-center">
                                    Working Hour
                                </th>

                                <th style="min-width:250px;">
                                    Remark
                                </th>

                            </tr>

                        </thead>


                        <tbody></tbody>


                        <tfoot>

                            <tr>

                                <th colspan="6" class="text-end">

                                    Page Total Working Hour :

                                </th>


                                <th class="text-center" id="total_working_hour">
                                    0:00
                                </th>

                                <th></th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>
$(function() {

    var table = $('.dataTable').DataTable({

        processing: true,

        serverSide: true,

        scrollX: true,

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],

        dom: 'Bfrtip',

        buttons: [

            {
                extend: 'excelHtml5',

                text: '<i class="fas fa-file-excel me-1"></i> Excel',

                className: 'btn btn-success btn-sm',

                title: 'Working Hour Report',

                exportOptions: {

                    columns: [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5,
                        6,
                        7
                    ]

                }

            }

        ],


        // ================= AJAX =================

        ajax: {

            url: "{{ route('admin.workinghour.report') }}",

            data: function(d) {

                d.from_date =
                    $('#from_date').val();

                d.to_date =
                    $('#to_date').val();

                d.employee_id =
                    $('#employee_id').val();

            }

        },


        // ================= COLUMNS =================

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },


            {
                data: 'employee_code',
                name: 's.code'
            },


            {
                data: 'employee_name',
                name: 's.name'
            },


            {
                data: 'attendance_date',
                name: 'atd.attendance_date'
            },


            {
                data: 'check_in',
                name: 'atd.check_in',
                className: 'text-center'
            },


            {
                data: 'check_out',
                name: 'atd.check_out',
                className: 'text-center'
            },


            {
                data: 'worked_hours',
                name: 'atd.worked_hours',
                className: 'text-center'
            },


            {
                data: 'remarks',
                name: 'atd.remarks',

                render: function(data) {

                    return data ?
                        data :
                        '-';

                }

            }

        ],


        // ================= FOOTER TOTAL =================

       footerCallback: function (row, data, start, end, display) {

    var api = this.api();

    var totalMinutes = 0;

    api.column(6, { page: 'current' }).data().each(function (value) {

        if (!value) {
            return;
        }

        // HTML badge থাকলে HTML remove করবে
        var text = $('<div>').html(value).text().trim();

        // 08:30 / 8:30 / 08:30:00
        if (text.indexOf(':') !== -1) {

            var parts = text.split(':');

            var hours = parseInt(parts[0]) || 0;
            var minutes = parseInt(parts[1]) || 0;

            totalMinutes += (hours * 60) + minutes;

        }

        // যদি 8.5 / 8.00 এর মতো decimal hour হয়
        else if (!isNaN(parseFloat(text))) {

            totalMinutes += Math.round(
                parseFloat(text) * 60
            );

        }

    });


    var hours = Math.floor(totalMinutes / 60);

    var minutes = totalMinutes % 60;


    $('#total_working_hour').html(
        hours + ':' + String(minutes).padStart(2, '0')
    );

}

    });


    // ================= FILTER BUTTON =================

    $('#btnFilter').on(
        'click',
        function() {

            table.ajax.reload();

        }
    );


    // ================= AUTO FILTER =================

    $('#from_date, #to_date, #employee_id')
        .on(
            'change',
            function() {

                table.ajax.reload();

            }
        );


    // ================= RESET =================

    $('#btnReset').on(
        'click',
        function() {

            $('#from_date').val('');
            $('#to_date').val('');
            $('#employee_id').val('');

            table.ajax.reload();

        }
    );

});
</script>


<style>
/* ================= WORKING HOUR BADGE ================= */

.working-hour-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 95px;

    padding: 6px 12px;

    border-radius: 20px;

    background: #eff6ff;

    color: #2563eb;

    font-weight: 600;

    font-size: 13px;

}


/* ================= TIME ================= */

.dataTable td.text-center {

    vertical-align: middle;

}


/* ================= REMARK ================= */

.dataTable td:last-child {

    white-space: normal;

    line-height: 1.5;

}
</style>

@endpush