var myVals = [];
var UnAvailableRoom = [];
var UnAvailableTherapist = [];
var appointment = [];
var UnAvailableGuest = [];
var searchFilter = [];
var unAvailableTherapistAndRooms = [];

//Newly Update 07/08/2023
$(document).on('click', '.roomView', function () {
    loadRoomAvailability($('#spa_id_val').val());
});

$(document).on('click', '.guestView', function () {
    $('#sales-data-lists').DataTable().ajax.reload(null, false);
});

// $(document).on('click', '.stop-sales-btn', function () {
//     var id = this.id;
//     stopSales(id);
// });

// $(document).on('change', '.select-edit-plus_time, .select-appointment-plus_time, .select-move-plus_time', function () {
//     var select_type = $(this).data("select");
//     var spa_id = $('#spa_id_val').val();
//     var selected = $(this).select2('data');
//     var selected_id = selected[0].id;
    
//     if (select_type == 'edit') {
//         var services = $('.select-edit-services').select2('data');
//         var value_services = services[0].id;

//         $('#edit_plus_time_price').val(0);
//         onChangePlusTime(spa_id, selected_id, '', value_services, 'edit_plus_time_price', 'edit_price', 'totalAmountFormatted', 'totalAmountEditToPay');
//     } else if (select_type == 'appointment') {
//         var id = $(this).data("id");
//         var value_services = $('#appointment_app_services_id'+id).val();
    
//         $('#appointment_plus_time_id'+id).val(selected_id);
//         $('#appointment_plus_time_price'+id).val(0);
//         onChangePlusTime(
//             spa_id, 
//             selected_id, 
//             id, 
//             value_services, 
//             'appointment_plus_time_price', 
//             'price_appointment_walkin', 
//             'total_amount_appointment', 
//             'appointment_total_service_price'
//         );
//     } else if (select_type == 'move') {
//         var value_services = $('#move_app_services_id').val();

//         $('#move_plus_time_id').val(selected_id);
//         $('#move_plus_time_price').val(0);
//         onChangePlusTime(spa_id, selected_id, '', value_services, 'move_plus_time_price', 'price_appointment_move', 'totalAmountMoveAppointmentFormatted', 'totalAmountMoveToPay');
//     }
// });

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

// $(document).on('click', '.reservedInfo', function () {
//     viewReservedRoom($(this).data("transaction_id"));
// });

//Newly Update 07/08/2023
$(document).on('click', '.transactionView', function () {
    $('#transaction-data-lists').DataTable().ajax.reload(null, false);
    // loadTransactions($('#spa_id_val').val());
});
// End Click Functions //

// $(document).on('click', '.view-invoice', function () {
//     viewInvoice(this.id);
// });

//New Update 07/08/2023
$(document).on('click', '.appointmentView', function () {
    $('#appointment-data-lists').DataTable().ajax.reload(null, false);
    // var spa_id = $('#spa_id_val').val();
    // loadAppointments(spa_id);
});

// $(document).on('click', '.view-appointment-btn', function () {
//     var id = this.id;

//     $('#view-appointment-modal').modal('show');
//     viewAppointment(id);
// });

// $(document).on('click', '.edit-appointment-btn', function () {
//     var id = this.id;

//     var currentDate = new Date();
//     var currentDateTime = currentDate.toISOString().slice(0, 16);
//     $("#start_time_appointment_up").attr("min", currentDateTime);

//     $('#update-appointment-modal').modal('show');
//     viewAppointment(id);
// });

// $(document).on('change', '#appointment_name_appointmentup', function () {
//     var val = $(this).val();
    
//     if (val == 'Social Media') {
//         $('.socialMedialUpdate').removeClass('hidden');
//     } else {
//         if (!$('.socialMedialUpdate').hasClass('hidden')) {
//             $('.socialMedialUpdate').addClass('hidden');
//             $('#social_media_appointmentup').val('');
//         }
//     }
// });

// $(document).on('change', '#appointment_name_appointmentmove', function () {
//     var val = $(this).val();

//     if (val == 'Social Media') {
//         $('.socialMedialMove').removeClass('hidden');
//     } else {
//         if (!$('.socialMedialMove').hasClass('hidden')) {
//             $('.socialMedialMove').addClass('hidden');
//             $('#social_media_appointmentmove').val('');
//         }
//     }
// });

// $('.update-appointment-btn').on('click', function() {
//     updateAppointment();
// });

// $(document).on('click', '.move-appointment-btn', function () {
//     var id = this.id;
//     var name = $(this).data("name");
//     var date = $(this).data("date");
//     var spa_id = $('#spa_id_val').val();

//     if (name != '') {
//         $('#move-appointment-modal').modal('show');

//         viewAppointment(id);
//         getPosTherapistApi($('#spa_id_val').val(), date);
//         getPosRoomApi($('#spa_id_val').val(), date);

//         getPlusTime('', 'move_plus_time');
//         getRoomList('', 'move_room');
//         getTherapists(spa_id, 'move', 0);
//     } else {
//         toastr.error('Client Information is missing. Please update Appointment Client Information first.');
//     }
// });

// $('.move-sales-appointment-btn').on('click', function() {
//     processMoveAppointment();
// });

// $(document).on('click','.delete-appointment-btn',function(){
//     var id = this.id;
//     deleteAppointment(id);
// });

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

//Update guest Modal
// $(document).on('select2:close', '.select-edit-services, .select-edit-masseur1, .select-edit-masseur2, .select-edit-plus_time, .select-edit-room', function (e) {
//     var evt = "scroll.select2";
//     $(e.target).parents().off(evt);
//     $(window).off(evt);
// });

//Update appointment
// $(document).on('select2:close', '.select-services-appointment', function (e) {
//     var evt = "scroll.select2";
//     $(e.target).parents().off(evt);
//     $(window).off(evt);
// });

//Move Appointment Modal
// $(document).on('select2:close', '.select-services-move-appointment, .select-move-plus_time, .select-move-masseur1, .select-move-masseur2, .select-move-room', function (e) {
//     var evt = "scroll.select2";
//     $(e.target).parents().off(evt);
//     $(window).off(evt);
// });
  
// $(document).on('change', '.start_time_appointment', function() {
//     var val = $(this).val();
//     $('.start_time_appointment').val(val);
// });

// $('#add-new-appointment-modal').on('hidden.bs.modal', function () {
//     searchFilter = [];
// })

// $(document).on('click', '.btnStartShift', function(e) {
//     e.preventDefault();
//     startShiftPos($('#spa_id_val').val());
// });

// $(document).on('click', '.btnMoneyOnHand', function(e) {
//     e.preventDefault();
//     var money = $('#money_on_hand').val();
//     var id = $('#start_shit_id').val();
//     startShiftMoney(id, money);
// });

// $(document).on('click', '.btnEndShift', function(e) {
//     e.preventDefault();
//     var id = $('#start_shit_id').val();
//     endShiftPost(id);
// });

// $(document).on('click', '.viewEndShiftReport', function (e) {
//     e.preventDefault();
//     loadEndOfShiftReport();
//     $('#view-shift-report').modal('show');
//     $('#start-shift-modal').modal('toggle');
// });

// $('#view-shift-report').on('hidden.bs.modal', function () {
//     $('#start-shift-modal').modal('show');
//     getPosShift($('#spa_id_val').val());
// })

$(document).on('change', '#start_time_appointment_move', function() {
    var val = $(this).val();

    if (val.length > 0) {
        getPosTherapistApi($('#spa_id_val').val(), val);
        getPosRoomApi($('#spa_id_val').val(), val);
    }
});