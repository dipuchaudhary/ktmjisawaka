@extends('adminlte::page')

@section('title', 'Search Mudda Data')

@section('content_header')
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 mt-3 mb-3">
        @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession
        <div class="pull-left">
            <h2>Mudda Challani Status</h2>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form id="searchForm">
            @csrf
            <div class="form-row d-flex">
                <div class="form-group col-md-4">
                    <select name="table_name" class="form-control">
                        <option value="">मुद्दा छनौट गर्नुहोस् </option>
                        <option value="mudda_dartas">मुद्दा राय दर्ता</option>
                        <option value="banking_muddas">बैकिङ्ग राय दर्ता</option>
                        <option value="patra_challanis">पत्र चलानी</option>
                        <option value="aviyog_challanis">अभियोग चलानी</option>
                        <option value="punarabedans">पुनरावेदन</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <input name="search_keyword" id="search_keyword"class="form-control" type="text" placeholder="मुद्दा नाम, प्रतिवादी नाम, जाहेरवाला, राय दर्ता नं. मात्र" />
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-block btn-primary">Search</button>
                </div>
            </div>
        </form>
        <div id="result" class="mt-4"></div>
    </div>
</div>
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$(document).ready(function() {
    const engToNep = s => String(s).replace(/[0-9]/g, d => '०१२३४५६७८९'[d]);
    $("#search_keyword").on("keyup", function() {
        $converted_string = engToNep($(this).val());
        $(this).val($converted_string);
    });
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('mudda-status.search') }}",
            method: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                $('#result').html('<p class="text-info">Searching...</p>');
            },
            success: function(response) {
                if (response.status === 'success') {
                    let html = '';
                    console.log(response.data);
                    if (response.data.length > 0) {
                        html += `<table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>राय दर्ता नं.</th>
                                            <th>मुद्दाको नाम</th>
                                            <th>जाहेरवालको नाम</th>
                                            <th>प्रतिवादीको नाम</th>
                                            <th>मुद्दा स्थिति</th>
                                            <th>Update Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                        response.data.forEach(row => {
                              let pratiwadiHtml = '';
                              let statusBadge = '';
                                try {
                                    const parsed = JSON.parse(row.pratiwadi_name);
                                    if (Array.isArray(parsed)) {
                                        parsed.forEach(item => {
                                            if (item.name && item.status) {
                                                pratiwadiHtml += `<small class='badge rounded-pill text-white bg-dark'>${item.name} (${item.status})</small><br>`;
                                            }
                                        });
                                    } else {
                                        pratiwadiHtml = row.pratiwadi_name ?? '-';
                                    }
                                } catch (e) {
                                    pratiwadiHtml = row.pratiwadi_name ?? '-';
                                }

                                if (row.status == 0 || row.status === false || row.status === '0') {
                                    statusBadge = `<span class="badge rounded-pill text-white bg-danger">Pending</span>`;
                                } else {
                                    statusBadge = `<span class="badge rounded-pill text-white bg-success">Done</span>`;
                                }
                                let statusSelect = `
                                    <select class="form-select status-select" data-id="${row.id}" data-table="${row.table_name}">
                                        <option value="0" ${row.status == 0 || row.status === '0' ? 'selected' : ''}>Pending</option>
                                        <option value="1" ${row.status == 1 || row.status === '1' ? 'selected' : ''}>Done</option>
                                    </select>
                                    `;
                            html += `<tr data-id="${row.id}">
                                <td>${row.mudda_number ?? '-'}</td>
                                <td>${row.mudda_name ?? '-'}</td>
                                <td>${row.jaherwala_name ?? '-'}</td>
                                <td>${pratiwadiHtml ?? '-'}</td>
                                <td class="status-badge">${statusBadge}</td>
                                <td>${statusSelect}</td>
                            </tr>`;
                        });

                        html += '</tbody></table>';
                    } else {
                        html = '<p class="text-warning">No results found.</p>';
                    }

                    $('#result').html(html);
                } else {
                    $('#result').html(`<p class="text-danger">${response.message}</p>`);
                }
            },
            error: function(xhr) {
                $('#result').html('<p class="text-danger">An error occurred. Please try again.</p>');
            }
        });
    });

$(document).on('change', '.status-select', function() {
    let newStatus = $(this).val();
    let id = $(this).data('id');
    let tableName = $(this).data('table');

    $.ajax({
        url: "{{ route('mudda-status.updateStatus') }}",
        method: "POST",
        data: {
            id: id,
            status: newStatus,
            table_name: tableName,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'अपडेट भयो!',
                    text: 'मुद्दा स्थिति सफलतापूर्वक अपडेट भयो।',
                    timer: 1500,
                    showConfirmButton: false
                });

                const newBadge = (newStatus == 0 || newStatus === '0')
                    ? `<span class="badge rounded-pill text-white bg-danger">Pending</span>`
                    : `<span class="badge rounded-pill text-white bg-success">Done</span>`;

                $(`tr[data-id="${id}"]`).find('td.status-badge').html(newBadge);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'त्रुटि',
                    text: 'स्थिति अपडेट गर्न असफल भयो।'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'त्रुटि',
                text: 'स्थिति अपडेट गर्दा समस्या आयो।'
            });
        }
    });
});

});
</script>
@stop
