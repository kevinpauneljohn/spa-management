var myVals = [];
var UnAvailableRoom = [];
var UnAvailableTherapist = [];
var appointment = [];
var UnAvailableGuest = [];

$(document).on('click', '.salesView', function () {
    clickSalesView($('#spa_id_val').val());
});

$(document).on('click', '.edit-sales-btn', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();

    getSalesInfo(id, spa_id);
});

$('.update-sales-btn').on('click', function() {
    var spa_id = $('#spa_id_val').val();
    var amount = $('#totalAmountEditToPay').val();
    var old_amount = $('#totalAmountEditToPayOld').val();

    updateSales(spa_id, amount, old_amount);
});

// Start Change Functions //
$(document).on('change', '.select-edit-services, .select-services-appointment, .select-services-walkin-appointment, .select-services-move-appointment', function () {
    var select_type = $(this).data("select")
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;

    if (select_type == 'edit') {
        $('#edit_price').val(0);
        onChangeServices(spa_id, selected_id, '', 'edit_price','edit_plus_time_price', 'totalAmountFormatted', 'totalAmountEditToPay', 'edit_plus_time');
    } else if (select_type == 'new') {
        var data_id = $(this).data("id")
        $('#price_appointment'+data_id).val('');
        $('#service_name_appointment_id'+data_id).val(selected_id);
    
        onChangeServices(
            spa_id, 
            selected_id, 
            data_id, 
            'price_appointment', 
            'appointment_plus_time_price_optional', 
            'totalAmountUpdateAppointmentFormatted', 
            'totalAmountAppointmentToPay', 
            'plusTimeAppointment'
        );
    } else if (select_type == 'appointment') {
        var data_id = $(this).data("id");
        $('#appointment_app_services_id'+data_id).val(selected_id);
        $('#price_appointment_walkin'+data_id).val(0);
    
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
    } else if (select_type == 'move') {
        $('#move_app_services_id').val(selected_id);
        $('#price_appointment_move').val(0);

        onChangeServices(spa_id, selected_id, '', 'price_appointment_move','move_plus_time_price', 'totalAmountMoveAppointmentFormatted', 'totalAmountMoveToPay', 'move_plus_time');
    }
});

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

$(document).on('change', '.select-appointment-masseur1, .select-edit-masseur1, .select-move-masseur1', function () {
    var select_type = $(this).data("select");
    var data_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var id = selected[0].id;

    UnAvailableTherapist.push(id);
    if (select_type == 'edit') {
        var cur_val = $('#edit_masseur1_id').val();
        if (cur_val !== id) {
            onChangeMasseur('', id, cur_val, 'edit_masseur1_id', 'select-edit-masseur1', 'select-edit-masseur2');
        }
    } else if (select_type == 'appointment') {
        var cur_val = $('#appointment_masseur1'+data_id+'_id').val();
        if (cur_val !== id) {
            onChangeMasseur(data_id, id, cur_val, 'appointment_masseur1'+data_id+'_id', 'select-appointment-masseur1', 'select-appointment-masseur2'); 
        }

        $('#appointmentCustomCheckbox'+data_id).prop('disabled', false);
    } else if (select_type == 'move') {
        var cur_val = $('#move_masseur1_id').val();
        if (cur_val !== id) {
            onChangeMasseur('', id, cur_val, 'move_masseur1_id', 'select-move-masseur1', 'select-move-masseur2');
        }

        $('#moveCustomCheckbox').prop('disabled', false);
    }
});

