@extends('layouts.admin.app')

@section('content')

<div class="card">


<div class="card-header d-flex justify-content-between align-items-center">

    <h5 class="mb-0">
        <i class="fas fa-hotel text-success"></i>
        Hotel Management
    </h5>

   
    <a href="{{ route('admin.hotel.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Hotel
    </a>

</div>

<div class="card-body">

    <table class="table table-bordered table-striped table-hover dataTable w-100">

        <thead>
            <tr>
                <th width="50">SL</th>
                <th>Name</th>
                <!-- <th>Phone</th> -->
                <th>Address</th>
                <th>Entry Date</th>
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

    dom: 'Bfrtip',

    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-success btn-sm',
            title: 'Hotel List',

            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            }
        }
    ],

    ajax: "{{ route('admin.hotel.index') }}",

    columns: [

        // SL
        {
            data: null,
            searchable: false,
            orderable: false,

            render: function(data, type, row, meta) {

                return meta.row +
                       meta.settings._iDisplayStart +
                       1;

            }
        },

        // Name
        {
            data: 'name',
            name: 'name'
        },

       

        // Address
        {
            data: 'address',
            name: 'address'
        },

        // Entry Date
        {
            data: 'entry_date',
            name: 'entry_date'
        },

        // Status
        {
            data: 'status',
            name: 'status',
            searchable: false
        },

        // Remarks
        {
            data: 'remarks',
            name: 'remarks'
        },

        // Action
        {
            data: 'actions',
            name: 'actions',
            orderable: false,
            searchable: false
        }

    ]

});

</script>

@endpush
