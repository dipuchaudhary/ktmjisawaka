$mudda_pathayko_date = $("form .row .date-picker");
$mudda_pathayko_date.nepaliDatePicker({
  dateFormat: "%y-%m-%d",
   closeOnDateSelect: true
});

$(document).ready(function() {
function updatePratiwadiCount() {
        const engToNep = s => String(s).replace(/[0-9]/g, d => '०१२३४५६७८९'[d]);
        const count = $('.pratiwadi-input-group .pratiwadi-group').length;
        $("#pratiwadi_number").val(engToNep(count));
        $("#pratiwadi_number").prop("readonly",true);
    }
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
    updatePratiwadiCount();
});

$(document).on('click', '.removeBtn', function (e) {
    let allGroups = $(this).closest('.pratiwadi-input-group').find('.pratiwadi-group');

    if (allGroups.length > 1) {
        $(this).closest('.pratiwadi-group').remove();
         updatePratiwadiCount();
    } else {
        e.preventDefault();
    }
});
// Initial count on page load
updatePratiwadiCount();
});
