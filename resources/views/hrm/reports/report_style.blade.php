
<style>
    .report-card {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .report-card .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f5;
        padding: 18px 22px;
    }

    .report-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .filter-section {
        background: #f8fafc;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .filter-label i {
        margin-right: 4px;
    }

    .form-control,
    .form-select {
        min-height: 40px;
        border-radius: 8px;
        border: 1px solid #dbe1ea;
        font-size: 13px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .10);
    }

    .btn {
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .table thead th {
        font-size: 12px;
        font-weight: 700;
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table tbody td {
        font-size: 13px;
        padding: 11px 10px;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    .amount-cell {
        font-weight: 700;
        color: #198754;
    }

    .payment-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .payment-badge.payment {
        background: #dcfce7;
        color: #15803d;
    }

    .payment-badge.advance {
        background: #fef3c7;
        color: #a16207;
    }

    .payment-badge.default {
        background: #e2e8f0;
        color: #475569;
    }

    tfoot tr {
        background: #eff6ff !important;
    }

    tfoot th {
        padding: 13px 10px !important;
        font-size: 13px;
    }

    #total_payment_amount {
        color: #0d6efd;
        font-size: 14px;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 12px;
    }

    .dataTables_wrapper .dt-buttons .btn {
        margin-right: 5px;
    }

    .dataTables_filter input {
        border: 1px solid #dbe1ea !important;
        border-radius: 8px !important;
        padding: 7px 10px !important;
        margin-left: 5px;
    }

    .dataTables_length select {
        border: 1px solid #dbe1ea;
        border-radius: 7px;
        padding: 5px 25px 5px 8px;
    }

    @media (max-width: 768px) {

        .report-card .card-header {
            padding: 15px;
        }

        .filter-section {
            padding: 15px !important;
        }

        .report-title {
            font-size: 16px;
        }
    }
</style>

