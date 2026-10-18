@extends('layouts.admin.app')

@section('content')
@php
    $staffJson = $staffs->map(function ($s) use ($advances) {
        return [
            'id'          => $s->id,
            'name'        => $s->name,
            'code'        => $s->employee_code ?? ('EMP-' . str_pad($s->id, 3, '0', STR_PAD_LEFT)),
            'mobile'      => $s->mobile ?? $s->phone ?? '',
            'department'  => $s->department ?? $s->address ?? '',
            'photo'       => !empty($s->photo) ? asset('storage/' . $s->photo) : '',
            'status'      => $s->status ?? 'Active',
            'outstanding' => (float) ($advances[$s->id] ?? 0),
        ];
    })->values();
@endphp

<style>
    .sp-wrap{--sp-blue:#0b5ed7;--sp-navy:#0f2a5c;--sp-green:#0a8f45;--sp-line:#e3e8f0;--sp-soft:#f4f7fc}
    .sp-wrap .sp-card{background:#fff;border:1px solid var(--sp-line);border-radius:12px;padding:16px;margin-bottom:16px}
    .sp-wrap .sp-title{color:var(--sp-navy);font-weight:700;margin:0}
    .sp-wrap .sp-icon{width:52px;height:52px;border-radius:10px;background:var(--sp-blue);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px}
    .sp-wrap .sp-emp{background:var(--sp-soft);border:1px solid var(--sp-line);border-radius:12px;padding:14px;display:flex;gap:16px;align-items:center}
    .sp-wrap .sp-emp img,.sp-wrap .sp-emp .sp-ph{width:86px;height:86px;border-radius:50%;object-fit:cover;background:#dde5f2;display:flex;align-items:center;justify-content:center;font-size:34px;color:#8a99b5}
    .sp-wrap .sp-emp dl{margin:0;display:grid;grid-template-columns:110px 1fr;row-gap:2px}
    .sp-wrap .sp-emp dt{font-weight:400;color:#333}
    .sp-wrap .sp-emp dd{margin:0}
    .sp-wrap .sp-badge{background:#dcf5e6;color:var(--sp-green);padding:2px 12px;border-radius:6px;font-size:13px}
    .sp-wrap .input-group-text{background:var(--sp-soft);color:var(--sp-navy)}
    .sp-wrap .sp-remain{background:#fdeaea;border-radius:10px;padding:10px 14px;color:#c00}
    .sp-wrap .sp-remain strong{font-size:28px;display:block;line-height:1.1}
    .sp-wrap .sp-sum .row-item{display:flex;justify-content:space-between;padding:8px 10px;border-bottom:1px solid var(--sp-line)}
    .sp-wrap .sp-sum .hl{border:0;border-radius:8px;margin:6px 0;font-weight:700;font-size:18px}
    .sp-wrap .hl-basic{background:#e8f0ff;color:var(--sp-blue)}
    .sp-wrap .hl-gross{background:#dff6e8;color:var(--sp-green)}
    .sp-wrap .hl-net{background:#e8f0ff;color:var(--sp-blue);font-size:22px!important}
    .sp-wrap .sp-results{position:absolute;z-index:50;left:0;right:0;top:100%;background:#fff;border:1px solid var(--sp-line);border-radius:8px;max-height:240px;overflow:auto;box-shadow:0 6px 18px rgba(0,0,0,.1)}
    .sp-wrap .sp-results div{padding:8px 12px;cursor:pointer}
    .sp-wrap .sp-results div:hover{background:var(--sp-soft)}
    .sp-wrap .btn-save{background:var(--sp-green);color:#fff}
    .sp-wrap .btn-preview{background:var(--sp-blue);color:#fff}
</style>

<div class="sp-wrap">
<form id="payForm" action="{{ route('admin.staff-payment.store') }}" method="POST">
    @csrf
    <input type="hidden" name="employee_id" id="employee_id">
    <input type="hidden" name="print" id="printFlag" value="0">

    {{-- Header --}}
    <div class="sp-card d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="sp-icon"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <h4 class="sp-title">Staff Salary Payment</h4>
                <small class="text-muted">Salary Management &rsaquo; Pay Salary</small>
            </div>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <div>
                <label class="form-label mb-1">Payment Date</label>
                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="form-label mb-1">Salary Month</label>
                <select name="payment_month" id="payment_month" class="form-select" required>
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" {{ (int)date('n') === $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label mb-1">Salary Year</label>
                <select name="payment_year" id="payment_year" class="form-select" required>
                    @for($i=date('Y')-2;$i<=date('Y')+2;$i++)
                        <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="sp-card">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label mb-1">Search by Employee Name <span class="text-danger">*</span></label>
                <div class="input-group position-relative">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" id="searchName" class="form-control" placeholder="Enter employee name..." autocomplete="off">
                    <button type="button" class="btn btn-primary" id="btnName"><i class="fas fa-search"></i></button>
                    <div class="sp-results d-none" id="resName"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-label mb-1">Search by Mobile Number</label>
                <div class="input-group position-relative">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" id="searchMobile" class="form-control" placeholder="Enter mobile number..." autocomplete="off">
                    <button type="button" class="btn btn-primary" id="btnMobile"><i class="fas fa-search"></i></button>
                    <div class="sp-results d-none" id="resMobile"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <a href="{{ route('admin.employee.create') }}" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Add New Employee</a>
            </div>
        </div>
    </div>

    {{-- Employee card --}}
    <div class="sp-emp mb-3">
        <div class="sp-ph" id="empPhoto"><i class="fas fa-user"></i></div>
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h5 class="sp-title" id="empName">No employee selected</h5>
                <span class="sp-badge d-none" id="empStatus">Active</span>
            </div>
            <dl>
                <dt>Emp. ID</dt><dd>: <span id="empCode">-</span></dd>
                <dt>Mobile</dt><dd>: <span id="empMobile">-</span></dd>
                <dt>Department</dt><dd>: <span id="empDept">-</span></dd>
            </dl>
        </div>
    </div>

    <div class="row g-3">
        {{-- Left --}}
        <div class="col-lg-7">
            <div class="sp-card">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Total Working Hours <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                            <input type="number" step="0.01" min="0" name="hours" id="hours" class="form-control calc" required>
                            <span class="input-group-text">hrs</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Hourly Rate (AED) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                            <input type="number" step="0.01" min="0" name="hour_rate" id="hour_rate" class="form-control calc" required>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Transport (AED)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bus"></i></span>
                            <input type="number" step="0.01" min="0" name="transport_amount" id="transport" class="form-control calc" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="sp-card">
                <h6 class="sp-title mb-3"><i class="fas fa-coins text-warning"></i> Advance Deduction</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Previous Outstanding (AED)</label>
                        <input type="text" id="outstanding" class="form-control" value="0.00" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Deduction Amount (AED)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                            <input type="number" step="0.01" min="0" name="advance_deduction" id="deduction" class="form-control calc" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sp-remain">
                            <small>Remaining Advance (AED)</small>
                            <strong id="remaining">0.00</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sp-card">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Payment Head <span class="text-danger">*</span></label>
                        <select name="payment_head_id" class="form-select" required>
                            <option value="313">313 - Transport Payment</option>
                            @foreach($coas as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->id }} - {{ $coa->head_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Remarks</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-file-alt"></i></span>
                            <textarea name="remarks" id="remarks" rows="1" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: summary --}}
        <div class="col-lg-5">
            <div class="sp-card sp-sum">
                <div class="row-item"><span>Total Working Hours</span><span><span id="sHours">0.00</span> hrs</span></div>
                <div class="row-item"><span>Hourly Rate (AED)</span><span id="sRate">0.00</span></div>
                <div class="row-item hl hl-basic"><span>Basic Salary (AED)</span><span id="sBasic">0.00</span></div>
                <div class="row-item"><span>Transport (AED)</span><span id="sTransport">0.00</span></div>
                <div class="row-item hl hl-gross"><span>Gross Salary (AED)</span><span id="sGross">0.00</span></div>
                <div class="row-item"><span>Advance Deduction (AED)</span><span id="sDeduct">0.00</span></div>
                <div class="row-item hl hl-net"><span><i class="fas fa-wallet"></i> Net Payable Amount (AED)</span><span id="sNet">0.00</span></div>
            </div>
            <input type="hidden" name="payment_amount" id="payment_amount" value="0">
            <input type="hidden" name="status" value="Payment">
        </div>
    </div>

    {{-- Actions --}}
    <div class="sp-card d-flex flex-wrap justify-content-end gap-2">
        <button type="button" class="btn btn-light border" id="btnClear"><i class="fas fa-trash"></i> Clear</button>
        <button type="button" class="btn btn-preview" id="btnPreview"><i class="fas fa-eye"></i> Preview Slip</button>
        <button type="submit" class="btn btn-save" id="btnSave"><i class="fas fa-save"></i> Save Payment</button>
        <button type="button" class="btn btn-save" id="btnSavePrint"><i class="fas fa-print"></i> Save &amp; Print</button>
    </div>
</form>
</div>

@endsection

@push('js')
<script>
(function () {
    const staffs = @json($staffJson);
    const $ = id => document.getElementById(id);
    const num = v => parseFloat(v) || 0;
    const fmt = v => v.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    let current = null;

    function selectStaff(s) {
        current = s;
        $('employee_id').value = s.id;
        $('empName').textContent = s.name;
        $('empCode').textContent = s.code;
        $('empMobile').textContent = s.mobile || '-';
        $('empDept').textContent = s.department || '-';
        $('empStatus').textContent = s.status;
        $('empStatus').classList.remove('d-none');
        $('empPhoto').outerHTML = s.photo
            ? '<img id="empPhoto" src="' + s.photo + '" alt="">'
            : '<div class="sp-ph" id="empPhoto"><i class="fas fa-user"></i></div>';
        $('outstanding').value = fmt(s.outstanding);
        $('searchName').value = s.name;
        $('searchMobile').value = s.mobile;
        $('resName').classList.add('d-none');
        $('resMobile').classList.add('d-none');
        calc();
    }

    function search(inputId, resId, field) {
        const q = $(inputId).value.trim().toLowerCase();
        const box = $(resId);
        const list = staffs.filter(s => !q || String(s[field] || '').toLowerCase().includes(q)).slice(0, 15);
        box.innerHTML = '';
        if (!list.length) { box.innerHTML = '<div class="text-muted">No employee found</div>'; }
        list.forEach(s => {
            const d = document.createElement('div');
            d.textContent = s.name + (s.mobile ? ' - ' + s.mobile : '');
            d.onclick = () => selectStaff(s);
            box.appendChild(d);
        });
        box.classList.remove('d-none');
    }

    function calc() {
        const hours = num($('hours').value), rate = num($('hour_rate').value);
        const transport = num($('transport').value);
        const outstanding = current ? current.outstanding : 0;
        const basic = hours * rate, gross = basic + transport;
        let ded = num($('deduction').value);
        if (ded > outstanding) { ded = outstanding; $('deduction').value = ded.toFixed(2); }
        if (ded > gross) { ded = gross; $('deduction').value = ded.toFixed(2); }
        const net = gross - ded;

        $('sHours').textContent = fmt(hours);
        $('sRate').textContent = fmt(rate);
        $('sBasic').textContent = fmt(basic);
        $('sTransport').textContent = fmt(transport);
        $('sGross').textContent = fmt(gross);
        $('sDeduct').textContent = fmt(ded);
        $('sNet').textContent = fmt(net);
        $('remaining').textContent = fmt(outstanding - ded);
        $('payment_amount').value = net.toFixed(2);
    }

    document.querySelectorAll('.calc').forEach(el => el.addEventListener('input', calc));
    $('searchName').addEventListener('input', () => search('searchName', 'resName', 'name'));
    $('searchMobile').addEventListener('input', () => search('searchMobile', 'resMobile', 'mobile'));
    $('btnName').onclick = () => search('searchName', 'resName', 'name');
    $('btnMobile').onclick = () => search('searchMobile', 'resMobile', 'mobile');
    document.addEventListener('click', e => {
        if (!e.target.closest('.position-relative')) {
            $('resName').classList.add('d-none'); $('resMobile').classList.add('d-none');
        }
    });

    $('btnClear').onclick = () => {
        $('payForm').reset(); current = null; $('employee_id').value = '';
        $('empName').textContent = 'No employee selected';
        ['empCode', 'empMobile', 'empDept'].forEach(i => $(i).textContent = '-');
        $('empStatus').classList.add('d-none');
        $('outstanding').value = '0.00';
        calc();
    };

    function validate() {
        if (!$('employee_id').value) { alert('Please search and select an employee first.'); return false; }
        return $('payForm').reportValidity();
    }

    $('payForm').addEventListener('submit', e => { if (!validate()) e.preventDefault(); });
    $('btnSavePrint').onclick = () => {
        if (!validate()) return;
        $('printFlag').value = '1';
        $('payForm').submit();
    };

    $('btnPreview').onclick = () => {
        if (!current) { alert('Please select an employee first.'); return; }
        const month = $('payment_month').selectedOptions[0].text + ' ' + $('payment_year').value;
        const row = (a, b) => '<tr><td>' + a + '</td><td style="text-align:right">' + b + '</td></tr>';
        const w = window.open('', '_blank', 'width=700,height=800');
        w.document.write('<html><head><title>Salary Slip</title><style>body{font-family:Arial;padding:30px}table{width:100%;border-collapse:collapse}td{padding:8px;border-bottom:1px solid #ddd}h2{margin:0 0 4px}.t td{font-weight:bold;font-size:18px}</style></head><body>'
            + '<h2>Salary Slip - ' + month + '</h2>'
            + '<p>' + current.name + ' (' + current.code + ')<br>' + (current.department || '') + '</p><table>'
            + row('Working Hours', $('sHours').textContent + ' hrs')
            + row('Hourly Rate (AED)', $('sRate').textContent)
            + row('Basic Salary (AED)', $('sBasic').textContent)
            + row('Transport (AED)', $('sTransport').textContent)
            + row('Gross Salary (AED)', $('sGross').textContent)
            + row('Advance Deduction (AED)', $('sDeduct').textContent)
            + '<tr class="t"><td>Net Payable (AED)</td><td style="text-align:right">' + $('sNet').textContent + '</td></tr>'
            + '</table><p>' + ($('remarks').value || '') + '</p></body></html>');
        w.document.close();
    };

    calc();
})();
</script>
@endpush