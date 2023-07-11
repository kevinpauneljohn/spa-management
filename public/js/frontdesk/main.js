var myVals = [];
var UnAvailableRoom = [];
var UnAvailableTherapist = [];
var appointment = [];
var UnAvailableGuest = [];
var searchFilter = [];
var unAvailableTherapistAndRooms = [];

//Newly Update 07/08/2023
$(document).on('click', '.guestView', function () {
    // clickSalesView($('#spa_id_val').val());
    $('#sales-data-lists').DataTable().ajax.reload(null, false);
});

// $(document).on('click', '.edit-sales-btn', function () {
//     var id = this.id;
//     var end_date = $(this).data("end_date");
//     var spa_id = $('#spa_id_val').val();

//     getPosTherapistApi($('#spa_id_val').val(), end_date);
//     getPosRoomApi($('#spa_id_val').val(), end_date);
//     getSalesInfo(id, spa_id);
// });

$(document).on('click', '.stop-sales-btn', function () {
    var id = this.id;
    stopSales(id);
});

// $('.update-sales-btn').on('click', function() {
//     var spa_id = $('#spa_id_val').val();
//     var amount = $('#totalAmountEditToPay').val();
//     var old_amount = $('#totalAmountEditToPayOld').val();

//     updateSales(spa_id, amount, old_amount);
// });

// Start Change Functions //
// $(document).on('change', '.select-edit-services, .select-services-appointment, .select-services-walkin-appointment, .select-services-move-appointment', function () {
//     var select_type = $(this).data("select")
//     var spa_id = $('#spa_id_val').val();
//     var selected = $(this).select2('data');
//     var selected_id = selected[0].id;

//     if (select_type == 'edit') {
//         $('#edit_price').val(0);
//         onChangeServices(spa_id, selected_id, '', 'edit_price','edit_plus_time_price', 'totalAmountFormatted', 'totalAmountEditToPay', 'edit_plus_time');
//     } else if (select_type == 'new') {
//         var data_id = $(this).data("id")
//         $('#price_appointment'+data_id).val('');
//         $('#service_name_appointment_id'+data_id).val(selected_id);
    
//         onChangeServices(
//             spa_id, 
//             selected_id, 
//             data_id, 
//             'price_appointment', 
//             'appointment_plus_time_price_optional', 
//             'totalAmountUpdateAppointmentFormatted', 
//             'totalAmountAppointmentToPay', 
//             'plusTimeAppointment'
//         );
//     } else if (select_type == 'appointment') {
//         var data_id = $(this).data("id");
//         $('#appointment_app_services_id'+data_id).val(selected_id);
//         $('#price_appointment_walkin'+data_id).val(0);
    
//         onChangeServices(
//             spa_id, 
//             selected_id, 
//             data_id, 
//             'price_appointment_walkin', 
//             'appointment_plus_time_price', 
//             'total_amount_appointment', 
//             'appointment_total_service_price', 
//             'plus_time_appointment'
//         );
//     } else if (select_type == 'move') {
//         $('#move_app_services_id').val(selected_id);
//         $('#price_appointment_move').val(0);

//         onChangeServices(spa_id, selected_id, '', 'price_appointment_move','move_plus_time_price', 'totalAmountMoveAppointmentFormatted', 'totalAmountMoveToPay', 'move_plus_time');
//     }
// });

$(document).on('change', '.select-edit-plus_time, .select-appointment-plus_time, .select-move-plus_time', function () {
    var select_type = $(this).data("select");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    if (select_type == 'edit') {
        var services = $('.select-edit-services').select2('data');
        var value_services = services[0].id;

        $('#edit_plus_time_price').val(0);
        onChangePlusTime(spa_id, selected_id, '', value_services, 'edit_plus_time_price', 'edit_price', 'totalAmountFormatted', 'totalAmountEditToPay');
    } else if (select_type == 'appointment') {
        var id = $(this).data("id");
        var value_services = $('#appointment_app_services_id'+id).val();
    
        $('#appointment_plus_time_id'+id).val(selected_id);
        $('#appointment_plus_time_price'+id).val(0);
        onChangePlusTime(
            spa_id, 
            selected_id, 
            id, 
            value_services, 
            'appointment_plus_time_price', 
            'price_appointment_walkin', 
            'total_amount_appointment', 
            'appointment_total_service_price'
        );
    } else if (select_type == 'move') {
        var value_services = $('#move_app_services_id').val();

        $('#move_plus_time_id').val(selected_id);
        $('#move_plus_time_price').val(0);
        onChangePlusTime(spa_id, selected_id, '', value_services, 'move_plus_time_price', 'price_appointment_move', 'totalAmountMoveAppointmentFormatted', 'totalAmountMoveToPay');
    }
});

