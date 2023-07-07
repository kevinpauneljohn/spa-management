function clickSalesView(id)
{
    loadSales(id);
    getTotalSales(id);
}

function multipleMasseurCheckbox(id, therapist_2, checkbox_id, therapist1_id, therapis2_id, therapist_2_val, therapist_select)
{
    if ($('#'+checkbox_id+id).is(':checked') == false) {
        const index = UnAvailableTherapist.indexOf(therapist_2);
        if (index > -1) {
            UnAvailableTherapist.splice(index, 1);
        }

        $('.'+therapist1_id).children('option[value="'+therapist_2+'"]').prop('disabled', false);
        $('.'+therapist1_id).select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });

        $('#'+therapist_select+id).attr('disabled',true);
        $('.'+therapis2_id).children('option[value="'+therapist_2+'"]').prop('disabled', false);
        $("."+therapis2_id).select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        }).val('').trigger("change");
        $('#'+therapist_2_val+id).val('');
    } else {
        $('#'+therapist_select+id).attr('disabled',false);
    }
}

function closeTabs(id, count)
{
    // var li = $(this).closest('li').prev('li');
    // if (id == 1) {
    //     li = $(this).closest('li').next('li');
    // }
    // console.log(li[0])
    
    // var cur_val = $('#guest_ids_val').val();

    // if ($('.appointmentNav'+id).hasClass('active')) {     
    //     if (count == 3) {
    //         alert('Unable to remove last Guest Tab.')
    //         return false;
    //     } else {
    //         $('a.appointmentNav'+li[0].id).addClass('active');
    //         $('div#appointment'+li[0].id).addClass('active');
    //     }
    // }

    // var remove = removeValue(cur_val, id);
    // remove.split(",").sort().join(",")
    // $('#guest_ids_val').val(remove);
    
    // $('.tabAppointmentTitle'+id).remove();
    // $('.tabAppointmentContent'+id).remove();
    // checkTabs();
}

function checkTabs()
{
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;
    $('#appointment_name_appointment'+firstLiId).prop('disabled', false);
    $('#social_media_appointment'+firstLiId).prop('disabled', false);
    $('#reservenow'+firstLiId).prop('disabled', false);
    $('#reservelater'+firstLiId).prop('disabled', false);
    $('#start_time_appointment_walkin'+firstLiId).prop('disabled', false);
    $('#start_time_appointment'+firstLiId).prop('disabled', false);
    
    var count = $('ul.dataTabsAppointment li').length;
    if (count == 3) {
        $(".divCloseTab").html('');
    }

    $('.appointmentTabNavSummary').removeClass('active');
    $('#summaryTab').removeClass('active');

    $('.summaryTabAppointmentLink').addClass('hidden');
    if ($('.process-appointment-btn').hasClass('hidden')) {
        $('.process-appointment-btn').removeClass('hidden');
        $('.add-appointment-btn').addClass('hidden');
    }
}

