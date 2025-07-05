@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1>पुनरावेदन सूची </h1>
        @can('punarabedan-list')
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                <th rowspan="2">ID</th>
                <th rowspan="2" >मुद्दा नं.</th>
                <th rowspan="2" >जाहेरवालाको नाम</th>
                <th rowspan="2" >प्रतिवादीको नाम</th>
                <th rowspan="2" >मुद्दाको किसिम</th>
                <th rowspan="2" >फैसला मिति</th>
                <th rowspan="2" >फैसला प्रमाणीकरण मिति</th>
                <th colspan="3" class="text-center">कार्यालयबाट भएको पुनरावेदन सम्बन्धी कारवाही</th>
                <th rowspan="2">Status</th>
                @if(auth()->user()->can('punarabedan-edit') || auth()->user()->can('punarabedan-delete'))
                    <th rowspan="2" width="105px">Action</th>
                @endif
            </tr>
            <tr>
                <th>पुवे/दो.पा</th>
                <th>चलानी मिति</th>
                <th>चलानी नं.</th>
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
            const hasActions = @json(auth()->user()->can('punarabedan-edit') || auth()->user()->can('punarabedan-delete'));
            let columns = [
                { data: 'id', name: 'id' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'jaherwala_name', name: 'jaherwala_name' },
                { data: 'pratiwadi_name', name: 'pratiwadi_name' },
                { data: 'mudda_name', name: 'mudda_name' },
                { data: 'faisala_date', name: 'faisala_date' },
                { data: 'faisala_pramanikaran_date', name: 'faisala_pramanikaran_date' },
                { data: 'punarabedan', name: 'punarabedan' },
                { data: 'punarabedan_date', name: 'punarabedan_date' },
                { data: 'punarabedan_challani_number', name: 'punarabedan_challani_number' },
                { data: 'status', name: 'status' },

            ];

            if (hasActions) {
            columns.push({ data: 'action', name: 'action', orderable: false, searchable: false });
            }

            $('#muddaTable').DataTable({
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('punarabedan.index') }}",
            },
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
                { extend: 'print', className: 'btn btn-info' },
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
