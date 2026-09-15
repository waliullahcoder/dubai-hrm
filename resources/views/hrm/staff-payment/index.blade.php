@extends('layouts.admin.app')
@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-gift text-success"></i>
         Payment Management
        </h5>

        @can('admin.staff-payment.create')
        <a href="{{ route('admin.staff-payment.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Payment
        </a>
        @endcan

    </div>

    <div class="card-body">

         <table class="table table-bordered table-striped table-hover dataTable w-100">

            <thead>

                <tr>

                    <th width="50">SL</th>
                    <th>Staff</th>
                    <th>Payment Head</th>
                    <th>Payment Month</th>
                    <th>Year</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th width="120">Action</th>

                </tr>

            </thead>

        </table>

    </div>

</div>

@endsection


@push('js')


<script>
$('.dataTable').DataTable({

    processing: true,
    serverSide: true,
    // Excel button
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                title: 'Loan List',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            }
        ],

    ajax: "{{ route('admin.staff-payment.index') }}",

       columns: [
    {
        data: null,
        searchable: false,
        orderable: false,
        render: function(data, type, row, meta){
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    },
    {data:'staff_name',name:'stf.name'},
    {data:'head_name',name:'c.head_name'},
    {data:'payment_month',name:'pmnt.payment_month'},
    {data:'payment_year',name:'pmnt.payment_year'},
    {data:'payment_amount',name:'pmnt.payment_amount'},
    {data:'payment_date',name:'pmnt.payment_date'},
    {data:'status',name:'pmnt.status',searchable:false},
    {data:'remarks',name:'pmnt.remarks'},
    {data:'actions',orderable:false,searchable:false}
    ]

    });


</script>

@endpush