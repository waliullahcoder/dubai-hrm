@extends('layouts.admin.app')

@section('content')

<style>
    .adv-card { border: 1px solid #dbe3ec; border-radius: 8px; margin-bottom: 14px; overflow: hidden; background: #fff; }
    .adv-card-head { padding: 10px 16px; font-weight: 600; font-size: 17px; display: flex; align-items: center; gap: 8px; }
    .adv-card-head small { font-weight: 500; font-size: 14px; }
    .adv-card-body { padding: 14px 16px 16px; }
    .head-search   { background: #e3f0fc; color: #0b3b7a; }
    .head-details  { background: #fff; color: #0b3b7a; border-bottom: 1px solid #e9ecef; }
    .head-info     { background: #fff; color: #0b3b7a; border-bottom: 1px solid #e9ecef; }
    .head-prev     { background: #fdf3e3; color: #6b3d00; }
    .head-amount   { background: #dff3df; color: #14532d; }
    .card-prev     { border-color: #f1d7a8; }
    .card-amount   { border-color: #b7e0b7; }
    .adv-card label { font-weight: 500; font-size: 14px; margin-bottom: 4px; }
    .adv-card .form-control, .adv-card .form-select { min-height: 40px; }
    .adv-card .form-control[readonly] { background: #eef1f4; }
    .big-input { font-size: 30px !important; font-weight: 600; min-height: 56px !important; }
    .big-input.blue { background: #e6f1fd !important; color: #0b57c3; border-color: #3b82f6; }
    .op-sign { font-size: 28px; font-weight: 700; color: #0b3b7a; text-align: center; padding-top: 28px; }
    .total-wrap { background: #e6f1fd; }
    #search_loader { display: none; }
</style>

<div class="container-fluid py-3">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-file-circle-plus fa-2x text-primary"></i>
            <div>
                <h4 class="fw-bold mb-0">Add Employee Advance</h4>
                <small class="text-muted">Create a new employee advance record</small>
            </div>
        </div>
        <a href="{{ route('admin.employee-loan.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- ========== Search Employee ========== --}}
    <div class="adv-card">
        <div class="adv-card-head head-search"><i class="fas fa-search"></i> Search Employee</div>
        <div class="adv-card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label>Employee Name <span class="text-danger">*</span></label>
                    <select id="employee_select" class="form-select select">
                        <option value="">Type employee name and select...</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}, {{ $employee->phone }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 text-center fw-bold pb-2">OR</div>

                <div class="col-md-3">
                    <label>Mobile Number</label>
                    <input type="text" id="mobile_search" class="form-control" placeholder="Enter mobile number">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="button" class="btn btn-primary flex-fill" id="btnSearch">
                        <i class="fas fa-search me-1"></i> Search
                        <span class="spinner-border spinner-border-sm ms-1" id="search_loader"></span>
                    </button>
                    <button type="button" class="btn btn-light border flex-fill" id="btnClear">
                        <i class="fas fa-sync-alt me-1"></i> Clear
                    </button>
                </div>
            </div>
            <div class="text-danger small mt-2" id="search_error" style="display:none;"></div>
        </div>
    </div>

    <form action="{{ route('admin.employee-loan.store') }}" method="POST" id="advanceForm">
        @csrf
        <input type="hidden" name="employee_id" id="employee_id">
        <input type="hidden" name="save_and_print" id="save_and_print" value="0">
        <input type="hidden" name="installment_amount" value="0">
        <input type="hidden" name="total_installments" value="0">
        <input type="hidden" name="status" value="Approved">

        {{-- ========== Employee Details ========== --}}
        <div class="adv-card">
            <div class="adv-card-head head-details">
                <i class="fas fa-user text-primary"></i> Employee Details <small>(Auto Filled)</small>
            </div>
            <div class="adv-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Employee Name</label>
                        <input type="text" id="emp_name" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Mobile Number</label>
                        <input type="text" id="emp_phone" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Emirates ID</label>
                        <input type="text" id="emp_national_id" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Employee ID</label>
                        <input type="text" id="emp_code" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== Advance Information ========== --}}
        <div class="adv-card">
            <div class="adv-card-head head-info"><i class="fas fa-calendar-alt text-primary"></i> Advance Information</div>
            <div class="adv-card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Type of Advance <span class="text-danger">*</span></label>
                        <select name="loan_type" class="form-select" required>
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
                    <div class="col-md-3">
                        <label>Payroll Month <span class="text-danger">*</span></label>
                        <select name="payroll_month" class="form-select" required>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Payroll Year <span class="text-danger">*</span></label>
                        <select name="payroll_year" class="form-select" required>
                            @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Advance Date <span class="text-danger">*</span></label>
                        <input type="date" name="loan_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== Previous Advance Information ========== --}}
        <div class="adv-card card-prev">
            <div class="adv-card-head head-prev">
                <i class="fas fa-history"></i> Previous Advance Information <small>(Auto Filled)</small>
            </div>
            <div class="adv-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Previous Advance Date</label>
                        <input type="text" id="prev_date" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Previous Advance Amount (AED)</label>
                        <input type="text" id="prev_amount" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Previous Outstanding Balance (AED)</label>
                        <input type="text" id="prev_outstanding_view" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== Advance Amount Details ========== --}}
        <div class="adv-card card-amount">
            <div class="adv-card-head head-amount"><i class="fas fa-dollar-sign"></i> Advance Amount Details</div>
            <div class="adv-card-body">
                <div class="row g-2 align-items-start">
                    <div class="col-md-4">
                        <label>New Advance Amount (AED) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="loan_amount" id="loan_amount"
                               class="form-control big-input blue" required>
                    </div>
                    <div class="col-md-1 op-sign">+</div>
                    <div class="col-md-3">
                        <label>Previous Outstanding Amount (AED)</label>
                        <input type="text" id="prev_outstanding" class="form-control" value="0.00" readonly>
                    </div>
                    <div class="col-md-1 op-sign">=</div>
                    <div class="col-md-3">
                        <label>Total Outstanding Amount (AED)</label>
                        <input type="text" id="total_outstanding" class="form-control big-input blue" value="0.00" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== Remarks ========== --}}
        <div class="adv-card">
            <div class="adv-card-body">
                <label class="fw-bold"><i class="fas fa-file-alt me-1"></i> Remarks <span class="fw-normal">(Optional)</span></label>
                <textarea name="remarks" rows="2" class="form-control" placeholder="Enter remarks..."></textarea>
            </div>
        </div>

        {{-- ========== Buttons ========== --}}
        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-success px-4" id="btnSave">
                <i class="fas fa-save me-1"></i> Save
            </button>
            <button type="button" class="btn btn-primary px-4" id="btnSavePrint">
                <i class="fas fa-print me-1"></i> Save &amp; Print
            </button>
            <button type="button" class="btn btn-secondary px-4" id="btnPrint">
                <i class="fas fa-print me-1"></i> Print
            </button>
        </div>
    </form>
</div>

@endsection


@push('js')

<script>
    const infoUrl  = "{{ route('admin.employee-loan.employee-info') }}";
    const printUrl = "{{ route('admin.advance.loan.report') }}";

    function fmt(n) { return (parseFloat(n) || 0).toFixed(2); }

    function calcTotal() {
        const total = (parseFloat($('#loan_amount').val()) || 0) + (parseFloat($('#prev_outstanding').val()) || 0);
        $('#total_outstanding').val(fmt(total));
    }

    function clearAll() {
        $('#employee_id').val('');
        $('#emp_name, #emp_phone, #emp_national_id, #emp_code, #prev_date, #prev_amount, #prev_outstanding_view').val('');
        $('#prev_outstanding').val('0.00');
        $('#search_error').hide();
        calcTotal();
    }

    function fillEmployee(d) {
        $('#employee_id').val(d.id);
        $('#emp_name').val(d.name);
        $('#emp_phone').val(d.phone);
        $('#emp_national_id').val(d.national_id || 'NA');
        $('#emp_code').val(d.code);
        $('#prev_date').val(d.prev_date || '-');
        $('#prev_amount').val(fmt(d.prev_amount));
        $('#prev_outstanding_view').val(fmt(d.prev_outstanding));
        $('#prev_outstanding').val(fmt(d.prev_outstanding));
        calcTotal();
    }

    function fetchEmployee(params) {
        $('#search_loader').show();
        $('#search_error').hide();
        $.get(infoUrl, params)
            .done(function (res) { fillEmployee(res); })
            .fail(function () {
                clearAll();
                $('#search_error').text('Employee not found.').show();
            })
            .always(function () { $('#search_loader').hide(); });
    }

    // Search button: mobile number first, otherwise selected employee
    $('#btnSearch').on('click', function () {
        const phone = $('#mobile_search').val().trim();
        const id    = $('#employee_select').val();

        if (phone) {
            fetchEmployee({ phone: phone });
        } else if (id) {
            fetchEmployee({ employee_id: id });
        } else {
            $('#search_error').text('Select an employee or enter a mobile number.').show();
        }
    });

    // Auto-fetch when employee selected from dropdown
    $('#employee_select').on('change', function () {
        if ($(this).val()) {
            $('#mobile_search').val('');
            fetchEmployee({ employee_id: $(this).val() });
        }
    });

    $('#btnClear').on('click', function () {
        $('#employee_select').val('').trigger('change.select2');
        $('#mobile_search').val('');
        clearAll();
    });

    $('#loan_amount').on('input', calcTotal);

    function ensureEmployee() {
        if (!$('#employee_id').val()) {
            alert('Please search and select an employee first.');
            return false;
        }
        return true;
    }

    $('#advanceForm').on('submit', function (e) {
        if (!ensureEmployee()) e.preventDefault();
    });

    // Save & Print: save first, controller redirects to print page of this staff
    $('#btnSavePrint').on('click', function () {
        if (!ensureEmployee()) return;
        const form = $('#advanceForm')[0];
        if (!form.checkValidity()) { form.reportValidity(); return; }
        $('#save_and_print').val(1);
        form.submit();
    });

    // Print only (no save): open print page for the selected staff
    $('#btnPrint').on('click', function () {
        if (!ensureEmployee()) return;
        window.open(printUrl + '?employee_id=' + $('#employee_id').val(), '_blank');
    });

    calcTotal();
</script>

@endpush