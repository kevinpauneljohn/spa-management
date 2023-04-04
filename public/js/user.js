$(document).on('click','.add-owner-btn',function(){
    var firstname = $('#firstname').val();
    var middlename = $('#middlename').val();
    var lastname = $('#lastname').val();
    var mobile_number = $('#mobile_number').val();
    var email = $('#email').val();
    var username = $('#username').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

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

    
    var data = {
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobile_number,
        email : email,
        username : username,
        password : password,
        password_confirmation : password,
    };

    swal.fire({
        title: "Are you sure you want to register Owners Information?",
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
                'url' : '/owners',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#owner-form').find('.add-owner-btn').val('Saving ... ').attr('disabled',true);
                    $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#owner-form').trigger('reset');
                        reloadOwnerTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-owner-modal').modal('hide');
                    } else {
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
            
                    $('#owner-form').find('.add-owner-btn').val('Save').attr('disabled',false);
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

$('#firstname, #lastname').on('input',function(e){
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();

    if (firstname.length > 0 && lastname.length > 0) {
        $('.basic_info_next_btn').prop('disabled', false);
    } else {
        $('.basic_info_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.basic_info_next_btn',function(){
    $('.basic_info_next_btn').addClass('hiddenBtn');
    $('.closeModal').addClass('hiddenBtn');
    $('.contact_info_previous_btn').removeClass('hiddenBtn');
    $('.contact_info_next_btn').removeClass('hiddenBtn');
});

$(document).on('click','.contact_info_previous_btn',function(){
    $('.basic_info_next_btn').removeClass('hiddenBtn');
    $('.closeModal').removeClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.contact_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.contact_info_next_btn',function(){
    $('.contact_info_next_btn').addClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_previous_btn').removeClass('hiddenBtn');
    $('.credential_info_submit_btn').removeClass('hiddenBtn');
});

$(document).on('click','.credential_info_previous_btn',function(){
    $('.contact_info_next_btn').removeClass('hiddenBtn');
    $('.contact_info_previous_btn').removeClass('hiddenBtn');
    $('.credential_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').addClass('hiddenBtn');
});

$('#add-new-owner-modal').on('hidden.bs.modal', function () {
    stepper.to(0);
    $('.basic_info_next_btn').removeClass('hiddenBtn');
    $('.closeModal').removeClass('hiddenBtn');
    $('.basic_info_next_btn').prop('disabled', true);
    $('.contact_info_next_btn').addClass('hiddenBtn');
    $('.contact_info_previous_btn').addClass('hiddenBtn');
    $('.contact_info_next_btn').prop('disabled', true);
    $('.credential_info_previous_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').addClass('hiddenBtn');
    $('.credential_info_submit_btn').prop('disabled', true);
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

$(document).on('click','.edit-owner-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/owners/'+id,
        'type' : 'GET',
        success: function(result){
            $('#edit_id').val(result.user.id);
            $('#edit_firstname').val(result.user.firstname);
            $('#edit_middlename').val(result.user.middlename);
            $('#edit_lastname').val(result.user.lastname);
            $('#edit_mobile_number').val(result.user.mobile_number);
            $('#edit_email').val(result.user.email);
            $('#edit_username').val(result.user.username);
        }
    });
});

$(document).on('click','.update-owner-btn',function(){
    var id = $('#edit_id').val();
    var firstname = $('#edit_firstname').val();
    var middlename = $('#edit_middlename').val();
    var lastname = $('#edit_lastname').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var username = $('#edit_username').val();
    
    var data = {
        id: id,
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobile_number,
        email : email,
        username : username
    };

    swal.fire({
        title: "Are you sure you want to update Owners Information?",
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
                'url' : '/owners/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#update-owner-form').find('.update-owner-btn').val('Saving ... ').attr('disabled',true);
                    $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#update-owner-form').trigger('reset');
                        reloadOwnerTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-owner-modal').modal('hide');
                    } else {
                        if (result.status === false) {
                            swal.fire("Warning!", result.message, "warning");
                        } else {
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
            
                    $('#update-owner-form').find('.update-owner-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.edit_basic_info_next_btn',function(){
    $('.edit_basic_info_next_btn').addClass('hiddenBtn');
    $('.edit_closeModal').addClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').removeClass('hiddenBtn');
    $('.edit_contact_info_next_btn').removeClass('hiddenBtn');
});

$(document).on('click','.edit_contact_info_previous_btn',function(){
    $('.edit_basic_info_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
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

$('#update-owner-modal').on('hidden.bs.modal', function () {
    steppers.to(0);
    $('.edit_basic_info_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_basic_info_next_btn').prop('disabled', false);
    $('.edit_contact_info_next_btn').addClass('hiddenBtn');
    $('.edit_contact_info_previous_btn').addClass('hiddenBtn');
    $('.edit_contact_info_next_btn').prop('disabled', false);
    $('.edit_credential_info_previous_btn').addClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').addClass('hiddenBtn');
    $('.edit_credential_info_submit_btn').prop('disabled', false);
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

$(document).on('click','.delete-owner-btn',function () {
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('#deleteOwnerId').val(id);
    $('.delete-owner-name').html('<strong style="color:red;">'+data[1]+'</strong>?');
});

$(document).on('click','.delete-owner-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete Owner: "+data[1]+"?",
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
                'url' : '/owners/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-owner-form').find('.delete-owner-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        reloadOwnerTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#delete-owner-modal').modal('hide');
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