// End Change Functions //

// Start Click Functions //
$(document).on('click', '.isEditMultipleMasseur, .isAppointmentMultipleMasseur, .isMoveMultipleMasseur', function () {
    var select_type = $(this).data("select");

    if (select_type == 'edit') {
        var therapist_2_val = $('#edit_masseur2_id').val();

        multipleMasseurCheckbox(
            '',
            therapist_2_val, 
            'editCustomCheckbox', 
            'select-edit-masseur1', 
            'select-edit-masseur2', 
            'edit_masseur2_id', 
            'edit_masseur2'
        );
    } else if (select_type == 'appointment') {
        var id = $(this).data("id");
        var therapist_2_val = $('#appointment_masseur2'+id+'_id').val();
    
        multipleMasseurCheckbox(
            id, 
            therapist_2_val, 
            'appointmentCustomCheckbox', 
            'select-appointment-masseur1', 
            'select-appointment-masseur2', 
            'appointment_masseur2'+id+'_id', 
            'appointment_masseur2'
        );
    } else if (select_type == 'move') {
        var therapist_2_val = $('#move_masseur2_id').val();

        multipleMasseurCheckbox(
            '',
            therapist_2_val, 
            'moveCustomCheckbox', 
            'select-move-masseur1', 
            'select-move-masseur2', 
            'move_masseur2_id', 
            'move_masseur2'
        );
    }
});

// $(document).on('change', '.select-edit-room, .select-appointment-room, .select-move-room', function () {
//     var select_type = $(this).data("select");
//     var selected = $(this).select2('data');
//     var id = selected[0].id;
//     UnAvailableRoom.push(id);

//     if (select_type == 'edit') {
//         var cur_val = $('#edit_room_val').val();
//         if (cur_val !== id) {
//             onChangeRoom('', id, cur_val, 'select-edit-room', 'edit_room_val')
//         }
//     } else if (select_type == 'appointment') {
//         var data_id = $(this).data("id");
//         var cur_val = $('#appointment_room_id'+data_id).val();
//         if (cur_val !== id) {
//             onChangeRoom(data_id, id, cur_val, 'select-appointment-room', 'appointment_room_id')

//             array = {
//                 guest_id: data_id,
//                 value: id,
//                 therapist: null,
//                 type: 'room'
//             };
    
//             unAvailableTherapistAndRooms.push(array);

//             console.log(unAvailableTherapistAndRooms);
//         }
//     } else if (select_type == 'move') {
//         var cur_val = $('#move_room_id').val();
//         if (cur_val !== id) {
//             onChangeRoom('', id, cur_val, 'select-move-room', 'move_room_id')
//         }
//     }
// });

$(document).on('click', '.reservedInfo', function () {
    viewReservedRoom($(this).data("transaction_id"));
});

//Newly Update 07/08/2023
$(document).on('click', '.transactionView', function () {
    $('#transaction-data-lists').DataTable().ajax.reload(null, false);
    // loadTransactions($('#spa_id_val').val());
});
// End Click Functions //

$(document).on('click', '.view-invoice', function () {
    viewInvoice(this.id);
});

