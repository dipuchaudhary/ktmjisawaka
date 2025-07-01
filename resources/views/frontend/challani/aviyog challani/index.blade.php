@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> अभियोग चलानी सूची </h1>
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>चलानी मिति</th>
                    <th>चलानी नं. </th>
                    <th>मुद्दा नं.</th>
                    <th>मुद्दाको किसिम </th>
                    <th>जाहेरवालाको नाम</th>
                    <th>प्रतिवादीको नाम</th>
                    <th>सरकारी वकील</th>
                    <th>फाँट</th>
                    <th>अनुसन्धान गर्ने निकाय</th>
                    <th>Status</th>
                    <th width="105px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    @endsection
    @push('datatable_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#muddaTable').DataTable({
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('aviyog_challani.index') }}",
                type: 'GET',
                dataSrc: function (json) {
                    console.log('Data from server:', json);
                    return json.data;
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'challani_date', name: 'challani_date' },
                { data: 'challani_number', name: 'challani_number' },
                { data: 'jaherwala_name', name: 'jaherwala_name' },
                { data: 'pratiwadi_name', name: 'pratiwadi_name' },
                { data: 'mudda_name', name: 'mudda_name' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'sarkariwakil_name', name: 'sarkariwakil_name' },
                { data: 'faat_name', name: 'faat_name' },
                { data: 'anusandhan_garne_nikaye', name: 'anusandhan_garne_nikaye' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            dom: '<"d-flex justify-content-between align-items-right mb-3"lBf>rtip',
            buttons: [
                { extend: 'excel', className: 'btn btn-success' },
                { extend: 'pdf', className: 'btn btn-danger' },
                { extend: 'print', className: 'btn btn-info' }
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
