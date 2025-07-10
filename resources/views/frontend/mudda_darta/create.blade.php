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
                    <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('anusandhan_garne_nikaye') is-invalid @enderror" id="anusandhan_garne_nikaye" name="anusandhan_garne_nikaye" value="{{ old('anusandhan_garne_nikaye') }}">
                        @error('anusandhan_garne_nikaye')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control custom-select2 @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name[]" value="{{ old('jaherwala_name') }}" multiple="multiple" >
                    </select>
                    @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको नाम" class="form-label">प्रतिवादीको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control custom-select2 @error('pratiwadi_name') is-invalid @enderror" id="pratiwadi_name" name="pratiwadi_name[]" value="{{ old('pratiwadi_name') }}" multiple="multiple">
                    </select>
                        @error('pratiwadi_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको संख्या" class="form-label">प्रतिवादीको संख्या</label>
                    <input type="text" class="form-control @error('pratiwadi_number') is-invalid @enderror" id="pratiwadi_number" name="pratiwadi_number" value="{{ old('pratiwadi_number') }}" >
                    @error('pratiwadi_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('mudda_name') is-invalid @enderror" id="mudda_name" name="mudda_name" value="{{ old('mudda_name') }}" >
                    @error('mudda_name')
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
                    <label for="मुद्दा दर्ता मिति" class="form-label">राय दर्ता मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('mudda_date') is-invalid @enderror" id="mudda_date" name="mudda_date" value="{{ old('mudda_date') }}">
                    @error('mudda_date')
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
                    <input type="text" class="form-control" id="jamma_din" name="jamma_din" value="{{ old('jamma_din') }}" readonly>
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
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
                    <input type="text" class="form-control @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ old('mudda_number') }}" >
                    @error('mudda_number')
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

const nepToEng = s => s.replace(/[०-९]/g, d => '०१२३४५६७८९'.indexOf(d));
const engToNep = s => String(s).replace(/[0-9]/g, d => '०१२३४५६७८९'[d]);

function isValidBsDate(bsStr) {
  if (!bsStr) return false;
  const parts = nepToEng(bsStr).split('-');
  if (parts.length !== 3) return false;
  const [y, m, d] = parts.map(Number);
  return y >= 1970 && y <= 2100 && m >= 1 && m <= 12 && d >= 1 && d <= 32;
}

function bsToAdDate(bsStr) {
  if (!isValidBsDate(bsStr)) throw new Error(`Invalid BS date: ${bsStr}`);
  const [y, m, d] = nepToEng(bsStr).split('-').map(Number);
  return calendarFunctions.getAdDateByBsDate(y, m, d);
}

function bsDaysDiff(bsStart, bsEnd) {
  const ad1 = bsToAdDate(bsStart);
  const ad2 = bsToAdDate(bsEnd);
  return Math.round((ad2 - ad1) / 86400000);
}

let startBS = '', endBS = '';

function updateGap() {
  if (!isValidBsDate(startBS) || !isValidBsDate(endBS)) return;

  // Parse the dates
  const [y1, m1, d1] = nepToEng(startBS).split('-').map(Number);
  const [y2, m2, d2] = nepToEng(endBS).split('-').map(Number);

  // Calculate total months difference
  let totalMonths = (y2 - y1) * 12 + (m2 - m1);

  // Calculate days difference
  let days = d2 - d1;

  // Adjust for negative days
  if (days < 0) {
    // Get the number of days in the previous month
    const prevMonthDays = calendarFunctions.getBsMonthDays(y2, m2 - 1 || 12);
    days += prevMonthDays;
    totalMonths--;
  }

  // Calculate years and remaining months
  const years = Math.floor(totalMonths / 12);
  const months = totalMonths % 12;

  // Format the output in Nepali
  let result = '';
  if (years > 0) result += engToNep(years) + ' वर्ष ';
  if (months > 0) result += engToNep(months) + ' महिना ';
  if (days > 0 || (years === 0 && months === 0)) result += engToNep(days) + ' दिन';

  // Also show total days in parentheses
  const totalDays = bsDaysDiff(startBS, endBS);
  result += ' (' + engToNep(totalDays) + ' दिन)';

  $('#jamma_din').val(result.trim());
}

$('#mudda_suru_myad').on('dateSelect', e => {
  startBS = e.target.value;
  updateGap();
});

$('#mudda_myad_thap').on('dateSelect', e => {
  endBS = e.target.value;
  updateGap();
});
</script>
@endpush
