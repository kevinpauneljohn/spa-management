var guestTabSpaId = $('.spaId').val();
function viewAppointment(id)
{
    $.ajax({
        'url' : '/appointment-show/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $(".viewAppointmentTitle").html('View Appointment');
            $(".viewAppointmentFullname").html(result.firstname+' '+result.lastname);
            $(".viewAppointmentDateOfBirth").html(result.date_of_birth);
            $(".viewAppointmentMobileNumber").html(result.mobile_number);
            $(".viewAppointmentEmail").html(result.email);
            $(".viewAppointmentAddress").html(result.address);
            $(".viewAppointmentStartTime").html(result.start_time_formatted);
            $(".viewAppointmentBatch").html('Batch # '+result.batch);
            $(".viewAppointmentClientType").html(result.client_type);

            if (result.appointment_type == 'Social Media') {
                $(".viewAppointmentType").html(result.appointment_type+'<br />('+result.social_media_type+')');
            } else {
                $(".viewAppointmentType").html(result.appointment_type);
            }
            $(".viewAppointmentStatus").html(result.appointment_status);
        }
    });

    $('#view-appointment-modal').modal('show');
}

function editViewAppointment(id)
{
    var currentDate = new Date();
    var currentDateTime = currentDate.toISOString().slice(0, 16);
    $("#start_time_appointment_up").attr("min", currentDateTime);
    $.ajax({
        'url' : '/appointment-show/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            //update values
            $('.viewAppointmentUpdateTitle').html('Update Appointment');
            if (result.client_id != '') {
                $('#edit_app_firstname').prop('disabled', true);
                $('#edit_app_middlename').prop('disabled', true);
                $('#edit_app_lastname').prop('disabled', true);
            } else {
                $('#edit_app_firstname').prop("disabled", false);
                $('#edit_app_middlename').prop('disabled', false);
                $('#edit_app_lastname').prop('disabled', false);
            }

            $('#edit_app_id').val(result.id);
            $('#edit_app_client_id').val(result.client_id);
            $('#edit_app_firstname').val(result.firstname);
            $('#edit_app_middlename').val(result.middlename);
            $('#edit_app_lastname').val(result.lastname);
            $('#edit_app_date_of_birth').val(result.date_of_birth);
            $('#edit_app_mobile_number').val(result.mobile_number);
            $('#edit_app_email').val(result.email);
            $('#edit_app_address').val(result.address);
            $('#edit_app_client_type').val(result.client_type);

            $("#appointment_name_appointmentup").val(result.appointment_type).change();
            if (result.appointment_type == 'Social Media') {
                $("#social_media_appointmentup").val(result.social_media_type).change();
            } else {
                $('.socialMedialUpdate').addClass('hidden');
                $("#social_media_appointmentup").val('').change();
            }

            $("#edit_app_servicesup").select2({
                placeholder: 'Choose Services',
                allowClear: false
            }).val(result.service_id).trigger("change");

            $('#price_appointment_up').val(result.amount);
            $('#start_time_appointment_up').val(result.start_time);
            $('.totalAmountUpdateAppointmentFormatted').html('&#8369; '+result.amount);
        }
    });

    $('#update-appointment-modal').modal('show');
}

function moveViewAppointment(id)
{
    var currentDate = new Date();
    var currentDateTime = currentDate.toISOString().slice(0, 16);
    $("#start_time_appointment_move").attr("min", currentDateTime);
    $.ajax({
        'url' : '/appointment-show/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('.viewAppointmentMoveTitle').html('Move Appointment to Sales');
            $('#move_app_id').val(result.id);
            $('#move_app_client_id').val(result.client_id);
            $('#move_app_firstname').val(result.firstname);
            $('#move_app_middlename').val(result.middlename);
            $('#move_app_lastname').val(result.lastname);
            $('#move_app_date_of_birth').val(result.date_of_birth);
            $('#move_app_mobile_number').val(result.mobile_number);
            $('#move_app_email').val(result.email);
            $('#move_app_address').val(result.address);

            if (result.appointment_type == 'Social Media') {
                $("#appointment_name_appointmentmove").val(result.appointment_type).change();
                $("#social_media_appointmentmove").val(result.social_media_type).change();
            } else {
                $("#appointment_name_appointmentmove").val(result.appointment_type).change();
                $('.socialMedialMove').addClass('hidden');
                $("#social_media_appointmentmove").val('').change();
            }

            $("#move_app_servicesmove").select2({
                placeholder: 'Choose Services',
                allowClear: false
            }).val(result.service_id).trigger("change");

            $('#move_app_services_id').val(result.service_id);
            $('#move_plus_time_id').val('');
            $(".select-move-plus_time").select2({
                placeholder: 'Choose Plus Time',
                allowClear: true
            });
            $('.select-move-plus_time').prop('disabled', true);
            $('#price_appointment_move').val(result.amount);
            $('#start_time_appointment_move').val(result.start_time);
            $('.totalAmountMoveAppointmentFormatted').html('&#8369; 0.00');
        }
    });

    $('#move-appointment-modal').modal('show');
}

