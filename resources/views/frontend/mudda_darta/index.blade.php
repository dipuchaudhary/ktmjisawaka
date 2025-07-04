@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> Mudda list </h1>
        @can('mulldarta-create')
        <a href="{{ route('mudda_darta.create') }}" class="btn btn-primary float-right mb-5">
          नयाँ मुल मुद्दा दर्ता सिर्जना गर्नुहोस्
        </a>
        @endcan
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>अनुसन्धान गर्ने निकाय</th>
                    <th>मुद्दा नं.</th>
                    <th>मुद्दाको किसिम </th>
                    <th>जाहेरवालाको नाम</th>
                    <th>प्रतिवादीको नाम</th>
                    <th>मुद्दाको स्थिति</th>
                    <th>मुद्दा दर्ता मिति</th>
                    <th>सरकारी वकील</th>
                    <th>फाँट</th>
                    @if(auth()->user()->can('mulldarta-edit') || auth()->user()->can('mulldarta-delete'))
                    <th width="105px">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    @endsection
    @push('datatable_scripts')
    <script type="text/javascript">
        const hasActions = @json(auth()->user()->can('mulldarta-edit') || auth()->user()->can('mulldarta-delete'));
        let columns = [
            { data: 'id', name: 'id' },
            { data: 'anusandhan_garne_nikaye', name: 'anusandhan_garne_nikaye' },
            { data: 'mudda_number', name: 'mudda_number' },
            { data: 'mudda_name', name: 'mudda_name' },
            { data: 'jaherwala_name', name: 'jaherwala_name' },
            { data: 'pratiwadi_name', name: 'pratiwadi_name' },
            { data: 'mudda_stithi', name: 'mudda_stithi' },
            { data: 'mudda_date', name: 'mudda_date' },
            { data: 'sarkariwakil_name', name: 'sarkariwakil_name' },
            { data: 'faat_name', name: 'faat_name' }
        ];

        if (hasActions) {
            columns.push({ data: 'action', name: 'action', orderable: false, searchable: false });
        }

        $(document).ready(function () {
            $('#muddaTable').DataTable({
                "order": [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: "{{ route('mudda_darta.index') }}",
                columns: columns,
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
