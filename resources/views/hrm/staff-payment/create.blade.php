@extends('layouts.admin.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-gift text-success"></i>
            Add Payment
        </h5>

        <a href="{{ route('admin.staff-payment.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

    <form action="{{ route('admin.staff-payment.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                    <select name="payment_head_id" class="form-select select" required>
                        <option value="313">313 - Transport Payment </option>           
                        @foreach($coas as $coa)
                            <option value="{{ $coa->id }}">
                                {{ $coa->id }} - {{ $coa->head_name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Staff Name<span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select select" required>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}">
                               {{ $staff->name }} ({{ $staff->address }})
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Month <span class="text-danger">*</span></label>

                    <select name="payment_month" class="form-select" required>

                        @for($i=1;$i<=12;$i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                        @endfor

                    </select>

                </div>

                <div class="col-md-3 mb-3">
                        <label><b>Payment Year</b></label>
                         <select name="payment_year" class="form-control">
                                    @for($i=date('Y')-2;$i<=date('Y')+2;$i++)
                                        <option value="{{ $i }}"
                                            {{ request('payment_year', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Amount <span class="text-danger">*</span></label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="payment_amount"
                           class="form-control"
                           required>

                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>

                    <input type="date"
                           name="payment_date"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="Payment">Payment</option>
                        <option value="Advance">Advance</option>

                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">Remarks</label>

                    <textarea name="remarks"
                              rows="1"
                              class="form-control"></textarea>

                </div>

            </div>

        </div>

        <div class="card-footer text-end">

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save 
            </button>

        </div>

    </form>

</div>

@endsection
