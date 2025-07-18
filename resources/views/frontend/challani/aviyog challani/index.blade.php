@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h1>अभियोग चलानी सूची</h1>

     <div class="alert alert-danger d-none" id="dataTableError">
        तालिका डाटा लोड गर्न असफल भयो। कृपया पृष्ठ रिफ्रेस गर्नुहोस् वा पछि प्रयास गर्नुहोस्।
    </div>

    <table id="muddaTable" class="table table-bordered table-hover data-table">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>चलानी मिति</th>
                <th>चलानी नं.</th>
                <th>राय दर्ता नं.</th>
                <th>मुद्दाको किसिम</th>
                <th>जाहेरवालाको नाम</th>
                <th>प्रतिवादीको नाम</th>
                <th>सरकारी वकील</th>
                <th>फाँट</th>
                <th>अनुसन्धान गर्ने निकाय</th>
                <th>अभियोग/निर्णय(पेश भएको कार्यालय)</th>
                <th>अभियोग अपलोड भएको मिति</th>
                <th>प्रविष्टकर्ता</th>
                <th>स्थिति</th>
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

        let columns = [
            { data: 'id', name: 'id', visible:false },
            { data: 'challani_date', name: 'challani_date', className: 'exportable' },
            { data: 'challani_number', name: 'challani_number', className: 'exportable' },
            { data: 'mudda_number', name: 'mudda_number', className: 'exportable' },
            { data: 'mudda_name', name: 'mudda_name', className: 'exportable' },
            { data: 'jaherwala_name', name: 'jaherwala_name', className: 'exportable' },
            { data: 'pratiwadi_name', name: 'pratiwadi_name', className: 'exportable' },
            { data: 'sarkariwakil_name', name: 'sarkariwakil_name', className: 'exportable' },
            { data: 'faat_name', name: 'faat_name', className: 'exportable' },
            { data: 'anusandhan_garne_nikaye', name: 'anusandhan_garne_nikaye', className: 'exportable' },
            { data: 'pesh_karyala', name: 'pesh_karyala', className: 'exportable' },
            { data: 'upload_date', name: 'upload_date', className: 'exportable' },
            { data: 'user_name', name: 'user_name', className: 'exportable' },
            { data: 'status', name: 'status', className: 'exportable' },
            { data: 'action', name: 'action',  orderable: false, searchable: false, className: 'text-center' },
        ];

        $('#muddaTable').DataTable({
            "order": [[0, "desc"]],
            processing: true,
            serverSide: true,
            scrollX: true,
            fixedHeader: true,
            scrollCollapse: true,
            ajax: {
                url: "{{ route('aviyog_challani.index') }}",
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
                { extend: 'excel', className: 'btn btn-success', exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page: 'all'
                        },
                        columns: '.exportable'
                    }
                },
                { extend: 'pdf', className: 'btn btn-danger',charset: 'UTF-8',
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
                }
            ],
            language: {
                zeroRecords: "कुनै डाटा फेला परेन",
                info: "_TOTAL_ मध्ये _START_ देखि _END_ प्रविष्टिहरू",
                infoEmpty: "० प्रविष्टिहरू",
                infoFiltered: "(कुल _MAX_ मध्येबाट छानिएको)",
                processing: "डाटा लोड हुँदैछ... कृपया प्रतीक्षा गर्नुहोस्"
            }
        });
         $('body').on('click', '#reloadTable', function() {
            $('#dataTableError').addClass('d-none');
            table.ajax.reload();
        });
    });
</script>
@endpush
