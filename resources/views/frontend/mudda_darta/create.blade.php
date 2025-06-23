@extends('layouts.master')
@section('content')
  <form class="container mt-5 p-10" method="POST" action="{{ route('mudda_darta.store') }}" enctype="multipart/form-data">
    @csrf
    <h2 class="mb-4">मुल दर्ता फारम</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय <span style="color:red">*</span></label>
            <input type="text" class="form-control" id="anusandhan_garne_nikaye" name="anusandhan_garne_nikaye" class="@error('anusandhan_garne_nikaye') is-invalid @enderror" value="{{ old('anusandhan_garne_nikaye') }}">
            @error('anusandhan_garne_nikaye')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
            <input type="text" class="form-control" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
            @error('mudda_number')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम <span style="color:red">*</span></label>
            <input type="text" class="form-control" id="mudda_name" name="mudda_name" value="{{ old('mudda_name') }}" >
             @error('mudda_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
            <input type="text" class="form-control" id="jaherwala_name" name="jaherwala_name" value="{{ old('jaherwala_name') }}" >
             @error('jaherwala_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="प्रतिवादीको नाम" class="form-label">प्रतिवादीको नाम <span style="color:red">*</span></label>
            <input type="text" class="form-control" id="pratiwadi_name" name="pratiwadi_name" value="{{ old('pratiwadi_name') }}" >
            @error('pratiwadi_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
            <input type="text" class="form-control" id="pratiwadi_number" name="pratiwadi_number" value="{{ old('pratiwadi_number') }}" >
             @error('pratiwadi_number')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
        <label for="मुद्दाको स्थिति" class="form-label">मुद्दाको स्थिति <span style="color:red">*</span></label>
        <select class="form-select form-control" name="mudda_stithi" id="mudda_stithi">
            <option selected>-----एउटाको विकल्प रोज्नु----</option>
            <option value="farar">फरार</option>
            <option value="pakrau">पक्राउ</option>
            <option value="hajiri_jawanima_xodeko">हाजिरि जमानीमा छोडेको</option>
        </select>
         @error('mudda_stithi')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दा दर्ता मिति" class="form-label">मुद्दा दर्ता मिति </label>
            <input type="text" class="form-control date-picker" id="mudda_date" name="mudda_date" value="{{ old('mudda_date') }}">
              @error('mudda_date')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="म्याद" class="form-label">म्याद</label>
            <input type="text" class="form-control date-picker" id="mudda_myad" name="mudda_myad" value="{{ old('mudda_myad') }}">
        </div>
    </div>
     <div class="row">
        <div class="col-md-4 mb-3">
            <label for="सरकारी वकील" class="form-label">सरकारी वकील</label>
            <input type="text" class="form-control" id="sarkariwakil_name" name="sarkariwakil_name">
        </div>
        <div class="col-md-4 mb-3">
            <label for="फाँट" class="form-label">फाँट</label>
            <select class="form-select form-control" name="faat_name" id="faat_name">
                <option selected>-----एउटाको विकल्प रोज्नु----</option>
                <option value="ka">क</option>
                <option value="kha">ख</option>
                <option value="ga">ग</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दा पठाएको मिति" class="form-label">मुद्दा पठाएको मिति <span style="color:red">*</span></label>
            <input type="text" class="form-control date-picker" id="mudda_pathayko_date" name="mudda_pathayko_date" value="{{ old('mudda_pathayko_date') }}">
             @error('mudda_pathayko_date')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
        <div class="col-md-4 mb-3">
            <label for="कैफियत" class="form-label">कैफियत</label>
            <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3"></textarea>
        </div>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
