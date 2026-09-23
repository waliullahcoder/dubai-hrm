@extends('layouts.admin.app')

@section('content')

<style>
    .loan-form-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
        overflow: hidden;
    }

    .loan-header {
        background: #f8fafc;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 22px;
    }

    .loan-title {
        font-weight: 600;
        color: #212529;
    }

    .loan-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .loan-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #198754;
        margin-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 8px;
    }

    .form-label {
        font-weight: 500;
        font-size: 14px;
    }

    .form-control,
    .form-select {
        min-height: 40px;
        border-radius: 6px;
    }

    .loan-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: 15px 22px;
    }

    /* Print Preview */
    #printPreview {
        display: none;
    }

    .print-header {
        text-align: center;
        border-bottom: 2px solid #198754;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .print-header h2 {
        margin: 0;
        font-size: 24px;
    }

    .print-header p {
        margin: 4px 0 0;
        color: #666;
    }

    .loan-print-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .loan-print-table th,
    .loan-print-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .loan-print-table th {
        width: 30%;
        background: #f5f5f5;
        font-weight: 600;
    }

    .signature-area {
        display: flex;
        justify-content: space-between;
        margin-top: 80px;
    }

    .signature-box {
        width: 180px;
        text-align: center;
        border-top: 1px solid #333;
        padding-top: 8px;
    }

  @media print {

    /* Hide normal page */
    .loan-form-card,
    header,
    footer,
    aside,
    nav,
    .sidebar,
    .main-sidebar,
    .navbar, .navbar-header {
        display: none !important;
    }

    /* Show print area */
    #printPreview {
        display: block !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;

        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;

        margin: 0 !important;
        padding: 10mm !important;

        background: #fff !important;

        box-sizing: border-box !important;

        page-break-before: avoid !important;
        page-break-after: avoid !important;
    }

    #printPreview * {
        visibility: visible !important;
    }

    .print-header {
        text-align: center !important;
        border-bottom: 2px solid #198754 !important;
        padding-bottom: 8px !important;
        margin-bottom: 10px !important;
    }

    .print-header h2 {
        margin: 0 !important;
        font-size: 20px !important;
    }

    .print-header p {
        margin: 2px 0 !important;
        font-size: 12px !important;
    }

    .loan-print-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;

        page-break-inside: avoid !important;
    }

    .loan-print-table tr {
        page-break-inside: avoid !important;
    }

    .loan-print-table th,
    .loan-print-table td {
        border: 1px solid #999 !important;
        padding: 6px 8px !important;
        font-size: 12px !important;
        line-height: 1.2 !important;
    }

    .loan-print-table th {
        width: 30% !important;
    }

    .signature-area {
        display: flex !important;
        justify-content: space-between !important;

        margin-top: 80px !important;

        page-break-inside: avoid !important;
    }

    .signature-box {
        width: 160px !important;

        border-top: 1px solid #333 !important;

        padding-top: 5px !important;

        text-align: center !important;

        font-size: 11px !important;
    }

    @page {
        size: A4 portrait;
        margin: 8mm;
    }
}
</style>

<div class="card loan-form-card">


{{-- Header --}}
<div class="loan-header d-flex justify-content-between align-items-center">

    <div>
        <h5 class="loan-title mb-1">
            <i class="fas fa-hand-holding-usd text-success me-1"></i>
            Add Employee Loan
        </h5>

        <small class="text-muted">
            Create a new employee loan record
        </small>
    </div>

    <a href="{{ route('admin.employee-loan.index') }}"
       class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>

</div>

<form action="{{ route('admin.employee-loan.store') }}"
      method="POST"
      id="loanForm">

    @csrf

    <div class="card-body p-4">

        {{-- Employee & Loan Information --}}
        <div class="loan-section">

            <div class="loan-section-title">
                <i class="fas fa-user-tie me-1"></i>
                Employee & Loan Information
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Employee <span class="text-danger">*</span>
                    </label>

                    <select name="employee_id"
                            id="employee_id"
                            class="form-select select"
                            required>
                        @foreach($employees as $employee)

                            <option value="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}">

                                {{ $employee->id }} - {{ $employee->name }}, {{ $employee->phone }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Loan Type <span class="text-danger">*</span>
                    </label>

                    <select name="loan_type"
                            id="loan_type"
                            class="form-select"
                            required>

                        <option value="Salary Advance">Salary Advance</option>
                        <option value="Personal Loan">Personal Loan</option>
                        <option value="Emergency Loan">Emergency Loan</option>
                        <option value="Medical Loan">Medical Loan</option>
                        <option value="House Loan">House Loan</option>
                        <option value="Vehicle Loan">Vehicle Loan</option>
                        <option value="Education Loan">Education Loan</option>
                        <option value="Festival Loan">Festival Loan</option>
                        <option value="Other">Other</option>

                    </select>

                </div>

            </div>

        </div>


        {{-- Payroll Information --}}
        <div class="loan-section">

            <div class="loan-section-title">
                <i class="fas fa-calendar-alt me-1"></i>
                Payroll Information
            </div>

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Payroll Month <span class="text-danger">*</span>
                    </label>

                    <select name="payroll_month"
                            id="payroll_month"
                            class="form-select"
                            required>

                        @for($i = 1; $i <= 12; $i++)

                            <option value="{{ $i }}"
                                {{ date('n') == $i ? 'selected' : '' }}>

                                {{ date('F', mktime(0,0,0,$i,1)) }}

                            </option>

                        @endfor

                    </select>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Payroll Year
                    </label>

                    <select name="payroll_year"
                            id="payroll_year"
                            class="form-select">

                        @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)

                            <option value="{{ $i }}"
                                {{ request('payroll_year', date('Y')) == $i ? 'selected' : '' }}>

                                {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Loan Date <span class="text-danger">*</span>
                    </label>

                    <input type="date"
                           name="loan_date"
                           id="loan_date"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           required>

                </div>


                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select">

                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Paid">Paid</option>

                    </select>

                </div>

            </div>

        </div>


        {{-- Loan Amount --}}
        <div class="loan-section">

            <div class="loan-section-title">
                <i class="fas fa-money-bill-wave me-1"></i>
                Loan Amount & Installment
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Loan Amount <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="loan_amount"
                           id="loan_amount"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Installment Amount <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="installment_amount"
                           id="installment_amount"
                           class="form-control"
                           value=0
                           required>

                </div>



                    <input type="hidden"
                           name="total_installments"
                           id="total_installments"
                           class="form-control"
                           value=0
                           required>


            </div>

        </div>


        {{-- Remarks --}}
        <div class="loan-section mb-0">

            <div class="loan-section-title">
                <i class="fas fa-comment-alt me-1"></i>
                Additional Information
            </div>

            <div class="row">

                <div class="col-md-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              id="remarks"
                              rows="2"
                              class="form-control"></textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- Footer --}}
    <div class="loan-footer d-flex justify-content-between align-items-center">

        <button type="button"
                class="btn btn-outline-primary"
                onclick="showPrintPreview()">

            <i class="fas fa-print"></i>
            Print Preview

        </button>


        <div>

            <a href="{{ route('admin.employee-loan.index') }}"
               class="btn btn-light me-1">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-success">

                <i class="fas fa-save"></i>
                Save Loan

            </button>

        </div>

    </div>

