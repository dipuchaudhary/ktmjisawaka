$mudda_pathayko_date = $("form .row .date-picker");
$mudda_pathayko_date.nepaliDatePicker({
  dateFormat: "%y-%m-%d",
   closeOnDateSelect: true
});

 $(document).ready(function() {
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
    group.closest('.pratiwadi-input-group').append(cloned);
});

$(document).on('click', '.removeBtn', function () {
    let allGroups = $(this).closest('.pratiwadi-input-group').find('.pratiwadi-group');

    if (allGroups.length > 1) {
        $(this).closest('.pratiwadi-group').remove();
    } else {
        alert('कम्तिमा एउटा समूह आवश्यक छ।');
    }
});
});
