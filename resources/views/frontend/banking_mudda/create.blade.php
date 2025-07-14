@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">बैकिङ्ग मुद्दा दर्ता फारम</h3>
    <a href="{{ route('banking_mudda.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
    <form class="container" method="POST" action="{{ route('banking_mudda.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('anusandhan_garne_nikaye') is-invalid @enderror" id="anusandhan_garne_nikaye" name="anusandhan_garne_nikaye" value="{{ old('anusandhan_garne_nikaye') }}">
                    @error('anusandhan_garne_nikaye')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम <span style="color:red">*</span></label>
                    <select class="form-select form-control @error('mudda_name') is-invalid @enderror" name="mudda_name" id="mudda_name">
                        <option value="बैकिङ्ग" selected>बैकिङ्ग</option>
                        <option value="ठगी">ठगी</option>
                    </select>
                @error('mudda_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control custom-select2 @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name[]" multiple="multiple" >
                    </select>
                        @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 pratiwadi-input-group mb-3">
                    <div class="d-flex gap-3 border pratiwadi-group">
                        <div class="flex-fill p-1">
                            <label for="प्रतिवादीको नाम">प्रतिवादीको नाम <span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="pratiwadi_name[]" placeholder="प्रतिवादीको नाम" value="{{ old('pratiwadi_name.0') }}">
                            @error('pratiwadi_name.0')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-fill p-1">
                            <label for="मुद्दा स्थिति">मुद्दा स्थिति <span style="color:red">*</span></label>
                            <select name="mudda_sthiti[]" class="form-control">
                                <option value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                                @foreach(['फरार','पक्राउ','हाजिरि जमानीमा छोडेको','तामेली','नचल्ने'] as $status)
                                    <option value="{{ $status }}" {{ old('mudda_sthiti.0') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mudda_sthiti.0')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-fill p-1 d-flex align-items-end gap-2 align-center">
                            <button type="button" class="btn btn-success btn-sm addBtn" style="margin-bottom:17px; margin-right:2px;">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm removeBtn" style="margin-bottom:17px;">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
                    <input type="text" class="form-control nep-number @error('pratiwadi_number') is-invalid @enderror" id="pratiwadi_number" name="pratiwadi_number" value="{{ old('pratiwadi_number') }}" >
                    @error('pratiwadi_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा दर्ता मिति" class="form-label">मुद्दा विवरण </label>
                     <select class="form-select form-control @error('mudda_bibran') is-invalid @enderror" name="mudda_bibran" id="mudda_bibran">
                    <option selected value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                    <option value="anusuchi_1">अनुसूची १</option>
                    <option value="anusuchi_2">अनुसूची २</option>
                </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा दर्ता मिति" class="form-label">राय दर्ता मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('mudda_date') is-invalid @enderror" id="mudda_date" name="mudda_date" value="{{ old('mudda_date') }}">
                    @error('mudda_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा पेश भएको कार्यालय" class="form-label">मुद्दा पेश भएको कार्यालय </label>
                    <select class="form-select form-control @error('pesi_karyala') is-invalid @enderror" name="pesi_karyala" id="pesi_karyala">
                        <option selected value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                        <option value="ktm">काठमाण्डौं जिल्ला अदालत</option>
                        <option value="patan">उच्च सरकारी वकील कार्यालय, पाटन</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
                    <input type="text" class="form-control nep-number @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="म्याद" class="form-label">म्याद</label>
                    <input type="text" class="form-control date-picker" id="mudda_myad" name="mudda_myad" value="{{ old('mudda_myad') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="सरकारी वकील" class="form-label">सरकारी वकील</label>
                    <input type="text" class="form-control" id="sarkariwakil_name" name="sarkariwakil_name">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा पठाएको मिति" class="form-label">मुद्दा पठाएको मिति</label>
                    <input type="text" class="form-control date-picker @error('mudda_pathayko_date') is-invalid @enderror" id="mudda_pathayko_date" name="mudda_pathayko_date" value="{{ old('mudda_pathayko_date') }}">
                    @error('mudda_pathayko_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा पठाएको मिति" class="form-label"> चलानी नं.(चलानी भएमा)</label>
                    <input type="text" class="form-control" id="challani_number" name="challani_number" value="{{ toNepaliNumber($ChallaniNumber) }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="चलानी भएको/नभएको" class="form-label">चलानी अवस्था <span style="color:red">*</span></label>
                    <select class="form-select form-control @error('status') is-invalid @enderror" name="status" id="status">
                        <option value="0">चलानी नभएको</option>
                        <option value="1">चलानी भएको</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
@push('script')
{{-- <script>
    // clone inputgroupt
$(document).on('click', '.addBtn', function () {
    let group = $(this).closest('.pratiwadi-group');
    let cloned = group.clone();

    // Remove labels
    cloned.find('label').remove();

    // Clear input/select values
    cloned.find('input').val('');
    cloned.find('select').val('');

    // Append cloned group
    group.closest('.col-md-6').append(cloned);
});

$(document).on('click', '.removeBtn', function () {
    let allGroups = $(this).closest('.col-md-6').find('.pratiwadi-group');

    if (allGroups.length > 1) {
        $(this).closest('.pratiwadi-group').remove();
    } else {
        alert('कम्तिमा एउटा समूह आवश्यक छ।');
    }
});
</script> --}}
@endpush
