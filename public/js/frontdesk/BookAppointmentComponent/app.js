var filterPreSelectedTherapist = [];
var filterPreSelectedRoom = [];
var searchFilter = [];
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
    var client_array = $('#fullname_array'+id).val();
    if (client_array !== null || client_array !== '') {
        searchFilter = $.grep(searchFilter, function(element){
            return element !== client_array;
        });
    } 

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
            $('.PreparationTimeDiv').removeClass('hidden');
        } else if (value == 'reserved_later') {
            $('.start_time_appointment_walkin').val(("mm/dd/yyyy --:-- --"));
            $('.reserveNow').prop('checked', false);
            $('.reserveLater').prop('checked', true);

            $('.defaultOptionalService').removeClass('hidden');
            $('.requiredService').addClass('hidden');
            $('.walkInHiddenDiv').addClass('hidden');

            $('.PreparationTimeDiv').addClass('hidden');
            $('.ServiceAppointmentDiv').addClass('hidden');
            $('.PlusTimeAppointmentDiv').addClass('hidden');
            $('.AppointmentRoomDiv').addClass('hidden');
            $('.AppointmentMasseurDiv1').addClass('hidden');
            $('.AppointmentMasseurDiv2').addClass('hidden');

            emptySelectedOptionsPos();
        }
    } else {
        $('.reserveNow').prop('checked', false);
        $('.reserveLater').prop('checked', false);
        $('.defaultOptionalService').addClass('hidden');
        $('.requiredService').addClass('hidden');
        $('.walkInHiddenDiv').addClass('hidden');

        $('.PreparationTimeDiv').addClass('hidden');
        $('.ServiceAppointmentDiv').addClass('hidden');
        $('.PlusTimeAppointmentDiv').addClass('hidden');
        $('.AppointmentRoomDiv').addClass('hidden');
        $('.AppointmentMasseurDiv1').addClass('hidden');
        $('.AppointmentMasseurDiv2').addClass('hidden');

        emptySelectedOptionsPos();
    }
});

function emptySelectedOptionsPos()
{
    $('.select-preparation-time').val('').trigger('change');
    $('.select-services-walkin-appointment').val('').trigger('change');
    $('.select-appointment-plus_time').val('').trigger('change');
    $('.select-appointment-room').val('').trigger('change');
    $('.select-appointment-masseur1').val('').trigger('change');
    $('.select-appointment-masseur2').val('').trigger('change');

    filterPreSelectedTherapist = [];
    filterPreSelectedRoom = [];
}

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
        $('.appointment_room_div'+id).removeClass('hidden');
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

$(document).on('change', '.appointment_name_appointment', function () {
    var id = $(this).data("id");
    var val = $(this).val();
    var spa_id = $('#spa_id_val').val();

    $('.appointment_name_appointment').val(val);
    onChangeAppointmentType(
        val, 
        id, 
        spa_id, 
        'socialMediaType', 
        'requiredService', 
        'requiredTherapist', 
        'defaultOptionalService', 
        'social_media_appointment', 
        'plus_time_appointment', 
        'appointment_room', 
        'appointment',
        'walkInOptions'
    );

    $('#error-appointment_name_appointment'+id).addClass('hidden');
    $('#error-appointment_name_appointment'+id).text('');
});

$(document).on('change', '.social_media_appointment', function () {
    var id = $(this).data("id");
    var val = $(this).val();
    
    $('.social_media_appointment').val(val);
});

