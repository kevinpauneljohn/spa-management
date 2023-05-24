function submitAppointment()
{
    var data = appointment;
    var appointment_type = $('.appointment_name_appointment').val();
    var amount = $('#totalAmountToPayAppointment').val();
    var spa_id = $('#spa_id_val').val();

    var message = 'Are you sure you want to save the appointment?';
    var url = '/appointment-store/'+spa_id;
    if (appointment_type == 'Walk-in') {
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

function processMoveAppointment()
{
        var spa_id = $('#spa_id_val').val();
    var id = $('#move_app_id').val();
    var client_id = $('#move_app_client_id').val();
    var firstname = $('#move_app_firstname').val();
    var middlename = $('#move_app_middlename').val();
    var lastname = $('#move_app_lastname').val();
    var date_of_birth = $('#move_app_date_of_birth').val();
    var mobile_number = $('#move_app_mobile_number').val();
    var email = $('#move_app_email').val();
    var address = $('#move_app_address').val();
    var appointment_type = $('#appointment_name_appointmentmove').val();

    var appointment_social = '';
    if (appointment_type == 'Social Media') {
        appointment_social = $('#move_app_social_media_appointment').val();
    }
    
    var value_services = '';
    var value_services_name = '';
    var services = '';
    if ($('#move_app_services_id').val().length > 0) {
        services = $('#edit_app_servicesup').select2('data');
        value_services = services[0].id;
        value_services_name = services[0].text;
    }
    
    var value_plus_time = '';
    var plus_time = '';
    if ($('#move_plus_time_id').val().length > 0) {
        value_plus_time =$('#move_plus_time_id').val();
    }
    
    var therapist_1 = $('#move_masseur1').select2('data');
    var value_therapist_1_id = therapist_1[0].id;
    var therapist_2 = $('#move_masseur2').select2('data');
    var value_therapist_2_id = therapist_2[0].id;
    var price = $('#price_appointment_move').val();
    var total_price = $('#totalAmountMoveToPay').val();
    var start_time = $('#start_time_appointment_up').val();
    var value_room_id = $('#move_room_id').val();
 
    var valid = false;
    if (
        appointment_type.length > 0 &&
        value_services.length > 0 &&
        start_time.length > 0 &&
        value_therapist_1_id.length > 0 &&
        value_room_id.length > 0
    ) {
        if (appointment_type == 'Social Media') {
            if (appointment_social != '') {
                valid = true;
            } else {
                valid = false;
            }
        } else {
            valid = true;
        }
    } else {
        if (appointment_type.length < 1) {
            $('#error-move_app_appointment_type').removeClass('hidden');
            $('#error-move_app_appointment_type').text('Appointment Type field is required!');
        } else {
            if (appointment_social ==  '') {
                $('#error-move_app_social_media_appointment').removeClass('hidden');
                $('#error-move_app_social_media_appointment').text('Social Media Type field is required!');
            }
        }
    
        if (value_services.length < 1) {
            $('#error-move_app_servicesmove').removeClass('hidden');
            $('#error-move_app_servicesmove').text('Services field is required!');
        }
    
        if (start_time.length < 1) {
            $('#error-start_time_appointment_move').removeClass('hidden');
            $('#error-start_time_appointment_move').text('Start time field is required!');
        }
    
        if (value_therapist_1_id.length < 1) {
            $('#error-move_masseur1_id').removeClass('hidden');
            $('#error-move_masseur1_id').text('Masseur 1 field is required!');
        }
    
        if (value_room_id.length < 1) {
            $('#error-move_room').removeClass('hidden');
            $('#error-move_room').text('Room field is required!');
        }
    }
    
    if (valid) {
        swal.fire({
            title: "Are you sure you want to move the appointment to sales?",
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
                    'url' : '/appointment-sales',
                    'type' : 'POST',
                    'data': {
                        spa_id: spa_id,
                        appointment_id: id,
                        client_id: client_id,
                        firstname: firstname,
                        middlename: middlename,
                        lastname: lastname,
                        date_of_birth: date_of_birth,
                        mobile_number: mobile_number,
                        email: email,
                        address: address,
                        appointment_type: appointment_type,
                        appointment_social: appointment_social,
                        value_services: value_services,
                        value_services_name: value_services_name,
                        value_plus_time: value_plus_time,
                        therapist_1: value_therapist_1_id,
                        therapist_2: value_therapist_2_id,
                        price: price,
                        total_price: total_price,
                        start_time: start_time,
                        room_id: value_room_id
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#move-appointment-form').find('.move-sales-appointment-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        if(result.status) {
                            $('#move-appointment-form').trigger('reset');
                            getAppointmentCount();
                            loadAppointments(spa_id);
                            loadSales(spa_id);
                            loadRoom();
                            getTotalSales(spa_id);
                            getMasseurAvailability(spa_id);
                            loadData(spa_id);
                            getUpcomingGuest($('#spa_id_val').val());
            
                            swal.fire("Done!", result.message, "success");
                            $('#move-appointment-modal').modal('hide');
                        } else {
                            swal.fire("Warning!", result.message, "warning");
                        }
                
                        $('#move-appointment-form').find('.move-sales-appointment-btn').val('Save').attr('disabled',false);
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
}