function updateAppointment()
{
    var id = $('#edit_app_id').val();
    var client_id = $('#edit_app_client_id').val();
    var firstname = $('#edit_app_firstname').val();
    var middlename = $('#edit_app_middlename').val();
    var lastname = $('#edit_app_lastname').val();
    var date_of_birth = $('#edit_app_date_of_birth').val();
    var mobile_number = $('#edit_app_mobile_number').val();
    var client_type = $('#edit_app_client_type').val();
    var email = $('#edit_app_email').val();
    var address = $('#edit_app_address').val();
    var appointment_type = $('#appointment_name_appointmentup').val();
    var appointment_social = $('#social_media_appointmentup').val();
    var start_time = $('#start_time_appointment_up').val();
    var validateMobile = mobileValidation(mobile_number);

    if (firstname.length < 1) {
        toastr.error('The firstname field is required.');
    }

    if (lastname.length < 1) {
        toastr.error('The lastname field is required.');
    }

    if (mobile_number.length < 1) {
        toastr.error('The mobile number field is required.');
    } else {
        if (!validateMobile) {
            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
        }
    }

    if (start_time.length < 1) {
        toastr.error('The start time field is required.');
    }

    if (appointment_type == 'Social Media') {
        if (appointment_social == null || typeof(appointment_social) == 'undefined') {
            toastr.error('The social media field is required.');
        }
    }

    var valid = false;
    if (firstname.length > 0 && lastname.length > 0 && mobile_number.length > 0 && start_time.length > 0 && validateMobile) {
        if (appointment_type == 'Social Media') {
            if (appointment_social != null) {
                valid = true;
            } else {
                valid = false;
            }
        } else {
            valid = true;
        }
    }

    if (valid) {
        swal.fire({
            title: "Are you sure you want to update the upcoming appointment?",
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
                    'url' : '/appointment-update/'+id,
                    'type' : 'PUT',
                    'data': {
                        id: id,
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
                        start_time: start_time,
                        client_type: client_type
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#update-appointment-form').find('.update-appointment-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        if(result.status) {
                            $('#update-appointment-form').trigger('reset');
                            // getAppointmentCount();
                            // loadAppointments(guestTabSpaId);
                            // loadRoom();
                            // loadSales(guestTabSpaId);
                            $('#appointment-data-lists').DataTable().ajax.reload(null, false);
                            // getTotalSales(guestTabSpaId);
                            // getMasseurAvailability(guestTabSpaId);
                            // loadData(guestTabSpaId);
                            // getUpcomingGuest(guestTabSpaId);
            
                            swal.fire("Done!", result.message, "success");
                            $('#update-appointment-modal').modal('hide');
                        } else {
                            swal.fire("Warning!", result.message, "warning");
                        }
                
                        $('#update-appointment-form').find('.update-appointment-btn').val('Save').attr('disabled',false);
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

function processMoveAppointment()
{
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
    
    var value_services = $('#move_app_services_id').val();
    var value_services_name = $('#move_app_services_name').val();
    var value_multiple_masseur = $('#move_app_services_multiple').val();
    var value_plus_time = $('#move_plus_time_id').val();
    var value_therapist_1_id = $('#move_masseur1_id').val();
    var value_therapist_2_id = $('#move_masseur2_id').val();
    var price = $('#price_appointment_move').val();
    var total_price = $('#totalAmountMoveToPay').val();
    var value_room_id = $('#move_room_id').val();
    var start_time = $('#start_time_appointment_move').val();

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
            toastr.error('The start time must be less than 1 hour from the current timesss.');
            valid = false;
        } else if (addDate < new Date(start_time)) {
            toastr.error('The start time must not be greater than 1 hour from the current time.');
            valid = false;
        } else {
            if (value_multiple_masseur == 1) {
                if (value_therapist_2_id.length < 1) {
                    toastr.error('The Masseur 2 field is required.');
                    valid = false;
                } else {
                    valid = true;
                }
            } else {
                valid = true;
            }
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
            toastr.error('The start time must be less than 1 hour from the current time2.');
        } else if (addDate < new Date(start_time)) {
            toastr.error('The start time must not be greater than 1 hour from the current time.');
        }
    
        if (value_therapist_1_id.length < 1) {
            toastr.error('The Masseur 1 field is required.');
        }

        if (value_multiple_masseur == 1) {
            console.log('here')
            if (value_therapist_2_id.length < 1) {
                toastr.error('The Masseur 2 field is required.');
            }
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
                        spa_id: guestTabSpaId,
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
                        room_id: value_room_id,
                        preparation_time: 0
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#move-appointment-form').find('.move-sales-appointment-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        if(result.status) {
                            $('#move-appointment-form').trigger('reset');
                            // getAppointmentCount();
                            // loadAppointments(guestTabSpaId);
                            // loadSales(spa_id);
                            $('#appointment-data-lists').DataTable().ajax.reload(null, false);
                            // loadRoom();
                            // getTotalSales(guestTabSpaId);
                            // getMasseurAvailability(guestTabSpaId);
                            // loadData(guestTabSpaId);
                            // getUpcomingGuest(guestTabSpaId);
            
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

function deleteAppointment(id)
{
    $tr = $(this).closest('tr');
    var name = $(this).data("name")
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete appointment ?",
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
                'url' : '/appointment-delete/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if(result.status) {
                        getAppointmentCount();
                        loadAppointments(guestTabSpaId);
                        getUpcomingGuest(guestTabSpaId);
        
                        swal.fire("Done!", result.message, "success");
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
}

function getUpcomingAppointmentType(id)
{
    $.ajax({
        'url' : '/appointment-type',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $("#appointment_name_appointmentup").html('');
            $("#social_media_appointmentup").html('');
        },
        success: function(result){
            $("#appointment_name_appointmentup").append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.appointment_type , function(index_appointment, val_appointment) {
                if (val_appointment != 'Walk-in') {
                    $("#appointment_name_appointmentup").append('<option value="'+val_appointment+'">'+val_appointment+'</option>');
                    $("#appointment_name_appointmentmove").append('<option value="'+val_appointment+'">'+val_appointment+'</option>');
                }
            });

            $("#social_media_appointmentup").append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.social_media , function(index_social, val_social) {
                $("#social_media_appointmentup").append('<option value="'+val_social+'">'+val_social+'</option>');
                $("#social_media_appointmentmove").append('<option value="'+val_social+'">'+val_social+'</option>');
            });
        }
    });
}

function getMoveServicesAppointment(spa_id)
{
    $.ajax({
        'url' : '/receptionist-service/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('.select-services-move-appointment').html('');
            $('.select-services-move-appointment').append('<option></option>');
            $('.select-services-move-appointment').select2({
                placeholder: "Choose Services",
                allowClear: false
            });

            $.each(result , function(index, val) { 
                $('.select-services-move-appointment').append('<option value="'+val+'">'+index+'</option>');
            });

            var move_room_id = $('#move_room_id').val();
            if (!$('.moveRoomDiv').hasClass('hidden')) {
                if (move_room_id.length > 0) {
                    filterPreSelectedRoom = $.grep(filterPreSelectedRoom, function(element){
                        return element !== move_room_id;
                    }); 
                }

                $('#move_room_id').val('');
                getMovePosRoomApi(guestTabSpaId);
                $('.moveRoomDiv').addClass('hidden');
            }

            var masseur_1 = $('#move_masseur1_id').val();
            var masseur_2 = $('#move_masseur2_id').val();
            if (!$('.moveMasseur1Div').hasClass('hidden') || !$('.moveMasseur2Div').hasClass('hidden')) {
                if (masseur_1.length > 0) {
                    filterPreSelectedTherapist = $.grep(filterPreSelectedRoom, function(element){
                        return element !== masseur_1;
                    }); 
                }

                if (masseur_2.length > 0) {
                    filterPreSelectedTherapist = $.grep(filterPreSelectedRoom, function(element){
                        return element !== masseur_2;
                    }); 
                }

                $('#move_masseur1_id').val('');
                $('#move_masseur2_id').val('');
                getMovePosTherapistApi(guestTabSpaId);
                $('.moveMasseur1Div').addClass('hidden');
                $('.moveMasseur2Div').addClass('hidden');
            }
        }
    });
}

function getMovePlusTime()
{
    $.ajax({
        'url' : '/service-duration-range/',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('.select-move-plus_time').html('');
            $('.select-move-plus_time').append('<option></option>');
            $('.select-move-plus_time').select2({
                placeholder: "Choose Plus Time",
                allowClear: true
            }); 

            $.each(result.range , function(index, val) { 
                $('.select-move-plus_time').append('<option value="'+index+'">'+val+'</option>');
            });

            if ($('#move_plus_time_id').val().length > 0) {
                $("#move_plus_time").select2().val($('#move_plus_time_id').val()).trigger("change");
            }
        }
    });

    $('.movePlusTimeDiv').removeClass('hidden');
}

