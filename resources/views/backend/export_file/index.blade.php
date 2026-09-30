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
                        <option value="{{ $key }}">{{ $moduleTitle }}</option>
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

        <div class="alert alert-info mt-4 mb-0">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>{{ $fiscalYear->display_name }}</strong> आर्थिक वर्षको चयन गरिएको
            मोड्युलका <strong>सबै database fields</strong> निर्यात गरिनेछ।
            कुनै data preview देखाइने छैन।
        </div>
    </div>
</div>
@stop

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const moduleSelect = document.getElementById('moduleSelect');
    const typeSelect = document.getElementById('typeSelect');
    const exportButton = document.getElementById('exportButton');

    function updateButtonState() {
        exportButton.disabled = !moduleSelect.value || !typeSelect.value;
    }

    moduleSelect.addEventListener('change', updateButtonState);
    typeSelect.addEventListener('change', updateButtonState);

    exportButton.addEventListener('click', function () {
        const selectedModule = moduleSelect.value;
        const selectedType = typeSelect.value;

        if (!selectedModule || !selectedType) {
            return;
        }

        const baseUrl = '{{ url('/admin/export-file') }}/' + encodeURIComponent(selectedModule);

        if (selectedType === 'pdf') {
            window.open(baseUrl + '/print?pdf=1', '_blank');
            return;
        }

        if (selectedType === 'excel') {
            window.location.href = baseUrl + '/excel';
            return;
        }

        if (selectedType === 'print') {
            window.open(baseUrl + '/print', '_blank');
        }
    });

    updateButtonState();
});
</script>
@endpush
