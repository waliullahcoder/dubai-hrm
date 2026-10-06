@extends('layouts.admin.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
            <i class="fas fa-file-invoice"></i>
            <h5 class="mb-0">Edit Invoice</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST"
                enctype="multipart/form-data" id="store_form">
                @csrf
                @method('PUT')
                @include('hrm.invoices._form')
            </form>
        </div>
    </div>
@endsection