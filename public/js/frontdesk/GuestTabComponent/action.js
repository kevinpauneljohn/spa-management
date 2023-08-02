var guestTabSpaId = $('.spaId').val();
function getSalesInfo(id)
{
    $.ajax({
        'url' : '/transaction/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#edit_masseur2_id_default').val('');
        },
        success: function(result){
            $('#edit_first_name').val(result.data.transaction.client.firstname);
            $('#edit_middle_name').val(result.data.transaction.client.middlename);
            $('#edit_last_name').val(result.data.transaction.client.lastname);
            $('#edit_date_of_birth').val(result.data.transaction.client.date_of_birth);
            $('#edit_mobile_number').val(result.data.transaction.client.mobile_number);
            $('#edit_email').val(result.data.transaction.client.email);
            $('#edit_address').val(result.data.transaction.client.address);
            $('#edit_client_type').val(result.data.transaction.client.client_type);
            $('#edit_start_time').val(result.data.transaction.start_time);
            $('#edit_masseur1_id').val(result.data.transaction.therapist_1);
            $('#edit_services_id').val(result.data.transaction.service_id);
            $('#edit_services_name').val(result.data.transaction.service_name);
            $('#edit_price').val(result.data.transaction.service_price);
            $('#edit_plus_time_id').val(result.data.transaction.plus_time);
            $('#edit_plus_time_price').val(result.data.transaction.plus_time_price_total);
            if (result.data.transaction.service.multiple_masseur) {
                $('#edit_masseur2_id').val(result.data.transaction.therapist_2);
                $('#edit_masseur2_id_default').val(result.data.transaction.therapist_2);
                if ($('.edit_masseur2_div').hasClass('hidden')) {
                    $('.edit_masseur2_div').removeClass('hidden'); 
                }

                if ($('.edit_masseur1_div').hasClass('col-md-6')) {
                    $('.edit_masseur1_div').removeClass('col-md-6');
                    $('.edit_masseur1_div').addClass('col-md-4');
                }

                if ($('.edit_services_div').hasClass('col-md-6')) {
                    $('.edit_services_div').removeClass('col-md-6');
                    $('.edit_services_div').addClass('col-md-4');
                }
            } else {
                $('#edit_masseur2_id').val('');
                $('.edit_masseur2_div').addClass('hidden');
                
                $('.edit_masseur1_div').removeClass('col-md-4');
                $('.edit_masseur1_div').addClass('col-md-6');

                $('.edit_services_div').removeClass('col-md-4');
                $('.edit_services_div').addClass('col-md-6');
            }

            $('#multiple_masseur').val(result.data.transaction.service.multiple_masseur);
            $('.totalAmountFormatted').html('&#8369; '+result.data.transaction.amount_formatted);
            $('#totalAmountEditToPay').val(result.data.transaction.amount);
            $('#totalAmountEditToPayOld').val(result.data.transaction.amount);
            $('#edit_transaction_id').val(result.data.transaction.id);
            $('#edit_client_id').val(result.data.transaction.client_id);
            $('#edit_sales_id').val(result.data.transaction.sales_id);
            
            if (result.data.services) {
                $('.select-edit-services').html('');
                $('.select-edit-services').append('<option></option>');
                $('.select-edit-services').select2({
                    placeholder: "Choose Services",
                    allowClear: false
                });

                $.each(result.data.services , function(index, services) { 
                    if (services.id == result.data.transaction.service_id) {
                        $('.select-edit-services').append('<option value="'+services.id+'" selected>'+services.name+'</option>');
                    } else {
                        $('.select-edit-services').append('<option value="'+services.id+'">'+services.name+'</option>');
                    }
                });
            }

            if (result.data.room)
            {
                $('#edit_room_val').val(result.data.transaction.room_id);
                $('.select-edit-room').html('');
                $('.select-edit-room').append('<option></option>');
                $('.select-edit-room').select2({
                    placeholder: "Choose Room",
                    allowClear: false
                });

                $.each(result.data.room , function(index, rooms) { 
                    if (rooms.room_id == result.data.transaction.room_id) {
                        $('.select-edit-room').append('<option value="'+rooms.room_id+'" selected>Room # '+rooms.room_id+'</option>');
                    } else {
                        $('.select-edit-room').append('<option value="'+rooms.room_id+'">Room # '+rooms.room_id+'</option>');
                    }
                });
            }
            
            if (result.data.plus_time)
            {
                $('.select-edit-plus_time').html('');
                $('.select-edit-plus_time').append('<option></option>');
                $('.select-edit-plus_time').select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                });

                $.each(result.data.plus_time , function(index, plus) { 
                    if (index == result.data.transaction.plus_time) {
                        $('.select-edit-plus_time').append('<option value="'+index+'" selected>'+plus+'</option>');
                    } else {
                        $('.select-edit-plus_time').append('<option value="'+index+'">'+plus+'</option>');
                    }
                });
            }

            if (result.data.therapist_1) {
                $('.select-edit-masseur1').html('');
                $('.select-edit-masseur1').append('<option></option>');
                $('.select-edit-masseur1').select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: false
                });

                $.each(result.data.therapist_1 , function(index, therapist_1) { 
                    if (therapist_1.therapist_id == result.data.transaction.therapist_1) {
                        $('.select-edit-masseur1').append('<option value="'+therapist_1.therapist_id+'" selected>'+therapist_1.fullname+'</option>');
                    } else {
                        $('.select-edit-masseur1').append('<option value="'+therapist_1.therapist_id+'">'+therapist_1.fullname+'</option>');
                    }
                });
            }

            if (result.data.therapist_2) {
                $('.select-edit-masseur2').html('');
                $('.select-edit-masseur2').append('<option></option>');
                $('.select-edit-masseur2').select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: false
                });

                $.each(result.data.therapist_2 , function(index, therapist_2) { 
                    if (therapist_2.therapist_id == result.data.transaction.therapist_2) {
                        $('.select-edit-masseur2').append('<option value="'+therapist_2.therapist_id+'" selected>'+therapist_2.fullname+'</option>');
                    } else {
                        $('.select-edit-masseur2').append('<option value="'+therapist_2.therapist_id+'">'+therapist_2.fullname+'</option>');
                    }
                });
            }

            $.each(filterPreSelectedRoom, function (key, value) {
                $('.select-edit-room').children('option[value="' + value + '"]').attr('disabled', true);
            });

            $.each(filterPreSelectedTherapist , function(un_index, un_val) {
                $('.select-edit-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                $('.select-edit-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);
            });
        }
    });

    $('#update-sales-modal').modal('show');
}

