
@extends('layouts.admin.app')

@section('content')

@include('hrm.reports.report_style')

<div class="row">

    <div class="col-12">

        <div class="card report-card shadow-sm">

            {{-- =====================================================
                HEADER
            ====================================================== --}}

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0 report-title">

                    <i class="fas fa-file-invoice-dollar text-primary me-2"></i>

                    Expense Report

                </h5>

            </div>


            {{-- =====================================================
                FILTER
            ====================================================== --}}

            <div class="card-body filter-section border-bottom">

                <div class="row g-3 align-items-end">

                    {{-- ================= FROM DATE ================= --}}

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


                    {{-- ================= TO DATE ================= --}}

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


                    {{-- ================= EXPENSE HEAD ================= --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-sitemap text-info"></i>

                            Expense Head

                        </label>

                        <select
                            id="coa_id"
                            class="form-select select"
                        >

                            <option value="">
                                All Expense Head
                            </option>

                            @foreach($heads as $head)

                                <option value="{{ $head->id }}">

                                    {{ $head->head_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ================= STATUS ================= --}}

                     <div class="col-lg-2 col-md-6">

                        <label class="filter-label">

                            <i class="fas fa-check-circle text-warning"></i>

                            Status

                        </label>

                        <select
                            id="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option value="Approved">
                                Approved
                            </option>

                            <option value="Paid">
                                Paid
                            </option>

                            <option value="Pending">
                                Pending
                            </option>

                        </select>

                    </div>


                    {{-- ================= BUTTONS ================= --}}

                    <div class="col-lg-2 col-md-6">

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


            {{-- =====================================================
                TABLE
            ====================================================== --}}

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover dataTable align-middle"
                        style="width:100%"
                    >

                        <thead class="table-dark text-nowrap">

                            <tr>

                                <th>
                                    SL
                                </th>

                                <th>
                                    Expense Head
                                </th>

                                <th>
                                    Expense Month
                                </th>

                                <th>
                                    Year
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                                <th>
                                    Expense Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th style="min-width:250px;">
                                    Remarks
                                </th>

                            </tr>

                        </thead>


                        <tbody></tbody>


                        <tfoot>

                            <tr>

                                <th
                                    colspan="4"
                                    class="text-end"
                                >

                                    Page Total :

                                </th>

                                <th
                                    class="text-end"
                                    id="total_expense_amount"
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


    /* =========================================================
       DATATABLE
    ========================================================= */

    var table = $('.dataTable').DataTable({

        processing: true,

        serverSide: true,

        scrollX: true,

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],


        /* =====================================================
           BUTTONS
        ====================================================== */

        dom: 'Bfrtip',

        buttons: [

            {
                extend: 'excelHtml5',

                text:
                    '<i class="fas fa-file-excel me-1"></i> Excel',

                className:
                    'btn btn-success btn-sm',

                title:
                    'Expense Report',

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


        /* =====================================================
           AJAX
        ====================================================== */

        ajax: {

            url: "{{ route('admin.expense.report') }}",

            data: function (d) {

                d.from_date =
                    $('#from_date').val();

                d.to_date =
                    $('#to_date').val();

                d.coa_id =
                    $('#coa_id').val();

                d.status =
                    $('#status').val();

            }

        },


        /* =====================================================
           COLUMNS
        ====================================================== */

        columns: [

            /* ================= SL ================= */

            {
                data: 'DT_RowIndex',

                name: 'DT_RowIndex',

                orderable: false,

                searchable: false,

                className: 'text-center'
            },


            /* ================= EXPENSE HEAD ================= */

            {
                data: 'expense_head',

                name: 'coa.head_name',

                defaultContent: '-',

                render: function (data) {

                    return data
                        ? data
                        : '-';

                }

            },


            /* ================= MONTH ================= */

            {
                data: 'expense_month',

                name: 'exp.expense_month',

                defaultContent: '-',

                className: 'text-center'

            },


            /* ================= YEAR ================= */

            {
                data: 'expense_year',

                name: 'exp.expense_year',

                defaultContent: '-',

                className: 'text-center'

            },


            /* ================= AMOUNT ================= */

            {
                data: 'expense_amount',

                name: 'exp.expense_amount',

                className: 'text-end amount-cell',

                render: function (data) {

                    var amount =
                        parseFloat(data) || 0;

                    return amount.toLocaleString(
                        'en-US',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    ) + ' Tk.';

                }

            },


            /* ================= EXPENSE DATE ================= */

            {
                data: 'expense_date',

                name: 'exp.expense_date',

                defaultContent: '-'

            },


            /* ================= STATUS ================= */

            {
                data: 'status',

                name: 'exp.status',

                orderable: true,

                searchable: true,

                defaultContent: '-',

                className: 'text-center'

            },


            /* ================= REMARKS ================= */

            {
                data: 'remarks',

                name: 'exp.remarks',

                defaultContent: '-',

                render: function (data) {

                    return data
                        ? data
                        : '-';

                }

            }

        ],


        /* =====================================================
           FOOTER TOTAL
        ====================================================== */

        footerCallback: function (
            row,
            data,
            start,
            end,
            display
        ) {

            var api = this.api();


            function parseValue(value) {

                if (
                    typeof value === 'string'
                ) {

                    return parseFloat(
                        value.replace(
                            /[^0-9.-]+/g,
                            ''
                        )
                    ) || 0;

                }


                return parseFloat(value) || 0;

            }


            var expenseTotal =

                api
                    .column(
                        4,
                        {
                            page: 'current'
                        }
                    )
                    .data()
                    .reduce(
                        function (a, b) {

                            return (
                                parseValue(a)
                                +
                                parseValue(b)
                            );

                        },
                        0
                    );


            $('#total_expense_amount').html(

                expenseTotal.toLocaleString(
                    'en-US',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                )
                +
                ' Tk.'

            );

        }

    });


    /* =========================================================
       FILTER BUTTON
    ====================================================== */

    $('#btnFilter').on(
        'click',
        function () {

            table.ajax.reload();

        }
    );


    /* =========================================================
       AUTO FILTER
    ====================================================== */

    $(
        '#from_date, #to_date, #coa_id, #status'
    ).on(
        'change',
        function () {

            table.ajax.reload();

        }
    );


    /* =========================================================
       RESET
    ====================================================== */

    $('#btnReset').on(
        'click',
        function () {

            $('#from_date').val('');

            $('#to_date').val('');

            $('#coa_id').val('');

            $('#status').val('');


            table.ajax.reload();

        }
    );


});

</script>

@endpush
