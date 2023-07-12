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
    var validateMobile = mobileValidation(mobile_number);
    var email = $('#move_app_email').val();
    var address = $('#move_app_address').val();
    var appointment_type = $('#appointment_name_appointmentmove').val();

    var appointment_social = $('#social_media_appointmentmove').val();
    
    var value_services = '';
    var value_services_name = '';
    var services = '';
    if ($('#move_app_services_id').val().length > 0) {
        services = $('#move_app_servicesmove').select2('data');
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
    var start_time = $('#start_time_appointment_move').val();
    var value_room_id = $('#move_room_id').val();
    var date_sub = new Date();
    var date_add = new Date();
    var subDate = subtractHours(date_sub, 1);
    var addDate = addHours(date_add, 1);

    var valid = false;
    if (
        appointment_type.length > 0 &&
        value_services.length > 0 &&
        start_time.length > 0 &&
        value_therapist_1_id.length > 0 &&
        value_room_id.length > 0 &&
        mobile_number.length > 0 && 
        validateMobile
    ) {
        if (appointment_type == 'Social Media') {
            if (appointment_social != null) {
                valid = true;
            } else {
                valid = false;
                toastr.error('The Social Media Type field is required.');
            }
        } else if (subDate > new Date(start_time)) {
            toastr.error('The start time must be less than 1 hour from the current time.');
            valid = false;
        } else if (addDate < new Date(start_time)) {
            toastr.error('The start time must not be greater than 1 hour from the current time.');
            valid = false;
        } else {
            valid = true;
        }
    } else {
        if (appointment_type.length < 1) {
            toastr.error('The Appointment Type field is required.');
        } else {
            if (appointment_type == 'Social Media') {
                if (typeof(appointment_social) === 'undefined' || appointment_social == null) {
                    toastr.error('The Social Media Type field is required.');
                }
            }
        }
    
        if (value_services.length < 1) {
            toastr.error('The Services field is required.');
        }
    
        if (start_time.length < 1) {
            toastr.error('The start time field is required.');
        } else if (subDate > new Date(start_time)) {
            toastr.error('The start time must be less than 1 hour from the current time.');
        } else if (addDate < new Date(start_time)) {
            toastr.error('The start time must not be greater than 1 hour from the current time.');
        }
    
        if (value_therapist_1_id.length < 1) {
            toastr.error('The Masseur 1 field is required.');
        }
    
        if (value_room_id.length < 1) {
            toastr.error('The Room field is required.');
        }

        if (mobile_number.length < 1) {
            toastr.error('The mobile number field is required.');
        } else {
            if (!validateMobile) {
                toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
            }
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
                            // loadSales(spa_id);
                            $('#sales-data-lists').DataTable().ajax.reload(null, false);
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

function startShiftPos(spa_id)
{
    swal.fire({
        title: "Are you sure you want to start your shift?",
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
                'url' : '/pos-start-shift/'+spa_id,
                'type' : 'POST',
                'data' : {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('.btnStartShift').text('Starting Shift ... ').attr('disabled',true);
                },
                success: function(result){
                    getPosShift(spa_id);
                    
                    swal.fire("Done!", result.message, "success");
                    $('#start-shift-modal').modal('hide');
                    $('.btnStartShift').val('Click here to start your shift!').attr('disabled',false);
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
}