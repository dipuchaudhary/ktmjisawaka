@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> पत्र चलानी सूची </h1>
        @can('patrachallani-create')
         <a href="{{ route('patra_challani.create') }}" class="btn btn-primary float-right mb-5">
          नयाँ पत्र चलानी गर्नुहोस्
        </a>
        @endcan
        @can('patrachallani-list')
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>पत्र चलानी भएको कार्यालय</th>
                    <th>चलानी मिति</th>
                    <th>चलानी नं. </th>
                    <th>मुद्दा नं. </th>
                    <th>विषय</th>
                    <th>बोधार्थ</th>
                    <th>दस्तखत गर्ने अधिकारी</th>
                    <th>Status</th>
                    @if(auth()->user()->can('patrachallani-edit') || auth()->user()->can('patrachallani-delete'))
                    <th width="105px">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        @endcan
    </div>
    @endsection
    @push('datatable_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            const hasActions = @json(auth()->user()->can('patrachallani-edit') || auth()->user()->can('patrachallani-delete'));
            let columns = [
                { data: 'id', name: 'id' },
                { data: 'karyalaya_name', name: 'karyalaya_name' },
                { data: 'challani_date', name: 'challani_date' },
                { data: 'challani_number', name: 'challani_number' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'challani_subject', name: 'challani_subject' },
                { data: 'bodartha', name: 'bodartha' },
                { data: 'verified_by', name: 'verified_by' },
                { data: 'status', name: 'status' },
            ];

            if (hasActions) {
            columns.push({ data: 'action', name: 'action', orderable: false, searchable: false });
            }

            $('#muddaTable').DataTable({
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: "",
            columns: columns,
            dom: '<"d-flex justify-content-between align-items-right mb-3"lBf>rtip',
            buttons: [
                { extend: 'excel', className: 'btn btn-success' },
                { extend: 'pdf', className: 'btn btn-danger',charset: 'UTF-8',
                    customize: function(doc) {
                    doc.defaultStyle = {
                    font: 'Devnagari'
                    };
                    }
                },
            ],
            language: {
                zeroRecords: "कुनै डाटा फेला परेन",
                info: "_TOTAL_ मध्य _START_ देखि _END_ प्रविष्टिहरू",
                infoEmpty: "० प्रविष्टिहरू",
                infoFiltered: "(कुल _MAX_ मध्येबाट छानिएको)",
            }
            });
        });
    </script>
    @endpush