$(document).on('click', '.update-sales-btn', function () {
    var transaction_id = $('#edit_transaction_id').val();
    var client_id = $('#edit_client_id').val();
    var sales_id = $('#edit_sales_id').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var address = $('#edit_address').val();
    var client_type = $('#edit_client_type').val();
    var amount = $('#totalAmountEditToPay').val();
    var prevAmount = $('#totalAmountEditToPayOld').val();
    var services = $('#edit_services').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var multiple_masseur = $('#multiple_masseur').val();  
    var value_plus_time = $('#edit_plus_time_id').val();
    var value_room_id = $('#edit_room_val').val();
    var masseur1_id = $('#edit_masseur1_id').val();

    var masseur2_id = '';
    if (multiple_masseur == 1) {
        var masseur2_id = $('#edit_masseur2_id').val();

        if (masseur2_id.length < 1) {
            toastr.error('The masseur 2 field is required.');
        }
    }

    var validateMobile = mobileValidation(mobile_number);
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

    if (value_room_id.length < 1) {
        toastr.error('The room # field is required.');
    }

    var valid = false;
    if (
        mobile_number.length > 0 &&
        value_services.length > 0 &&
        masseur1_id.length > 0 &&
        value_room_id.length > 0 && 
        validateMobile
    ) {
        if (!validateMobile) {
            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
            valid = false;
        } else if (multiple_masseur == 1) {
            if (masseur2_id.length < 1) {
                toastr.error('The masseur 2 field is required.');
                valid = false;
            } else {
                valid = true;
            }
        } else {
            valid = true;
        }
    }

    if (valid) {
        swal.fire({
            title: "Are you sure you want to update the transaction?",
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
                    'url' : '/transaction-update/'+transaction_id,
                    'type' : 'PUT',
                    'data': {
                        id: transaction_id,
                        client_id: client_id,
                        sales_id: sales_id,
                        mobile_number: mobile_number,
                        email: email,
                        address: address,
                        client_type: client_type,
                        amount: amount,
                        prevAmount: prevAmount,
                        service_id: value_services,
                        service_name: value_services_name,
                        therapist_1: masseur1_id,
                        therapist_2: masseur2_id,
                        plus_time: value_plus_time,
                        room_id: value_room_id,
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#sales-update-form').find('.update-sales-btn').attr('disabled',true);
                        $('#sales-update-form').find('.text-update-btn').text('Saving ...');
                        $('#sales-update-form').find('.spinner-update-btn').removeClass('hidden');
                    },success: function (result) {
                        setTimeout(function() { 
                            if(result.status) {
                                $('#sales-update-form').trigger('reset');
                                $('#sales-data-lists').DataTable().ajax.reload(null, false);
                                getMasseurAvailability(guestTabSpaId);
                                swal.fire("Done!", result.message, "success");
                                $('#update-sales-modal').modal('hide');
                            } else {
                                swal.fire("Warning!", result.message, "warning");
                            }

                            $('#sales-update-form').find('.update-sales-btn').attr('disabled',false);
                            $('#sales-update-form').find('.text-update-btn').text('Save');
                            $('#sales-update-form').find('.spinner-update-btn').addClass('hidden');
                        }, 1000);
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
});

function stopSales(id)
{
    swal.fire({
        title: "Are you sure you want to stop / cancel the on going reservation?",
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
                'url' : '/transaction-stop/'+id,
                'type' : 'PUT',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {

                },success: function (result) {
                    if(result.status) {
                        $('#sales-data-lists').DataTable().ajax.reload(null, false);
                        // getMasseurAvailability(guestTabSpaId);
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
    }, function (dismiss) {
        return false;
    })
}

function getEditServiceById(id)
{
    $.ajax({
        'url' : '/service/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            var masseur_1 = $('#edit_masseur1_id').val();
            var masseur_2 = $('#edit_masseur2_id').val();
            $('#edit_services_id').val(result.service.id);
            $('#edit_price').val(result.service.price);
            $('#multiple_masseur').val(result.service.multiple_masseur);

            if (result.service.multiple_masseur == 1) {
                if ($('.edit_services_div').hasClass('col-md-6')) {
                    $('.edit_services_div').removeClass('col-md-6');
                    $('.edit_services_div').addClass('col-md-4');
                }

                if ($('.edit_masseur1_div').hasClass('col-md-6')) {
                    $('.edit_masseur1_div').removeClass('col-md-6');
                    $('.edit_masseur1_div').addClass('col-md-4');
                }

                if ($('.edit_masseur2_div').hasClass('hidden')) {
                    $('.edit_masseur2_div').removeClass('hidden'); 
                }
            } else {
                if ($('.edit_services_div').hasClass('col-md-4')) {
                    $('.edit_services_div').removeClass('col-md-4');
                    $('.edit_services_div').addClass('col-md-6');
                }

                if ($('.edit_masseur1_div').hasClass('col-md-4')) {
                    $('.edit_masseur1_div').removeClass('col-md-4');
                    $('.edit_masseur1_div').addClass('col-md-6');
                }

                $('.edit_masseur2_div').addClass('hidden');

                var masseur_2_default = $('#edit_masseur2_id_default').val();
                $('.select-edit-masseur1').children('option[value="' + masseur_2 + '"]').attr('disabled', false);
                filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                    console.log(masseur_2);
                    return element !== masseur_2;
                }); 

                // if (masseur_2_default.length < 1) {
                //     console.log(masseur_2)
                //     $('.select-edit-masseur1').children('option[value="' + masseur_2 + '"]').attr('disabled', false);
                //     $('.select-edit-masseur2').children('option[value="' + masseur_2 + '"]').attr('disabled', false);

                //     filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
                //         return element !== masseur_2;
                //     }); 

                //     $('.select-edit-masseur2').select2({
                //         placeholder: "Choose Masseur 2",
                //         allowClear: false
                //     }).val('').trigger('change');
                //     $('#edit_masseur2_id').val('');
                // }
            }
        }
    });
}

function getEditPosTherapistApi(spa_id)
{
    var dateTime = $('#edit_start_time').val();
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
            // $('#move_masseur1').html('');
            // $('#move_masseur1').append('<option></option>');
            // $('#move_masseur1').select2({
            //     placeholder: "Choose Masseur 1",
            //     allowClear: false
            // }); 

            // $('#move_masseur2').html('');
            // $('#move_masseur2').append('<option></option>');
            // $('#move_masseur2').select2({
            //     placeholder: "Choose Masseur 2",
            //     allowClear: false
            // }); 

            // $.each(result , function(index, val) {                
            //     $('#move_masseur1').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
            //     $('#move_masseur2').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
            //     if (val.availability == 'yes') {
            //         $('#move_masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
            //         $('#move_masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
            //     } else {
            //         $('#move_masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
            //         $('#move_masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
            //     }
                
            //     if (filterPreSelectedTherapist.length > 0) {
            //         $.each(filterPreSelectedTherapist , function(un_index, un_val) {
            //             $('#move_masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
            //             $('#move_masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);
            //         });
            //     }
            // });

            // var multiple_masseur = $('#move_app_services_multiple').val();
            // var masseur_1 = $('#move_masseur1_id').val();
            // var masseur_2 = $('#move_masseur2_id').val();

            // if (multiple_masseur.length > 0) {
            //     if (multiple_masseur == 1) {
            //         $('.moveMasseur1Div').removeClass('hidden');
            //         $('.moveMasseur2Div').removeClass('hidden');
    
            //         if ($('.moveMasseur1Div').hasClass('col-md-6')) {
            //             $('.moveMasseur1Div').removeClass('col-md-6');
            //             $('.moveMasseur1Div').addClass('col-md-4');
            //         }
            //     } else {
            //         $('.moveMasseur1Div').removeClass('hidden');
            //         if (!$('.moveMasseur2Div').hasClass('hidden')) {
            //             $('.moveMasseur2Div').addClass('hidden');
            //             $('#move_masseur2_id').val('');
    
            //             if ($('.moveMasseur1Div').hasClass('col-md-4')) {
            //                 $('.moveMasseur1Div').removeClass('col-md-4');
            //                 $('.moveMasseur1Div').addClass('col-md-6');
            //             }
            //         }
    
            //         if (masseur_2.length > 0) {
            //             filterPreSelectedTherapist = $.grep(filterPreSelectedTherapist, function(element){
            //                 return element !== masseur_2;
            //             }); 
    
            //             $("#move_masseur1").children('option[value="' + masseur_2 + '"]').attr('disabled', false);
            //         }
            //     }
    
            //     if ($('#move_masseur1_id').val().length > 0) {
            //         $('#move_masseur1').select2({
            //             placeholder: "Choose Masseur 1",
            //             allowClear: false
            //         }).val(masseur_1).trigger("change");
            //     }
            // }
        }
    });
}