@extends('layouts.admin.app')

@section('content')
@include('hrm.reports.report_style')
<div class="row">

    <div class="col-12">

        <div class="card report-card shadow-sm">

            {{-- ================= HEADER ================= --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0 report-title">

                    <i class="fas fa-money-bill-wave text-primary me-2"></i>

                    Payment Report

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

                        <input
                            type="date"
                            id="from_date"
                            class="form-control"
                            value="{{ date('Y-m-01') }}"
                        >

                    </div>


                    {{-- To Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-calendar-alt text-primary"></i>

                            To Date

                        </label>

                        <input
                            type="date"
                            id="to_date"
                            class="form-control"
                            value="{{ date('Y-m-t') }}"
                        >

                    </div>


                    {{-- Employee --}}
                    <div class="col-lg-3 col-md-6">

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


                    {{-- Payment Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-money-check-alt text-warning"></i>

                            Payment Status

                        </label>

                        <select id="status" class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Payment">
                                Payment
                            </option>

                            <option value="Advance">
                                Advance
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="d-flex gap-2">

                            <button
                                type="button"
                                id="btnFilter"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-search me-1"></i>

                                Filter

                            </button>


                            <button
                                type="button"
                                id="btnReset"
                                class="btn btn-outline-secondary"
                            >

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

                    <table
                        class="table table-bordered table-hover dataTable align-middle"
                        style="width:100%"
                    >

                        <thead class="table-dark text-nowrap">

                            <tr>

                                <th>SL</th>

                                <th>Employee Code</th>

                                <th>Employee Name</th>

                                <th>Payroll Month</th>

                                <th>Year</th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>Payment Date</th>

                                <th>Payment Status</th>

                                <th style="min-width:200px;">
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody></tbody>


                        <tfoot>

                            <tr>

                                <th colspan="5" class="text-end">

                                    Page Total :

                                </th>

                                <th
                                    class="text-end"
                                    id="total_payment_amount"
                                >
                                    0.00 Tk.
                                </th>

                                <th colspan="3"></th>

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

$(function () {

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

                title: 'Payment Report',

                exportOptions: {

                    columns: [
                        0,
                        1,
                        2,
                        3,
                        4,
                        5,
                        6,
                        7,
                        8
                    ]

                }

            }

        ],


        ajax: {

            url: "{{ route('admin.payment.report') }}",

            data: function (d) {

                d.from_date = $('#from_date').val();

                d.to_date = $('#to_date').val();

                d.employee_id = $('#employee_id').val();

                d.status = $('#status').val();

            }

        },


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
                data: 'payment_month',
                name: 'p.payment_month'
            },

            {
                data: 'payment_year',
                name: 'p.payment_year'
            },

            {
                data: 'payment_amount',
                name: 'p.payment_amount',

                className: 'text-end amount-cell'
            },

            {
                data: 'payment_date',
                name: 'p.payment_date'
            },

            {
                data: 'status',
                name: 'p.status',

                orderable: true,

                searchable: true
            },

            {
                data: 'remarks',
                name: 'p.remarks',

                render: function (data) {

                    return data ? data : '-';

                }

            }

        ],


        footerCallback: function (row, data, start, end, display) {

            var api = this.api();


            function parseValue(value) {

                if (typeof value === 'string') {

                    return parseFloat(
                        value.replace(/[^0-9.-]+/g, '')
                    ) || 0;

                }

                return parseFloat(value) || 0;

            }


            var paymentTotal = api
                .column(5, {
                    page: 'current'
                })
                .data()
                .reduce(function (a, b) {

                    return parseValue(a) + parseValue(b);

                }, 0);


            $('#total_payment_amount').html(

                paymentTotal.toLocaleString('en-US', {

                    minimumFractionDigits: 2,

                    maximumFractionDigits: 2

                }) + ' Tk.'

            );

        }

    });


    // ================= FILTER =================

    $('#btnFilter').on('click', function () {

        table.ajax.reload();

    });


    // ================= AUTO FILTER =================

    $('#from_date, #to_date, #employee_id, #status')
        .on('change', function () {

            table.ajax.reload();

        });


    // ================= RESET =================

    $('#btnReset').on('click', function () {

        $('#from_date').val('');

        $('#to_date').val('');

        $('#employee_id').val('');

        $('#status').val('');

        table.ajax.reload();

    });

});

</script>

@endpush