function getMovePosTherapistApi(spa_id)
{
    var dateTime = $('#start_time_appointment_move').val();
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
            $('#move_masseur1').html('');
            $('#move_masseur1').append('<option></option>');
            $('#move_masseur1').select2({
                placeholder: "Choose Masseur 1",
                allowClear: false
            }); 

            $('#move_masseur2').html('');
            $('#move_masseur2').append('<option></option>');
            $('#move_masseur2').select2({
                placeholder: "Choose Masseur 2",
                allowClear: false
            }); 

            $.each(result , function(index, val) {                
                $('#move_masseur1').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                $('#move_masseur2').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                if (val.availability == 'yes') {
                    $('#move_masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                    $('#move_masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                } else {
                    $('#move_masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('#move_masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                }
                
                if (filterPreSelectedTherapist.length > 0) {
                    $.each(filterPreSelectedTherapist , function(un_index, un_val) {
                        $('#move_masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                        $('#move_masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);
                    });
                }
            });

            var multiple_masseur = $('#move_app_services_multiple').val();
            var masseur_1 = $('#move_masseur1_id').val();
            var masseur_2 = $('#move_masseur2_id').val();

            if (multiple_masseur.length > 0) {
                if (multiple_masseur == 1) {
                    $('.moveMasseur1Div').removeClass('hidden');
                    $('.moveMasseur2Div').removeClass('hidden');
    
                    if ($('.moveMasseur1Div').hasClass('col-md-6')) {
                        $('.moveMasseur1Div').removeClass('col-md-6');
                        $('.moveMasseur1Div').addClass('col-md-4');
                    }
                } else {
                    $('.moveMasseur1Div').removeClass('hidden');
                    if (!$('.moveMasseur2Div').hasClass('hidden')) {
                        $('.moveMasseur2Div').addClass('hidden');
                        $('#move_masseur2_id').val('');
    
                        if ($('.moveMasseur1Div').hasClass('col-md-4')) {
                            $('.moveMasseur1Div').removeClass('col-md-4');
                            $('.moveMasseur1Div').addClass('col-md-6');
                        }
                    }
    
                    if (masseur_2.length > 0) {
                        filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                            return element !== masseur_2;
                        }); 
    
                        $("#move_masseur1").children('option[value="' + masseur_2 + '"]').attr('disabled', false);
                    }
                }
    
                if ($('#move_masseur1_id').val().length > 0) {
                    $('#move_masseur1').select2({
                        placeholder: "Choose Masseur 1",
                        allowClear: false
                    }).val(masseur_1).trigger("change");
                }
            }
        }
    });
}