function getAppointmentTypeforNewGuest(id)
{
    var spa_id = $('#spa_id_val').val();
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;

    var appointmentVal = '';
    if ($('#appointment_name_appointment'+firstLiId).val() != '') {
        appointmentVal = $('#appointment_name_appointment'+firstLiId).val();
    }

    var socialMediaVal = '';
    if ($('#social_media_appointment'+firstLiId).val() != '') {
        socialMediaVal = $('#social_media_appointment'+firstLiId).val();
    }

    $('#social_media_appointment'+id).val(socialMediaVal).change();
    $('#appointment_name_appointment'+id).val(appointmentVal).change();

    var startTimeval = '';
    if (appointmentVal == 'Walk-in') {
        if ($('#reservenow'+firstLiId).is(':checked')) {
            startTimeval = $('#start_time_appointment_walkin'+firstLiId).val();
            $('.start_time_appointment_walkin').val(startTimeval);    
            $('.reserveNow').prop('checked', true);
            $('.reserveLater').prop('checked', false);
    
            $('.defaultOptionalService').addClass('hidden');
    
            $('.requiredService').removeClass('hidden');
            if (startTimeval.length > 0) {
                $('.walkInHiddenDiv').removeClass('hidden');
                getPosTherapistApi($('#spa_id_val').val(), startTimeval);
                getPosRoomApi($('#spa_id_val').val(), startTimeval);
            }
    
            getPlusTime(id, 'plus_time_appointment');
            getRoomList(id, 'appointment_room');
            getTherapists(spa_id, 'appointment', id);
        } else if ($('#reservelater'+firstLiId).is(':checked')) {
            startTimeval = $('#start_time_appointment'+firstLiId).val();
            $('.start_time_appointment').val(startTimeval);
            $('.reserveNow').prop('checked', false);
            $('.reserveLater').prop('checked', true);
    
            $('.defaultOptionalService').removeClass('hidden');
            $('.requiredService').addClass('hidden');
            $('.walkInHiddenDiv').addClass('hidden'); 
        } else {
            $('.defaultOptionalService').addClass('hidden');
            $('.requiredService').addClass('hidden');
            $('.walkInHiddenDiv').addClass('hidden');
        }
    } else {
        startTimeval = $('#start_time_appointment'+firstLiId).val();
        $('.start_time_appointment').val(startTimeval); 
    }
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

//New Update 07/08/2023
$(document).on('click', '.appointmentView', function () {
    $('#appointment-data-lists').DataTable().ajax.reload(null, false);
    // var spa_id = $('#spa_id_val').val();
    // loadAppointments(spa_id);
});

$(document).on('click', '.view-appointment-btn', function () {
    var id = this.id;

    $('#view-appointment-modal').modal('show');
    viewAppointment(id);
});

$(document).on('click', '.edit-appointment-btn', function () {
    var id = this.id;

    var currentDate = new Date();
    var currentDateTime = currentDate.toISOString().slice(0, 16);
    $("#start_time_appointment_up").attr("min", currentDateTime);

    $('#update-appointment-modal').modal('show');
    viewAppointment(id);
});

$(document).on('change', '#appointment_name_appointmentup', function () {
    var val = $(this).val();
    
    if (val == 'Social Media') {
        $('.socialMedialUpdate').removeClass('hidden');
    } else {
        if (!$('.socialMedialUpdate').hasClass('hidden')) {
            $('.socialMedialUpdate').addClass('hidden');
            $('#social_media_appointmentup').val('');
        }
    }
});

$(document).on('change', '#appointment_name_appointmentmove', function () {
    var val = $(this).val();

    if (val == 'Social Media') {
        $('.socialMedialMove').removeClass('hidden');
    } else {
        if (!$('.socialMedialMove').hasClass('hidden')) {
            $('.socialMedialMove').addClass('hidden');
            $('#social_media_appointmentmove').val('');
        }
    }
});

$('.update-appointment-btn').on('click', function() {
    updateAppointment();
});

$(document).on('click', '.move-appointment-btn', function () {
    var id = this.id;
    var name = $(this).data("name");
    var date = $(this).data("date");
    var spa_id = $('#spa_id_val').val();

    if (name != '') {
        $('#move-appointment-modal').modal('show');

        viewAppointment(id);
        getPosTherapistApi($('#spa_id_val').val(), date);
        getPosRoomApi($('#spa_id_val').val(), date);

        getPlusTime('', 'move_plus_time');
        getRoomList('', 'move_room');
        getTherapists(spa_id, 'move', 0);
    } else {
        toastr.error('Client Information is missing. Please update Appointment Client Information first.');
    }
});

$('.move-sales-appointment-btn').on('click', function() {
    processMoveAppointment();
});

$(document).on('click','.delete-appointment-btn',function(){
    var id = this.id;
    deleteAppointment(id);
});

$(document).on('click', '.update-invoice', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();
    var invoice_no = $(this).data("invoice");
    var payment_method = $(this).data("payment");
    var account_number = $(this).data("account");
    var bank_name = $(this).data("bank");
    var status = $(this).data("status");
    var batch = $(this).data("batch");
    var amount = $(this).data("amount");
    var amount_to_pay = $(this).data("pay");

    $('.payment_bank_name').addClass('hidden');
    $('.account_number_div').addClass('hidden');

    $('.updateInvoiceTitle').html('Update '+invoice_no);
    $('#sales_invoice_id').val(id);
    $('#sales_batch_id').val(batch);
    $('#total_transaction_amount').val(amount);
    $('#transaction_amount').val(amount_to_pay);
    $('#payment_method').val(payment_method).change();
    if (payment_method == 'bank') {
        $('.payment_bank_name').removeClass('hidden');
        $('#payment_bank_name').val(bank_name);

        $('#payment_account_number').val(account_number);
        $('.account_number_div').removeClass('hidden');
    } else if (payment_method == 'gcash' || payment_method == 'paymaya') {
        $('.payment_bank_name').addClass('hidden');
        $('#payment_bank_name').val('');

        $('#payment_account_number').val(account_number);
        $('.account_number_div').removeClass('hidden');
    } else {
        $('#payment_bank_name').val('');
        $('.payment_bank_name').addClass('hidden');

        $('#payment_account_number').val('');
        $('.account_number_div').addClass('hidden');
    }

    $('#payment_status').val(status).change();
    $('#update-invoice-modal').modal('show');
});

