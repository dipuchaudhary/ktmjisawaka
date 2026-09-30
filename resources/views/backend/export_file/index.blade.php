@extends('adminlte::page')

@section('title', 'Export File - ' . $title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ $title }} निर्यात</h1>
        <span class="badge badge-warning text-dark">
            आ.व. {{ \App\Support\FiscalYearContext::current()->display_name }}
        </span>
    </div>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-export mr-1"></i> {{ $title }} Export File</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info py-2">
            <i class="fas fa-info-circle mr-1"></i>
            खोजी/फिल्टर गरिएको डाटा मात्र Export हुनेछ। ID र Action समावेश गरिएको छैन।
        </div>

        <div class="table-responsive">
            <table id="exportTable" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        @foreach($columns as $label)
                            <th>{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            @foreach($columns as $field => $label)
                                @php
                                    $value = $record->{$field};
                                    if ($field === 'pratiwadi_name' && is_string($value)) {
                                        $decoded = json_decode($value, true);
                                        if (is_array($decoded)) {
                                            $value = collect($decoded)->map(function ($item) {
                                                $name = $item['name'] ?? '';
                                                $status = $item['status'] ?? '';
                                                return trim($name . ($status ? ' (' . $status . ')' : ''));
                                            })->filter()->implode(', ');
                                        }
                                    }
                                    if (is_array($value)) {
                                        $value = collect($value)->map(function ($item) {
                                            return is_array($item) ? implode(' ', $item) : $item;
                                        })->implode(', ');
                                    }
                                    if ($field === 'status') {
                                        $value = ($value === true || $value === 1 || $value === '1') ? 'Done' : 'Pending';
                                    }
                                @endphp
                                <td>{{ $value ?? '-' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.dataTables.min.css">
<style>
    #exportTable th,
    #exportTable td {
        vertical-align: middle;
        white-space: nowrap;
    }
    .dt-buttons .dt-button {
        margin-right: .35rem;
        border-radius: .25rem;
    }
    @media print {
        .main-sidebar,
        .main-header,
        .content-header,
        .dt-buttons,
        .dt-search,
        .dt-length,
        .dt-info,
        .dt-paging {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
        }
        #exportTable {
            font-size: 9px;
        }
    }
</style>
@endpush

@push('js')
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.20/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.20/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.print.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    pdfMake.fonts = {
        Devnagari: {
            normal: '{{ asset("frontend/fonts/NotoSansDevanagari-Regular.ttf") }}',
            bold: '{{ asset("frontend/fonts/NotoSansDevanagari-Bold.ttf") }}'
        }
    };

    new DataTable('#exportTable', {
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'सबै']],
        order: [],
        scrollX: true,
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success',
                        title: @json($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name),
                        exportOptions: { modifier: { search: 'applied', order: 'applied', page: 'all' } }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        title: @json($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name),
                        customize: function (doc) {
                            doc.defaultStyle = { font: 'Devnagari', fontSize: 7 };
                            doc.styles.tableHeader = { font: 'Devnagari', bold: true, fontSize: 8 };
                            doc.pageMargins = [15, 20, 15, 20];
                        },
                        exportOptions: { modifier: { search: 'applied', order: 'applied', page: 'all' } }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info',
                        title: @json($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name),
                        exportOptions: { modifier: { search: 'applied', order: 'applied', page: 'all' } },
                        customize: function (win) {
                            win.document.body.style.fontFamily = 'Noto Sans Devanagari, Arial, sans-serif';
                            win.document.body.style.fontSize = '10px';
                        }
                    }
                ]
            }
        },
        language: {
            search: 'खोजी गर्नुहोस्:',
            lengthMenu: '_MENU_ प्रविष्टि देखाउनुहोस्',
            info: '_TOTAL_ मध्ये _START_ देखि _END_ प्रविष्टिहरू',
            infoEmpty: '० प्रविष्टिहरू',
            zeroRecords: 'कुनै डाटा फेला परेन',
            infoFiltered: '(कुल _MAX_ मध्येबाट छानिएको)'
        }
    });
});
</script>
@endpush
