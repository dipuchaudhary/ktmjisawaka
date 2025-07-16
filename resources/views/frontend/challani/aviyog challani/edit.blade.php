@extends('layouts.master')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container mt-5">
    <h3 class="mb-5">अभियोग चलानी फारम</h3>
    <a href="{{ route('aviyog_challani.index') }}" class="btn btn-success mb-5">
       <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
    </a>
    <form class="container" method="POST" action="{{ route('aviyog_challani.update',$aviyogchallani->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center mb-5">
                <div class="col-auto">
                    <label for="चलानी नं." class="col-form-label">चलानी नं.</label>
                </div>
                <div class="col-auto">
                    <input type="text" class="input-group-text mb-2" id="challani_number" name="challani_number"
                    value="{{ (isset($aviyogchallani) && $aviyogchallani->status == true)
                        ? toNepaliNumber($aviyogchallani->challani_number)
                        : toNepaliNumber($nextChallaniNumber) }}
                    " readonly>
                </div>
            </div>
            <div class="row">
              <div class="col-md-3 mb-3">
                    <label for="चलानी मिति" class="form-label">चलानी मिति <span style="color:red">*</span></label>
                    <input type="text" class="form-control date-picker @error('challani_date') is-invalid @enderror" id="challani_date" name="challani_date" value="{{ $aviyogchallani->challani_date }}" >
                    @error('challani_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="जाहेरवालाको नाम" class="form-label">जाहेरवालाको नाम <span style="color:red">*</span></label>
                    <select type="text" class="form-control @error('jaherwala_name') is-invalid @enderror" id="jaherwala_name" name="jaherwala_name[]" multiple="multiple">
                    @if (!empty($aviyogchallani->jaherwala_name))
                        @foreach (explode(',', $aviyogchallani->jaherwala_name) as $value)
                            <option value="{{ $value }}" selected>{{ $value }}</option>
                        @endforeach
                        @endif
                    </select>
                        @error('jaherwala_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 pratiwadi-input-group mb-3">
                    @foreach(json_decode($aviyogchallani->pratiwadi_name, true) as $index => $pratiwadi)
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
                            <div class="flex-fill p-1 d-flex align-items-end gap-2 align-center ">
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
                    <label for="मुद्दाको किसिम" class="form-label">मुद्दाको किसिम <span style="color:red">*</span></label>
                    <input type="text" class="form-control @error('mudda_name') is-invalid @enderror" id="mudda_name" name="mudda_name" value="{{ $aviyogchallani->mudda_name }}" readonly>
                    @error('mudda_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="मुद्दा नं." class="form-label">राय दर्ता नं.</label>
                    <input type="text" class="form-control nep-number @error('mudda_number') is-invalid @enderror" id="mudda_number" name="mudda_number" value="{{ $aviyogchallani->mudda_number }}" readonly>
                    @error('mudda_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <fieldset class="border p-3 rounded">
                        <legend class="float-none w-auto px-2" style="font-size: 1.1rem;">लिङ्ग</legend>
                        <?php $gender = json_decode($aviyogchallani->gender, true); ?>
                        <div class="d-flex gap-3">
                        <div class="flex-fill text-center p-1">
                            <label for="male_count" class="form-label">पुरुष संख्या</label>
                            <input type="text" class="form-control nep-number" id="male_count" name="gender[male]" placeholder="0" value="{{ $gender['male'] ?? null }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="female_count" class="form-label">महिला संख्या</label>
                            <input type="text" class="form-control nep-number" id="female_count" name="gender[female]" placeholder="0" value="{{ $gender['female'] ?? null }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="child_count" class="form-label">नाबालक संख्या</label>
                            <input type="text" class="form-control nep-number" id="child_count" name="gender[child]" placeholder="0" value="{{ $gender['child'] ?? null }}">
                        </div>
                        <div class="flex-fill text-center p-1">
                            <label for="other_count" class="form-label">अन्य संख्या</label>
                            <input type="text" class="form-control nep-number" id="other_count" name="gender[other]" placeholder="0" value="{{ $gender['other'] ?? null }}">
                        </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="अनुसन्धान गर्ने निकाय" class="form-label">अनुसन्धान गर्ने निकाय </label>
                    <input type="text" class="form-control @error('anusandhan_garne_nikaye') is-invalid @enderror" id="anusandhan_garne_nikaye" name="anusandhan_garne_nikaye" value="{{ $aviyogchallani->anusandhan_garne_nikaye }}">
                    @error('anusandhan_garne_nikaye')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="अनुसन्धान गर्ने निकाय" class="form-label">अभियोग/निर्णय (पेश भएको कार्यालय) </label>
                    <input type="text" class="form-control @error('pesh_karyala') is-invalid @enderror" id="pesh_karyala" name="pesh_karyala" value="{{ $aviyogchallani->pesh_karyala }}" placeholder="पेश भएको कार्यालय">
                    @error('pesh_karyala')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md- mb-3">
                    <label for="सरकारी वकील" class="form-label">सरकारी वकील</label>
                    <input type="text" class="form-control @error('sarkariwakil_name') is-invalid @enderror" id="sarkariwakil_name" name="sarkariwakil_name" value="{{ $aviyogchallani->sarkariwakil_name }}" >
                    @error('sarkariwakil_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="फाँट" class="form-label">फाँट</label>
                    <select class="form-select form-control" name="faat_name" id="faat_name">
                        <option value="" {{ empty($aviyogchallani->faat_name) ? 'selected' : '' }}>--एउटाको विकल्प रोज्नुहोस।--</option>
                        <option value="क" {{ $aviyogchallani->faat_name == 'क' ? 'selected' : '' }}>क</option>
                        <option value="ख" {{ $aviyogchallani->faat_name == 'ख' ? 'selected' : '' }}>ख</option>
                        <option value="ग" {{ $aviyogchallani->faat_name == 'ग' ? 'selected' : '' }}>ग</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="कैफियत" class="form-label">कैफियत</label>
                    <textarea class="form-control" id="kaifiyat" name="kaifiyat" rows="2">{{ $aviyogchallani->kaifiyat}}</textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="अभियोग फाइल" class="form-label">अभियोग अपलोड भएको मितिः</label>
                    <input class="form-control date-picker @error('upload_date') is-invalid @enderror" type="text" id="upload_date" name="upload_date" value="{{ $aviyogchallani->upload_date }}">
                    @error('upload_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
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
});

</script>
@endpush
