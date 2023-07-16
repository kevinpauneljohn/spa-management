var guestTabSpaId = $('.spaId').val();
$(document).on('click', '.view-appointment-btn', function () {
    var id = this.id;
    viewAppointment(id);
});

$(document).on('click', '.edit-appointment-btn', function () {
    var id = this.id;

    editViewAppointment(id);
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
    var name = $(this).data("name");
    var date = $(this).data("date");

    if (name != '') {
        moveViewAppointment(id);
        getMoveServicesAppointment(guestTabSpaId);
        // getMovePlusTime();
        // getMovePosTherapistApi(guestTabSpaId);
        // getMovePosRoomApi(guestTabSpaId);

        // getPosTherapistApi($('#spa_id_val').val(), date);
        // getPosRoomApi($('#spa_id_val').val(), date);

        // getPlusTime('', 'move_plus_time');
        // getRoomList('', 'move_room');
        // getTherapists(spa_id, 'move', 0);
    } else {
        toastr.error('Client Information is missing. Please update Appointment Client Information first.');
    }
});

$(document).on('change', '#start_time_appointment_move', function () {
    $('#price_appointment_move').val(0);
    $('#move_app_services_id').val('');
    $('#move_app_services_name').val('');
    $('#move_app_services_multiple').val('');

    $('#move_plus_time_price').val(0);
    $('#move_plus_time_id').val('');

    $('.totalAmountMoveAppointmentFormatted').html('&#8369; 0.00');
    $('#totalAmountMoveToPay').val(0);
    
    getMoveServicesAppointment(guestTabSpaId);
    getMovePlusTime();
});

$('.select-services-move-appointment').on('select2:select', function(e) {
    var value = e.params.data;
    $('#move_app_services_id').val(value.id);
    $('#move_app_services_name').val(value.text);

    $('.select-move-plus_time').prop('disabled', false);
    getMovePlusTime();
    getMoveServiceById(guestTabSpaId, value.id);
    onChangeMoveServices(guestTabSpaId, value.id);
});

function onChangeMoveServices(spa_id, selected_services_id)
{
    var selected_plus_time_id = $('#move_plus_time_id').val();
    if (selected_services_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_services_id+'/'+spa_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#price_appointment_move').val(result);
                var price = parseInt(result) + parseInt($('#move_plus_time_price').val());
    
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
                $('#totalAmountMoveToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#price_appointment_move').val()) + parseInt($('#move_plus_time_price').val());
    
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
        $('#totalAmountMoveToPay').val(price);
    }

    onChangePlusTimeMove(guestTabSpaId, selected_plus_time_id, selected_services_id);
}

$('.select-move-plus_time').on('select2:select', function(e) {
    var value = e.params.data;
    var value_services_id = $('#move_app_services_id').val();
    $('#move_plus_time_id').val(value.id);
    $('#move_plus_time_price').val(0);

    onChangePlusTimeMove(guestTabSpaId, value.id, value_services_id);
});

function onChangePlusTimeMove(spa_id, selected_plus_time_id, value_services_id)
{
    if (selected_plus_time_id != '' && value_services_id != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services_id+'/'+spa_id+'/'+selected_plus_time_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#move_plus_time_price').val(result);
                var price = parseInt(result) + parseInt($('#price_appointment_move').val());
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
                $('#totalAmountMoveToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#move_plus_time_price').val()) + parseInt($('#price_appointment_move').val());
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
        $('#totalAmountMoveToPay').val(price);
    }
}

$('.select-move-masseur1').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#move_masseur1_id').val();
    if (cur_val !== id) {   
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        } 
        $('#move_masseur1_id').val(id);
        onChangeMoveMasseur(id, cur_val); 
    }

});

$('.select-move-masseur2').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#move_masseur2_id').val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        }
        $('#move_masseur2_id').val(id);
        onChangeMoveMasseur(id, cur_val);
    }
});

function onChangeMoveMasseur(id, cur_val)
{
    $('.select-move-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
    $('.select-move-masseur2').children('option[value="' + id + '"]').attr('disabled', true);

    $('.select-move-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
    $('.select-move-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);

    $('.select-move-masseur1').select2({
        placeholder: "Choose Masseur 1",
        allowClear: false
    });

    $('.select-move-masseur2').select2({
        placeholder: "Choose Masseur 2",
        allowClear: false
    });
}

$('.select-move-room').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedRoom.push(id);
    var cur_val = $('#move_room_id').val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedRoom = $.grep(filterPreSelectedRoom, function(element){
                return element !== cur_val;
            }); 
        } 
        $('#move_room_id').val(id);
        onChangeMoveRoom(id, cur_val)
    }
});

function onChangeMoveRoom(id, cur_val)
{
    $('.select-move-room').children('option[value="' + id + '"]').attr('disabled', true);
    $('.select-move-room').children('option[value="' + cur_val + '"]').attr('disabled', false);

    $('.select-move-room').select2({
        placeholder: "Choose Room",
        allowClear: false
    });
}

$('.move-sales-appointment-btn').on('click', function() {
    processMoveAppointment();
});

$(document).on('click','.delete-appointment-btn',function(){
    var id = this.id;
    deleteAppointment(id);
});