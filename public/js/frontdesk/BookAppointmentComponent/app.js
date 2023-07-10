var filterPreSelectedTherapist = [];
var filterPreSelectedRoom = [];
$('#addNewAppointment').on('click', function() {
    $('#add-new-appointment-modal').modal('show');

    $('.dataTabsAppointment').html('');
    $('.appointmentContent').remove();
    $('#summaryTab').removeClass('active');
    $('.tableSummaryAppointment').html('');
    $('.total_amount_appointment').html('&#8369;0');
    $('#totalAmountToPayAppointment').val(0);

    if (!$('.add-appointment-btn').hasClass('hidden')) {
        $('.add-appointment-btn').addClass('hidden');
        $('.process-appointment-btn').removeClass('hidden');
    }
    createAppointmentForm(1, 'active', 'yes', 'no');
});

$(document).on('click', '.addNewTabs', function () {
    var liCount = $('.appointmentTab').last().attr('id');
    if (liCount == 1) {
        $(".isCloseTab"+liCount).append('<button type="button" class="closeTabs pointer" id="'+liCount+'">×</button></div>');
    }

    var id = parseInt(liCount) + 1;
    var cur_val = $('#guest_ids_val').val();
    $('#guest_ids_val').val(cur_val + "," + id);

    $('.process-appointment-btn').text('Process').prop('disabled', false);
    createAppointmentForm(id, 'inactive', 'no', 'yes');
});

$(document).on('click', '.appointmentTabNav', function () {
    // var id = this.id;
    // $('.appointmentContent').removeClass('active');
    // $('.tabAppointmentContent'+id).addClass('active');
});

$(document).on('click', '.closeTabs', function () {
    var id = this.id;
    var count = $('ul.dataTabsAppointment li').length;

    // closeTabs(id, count);
    var li = $(this).closest('li').prev('li');
    if (id == 1) {
        li = $(this).closest('li').next('li');
    }

    var cur_val = $('#guest_ids_val').val();
    var therapist_1 = $('#appointment_masseur1'+id+'_id').val();
    var therapist_2 = $('#appointment_masseur2'+id+'_id').val();
    var room = $('#appointment_room_id'+id).val();
    
    if (room !== null || room !== '') {
        filterPreSelectedRoom = $.grep(filterPreSelectedRoom, function(element){
            return element !== room;
        }); 

        $('.select-appointment-room').children('option[value="' + room + '"]').attr('disabled', false);
        $('.select-appointment-room').select2({
            placeholder: "Choose Room",
            allowClear: false
        });
    } 

    if (therapist_1 !== null || therapist_1 !== '') {
        filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
            return element !== therapist_1;
        }); 

        $('.select-appointment-masseur1').children('option[value="' + therapist_1 + '"]').attr('disabled', false);
        $('.select-appointment-masseur2').children('option[value="' + therapist_1 + '"]').attr('disabled', false);

        $('.select-appointment-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: false
        });

        $('.select-appointment-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: false
        });
    } 

    if (therapist_2 !== null || therapist_2 !== '') {
        filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
            return element !== therapist_2;
        }); 

        $('.select-appointment-masseur2').children('option[value="' + therapist_2 + '"]').attr('disabled', false);
        $('.select-appointment-masseur1').children('option[value="' + therapist_2 + '"]').attr('disabled', false);

        $('.select-appointment-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: false
        });

        $('.select-appointment-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: false
        });
    }

    if ($('.appointmentNav'+id).hasClass('active')) {     
        if (count == 3) {
            alert('Unable to remove last Guest Tab.')
            return false;
        } else {
            $('a.appointmentNav'+li[0].id).addClass('active');
            $('div#appointment'+li[0].id).addClass('active');
        }
    }

    var remove = removeValue(cur_val, id);
    remove.split(",").sort().join(",")
    $('#guest_ids_val').val(remove);
    
    $('.tabAppointmentTitle'+id).remove();
    $('.tabAppointmentContent'+id).remove();
    checkTabs();
});

$(document).on('input paste', '.filterClientAppointment', function () {
    var id = this.id;
    var val = $(this).val();
    var spa_id = $('#spa_id_val').val();

    filterClient(id, val, spa_id)
});

