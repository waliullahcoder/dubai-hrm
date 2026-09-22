@extends('layouts.admin.app')

@section('content')

<div class="card">


<div class="card-header d-flex justify-content-between align-items-center">

    <h5 class="mb-0">
        <i class="fas fa-edit text-primary"></i>
        Edit Hotel
    </h5>

    <a href="{{ route('admin.hotel.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</div>

<form action="{{ route('admin.hotel.update', $hotel->id) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="card-body">

        <div class="row">

            {{-- Name --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $hotel->name) }}"
                    required
                >

            </div>

           
            {{-- Address --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Address <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="address"
                    class="form-control"
                    value="{{ old('address', $hotel->address) }}"
                    required
                >

            </div>

            

            {{-- Remarks --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="1"
                    class="form-control"
                >{{ old('remarks', $hotel->remarks) }}</textarea>

            </div>

            {{-- Entry Date --}}
            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Entry Date <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="entry_date"
                    class="form-control"
                    value="{{ old('entry_date', $hotel->entry_date ? \Carbon\Carbon::parse($hotel->entry_date)->format('Y-m-d') : '') }}"
                    required
                >

            </div>

            {{-- Status --}}
            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Status
                </label>

                <select name="status" class="form-select">

                    <option value="Active"
                        {{ old('status', $hotel->status) == 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive"
                        {{ old('status', $hotel->status) == 'Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>

        </div>

    </div>

    <div class="card-footer text-end">

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update
        </button>

    </div>

</form>


</div>

@endsection
