@extends('layouts.master')
@section('content')
<div class="container mt-5 table-responsive" style="max-width: 85%;">
    <h1>पुनरावेदन सूची</h1>
     @auth
        @can('punarabedan-create')
        <a href="{{ route('punarabedan.create') }}" class="btn btn-primary float-right mb-5">
          <i class="fas fa-plus"></i> नयाँ पुनरावेदन सिर्जना गर्नुहोस्
        </a>
        @endcan
    @endauth
    <div class="alert alert-danger d-none" id="dataTableError">
        तालिका डाटा लोड गर्न असफल भयो। कृपया पृष्ठ रिफ्रेस गर्नुहोस् वा पछि प्रयास गर्नुहोस्।
    </div>
    <table id="muddaTable" class="table table-bordered table-hover data-table table-sm">
        <thead class="thead-light">
            <tr>
                <th rowspan="2">ID</th>
                <th rowspan="2">राय दर्ता नं.</th>
                <th rowspan="2">जाहेरवालाको नाम</th>
                <th rowspan="2">प्रतिवादीको नाम</th>
                <th rowspan="2">मुद्दाको किसिम</th>
                <th rowspan="2">अदालत मुद्दा नं.</th>
                <th rowspan="2">फैसला मिति</th>
                <th rowspan="2">फैसला प्रमाणीकरण मिति</th>
                <th colspan="3" class="text-center">कार्यालयबाट भएको पुनरावेदन सम्बन्धी कारवाही</th>
                <th rowspan="2">प्रविष्टकर्ता</th>
                <th rowspan="2">स्थिति</th>
                @auth
                    @if(auth()->user()->can('punarabedan-edit') || auth()->user()->can('punarabedan-delete'))
                        <th class="noExport" rowspan="2" width="105px">Action</th>
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
        { data: 'mudda_number', name: 'mudda_number', className: 'exportable' },
        { data: 'jaherwala_name', name: 'jaherwala_name', className: 'exportable' },
        { data: 'pratiwadi_name', name: 'pratiwadi_name', className: 'exportable' },
        { data: 'mudda_name', name: 'mudda_name', className: 'exportable' },
        { data: 'adalat_mudda_number', name: 'adalat_mudda_number', className: 'exportable' },
        { data: 'faisala_date', name: 'faisala_date', className: 'exportable' },
        { data: 'faisala_pramanikaran_date', name: 'faisala_pramanikaran_date', className: 'exportable' },
        { data: 'punarabedan', name: 'punarabedan', className: 'exportable' },
        { data: 'punarabedan_date', name: 'punarabedan_date', className: 'exportable' },
        { data: 'punarabedan_challani_number', name: 'punarabedan_challani_number', className: 'exportable' },
        { data: 'user_name', name: 'user_name', className: 'exportable' },
        { data: 'status', name: 'status', className: 'exportable' }
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
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10', '25', '50', '100', 'All' ]
        ],
        "order": [[0, "desc"]],
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: true,
        responsive: true,
        fixedHeader: true,
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
        deferRender: false,
        dom: '<"d-flex justify-content-between align-items-right mb-3"lBf>rtip',
        buttons: [
            { extend: 'excel', className: 'btn btn-success', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: ':not(.noExport)'
                    }
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger',
                charset: 'UTF-8',
                customize: function(doc) {
                    doc.defaultStyle = {
                        font: 'Devnagari'
                    };
                },
                exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: ':not(.noExport)'
                    }
            },
            { extend: 'print', className: 'btn btn-info', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: ':not(.noExport)'
                    }

            },
        ],
        language: {
            zeroRecords: "कुनै डाटा फेला परेन",
            info: "_TOTAL_ मध्ये _START_ देखि _END_ प्रविष्टिहरू",
            infoEmpty: "० प्रविष्टिहरू",
            infoFiltered: "(कुल _MAX_ मध्येबाट छानिएको)",
            processing: "डाटा लोड हुँदैछ... कृपया प्रतीक्षा गर्नुहोस्"
        }
    });
});
</script>
@endpush