$(document).on('change', '.reserveOption', function() {
    var id = $(this).data("id");
    var value = $(this).data("value");
    var spa_id = $('#spa_id_val').val();

    if(this.checked) {
        if (value == 'reserved_now') {
            $('.start_time_appointment').val(("mm/dd/yyyy --:-- --"));
            $('.reserveNow').prop('checked', true);
            $('.reserveLater').prop('checked', false);

            $('.defaultOptionalService').addClass('hidden');
            $('.requiredService').removeClass('hidden');
        } else if (value == 'reserved_later') {
            $('.start_time_appointment_walkin').val(("mm/dd/yyyy --:-- --"));
            $('.reserveNow').prop('checked', false);
            $('.reserveLater').prop('checked', true);

            $('.defaultOptionalService').removeClass('hidden');
            $('.requiredService').addClass('hidden');
            $('.walkInHiddenDiv').addClass('hidden');
        }
    } else {
        $('.reserveNow').prop('checked', false);
        $('.reserveLater').prop('checked', false);
        $('.defaultOptionalService').addClass('hidden');
        $('.requiredService').addClass('hidden');
        $('.walkInHiddenDiv').addClass('hidden');
    }
});

$(document).on('change', '.select-preparation-time', function() {
    var val = $(this).val();
    var id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var currentDate = new Date();
    var futureDate = new Date(currentDate.getTime() + val * 60000);
    var convertedTime = convertTime(futureDate);
    
    if (val.length > 0) {
        $('.start_time_appointment_walkin').val(convertedTime);
        $('.walkInHiddenDiv'+id).removeClass('hidden');
        getPosTherapistApi(spa_id, val, id);
        getPosRoomApi(spa_id, val, id);
        getServicesAppointment(spa_id, id);
        getPlusTime(id);
    } else {
        $('.walkInHiddenDiv'+id).addClass('hidden');
        getPosTherapistApi(spa_id, val, id);
        getPosRoomApi(spa_id, val, id);
        getServicesAppointment(spa_id, id);
        getPlusTime(id);
    }

    if ($('.appointment_room_div'+id).hasClass('col-md-6')) {
        $('.appointment_room_div'+id).removeClass('col-md-6');
        $('.appointment_room_div'+id).addClass('col-md-4');
    }

    if ($('.appointment_masseur1_div'+id).hasClass('col-md-6')) {
        $('.appointment_masseur1_div'+id).removeClass('col-md-6');
        $('.appointment_masseur1_div'+id).addClass('col-md-4');
        $('.appointment_masseur1_div'+id).addClass('hidden');
    } else {
        $('.appointment_masseur1_div'+id).addClass('hidden');
    }
    $('.appointment_masseur2_div'+id).addClass('hidden');
});

function convertTime(timeString) {
    const date = new Date(timeString);
    const year = String(date.getFullYear());
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
  
    const convertedTime = `${year}-${month}-${day}T${hours}:${minutes}`;
    return convertedTime;
}

$(document).on('change', '.select-services-walkin-appointment', function () {
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    var data_id = $(this).data("id");
    $('#appointment_app_services_id'+data_id).val(selected_id);
    $('#price_appointment_walkin'+data_id).val(0);

    getServiceById(selected_id, data_id);
    onChangeServices(
        spa_id, 
        selected_id, 
        data_id, 
        'price_appointment_walkin', 
        'appointment_plus_time_price', 
        'total_amount_appointment', 
        'appointment_total_service_price', 
        'plus_time_appointment'
    );
});

$(document).on('select2:close', '.select-services-walkin-appointment', function (e) {
    var id = $(this).data("id");
    var time = $('#preparation_time'+id).val();
    var evt = "scroll.select2";
    $(e.target).parents().off(evt);
    $(window).off(evt);

    if ($('.appointment_room_div'+id).hasClass('col-md-6')) {
        $('.appointment_room_div'+id).addClass('col-md-4');
        $('.appointment_room_div'+id).removeClass('col-md-6');
    }

    if ($('.appointment_masseur1_div'+id).hasClass('col-md-6')) {
        $('.appointment_masseur1_div'+id).addClass('col-md-4');
        $('.appointment_masseur1_div'+id).removeClass('col-md-6');
    }

    if (!$('.appointment_masseur2_div'+id).hasClass('hidden')) {
        $('.appointment_masseur2_div'+id).addClass('hidden');
    }

    $('.appointment_masseur1_div'+id).addClass('hidden');
    getPosTherapistApi($('#spa_id_val').val(), time, id);
});

function onChangeServices(spa_id, selected_id, data_id, service_price, plus_time_price, formatted_amount, amount, plus_time)
{
    if (selected_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_id+'/'+spa_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#'+service_price+data_id).val(result);
                var price = parseInt(result) + parseInt($('#'+plus_time_price+data_id).val());
    
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.'+formatted_amount+data_id).html(price_formatted);
                $('#'+amount+data_id).val(price);
            }
        });
    } else {
        var price = parseInt($('#'+service_price).val()) + parseInt($('#'+plus_time_price+data_id).val());
    
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.'+formatted_amount+data_id).html(price_formatted);
        $('#'+amount+data_id).val(price);
    }

    triggerPlusTime(spa_id, plus_time, data_id, service_price, selected_id, plus_time_price, formatted_amount, amount);
}

