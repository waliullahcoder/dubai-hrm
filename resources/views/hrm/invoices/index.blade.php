@extends('layouts.admin.app')

@section('content')
    {{-- Summary Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#e8f1ff;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-primary text-white"
                        style="width:48px;height:48px;"><i class="fas fa-file-invoice fa-lg"></i></span>
                    <div>
                        <small class="text-primary fw-semibold">Total Invoices</small>
                        <h3 class="mb-0 fw-bold">{{ $summary->total_invoices }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#e6f7ee;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-success text-white"
                        style="width:48px;height:48px;"><i class="fas fa-dollar-sign fa-lg"></i></span>
                    <div>
                        <small class="text-muted">Total Amount<br>(Before VAT)</small>
                        <h6 class="mb-0 fw-bold">AED {{ number_format($summary->total_before_vat, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#f1e9ff;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded text-white"
                        style="width:48px;height:48px;background:#8b5cf6;"><i class="fas fa-percent fa-lg"></i></span>
                    <div>
                        <small class="text-muted">Total VAT (5%)</small>
                        <h6 class="mb-0 fw-bold">AED {{ number_format($summary->total_vat, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#fff3e0;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-warning text-white"
                        style="width:48px;height:48px;"><i class="fas fa-coins fa-lg"></i></span>
                    <div>
                        <small class="text-muted">Total Amount<br>(With VAT)</small>
                        <h6 class="mb-0 fw-bold">AED {{ number_format($summary->total_with_vat, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#e6f7ee;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-success text-white"
                        style="width:48px;height:48px;"><i class="fas fa-check fa-lg"></i></span>
                    <div>
                        <small class="text-muted">Paid Amount</small>
                        <h6 class="mb-0 fw-bold">AED {{ number_format($summary->paid_amount, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="background:#ffe9e9;">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-danger text-white"
                        style="width:48px;height:48px;"><i class="fas fa-hourglass-half fa-lg"></i></span>
                    <div>
                        <small class="text-muted">Unpaid Amount</small>
                        <h6 class="mb-0 fw-bold">AED {{ number_format($summary->unpaid_amount, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoice List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded bg-primary text-white"
                    style="width:36px;height:36px;"><i class="fas fa-file-invoice"></i></span>
                <h5 class="mb-0 fw-bold">Invoice List</h5>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @can('admin.invoices.create')
                    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Invoice
                    </a>
                @endcan
                <a href="#" id="export_btn" class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i> Export
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                    data-bs-target="#filter_area">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </div>

        {{-- Filter --}}
        <div class="collapse border-bottom" id="filter_area">
            <div class="row g-2 p-3">
                <div class="col-md-3">
                    <label class="form-label mb-1"><b>From Date</b></label>
                    <input type="text" id="from_date" class="form-control date_picker" placeholder="dd-mm-yyyy"
                        autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1"><b>To Date</b></label>
                    <input type="text" id="to_date" class="form-control date_picker" placeholder="dd-mm-yyyy"
                        autocomplete="off">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1"><b>Active Status</b></label>
                    <select id="f_active_status" class="form-select">
                        <option value="">All</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1"><b>Payment Status</b></label>
                    <select id="f_payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="button" id="apply_filter" class="btn btn-primary w-50">Apply</button>
                    <button type="button" id="reset_filter" class="btn btn-secondary w-50">Reset</button>
                </div>
            </div>
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center w-100" id="invoice_table">
                    <thead class="bg-light text-nowrap">
                        <tr>
                            <th width="30">#</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Amount<br>Before VAT (AED)</th>
                            <th>VAT (5%)</th>
                            <th>Total Amount<br>(AED)</th>
                            <th>LPO Number</th>
                            <th>Active Status</th>
                            <th>Payment Status</th>
                            <th>Invoice PDF</th>
                            <th>Remarks</th>
                            <th width="130">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });

            var table = $('#invoice_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.invoices.index') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.active_status = $('#f_active_status').val();
                        d.payment_status = $('#f_payment_status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_no',
                        name: 'i.invoice_no'
                    },
                    {
                        data: 'invoice_date',
                        name: 'i.invoice_date'
                    },
                    {
                        data: 'amount_before_vat',
                        name: 'i.amount_before_vat',
                        className: 'text-end'
                    },
                    {
                        data: 'vat_amount',
                        name: 'i.vat_amount',
                        className: 'text-end'
                    },
                    {
                        data: 'total_amount',
                        name: 'i.total_amount',
                        className: 'text-end'
                    },
                    {
                        data: 'lpo_number',
                        name: 'i.lpo_number'
                    },
                    {
                        data: 'active_status',
                        name: 'i.active_status'
                    },
                    {
                        data: 'payment_status',
                        name: 'i.payment_status'
                    },
                    {
                        data: 'invoice_pdf',
                        name: 'i.invoice_pdf',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'remarks',
                        name: 'i.remarks'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'desc']
                ],
            });

            $('#apply_filter').on('click', function() {
                table.ajax.reload();
            });

            $('#reset_filter').on('click', function() {
                $('#from_date, #to_date').val('');
                $('#f_active_status, #f_payment_status').val('');
                table.ajax.reload();
            });

            // Export (filter soho)
            $('#export_btn').on('click', function(e) {
                e.preventDefault();
                var params = $.param({
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                    active_status: $('#f_active_status').val(),
                    payment_status: $('#f_payment_status').val(),
                });
                window.location.href = "{{ route('admin.invoices.export') }}?" + params;
            });

            // Delete
            $(document).on('click', '.link-delete', function() {
                var url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This invoice will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    toast: true,
                                    icon: 'success',
                                    position: 'top-right',
                                    text: 'Invoice deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush