function filterClient(id, value, spa_id)
{
    if (value.length > 2 ) {
        $.ajax({
            'url' : '/client-filter/'+value+'/'+spa_id,
            'type' : 'GET',
            'data' : {
                'search': value
            },
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function () {
                $("#suggesstion-box-appointment"+id).html('');
                $('#existing_user_id_appointment_'+id).val('');
                $('#first_name_appointment'+id).val('');
                $('#first_name_appointment'+id).prop( "disabled", false );
                $('#middle_name_appointment'+id).val('');
                $('#middle_name_appointment'+id).prop( "disabled", false );
                $('#last_name_appointment'+id).val('');
                $('#last_name_appointment'+id).prop( "disabled", false );
                $('#date_of_birth_appointment'+id).val('');
                $('#mobile_number_appointment'+id).val('');
                $('#email_appointment'+id).val('');
                $('#address_appointment'+id).val('');
                $('#client_type_appointment'+id).val('');
                $('#client_type_appointment'+id).prop( "disabled", false );
    
                $('.clientInfo_appointment'+id).addClass('hidden');
                $('.clientContact_appointment'+id).addClass('hidden');
                $('.clientAddress_appointment'+id).addClass('hidden');
                $('.clientService_appointment'+id).addClass('hidden');
                $('.clientAppointment_appointment'+id).addClass('hidden');
                
                $('#first_name_appointment'+id).prop( "disabled", true );
                $('#middle_name_appointment'+id).prop( "disabled", true );
                $('#last_name_appointment'+id).prop( "disabled", true );

                $("#suggesstion-box-appointment"+id).removeClass('hidden');
            },
            success: function(result){
                if (result.count > 0) {
                    if (result.status) {
                        $("#suggesstion-box-appointment"+id).html('');
                        $.each(result.data , function(index, val) { 
                            if( $.inArray(index, searchFilter) !== -1 ) {
                                $("#suggesstion-box-appointment"+id).append('<a class="list-group-item pointer filterNewAccount" data-id="'+id+'">No data found. (Create new Account)</a>');
                            } else {
                                $("#suggesstion-box-appointment"+id).append('<a class="list-group-item pointer filterValue" data-id="'+id+'" data-index="'+index+'" id="'+val+'">'+index+'</a>');
                            }
                        });
                    }
                } else {
                    $("#suggesstion-box-appointment"+id).html('');
                    $("#suggesstion-box-appointment"+id).append('<a class="list-group-item pointer filterNewAccount" data-id="'+id+'">No data found. (Create new Account)</a>');
                }           
            }
        });
    } else {
        $("#suggesstion-box-appointment"+id).html('');
        $("#suggesstion-box-appointment"+id).append('<a class="list-group-item pointer filterNewAccount" data-id="'+id+'">No data found. (Create new Account)</a>');
    }
}

$(document).on('click', '.filterNewAccount', function () {
    var data_id = $(this).data("id");
    $('#client_type_appointment'+data_id).val('new');
    $('#client_type_appointment'+data_id).prop( "disabled", true );

    $("#suggesstion-box-appointment"+data_id).html('');
    $("#suggesstion-box-appointment"+data_id).addClass('hidden');

    $('.clientInfo_appointment'+data_id).slideDown(500);
    $('.clientContact_appointment'+data_id).slideDown(500);
    $('.clientAddress_appointment'+data_id).slideDown(500);
    $('.clientService_appointment'+data_id).slideDown(500);
    $('.clientAppointment_appointment'+data_id).slideDown(500);

    $('#first_name_appointment'+data_id).prop( "disabled", false );
    $('#middle_name_appointment'+data_id).prop( "disabled", false );
    $('#last_name_appointment'+data_id).prop( "disabled", false );

    $('.clientFilterAppointent'+data_id).val('');
    getAppointmentTypeforNewGuest(data_id);
});

$(document).on('click', '.filterValue', function () {
    var id = this.id;
    var index = $(this).data("index");
    var data_id = $(this).data("id");

    $('.clientFilterAppointent'+data_id).val('');
    $("#suggesstion-box-appointment"+data_id).html('');
    $("#suggesstion-box-appointment"+data_id).addClass('hidden');

    $.ajax({
        'url' : '/client/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (result.client != '') {
                searchFilter.push(index);
                $('#fullname_array'+data_id).val(index)
                $('#existing_user_id_appointment_'+data_id).val(result.client.id);
                $('#first_name_appointment'+data_id).val(result.client.firstname);
                $('#first_name_appointment'+data_id).prop( "disabled", true );
                $('#middle_name_appointment'+data_id).val(result.client.middlename);
                $('#middle_name_appointment'+data_id).prop( "disabled", true );
                $('#last_name_appointment'+data_id).val(result.client.lastname);
                $('#last_name_appointment'+data_id).prop( "disabled", true );
                $('#date_of_birth_appointment'+data_id).val(result.client.date_of_birth);
                $('#mobile_number_appointment'+data_id).val(result.client.mobile_number);
                $('#email_appointment'+data_id).val(result.client.email);
                $('#address_appointment'+data_id).val(result.client.address);
                $('#client_type_appointment'+data_id).val('member');
                $('#client_type_appointment'+data_id).prop( "disabled", true );

                $('.clientInfo_appointment'+data_id).slideDown(500);
                $('.clientContact_appointment'+data_id).slideDown(500);
                $('.clientAddress_appointment'+data_id).slideDown(500);
                $('.clientService_appointment'+data_id).slideDown(500);
                $('.clientAppointment_appointment'+data_id).slideDown(500);

                getAppointmentTypeforNewGuest(data_id);
            }
        }
    });
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