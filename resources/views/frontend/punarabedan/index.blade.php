@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h1>पुनरावेदन सूची</h1>
    <div class="alert alert-danger d-none" id="dataTableError">
        तालिका डाटा लोड गर्न असफल भयो। कृपया पृष्ठ रिफ्रेस गर्नुहोस् वा पछि प्रयास गर्नुहोस्।
    </div>
    <table id="muddaTable" class="table table-bordered data-table">
        <thead>
            <tr>
                <th rowspan="2">ID</th>
                <th rowspan="2">मुद्दा नं.</th>
                <th rowspan="2">जाहेरवालाको नाम</th>
                <th rowspan="2">प्रतिवादीको नाम</th>
                <th rowspan="2">मुद्दाको किसिम</th>
                <th rowspan="2">फैसला मिति</th>
                <th rowspan="2">फैसला प्रमाणीकरण मिति</th>
                <th colspan="3" class="text-center">कार्यालयबाट भएको पुनरावेदन सम्बन्धी कारवाही</th>
                <th rowspan="2">प्रयोगकर्ता</th>
                <th rowspan="2">Status</th>
                @auth
                    @if(auth()->user()->can('punarabedan-edit') || auth()->user()->can('punarabedan-delete'))
                        <th rowspan="2" width="105px">Action</th>
                    @endif
                @endauth
            </tr>
            <tr>
                <th>पुवे/दो.पा</th>
                <th>चलानी मिति</th>
                <th>चलानी नं.</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('datatable_scripts')
<script type="text/javascript">
$(document).ready(function () {
    // Check if user is logged in and has permissions
    let showActions = @json(auth()->check() && (auth()->user()->can('punarabedan-edit') || auth()->user()->can('punarabedan-delete')));

    // Base columns configuration
    let columns = [
        { data: 'id', name: 'id', visible:false },
        { data: 'mudda_number', name: 'mudda_number' },
        { data: 'jaherwala_name', name: 'jaherwala_name' },
        { data: 'pratiwadi_name', name: 'pratiwadi_name' },
        { data: 'mudda_name', name: 'mudda_name' },
        { data: 'faisala_date', name: 'faisala_date' },
        { data: 'faisala_pramanikaran_date', name: 'faisala_pramanikaran_date' },
        { data: 'punarabedan', name: 'punarabedan' },
        { data: 'punarabedan_date', name: 'punarabedan_date' },
        { data: 'punarabedan_challani_number', name: 'punarabedan_challani_number' },
        { data: 'user_name', name: 'user_name' },
        { data: 'status', name: 'status' }
    ];

    // Add action column only if user has permission
    if (showActions) {
        columns.push({
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        });
    }

    $('#muddaTable').DataTable({
        "order": [[0, "desc"]],
        processing: true,
        serverSide: true,
        scrollX: true,
         ajax: {
            url: "{{ route('punarabedan.index') }}",
            error: function (xhr, error, thrown) {
                $('#dataTableError').removeClass('d-none');
                if (xhr.status === 401) {
                    $('#dataTableError').html('तपाईंलाई यो डाटा हेर्न अनुमति छैन। <a href="{{ route('login') }}">कृपया लगइन गर्नुहोस्</a>');
                }
            }
        },
        columns: columns,
        dom: '<"d-flex justify-content-between align-items-right mb-3"lBf>rtip',
        buttons: [
            { extend: 'excel', className: 'btn btn-success' },
            {
                extend: 'pdf',
                className: 'btn btn-danger',
                charset: 'UTF-8',
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
            processing: "डाटा लोड हुँदैछ... कृपया प्रतीक्षा गर्नुहोस्"
        }
    });
});
</script>
@endpush