</form>


</div>

{{-- =========================
PRINT PREVIEW
========================= --}}

<div id="printPreview">


<div class="print-header">

    <h2>Employee Loan Documents</h2>

    <p>Employee Loan Information</p>

</div>


<table class="loan-print-table">

    <tr>
        <th>Employee</th>
        <td id="preview_employee">-</td>
    </tr>

    <tr>
        <th>Loan Type</th>
        <td id="preview_loan_type">-</td>
    </tr>

    <tr>
        <th>Payroll Month</th>
        <td id="preview_payroll_month">-</td>
    </tr>

    <tr>
        <th>Payroll Year</th>
        <td id="preview_payroll_year">-</td>
    </tr>

    <tr>
        <th>Loan Amount</th>
        <td id="preview_loan_amount">0.00</td>
    </tr>

    <tr>
        <th>Installment Amount</th>
        <td id="preview_installment_amount">0.00</td>
    </tr>

    <tr>
        <th>Total Installments</th>
        <td id="preview_total_installments">0</td>
    </tr>

    <tr>
        <th>Loan Date</th>
        <td id="preview_loan_date">-</td>
    </tr>

    <tr>
        <th>Status</th>
        <td id="preview_status">Pending</td>
    </tr>

    <tr>
        <th>Remarks</th>
        <td id="preview_remarks">-</td>
    </tr>

</table>


<div class="signature-area">

    <div class="signature-box">
        Employee Signature
    </div>

    <div class="signature-box">
        Authorized By
    </div>

</div>


</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | Calculate Total Installments
    |--------------------------------------------------------------------------
    */

    function calculateInstallments() {

        let loanAmount =
            parseFloat($('#loan_amount').val()) || 0;

        let installmentAmount =
            parseFloat($('#installment_amount').val()) || 0;

        if (loanAmount > 0 && installmentAmount > 0) {

            let total =
                Math.ceil(loanAmount / installmentAmount);

            $('#total_installments').val(total);

        } else {

            $('#total_installments').val('');

        }
    }


    $('#loan_amount, #installment_amount').on('input', function () {

        calculateInstallments();

    });


    /*
    |--------------------------------------------------------------------------
    | Print Preview
    |--------------------------------------------------------------------------
    */

    function showPrintPreview() {

        let employee =
            $('#employee_id option:selected').text().trim();

        let loanType =
            $('#loan_type').val();

        let payrollMonth =
            $('#payroll_month option:selected').text();

        let payrollYear =
            $('#payroll_year').val();

        let loanAmount =
            parseFloat($('#loan_amount').val() || 0).toFixed(2);

        let installmentAmount =
            parseFloat($('#installment_amount').val() || 0).toFixed(2);

        let totalInstallments =
            $('#total_installments').val() || 0;

        let loanDate =
            $('#loan_date').val();

        let status =
            $('#status').val();

        let remarks =
            $('#remarks').val() || '-';


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if (!$('#loanForm')[0].checkValidity()) {

            $('#loanForm')[0].reportValidity();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Set Preview Data
        |--------------------------------------------------------------------------
        */

        $('#preview_employee').text(employee);

        $('#preview_loan_type').text(loanType);

        $('#preview_payroll_month').text(payrollMonth);

        $('#preview_payroll_year').text(payrollYear);

        $('#preview_loan_amount').text(loanAmount);

        $('#preview_installment_amount').text(installmentAmount);

        $('#preview_total_installments').text(totalInstallments);

        $('#preview_loan_date').text(loanDate);

        $('#preview_status').text(status);

        $('#preview_remarks').text(remarks);


        /*
        |--------------------------------------------------------------------------
        | Print
        |--------------------------------------------------------------------------
        */

        window.print();

    }

</script>

@endsection
