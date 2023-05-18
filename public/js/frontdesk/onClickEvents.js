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
    var li = $(this).closest('li').prev('li');
    if (id == 1) {
        var li = $(this).closest('li').next('li');
    }
    
    var cur_val = $('#guest_ids_val').val();

    if ($('.appointmentNav'+id).hasClass('active')) {     
        if (count == 3) {
            alert('Unable to remove last Guest Tab.')
            return false;
        } else {
            $('a.appointmentNav'+li[0].id).addClass('active');
            $('div#appointment'+li[0].id).addClass('active');
        }
    }

    var remove = removeValue(cur_val, id);
    remove.split(",").sort().join(",")
    $('#guest_ids_val').val(remove);
    
    $('.tabAppointmentTitle'+id).remove();
    $('.tabAppointmentContent'+id).remove();
    checkTabs();
}

function checkTabs()
{
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;
    $('#appointment_name_appointment'+firstLiId).prop('disabled', false);
    $('#social_media_appointment'+firstLiId).prop('disabled', false);
    
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
        $.each(data, function (key, value) {
            var value_client_type = $('#client_type_appointment'+value).val();

            var value_first_name = $('#first_name_appointment'+value).val();
            if (value_first_name.length < 1) {
                $('#error-first_name_appointment'+value).removeClass('hidden');
                $('#error-first_name_appointment'+value).text('First Name field is required!');
            } else {
                $('#error-first_name_appointment'+value).addClass('hidden');
                $('#error-first_name_appointment'+value).text('');
            }

            var value_middle_name = $('#middle_name_appointment'+value).val();

            var value_last_name = $('#last_name_appointment'+value).val();
            if (value_last_name.length < 1) {
                $('#error-last_name_appointment'+value).removeClass('hidden');
                $('#error-last_name_appointment'+value).text('Last Name field is required!');
            } else {
                $('#error-last_name_appointment'+value).addClass('hidden');
                $('#error-last_name_appointment'+value).text('');
            }

            var value_date_of_birth = $('#date_of_birth_appointment'+value).val();

            var value_mobile_number = $('#mobile_number_appointment'+value).val();
            if (value_mobile_number.length < 1) {
                $('#error-mobile_number_appointment'+value).removeClass('hidden');
                $('#error-mobile_number_appointment'+value).text('Mobile Number field is required!');
            } else {
                $('#error-mobile_number_appointment'+value).addClass('hidden');
                $('#error-mobile_number_appointment'+value).text('');
            }

            var value_email = $('#email_appointment'+value).val();
            var value_address = $('#address_appointment'+value).val();

            var existing_user_id = $('#existing_user_id_appointment_'+value).val();
  
            var value_appointment_type = '';
            if ($('#appointment_name_appointment'+value).val() != null) {
                value_appointment_type = $('#appointment_name_appointment'+value).val();

                if (!$('#error-appointment_name_appointment'+value).hasClass('hidden')) {
                    $('#error-appointment_name_appointment'+value).addClass('hidden');
                }

                $('#error-appointment_name_appointment'+value).text('');
            } else {
                $('#error-appointment_name_appointment'+value).removeClass('hidden');
                $('#error-appointment_name_appointment'+value).text('Appointment Type field is required!');
            }

            var value_social_type = '';
            var value_appointment_socials = value_appointment_type;
            if ($('#social_media_appointment'+value).val() != null) {
                value_social_type = $('#social_media_appointment'+value).val();
                value_appointment_socials = value_appointment_type+'<br />('+value_social_type+')';

                if (!$('#error-social_media_appointment'+value).hasClass('hidden')) {
                    $('#error-social_media_appointment'+value).addClass('hidden');
                }

                $('#error-social_media_appointment'+value).text('');
            } else {
                $('#error-social_media_appointment'+value).removeClass('hidden');
                $('#error-social_media_appointment'+value).text('Social Media Type field is required!');
            }

            var services = $('#service_name_appointment'+value).select2('data');
            var value_services = $('#service_name_appointment_id'+value).val();
            var value_start_time = $('#start_time_appointment'+value).val();
            var value_start_time_format = gettime(value_start_time);
            var value_start_time_date_format = getdate(value_start_time);
            if (value_start_time.length < 1) {
                $('#error-start_time_appointment'+value).removeClass('hidden');
                $('#error-start_time_appointment'+value).text('Start Time field is required!');
            } else {
                $('#error-start_time_appointment'+value).addClass('hidden');
                $('#error-start_time_appointment'+value).text('');
            }

            var price = parseInt($('#price_appointment'+value).val());
            total_amount += parseInt($('#price_appointment'+value).val());
            var plus_time = 0;
            var plus_time_price = 0;
            var therapist_1 = '';
            var therapist_2 = '';
            var room = '';
            var price_converted = ReplaceNumberWithCommas(price);

            if ($('#appointment_name_appointment'+value).val() == 'Walk-in') {
                services = $('#service_name_appointment_walkin'+value).select2('data');
                value_services = $('#appointment_app_services_id'+value).val();
                value_start_time = $('#start_time_appointment_walkin'+value).val();
                price = parseInt($('#appointment_total_service_price'+value).val());
                plus_time = $('#appointment_plus_time_id'+value).val();
                plus_time_price = $('#appointment_plus_time_price'+value).val();
                therapist_1 = $('#appointment_masseur1'+value+'_id').val();
                therapist_2 = $('#appointment_masseur2'+value+'_id').val();
                room = $('#appointment_room_id'+value).val();
                total_amount += parseInt($('#appointment_total_service_price'+value).val());
                price_converted = ReplaceNumberWithCommas(price);
            }
            
            var price_formatted = '&#8369;'+0;
            if (price_converted > 0) {
                price_formatted ='&#8369;'+price_converted;
            }

            var value_services_name = services[0].text;

            if ($('#appointment_name_appointment'+value).val() == 'Walk-in') {
                if (value_services.length < 1) {
                    $('#error-service_name_appointment_walkin'+value).removeClass('hidden');
                    $('#error-service_name_appointment_walkin'+value).text('Services Type field is required!');
                }

                if (value_start_time.length < 1) {
                    $('#error-start_time_appointment_walkin'+value).removeClass('hidden');
                    $('#error-start_time_appointment_walkin'+value).text('Start time field is required!');
                }

                if (therapist_1.length < 1) {
                    $('#error-appointment_masseur1'+value+'_id').removeClass('hidden');
                    $('#error-appointment_masseur1'+value+'_id').text('Masseur 1 field is required!');
                }

                if (room.length < 1) {
                    $('#error-appointment_room'+value).removeClass('hidden');
                    $('#error-appointment_room'+value).text('Room number field is required!');
                }
            }

            if (
                value_client_type.length < 1 ||
                value_first_name.length < 1 ||
                value_last_name.length < 1 ||
                value_mobile_number.length < 1 ||
                value_appointment_type.length < 1 ||
                value_start_time.length < 1
            ) {
                $('.tabAppointmentTitle'+value).addClass('error-border');
            } else {
                if (value_appointment_type == 'Social Media') {
                    if (value_social_type.length < 1) {
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                    } else {
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                } else if (value_appointment_type == 'Walk-in') {
                    if (
                        value_services.length < 1 &&
                        value_start_time.length < 1 &&
                        therapist_1.length < 1 &&
                        room.length < 1
                    ) {
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                    } else {
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                } else {
                    $('.tabAppointmentTitle'+value).removeClass('error-border');
                }
            }

            if (
                value_client_type.length > 0 &&
                value_first_name.length > 0 &&
                value_last_name.length > 0 &&
                value_mobile_number.length > 0 &&
                value_appointment_type.length > 0 &&
                value_start_time.length > 0
            ) {
                if (value_appointment_type == 'Social Media') {
                    if (value_social_type.length > 0) {
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    } else {
                        $('.summaryTabAppointmentLink').addClass('hidden');
                    }
                } else if (value_appointment_type == 'Walk-in') {
                    if (
                        value_services.length > 0 &&
                        value_start_time.length > 0 &&
                        therapist_1.length > 0 &&
                        room.length > 0
                    ) {
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    } else {
                        $('.summaryTabAppointmentLink').addClass('hidden');
                    }
                } else {
                    $('.summaryTabAppointmentLink').removeClass('hidden');
                }
            } else {
                $('.summaryTabAppointmentLink').addClass('hidden');
            }
            

            appointment.push({
                key: value,
                value_client_type: value_client_type,
                value_first_name: value_first_name,
                value_middle_name: value_middle_name,
                value_last_name: value_last_name,
                value_date_of_birth: value_date_of_birth,
                value_mobile_number: value_mobile_number,
                value_email: value_email,
                value_address: value_address,
                value_services: value_services,
                value_services_name: value_services_name,
                value_start_time: value_start_time,
                price: price,
                price_formatted: price_formatted,
                existing_user_id: existing_user_id,
                value_appointment_type: value_appointment_type,
                value_social_type: value_social_type,
                value_appointment_socials: value_appointment_socials,
                total_price: total_amount,
                plus_time: plus_time,
                therapist_1: therapist_1,
                therapist_2: therapist_2,
                room_id: room,
                value_start_time_format: value_start_time_format,
                value_start_time_date_format: value_start_time_date_format
            })
        });

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