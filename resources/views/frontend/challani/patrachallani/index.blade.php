@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h1>पत्र चलानी सूची</h1>
    @can('patrachallani-create')
    <a href="{{ route('patra_challani.create') }}" class="btn btn-primary float-right mb-5">
        नयाँ पत्र चलानी गर्नुहोस्
    </a>
    @endcan

    <div class="alert alert-danger d-none" id="dataTableError">
        तालिका डाटा लोड गर्न असफल भयो। कृपया पृष्ठ रिफ्रेस गर्नुहोस् वा पछि प्रयास गर्नुहोस्।
    </div>

    <table id="patraChallaniTable" class="table table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>पत्र चलानी भएको कार्यालय</th>
                <th>चलानी मिति</th>
                <th>चलानी नं.</th>
                <th>मुद्दा नं.</th>
                <th>विषय</th>
                <th>दस्तखत गर्ने अधिकारी</th>
                <th>चलानी शाखा</th>
                <th>प्रयोगकर्ता</th>
                <th>Status</th>
                @auth
                    @if(auth()->user()->can('patrachallani-edit') || auth()->user()->can('patrachallani-delete'))
                        <th width="105px">Action</th>
                    @endif
                @endauth
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('datatable_scripts')
<script type="text/javascript">
$(document).ready(function () {
    // Check if user is authenticated and has permissions
    const showActions = @json(auth()->check() && (auth()->user()->can('patrachallani-edit') || auth()->user()->can('patrachallani-delete')));

    // Base columns configuration
    let columns = [
        { data: 'id', name: 'id' },
        { data: 'karyalaya_name', name: 'karyalaya_name' },
        { data: 'challani_date', name: 'challani_date' },
        { data: 'challani_number', name: 'challani_number' },
        { data: 'mudda_number', name: 'mudda_number' },
        { data: 'challani_subject', name: 'challani_subject' },
        { data: 'verified_by', name: 'verified_by' },
        { data: 'challani_sakha', name: 'challani_sakha' },
        { data: 'user_name', name: 'user_name' },
        { data: 'status', name: 'status' }
    ];

    // Add action column only if user has permission
    if (showActions) {
        columns.push({
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center'
        });
    }

    $('#patraChallaniTable').DataTable({
        "order": [[0, "desc"]],
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            url: "{{ route('patra_challani.index') }}",
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
                { extend: 'pdf', className: 'btn btn-danger',charset: 'UTF-8',
                    customize: function(doc) {
                    doc.defaultStyle = {
                    font: 'Devnagari'
                    };
                    }
                },
                { extend: 'print', className: 'btn btn-info' }
            ],
            language: {
                zeroRecords: "कुनै डाटा फेला परेन",
                info: "_TOTAL_ मध्ये _START_ देखि _END_ प्रविष्टिहरू",
                infoEmpty: "० प्रविष्टिहरू",
                infoFiltered: "(कुल _MAX_ मध्येबाट छानिएको)",
            }
    });
    $('body').on('click', '#reloadTable', function() {
            $('#dataTableError').addClass('d-none');
            table.ajax.reload();
        });
});
</script>
@endpush
