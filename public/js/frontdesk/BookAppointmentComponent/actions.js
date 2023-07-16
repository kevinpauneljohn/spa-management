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

function getAppointmentType(id)
{
    $.ajax({
        'url' : '/appointment-type',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $("#appointment_name_appointment"+id).html('');
            $("#social_media_appointment"+id).html('');
        },
        success: function(result){
            $("#appointment_name_appointment"+id).append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.appointment_type , function(index_appointment, val_appointment) {
                $("#appointment_name_appointment"+id).append('<option value="'+val_appointment+'">'+val_appointment+'</option>');
            });

            $("#social_media_appointment"+id).append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.social_media , function(index_social, val_social) {
                $("#social_media_appointment"+id).append('<option value="'+val_social+'">'+val_social+'</option>');
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
                    $('#appointment_masseur1'+id).children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('#appointment_masseur2'+id).children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                }
                
                if (filterPreSelectedTherapist.length > 0) {
                    $.each(filterPreSelectedTherapist , function(un_index, un_val) {
                        $('.select-appointment-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                        $('.select-appointment-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

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

            var masseur_1 = $('#appointment_masseur1'+data_id+'_id').val();
            var masseur_2 = $('#appointment_masseur2'+data_id+'_id').val();
            if (masseur_1.length > 0) {
                filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                    return element !== masseur_1;
                }); 

                $('.select-appointment-masseur1').children('option[value="' + masseur_1 + '"]').attr('disabled', false);
            }

            if (masseur_2.length > 0) {
                filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                    return element !== masseur_2;
                }); 

                $('.select-appointment-masseur2').children('option[value="' + masseur_2 + '"]').attr('disabled', false);
            }

            var spa_id = $('#spa_id_val').val();
            var dateTime = $('#start_time_appointment_walkin'+data_id).val();
            getPosTherapistApi(spa_id, dateTime, data_id);
        }
    });
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
                            loadRoomAvailability(spa_id);
                            // getTotalSales(spa_id);
                        } else {
                            // getAppointmentCount();
                            // loadAppointments(spa_id);
                        }
                        getMasseurAvailability(spa_id);
                        getUpcomingGuest(spa_id)

                        // loadData(spa_id);
                        searchFilter = [];
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