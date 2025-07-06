@extends('layouts.master')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">पुनरावेदन कारवाही विवरण फारम</h3>
    <form class="container" method="POST" action="{{ route('punarabedan.update', $punarabedan->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center mb-5">
                <div class="col-md-2 mb-3">
                    <label for="मुद्दा नं." class="form-label">मुद्दा नं.</label>
                    <input type="text" class="form-control @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ $punarabedan->mudda_number }}" >
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name" value="{{ $punarabedan->jaherwala_name }}">
                    @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="प्रतिवादीको नाम" class="form-label">प्रतिवादीको नाम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('pratiwadi_name') is-invalid @enderror" id="pratiwadi_name" name="pratiwadi_name" value="{{ $punarabedan->pratiwadi_name }}">
                    @error('pratiwadi_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2 mb-3">
                    <label for="मुद्दाको नाम" class="form-label">मुद्दाको नाम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('mudda_name') is-invalid @enderror" id="mudda_name" name="mudda_name" value="{{ $punarabedan->mudda_name }}">
                    @error('mudda_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="चलानी मिति" class="form-label">फैसला मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('faisala_date') is-invalid @enderror" id="faisala_date" name="faisala_date" value="{{ $punarabedan->faisala_date }}" >
                    @error('faisala_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="चलानी मिति" class="form-label">फैसला प्रमाणीकरण मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('faisala_pramanikaran_date') is-invalid @enderror" id="faisala_pramanikaran_date" name="faisala_pramanikaran_date" value="{{ $punarabedan->faisala_pramanikaran_date }}" >
                    @error('faisala_pramanikaran_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="चलानी मिति" class="form-label">सूचना प्राप्त मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('suchana_date') is-invalid @enderror" id="suchana_date" name="suchana_date" value="{{ $punarabedan->suchana_date }}" >
                    @error('suchana_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="फैसाला गर्ने निकाय" class="form-label">फैसला गर्ने निकाय <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('faisala_garne_nikaye') is-invalid @enderror" id="faisala_garne_nikaye" name="faisala_garne_nikaye" value="{{ $punarabedan->faisala_garne_nikaye }}">
                    @error('faisala_garne_nikaye')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-8 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">शुरु निकायबाट प्रतिवादीलाई भएको सजाय सम्बन्धी विवरण</legend>
                        <?php  ?>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="कैद" class="form-label">कैद</label>
                            <input type="text" class="form-control" id="pra_kaid" name="pra_kaid" placeholder="" value="{{ $punarabedan->pra_kaid }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="जरिवाना" class="form-label">जरिवाना</label>
                            <input type="text" class="form-control" id="pra_jariwana" name="pra_jariwana" placeholder="0" value="{{ $punarabedan->pra_jariwana }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="क्षतिपूर्ति" class="form-label">क्षतिपूर्ति</label>
                            <input type="text" class="form-control" id="pra_xatipurti" name="pra_xatipurti" placeholder="0" value="{{ $punarabedan->pra_xatipurti }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="विगो" class="form-label">विगो</label>
                            <input type="text" class="form-control" id="pra_bigo" name="pra_bigo" placeholder="0" value="{{ $punarabedan->pra_bigo }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="मूल्तवी" class="form-label">मूल्तवी</label>
                            <input type="text" class="form-control" id="pra_multabi" name="pra_multabi" placeholder="0" value="{{ $punarabedan->pra_multabi }}">
                        </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">पुनरावेदन तहको फैसला विवरण</legend>
                        <?php  ?>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="कैद" class="form-label">कैद</label>
                            <input type="text" class="form-control" id="faisala_kaid" name="faisala_kaid" placeholder="" value="{{ $punarabedan->faisala_kaid }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="जरिवाना" class="form-label">जरिवाना</label>
                            <input type="text" class="form-control" id="faisala_jariwana" name="faisala_jariwana" placeholder="0" value="{{ $punarabedan->faisala_jariwana }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="क्षतिपूर्ति" class="form-label">क्षतिपूर्ति</label>
                            <input type="text" class="form-control" id="faisala_xatipurti" name="faisala_xatipurti" placeholder="0" value="{{ $punarabedan->faisala_xatipurti }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="विगो" class="form-label">विगो</label>
                            <input type="text" class="form-control" id="faisala_bigo" name="faisala_bigo" placeholder="0" value="{{ $punarabedan->faisala_bigo }}">
                        </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-6 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">कार्यालयबाट भएको पुनरावेदन सम्बन्धी कारवाही</legend>
                        <?php  ?>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="पुवे/दो.पा" class="form-label">पुवे/दो.पा <span style="color:red">*</span></label>
                            <select class="form-select form-control" name="punarabedan" id="punarabedan">
                                <option value="" {{ empty($punarabedan->punarabedan) ? 'selected' : '' }}>--एउटाको विकल्प रोज्नुहोस।--</option>
                                <option value="गर्ने" {{ $punarabedan->punarabedan == 'गर्ने' ? 'selected' : '' }}>पुनरावेदन गर्ने</option>
                                <option value="नगर्ने" {{ $punarabedan->punarabedan == 'नगर्ने' ? 'selected' : '' }}>पुनरावेदन नगर्ने</option>
                                <option value="दो.पा" {{ $punarabedan->punarabedan == 'दो.पा' ? 'selected' : '' }}>दो.पा</option>
                            </select>
                            @error('punarabedan')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="चलानी नं." class="form-label">चलानी नं. <span style="color:red">*</span></label>
                            <input type="text" class="form-control  @error('punarabedan_challani_number') is-invalid @enderror" id="punarabedan_challani_number" name="punarabedan_challani_number"
                            value="{{ (isset($punarabedan) && $punarabedan->status == true)
                                        ? toNepaliNumber($punarabedan->punarabedan_challani_number)
                                        : toNepaliNumber($nextChallaniNumber) }}" readonly>

                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="चलानी मिति" class="form-label">चलानी मिति <span style="color:red">*</span></label>
                            <input type="text" class="form-control date-picker @error('punarabedan_date') is-invalid @enderror" id="punarabedan_date" name="punarabedan_date" value="{{ $punarabedan->punarabedan_date }}" >
                            @error('punarabedan_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">तालुक कार्यालयबाट अन्तिम निर्णय निकासा विवरण</legend>
                        <?php  ?>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="निर्णय व्यहोरा" class="form-label">निर्णय व्यहोरा </label>
                             <textarea class="form-control" id="nirnaye" name="nirnaye" rows="3">{{ $punarabedan->nirnaye }}</textarea>
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="निर्णय मिति" class="form-label">निर्णय मिति </label>
                            <input type="text" class="form-control date-picker" id="nirnaye_date" name="nirnaye_date" value="{{ $punarabedan->nirnaye_date }}" >
                        </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="सरकारी वकील" class="form-label">सरकारी वकील</label>
                    <input type="text" class="form-control @error('sarkariwakil_name') is-invalid @enderror" id="sarkariwakil_name" name="sarkariwakil_name" value="{{ $punarabedan->sarkariwakil_name }}" >
                    @error('sarkariwakil_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="3">{{ $punarabedan->kaifiyat }}</textarea>
                </div>
                <input type="hidden" name="status" value="{{ $punarabedan->status }}">
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
