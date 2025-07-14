@extends('layouts.master')

@section('content')
<div class="container mt-5 table-responsive" style="max-width: 85%;">
    <h1 class="mb-4">मुद्दाको सम्रग स्थिति सूची</h1>
    <div class="alert alert-danger d-none" id="dataTableError">
        तालिका डाटा लोड गर्न असफल भयो। कृपया पृष्ठ रिफ्रेस गर्नुहोस् वा पछि प्रयास गर्नुहोस्।
    </div>

    <table id="muddaTable" class="table table-bordered table-striped table-sm data-table">
        <thead class="thead-light">
            <tr>
                <th rowspan="2" scope="col">मुद्दा नं.</th>
                <th rowspan="2" scope="col">जाहेरवालाको नाम</th>
                <th rowspan="2" scope="col">प्रतिवादीको नाम</th>
                <th rowspan="2" scope="col">मुद्दाको नाम</th>
                <th colspan="4" class="text-center">बैंकिङ मुद्दा विवरण</th>
                <th colspan="4" class="text-center">अभियोग चलानी विवरण</th>
                <th colspan="4" class="text-center">पुनरावेदन विवरण</th>
            </tr>
            <tr>
                <th scope="col">सरकारी वकिल</th>
                <th scope="col">चलानी नं.</th>
                <th scope="col">प्रयोगकर्ता</th>
                <th scope="col">स्थिति</th>

                <th scope="col">अपलोड मिति</th>
                <th scope="col">चलानी नं.</th>
                <th scope="col">प्रयोगकर्ता</th>
                <th scope="col">स्थिति</th>

                <th scope="col">पुनरावेदन मिति</th>
                <th scope="col">चलानी नं.</th>
                <th scope="col">प्रयोगकर्ता</th>
                <th scope="col">स्थिति</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection


@push('datatable_scripts')
<script>
$(function () {

    $('#muddaTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        responsive: true,
        fixedHeader: true,
        ajax: {
            url: "{{ route('mudda.overall_status') }}",
            error: function (xhr) {
                $('#dataTableError').removeClass('d-none').text(
                    xhr.status === 401
                        ? 'तपाईंलाई यो डाटा हेर्न अनुमति छैन। कृपया लगइन गर्नुहोस्।'
                        : 'डेटा लोड गर्न सकिएन। कृपया पछि प्रयास गर्नुहोस्।'
                );
            }
        },
        columns: [
            { data: 'mudda_number', name: 'mudda_number', className: 'exportable', defaultContent: '-' },
            { data: 'jaherwala_name', name: 'jaherwala_name',className: 'exportable', defaultContent: '-' },
            { data: 'pratiwadi_name', name: 'pratiwadi_name',className: 'exportable', defaultContent: '-' },
            { data: 'mudda_name', name: 'mudda_name',className: 'exportable', defaultContent: '-' },

            { data: 'banking_sarkariwakil_name', name: 'banking_sarkariwakil_name',className: 'exportable', defaultContent: '-' },
            { data: 'banking_challani_number', name: 'banking_challani_number',className: 'exportable', defaultContent: '-' },
            { data: 'banking_user_name', name: 'banking_user_name',className: 'exportable', defaultContent: '-' },
            { data: 'banking_status', name: 'banking_status', orderable: false, searchable: false,className: 'exportable', defaultContent: '-' },

            { data: 'aviyog_upload_date', name: 'aviyog_upload_date',className: 'exportable', defaultContent: '-' },
            { data: 'aviyog_challani_number', name: 'aviyog_challani_number',className: 'exportable', defaultContent: '-' },
            { data: 'aviyog_user_name', name: 'aviyog_user_name',className: 'exportable', defaultContent: '-' },
            { data: 'aviyog_status', name: 'aviyog_status', orderable: false, searchable: false,className: 'exportable', defaultContent: '-' },

            { data: 'punarabedan_date', name: 'punarabedan_date',className: 'exportable', defaultContent: '-' },
            { data: 'punarabedan_challani_number', name: 'punarabedan_challani_number',className: 'exportable', defaultContent: '-' },
            { data: 'punarabedan_user_name', name: 'punarabedan_user_name',className: 'exportable', defaultContent: '-' },
            { data: 'punarabedan_status', name: 'punarabedan_status', orderable: false, searchable: false,className: 'exportable', defaultContent: '-' }
        ],
        dom: '<"d-flex justify-content-between align-items-right mb-3"lBf>rtip',
        buttons: [
            { extend: 'excel', className: 'btn btn-success', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: '.exportable'
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
                        columns: '.exportable'
                    }
            },
            { extend: 'print', className: 'btn btn-info', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: '.exportable'
                    }

            },
        ],
        language: {
            processing: "लोड हुँदैछ...",
            zeroRecords: "कुनै रेकर्ड फेला परेन",
            info: "_TOTAL_ मध्ये _START_ देखि _END_ प्रविष्टिहरू",
            infoEmpty: "० प्रविष्टिहरू",
            infoFiltered: "(कुल _MAX_ मध्येबाट छानिएको)"
        }
    });
});
</script>
@endpush
