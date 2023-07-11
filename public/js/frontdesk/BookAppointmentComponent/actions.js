function getPrepTimeList(id)
{
    $.ajax({
        'url' : '/preparation_time',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#preparation_time'+id).html('');
            $('#preparation_time'+id).append('<option></option>');
            $('#preparation_time'+id).select2({
                placeholder: "Choose Time",
                allowClear: false
            });
            $.each(result , function(index, val) { 
                $('#preparation_time'+id).append('<option value="'+index+'">'+val+'</option>');
            });
        }
    });
}

function getServicesAppointment(spa_id, id)
{
    $.ajax({
        'url' : '/receptionist-service/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#service_name_appointment_walkin'+id).html('');
            $('#service_name_appointment_walkin'+id).append('<option></option>');
            $('#service_name_appointment_walkin'+id).select2({
                placeholder: "Choose Services",
                allowClear: false
            });
            $.each(result , function(index, val) { 
                $('#service_name_appointment_walkin'+id).append('<option value="'+val+'">'+index+'</option>');
            });
        }
    });
}

function getPlusTime(id)
{
    $.ajax({
        'url' : '/service-duration-range/',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#plus_time_appointment'+id).html('');
            $('#plus_time_appointment'+id).append('<option></option>');
            $('#plus_time_appointment'+id).select2({
                placeholder: "Choose Plus Time",
                allowClear: true
            }); 

            $.each(result.range , function(index, val) { 
                $('#plus_time_appointment'+id).append('<option value="'+index+'">'+val+'</option>');
            });
        }
    });
}

function getPosTherapistApi(spa_id, dateTime, id)
{
    $.ajax({
        'url' : '/pos-api-therapist-list/'+spa_id,
        'type' : 'GET',
        'data' : {
            'date': dateTime
        },
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('#appointment_masseur1'+id).html('');
            $('#appointment_masseur1'+id).append('<option></option>');
            $('#appointment_masseur1'+id).select2({
                placeholder: "Choose Masseur 1",
                allowClear: false
            }); 

            $('#appointment_masseur2'+id).html('');
            $('#appointment_masseur2'+id).append('<option></option>');
            $('#appointment_masseur2'+id).select2({
                placeholder: "Choose Masseur 2",
                allowClear: false
            }); 

            $.each(result , function(index, val) {                
                $('#appointment_masseur1'+id).append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                $('#appointment_masseur2'+id).append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                if (val.availability == 'yes') {
                    $('#appointment_masseur1'+id).children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                    $('#appointment_masseur2'+id).children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                } else {
                    $('.select-appointment-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('#appointment_masseur2'+id).children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                }
                
                if (filterPreSelectedTherapist.length > 0) {
                    $.each(filterPreSelectedTherapist , function(un_index, un_val) {
                        $('#appointment_masseur1'+id).children('option[value="' + un_val + '"]').attr('disabled', true);
                        $('#appointment_masseur2'+id).children('option[value="' + un_val + '"]').attr('disabled', true);

                        $('#appointment_masseur1'+id).select2({
                            placeholder: "Choose Masseur 1",
                            allowClear: true
                        });
                        $('#appointment_masseur2'+id).select2({
                            placeholder: "Choose Masseur 2",
                            allowClear: true
                        });
                    });
                }
            });
        }
    });
}

function getPosRoomApi(spa_id, dateTime, id)
{
    $.ajax({
        'url' : '/pos-api-room-list/'+spa_id,
        'type' : 'GET',
        'data' : {
            'date': dateTime
        },
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('#appointment_room'+id).html('');
            $('#appointment_room'+id).append('<option></option>');
            $('#appointment_room'+id).select2({
                placeholder: "Choose Room",
                allowClear: false
            }); 

            $.each(result , function(index, val) { 
                $('#appointment_room'+id).append('<option value="'+val.room_id+'">Room # '+val.room_id+'</option>');

                if (val.is_available == 'yes') {
                    $('#appointment_room'+id).children('option[value="' + val.room_id + '"]').attr('disabled', false);
                } else {
                    $('#appointment_room'+id).children('option[value="' + val.room_id + '"]').attr('disabled', true);
                }
                
                if (filterPreSelectedRoom.length > 0) {
                    $.each(filterPreSelectedRoom , function(un_index, un_val) {
                        $('#appointment_room'+id).children('option[value="' + un_val + '"]').attr('disabled', true);

                        $('#appointment_room'+id).select2({
                            placeholder: "Choose Masseur 1",
                            allowClear: true
                        });
                    });
                }
            });
        }
    });
}

function getServiceById(id, data_id)
{
    $.ajax({
        'url' : '/service/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            if (result.service.multiple_masseur == 1) {
                if ($('.appointment_room_div'+data_id).hasClass('col-md-6')) {
                    $('.appointment_room_div'+data_id).removeClass('col-md-6');
                    $('.appointment_room_div'+data_id).addClass('col-md-4');
                }

                if ($('.appointment_masseur1_div'+data_id).hasClass('col-md-6')) {
                    $('.appointment_masseur1_div'+data_id).removeClass('col-md-6');
                    $('.appointment_masseur1_div'+data_id).addClass('col-md-4');
                }

                $('.appointment_masseur1_div'+data_id).removeClass('hidden');
                $('.appointment_masseur2_div'+data_id).removeClass('hidden');
            } else {
                if ($('.appointment_room_div'+data_id).hasClass('col-md-4')) {
                    $('.appointment_room_div'+data_id).removeClass('col-md-4');
                    $('.appointment_room_div'+data_id).addClass('col-md-6');
                }

                if ($('.appointment_masseur1_div'+data_id).hasClass('col-md-4')) {
                    $('.appointment_masseur1_div'+data_id).removeClass('col-md-4');
                    $('.appointment_masseur1_div'+data_id).addClass('col-md-6');
                }

                if (!$('.appointment_masseur2_div'+data_id).hasClass('hidden')) {
                    $('.appointment_masseur2_div'+data_id).addClass('hidden');
                }

                $('.appointment_masseur1_div'+data_id).removeClass('hidden');
            }
            $('#appointment_service_multiple'+data_id).val(result.service.multiple_masseur);
        }
    });
}