function loadRole(status, value)
{
    $.ajax({
        'url' : '/roles',
        'type' : 'GET',
        'data': {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.select-role').html('');
            $('.select-edit-role').html('');
        },success: function (result) {
            if (status == 'new') {
                $('.select-role').append('<option></option>');
                $('.select-role').select2({
                    placeholder: "Choose Role",
                    allowClear: true
                });

                $.each(result , function(index, val) { 
                    $('.select-role').append('<option value="'+index+'">'+index+'</option>');
                });
            } else {
                $.each(result , function(index, val) { 
                    $('.select-edit-role').append('<option value="'+index+'">'+index+'</option>');
                });

                $(".select-edit-role").select2().val(value).trigger("change");
                $('.select-edit-role').select2();
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

function loadSpa(status, value)
{
    $.ajax({
        'url' : '/spas',
        'type' : 'GET',
        'data': {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.select-spa').html('');
            $('.select-edit-spa').html('');
        },success: function (result) {
            if (status == 'new') {
                $('.select-spa').append('<option></option>');
                $('.select-spa').select2({
                    placeholder: "Choose Spa",
                    allowClear: true
                });     
                
                $.each(result , function(index, val) { 
                    $('.select-spa').append('<option value="'+val+'">'+index+'</option>');
                });
            } else {
                $.each(result , function(index, val) { 
                    $('.select-edit-spa').append('<option value="'+val+'">'+index+'</option>');
                });

                $(".select-edit-spa").select2().val(value).trigger("change");
                $('.select-edit-spa').select2()
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

$('.select-role').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    $('#selected-role').val(id);

    if (id == 'therapist') {
        $('.gender').removeClass('hiddenBtn');
        $('.certificate').removeClass('hiddenBtn');
        $('.offer_type_div').removeClass('hiddenBtn');
    } else {        
        if (!$('.gender').hasClass('hiddenBtn')) {
            $('.gender').addClass('hiddenBtn');
            $('.gender').val('');
        }

        if (!$('.certificate').hasClass('hiddenBtn')) {
            $('.certificate').addClass('hiddenBtn');
            $('.certificate').val('');
        }

        if (!$('.offer_type_div').hasClass('hiddenBtn')) {
            $('.offer_type_div').addClass('hiddenBtn');
            $('.offer_type').val('');

            if (!$('.commissionDiv').hasClass('hiddenBtn')) {
                $('.commissionDiv').addClass('hiddenBtn');
                $('.commission').val('');
            }

            if (!$('.allowanceDiv').hasClass('hiddenBtn')) {
                $('.allowanceDiv').addClass('hiddenBtn');
                $('.allowance').val('');
            }
        }
    }
});

$(".select-role").on("select2:unselecting", function(e) {
    $('#selected-role').val('');
});

$('.select-spa').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    $('#selected-spa').val(id);
});

$(".select-spa").on("select2:unselecting", function(e) {
    $('#selected-spa').val('');
});

$(document).on('click','.role_info_next_btn',function(){
    var role = $('#selected-role').val();
    var spa = $('#selected-spa').val();

    if (
        role.length > 0 && 
        spa.length > 0
    ) {
        stepper.next()

        $('.role_info_next_btn').addClass('hiddenBtn');
        $('.closeModal').addClass('hiddenBtn');
        $('.basic_info_previous_btn').removeClass('hiddenBtn');
        $('.basic_info_next_btn').removeClass('hiddenBtn');
    } else {
        alert('Role and Spa Field is required.');
    }
});

$('#offer_type').on('change',function(e){
    var val = $(this).val();
    $('.offer_type_field').removeClass('hiddenBtn');

    if (val == 'percentage_only') {
        $('.commissionDiv').removeClass('hiddenBtn');
        $('.commissionDiv label').text('Commission Percentage');

        if (!$('.allowanceDiv').hasClass('hiddenBtn')) {
            $('.allowanceDiv').addClass('hiddenBtn');
        }
    } else if (val == 'percentage_plus_allowance') {
        $('.commissionDiv label').text('Commission Percentage');

        $('.commissionDiv').removeClass('hiddenBtn');
        $('.allowanceDiv').removeClass('hiddenBtn');
    } else if (val == 'amount_only') {
        $('.commissionDiv').removeClass('hiddenBtn');
        $('.commissionDiv label').text('Commission Flat / Amount');

        if (!$('.allowanceDiv').hasClass('hiddenBtn')) {
            $('.allowanceDiv').addClass('hiddenBtn');
        }
    } else if (val == 'amount_plus_allowance') {
        $('.commissionDiv label').text('Commission Flat / Amount');

        $('.commissionDiv').removeClass('hiddenBtn');
        $('.allowanceDiv').removeClass('hiddenBtn');
    }
});

$('#firstname, #lastname').on('keyup, change, input',function(e){
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();

    if (
        firstname.length > 0 && 
        lastname.length > 0
    ) {
        $('.basic_info_next_btn').prop('disabled', false);
    } else {
        $('.basic_info_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.basic_info_previous_btn',function(){
    $('.role_info_next_btn').removeClass('hiddenBtn');
    $('.closeModal').removeClass('hiddenBtn');
    $('.basic_info_previous_btn').addClass('hiddenBtn');
    $('.basic_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.basic_info_next_btn',function(){
    if ($('.selected-role').val() == 'therapist') {
        var gender = $('#gender option:selected').val()
        var offer_type = $('#offer_type option:selected').val()
        var commission = $('.commission').val();
        var allowance = $('.allowance').val();

        $('.mobile_number span').html('');
        $('.email span').html('');
        $('.username span').html('');
        $('.password span').html('');
        $('.password_confirmation span').html('');
        if (gender.length > 0 && offer_type.length > 0) {
            if (commission.length > 0 || allowance.length > 0) {
                stepper.next()

                $('.basic_info_next_btn').addClass('hiddenBtn');
                $('.basic_info_previous_btn').addClass('hiddenBtn');
                $('.contact_info_previous_btn').removeClass('hiddenBtn');
                $('.contact_info_next_btn').removeClass('hiddenBtn');
                $('.contact_info_next_btn').prop('disabled', false);
                $('.add-staff-btn').prop('disabled', false);
            } else {
                alert('Commission or Allowance field is required.'); 
            }
        } else {
            alert('Gender and Offer type field is required.');
        }
    } else {
        stepper.next();
        $('.mobile_number span').html('*');
        $('.email span').html('*');
        $('.username span').html('*');
        $('.password span').html('*');
        $('.password_confirmation span').html('*');
        $('.add-staff-btn').prop('disabled', true);
        $('.contact_info_next_btn').prop('disabled', true);

        $('.basic_info_next_btn').addClass('hiddenBtn');
        $('.basic_info_previous_btn').addClass('hiddenBtn');
        $('.contact_info_previous_btn').removeClass('hiddenBtn');
        $('.contact_info_next_btn').removeClass('hiddenBtn');
    }
});

$(document).on('click','.contact_info_previous_btn',function(){
    $('.basic_info_next_btn').removeClass('hiddenBtn');
    $('.basic_info_previous_btn').removeClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.contact_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.contact_info_next_btn',function(){
    $('.contact_info_next_btn').addClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_previous_btn').removeClass('hiddenBtn');
    $('.credential_info_submit_btn').removeClass('hiddenBtn');

    $('#username').val('');
    $('#password').val('');
});

$(document).on('click','.credential_info_previous_btn',function(){
    $('.contact_info_next_btn').removeClass('hiddenBtn');
    $('.contact_info_previous_btn').removeClass('hiddenBtn');
    $('.credential_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').addClass('hiddenBtn');
});

$('#add-new-staff-modal').on('hidden.bs.modal', function () {
    stepper.to(0);
    $('.closeModal').removeClass('hiddenBtn');
    $('.role_info_next_btn').removeClass('hiddenBtn');
    $('.basic_info_next_btn').addClass('hiddenBtn');
    $('.basic_info_previous_btn').addClass('hiddenBtn');
    $('.basic_info_next_btn').prop('disabled', true);
    $('.contact_info_next_btn').addClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.contact_info_next_btn').prop('disabled', true);
    $('.credential_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').prop('disabled', true);
});

$(document).on('click','.add-staff-btn',function(){
    var role = $('#selected-role').val();
    var spa = $('#selected-spa').val();
    var firstname = $('#firstname').val();
    var middlename = $('#middlename').val();
    var lastname = $('#lastname').val();
    var mobile_number = $('#mobile_number').val();
    var email = $('#email').val();
    var username = $('#username').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

    if (role != 'therapist') {
        var password_valid = false;
        if (password == password_confirmation) {
            password_valid = true;
        } else {
            var element_password = $('#show_hide_password');
            element_password.closest('div.password')
            .find('.text-danger')
            .remove();
    
            element_password.after('<p class="text-danger">Password and Password confirmation does not match.</p>');
        }
    }

    var gender;
    var certificate;
    var offer_type;
    var allowance;
    var commission;
    if (role == 'therapist') {
        gender = $('#gender option:selected').val();
        certificate = $('#certificate option:selected').val();
        offer_type = $('#offer_type option:selected').val();
        allowance = $('#allowance').val();
        commission = $('#commission').val();
    }

    var data = {
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobile_number,
        email : email,
        username : username,
        password : password,
        password_confirmation : password,
        role: role,
        spa: spa,
        gender: gender,
        certificate: certificate,
        offer_type: offer_type,
        allowance: allowance,
        commission: commission
    };

    swal.fire({
        title: "Are you sure you want to register Staff Information?",
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
                'url' : '/my-staff-create',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#staff-form').find('.add-staff-btn').val('Saving ... ').attr('disabled',true);
                    $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#staff-form').trigger('reset');
                        reloadStaffTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-staff-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", 'Kindly check all fields to view error.', "warning");
                        $.each(result, function (key, value) {
                            var element = $('#'+key);
            
                            element.closest('div.'+key)
                                .addClass(value.length > 0 ? 'has-error' : 'has-success')
                                .find('.text-danger')
                                .remove();
                            if (key === 'password') {
                                var element_password = $('#show_hide_password');

                                element_password.after('<p class="text-danger">'+value+'</p>');
                            } else if (key === 'password_confirmation') {
                                var element_confirm_password = $('#show_hide_confirm_password');

                                element_confirm_password.after('<p class="text-danger">'+value+'</p>');
                            } else {
                                element.after('<p class="text-danger">'+value+'</p>');
                            }
                        });
                    }
            
                    $('#staff-form').find('.add-staff-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.edit-staff-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/my-staff-show/'+id,
        'type' : 'GET',
        beforeSend: function () {
            $('.modalUpdateTitle').text('');
            $('#edit_id').val('');
            $('#edit_firstname').val('');
            $('#edit_middlename').val('');
            $('#edit_lastname').val('');
            $('#edit_mobile_number').val('');
            $('#edit_email').val('');
            $('#edit_username').val('');
            $('#edit_username').val('');
            $('#selected-edit-role').val('');
            $('#selected-edit-spa').val('');
            $('.edit_gender').val('');
            $('.edit_gender_data').val('');
            $('.edit_certificate').val('');
            $('.edit_certificate_data').val('');
            $('.edit_offer_type').val('');
            $('.edit_offer_type_data').val('');
            $('.edit_therapist_id').val('');
            $('.edit_commission').val('');
            $('.edit_commission_data').val('');
            $('.edit_allowance').val('');
            $('.edit_allowance_data').val('');
        },
        success: function(result){
            $('.modalUpdateTitle').text('Update [ '+result.staff.firstname+' '+result.staff.lastname+' ] account details');
            $('#edit_id').val(result.staff.id);
            $('#edit_firstname').val(result.staff.firstname);
            $('#edit_middlename').val(result.staff.middlename);
            $('#edit_lastname').val(result.staff.lastname);
            $('#edit_mobile_number').val(result.staff.mobile_number);
            $('#edit_email').val(result.staff.email);
            $('#edit_username').val(result.staff.username);
            $('#edit_username').val(result.staff.username);
            $('#selected-edit-role').val(result.role);
            $('#selected-edit-spa').val(result.staff.spa_id);

            loadRole('edit', result.role);
            loadSpa('edit', result.staff.spa_id);

            if (result.role == 'therapist') {
                $('.edit_gender').removeClass('hiddenBtn');
                $('.edit_gender').val(result.is_therapist.gender);
                $('.edit_gender_data').val(result.is_therapist.gender);
                $('.edit_certificate').removeClass('hiddenBtn');
                $('.edit_certificate').val(result.is_therapist.certificate);
                $('.edit_certificate_data').val(result.is_therapist.certificate);
                $('.edit_offer_type_div').removeClass('hiddenBtn');
                $('.edit_offer_type').val(result.is_therapist.offer_type);
                $('.edit_offer_type_data').val(result.is_therapist.offer_type);
                $('.edit_therapist_id').val(result.is_therapist.id);
                
                if (result.is_therapist.offer_type != '') {
                    $('.edit_offer_type_field').removeClass('hiddenBtn');
                    if (result.is_therapist.offer_type == 'percentage_only') {
                        $('.edit_commissionDiv label').text('Commission Percentage');
                        $('.edit_commissionDiv').removeClass('hiddenBtn');
                        $('.edit_commission').val(result.is_therapist.commission_percentage);
                        $('.edit_commission_data').val(result.is_therapist.commission_percentage);

                        if (!$('.edit_allowanceDiv').hasClass('hiddenBtn')) {
                            $('.edit_allowanceDiv').addClass('hiddenBtn');
                        }
                    } else if (result.is_therapist.offer_type == 'percentage_plus_allowance') {
                        $('.edit_commissionDiv label').text('Commission Percentage');
                        $('.edit_commissionDiv').removeClass('hiddenBtn');
                        $('.edit_commission').val(result.is_therapist.commission_percentage);
                        $('.edit_commission_data').val(result.is_therapist.commission_percentage);

                        $('.edit_allowanceDiv').removeClass('hiddenBtn');
                        $('.edit_allowance').val(result.is_therapist.allowance);
                        $('.edit_allowance_data').val(result.is_therapist.allowance);
                    } else if (result.is_therapist.offer_type == 'amount_only') {
                        $('.edit_commissionDiv label').text('Commission Flat / Amount');
                        $('.edit_commissionDiv').removeClass('hiddenBtn');
                        $('.edit_commission').val(result.is_therapist.commission_flat);
                        $('.edit_commission_data').val(result.is_therapist.commission_flat);

                        if (!$('.edit_allowanceDiv').hasClass('hiddenBtn')) {
                            $('.edit_allowanceDiv').addClass('hiddenBtn');
                        }
                    } else if (result.is_therapist.offer_type == 'amount_plus_allowance') {
                        $('.edit_commissionDiv label').text('Commission Flat / Amount');
                        $('.edit_commissionDiv').removeClass('hiddenBtn');
                        $('.edit_commission').val(result.is_therapist.commission_flat);
                        $('.edit_commission_data').val(result.is_therapist.commission_flat);

                        $('.edit_allowanceDiv').removeClass('hiddenBtn');
                        $('.edit_allowance').val(result.is_therapist.allowance);
                        $('.edit_allowance_data').val(result.is_therapist.allowance);
                    } else {
                        $('.edit_commissionDiv label').text('Commission');
                        $('.edit_commissionDiv').addClass('hiddenBtn');
                        $('.edit_commission').val('');

                        $('.edit_allowanceDiv').addClass('hiddenBtn');
                        $('.edit_allowance').val('');
                    }
                }
            } else {
                $('.edit_gender').addClass('hiddenBtn');
                $('.edit_gender').val('');
                $('.edit_certificate').addClass('hiddenBtn');
                $('.edit_certificate').val('');
                $('.edit_offer_type_div').addClass('hiddenBtn');
                $('.edit_offer_type').val('');
                $('.edit_therapist_id').val('');
                $('.edit_offer_type_field').addClass('hiddenBtn');
                $('.edit_commissionDiv label').text('Commission');
                $('.edit_commissionDiv').addClass('hiddenBtn');
                $('.edit_commission').val('');
                $('.edit_allowanceDiv').addClass('hiddenBtn');
                $('.edit_allowance').val('');
            }
        }
    });
});

$('.select-edit-role').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    $('#selected-edit-role').val(id);

    if (id == 'therapist') {       
        $('.edit_gender').removeClass('hiddenBtn');
        if ($('.edit_gender_data').val() != '') {
            $('.edit_gender').val($('.edit_gender_data').val());
        } else {
            $('.edit_gender').val('');
        }
        
        $('.edit_certificate').removeClass('hiddenBtn');
        if ($('.edit_certificate_data').val() != '') {
            $('.edit_certificate').val($('.edit_certificate_data').val());
        } else {
            $('.edit_certificate').val('');
        }

        $('.edit_offer_type_div').removeClass('hiddenBtn');
        if ($('.edit_offer_type_data').val() != '') {
            $('.edit_offer_type').val($('.edit_offer_type_data').val());
        } else {
            $('.edit_offer_type').val('');
        }

        if ($('.edit_commission_data').val() != '') {
            $('.edit_commission').val($('.edit_commission_data').val());
        } else {
            $('.edit_commission').val('');
        }

        if ($('.edit_allowance_data').val() != '') {
            $('.edit_allowance').val($('.edit_allowance_data').val());
        } else {
            $('.edit_allowance').val('');
        }

    } else {        
        if (!$('.edit_gender').hasClass('hiddenBtn')) {
            $('.edit_gender').addClass('hiddenBtn');
            $('.edit_gender').val('');
        }

        if (!$('.edit_certificate').hasClass('hiddenBtn')) {
            $('.edit_certificate').addClass('hiddenBtn');
            $('.edit_certificate').val('');
        }

        if (!$('.edit_offer_type_div').hasClass('hiddenBtn')) {
            $('.edit_offer_type_div').addClass('hiddenBtn');
            $('.edit_offer_type').val('');

            if (!$('.edit_commissionDiv').hasClass('hiddenBtn')) {
                $('.edit_commissionDiv').addClass('hiddenBtn');
                $('.edit_commission').val('');
            }

            if (!$('.edit_allowanceDiv').hasClass('hiddenBtn')) {
                $('.edit_allowanceDiv').addClass('hiddenBtn');
                $('.edit_allowance').val('');
            }
        }
    }
});

$(".select-edit-role").on("select2:unselecting", function(e) {
    $('#selected-edit-role').val('');
});

$('.select-spa').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    $('#selected-edit-spa').val(id);
});

$(".select-spa").on("select2:unselecting", function(e) {
    $('#selected-edit-spa').val('');
});

$(document).on('click','.edit_role_info_next_btn',function(){
    $('.edit_role_info_next_btn').addClass('hiddenBtn');
    $('.edit_closeModal').addClass('hiddenBtn');
    $('.edit_basic_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_basic_info_next_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_basic_info_previous_btn',function(){
    $('.edit_basic_info_next_btn').addClass('hiddenBtn');
    $('.edit_basic_info_previous_btn').addClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_role_info_next_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_basic_info_next_btn',function(){
    if ($('.selected-edit-role').val() == 'therapist') {
        var gender = $('#edit_gender option:selected').val()
        var offer_type = $('#edit_offer_type option:selected').val()
        var commission = $('.edit_commission').val();
        var allowance = $('.edit_allowance').val();

        $('.edit_mobile_number span').html('');
        $('.edit_email span').html('');
        $('.edit_username span').html('');
        if (gender.length > 0 && offer_type.length > 0) {
            if (commission.length > 0 || allowance.length > 0) {
                steppers.next()

                $('.edit_basic_info_next_btn').addClass('hiddenBtn');
                $('.edit_basic_info_previous_btn').addClass('hiddenBtn');
                $('.edit_contact_info_previous_btn').removeClass('hiddenBtn');
                $('.edit_contact_info_next_btn').removeClass('hiddenBtn');
            } else {
                alert('Commission or Allowance field is required.'); 
            }
        } else {
            alert('Gender and Offer type field is required.');
        }
    } else {
        $('.edit_mobile_number span').html('*');
        $('.edit_email span').html('*');
        $('.edit_username span').html('*');

        steppers.next()
        if ($('#edit_mobile_number').val() != '' && $('#edit_email').val() != '') {
            $('.edit_contact_info_next_btn').prop('disabled', false);
        } else {
            $('.edit_contact_info_next_btn').prop('disabled', true);
        }
        
        if ($('#edit_username').val() != '') {
            $('.edit_credential_info_submit_btn').prop('disabled', false);
        } else {
            $('.edit_credential_info_submit_btn').prop('disabled', true);
        }

        $('.edit_basic_info_next_btn').addClass('hiddenBtn');
        $('.edit_basic_info_previous_btn').addClass('hiddenBtn');
        $('.edit_contact_info_previous_btn').removeClass('hiddenBtn');
        $('.edit_contact_info_next_btn').removeClass('hiddenBtn');
    }
});

$(document).on('click','.edit_contact_info_previous_btn',function(){
    $('.edit_basic_info_next_btn').removeClass('hiddenBtn');
    $('.edit_basic_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').addClass('hiddenBtn');
    $('.edit_contact_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.edit_contact_info_next_btn',function(){
    $('.edit_contact_info_next_btn').addClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').addClass('hiddenBtn');
    $('.edit_credential_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_credential_info_previous_btn',function(){
    $('.edit_contact_info_next_btn').removeClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_credential_info_previous_btn').addClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').addClass('hiddenBtn');
});

$('#edit_offer_type').on('change',function(e){
    var val = $(this).val();
    $('.edit_offer_type_field').removeClass('hiddenBtn');

    if (val == 'percentage_only') {
        $('.edit_commissionDiv').removeClass('hiddenBtn');
        $('.edit_commissionDiv label').text('Commission Percentage');

        if (!$('.edit_allowanceDiv').hasClass('hiddenBtn')) {
            $('.edit_allowanceDiv').addClass('hiddenBtn');
        }
    } else if (val == 'percentage_plus_allowance') {
        $('.edit_commissionDiv label').text('Commission Percentage');

        $('.edit_commissionDiv').removeClass('hiddenBtn');
        $('.edit_allowanceDiv').removeClass('hiddenBtn');
    } else if (val == 'amount_only') {
        $('.edit_commissionDiv').removeClass('hiddenBtn');
        $('.edit_commissionDiv label').text('Commission Flat / Amount');

        if (!$('.edit_allowanceDiv').hasClass('hiddenBtn')) {
            $('.edit_allowanceDiv').addClass('hiddenBtn');
        }
    } else if (val == 'amount_plus_allowance') {
        $('.edit_commissionDiv label').text('Commission Flat / Amount');

        $('.edit_commissionDiv').removeClass('hiddenBtn');
        $('.edit_allowanceDiv').removeClass('hiddenBtn');
    }
});

$(document).on('click','.update-staff-btn',function(){
    var id = $('#edit_id').val();
    var firstname = $('#edit_firstname').val();
    var middlename = $('#edit_middlename').val();
    var lastname = $('#edit_lastname').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var username = $('#edit_username').val();
    var role = $('#selected-edit-role').val();
    var spa = $('#selected-edit-spa').val();

    var gender;
    var certificate;
    var offer_type;
    var allowance;
    var commission;
    var therapist_id = $('#edit_therapist_id').val();
    if (role == 'therapist') {
        gender = $('#edit_gender option:selected').val();
        certificate = $('#edit_certificate option:selected').val();
        offer_type = $('#edit_offer_type option:selected').val();
        allowance = $('#edit_allowance').val();
        commission = $('#edit_commission').val();
    }

    var data = {
        id: id,
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobile_number,
        email : email,
        username : username,
        role: role,
        spa: spa,
        gender: gender,
        certificate: certificate,
        offer_type: offer_type,
        allowance: allowance,
        commission: commission,
        therapist_id: therapist_id
    };

    swal.fire({
        title: "Are you sure you want to update Staff Information?",
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
                'url' : '/my-staff-update/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#update-staff-form').find('.update-staff-btn').val('Saving ... ').attr('disabled',true);
                    $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#update-staff-form').trigger('reset');
                        reloadStaffTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-staff-modal').modal('hide');
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
            
                    $('#update-staff-form').find('.update-staff-btn').val('Save').attr('disabled',false);
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

$('#update-staff-modal').on('hidden.bs.modal', function () {
    steppers.to(0);
    $('.edit_role_info_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_basic_info_next_btn').addClass('hiddenBtn');
    $('.edit_basic_info_previous_btn').addClass('hiddenBtn');
    $('.edit_basic_info_next_btn').prop('disabled', false);
    $('.edit_contact_info_next_btn').addClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').addClass('hiddenBtn');
    $('.edit_contact_info_next_btn').prop('disabled', false);
    $('.edit_credential_info_previous_btn').addClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').addClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').prop('disabled', false);
});

$(document).on('click','.delete-staff-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete Staff: "+data[1]+"?",
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
                'url' : '/my-staff-delete/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                   
                },success: function (result) {
                    if(result.status) {
                        reloadStaffTable();
        
                        swal.fire("Done!", result.message, "success");
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});

$('#firstname').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#firstname');
        element.closest('div.firstname')
        .find('.text-danger')
        .remove();
    }
});

$('#lastname').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#lastname');
        element.closest('div.lastname')
        .find('.text-danger')
        .remove();
    }
});

$('#mobile_number').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#mobile_number');
        element.closest('div.mobile_number')
        .find('.text-danger')
        .remove();
    }
});

$('#email').on('input',function(e){
    var validate = validateEmail($(this).val());
    if ($(this).val().length > 0) {
        if (validate) {
            var element = $('#email');
            element.closest('div.email')
            .find('.text-danger')
            .remove();

            $('.emailValidation').addClass('hiddenBtn');
        } else {
            $('.emailValidation').removeClass('hiddenBtn');
            $('.emailValidation').text('Invalid Email format.');
        }
    }
});

$('#mobile_number, #email').on('input',function(e){
    var mobile_number = $('#mobile_number').val();
    var email = $('#email').val();

    var validate = validateEmail(email);
    if (mobile_number.length > 0 && email.length > 0) {
        if (validate) {
            $('.contact_info_next_btn').prop('disabled', false);
        } else {
            $('.contact_info_next_btn').prop('disabled', true);
        }
    } else {
        $('.contact_info_next_btn').prop('disabled', true);
    }
});

$('#username').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#username');
        element.closest('div.username')
        .find('.text-danger')
        .remove();

        $('#username').removeClass('errorForm');
    } else {
        $('#username').addClass('errorForm');
    }
});

$('#password').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#password').removeClass('errorForm');
        $('.password_icon').prop("disabled", false);
    } else {
        $('#password').addClass('errorForm');
    }
});

