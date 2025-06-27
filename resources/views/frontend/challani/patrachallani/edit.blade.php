@extends('layouts.master')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">पत्र चलानी फारम</h3>
    <form class="container" method="POST" action="{{ route('patra_challani.update',$patrachallani->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center mb-5">
                <div class="col-auto">
                    <label for="चलानी नं." class="col-form-label">चलानी नं.</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="input-group-text mb-2" id="challani_number" name="challani_number" value="{{ $patrachallani->challani_number }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="पत्र चलान भएको कार्यालय" class="form-label">पत्र चलान भएको कार्यालय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('karyalaya_name') is-invalid @enderror" id="karyalaya_name" name="karyalaya_name" value="{{ $patrachallani->karyalaya_name }}">
                    @error('karyalaya_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="चलानी मिति" class="form-label">चलानी मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('challani_date') is-invalid @enderror" id="challani_date" name="challani_date" value="{{ $patrachallani->challani_date }}" >
                    @error('challani_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="विषय" class="form-label">विषय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('challani_subject') is-invalid @enderror" id="challani_subject" name="challani_subject" value="{{ $patrachallani->challani_subject }}" >
                    @error('challani_subject')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
                    <input type="text" class="form-control @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ $patrachallani->mudda_number }}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="बोधार्थ" class="form-label">बोधार्थ </label>
                    <select class="challani-bodartha" name="bodartha[]" id="bodartha" multiple="multiple" style="width: 100%;">
                      @if (!empty($patrachallani->bodartha))
                        @foreach (explode(',', $patrachallani->bodartha) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                    @endif
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="दस्तखत गर्ने अधिकारी" class="form-label">दस्तखत गर्ने अधिकारी</label>
                    <input type="text" class="form-control @error('verified_by') is-invalid @enderror" id="verified_by" name="verified_by" value="{{ $patrachallani->verified_by }}" >
                    @error('verified_by')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3">{{ $patrachallani->kaifiyat}}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.challani-bodartha').select2({
        tags: true,
        placeholder: "विकल्प खोज्नुहोस् वा नयाँ टाइप गर्नुहोस्"
    });

    $('.challani-bodartha').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();

            let select = $(this);
            let input = select.data('select2').dropdown.$search || select.data('select2').$selection.find('input.select2-search__field');
            let value = input.val().trim();

            if (value !== '') {
                let exists = select.find('option').filter(function() {
                    return $(this).text().toLowerCase() === value.toLowerCase();
                }).length;

                if (!exists) {
                    let newOption = new Option(value, value, true, true);
                    select.append(newOption).trigger('change');
                }
                input.val('');
            }
        }
    });
});

</script>
@endpush