function onChangePlusTime(spa_id, selected_id, id, value_services, plus_time_price, service_price, formatted_amount, amount)
{
    if (selected_id != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#'+plus_time_price+id).val(result);
                var price = parseInt(result) + parseInt($('#'+service_price+id).val());
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.'+formatted_amount+id).html(price_formatted);
                $('#'+amount+id).val(price);
            }
        });
    } else {
        var price = parseInt($('#'+plus_time_price+id).val()) + parseInt($('#'+service_price+id).val());
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.'+formatted_amount+id).html(price_formatted);
        $('#'+amount+id).val(price);
    }
}

function triggerPlusTime(spa_id, plus_time, data_id, service_price, selected_id, plus_time_price, formatted_amount, amount)
{
    if (plus_time > 0) {
        var plusTime = $('#'+plus_time+data_id).select2('data');
        var value_plusTime = plusTime[0].id;
        var value_services = selected_id;
    
        if (value_plusTime != '' && value_services != '') {
            $.ajax({
                'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+value_plusTime,
                'type' : 'GET',
                'data' : {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(result){
                    $('#'+plus_time_price+data_id).val(result);
                    var price = parseInt(result) + parseInt($('#'+service_price+data_id).val());
                    var price_converted = ReplaceNumberWithCommas(price);
                    var price_formatted ='&#8369;'+price_converted;
        
                    $('.'+formatted_amount+data_id).html(price_formatted);
                    $('#'+amount+data_id).val(price);
                }
            });
        } else {
            $('#'+plus_time_price+data_id).val(0);
            var price = 0;
            var price_converted = ReplaceNumberWithCommas(price);
            var price_formatted ='&#8369;'+price_converted;
    
            $('.'+formatted_amount+data_id).html(price_formatted);
            $('#'+amount+data_id).val(price);
        }
    }
}

$(document).on('change', '.select-appointment-masseur1', function () {
    var select_type = $(this).data("select");
    var data_id = $(this).data("id");
    var selected = $(this).select2('data');
    var id = selected[0].id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#appointment_masseur1'+data_id+'_id').val();
    if (cur_val !== id) {   
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeMasseur(data_id, id, cur_val, 'appointment_masseur1'+data_id+'_id', 'select-appointment-masseur1', 'select-appointment-masseur2'); 
    }

});

$(document).on('change', '.select-appointment-masseur2', function () {
    var select_type = $(this).data("select");
    var data_id = $(this).data("id");
    var selected = $(this).select2('data');
    var id = selected[0].id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#appointment_masseur2'+data_id+'_id').val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeMasseur(data_id, id, cur_val, 'appointment_masseur2'+data_id+'_id', 'select-appointment-masseur1', 'select-appointment-masseur2');
    }
});

function onChangeMasseur(data_id, id, cur_val, field, therapist_1, therapist_2)
{
    $('.'+therapist_1).children('option[value="' + id + '"]').attr('disabled', true);
    $('.'+therapist_2).children('option[value="' + id + '"]').attr('disabled', true);

    $('.'+therapist_1).children('option[value="' + cur_val + '"]').attr('disabled', false);
    $('.'+therapist_2).children('option[value="' + cur_val + '"]').attr('disabled', false);

    $('#'+field).val(id);

    $('.'+therapist_1).select2({
        placeholder: "Choose Masseur 1",
        allowClear: false
    });

    $('.'+therapist_2).select2({
        placeholder: "Choose Masseur 2",
        allowClear: false
    });
}

$(document).on('change', '.select-appointment-room', function () {
    var select_type = $(this).data("select");
    var selected = $(this).select2('data');
    var id = selected[0].id;

    filterPreSelectedRoom.push(id);
    var data_id = $(this).data("id");
    var cur_val = $('#appointment_room_id'+data_id).val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedRoom = $.grep(filterPreSelectedRoom, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeRoom(data_id, id, cur_val, 'select-appointment-room', 'appointment_room_id')
    }
});

function onChangeRoom(data_id, id, cur_val, selectRoom, appointmentRoom)
{
    $('.'+selectRoom).children('option[value="' + id + '"]').attr('disabled', true);
    
    $('.'+selectRoom).children('option[value="' + cur_val + '"]').attr('disabled', false);

    $('#'+appointmentRoom+data_id).val(id);

    $('.'+selectRoom).select2({
        placeholder: "Choose Room",
        allowClear: false
    });
}