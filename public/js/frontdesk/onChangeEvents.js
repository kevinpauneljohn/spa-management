// function onChangeServices(spa_id, selected_id, data_id, service_price, plus_time_price, formatted_amount, amount, plus_time)
// {
//     if (selected_id != '') {
//         $.ajax({
//             'url' : '/service-price/'+selected_id+'/'+spa_id,
//             'type' : 'GET',
//             'data' : {},
//             'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//             success: function(result){
//                 $('#'+service_price+data_id).val(result);
//                 var price = parseInt(result) + parseInt($('#'+plus_time_price+data_id).val());
    
//                 var price_converted = ReplaceNumberWithCommas(price);
//                 var price_formatted ='&#8369;'+price_converted;
    
//                 $('.'+formatted_amount+data_id).html(price_formatted);
//                 $('#'+amount+data_id).val(price);
//             }
//         });
//     } else {
//         var price = parseInt($('#'+service_price).val()) + parseInt($('#'+plus_time_price+data_id).val());
    
//         var price_converted = ReplaceNumberWithCommas(price);
//         var price_formatted ='&#8369;'+price_converted;

//         $('.'+formatted_amount+data_id).html(price_formatted);
//         $('#'+amount+data_id).val(price);
//     }

//     triggerPlusTime(spa_id, plus_time, data_id, service_price, selected_id, plus_time_price, formatted_amount, amount);
// }

// function onChangePlusTime(spa_id, selected_id, id, value_services, plus_time_price, service_price, formatted_amount, amount)
// {
//     if (selected_id != '' && value_services != '') {
//         $.ajax({
//             'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
//             'type' : 'GET',
//             'data' : {},
//             'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//             success: function(result){
//                 $('#'+plus_time_price+id).val(result);
//                 var price = parseInt(result) + parseInt($('#'+service_price+id).val());
//                 var price_converted = ReplaceNumberWithCommas(price);
//                 var price_formatted ='&#8369;'+price_converted;
    
//                 $('.'+formatted_amount+id).html(price_formatted);
//                 $('#'+amount+id).val(price);
//             }
//         });
//     } else {
//         var price = parseInt($('#'+plus_time_price+id).val()) + parseInt($('#'+service_price+id).val());
//         var price_converted = ReplaceNumberWithCommas(price);
//         var price_formatted ='&#8369;'+price_converted;

//         $('.'+formatted_amount+id).html(price_formatted);
//         $('#'+amount+id).val(price);
//     }
// }

// function triggerPlusTime(spa_id, plus_time, data_id, service_price, selected_id, plus_time_price, formatted_amount, amount)
// {
//     if (plus_time > 0) {
//         var plusTime = $('#'+plus_time+data_id).select2('data');
//         var value_plusTime = plusTime[0].id;
//         var value_services = selected_id;
    
//         if (value_plusTime != '' && value_services != '') {
//             $.ajax({
//                 'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+value_plusTime,
//                 'type' : 'GET',
//                 'data' : {},
//                 'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//                 success: function(result){
//                     $('#'+plus_time_price+data_id).val(result);
//                     var price = parseInt(result) + parseInt($('#'+service_price+data_id).val());
//                     var price_converted = ReplaceNumberWithCommas(price);
//                     var price_formatted ='&#8369;'+price_converted;
        
//                     $('.'+formatted_amount+data_id).html(price_formatted);
//                     $('#'+amount+data_id).val(price);
//                 }
//             });
//         } else {
//             $('#'+plus_time_price+data_id).val(0);
//             var price = 0;
//             var price_converted = ReplaceNumberWithCommas(price);
//             var price_formatted ='&#8369;'+price_converted;
    
//             $('.'+formatted_amount+data_id).html(price_formatted);
//             $('#'+amount+data_id).val(price);
//         }
//     }
// }

// function onChangeMasseur(data_id, id, cur_val, field, therapist_1, therapist_2)
// {
//     $('.'+therapist_1).children('option[value="' + id + '"]').attr('disabled', true);
//     $('.'+therapist_2).children('option[value="' + id + '"]').attr('disabled', true);

//     $('.'+therapist_1).children('option[value="' + cur_val + '"]').attr('disabled', false);
//     $('.'+therapist_2).children('option[value="' + cur_val + '"]').attr('disabled', false);

//     $('#'+field).val(id);

//     $('.'+therapist_1).select2({
//         placeholder: "Choose Masseur 1",
//         allowClear: true
//     });

//     $('.'+therapist_2).select2({
//         placeholder: "Choose Masseur 2",
//         allowClear: true
//     });
// }

// function onChangeRoom(data_id, id, cur_val, selectRoom, appointmentRoom)
// {
//     $('.'+selectRoom).children('option[value="' + id + '"]').attr('disabled', true);
    
//     $('.'+selectRoom).children('option[value="' + cur_val + '"]').attr('disabled', false);

//     $('#'+appointmentRoom+data_id).val(id);

//     $('.'+selectRoom).select2({
//         placeholder: "Choose Room",
//         allowClear: true
//     });
// }

// function onChangeAppointmentType(
//     val, 
//     id, 
//     spa_id, 
//     socialMediaType, 
//     requiredService, 
//     requiredTherapist, 
//     defaultOptionalService, 
//     social_media_appointment, 
//     plus_time_appointment, 
//     appointment_room, 
//     appointment,
//     walkInOptions
// ) {
//     $('#reservenow'+id).prop('checked', false);
//     $('#reservelater'+id).prop('checked', false);

//     if (val == 'Social Media') {
//         $('.'+socialMediaType).removeClass('hidden');

//         $('.'+walkInOptions).addClass('hidden');
//         $('.'+defaultOptionalService).removeClass('hidden');
//         if (!$('.'+requiredService).hasClass('hidden')) {
//             $('.'+requiredService).addClass('hidden');
//             $('.'+requiredTherapist).addClass('hidden');
//         }
//     } else if (val == 'Walk-in') {
//         $('.'+walkInOptions).removeClass('hidden');
//         $('.'+defaultOptionalService).addClass('hidden');

//         if (!$('.'+socialMediaType).hasClass('hidden')) {
//             $('.'+socialMediaType).addClass('hidden');
//             $('.'+social_media_appointment).val('');
//         }
//     } else {
//         if (!$('.'+socialMediaType).hasClass('hidden')) {
//             $('.'+socialMediaType).addClass('hidden');
//             $('.'+social_media_appointment).val('');
//         }

//         $('.'+walkInOptions).addClass('hidden');
//         $('.'+defaultOptionalService).removeClass('hidden');
//         if (!$('.'+requiredService).hasClass('hidden')) {
//             $('.'+requiredService).addClass('hidden');
//             $('.'+requiredTherapist).addClass('hidden');
//         }

//         $('#appointmentCustomCheckbox'+id).prop('checked', false);
//         $('#appointmentCustomCheckbox'+id).prop('disabled', true);
//     }
// }