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
    console.log(UnAvailableTherapist)
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