function onChangeAppointmentType(
    val, 
    id, 
    spa_id, 
    socialMediaType, 
    requiredService, 
    requiredTherapist, 
    defaultOptionalService, 
    social_media_appointment, 
    plus_time_appointment, 
    appointment_room, 
    appointment,
    walkInOptions
) {
    $('#reservenow'+id).prop('checked', false);
    $('#reservelater'+id).prop('checked', false);

    if (val == 'Social Media') {
        $('.'+socialMediaType).removeClass('hidden');

        $('.'+walkInOptions).addClass('hidden');
        $('.'+defaultOptionalService).removeClass('hidden');
        if (!$('.'+requiredService).hasClass('hidden')) {
            $('.'+requiredService).addClass('hidden');
            $('.'+requiredTherapist).addClass('hidden');
        }
    } else if (val == 'Walk-in') {
        $('.'+walkInOptions).removeClass('hidden');
        $('.'+defaultOptionalService).addClass('hidden');

        if (!$('.'+socialMediaType).hasClass('hidden')) {
            $('.'+socialMediaType).addClass('hidden');
            $('.'+social_media_appointment).val('');
        }
    } else {
        if (!$('.'+socialMediaType).hasClass('hidden')) {
            $('.'+socialMediaType).addClass('hidden');
            $('.'+social_media_appointment).val('');
        }

        $('.'+walkInOptions).addClass('hidden');
        $('.'+defaultOptionalService).removeClass('hidden');
        if (!$('.'+requiredService).hasClass('hidden')) {
            $('.'+requiredService).addClass('hidden');
            $('.'+requiredTherapist).addClass('hidden');
        }

        $('#appointmentCustomCheckbox'+id).prop('checked', false);
        $('#appointmentCustomCheckbox'+id).prop('disabled', true);
    }
}

$('.process-appointment-btn').on('click', function() {
    $('.divCloseTab').addClass('hidden');
    var cur_val = $('#guest_ids_val').val();
    const data = cur_val.split(',');

    $('.process-appointment-btn').text('Processing...').prop('disabled', true);
    setTimeout(function() { 
        processAppointment(data);
    }, 1000);
});

$(document).on('click', '.appointmentTabNav', function () {
    var id = $(this).data("id");
    var ids = this.id;
    $('.appointmentContent').removeClass('active');
    $('.tabAppointmentContent'+ids).addClass('active');
    $('.process-appointment-btn').text('Process').prop('disabled', false);
    appointmentSummary(id);
});

$('.add-appointment-btn').on('click', function() {
    submitAppointment();
});