function getMovePosRoomApi(spa_id)
{
    var dateTime = $('#start_time_appointment_move').val();
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
            $('#move_room').html('');
            $('#move_room').append('<option></option>');
            $('#move_room').select2({
                placeholder: "Choose Room",
                allowClear: false
            }); 

            $.each(result , function(index, val) { 
                $('#move_room').append('<option value="'+val.room_id+'">Room # '+val.room_id+'</option>');

                if (val.is_available == 'yes') {
                    $('#move_room').children('option[value="' + val.room_id + '"]').attr('disabled', false);
                } else {
                    $('#move_room').children('option[value="' + val.room_id + '"]').attr('disabled', true);
                }
                
                if (filterPreSelectedRoom.length > 0) {
                    $.each(filterPreSelectedRoom , function(un_index, un_val) {
                        $('#move_room').children('option[value="' + un_val + '"]').attr('disabled', true);
                    });
                }
            });

            var room_id = $('#move_room_id').val();
            var multiple_masseur = $('#move_app_services_multiple').val();

            if (multiple_masseur.length > 0) {
                if ($('#move_room_id').val().length > 0) {
                    $('#move_room').select2({
                        placeholder: "Choose Room",
                        allowClear: false
                    }).val(room_id).trigger("change");
                }
    
                if (multiple_masseur == 1) {
                    if ($('.moveRoomDiv').hasClass('col-md-6')) {
                        $('.moveRoomDiv').removeClass('col-md-6');
                        $('.moveRoomDiv').addClass('col-md-4');
                    }
                } else {
                    if ($('.moveRoomDiv').hasClass('col-md-4')) {
                        $('.moveRoomDiv').removeClass('col-md-4');
                        $('.moveRoomDiv').addClass('col-md-6');
                    }
                }
                $('.moveRoomDiv').removeClass('hidden');
            }
        }
    });
}

function getMoveServiceById(guestTabSpaId, id)
{
    $.ajax({
        'url' : '/service/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('#move_app_services_multiple').val(result.service.multiple_masseur);
            getMovePosTherapistApi(guestTabSpaId);
            getMovePosRoomApi(guestTabSpaId);
        }
    });
}