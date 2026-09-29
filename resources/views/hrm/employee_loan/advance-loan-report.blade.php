


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
                    <h4 class="fw-bold mb-1">Advance Loan Report </h4>
                    <p class="text-muted mb-0">
                        Create Advance Loan for your staff based on taken loan
                    </p>
                </div>

            </div>
        </div>
    </div>


    {{-- Filters --}}
    <form method="GET" action="{{route('admin.advance.loan.report')}}" class="card border-0 shadow-sm mb-4 formsec">
        @csrf

        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fa-solid fa-filter text-primary me-2"></i>
                Advance Loan Filter
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Staff --}}
                <div class="col-md-6">
                    <select name="employee_id" class="form-select select" required>
                        @foreach($staffs ?? [] as $staff)

                            <option value="{{ $staff->id }}">
                                {{ $staff->name }},  {{ $staff->phone }}
                            </option>

                        @endforeach

                    </select>
                </div>



            <div class="col-md-6">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    View Advance Report
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
