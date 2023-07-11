function clickSalesView(id)
{
    loadSales(id);
    getTotalSales(id);
}

// function multipleMasseurCheckbox(id, therapist_2, checkbox_id, therapist1_id, therapis2_id, therapist_2_val, therapist_select)
// {
//     if ($('#'+checkbox_id+id).is(':checked') == false) {
//         const index = UnAvailableTherapist.indexOf(therapist_2);
//         if (index > -1) {
//             UnAvailableTherapist.splice(index, 1);
//         }

//         $('.'+therapist1_id).children('option[value="'+therapist_2+'"]').prop('disabled', false);
//         $('.'+therapist1_id).select2({
//             placeholder: "Choose Masseur 1",
//             allowClear: true
//         });

//         $('#'+therapist_select+id).attr('disabled',true);
//         $('.'+therapis2_id).children('option[value="'+therapist_2+'"]').prop('disabled', false);
//         $("."+therapis2_id).select2({
//             placeholder: "Choose Masseur 2",
//             allowClear: true
//         }).val('').trigger("change");
//         $('#'+therapist_2_val+id).val('');
//     } else {
//         $('#'+therapist_select+id).attr('disabled',false);
//     }
// }

function closeTabs(id, count)
{
    // var li = $(this).closest('li').prev('li');
    // if (id == 1) {
    //     li = $(this).closest('li').next('li');
    // }
    // console.log(li[0])
    
    // var cur_val = $('#guest_ids_val').val();

    // if ($('.appointmentNav'+id).hasClass('active')) {     
    //     if (count == 3) {
    //         alert('Unable to remove last Guest Tab.')
    //         return false;
    //     } else {
    //         $('a.appointmentNav'+li[0].id).addClass('active');
    //         $('div#appointment'+li[0].id).addClass('active');
    //     }
    // }

    // var remove = removeValue(cur_val, id);
    // remove.split(",").sort().join(",")
    // $('#guest_ids_val').val(remove);
    
    // $('.tabAppointmentTitle'+id).remove();
    // $('.tabAppointmentContent'+id).remove();
    // checkTabs();
}

function checkTabs()
{
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;
    $('#appointment_name_appointment'+firstLiId).prop('disabled', false);
    $('#social_media_appointment'+firstLiId).prop('disabled', false);
    $('#reservenow'+firstLiId).prop('disabled', false);
    $('#reservelater'+firstLiId).prop('disabled', false);
    $('#start_time_appointment_walkin'+firstLiId).prop('disabled', false);
    $('#start_time_appointment'+firstLiId).prop('disabled', false);
    
    var count = $('ul.dataTabsAppointment li').length;
    if (count == 3) {
        $(".divCloseTab").html('');
    }

    $('.appointmentTabNavSummary').removeClass('active');
    $('#summaryTab').removeClass('active');

    $('.summaryTabAppointmentLink').addClass('hidden');
    if ($('.process-appointment-btn').hasClass('hidden')) {
        $('.process-appointment-btn').removeClass('hidden');
        $('.add-appointment-btn').addClass('hidden');
    }
}