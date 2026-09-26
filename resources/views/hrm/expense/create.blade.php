@extends('layouts.admin.app')


@if(auth()->user()->role_status==4)

@section('content')

@include('hrm.dashboard.app_style')

@php
$staff = \App\Models\Staff::where('user_id', auth()->id())->first();

@endphp
<style>
.lblbody{
    padding-bottom:10px;
}
</style>
<div class="container py-4">
    <div class="app-container position-relative">

        <!-- Header -->
        @include('hrm.dashboard.header')

        <div class="checkin-card">


            <!-- Check Out -->


            <div class="advance-title">
                <h3>
                    <i class="fas fa-arrow-right"></i>
                    Transport Expense
                </h3>
            </div>
            <form action="{{ route('admin.expense.store') }}" method="POST">

                @csrf

                <div class="card-body">

                    <div class="row">


                      
                        <input type="hidden" name="employee_id" value="{{$staff->id}}">
                        <input type="hidden" name="status" value="Pending">

                       <div class="col-md-12 mb-12 lblbody">
                        <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                        <select name="expense_head_id" class="form-select select" required>
                            <option value="313">313 - RTA Bus </option>
                            @foreach($coas as $coa)
                            <option value="{{ $coa->id }}">
                                {{ $coa->id }} - {{ $coa->head_name }}
                            </option>
                            @endforeach

                        </select>
                         </div>


                        <div class="col-md-12 mb-12 lblbody">
                            <label class="form-label">Expense Month <span class="text-danger">*</span></label>

                            <select name="expense_month" class="form-select" required>

                                @for($i=1;$i<=12;$i++) <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i,1)) }}
                                    </option>
                                    @endfor

                            </select>

                        </div>
                          
                        <div class="col-md-12 mb-12 lblbody">
                            <label class="form-label">Expense Year</label>
                            <select name="expense_year" class="form-control">
                                @for($i=date('Y')-2;$i<=date('Y')+2;$i++) <option value="{{ $i }}"
                                    {{ request('expense_year', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>

                        <div class="col-md-12 mb-12 lblbody">
                            <label class="form-label">Expense Amount <span class="text-danger">*</span></label>

                            <input type="number" step="0.01" min="0" name="expense_amount" class="form-control"
                                required>

                        </div>

                        <div class="col-md-12 mb-12 lblbody">
                            <label class="form-label">Expense Date <span class="text-danger">*</span></label>

                            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>

                        </div>


                        <div class="col-md-12 mb-12 lblbody">

                            <label class="form-label">Remarks</label>

                            <textarea name="remarks" rows="1" class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save
                    </button>

                </div>

            </form>


        </div><br><br>



        <!-- Bottom Navigation -->
        @include('hrm.dashboard.bottom_navigation')

    </div>
</div>
@endsection

@else
@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-gift text-success"></i>
            Add Expense
        </h5>

        <a href="{{ route('admin.expense.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

    <form action="{{ route('admin.expense.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                    <select name="expense_head_id" class="form-select select" required>
                        <option value="313">313 - RTA Bus </option>
                        @foreach($coas as $coa)
                        <option value="{{ $coa->id }}">
                            {{ $coa->id }} - {{ $coa->head_name }}
                        </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Work Location<span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select select" required>
                        <option value="">Select Location</option>

                        @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">
                            {{ $hotel->name }}, ({{ $hotel->address }})
                        </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Expense Month <span class="text-danger">*</span></label>

                    <select name="expense_month" class="form-select" required>

                        @for($i=1;$i<=12;$i++) <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                            @endfor

                    </select>

                </div>

                <div class="col-md-3 mb-3">
                    <label><b>Expense Year</b></label>
                    <select name="expense_year" class="form-control">
                        @for($i=date('Y')-2;$i<=date('Y')+2;$i++) <option value="{{ $i }}"
                            {{ request('expense_year', date('Y')) == $i ? 'selected' : '' }}>
                            {{ $i }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Expense Amount <span class="text-danger">*</span></label>

                    <input type="number" step="0.01" min="0" name="expense_amount" class="form-control" required>

                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Expense Date <span class="text-danger">*</span></label>

                    <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Paid">Paid</option>

                    </select>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">Remarks</label>

                    <textarea name="remarks" rows="1" class="form-control"></textarea>

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
@endif