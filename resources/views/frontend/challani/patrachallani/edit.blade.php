@extends('layouts.master')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">पत्र चलानी फारम</h3>
    <a href="{{ route('patra_challani.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
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
                <div class="col-md-4 mb-3">
                    <label for="चलानी शाखा" class="form-label">चलानी गर्ने शाखा</label>
                    @php
                        if (strpos($patrachallani->challani_sakha, '-') !== false) {
                            $mudda_faat = trim(explode('-', $patrachallani->challani_sakha)[0]);
                        } else {
                            $mudda_faat = $patrachallani->challani_sakha;
                        }
                    @endphp
                    <select class="form-control" id="challani_sakha" name="challani_sakha">
                            <option value="" {{ $patrachallani->challani_sakha == '' ? 'selected' : '' }}>छान्नुहोस्</option>
                            <option value="सचिवालय" {{ $patrachallani->challani_sakha == 'सचिवालय' ? 'selected' : '' }}>सचिवालय</option>
                            <option value="प्रशासन" {{ $patrachallani->challani_sakha == 'प्रशासन' ? 'selected' : '' }}>प्रशासन</option>
                            <option value="लेखा" {{ $patrachallani->challani_sakha == 'लेखा' ? 'selected' : '' }}>लेखा</option>
                            <option value="मुद्दा" {{ $mudda_faat == 'मुद्दा' ? 'selected' : '' }}>मुद्दा</option>
                            <option value="पुनरावेदन" {{ $patrachallani->challani_sakha == 'पुनरावेदन' ? 'selected' : '' }}>पुनरावेदन</option>
                            <option value="बैकिङ्ग" {{ $patrachallani->challani_sakha == 'बैकिङ्ग' ? 'selected' : '' }}>बैकिङ्ग</option>
                        </select>
                    @error('challani_sakha')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2 mb-3" id="faat-div">
                    <label for="faat" class="form-label">फाँट</label>
                    <select class="form-control" id="faat" name="faat">
                            <option value="" {{ $patrachallani->faat == '' ? 'selected' : '' }}>छान्नुहोस्</option>
                            <option value="क" {{ $patrachallani->faat == 'क' ? 'selected' : '' }}>क</option>
                            <option value="ख" {{ $patrachallani->faat == 'ख' ? 'selected' : '' }}>ख</option>
                            <option value="ग" {{ $patrachallani->faat == 'ग' ? 'selected' : '' }}>ग</option>
                    </select>
                    @error('faat')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="जाहेरवाला" class="form-label">जाहेरवाला </label>
                   <select type="text" class="form-control custom-select2 @error('jaherwala') is-invalid @enderror" id="jaherwala" name="jaherwala[]" multiple="multiple" >
                        @if (!empty($patrachallani->jaherwala))
                        @foreach (explode(',', $patrachallani->jaherwala) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                        @endif
                    </select>
                        @error('jaherwala')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="प्रतिवादी" class="form-label">प्रतिवादी </label>
                    <select class="form-control custom-select2 @error('pratiwadi') is-invalid @enderror" id="pratiwadi" name="pratiwadi[]" multiple="multiple">
                         @if (!empty($patrachallani->pratiwadi))
                        @foreach (explode(',', $patrachallani->pratiwadi) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                        @endif
                    </select>
                        @error('pratiwadi')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
                    <input type="text" class="form-control nep-number @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ $patrachallani->mudda_number}}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="बोधार्थ" class="form-label">बोधार्थ </label>
                    <select class="challani-bodartha custom-select2" name="bodartha[]" id="bodartha" multiple="multiple" style="width: 100%;">
                    @if (!empty($patrachallani->bodartha))
                        @foreach (explode(',', $patrachallani->bodartha) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                    @endif
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="दस्तखत गर्ने अधिकारी" class="form-label">दस्तखत गर्ने अधिकारी</label>
                    <input type="text" class="form-control @error('verified_by') is-invalid @enderror" id="verified_by" name="verified_by" value="{{ $patrachallani->verified_by }}" >
                    @error('verified_by')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3">{{ $patrachallani->kaifiyat }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    function toggle_challani() {
        var selectedValue = $('#challani_sakha').val();
        if (selectedValue == 'मुद्दा') {
            $('#faat-div').show();
        } else {
            $('#faat-div').hide();
            $('#faat').val('');
        }
    }
    toggle_challani();
    $('#challani_sakha').change(function() {
        toggle_challani();

    });
});

</script>
@endpush