$(document).on('change', '.select-appointment-masseur2, .select-edit-masseur2, .select-move-masseur2', function () {
    var select_type = $(this).data("select");
    var data_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var id = selected[0].id;

    UnAvailableTherapist.push(id);
    if (select_type == 'edit') {
        var cur_val = $('#edit_masseur2_id').val();
        if (cur_val !== id) {
            onChangeMasseur('', id, cur_val, 'edit_masseur2_id', 'select-edit-masseur1', 'select-edit-masseur2');
        }
    } else if (select_type == 'appointment') {
        var cur_val = $('#appointment_masseur2'+data_id+'_id').val();
        if (cur_val !== id) {
            onChangeMasseur(data_id, id, cur_val, 'appointment_masseur2'+data_id+'_id', 'select-appointment-masseur1', 'select-appointment-masseur2');
        }
    } else if (select_type == 'move') {
        var cur_val = $('#move_masseur2_id').val();
        if (cur_val !== id) {
            onChangeMasseur('', id, cur_val, 'move_masseur2_id', 'select-move-masseur1', 'select-move-masseur2');
        }
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

$(document).on('change', '.select-edit-room, .select-appointment-room, .select-move-room', function () {
    var select_type = $(this).data("select");
    var selected = $(this).select2('data');
    var id = selected[0].id;

    UnAvailableRoom.push(id);
    if (select_type == 'edit') {
        var cur_val = $('#edit_room_val').val();
        if (cur_val !== id) {
            onChangeRoom('', id, cur_val, 'select-edit-room', 'edit_room_val')
        }
    } else if (select_type == 'appointment') {
        var data_id = $(this).data("id");
        var cur_val = $('#appointment_room_id'+data_id).val();
        if (cur_val !== id) {
            onChangeRoom(data_id, id, cur_val, 'select-appointment-room', 'appointment_room_id')
        }
    } else if (select_type == 'move') {
        var cur_val = $('#move_room_id').val();
        if (cur_val !== id) {
            onChangeRoom('', id, cur_val, 'select-move-room', 'move_room_id')
        }
    }
});

$(document).on('click', '.reservedInfo', function () {
    viewReservedRoom($(this).data("transaction_id"));
});

$(document).on('click', '.transactionView', function () {
    loadTransactions($('#spa_id_val').val());
});
// End Click Functions //

$(document).on('click', '.view-invoice', function () {
    viewInvoice(this.id);
});

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

    createAppointmentForm(id, 'inactive', 'no', 'yes');
});

$(document).on('click', '.appointmentTabNav', function () {
    var id = this.id;
    $('.appointmentContent').removeClass('active');
    $('.tabAppointmentContent'+id).addClass('active');
});

$(document).on('click', '.closeTabs', function () {
    var id = this.id;
    var count = $('ul.dataTabsAppointment li').length;

    closeTabs(id, count);
});

$(document).on('change keyup input', '.filterClientAppointment', function () {
    var id = this.id;
    var val = $(this).val();

    var value;
    if (val.length > 0) {
        value = val;
    } else {
        value = 'NoData';
    }

    filterClient(id, value)
});

function getAppointmentTypeforNewGuest(id)
{
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

    $('#social_media_appointment'+id).val(socialMediaVal).change()
    $('#appointment_name_appointment'+id).val(appointmentVal).change()
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
        'appointment'
    );
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

    processAppointment(data);
});

$(document).on('click', '.appointmentTabNav', function () {
    var id = $(this).data("id");

    appointmentSummary(id);
});

$('.add-appointment-btn').on('click', function() {
    submitAppointment();
});

$(document).on('click', '.appointmentView', function () {
    var spa_id = $('#spa_id_val').val();
    loadAppointments(spa_id);
});

$(document).on('click', '.view-appointment-btn', function () {
    var id = this.id;

    $('#view-appointment-modal').modal('show');
    viewAppointment(id);
});

$(document).on('click', '.edit-appointment-btn', function () {
    var id = this.id;

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

$('.update-appointment-btn').on('click', function() {
    updateAppointment();
});

$(document).on('click', '.move-appointment-btn', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();

    $('#move-appointment-modal').modal('show');
    viewAppointment(id);
    getPlusTime('', 'move_plus_time');
    getRoomList('', 'move_room');
    getTherapists(spa_id, 'move', 0);
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

    $('.updateInvoiceTitle').html('Update '+invoice_no);
    $('#sales_invoice_id').val(id);
    $('#payment_method').val(payment_method).change();
    if (payment_method == 'bank') {
        if(!$('.payment_bank_name').hasClass('hidden')) {
            $('.payment_bank_name').removeClass('hidden');
        }

        $('#payment_bank_name').val(bank_name);
    } else {
        $('.payment_bank_name').addClass('hidden');
    }
    $('#payment_account_number').val(account_number);
    
    $('#payment_status').val(status).change();
    $('#update-invoice-modal').modal('show');
});

$(document).on('change', '#payment_method', function () {
    var val = $(this).val();

    if (val == 'bank') {
        $('.payment_bank_name').removeClass('hidden');
        if ($('.account_number_div').hasClass('hidden')) {
            $('.account_number_div').removeClass('hidden');
        }
    } else if (val == 'cash') {
        $('.account_number_div').addClass('hidden');
    } else {
        if (!$('.payment_bank_name').hasClass('hidden')) {
            $('.payment_bank_name').addClass('hidden');
        }

        if ($('.account_number_div').hasClass('hidden')) {
            $('.account_number_div').removeClass('hidden');
        }
    }
});

$(document).on('click','.update-invoice-btn',function(){
    var id =  $('#sales_invoice_id').val();
    updateInvoice(id);
});

function ReplaceNumberWithCommas(value) {
    var n= value.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return n.join(".");
}

function removeValue(list, value) {
    list = list.split(',');
    list.splice(list.indexOf(value), 1);
    return list.join(',');
}