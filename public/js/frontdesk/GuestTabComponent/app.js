var guestTabSpaId = $('.spaId').val();
$(document).on('click', '.edit-sales-btn', function () {
    var id = this.id;
    getSalesInfo(id);
});

$(document).on('click', '.stop-sales-btn', function () {
    var id = this.id;
    stopSales(id);
});

$('.select-edit-services').on('select2:select', function(e) {
    var value = e.params.data;
    $('#edit_services_id').val(value.id);
    $('#edit_services_name').val(value.text);

    getEditServiceById(value.id);
    onChangeEditServices(guestTabSpaId, value.id);
});

function onChangeEditServices(guestTabSpaId, selected_service_id)
{
    var selected_plus_time_id = $('#edit_plus_time_id').val();
    if (selected_service_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_service_id+'/'+guestTabSpaId,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#edit_price').val(result);
                var price = parseInt(result) + parseInt($('#edit_plus_time_price').val());
    
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountFormatted').html(price_formatted);
                $('#totalAmountEditToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#edit_price').val()) + parseInt($('#edit_plus_time_price').val());
    
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountFormatted').html(price_formatted);
        $('#totalAmountEditToPay').val(price);
    }

    onChangePlusTimeEdit(guestTabSpaId, selected_plus_time_id, selected_service_id)
}

$('.select-edit-plus_time').on('select2:select', function(e) {
    var value = e.params.data;
    var value_services_id = $('#edit_services_id').val();
    $('#edit_plus_time_id').val(value.id);
    $('#edit_plus_time_price').val(0);

    onChangePlusTimeEdit(guestTabSpaId, value.id, value_services_id);
});

function onChangePlusTimeEdit(guestTabSpaId, selected_plus_time_id, value_services_id)
{
    if (selected_plus_time_id != '' && value_services_id != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services_id+'/'+guestTabSpaId+'/'+selected_plus_time_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#edit_plus_time_price').val(result);
                var price = parseInt(result) + parseInt($('#edit_price').val());
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountFormatted').html(price_formatted);
                $('#totalAmountEditToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#edit_plus_time_price').val()) + parseInt($('#edit_price').val());
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountFormatted').html(price_formatted);
        $('#totalAmountEditToPay').val(price);
    }
}

$('.select-edit-masseur1').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#edit_masseur1_id').val();
    if (cur_val !== id) {   
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeEditMasseur(id, cur_val, 'edit_masseur1_id'); 
    }

});

$('.select-edit-masseur2').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedTherapist.push(id);
    var cur_val = $('#edit_masseur2_id').val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeEditMasseur(id, cur_val, 'edit_masseur2_id');
    }
});

function onChangeEditMasseur(id, cur_val, field)
{
    $('.select-edit-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
    $('.select-edit-masseur2').children('option[value="' + id + '"]').attr('disabled', true);

    $('.select-edit-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
    $('.select-edit-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);

    $('#'+field).val(id);

    $('.select-edit-masseur1').select2({
        placeholder: "Choose Masseur 1",
        allowClear: false
    });

    $('.select-edit-masseur2').select2({
        placeholder: "Choose Masseur 2",
        allowClear: false
    });
}

$('.select-edit-room').on('select2:select', function(e) {
    var value = e.params.data;
    var id = value.id;

    filterPreSelectedRoom.push(id);
    var cur_val = $('#edit_room_val').val();
    if (cur_val !== id) {
        if (cur_val.length > 0) {
            filterPreSelectedRoom = $.grep(filterPreSelectedRoom, function(element){
                return element !== cur_val;
            }); 
        } 
        onChangeEditRoom(id, cur_val)
    }
});

function onChangeEditRoom(id, cur_val)
{
    $('.select-edit-room').children('option[value="' + id + '"]').attr('disabled', true);
    $('.select-edit-room').children('option[value="' + cur_val + '"]').attr('disabled', false);
    $('#edit_room_val').val(id);

    $('.select-edit-room').select2({
        placeholder: "Choose Room",
        allowClear: false
    });
}