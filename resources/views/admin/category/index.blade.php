@extends('layouts.admin.index_app')

@section('content')

    @php
        $currentRouteName = \Request::route()->getName();
        $link = Route($currentRouteName);
        $delete_link = str_replace('index', 'destroy', $currentRouteName);
        $isSoftwareAdmin = Auth::user()->hasRole('Software Admin');
    @endphp

    <div class="card-body">

        <table class="dataTable table align-middle" style="width:100%">

            <thead>
                <tr class="text-nowrap">

                    <th width="3"></th>

                    @if ($isSoftwareAdmin)
                        <th>Hotel Name</th>
                    @endif

                    <th>Department Name</th>

                    <th>Status</th>

                    <th width="110" class="text-end">
                        Actions
                    </th>

                </tr>
            </thead>

            <tbody>
            </tbody>

           

        </table>

    </div>

@endsection


@push('js')

<script type="text/javascript">

$(document).ready(function () {

    var table = $('.dataTable').DataTable({

        processing: true,

        serverSide: true,

        scrollX: true,

        ajax: {
            url: "{{ $link }}",
            type: "GET",

            data: function (data) {

                data.type = $('#filter').val();

            }
        },

        columns: [

            {
                data: "checkbox",
                name: "checkbox",
                orderable: false,
                searchable: false,
                width: "3%"
            },

           

            {
                data: "hotel_name",
                name: "hotel_name",
                defaultContent: "-"
            },


            {
                data: "name",
                name: "name"
            },

            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false
            },

            {
                data: "actions",
                name: "actions",
                orderable: false,
                searchable: false,
                className: "text-end"
            }

        ],

        drawCallback: function () {

            const tooltips = document.querySelectorAll('.tt');

            tooltips.forEach(function (t) {

                new bootstrap.Tooltip(t);

            });

        }

    });

});

</script>

@endpush