$(document).on('click','.password_icon',function(){
    if($('#password').attr("type") == "text"){
        $('#password').attr('type', 'password');
        $('#show_hide_password i').addClass("fa-eye-slash");
        $('#show_hide_password i').removeClass("fa-eye");
    }else if($('#password').attr("type") == "password"){
        $('#password').attr('type', 'text');
        $('#show_hide_password i').removeClass( "fa-eye-slash" );
        $('#show_hide_password i').addClass( "fa-eye" );
    }
});

$('#password_confirmation').on('input',function(e){
    var password =  $('#password').val();
    var confirm_password =  $(this).val();
    if ($(this).val().length > 0) {
        if (password === confirm_password) {
            $('.passwordValidation').addClass('hiddenBtn');
        } else {
            $('.passwordValidation').removeClass('hiddenBtn');
            $('.passwordValidation').text('Password and Confirm password not match!');
        }

        $('#password_confirmation').removeClass('errorForm');
        $('.confirm_password_icon').prop("disabled", false);
    } else {
        $('#password_confirmation').addClass('errorForm');
    }
});

$(document).on('click','.confirm_password_icon',function(){
    if($('#password_confirmation').attr("type") == "text"){
        $('#password_confirmation').attr('type', 'password');
        $('#show_hide_confirm_password i').addClass("fa-eye-slash");
        $('#show_hide_confirm_password i').removeClass("fa-eye");
    } else if($('#password_confirmation').attr("type") == "password"){
        $('#password_confirmation').attr('type', 'text');
        $('#show_hide_confirm_password i').removeClass( "fa-eye-slash" );
        $('#show_hide_confirm_password i').addClass( "fa-eye" );
    }
});

