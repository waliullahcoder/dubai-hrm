@extends('layouts.admin.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-edit text-warning"></i>
            Edit Payment
        </h5>

        <a href="{{ route('admin.staff-payment.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

    <form action="{{ route('admin.staff-payment.update',$payment->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        Payment Type <span class="text-danger">*</span>
                    </label>

                    <select name="payment_head_id" class="form-select select" required>

                        <option value="">Select Payment Type</option>

                        @foreach($coas as $coa)

                            <option value="{{ $coa->id }}"
                                {{ $payment->payment_head_id == $coa->id ? 'selected' : '' }}>

                                {{ $coa->id }} - {{ $coa->head_name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Staff Name<span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select select" required>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}"
                             {{ $payment->employee_id == $staff->id ? 'selected' : '' }}>
                               {{ $staff->name }} ({{ $staff->address }})
                            </option>
                        @endforeach

                    </select>
                </div>

                

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Payment Month <span class="text-danger">*</span>
                    </label>

                    <select name="payment_month" class="form-select" required>

                        @for($i=1;$i<=12;$i++)

                            <option value="{{ $i }}"
                                {{ $payment->payment_month == $i ? 'selected' : '' }}>

                                {{ date('F', mktime(0,0,0,$i,1)) }}

                            </option>

                        @endfor

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label><b>Payment Year</b></label>

                    <select name="payment_year" class="form-control">

                        @for($i=date('Y')-2;$i<=date('Y')+2;$i++)

                            <option value="{{ $i }}"
                                {{ $payment->payment_year == $i ? 'selected' : '' }}>

                                {{ $i }}

                            </option>

                        @endfor

                    </select>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Payment Amount <span class="text-danger">*</span>
                    </label>

                    <input type="number"
                           name="payment_amount"
                           class="form-control"
                           min="0"
                           step="0.01"
                           value="{{ $payment->payment_amount }}"
                           required>

                </div>
                

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Payment Date <span class="text-danger">*</span>
                    </label>

                    <input type="date"
                           name="payment_date"
                           class="form-control"
                           value="{{ $payment->payment_date }}"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="Payment"
                            {{ $payment->status=='Payment'?'selected':'' }}>
                            Payment
                        </option>

                        <option value="Advance"
                            {{ $payment->status=='Advance'?'selected':'' }}>
                            Advance
                        </option>

                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">Remarks</label>

                    <textarea name="remarks"
                              rows="1"
                              class="form-control">{{ $payment->remarks }}</textarea>

                </div>

            </div>

        </div>

        <div class="card-footer text-end">

            <button type="submit" class="btn btn-warning">

                <i class="fas fa-save"></i> Update

            </button>

        </div>

    </form>

</div>

@endsection