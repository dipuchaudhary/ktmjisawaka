@extends('adminlte::page')

@section('title', 'Export File')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Export File</h1>
        <span class="badge badge-warning text-dark">
            आ.व. {{ $fiscalYear->display_name }}
        </span>
    </div>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-export mr-1"></i> Module Export
        </h3>
    </div>

    <div class="card-body">
        <form id="exportForm" class="row align-items-end">
            <div class="col-md-5">
                <label for="moduleSelect">Module</label>
                <select id="moduleSelect" class="form-control">
                    <option value="">-- Select Module --</option>
                    @foreach($modules as $key => $moduleTitle)
                        <option value="{{ $key }}" @selected($module === $key)>
                            {{ $moduleTitle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mt-3 mt-md-0">
                <label for="typeSelect">Export Type</label>
                <select id="typeSelect" class="form-control">
                    <option value="">-- Select File Type --</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="print">Print</option>
                </select>
            </div>

            <div class="col-md-3 mt-3 mt-md-0">
                <button type="button" id="exportButton" class="btn btn-primary btn-block" disabled>
                    <i class="fas fa-file-export mr-1"></i> Export
                </button>
            </div>
        </form>

        <div class="alert alert-info mt-4 mb-3">
            <i class="fas fa-info-circle mr-1"></i>
            पहिले <strong>Module</strong> र <strong>Export Type</strong> छान्नुहोस्, त्यसपछि
            <strong>Export</strong> बटन थिच्नुहोस्। निर्यातमा ID र Action समावेश गरिएको छैन।
            हाल चयन गरिएको आर्थिक वर्षको डाटा मात्र प्रयोग हुन्छ।
        </div>

        @if($module)
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">{{ $title }}</h5>
                <span class="text-muted">{{ $records->count() }} records</span>
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
                                            $value = ($value === true || $value === 1 || $value === '1')
                                                ? 'Done'
                                                : 'Pending';
                                        }
                                    @endphp
                                    <td>{{ $value === null || $value === '' ? '-' : $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-export fa-3x mb-3"></i>
                <h5>Select a module to preview its records</h5>
                <p class="mb-0">You can export the selected module as PDF, Excel, or Print.</p>
            </div>
        @endif
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

    #hiddenExportButtons {
        display: none;
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
    const moduleSelect = document.getElementById('moduleSelect');
    const typeSelect = document.getElementById('typeSelect');
    const exportButton = document.getElementById('exportButton');
    const currentModule = @json($module);

    function updateButtonState() {
        exportButton.disabled = !moduleSelect.value || !typeSelect.value;
    }

    moduleSelect.addEventListener('change', function () {
        if (this.value) {
            window.location.href = '{{ url('/admin/export-file') }}/' + encodeURIComponent(this.value);
        }
        updateButtonState();
    });

    typeSelect.addEventListener('change', updateButtonState);

    if (!currentModule) {
        return;
    }

    pdfMake.fonts = {
        Devnagari: {
            normal: '{{ asset("frontend/fonts/NotoSansDevanagari-Regular.ttf") }}',
            bold: '{{ asset("frontend/fonts/NotoSansDevanagari-Bold.ttf") }}'
        }
    };

    const table = new DataTable('#exportTable', {
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'सबै']],
        order: [],
        scrollX: true,
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        title: @json($title . ' - आ.व. ' . $fiscalYear->display_name),
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            modifier: { search: 'applied', order: 'applied', page: 'all' }
                        },
                        customize: function (doc) {
                            doc.defaultStyle = { font: 'Devnagari', fontSize: 7 };
                            doc.styles.tableHeader = {
                                font: 'Devnagari',
                                bold: true,
                                fontSize: 8
                            };
                            doc.pageMargins = [15, 20, 15, 20];
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: @json($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name),
                        exportOptions: {
                            modifier: { search: 'applied', order: 'applied', page: 'all' }
                        }
                    },
                    {
                        extend: 'print',
                        title: @json($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name),
                        exportOptions: {
                            modifier: { search: 'applied', order: 'applied', page: 'all' }
                        },
                        customize: function (win) {
                            win.document.body.style.fontFamily =
                                'Noto Sans Devanagari, Arial, sans-serif';
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

    // Keep DataTables' individual export buttons hidden.
    document.querySelectorAll('.dt-buttons').forEach(function (element) {
        element.style.display = 'none';
    });

    exportButton.addEventListener('click', function () {
        const selectedType = typeSelect.value;
        const selectedModule = moduleSelect.value;

        if (!selectedModule || !selectedType) {
            return;
        }

        if (selectedType === 'pdf') {
            // Use the print-ready server view for PDF. Browser print supports
            // Nepali/Devanagari fonts reliably; select "Save as PDF".
            window.open(
                '{{ url('/admin/export-file') }}/' +
                encodeURIComponent(selectedModule) + '/print?pdf=1',
                '_blank'
            );
            return;
        }

        if (selectedType === 'excel') {
            window.location.href =
                '{{ url('/admin/export-file') }}/' +
                encodeURIComponent(selectedModule) + '/excel';
            return;
        }

        if (selectedType === 'print') {
            window.location.href =
                '{{ url('/admin/export-file') }}/' +
                encodeURIComponent(selectedModule) + '/print';
        }
    });

    updateButtonState();
});
</script>
@endpush
