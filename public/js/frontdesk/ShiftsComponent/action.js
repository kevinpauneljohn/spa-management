function getPosShift(spa_id)
{
    $.ajax({
        'url' : '/pos-get-shift/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#start_shit_id').val('');
            $('.shiftMessage').html('');
        },
        success: function(result){
            if (result.status) {
                if (result.shift_today) {
                    if (result.end_shift) {
                        $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                        $('#start-shift-modal').modal('show');
                        $('#start-shift-form').find('.btnStartShift').text('Click here to start your shift again');
                    } else {
                        // Confirm money on hand
                        if (!result.money_confirm) {
                            $('#money-on-hand-modal').modal('show');
                        }
                    }

                    if (!$('.btnEndShift').hasClass('hidden')) {
                        $('.btnEndShift').addClass('hidden');
                    }
                } else {
                    if (!result.end_shift) {
                        $('.shiftMessage').html('Warning!! System noticed that you forgot to End your shift. Please click the button below to end your shift.');
                        $('.viewEndShiftReport').addClass('hidden');
                        $('#start-shift-modal').modal('show');
                        $('.btnStartShift').addClass('hidden');
                        $('.btnEndShift').removeClass('hidden');
                        $('#start-shift-form').find('.btnEndShift').text('Click here to End your shift');
                    } else {
                        // New Shift
                        $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                        $('.btnEndShift').addClass('hidden');

                        if ($('.btnStartShift').hasClass('hidden')) {
                            $('.btnStartShift').removeClass('hidden');
                        }

                        $('.viewEndShiftReport').removeClass('hidden');
                        $('#start_shit_id').val('');
                        $('#start-shift-modal').modal('show');
                        $('#start-shift-form').find('.btnStartShift').text('Click here to start your new shift');
                    }
                }

                $('#start_shit_id').val(result.data.id);
            } else {
                //First Time Log Shift
                $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                $('#start_shit_id').val('');
                $('.viewEndShiftReport').addClass('hidden');
                $('#start-shift-form').find('.btnStartShift').text('Click here to start your new shift');
                $('#start-shift-modal').modal('show');
            }
        }
    });
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
            loadData(spa_id);
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