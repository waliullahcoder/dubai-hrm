@extends('layouts.admin.app')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-gift text-success"></i>
            Add Hotel
        </h5>

        <a href="{{ route('admin.hotel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>

    </div>

    <form action="{{ route('admin.hotel.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="row">
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hotel Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" required>
                </div>

               

                

                <div class="col-md-6 mb-3">

                    <label class="form-label">Remarks</label>

                    <textarea name="remarks" rows="1" class="form-control"></textarea>

                </div>
                 <div class="col-md-3 mb-3">
                    <label class="form-label">Entry Date <span class="text-danger">*</span></label>

                    <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required>

                </div>
                <div class="col-md-3 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

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