function updateSales(spa_id, amount, old_amount)
{
    var services = $('#edit_services').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var masseur2_id = $('#edit_masseur2_id_val').val();
    var plus_time = $('#edit_plus_time').select2('data');
    var value_plus_time = plus_time[0].id;
    var room_id = $('#edit_room').select2('data');
    var value_room_id = room_id[0].id;

    swal.fire({
        title: "Are you sure you want to update the reservation?",
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
                    id: $('#edit_transaction_id').val(),
                    client_id: $('#edit_client_id').val(),
                    sales_id: $('#edit_sales_id').val(),
                    firstname: $('#edit_first_name').val(),
                    middlename: $('#edit_middle_name').val(),
                    lastname: $('#edit_last_name').val(),
                    date_of_birth: $('#edit_date_of_birth').val(),
                    mobile_number: $('#edit_mobile_number').val(),
                    email: $('#edit_email').val(),
                    address: $('#edit_address').val(),
                    client_type: $('#edit_client_type').val(),
                    service_id: value_services,
                    service_name: value_services_name,
                    therapist_1: $('#edit_masseur1_id').val(),
                    therapist_2: $('#edit_masseur2_id').val(),
                    therapist_2_id: masseur2_id,
                    start_time: $('#edit_start_time').val(),
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
    var email = $('#edit_app_email').val();
    var address = $('#edit_app_address').val();
    var appointment_type = $('#appointment_name_appointmentup').val();
    var appointment_social = $('#social_media_appointmentup').val();
    var services = $('#edit_app_servicesup').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var price = $('#price_appointment_up').val();
    var start_time = $('#start_time_appointment_up').val();

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
                    value_services: value_services,
                    value_services_name: value_services_name,
                    price: price,
                    start_time: start_time
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

function updateInvoice(id)
{
    var spa_id = $('#spa_id_val').val();
    var payment_method = $('#payment_method').val();
    var payment_status = $('#payment_status').val();
    var payment_account_number = $('#payment_account_number').val();
    var  payment_bank_name = $('#payment_bank_name').val();

    var valid = false;
    if (payment_method !== 'cash') {
        if (payment_method == 'bank') {
            if (payment_bank_name.length < 1) {
                $('#error-payment_bank_name').removeClass('hidden');
                $('#error-payment_bank_name').text('Bank Name field is required!');
            } else {
                $('#error-payment_bank_name').addClass('hidden');
                $('#error-payment_bank_name').text('');
            }
    
            if (payment_account_number.length < 1) {
                $('#error-payment_account_number').removeClass('hidden');
                $('#error-payment_account_number').text('Account Number field is required!');
            } else {
                $('#error-payment_account_number').addClass('hidden');
                $('#error-payment_account_number').text('');
            }

            if (payment_bank_name.length > 1 && payment_account_number.length > 1) {
                valid = true;
            }
        } else  if (payment_method == 'gcash' || payment_method == 'paymaya') {
            if (payment_account_number.length < 1) {
                $('#error-payment_account_number').removeClass('hidden');
                $('#error-payment_account_number').text('Account Number field is required!');
            } else {
                $('#error-payment_account_number').addClass('hidden');
                $('#error-payment_account_number').text('');

                valid = true;
            }
        } else {
            $('#error-payment_method').removeClass('hidden');
            $('#error-payment_method').text('Payment method field is required!');
        }
    } else if (payment_method == 'cash') {
        valid = true;
    }

    if (valid) {
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
    } else {
        return false;
    }
}