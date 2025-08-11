@extends('layouts.master')
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">राय दर्ता फारम</h3>
    <a href="{{ route('mudda_darta.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
    <form class="container" method="POST" action="{{ route('mudda_darta.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="मुद्दा नं." class="form-label">राय दर्ता नं.</label>
                    <input type="text" class="form-control nep-number @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="मुद्दा दर्ता मिति" class="form-label">राय दर्ता मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('mudda_date') is-invalid @enderror" id="mudda_date" name="mudda_date" value="{{ old('mudda_date') }}">
                    @error('mudda_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('anusandhan_garne_nikaye') is-invalid @enderror" id="anusandhan_garne_nikaye" name="anusandhan_garne_nikaye" value="{{ old('anusandhan_garne_nikaye') }}">
                        @error('anusandhan_garne_nikaye')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name" value={{ old('jaherwala_name') }}>
                    @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 pratiwadi-input-group mb-3">
                    <div class="d-flex gap-3 border pratiwadi-group">
                        <div class="flex-fill p-1">
                            <label for="प्रतिवादीको नाम">प्रतिवादीको नाम <span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="pratiwadi_name[]" placeholder="प्रतिवादीको नाम" value="{{ old('pratiwadi_name.0','') }}">
                            @error('pratiwadi_name.0')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-fill p-1">
                            <label for="मुद्दा स्थिति">स्थिति <span style="color:red">*</span></label>
                            <select name="mudda_sthiti[]" class="form-control">
                                <option value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                                @foreach(['फरार','पक्राउ','हाजिरि जमानीमा छोडेको','तामेली','नचल्ने','कारागार'] as $status)
                                    <option value="{{ $status }}" {{ old('mudda_sthiti.0','') == $status ? 'selected' : '' }}>
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
                <div class="col-md-3 mb-3">
                    <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
                    <input type="text" class="form-control nep-number @error('pratiwadi_number') is-invalid @enderror" id="pratiwadi_number" name="pratiwadi_number" value="{{ old('pratiwadi_number') }}" >
                    @error('pratiwadi_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('mudda_name') is-invalid @enderror" id="mudda_name" name="mudda_name" value="{{ old('mudda_name') }}" >
                    @error('mudda_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="म्याद" class="form-label">सुरु म्याद</label>
                    <input type="text" class="form-control date-picker" id="mudda_suru_myad" name="mudda_suru_myad" value="{{ old('mudda_suru_myad') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="म्याद" class="form-label">म्याद थप</label>
                    <input type="text" class="form-control date-picker" id="mudda_myad_thap" name="mudda_myad_thap" value="{{ old('mudda_myad_thap') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="म्याद" class="form-label">जम्मा दिन</label>
                    <input type="text" class="form-control" id="jamma_din" name="jamma_din" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा दर्ता मिति" class="form-label">मुद्दा विवरण <span style="color:red">*</span></label>
                     <select class="form-select form-control @error('mudda_bibran') is-invalid @enderror" name="mudda_bibran" id="mudda_bibran">
                    <option selected value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                    <option value="anusuchi_1">अनुसूची १</option>
                    <option value="anusuchi_2">अनुसूची २</option>
                </select>
                @error('mudda_bibran')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा नं." class="form-label">अदालत मुद्दा नं.</label>
                    <input type="text" class="form-control nep-number @error('adalat_mudda_number') is-invalid @enderror" id="adalat_mudda_number" name="adalat_mudda_number" value="{{ old('adalat_mudda_number') }}" >
                    @error('adalat_mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="सरकारी वकील" class="form-label">सरकारी वकील</label>
                    <input type="text" class="form-control" id="sarkariwakil_name" name="sarkariwakil_name">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="फाँट" class="form-label">फाँट</label>
                    <select class="form-select form-control" name="faat_name" id="faat_name">
                        <option value="" selected>--एउटाको विकल्प रोज्नुहोस।--</option>
                        <option value="क">क</option>
                        <option value="ख">ख</option>
                        <option value="ग">ग</option>
                    </select>
                </div>
            </div>
            <div class="row">
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
$(document).ready(function () {

  const nepToEng = s => s.replace(/[०-९]/g, d => '0123456789'.indexOf(d));
  const engToNep = s => String(s).replace(/[0-9]/g, d => '०१२३४५६७८९'[d]);

  function isValidBsDate(bsStr) {
    if (typeof bsStr !== 'string' || !bsStr) return false;
    const parts = nepToEng(bsStr).split('-');
    if (parts.length !== 3) return false;
    const [y, m, d] = parts.map(Number);
    return y >= 1970 && y <= 2100 && m >= 1 && m <= 12 && d >= 1 && d <= 32;
  }

  function bsDaysDiff(bsStart, bsEnd) {
    const ad1Str = BS2AD(bsStart); // returns YYYY-MM-DD
    const ad2Str = BS2AD(bsEnd);

    if (!ad1Str || !ad2Str) return null;

    const ad1 = new Date(ad1Str);
    const ad2 = new Date(ad2Str);

    return Math.round((ad2 - ad1) / 86400000);
  }

  function updateGap() {
    let startBS = $('#mudda_suru_myad').data("en-value") || $('#mudda_suru_myad').val();
    let endBS   = $('#mudda_myad_thap').data("en-value") || $('#mudda_myad_thap').val();

    // Convert picker output (possibly Nepali digits) to English digits
    startBS = nepToEng(startBS);
    endBS   = nepToEng(endBS);

    // Store English for calculations
    $('#mudda_suru_myad').data("en-value", startBS).val(engToNep(startBS));
    $('#mudda_myad_thap').data("en-value", endBS).val(engToNep(endBS));

    if (!startBS || !endBS) {
      $('#jamma_din').val('');
      return;
    }

    if (!isValidBsDate(startBS) || !isValidBsDate(endBS)) {
      $('#jamma_din').val('');
      return;
    }

    const [y1, m1, d1] = startBS.split('-').map(Number);
    const [y2, m2, d2] = endBS.split('-').map(Number);

    let totalMonths = (y2 - y1) * 12 + (m2 - m1);
    let days = d2 - d1;

    if (days < 0) {
      let prevMonth = m2 - 1;
      let prevYear = y2;
      if (prevMonth < 1) {
        prevMonth = 12;
        prevYear--;
      }
      const prevMonthDays = getBsMonthDays(prevYear, prevMonth);
      days += prevMonthDays;
      totalMonths--;
    }

    const years = Math.floor(totalMonths / 12);
    const months = totalMonths % 12;

    let result = '';
    if (years > 0) result += engToNep(years) + ' वर्ष ';
    if (months > 0) result += engToNep(months) + ' महिना ';
    if (days > 0 || (years === 0 && months === 0)) result += engToNep(days) + ' दिन';

    const totalDays = bsDaysDiff(startBS, endBS);
    if (totalDays !== null) {
      result += ' (' + engToNep(totalDays) + ' दिन)';
    }

    $('#jamma_din').val(result.trim());
  }

  $('#mudda_suru_myad').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 100,
    onChange: function() {
    // get the current value from the input directly
    const val = $('#mudda_suru_myad').val();
    if (typeof val === 'string' && val.trim() !== '') {
      const engDate = nepToEng(val);
      $('#mudda_suru_myad').data('en-value', engDate).val(engToNep(engDate));
      updateGap();
    }
  }
  });

  $('#mudda_myad_thap').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 100,
    onChange: function() {
    // get the current value from the input directly
    const val = $('#mudda_myad_thap').val();
    if (typeof val === 'string' && val.trim() !== '') {
    	const engDate = nepToEng(val);
		$('#mudda_myad_thap').data('en-value', engDate).val(engToNep(engDate));
		updateGap();
    }
  }
  });

});
</script>
@endpush
