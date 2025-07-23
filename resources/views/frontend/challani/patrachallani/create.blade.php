@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">पत्र चलानी फारम</h3>
    <a href="{{ route('patra_challani.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
    <form class="container" method="POST" action="{{ route('patra_challani.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center mb-5">
                <div class="col-auto">
                    <label for="चलानी नं." class="col-form-label">चलानी नं.</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="input-group-text mb-2" id="challani_number" name="challani_number" value="{{ toNepaliNumber($ChallaniNumber) }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="पत्र चलान भएको कार्यालय" class="form-label">पत्र चलान भएको कार्यालय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('karyalaya_name') is-invalid @enderror" id="karyalaya_name" name="karyalaya_name" value="{{ old('karyalaya_name') }}">
                    @error('karyalaya_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="चलानी मिति" class="form-label">चलानी मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('challani_date') is-invalid @enderror" id="challani_date" name="challani_date" value="{{ old('challani_date') }}" >
                    @error('challani_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="विषय" class="form-label">विषय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('challani_subject') is-invalid @enderror" id="challani_subject" name="challani_subject" value="{{ old('challani_subject') }}" >
                    @error('challani_subject')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="चलानी शाखा" class="form-label">चलानी गर्ने शाखा <span style="color:red">*</span></label>
                    <select class="form-control" id="challani_sakha" name="challani_sakha">
                            <option value="">छान्नुहोस्</option>
                            <option value="सचिवालय">सचिवालय</option>
                            <option value="प्रशासन">प्रशासन</option>
                            <option value="लेखा">लेखा</option>
                            <option value="मुद्दा">मुद्दा</option>
                            <option value="पुनरावेदन">पुनरावेदन</option>
                            <option value="बैकिङ्ग">बैकिङ्ग</option>
                            <option value="कारागार">कारागार</option>
                        </select>
                    @error('challani_sakha')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2 mb-3" id="faat-div">
                    <label for="faat" class="form-label">फाँट</label>
                    <select class="form-control" id="faat" name="faat">
                            <option value="">छान्नुहोस्</option>
                            <option value="क">क</option>
                            <option value="ख">ख</option>
                            <option value="ग">ग</option>
                    </select>
                    @error('faat')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="जाहेरवाला" class="form-label">जाहेरवाला </label>
                    <input type="text" class="form-control @error('jaherwala_name') is-invalid @enderror" id="jaherwala" name="jaherwala_name" value={{ old('jaherwala_name') }} >
                        @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="प्रतिवादी" class="form-label">प्रतिवादी </label>
                    <input type="text" class="form-control @error('pratiwadi_name') is-invalid @enderror" id="pratiwadi_name" name="pratiwadi_name" value={{ old('pratiwadi_name') }} >
                        @error('pratiwadi_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="मुद्दा नं." class="form-label">राय दर्ता नं.</label>
                    <input type="text" class="form-control nep-number @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="बोधार्थ" class="form-label">बोधार्थ </label>
                    <select class="challani-bodartha custom-select2" name="bodartha[]" id="bodartha" multiple="multiple" style="width: 100%;">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="दस्तखत गर्ने अधिकारी" class="form-label">दस्तखत गर्ने अधिकारी</label>
                    <input type="text" class="form-control @error('verified_by') is-invalid @enderror" id="verified_by" name="verified_by" value="{{ old('verified_by') }}" >
                    @error('verified_by')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
@push('scripts')
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
