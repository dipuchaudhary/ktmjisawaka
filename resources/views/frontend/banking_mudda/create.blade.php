@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">बैकिङ्ग मुद्दा दर्ता फारम</h3>
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
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको नाम" class="form-label">प्रतिवादीको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control custom-select2 @error('pratiwadi_name') is-invalid @enderror" id="pratiwadi_name" name="pratiwadi_name[]" multiple="multiple" >
                    </select>
                        @error('pratiwadi_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
                    <input type="text" class="form-control @error('pratiwadi_number') is-invalid @enderror" id="pratiwadi_number" name="pratiwadi_number" value="{{ old('pratiwadi_number') }}" >
                    @error('pratiwadi_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                <label for="मुद्दाको स्थिति" class="form-label">मुद्दाको स्थिति <span style="color:red">*</span></label>
                <select class="form-select form-control @error('mudda_stithi') is-invalid @enderror" name="mudda_stithi" id="mudda_stithi">
                    <option selected value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                    <option value="फरार">फरार</option>
                    <option value="पक्राउ">पक्राउ</option>
                    <option value="हाजिरि जमानीमा छोडेको">हाजिरि जमानीमा छोडेको</option>
                    <option value="तामेली">तामेली</option>
                    <option value="नचल्ने">मुद्दा नचल्ने</option>
                </select>
                @error('mudda_stithi')
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
                    <input type="text" class="form-control @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
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
