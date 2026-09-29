


@extends('layouts.admin.app')
@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">

                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="fa-regular fa-calendar-days fs-4"></i>
                </div>

                <div>
                    <h4 class="fw-bold mb-1">Salary Certificate </h4>
                    <p class="text-muted mb-0">
                        Create Certificate for your staff based on attendance and hourly rate
                    </p>
                </div>

            </div>
        </div>
    </div>


    {{-- Filters --}}
    <form method="GET" action="{{route('admin.certificate.report')}}" class="card border-0 shadow-sm mb-4 formsec">
        @csrf

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fa-solid fa-filter text-primary me-2"></i>
                Certificate Filter
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Month --}}
                <div class="col-md-3">
                    <select name="payslip_month" class="form-select">
                        @foreach([
                            'January','February','March','April',
                            'May','June','July','August',
                            'September','October','November','December'
                        ] as $month)

                            <option value="{{ $month }}"
                                {{ (old('payroll_month', $selectedMonth ?? 'September') == $month) ? 'selected' : '' }}>
                                {{ $month }}
                            </option>

                        @endforeach
                    </select>
                </div>


                {{-- Year --}}
                <div class="col-md-3">
                    <select name="payslip_year" class="form-select">

                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)

                            <option value="{{ $y }}"
                                {{ (old('payroll_year', $selectedYear ?? date('Y')) == $y) ? 'selected' : '' }}>
                                {{ $y }}
                            </option>

                        @endfor

                    </select>
                </div>


                {{-- Staff --}}
                <div class="col-md-3">
                    <select name="employee_id" class="form-select select" required>
                        @foreach($staffs ?? [] as $staff)

                            <option value="{{ $staff->id }}">
                                {{ $staff->name }}
                            </option>

                        @endforeach

                    </select>
                </div>



            <div class="col-md-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    View Certificate
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
