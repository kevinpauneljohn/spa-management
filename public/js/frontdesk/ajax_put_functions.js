function updateSales(spa_id, amount, old_amount)
{
    var transaction_id = $('#edit_transaction_id').val();
    var client_id = $('#edit_client_id').val();
    var sales_id = $('#edit_sales_id').val();
    var firstname = $('#edit_first_name').val();
    var middlename = $('#edit_middle_name').val();
    var lastname = $('#edit_last_name').val();
    var date_of_birth = $('#edit_date_of_birth').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var address = $('#edit_address').val();
    var client_type = $('#edit_client_type').val();
    var services = $('#edit_services').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var masseur1_id = $('#edit_masseur1_id').val();
    var masseur2_id = $('#edit_masseur2_id_val').val();
    var start_time = $('#edit_start_time').val();
    var plus_time = $('#edit_plus_time').select2('data');
    var value_plus_time = plus_time[0].id;
    var room_id = $('#edit_room').select2('data');
    var value_room_id = room_id[0].id;
    var validateMobile = mobileValidation(mobile_number);

    var date_sub = new Date();
    var date_add = new Date();
    var subDate = subtractHours(date_sub, 1);
    var addDate = addHours(date_add, 1);

    if (mobile_number.length < 1) {
        toastr.error('The mobile number field is required.');
    } else {
        if (!validateMobile) {
            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
        }
    }

    if (value_services.length < 1) {
        toastr.error('The services field is required.');
    }

    if (masseur1_id.length < 1) {
        toastr.error('The masseur 1 field is required.');
    }

    if (start_time.length < 1) {
        toastr.error('The start time field is required.');
    }

    if (value_room_id.length < 1) {
        toastr.error('The room # field is required.');
    }

    var valid = false;
    if (
        mobile_number.length > 0 &&
        value_services.length > 0 &&
        masseur1_id.length > 0 &&
        start_time.length > 0 &&
        value_room_id.length > 0 && 
        validateMobile
    ) {
        if (!validateMobile) {
            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
            valid = false;
        } else {
            valid = true;
        }

        // if (subDate > new Date(start_time)) {
        //     toastr.error('The start time must be less than 1 hour from the current time.');
        //     valid = false;
        // } else 
        // else if (addDate < new Date(start_time)) {
        //     toastr.error('The start time must not be greater than 1 hour from the current time.');
        //     valid = false;
        // } 
    }

    if (valid) {
        swal.fire({
            title: "Are you sure you want to update the on going reservation?",
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
                    'url' : '/update/'+spa_id+'/'+amount,
                    'type' : 'PUT',
                    'data': {
                        id: transaction_id,
                        client_id: client_id,
                        sales_id: sales_id,
                        firstname: firstname,
                        middlename: middlename,
                        lastname: lastname,
                        date_of_birth: date_of_birth,
                        mobile_number: mobile_number,
                        email: email,
                        address: address,
                        client_type: client_type,
                        service_id: value_services,
                        service_name: value_services_name,
                        therapist_1: masseur1_id,
                        therapist_2: masseur2_id,
                        therapist_2_id: masseur2_id,
                        start_time: start_time,
                        plus_time: value_plus_time,
                        room_id: value_room_id,
                        old_amount: old_amount,
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#sales-update-form').find('.update-sales-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        if(result.status) {
                            $('#sales-update-form').trigger('reset');
                            loadRoom();
                            loadSales(spa_id);
                            getTotalSales(spa_id);
                            getMasseurAvailability(spa_id);
                            loadData(spa_id);
                            getUpcomingGuest($('#spa_id_val').val());
    
                            swal.fire("Done!", result.message, "success");
                            $('#update-sales-modal').modal('hide');
                        } else {
                            swal.fire("Warning!", result.message, "warning");
                        }
                
                        $('#sales-update-form').find('.update-sales-btn').val('Save').attr('disabled',false);
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

function updateAppointment()
{
    var spa_id = $('#spa_id_val').val();
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
            title: "Are you sure you want to update the appointment?",
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
                            getAppointmentCount();
                            loadAppointments(spa_id);
                            loadRoom();
                            loadSales(spa_id);
                            getTotalSales(spa_id);
                            getMasseurAvailability(spa_id);
                            loadData(spa_id);
                            getUpcomingGuest($('#spa_id_val').val());
            
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

function updateInvoice(id)
{
    var spa_id = $('#spa_id_val').val();
    var payment_method = $('#payment_method').val();
    var payment_status = $('#payment_status').val();
    var payment_account_number = $('#payment_account_number').val();
    var payment_bank_name = $('#payment_bank_name').val();
    var batch = $('#sales_batch_id').val();

    var valid = false;
    if (payment_method !== 'cash') {
        if (payment_method == 'bank') {
            if (payment_bank_name.length < 1) {
                toastr.error('The Bank Name field is required.');
                valid = false;
            }
    
            if (payment_account_number.length < 1) {
                toastr.error('The Reference Number field is required.');
                valid = false;
            }

            if (payment_bank_name.length > 1 && payment_account_number.length > 1) {
                valid = true;
            }
        } else  if (payment_method == 'gcash' || payment_method == 'paymaya') {
            if (payment_account_number.length < 1) {
                toastr.error('The Reference Number field is required.');
                valid = false;
            } else {
                valid = true;
            }
        } else {
            toastr.error('The Payment method field is required.');
            valid = false;
        }
    } else if (payment_method == 'cash') {
        valid = true;
    }

    if (valid) {
        $.ajax({
            'url' : '/check-appointment-batch/'+spa_id+'/'+batch,
            'type' : 'GET',
            'data': {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function () {
    
            },success: function (result) {
                if(result) {
                    toastr.error('NOTICE!!! Remaining appointment with the same batch still under upcoming tab. Please make sure to move each appointment with the same batch to sales before updating the invoice.');
                    return false;
                } else {
                    updateInvoiceConfirmed(spa_id, payment_method, payment_status, payment_account_number, payment_bank_name, id);
                }
            },error: function(xhr, status, error){
                console.log(xhr);
            }
        });
    } else {
        return false;
    }
}

function updateInvoiceConfirmed(spa_id, payment_method, payment_status, payment_account_number, payment_bank_name, id)
{
    swal.fire({
        title: "Are you sure you want to update Therapist?",
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
                'url' : '/sales-update/'+id,
                'type' : 'PUT',
                'data': {
                    payment_method: payment_method,
                    payment_status: payment_status,
                    payment_account_number: payment_account_number,
                    payment_bank_name: payment_bank_name
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#invoice-update-form').find('.update-invoice-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        loadTransactions(spa_id);

                        swal.fire("Done!", result.message, "success");
                        $('#update-invoice-modal').modal('toggle');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }

                    $('#invoice-update-form').find('.update-invoice-btn').val('Save').attr('disabled',false);
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

function startShiftMoney(id, amount)
{
    var spa_id = $('#spa_id_val').val();
    $.ajax({
        'url' : '/pos-update-shift/'+id+'/'+amount+'/start_money',
        'type' : 'PUT',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.btnMoneyOnHand').text('Confirming ... ').attr('disabled',true)
        },
        success: function(result){
            getPosShift(spa_id);
            swal.fire("Done!", result.message, "success");
            $('#money-on-hand-modal').modal('hide');
            $('.btnMoneyOnHand').text('Click here to confirm').attr('disabled',false)
        }
    });
}

function endShiftPost(id)
{
    var spa_id = $('#spa_id_val').val();
    swal.fire({
        title: "Are you sure you want to end your shift?",
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
                'url' : '/pos-update-shift/'+id+'/0/end_shift',
                'type' : 'PUT',
                'data' : {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
        
                },
                success: function(result){
                    getPosShift(spa_id);
                    swal.fire("Done!", result.message, "success");
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
}