function processAppointment(data)
{
    if (data.length > 0) {
        appointment = [];
        var total_amount = 0;
        var date = new Date();
        var newDate = subtractHours(date, 1);
        var validation = false;
        $.each(data, function (key, value) {
            var client_type = $('#client_type_appointment'+value).val();
            var firstname = $('#first_name_appointment'+value).val();
            var middlename = $('#middle_name_appointment'+value).val();
            var lastname = $('#last_name_appointment'+value).val();
            var date_of_birth = $('#date_of_birth_appointment'+value).val();
            var mobile_number = $('#mobile_number_appointment'+value).val();
            var validateMobile = mobileValidation(mobile_number);
            var email = $('#email_appointment'+value).val();
            var address = $('#address_appointment'+value).val();
            var existing_user_id = $('#existing_user_id_appointment_'+value).val();
            var value_appointment_type = $('#appointment_name_appointment'+value).val();
            var value_appointment_socials = value_appointment_type;
            var value_social_type = $('#social_media_appointment'+value).val();
            var value_start_time = $('#start_time_appointment'+value).val();
            var services = '';
            var value_services = '';
            var value_services_name = '';
            var reserve_now = 'no';
            var reserve_later = 'no';
            var is_multiple_masseur = 'no';
            var value_start_time = $('#start_time_appointment'+value).val();
            var value_preparation_time = $('#preparation_time'+value).val();
            var price = parseInt($('#appointment_total_service_price'+value).val());
            total_amount += parseInt($('#appointment_total_service_price'+value).val());
            var plus_time = $('#appointment_plus_time_id'+value).val();
            var plus_time_price = $('#appointment_plus_time_price'+value).val();
            var therapist_1 = $('#appointment_masseur1'+value+'_id').val();
            var therapist_2 = $('#appointment_masseur2'+value+'_id').val();
            var room = $('#appointment_room_id'+value).val();
            var price_converted = ReplaceNumberWithCommas(price);

            var price_formatted = '&#8369;'+0;
            if (price_converted > 0) {
                price_formatted ='&#8369;'+price_converted;
            }

            var is_multiple_service_masseur = $('#appointment_service_multiple'+value).val();
            if (value_appointment_type == 'Walk-in') {
                if ($('#reservenow'+value).is(':checked')) {
                    value_start_time = $('#start_time_appointment_walkin'+value).val();
                    reserve_now = 'yes';
                    reserve_later = 'no';

                    services = $('#service_name_appointment_walkin'+value).select2('data');
                    value_services = $('#appointment_app_services_id'+value).val();
                    value_start_time = $('#start_time_appointment_walkin'+value).val();
                    value_services_name = services[0].text;

                    if ($('#appointmentCustomCheckbox'+value).is(':checked')) {
                        is_multiple_masseur = 'yes';
                    }
                } else if ($('#reservelater'+value).is(':checked')) {
                    value_start_time = $('#start_time_appointment'+value).val();
                    reserve_later = 'yes';
                    reserve_now = 'no';
                }
            }

            var value_start_time_format = gettime(value_start_time);
            var value_start_time_date_format = getdate(value_start_time);

            var reserved_option = 'no';
            if (reserve_now == 'yes') {
                reserved_option = 'yes';
            } else if (reserve_later == 'yes') {
                reserved_option = 'yes';
            }

            var validate_form = [
                { 'name': 'First name', 'value': firstname, 'id': value, 'validation': 'length' },
                { 'name': 'Last name', 'value': lastname, 'id': value, 'validation': 'length' },
                { 'name': 'Appointment type', 'value': value_appointment_type, 'id': value, 'validation': 'nullable' },
                { 'name': 'Social media type', 'value': value_social_type, 'id': value, 'validation': 'nullable' },
                { 'name': 'Start time', 'value': value_start_time, 'id': value, 'validation': 'time' },
                { 'name': 'Mobile number', 'value': mobile_number, 'id': value, 'validation': 'mobile' },
                { 'name': 'Reserve Option', 'value': reserved_option, 'id': value, 'validation': 'checkbox' },
                { 'name': 'Services', 'value': value_services, 'id': value, 'validation': 'length' },
                { 'name': 'Therapist 1', 'value': therapist_1, 'id': value, 'validation': 'length' },
                { 'name': 'Therapist 2', 'value': therapist_2, 'id': value, 'validation': 'length' },
                { 'name': 'Room', 'value': room, 'id': value, 'validation': 'length' },
            ];

            $.each(validate_form, function(array_key, array) {
                var service_conditions = array.name != 'Services' && 
                    array.name != 'Therapist 1' && 
                    array.name != 'Therapist 2' && 
                    array.name != 'Room';

                var condition_without_service =  service_conditions;

                var condition_without_reserved_option =  array.name != 'Reserve Option' &&
                    service_conditions;

                var condition_without_social_type =  array.name != 'Social media type' && 
                    array.name != 'Reserve Option' &&
                    service_conditions;
                
                if (value_appointment_type != null) {
                    if (value_appointment_type == 'Social Media') {
                        if (key == 0) {
                            if (condition_without_reserved_option) {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            }
                        }
                    } else if (value_appointment_type == 'Walk-in' && array.name != 'Social media type') {
                        if(reserve_now == 'no' && reserve_later =='no') {
                            if (key == 0) {
                                if (condition_without_service) {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        } else if (reserve_now == 'yes' && reserve_later =='no') {
                            if (is_multiple_service_masseur == 1) {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            } else {
                                if (array.name != 'Therapist 2') {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        } else if (reserve_later == 'yes' && reserve_now =='no') {
                            if (key == 0) {
                                if (condition_without_service) {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        }
                    } else {
                        if (key == 0) {
                            if (condition_without_social_type) {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            }
                        }
                    }
                } else {
                    if (key == 0) {
                        if (condition_without_social_type) {
                            validateAppointmentForm(array.value, array.name, array.id, array.validation);
                        }
                    }
                }
            });


            var key_value = value;
            if (key == 0) {
                key_value = value;
               if (value_appointment_type != null && value_appointment_type == 'Social Media') {
                    if (value_social_type != null) {
                        value_appointment_socials = value_appointment_type+'<br />('+value_social_type+')';
                    }
                }
            }

            validation = vaidateAppointmentTab(
                key,
                key_value,
                value,
                client_type, 
                firstname,
                lastname,
                mobile_number,
                value_appointment_type,
                value_start_time,
                validateMobile,
                value_social_type,
                value_services,
                therapist_1,
                therapist_2,
                is_multiple_masseur,
                room,
                reserve_now,
                reserve_later,
                newDate
            );

            if (validation) {
                appointment.push({
                    key: value,
                    client_type: client_type,
                    firstname: firstname,
                    middlename: middlename,
                    lastname: lastname,
                    date_of_birth: date_of_birth,
                    mobile_number: mobile_number,
                    email: email,
                    address: address,
                    service_id: value_services,
                    service_name: value_services_name,
                    start_time: value_start_time,
                    price: price,
                    price_formatted: price_formatted,
                    existing_user_id: existing_user_id,
                    appointment_type: value_appointment_type,
                    social_type: value_social_type,
                    appointment_socials: value_appointment_socials,
                    total_price: total_amount,
                    plus_time: plus_time,
                    therapist_1: therapist_1,
                    therapist_2: therapist_2,
                    room_id: room,
                    start_time_format: value_start_time_format,
                    start_time_date_format: value_start_time_date_format,
                    reserve_now: reserve_now,
                    reserve_later: reserve_later,
                    spa_id: $('#spa_id_val').val(),
                    owner_id: $('#owner_id_val').val(),
                    preparation_time: value_preparation_time,
                });
            }
        });

        if (validation) {
            swal.fire("Done!", 'Processed! Click the Summary tab to review and submit the form.', "success");
            $('.process-appointment-btn').text('Processed');
        }

        var converted_amount = 0;
        var getTotal_amount = 0;
        if (total_amount > 0) {
            converted_amount = ReplaceNumberWithCommas(total_amount);
            getTotal_amount = total_amount;
        }

        $('#totalAmountToPayAppointment').val(getTotal_amount);
        $('.total_amount_appointment').html('&#8369;'+converted_amount);
    }
}

function vaidateAppointmentTab(
    key,
    key_value,
    value,
    client_type, 
    firstname,
    lastname,
    mobile_number,
    value_appointment_type,
    value_start_time,
    validateMobile,
    value_social_type,
    value_services,
    therapist_1,
    therapist_2,
    is_multiple_masseur,
    room,
    reserve_now,
    reserve_later,
    newDate
) {
    var status;
    var default_validation = firstname.length < 1 ||
        lastname.length < 1 ||
        mobile_number.length < 1 ||
        value_appointment_type == null ||
        value_start_time.length < 1 ||
        !validateMobile;

    var social_validation = value_social_type == null || default_validation;
    var services_validation = value_services.length < 1 || therapist_1.length < 1 || room.length < 1 || default_validation;
    var multiple_service_validation = value_services.length < 1 || therapist_1.length < 1 || therapist_2.length < 1 || room.length < 1 || default_validation;

    if (
        client_type.length > 0 &&
        firstname.length > 0 &&
        lastname.length > 0 &&
        mobile_number.length > 0 &&
        value_appointment_type != null &&
        value_start_time.length > 0 &&
        newDate < new Date(value_start_time) &&
        validateMobile
    ) {
        if (value_appointment_type == 'Social Media') {
            if (value_social_type != null) {
                status = true;
                $('.tabAppointmentTitle'+value).removeClass('error-border');
                $('.summaryTabAppointmentLink').removeClass('hidden');
            } else {
                status = false;
                $('.tabAppointmentTitle'+value).addClass('error-border');
                $('.summaryTabAppointmentLink').addClass('hidden');
                $('.process-appointment-btn').text('Process').prop('disabled', false);
            }
        } else if (value_appointment_type == 'Walk-in') {
            if (reserve_now == 'yes') {
                if (is_multiple_masseur == 'yes') {
                    if (multiple_service_validation) {
                        status = false;
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                        $('.summaryTabAppointmentLink').addClass('hidden');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    } else {
                        status = true;
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    }
                } else {
                    if (services_validation) {
                        status = false;
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                        $('.summaryTabAppointmentLink').addClass('hidden');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    } else {
                        status = true;
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                }      
            } else if (reserve_later == 'yes') {
                if (default_validation) {
                    if (key == 0) {
                        status = false;
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    }
                    $('.summaryTabAppointmentLink').addClass('hidden');
                } else {
                    status = true;
                    $('.tabAppointmentTitle'+key_value).removeClass('error-border');  
                    $('.summaryTabAppointmentLink').removeClass('hidden');
                }
            } else {
                status = false;
                $('.summaryTabAppointmentLink').addClass('hidden');
                $('.process-appointment-btn').text('Process').prop('disabled', false);
                if (key == 0) {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');
                }
            }
        } else {
            status = true;
            $('.tabAppointmentTitle'+value).removeClass('error-border');
            $('.summaryTabAppointmentLink').removeClass('hidden');
        }
    } else {
        status = false;
        $('.process-appointment-btn').text('Process').prop('disabled', false);
        $('.summaryTabAppointmentLink').addClass('hidden');
        if (value_appointment_type != null) {
            if (value_appointment_type == 'Social Media') {
                if (key == 0) {
                    if (social_validation) {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                    }
                }
            } else if (value_appointment_type == 'Walk-in') {
                if (reserve_now == 'no' && reserve_later == 'no') {
                    if (default_validation) {
                        if (key == 0) {
                            $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        }
                    } else {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                    }
                } else if (reserve_now == 'yes') {
                    if (is_multiple_masseur == 'yes') {
                        if (multiple_service_validation) {
                            $('.tabAppointmentTitle'+value).addClass('error-border');
                        }
                    } else {
                        if (services_validation) {
                            $('.tabAppointmentTitle'+value).addClass('error-border');
                        }
                    }
                } else if (reserve_later == 'yes') {
                    if (default_validation) {
                        if (key == 0) {
                            $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        }
                    } else {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                    }
                }
            } else {
                if (default_validation) {
                    if (key == 0) {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                    }
                } else {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                }
            }
        } else {
            if (default_validation) {
                if (key == 0) {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');
                }
            } else {
                $('.tabAppointmentTitle'+key_value).addClass('error-border');  
            }
        }
    }

    return status;
}

function submitAppointment()
{
    var data = appointment;
    var appointment_type = $('.appointment_name_appointment').val();
    var reserve_now = $('.reserveNow').is(':checked');
    var amount = $('#totalAmountToPayAppointment').val();
    var spa_id = $('#spa_id_val').val();

    var message = 'Are you sure you want to save the appointment?';
    var url = '/appointment-store/'+spa_id;
    if (appointment_type == 'Walk-in' && reserve_now == true) {
        message = 'Are you sure you want to save the appointment as sales?';
        url = '/appointment-create-sales/'+spa_id+'/'+amount
    }

    swal.fire({
        title: message,
        icon: 'question',
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                'url' : url,
                'type' : 'POST',
                'data': {value: data},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#appointment-form').find('.add-appointment-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#appointment-form').trigger('reset');
                        $('.process-appointment-btn').removeClass('hidden');
                        $('.add-appointment-btn').addClass('hidden');

                        if (appointment_type == 'Walk-in') {
                            loadRoom();
                            getTotalSales(spa_id);
                        } else {
                            getAppointmentCount();
                            loadAppointments(spa_id);
                        }
                        getMasseurAvailability(spa_id);
                        getUpcomingGuest($('#spa_id_val').val());

                        loadData(spa_id);
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-appointment-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#appointment-form').find('.add-appointment-btn').val('Save').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
}