$('#username, #password, #password_confirmation').on('input',function(e){
    var username = $('#username').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

    if (username.length > 0 && password.length > 0 && password_confirmation.length > 0) {
        if (password === password_confirmation) {
            $('.credential_info_submit_btn').prop('disabled', false);
        } else {
            $('.credential_info_submit_btn').prop('disabled', true);
        }
    } else {
        $('.credential_info_submit_btn').prop('disabled', true);
    }
});

$('#edit_firstname, #edit_lastname').on('input',function(e){
    var firstname = $('#edit_firstname').val();
    var lastname = $('#edit_lastname').val();

    if (firstname.length > 0 && lastname.length > 0) {
        $('.edit_basic_info_next_btn').prop('disabled', false);
    } else {
        $('.edit_basic_info_next_btn').prop('disabled', true);
    }
});

$('#edit_email').on('input',function(e){
    var validate = validateEmail($(this).val());
    if ($(this).val().length > 0) {
        if (validate) {
            $('.emailValidationEdit').addClass('hiddenBtn');
        } else {
            $('.emailValidationEdit').removeClass('hiddenBtn');
            $('.emailValidationEdit').text('Invalid Email format.');
        }
    }
});

$('#edit_mobile_number, #edit_email').on('input',function(e){
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();

    var validate = validateEmail(email);
    if (mobile_number.length > 0 && email.length > 0) {
        if (validate) {
            $('.edit_contact_info_next_btn').prop('disabled', false);
        } else {
            $('.edit_contact_info_next_btn').prop('disabled', true);
        }
    } else {
        $('.edit_contact_info_next_btn').prop('disabled', true);
    }
});

$('#edit_username').on('input',function(e){
    var username = $('#edit_username').val();

    if (username.length > 0) {
        $('.edit_credential_info_submit_btn').prop('disabled', false);
    } else {
        $('.edit_credential_info_submit_btn').prop('disabled', true);
    }
});



function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}