$(document).on('change', '#payment_method', function () {
    var val = $(this).val();

    if (val == 'bank') {
        $('.payment_bank_name').removeClass('hidden');
        $('.account_number_div').removeClass('hidden');
        $('#payment_account_number').val('');
        $('#payment_bank_name').val('');

        $('.cash_amount').addClass('hidden');
        $('.cash_change').addClass('hidden');
        $('.transaction_amount').addClass('hidden');
        $('#cash_change').val(0);
        $('#cash_change_amount').val(0);
    } else if (val == 'gcash' || val == 'paymaya') {
        $('.account_number_div').removeClass('hidden');
        $('#payment_account_number').val('');

        $('.payment_bank_name').addClass('hidden');
        $('#payment_bank_name').val('');

        $('.cash_amount').addClass('hidden');
        $('.cash_change').addClass('hidden');
        $('.transaction_amount').addClass('hidden');
        $('#cash_change').val(0);
        $('#cash_change_amount').val(0);
    } else if (val == 'cash') {
        $('.account_number_div').addClass('hidden');
        $('#payment_account_number').val('');
        $('.cash_amount').removeClass('hidden');
        $('.cash_change').removeClass('hidden');
        $('.transaction_amount').removeClass('hidden');

        $('.payment_bank_name').addClass('hidden');
        $('#payment_bank_name').val('');
    }

    $('#error-payment_method').addClass('hidden');
    $('#error-payment_method').text('');
});

$(document).on('change, keyup', '#cash_amount', function () {
    var total = $('#total_transaction_amount').val();
    var cash = $(this).val();
    var change = cash - total;

    $('#cash_change').val(change.toFixed(2));
    $('#cash_change_amount').val(change);
});

$(document).on('change, keyup', '#payment_account_number', function () {
    if ($(this).val().length > 0) {
        $('#error-payment_account_number').addClass('hidden');
        $('#error-payment_account_number').text('');
    } else {
        $('#error-payment_account_number').removeClass('hidden');
        $('#error-payment_account_number').text('Account Number field is required!');
    }
});

$(document).on('change, keyup', '#payment_bank_name', function () {
    if ($(this).val().length > 0) {
        $('#error-payment_bank_name').addClass('hidden');
        $('#error-payment_bank_name').text('');
    } else {
        $('#error-payment_bank_name').removeClass('hidden');
        $('#error-payment_bank_name').text('Bank Name field is required!');
    }
});

$(document).on('click','.update-invoice-btn',function(){
    var id =  $('#sales_invoice_id').val();
    updateInvoice(id);
});

//New Appointment Modal with Walk-In type
// $(document).on('select2:close', '.select-services-walkin-appointment, .select-appointment-plus_time, .select-appointment-masseur1, .select-appointment-masseur2, .select-appointment-room', function (e) {
//     var evt = "scroll.select2";
//     $(e.target).parents().off(evt);
//     $(window).off(evt);
// });