function processAppointment(data)
{
    if (data.length > 0) {
        appointment = [];
        var total_amount = 0;
        var date = new Date();
        var newDate = subtractHours(date, 1);
        var validation = false;
        $.each(data, function (key, value) {
            var client_type = $('#client_type_appointment'+value).val();
            var firstname = $('#first_name_appointment'+value).val();
            var middlename = $('#middle_name_appointment'+value).val();
            var lastname = $('#last_name_appointment'+value).val();
            var date_of_birth = $('#date_of_birth_appointment'+value).val();
            var mobile_number = $('#mobile_number_appointment'+value).val();
            var validateMobile = mobileValidation(mobile_number);
            var email = $('#email_appointment'+value).val();
            var address = $('#address_appointment'+value).val();
            var existing_user_id = $('#existing_user_id_appointment_'+value).val();
            var value_appointment_type = $('#appointment_name_appointment'+value).val();
            var value_appointment_socials = value_appointment_type;
            var value_social_type = $('#social_media_appointment'+value).val();
            var value_start_time = $('#start_time_appointment'+value).val();
            var services = '';
            var value_services = '';
            var value_services_name = '';
            var reserve_now = 'no';
            var reserve_later = 'no';
            var is_multiple_masseur = 'no';
            var value_start_time = $('#start_time_appointment'+value).val();

            var price = parseInt($('#appointment_total_service_price'+value).val());
            total_amount += parseInt($('#appointment_total_service_price'+value).val());
            var plus_time = $('#appointment_plus_time_id'+value).val();
            var plus_time_price = $('#appointment_plus_time_price'+value).val();
            var therapist_1 = $('#appointment_masseur1'+value+'_id').val();
            var therapist_2 = $('#appointment_masseur2'+value+'_id').val();
            var room = $('#appointment_room_id'+value).val();
            var price_converted = ReplaceNumberWithCommas(price);

            var price_formatted = '&#8369;'+0;
            if (price_converted > 0) {
                price_formatted ='&#8369;'+price_converted;
            }

            if (value_appointment_type == 'Walk-in') {
                if ($('#reservenow'+value).is(':checked')) {
                    value_start_time = $('#start_time_appointment_walkin'+value).val();
                    reserve_now = 'yes';
                    reserve_later = 'no';

                    services = $('#service_name_appointment_walkin'+value).select2('data');
                    value_services = $('#appointment_app_services_id'+value).val();
                    value_start_time = $('#start_time_appointment_walkin'+value).val();
                    value_services_name = services[0].text;

                    if ($('#appointmentCustomCheckbox'+value).is(':checked')) {
                        is_multiple_masseur = 'yes';
                    }
                } else if ($('#reservelater'+value).is(':checked')) {
                    value_start_time = $('#start_time_appointment'+value).val();
                    reserve_later = 'yes';
                    reserve_now = 'no';
                }
            }

            var value_start_time_format = gettime(value_start_time);
            var value_start_time_date_format = getdate(value_start_time);

            var reserved_option = 'no';
            if (reserve_now == 'yes') {
                reserved_option = 'yes';
            } else if (reserve_later == 'yes') {
                reserved_option = 'yes';
            }

            var validate_form = [
                { 'name': 'First name', 'value': firstname, 'id': value, 'validation': 'length' },
                { 'name': 'Last name', 'value': lastname, 'id': value, 'validation': 'length' },
                { 'name': 'Appointment type', 'value': value_appointment_type, 'id': value, 'validation': 'nullable' },
                { 'name': 'Social media type', 'value': value_social_type, 'id': value, 'validation': 'nullable' },
                { 'name': 'Start time', 'value': value_start_time, 'id': value, 'validation': 'time' },
                { 'name': 'Mobile number', 'value': mobile_number, 'id': value, 'validation': 'mobile' },
                { 'name': 'Reserve Option', 'value': reserved_option, 'id': value, 'validation': 'checkbox' },
                { 'name': 'Services', 'value': value_services, 'id': value, 'validation': 'length' },
                { 'name': 'Therapist 1', 'value': therapist_1, 'id': value, 'validation': 'length' },
                { 'name': 'Therapist 2', 'value': therapist_2, 'id': value, 'validation': 'length' },
                { 'name': 'Room', 'value': room, 'id': value, 'validation': 'length' },
            ];

            $.each(validate_form, function(array_key, array) {
                var service_conditions = array.name != 'Services' && 
                    array.name != 'Therapist 1' && 
                    array.name != 'Therapist 2' && 
                    array.name != 'Room';

                var condition_without_service =  service_conditions;

                var condition_without_reserved_option =  array.name != 'Reserve Option' &&
                    service_conditions;

                var condition_without_social_type =  array.name != 'Social media type' && 
                    array.name != 'Reserve Option' &&
                    service_conditions;
                
                if (value_appointment_type != null) {
                    if (value_appointment_type == 'Social Media') {
                        if (key == 0) {
                            if (condition_without_reserved_option) {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            }
                        }
                    } else if (value_appointment_type == 'Walk-in' && array.name != 'Social media type') {
                        if(reserve_now == 'no' && reserve_later =='no') {
                            if (key == 0) {
                                if (condition_without_service) {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        } else if (reserve_now == 'yes' && reserve_later =='no') {
                            if (is_multiple_masseur == 'yes') {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            } else {
                                if (array.name != 'Therapist 2') {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        } else if (reserve_later == 'yes' && reserve_now =='no') {
                            if (key == 0) {
                                if (condition_without_service) {
                                    validateAppointmentForm(array.value, array.name, array.id, array.validation);
                                }
                            }
                        }
                    } else {
                        if (key == 0) {
                            if (condition_without_social_type) {
                                validateAppointmentForm(array.value, array.name, array.id, array.validation);
                            }
                        }
                    }
                } else {
                    if (key == 0) {
                        if (condition_without_social_type) {
                            validateAppointmentForm(array.value, array.name, array.id, array.validation);
                        }
                    }
                }
            });


            var key_value = value;
            if (key == 0) {
                key_value = value;
               if (value_appointment_type != null && value_appointment_type == 'Social Media') {
                    if (value_social_type != null) {
                        value_appointment_socials = value_appointment_type+'<br />('+value_social_type+')';
                    }
                }
            }

            validation = vaidateAppointmentTab(
                key,
                key_value,
                value,
                client_type, 
                firstname,
                lastname,
                mobile_number,
                value_appointment_type,
                value_start_time,
                validateMobile,
                value_social_type,
                value_services,
                therapist_1,
                therapist_2,
                is_multiple_masseur,
                room,
                reserve_now,
                reserve_later,
                newDate
            );

            if (validation) {
                appointment.push({
                    key: value,
                    client_type: client_type,
                    firstname: firstname,
                    middlename: middlename,
                    lastname: lastname,
                    date_of_birth: date_of_birth,
                    mobile_number: mobile_number,
                    email: email,
                    address: address,
                    service_id: value_services,
                    service_name: value_services_name,
                    start_time: value_start_time,
                    price: price,
                    price_formatted: price_formatted,
                    existing_user_id: existing_user_id,
                    appointment_type: value_appointment_type,
                    social_type: value_social_type,
                    appointment_socials: value_appointment_socials,
                    total_price: total_amount,
                    plus_time: plus_time,
                    therapist_1: therapist_1,
                    therapist_2: therapist_2,
                    room_id: room,
                    start_time_format: value_start_time_format,
                    start_time_date_format: value_start_time_date_format,
                    reserve_now: reserve_now,
                    reserve_later: reserve_later,
                    spa_id: $('#spa_id_val').val(),
                    owner_id: $('#owner_id_val').val(),
                });
            }
        });

        if (validation) {
            swal.fire("Done!", 'Processed! Click the Summary tab to review and submit the form.', "success");
            $('.process-appointment-btn').text('Processed');
            console.log(appointment)
        }

        var converted_amount = 0;
        var getTotal_amount = 0;
        if (total_amount > 0) {
            converted_amount = ReplaceNumberWithCommas(total_amount);
            getTotal_amount = total_amount;
        }

        $('#totalAmountToPayAppointment').val(getTotal_amount);
        $('.total_amount_appointment').html('&#8369;'+converted_amount);
    }
}

function vaidateAppointmentTab(
    key,
    key_value,
    value,
    client_type, 
    firstname,
    lastname,
    mobile_number,
    value_appointment_type,
    value_start_time,
    validateMobile,
    value_social_type,
    value_services,
    therapist_1,
    therapist_2,
    is_multiple_masseur,
    room,
    reserve_now,
    reserve_later,
    newDate
) {
    var status;
    var default_validation = firstname.length < 1 ||
        lastname.length < 1 ||
        mobile_number.length < 1 ||
        value_appointment_type == null ||
        value_start_time.length < 1 ||
        !validateMobile;

    var social_validation = value_social_type == null || default_validation;
    var services_validation = value_services.length < 1 || therapist_1.length < 1 || room.length < 1 || default_validation;
    var multiple_service_validation = value_services.length < 1 || therapist_1.length < 1 || therapist_2.length < 1 || room.length < 1 || default_validation;

    if (
        client_type.length > 0 &&
        firstname.length > 0 &&
        lastname.length > 0 &&
        mobile_number.length > 0 &&
        value_appointment_type != null &&
        value_start_time.length > 0 &&
        newDate < new Date(value_start_time) &&
        validateMobile
    ) {
        if (value_appointment_type == 'Social Media') {
            if (value_social_type != null) {
                status = true;
                $('.tabAppointmentTitle'+value).removeClass('error-border');
                $('.summaryTabAppointmentLink').removeClass('hidden');
            } else {
                status = false;
                $('.tabAppointmentTitle'+value).addClass('error-border');
                $('.summaryTabAppointmentLink').addClass('hidden');
                $('.process-appointment-btn').text('Process').prop('disabled', false);
            }
        } else if (value_appointment_type == 'Walk-in') {
            if (reserve_now == 'yes') {
                if (is_multiple_masseur == 'yes') {
                    if (multiple_service_validation) {
                        status = false;
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                        $('.summaryTabAppointmentLink').addClass('hidden');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    } else {
                        status = true;
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    }
                } else {
                    if (services_validation) {
                        status = false;
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                        $('.summaryTabAppointmentLink').addClass('hidden');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    } else {
                        status = true;
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                }      
            } else if (reserve_later == 'yes') {
                if (default_validation) {
                    if (key == 0) {
                        status = false;
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        $('.process-appointment-btn').text('Process').prop('disabled', false);
                    }
                    $('.summaryTabAppointmentLink').addClass('hidden');
                } else {
                    status = true;
                    $('.tabAppointmentTitle'+key_value).removeClass('error-border');  
                    $('.summaryTabAppointmentLink').removeClass('hidden');
                }
            } else {
                status = false;
                $('.summaryTabAppointmentLink').addClass('hidden');
                $('.process-appointment-btn').text('Process').prop('disabled', false);
                if (key == 0) {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');
                }
            }
        } else {
            status = true;
            $('.tabAppointmentTitle'+value).removeClass('error-border');
            $('.summaryTabAppointmentLink').removeClass('hidden');
        }
    } else {
        status = false;
        $('.process-appointment-btn').text('Process').prop('disabled', false);
        $('.summaryTabAppointmentLink').addClass('hidden');
        if (value_appointment_type != null) {
            if (value_appointment_type == 'Social Media') {
                if (key == 0) {
                    if (social_validation) {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                    }
                }
            } else if (value_appointment_type == 'Walk-in') {
                if (reserve_now == 'no' && reserve_later == 'no') {
                    if (default_validation) {
                        if (key == 0) {
                            $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        }
                    } else {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                    }
                } else if (reserve_now == 'yes') {
                    if (is_multiple_masseur == 'yes') {
                        if (multiple_service_validation) {
                            $('.tabAppointmentTitle'+value).addClass('error-border');
                        }
                    } else {
                        if (services_validation) {
                            $('.tabAppointmentTitle'+value).addClass('error-border');
                        }
                    }
                } else if (reserve_later == 'yes') {
                    if (default_validation) {
                        if (key == 0) {
                            $('.tabAppointmentTitle'+key_value).addClass('error-border');
                        }
                    } else {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                    }
                }
            } else {
                if (default_validation) {
                    if (key == 0) {
                        $('.tabAppointmentTitle'+key_value).addClass('error-border');
                    }
                } else {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');  
                }
            }
        } else {
            if (default_validation) {
                if (key == 0) {
                    $('.tabAppointmentTitle'+key_value).addClass('error-border');
                }
            } else {
                $('.tabAppointmentTitle'+key_value).addClass('error-border');  
            }
        }
    }

    return status;
}