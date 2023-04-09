$(document).on('click','.add-therapist-btn',function(){
    var firstname = $('#firstname').val();
    var middlename = $('#middlename').val();
    var lastname = $('#lastname').val();
    var date_of_birth = $('#date_of_birth').val();
    var mobile_number = $('#mobile_number').val();
    var email = $('#email').val();
    var gender = $('#gender').val();
    var certificate = $('#certificate').val();
    var commission = $('#commission').val();
    var allowance = $('#allowance').val();
    var offer_type = $('#offer_type').val();
    var spa_id = $('.spa-id').val()

    var data = {
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        date_of_birth : date_of_birth,
        mobile_number : mobile_number,
        email : email,
        gender : gender,
        certificate : certificate,
        commission : commission,
        allowance : allowance,
        offer_type : offer_type,
        spa_id : spa_id
    };

    swal.fire({
        title: "Are you sure you want to create Therapist?",
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
                'url' : '/therapist',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#therapist-form').find('.add-therapist-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#therapist-form').trigger('reset');
                        reloadTherapistTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-therapist-modal').modal('hide');
                    } else {
                        if (result.status === false) {
                            swal.fire("Warning!", result.message, "warning");
                        } else {
                            swal.fire("Warning!", 'Kindly check all fields to view error.', "warning");
                            $.each(result, function (key, value) {
                                var element = $('#'+key);
                
                                element.closest('div.'+key)
                                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                                    .find('.text-danger')
                                    .remove();
                                
                                element.after('<p class="text-danger">'+value+'</p>');
                            });
                        }
                    }
            
                    $('#therapist-form').find('.add-therapist-btn').val('Save').attr('disabled',false);
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
});

$('#firstname, #lastname').on('input',function(e){
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();

    if (firstname.length > 0 && lastname.length > 0) {
        $('.name_next_btn').prop('disabled', false);
    } else {
        $('.name_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.name_next_btn',function(){
    $('.name_next_btn').addClass('hiddenBtn');
    $('.closeModal').addClass('hiddenBtn');
    $('.info_previous_btn').removeClass('hiddenBtn');
    $('.info_next_btn').removeClass('hiddenBtn');
});

$('#gender').on('input',function(e){
    var gender = $('#gender').val();

    if (gender.length > 0) {
        $('.info_next_btn').prop('disabled', false);
    } else {
        $('.info_next_btn').prop('disabled', true);
    }
});

$('#mobile_number').on('input',function(e){
    var mobile_number = $('#mobile_number').val();

    if (mobile_number.length > 0) {
        $('.contact_next_btn').prop('disabled', false);
    } else {
        $('.contact_next_btn').prop('disabled', true);
    }
});

$('#offer_type, #commission, #allowance').on('input',function(e){
    var offer_type = $('#offer_type').val();
    var commission = $('#commission').val();
    var allowance = $('#allowance').val();

    if (offer_type.length > 0 && commission.length > 0) {
        if (offer_type === 'percentage_plus_allowance' || offer_type === 'amount_plus_allowance') {
            if (allowance.length > 0) {
                $('.offer_submit_btn').prop('disabled', false);
            } else {
                $('.offer_submit_btn').prop('disabled', true);
            }
        } else {
            $('.offer_submit_btn').prop('disabled', false);
        }
    } else {
        $('.offer_submit_btn').prop('disabled', true);
    }
});

$(document).on('click','.info_next_btn',function(){
    $('.info_next_btn').addClass('hiddenBtn');
    $('.info_previous_btn').addClass('hiddenBtn');
    $('.contact_next_btn').removeClass('hiddenBtn');
    $('.contact_previous_btn').removeClass('hiddenBtn');
});

$(document).on('click','.info_previous_btn',function(){
    $('.name_next_btn').removeClass('hiddenBtn');
    $('.closeModal').removeClass('hiddenBtn');
    $('.info_previous_btn').addClass('hiddenBtn');
    $('.info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.contact_next_btn',function(){
    $('.contact_next_btn').addClass('hiddenBtn');
    $('.contact_previous_btn').addClass('hiddenBtn');
    $('.offer_previous_btn').removeClass('hiddenBtn');
    $('.offer_submit_btn').removeClass('hiddenBtn');
});

$(document).on('click','.contact_previous_btn',function(){
    $('.info_next_btn').removeClass('hiddenBtn');
    $('.info_previous_btn').removeClass('hiddenBtn');
    $('.contact_previous_btn').addClass('hiddenBtn');
    $('.contact_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.offer_previous_btn',function(){
    $('.offer_previous_btn').addClass('hiddenBtn');
    $('.offer_submit_btn').addClass('hiddenBtn');
    $('.contact_previous_btn').removeClass('hiddenBtn');
    $('.contact_next_btn').removeClass('hiddenBtn');
});

$('#add-new-therapist-modal').on('hidden.bs.modal', function () {
    stepper.to(0);
    $('.name_next_btn').removeClass('hiddenBtn');
    $('.closeModal').removeClass('hiddenBtn');
    $('.name_next_btn').prop('disabled', true);
    $('.info_next_btn').addClass('hiddenBtn');
    $('.info_previous_btn').addClass('hiddenBtn');
    $('.info_next_btn').prop('disabled', true);
    $('.contact_previous_btn').addClass('hiddenBtn')
    $('.contact_next_btn').addClass('hiddenBtn')
    $('.offer_previous_btn').addClass('hiddenBtn');
    $('.offer_submit_btn').addClass('hiddenBtn');
    $('.offer_submit_btn').prop('disabled', true);
    $('.commission').addClass('hiddenBtn');
    $('.allowance').addClass('hiddenBtn');
});

$(document).on('click','.edit-therapist-btn',function(){
    let id = this.id;
    let user_id = $(this).data("user_id");
    $.ajax({
        'url' : '/therapist/'+id,
        'type' : 'GET',
        success: function(result){
            $('#edit_id').val(result.therapist.id);
            $('#edit_user_id').val(user_id);
            $('#edit_firstname').val(result.therapist.firstname);
            $('#edit_middlename').val(result.therapist.middlename);
            $('#edit_lastname').val(result.therapist.lastname);
            $('#edit_date_of_birth').val(result.therapist.date_of_birth);
            $('#edit_mobile_number').val(result.therapist.mobile_number);
            $('#edit_email').val(result.therapist.email);
            $('#edit_gender').val(result.therapist.gender);

            $('#edit_gender').html('');
            $('#edit_certificate').html('');
            $('#edit_offer_type').html('');
            if (result.therapist.gender === 'male') {
                $('#edit_gender').append('<option value="male" selected="selected">Male</option>');
                $('#edit_gender').append('<option value="female">Female</option>');
            } else if (result.therapist.gender === 'female') {
                $('#edit_gender').append('<option value="female" selected="selected">Female</option>');
                $('#edit_gender').append('<option value="male">Male</option>');
            } else {
                $('#edit_gender').append('<option value="" selected="selected">Select here</option>');
                $('#edit_gender').append('<option value="male">Male</option>');
                $('#edit_gender').append('<option value="female">Female</option>');
            }

            if (result.therapist.certificate === 'DOH') {
                $('#edit_certificate').append('<option value="DOH" selected="selected">DOH</option>');
                $('#edit_certificate').append('<option value="NC2">NC2</option>');
            } else if (result.therapist.certificate === 'NC2') {
                $('#edit_certificate').append('<option value="NC2" selected="selected">NC2</option>');
                $('#edit_certificate').append('<option value="DOH">DOH</option>');
            } else {
                $('#edit_certificate').append('<option value="" selected="selected">Select here</option>');
                $('#edit_certificate').append('<option value="DOH">DOH</option>');
                $('#edit_certificate').append('<option value="NC2">NC2</option>');
            }

            if (result.therapist.offer_type === 'percentage_only') {
                $('#edit_offer_type').append('<option value="percentage_only" selected="selected">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                $('.edit_commission').removeClass('hidden');
                $('.edit_commission_name').text('Commission Rate');
                $('.edit_allowance').addClass('hidden');
            } else if (result.therapist.offer_type === 'percentage_plus_allowance') {
                $('#edit_offer_type').append('<option value="percentage_plus_allowance" selected="selected">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                $('.edit_commission').removeClass('hidden');
                $('.edit_allowance').removeClass('hidden');
                $('.edit_commission_name').text('Commission Rate');
            } else if (result.therapist.offer_type === 'amount_only') {
                $('#edit_offer_type').append('<option value="amount_only" selected="selected">Amount Only</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                $('.edit_commission').removeClass('hidden');
                $('.edit_allowance').addClass('hidden');
                $('.edit_commission_name').text('Commission Amount');
            } else if (result.therapist.offer_type === 'amount_plus_allowance') {
                $('#edit_offer_type').append('<option value="amount_plus_allowance" selected="selected">Amount + Allowance</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');

                $('.edit_commission').removeClass('hidden');
                $('.edit_allowance').removeClass('hidden');
                $('.edit_commission_name').text('Commission Amount');
            } else {
                $('#edit_certificate').append('<option value="" selected="selected">Select here</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                $('.edit_commission').addClass('hidden');
                $('.edit_allowance').addClass('hidden');
            }

            $('#edit_commission').val(result.therapist.commission);
            $('#edit_allowance').val(result.therapist.allowance);
        }
    });
});

$(document).on('click','.update-therapist-btn',function(){
    var id = $('#edit_id').val();
    var user_id = $('#edit_user_id').val();
    var firstname = $('#edit_firstname').val();
    var middlename = $('#edit_middlename').val();
    var lastname = $('#edit_lastname').val();
    var date_of_birth = $('#edit_date_of_birth').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var gender = $('#edit_gender').val();
    var certificate = $('#edit_certificate').val();
    var commission = $('#edit_commission').val();
    var allowance = $('#edit_allowance').val();
    var offer_type = $('#edit_offer_type').val();

    var data = {
        id: id,
        user_id: user_id,
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        date_of_birth : date_of_birth,
        mobile_number : mobile_number,
        email : email,
        gender : gender,
        certificate : certificate,
        commission : commission,
        allowance : allowance,
        offer_type : offer_type
    };

    swal.fire({
        title: "Are you sure you want to update Therapist?",
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
                'url' : '/therapist/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-therapist-form').find('.update-therapist-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#therapist-form').trigger('reset');
                        reloadTherapistTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-therapist-modal').modal('hide');
                    } else {
                        if (result.status === false) {
                            swal.fire("Warning!", result.message, "warning");
                        } else {
                            swal.fire("Warning!", 'Kindly check all fields to view error.', "warning");
                            $.each(result, function (key, value) {
                                var element = $('#edit_'+key);
                
                                element.closest('div.'+key)
                                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                                    .find('.text-danger')
                                    .remove();
                                
                                element.after('<p class="text-danger">'+value+'</p>');
                            });
                        }
                    }
            
                    $('#update-therapist-form').find('.update-therapist-btn').val('Save').attr('disabled',false);
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
});

$('#edit_firstname, #edit_lastname').on('input',function(e){
    var firstname = $('#edit_firstname').val();
    var lastname = $('#edit_lastname').val();

    if (firstname.length > 0 && lastname.length > 0) {
        $('.edit_name_next_btn').prop('disabled', false);
    } else {
        $('.edit_name_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.edit_name_next_btn',function(){
    $('.edit_name_next_btn').addClass('hiddenBtn');
    $('.edit_closeModal').addClass('hiddenBtn');
    $('.edit_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_info_next_btn').removeClass('hiddenBtn');
});

$('#edit_gender').on('input',function(e){
    var gender = $('#edit_gender').val();

    if (gender.length > 0) {
        $('.edit_info_next_btn').prop('disabled', false);
    } else {
        $('.edit_info_next_btn').prop('disabled', true);
    }
});

$('#edit_mobile_number').on('input',function(e){
    var edit_mobile_number = $('#edit_mobile_number').val();

    if (edit_mobile_number.length > 0) {
        $('.edit_contact_next_btn').prop('disabled', false);
    } else {
        $('.edit_contact_next_btn').prop('disabled', true);
    }
});

$('#edit_offer_type, #edit_commission, #edit_allowance').on('input',function(e){
    var offer_type = $('#edit_offer_type').val();
    var commission = $('#edit_commission').val();
    var allowance = $('#edit_allowance').val();

    if (offer_type.length > 0 && commission.length > 0) {
        if (offer_type === 'percentage_plus_allowance' || offer_type === 'amount_plus_allowance') {
            if (allowance.length > 0) {
                $('.edit_offer_submit_btn').prop('disabled', false);
            } else {
                $('.edit_offer_submit_btn').prop('disabled', true);
            }
        } else {
            $('.edit_offer_submit_btn').prop('disabled', false);
        }
    } else {
        $('.edit_offer_submit_btn').prop('disabled', true);
    }
});

$(document).on('click','.edit_info_next_btn',function(){
    $('.edit_info_next_btn').addClass('hiddenBtn');
    $('.edit_info_previous_btn').addClass('hiddenBtn');
    $('.edit_contact_next_btn').removeClass('hiddenBtn');
    $('.edit_contact_previous_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_info_previous_btn',function(){
    $('.edit_name_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_info_previous_btn').addClass('hiddenBtn');
    $('.edit_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.edit_contact_next_btn',function(){
    $('.edit_contact_next_btn').addClass('hiddenBtn');
    $('.edit_contact_previous_btn').addClass('hiddenBtn');
    $('.edit_offer_previous_btn').removeClass('hiddenBtn');
    $('.edit_offer_submit_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_contact_previous_btn',function(){
    $('.edit_info_next_btn').removeClass('hiddenBtn');
    $('.edit_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_contact_previous_btn').addClass('hiddenBtn');
    $('.edit_contact_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.edit_offer_previous_btn',function(){
    $('.edit_offer_previous_btn').addClass('hiddenBtn');
    $('.edit_offer_submit_btn').addClass('hiddenBtn');
    $('.edit_contact_previous_btn').removeClass('hiddenBtn');
    $('.edit_contact_next_btn').removeClass('hiddenBtn');
});

$('#update-therapist-modal').on('hidden.bs.modal', function () {
    steppers.to(0);
    $('.edit_name_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_name_next_btn').prop('disabled', false);
    $('.edit_info_next_btn').addClass('hiddenBtn');
    $('.edit_info_previous_btn').addClass('hiddenBtn');
    $('.edit_info_next_btn').prop('disabled', false);
    $('.edit_contact_previous_btn').addClass('hiddenBtn')
    $('.edit_contact_next_btn').addClass('hiddenBtn')
    $('.edit_offer_previous_btn').addClass('hiddenBtn');
    $('.edit_offer_submit_btn').addClass('hiddenBtn');
    $('.edit_offer_submit_btn').prop('disabled', false);
    $('.edit_commission').addClass('hiddenBtn');
    $('.edit_allowance').addClass('hiddenBtn');
});

$(document).on('click','.delete-therapist-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete Therapist: "+data[1]+"?",
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
                'url' : '/therapist/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-therapist-form').find('.delete-therapist-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        reloadTherapistTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#delete-therapist-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#delete-therapist-form').find('.delete-therapist-modal-btn').val('Delete').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});