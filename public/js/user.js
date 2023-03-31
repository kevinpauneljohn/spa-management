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

    var isValid = true;
    
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

    if (isValid) {
        var returnConfirmed = confirm("Are you sure you want to register Owners Information?");

        if (returnConfirmed) {
            $.ajax({
                'url' : '/owners',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#owner-form').find('.add-owner-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#owner-form').trigger('reset');
                        reloadOwnerTable();
        
                        alert(result.message);
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
            return false;
        }
    }
});

$('#firstname').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#firstname');
        element.closest('div.firstname')
        .find('.text-danger')
        .remove();

        $('#firstname').removeClass('errorForm');
    } else {
        $('#firstname').addClass('errorForm');
    }
});

$('#lastname').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#lastname');
        element.closest('div.lastname')
        .find('.text-danger')
        .remove();

        $('#lastname').removeClass('errorForm');
    } else {
        $('#lastname').addClass('errorForm');
    }
});

$('#mobile_number').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#mobile_number');
        element.closest('div.mobile_number')
        .find('.text-danger')
        .remove();

        $('#mobile_number').removeClass('errorForm');
    } else {
        $('#mobile_number').addClass('errorForm');
    }
});

$('#email').on('input',function(e){
    if ($(this).val().length > 0) {
        var element = $('#email');
        element.closest('div.email')
        .find('.text-danger')
        .remove();

        $('#email').removeClass('errorForm');
    } else {
        $('#email').addClass('errorForm');
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
    if ($(this).val().length > 0) {
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
    }else if($('#password_confirmation').attr("type") == "password"){
        $('#password_confirmation').attr('type', 'text');
        $('#show_hide_confirm_password i').removeClass( "fa-eye-slash" );
        $('#show_hide_confirm_password i').addClass( "fa-eye" );
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

    var isValid = true;
    
    var data = {
        id: id,
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobile_number,
        email : email,
        username : username
    };

    if (isValid) {
        var returnConfirmed = confirm("Are you sure you want to update Owners Information?");

        if (returnConfirmed) {
            $.ajax({
                'url' : '/owners/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-owner-form').find('.update-owner-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#update-owner-form').trigger('reset');
                        reloadOwnerTable();
        
                        alert(result.message);
                        $('#update-owner-modal').modal('hide');
                    }

                    $('#update-owner-form').find('.update-owner-btn').val('Save').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            return false;
        }
    }
});

$('#edit_firstname').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#edit_firstname').removeClass('errorForm');
    } else {
        $('#edit_firstname').addClass('errorForm');
    }
});

$('#edit_lastname').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#edit_lastname').removeClass('errorForm');
    } else {
        $('#edit_lastname').addClass('errorForm');
    }
});

$('#edit_mobile_number').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#edit_mobile_number').removeClass('errorForm');
    } else {
        $('#edit_mobile_number').addClass('errorForm');
    }
});

$('#edit_email').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#edit_email').removeClass('errorForm');
    } else {
        $('#edit_email').addClass('errorForm');
    }
});

$('#edit_username').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#edit_username').removeClass('errorForm');
    } else {
        $('#edit_username').addClass('errorForm');
    }
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

$(document).on('click','.delete-owner-modal-btn',function(){
    let id = $('#deleteOwnerId').val();

    $.ajax({
        'url' : '/owners/'+id,
        'type' : 'DELETE',
        'data': '',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
          $('#delete-owner-form').find('.delete-owner-modal-btn').val('Deleting ... ').attr('disabled',true);
        },success: function (result) {
            if(result.status) {
                reloadOwnerTable();

                alert(result.message);
                $('#delete-owner-modal').modal('hide');
            }

            $('#delete-owner-form').find('.delete-owner-modal-btn').val('Delete').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});