@extends('layouts.master')
@section('content')
  <form class="container mt-5 p-10" method="POST" action="#">
    @csrf
    <h2 class="mb-4">मुल दर्ता फारम</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय</label>
            <input type="text" class="form-control" id="anusandhan_garne_nikale" name="anusandhan_garne_nikale" class="@error('anusandhan_garne_nikale') is-invalid @enderror">
            @error('anusandhan_garne_nikale')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
            <input type="text" class="form-control" id="mudda_number" name="mudda_number" >
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम</label>
            <input type="text" class="form-control" id="mudda_name" name="mudda_name">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम</label>
            <input type="text" class="form-control" id="jaherwala_name" name="jaherwala_name">
        </div>
        <div class="col-md-4 mb-3">
            <label for="प्रतिवादीको नाम" class="form-label">प्रतिवादीको नाम</label>
            <input type="text" class="form-control" id="pratiwadi_name" name="pratiwadi_name">
        </div>
        <div class="col-md-4 mb-3">
            <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
            <input type="number" class="form-control" id="pratiwadi_number" name="pratiwadi_number">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
        <label for="मुद्दाको स्थिति" class="form-label">मुद्दाको स्थिति</label>
        <select class="form-select form-control" name="mudda_stithi" id="mudda_stithi">
            <option selected>-----एउटाको विकल्प रोज्नु----</option>
            <option value="farar">फरार</option>
            <option value="pakrau">पक्राउ</option>
            <option value="hajiri_jawanima_xodeko">हाजिरि जमानीमा छोडेको</option>
        </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="मुद्दा दर्ता मिति" class="form-label">मुद्दा दर्ता मिति</label>
            <input type="text" class="form-control date-picker" id="mudda_date" name="mudda_date">
        </div>
        <div class="col-md-4 mb-3">
            <label for="म्याद" class="form-label">म्याद</label>
            <input type="text" class="form-control date-picker" id="mudda_myad" name="mudda_myad">
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
            <label for="मुद्दा पठाएको मिति" class="form-label">मुद्दा पठाएको मिति</label>
            <input type="text" class="form-control date-picker" id="mudda_pathayko_date" name="mudda_pathayko_date" value="">
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