//Update guest Modal
$(document).on('select2:close', '.select-edit-services, .select-edit-masseur1, .select-edit-masseur2, .select-edit-plus_time, .select-edit-room', function (e) {
    var evt = "scroll.select2";
    $(e.target).parents().off(evt);
    $(window).off(evt);
});

//Update appointment
$(document).on('select2:close', '.select-services-appointment', function (e) {
    var evt = "scroll.select2";
    $(e.target).parents().off(evt);
    $(window).off(evt);
});

//Move Appointment Modal
$(document).on('select2:close', '.select-services-move-appointment, .select-move-plus_time, .select-move-masseur1, .select-move-masseur2, .select-move-room', function (e) {
    var evt = "scroll.select2";
    $(e.target).parents().off(evt);
    $(window).off(evt);
});
  
// $(document).on('change', '.reserveOption', function() {
//     var id = $(this).data("id");
//     var value = $(this).data("value");
//     var spa_id = $('#spa_id_val').val();

//     if(this.checked) {
//         if (value == 'reserved_now') {
//             $('.start_time_appointment').val(("mm/dd/yyyy --:-- --"));
//             $('.reserveNow').prop('checked', true);
//             $('.reserveLater').prop('checked', false);

//             $('.defaultOptionalService').addClass('hidden');

//             $('.requiredService').removeClass('hidden');
//             // $('.walkInHiddenDiv').removeClass('hidden');

//             // getPosTherapistApi($('#spa_id_val').val(), $('.start_time_appointment').val());
//             // getPosRoomApi($('#spa_id_val').val(), $('.start_time_appointment').val());

//             getPlusTime(id, 'plus_time_appointment');
//             getRoomList(id, 'appointment_room');
//             getTherapists(spa_id, 'appointment', id);
//         } else if (value == 'reserved_later') {
//             $('.start_time_appointment_walkin').val(("mm/dd/yyyy --:-- --"));
//             $('.reserveNow').prop('checked', false);
//             $('.reserveLater').prop('checked', true);

//             $('.defaultOptionalService').removeClass('hidden');
//             $('.requiredService').addClass('hidden');
//             $('.walkInHiddenDiv').addClass('hidden');
//         }
//     } else {
//         $('.reserveNow').prop('checked', false);
//         $('.reserveLater').prop('checked', false);
//         $('.defaultOptionalService').addClass('hidden');
//         $('.requiredService').addClass('hidden');
//         $('.walkInHiddenDiv').addClass('hidden');
//     }
// });

$(document).on('change', '.start_time_appointment', function() {
    var val = $(this).val();
    $('.start_time_appointment').val(val);
});

// $(document).on('change', '.start_time_appointment_walkin', function() {
//     var val = $(this).val();

//     if (val.length > 0) {
//         $('.walkInHiddenDiv').removeClass('hidden');
//         $('.start_time_appointment_walkin').val(val);
//         getPosTherapistApi($('#spa_id_val').val(), val);
//         getPosRoomApi($('#spa_id_val').val(), val);
//     }
// });

$('#add-new-appointment-modal').on('hidden.bs.modal', function () {
    searchFilter = [];
})

$(document).on('click', '.btnStartShift', function(e) {
    e.preventDefault();
    startShiftPos($('#spa_id_val').val());
});

$(document).on('click', '.btnMoneyOnHand', function(e) {
    e.preventDefault();
    var money = $('#money_on_hand').val();
    var id = $('#start_shit_id').val();
    startShiftMoney(id, money);
});

$(document).on('click', '.btnEndShift', function(e) {
    e.preventDefault();
    var id = $('#start_shit_id').val();
    endShiftPost(id);
});

$(document).on('click', '.viewEndShiftReport', function (e) {
    e.preventDefault();
    loadEndOfShiftReport();
    $('#view-shift-report').modal('show');
    $('#start-shift-modal').modal('toggle');
});

$('#view-shift-report').on('hidden.bs.modal', function () {
    $('#start-shift-modal').modal('show');
    getPosShift($('#spa_id_val').val());
})

$(document).on('change', '#start_time_appointment_move', function() {
    var val = $(this).val();

    if (val.length > 0) {
        getPosTherapistApi($('#spa_id_val').val(), val);
        getPosRoomApi($('#spa_id_val').val(), val);
    }
});