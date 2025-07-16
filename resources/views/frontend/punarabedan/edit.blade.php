@extends('layouts.master')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">पुनरावेदन कारवाही विवरण फारम</h3>
    <a href="{{ route('punarabedan.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
    <form class="container" method="POST" action="{{ route('punarabedan.update', $punarabedan->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center mb-5">
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा नं." class="form-label">राय दर्ता नं.</label>
                    <input type="text" class="form-control @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ $punarabedan->mudda_number }}" readonly>
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name[]" multiple="multiple" readonly>
                    @if (!empty($punarabedan->jaherwala_name))
                        @foreach (explode(',', $punarabedan->jaherwala_name) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 pratiwadi-input-group">
                    @foreach(json_decode($punarabedan->pratiwadi_name, true) as $index => $pratiwadi)
                        <div class="d-flex gap-3 border p-2 pratiwadi-group">
                            <div class="flex-fill p-1">
                                 @if ($loop->first)
                                <label>प्रतिवादीको नाम <span style="color:red">*</span></label>
                                @endif
                                <input type="text" class="form-control" name="pratiwadi_name[]" id="pratiwadi_name" placeholder="प्रतिवादीको नाम"
                                    value="{{ $pratiwadi['name'] }}">

                                @error("pratiwadi_name.$index")
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex-fill p-1">
                                @if ($loop->first)
                                <label>मुद्दा स्थिति <span style="color:red">*</span></label>
                                @endif
                                <select name="mudda_sthiti[]" class="form-control" id="mudda_sthiti">
                                    <option value="">--एउटाको विकल्प रोज्नुहोस।--</option>
                                    @foreach(['फरार','पक्राउ','हाजिरि जमानीमा छोडेको','तामेली','नचल्ने'] as $status)
                                        <option value="{{ $status }}"
                                            {{ $pratiwadi['status'] == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("mudda_sthiti.$index")
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex-fill p-1 d-flex align-items-end gap-2 align-center">
                                <button type="button" class="btn btn-success btn-sm addBtn edit-addbtn" style="margin-bottom:17px; margin-right:2px;">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm removeBtn edit-removebtn" style="margin-bottom:17px;">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="मुद्दाको नाम" class="form-label">मुद्दाको नाम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('mudda_name') is-invalid @enderror" id="mudda_name" name="mudda_name" value="{{ $punarabedan->mudda_name }}" readonly>
                    @error('mudda_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="चलानी मिति" class="form-label">फैसला मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('faisala_date') is-invalid @enderror" id="faisala_date" name="faisala_date" value="{{ $punarabedan->faisala_date }}" >
                    @error('faisala_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="चलानी मिति" class="form-label">फैसला प्रमाणीकरण मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('faisala_pramanikaran_date') is-invalid @enderror" id="faisala_pramanikaran_date" name="faisala_pramanikaran_date" value="{{ $punarabedan->faisala_pramanikaran_date }}" >
                    @error('faisala_pramanikaran_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
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
                            <input type="text" class="form-control nep-number" id="pra_jariwana" name="pra_jariwana" placeholder="0" value="{{ $punarabedan->pra_jariwana }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="क्षतिपूर्ति" class="form-label">क्षतिपूर्ति</label>
                            <input type="text" class="form-control nep-number" id="pra_xatipurti" name="pra_xatipurti" placeholder="0" value="{{ $punarabedan->pra_xatipurti }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="विगो" class="form-label">विगो</label>
                            <input type="text" class="form-control nep-number" id="pra_bigo" name="pra_bigo" placeholder="0" value="{{ $punarabedan->pra_bigo }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="मूल्तवी" class="form-label">मूल्तवी</label>
                            <input type="text" class="form-control" id="pra_multabi" name="pra_multabi" placeholder="" value="{{ $punarabedan->pra_multabi }}">
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
                            <input type="text" class="form-control nep-number" id="faisala_jariwana" name="faisala_jariwana" placeholder="0" value="{{ $punarabedan->faisala_jariwana }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="क्षतिपूर्ति" class="form-label">क्षतिपूर्ति</label>
                            <input type="text" class="form-control nep-number" id="faisala_xatipurti" name="faisala_xatipurti" placeholder="0" value="{{ $punarabedan->faisala_xatipurti }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="विगो" class="form-label">विगो</label>
                            <input type="text" class="form-control nep-number" id="faisala_bigo" name="faisala_bigo" placeholder="0" value="{{ $punarabedan->faisala_bigo }}">
                        </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-6 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">कार्यालयबाट भएको पुनरावेदन सम्बन्धी कारवाही</legend>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="पुवे/दो.पा" class="form-label">पुवे/दो.पा <span style="color:red">*</span></label>
                            <select class="form-select form-control" name="punarabedan" id="punarabedan">
                                <option value="" {{ empty($punarabedan->punarabedan) ? 'selected' : '' }}>--एउटाको विकल्प रोज्नुहोस।--</option>
                                <option value="गर्ने" {{ $punarabedan->punarabedan == 'गर्ने' ? 'selected' : '' }}>पुनरावेदन गर्ने</option>
                                <option value="नगर्ने" {{ $punarabedan->punarabedan == 'नगर्ने' ? 'selected' : '' }}>पुनरावेदन नगर्ने</option>
                                <option value="दो.पा" {{ $punarabedan->punarabedan == 'दो.पा' ? 'selected' : '' }}>दो.पा</option>
                                <option value="सफल" {{ $punarabedan->punarabedan == 'सफल' ? 'selected' : '' }}>सफल</option>
                            </select>
                            @error('punarabedan')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-fill text-center p-1 " id="challani-num-div">
                            <label for="चलानी नं." class="form-label">चलानी नं. <span style="color:red">*</span></label>
                            <input type="text" class="form-control  @error('punarabedan_challani_number') is-invalid @enderror" id="punarabedan_challani_number" name="punarabedan_challani_number"
                            value="{{ (isset($punarabedan) && $punarabedan->status == true)
                                        ? toNepaliNumber($punarabedan->punarabedan_challani_number)
                                        : toNepaliNumber($nextChallaniNumber) }}" readonly>

                        </div>
                        <div class="flex-fill text-center p-1 " id="challani-date-div">
                            <label for="चलानी मिति" class="form-label">चलानी मिति <span style="color:red">*</span></label>
                            <input type="text" class="form-control date-picker @error('punarabedan_date') is-invalid @enderror" id="punarabedan_date" name="punarabedan_date" value="{{ $punarabedan->punarabedan_date }}" >
                            @error('punarabedan_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                        <div class="row" id="pesh_karyala-div">
                        <div class="text-center p-1">
                            <label for="पेश भएको कार्यालय" class="form-label">पेश भएको कार्यालय </label>
                            <input type="text" class="form-control  @error('punarabedan_pesh_karyala') is-invalid @enderror" id="punarabedan_pesh_karyala" name="punarabedan_pesh_karyala" value="{{ $punarabedan->punarabedan_pesh_karyala }}" >
                            @error('punarabedan_pesh_karyala')
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

    $('#pratiwadi_name, #jaherwala_name,#mudda_sthiti')
    .prop('readonly', true)
    .on('focus mousedown keypress paste', function (e) {
        e.preventDefault();
        $(this).blur();
    });
    $('.edit-addbtn,.edit-removebtn').on('click',function(e) {
        e.preventDefault();
        $(this).prop('disabled',true);
    });
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

    var challani_number = $('#punarabedan_challani_number').val();
    function toggle_punarabedan() {
        var selectedValue = $('#punarabedan').val();
        if (selectedValue == 'सफल') {
            $('#challani-num-div').hide();
            $('#challani-date-div').hide();
            $('#pesh_karyala-div').hide();
            $('#punarabedan_challani_number').val('');
            $('#punarabedan_challani_date').val('');
        } else {
            $('#challani-num-div').show();
            $('#challani-date-div').show();
             $('#pesh_karyala-div').show();
            $('#punarabedan_challani_number').val(challani_number);
        }
    }
    toggle_punarabedan();
    $('#punarabedan').change(function() {
        toggle_punarabedan();

    });
});

</script>
@endpush
