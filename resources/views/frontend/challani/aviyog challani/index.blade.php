@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> अभियोग चलानी सूची </h1>
         <a href="{{ route('patra_challani.create') }}" class="btn btn-primary float-right mb-5">
          नयाँ पत्र चलानी गर्नुहोस्
        </a>
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
                    <th>जम्मा प्रतिवादी</th>
                    <th>मुद्दा दर्ता मिति</th>
                    <th>सरकारी वकील</th>
                    <th>फाँट</th>
                    <th>अनुसन्धान गर्ने निकाय</th>
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
            processing: true,
            serverSide: true,
            ajax: "",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'karyalaya_name', name: 'karyalaya_name' },
                { data: 'challani_date', name: 'challani_date' },
                { data: 'challani_number', name: 'challani_number' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'challani_subject', name: 'challani_subject' },
                { data: 'bodartha', name: 'bodartha' },
                { data: 'verified_by', name: 'verified_by' },
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
