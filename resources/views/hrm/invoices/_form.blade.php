{{-- resources/views/hrm/invoices/_form.blade.php --}}
@php
    $isEdit = isset($invoice);
    $oldDate = old('invoice_date', $isEdit ? date('d-m-Y', strtotime($invoice->invoice_date)) : date('d-m-Y'));
@endphp

@if ($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="invoice_no" class="form-label"><b>Invoice Number <span class="text-danger">*</span></b></label>
        <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="Invoice Number"
            value="{{ old('invoice_no', $isEdit ? $invoice->invoice_no : $invoice_no) }}" required>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="invoice_date" class="form-label"><b>Invoice Date <span class="text-danger">*</span></b></label>
        <input type="text" class="form-control date_picker" id="invoice_date" name="invoice_date"
            value="{{ $oldDate }}" placeholder="dd-mm-yyyy" autocomplete="off" required>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="amount_before_vat" class="form-label"><b>Amount Before VAT (AED) <span
                    class="text-danger">*</span></b></label>
        <input type="number" step="any" min="0" class="form-control" id="amount_before_vat"
            name="amount_before_vat" placeholder="0.00"
            value="{{ old('amount_before_vat', $isEdit ? $invoice->amount_before_vat : '') }}" required>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="vat_percent" class="form-label"><b>VAT % <span class="text-danger">*</span></b></label>
        <select name="vat_percent" id="vat_percent" class="form-select" required>
            @foreach ([0, 5, 10, 15] as $vat)
                <option value="{{ $vat }}"
                    {{ (string) old('vat_percent', $isEdit ? (float) $invoice->vat_percent : 5) === (string) $vat ? 'selected' : '' }}>
                    {{ $vat }}%
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="vat_amount" class="form-label"><b>VAT Amount (AED)</b></label>
        <input type="text" class="form-control bg-light" id="vat_amount" readonly
            value="{{ $isEdit ? number_format($invoice->vat_amount, 2, '.', '') : '0.00' }}">
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="total_amount" class="form-label"><b>Total Amount (AED)</b></label>
        <input type="text" class="form-control bg-light fw-bold" id="total_amount" readonly
            value="{{ $isEdit ? number_format($invoice->total_amount, 2, '.', '') : '0.00' }}">
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="lpo_number" class="form-label"><b>LPO Number <span class="text-danger">*</span></b></label>
        <input type="text" class="form-control" id="lpo_number" name="lpo_number" placeholder="LPO Number"
            value="{{ old('lpo_number', $isEdit ? $invoice->lpo_number : '') }}" required>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="active_status" class="form-label"><b>Active Status <span class="text-danger">*</span></b></label>
        <select name="active_status" id="active_status" class="form-select" required>
            @foreach (['Active', 'Inactive'] as $st)
                <option value="{{ $st }}"
                    {{ old('active_status', $isEdit ? $invoice->active_status : 'Active') == $st ? 'selected' : '' }}>
                    {{ $st }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
        <label for="payment_status" class="form-label"><b>Payment Status <span class="text-danger">*</span></b></label>
        <select name="payment_status" id="payment_status" class="form-select" required>
            @foreach (['Unpaid', 'Paid'] as $st)
                <option value="{{ $st }}"
                    {{ old('payment_status', $isEdit ? $invoice->payment_status : 'Unpaid') == $st ? 'selected' : '' }}>
                    {{ $st }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 col-md-8 col-sm-12">
        <label for="invoice_pdf" class="form-label"><b>Invoice PDF
                @unless ($isEdit)
                    <span class="text-danger">*</span>
                @endunless
            </b></label>
        <div class="d-flex align-items-center gap-3">
            <input type="file" class="form-control" id="invoice_pdf" name="invoice_pdf" accept="application/pdf"
                {{ $isEdit ? '' : 'required' }}>
            @if ($isEdit && $invoice->invoice_pdf)
                <a href="{{ asset('storage/' . $invoice->invoice_pdf) }}" target="_blank"
                    class="text-decoration-none text-nowrap">
                    <i class="fas fa-file-pdf text-danger fa-lg"></i>
                    {{ basename($invoice->invoice_pdf) }}
                </a>
            @endif
        </div>
        @if ($isEdit)
            <small class="text-muted">New PDF select na korle purano PDF thakbe.</small>
        @endif
    </div>

    <div class="col-12">
        <label for="remarks" class="form-label"><b>Remarks</b></label>
        <textarea name="remarks" id="remarks" rows="3" class="form-control"
            placeholder="Enter remarks (e.g. Hotel/Outlet name, notes, etc.)">{{ old('remarks', $isEdit ? $invoice->remarks : '') }}</textarea>
    </div>

    <div class="col-12 d-flex flex-wrap gap-2">
        <button type="submit" name="save" value="1" class="btn btn-primary px-4">
            <i class="far fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Save' }}
        </button>
        @unless ($isEdit)
            <button type="submit" name="save_new" value="1" class="btn btn-success px-4">
                <i class="fas fa-file-medical me-1"></i> Save &amp; New
            </button>
        @endunless
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary px-4">
            <i class="far fa-times-circle me-1"></i> Cancel
        </a>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });

            function calcVat() {
                var amount = parseFloat($('#amount_before_vat').val()) || 0;
                var percent = parseFloat($('#vat_percent').val()) || 0;
                var vat = Math.round(amount * percent) / 100;
                var total = amount + vat;
                $('#vat_amount').val(vat.toFixed(2));
                $('#total_amount').val(total.toFixed(2));
            }

            $(document).on('keyup change wheel', '#amount_before_vat, #vat_percent', calcVat);
            calcVat();

            // Sudhu PDF allow
            $('#invoice_pdf').on('change', function() {
                var file = this.files[0];
                if (file && file.type !== 'application/pdf') {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-right',
                        text: 'Please select a PDF file only',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $(this).val('');
                }
            });
        });
    </script>
@endpush