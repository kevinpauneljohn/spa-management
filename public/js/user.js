$(document).on('click','.add-owner-btn',function(){
    var firstname = $('#firstname').val();
    var middlename = $('#middlename').val();
    var lastname = $('#lastname').val();
    var mobileNo = $('#mobileNo').val();
    var email = $('#email').val();
    var username = $('#username').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

    var firstname_valid = false;
    if (firstname === '') {
        $('#firstname').addClass('errorForm');
        return false;
    } else {
        firstname_valid = true;
    }

    var lastname_valid = false;
    if (lastname === '') {
        $('#lastname').addClass('errorForm');
        return false;
    } else {
        lastname_valid = true;
    }

    var mobileNo_valid = false;
    if (mobileNo === '') {
        $('#mobileNo').addClass('errorForm');
        return false;
    } else {
        mobileNo_valid = true;
    }

    var email_valid = false;
    if (email === '') {
        $('#email').addClass('errorForm');
        return false;
    } else {
        if( !validateEmail(email)) {
            alert('Invalid Email format.');
            return false;
        } else {
            email_valid = true;
        }
    }

    var username_valid = false;
    if (username === '') {
        $('#username').addClass('errorForm');
        return false;
    } else {
        username_valid = true;
    }

    if (password === '') {
        $('#password').addClass('errorForm');
        return false;
    }

    if (password_confirmation === '') {
        $('#password_confirmation').addClass('errorForm');
        return false;
    }

    var password_valid = false;
    if (password == password_confirmation) {
        password_valid = true;
    } else {
        alert('Password not match.');
        return false;
    }

    var isValid = false;
    if (
        firstname_valid &&
        lastname_valid &&
        mobileNo_valid &&
        email_valid &&
        username_valid &&
        password_valid
    ) {
        isValid = true;
    }
    
    var data = {
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobileNo,
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
                        alert(result.message);
                        reloadOwnerTable();
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
        $('#firstname').removeClass('errorForm');
    } else {
        $('#firstname').addClass('errorForm');
    }
});

$('#lastname').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#lastname').removeClass('errorForm');
    } else {
        $('#lastname').addClass('errorForm');
    }
});

$('#mobileNo').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#mobileNo').removeClass('errorForm');
    } else {
        $('#mobileNo').addClass('errorForm');
    }
});

$('#email').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#email').removeClass('errorForm');
    } else {
        $('#email').addClass('errorForm');
    }
});

$('#username').on('input',function(e){
    if ($(this).val().length > 0) {
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
    alert("No backend function yet. Will continue tomorrow");
    let id = this.id;
    $.ajax({
        'url' : '/owners/'+id,
        'type' : 'GET',
        success: function(result){
            console.log(result);
            $('#update-id').val(result.user.id);
            $('#update-firstname').val(result.user.firstname);
            $('#update-middlename').val(result.user.middlename);
            $('#update-lastname').val(result.user.lastname);
            $('#update-mobileNo').val(result.user.mobile_number);
            $('#update-email').val(result.user.email);
            $('#update-username').val(result.user.username);
        }
    });
});

$(document).on('click','.update-owner-btn',function(){
    var firstname = $('#update-firstname').val();
    var middlename = $('#update-middlename').val();
    var lastname = $('#update-lastname').val();
    var mobileNo = $('#update-mobileNo').val();
    var email = $('#update-email').val();
    var username = $('#update-username').val();

    var firstname_valid = false;
    if (firstname === '') {
        $('#update-firstname').addClass('errorForm');
        return false;
    } else {
        firstname_valid = true;
    }

    var lastname_valid = false;
    if (lastname === '') {
        $('#update-lastname').addClass('errorForm');
        return false;
    } else {
        lastname_valid = true;
    }

    var mobileNo_valid = false;
    if (mobileNo === '') {
        $('#update-mobileNo').addClass('errorForm');
        return false;
    } else {
        mobileNo_valid = true;
    }

    var email_valid = false;
    if (email === '') {
        $('#update-email').addClass('errorForm');
        return false;
    } else {
        if( !validateEmail(email)) {
            alert('Invalid Email format.');
            return false;
        } else {
            email_valid = true;
        }
    }

    var username_valid = false;
    if (username === '') {
        $('#update-username').addClass('errorForm');
        return false;
    } else {
        username_valid = true;
    }

    var isValid = false;
    if (
        firstname_valid &&
        lastname_valid &&
        mobileNo_valid &&
        email_valid &&
        username_valid
    ) {
        isValid = true;
    }
    
    var data = {
        firstname : firstname,
        middlename : middlename,
        lastname : lastname,
        mobile_number : mobileNo,
        email : email,
        username : username
    };

    if (isValid) {
        var returnConfirmed = confirm("Are you sure you want to update Owners Information?");

        if (returnConfirmed) {
            // $.ajax({
            //     'url' : '/owners',
            //     'type' : 'POST',
            //     'data': data,
            //     'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            //     beforeSend: function () {
            //       $('#update-owner-form').find('.update-owner-btn').val('Saving ... ').attr('disabled',true);
            //     },success: function (result) {
            //         if(result.status) {
            //             $('#update-owner-form').trigger('reset');
            //             reloadOwnerTable();
        
            //             alert(result.message);
            //             $('#update-owner-modal').modal('hide');
            //         } else {
            //             alert(result.message);
            //             reloadOwnerTable();
            //         }
            
            //         $('#update-form').find('.update-owner-btn').val('Save').attr('disabled',false);
            //     },error: function(xhr, status, error){
            //         console.log(xhr);
            //     }
            // });
        } else {
            return false;
        }
    }
});

$('#update-firstname').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#update-firstname').removeClass('errorForm');
    } else {
        $('#update-firstname').addClass('errorForm');
    }
});

$('#update-lastname').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#update-lastname').removeClass('errorForm');
    } else {
        $('#update-lastname').addClass('errorForm');
    }
});

$('#update-mobileNo').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#update-mobileNo').removeClass('errorForm');
    } else {
        $('#update-mobileNo').addClass('errorForm');
    }
});

$('#update-email').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#update-email').removeClass('errorForm');
    } else {
        $('#update-email').addClass('errorForm');
    }
});

$('#update-username').on('input',function(e){
    if ($(this).val().length > 0) {
        $('#update-username').removeClass('errorForm');
    } else {
        $('#update-username').addClass('errorForm');
    }
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}