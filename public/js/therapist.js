document.addEventListener('DOMContentLoaded', function () {
    window.addTherapistStepper = new Stepper(document.querySelector('#bs-stepper-add-therapist'))
});

document.addEventListener('DOMContentLoaded', function () {
    window.editTherapistStepper = new Stepper(document.querySelector('#bs-stepper-update-therapist'))
});

$(document).on('click','.add-therapist-btn',function(){
    var firstname = $('#firstname').val();
    var middlename = $('#middlename').val();
    var lastname = $('#lastname').val();
    var date_of_birth = $('#date_of_birth').val();
    var mobile_number = $('#mobile_number').val();
    var email = $('#email').val();
    var gender = $('#gender').val();
    var certificate = $('#certificate').val();
    var commission_percentage = $('#commission_percentage').val();
    var commission_flat = $('#commission_flat').val();
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
        commission_percentage : commission_percentage,
        commission_flat : commission_flat,
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
                  $('#therapist-form').find('.add-therapist-btn').text('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    console.log(result);
                    if(result.status) {
                        $('#therapist-form').trigger('reset');
                        reloadTherapistTable();

                        swal.fire("Done!", result.message, "success");
                        $('#add-new-therapist-modal').modal('toggle');
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
        $('.therapist_name_next_btn').prop('disabled', false);
    } else {
        $('.therapist_name_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.therapist_name_next_btn',function(){
    $('.therapist_name_next_btn, .therapist_closeModal').addClass('hiddenBtn');
    $('.therapist_info_previous_btn, .therapist_info_next_btn').removeClass('hiddenBtn');
});

$('#gender').on('input',function(e){
    var gender = $('#gender').val();

    if (gender.length > 0) {
        $('.therapist_info_next_btn').prop('disabled', false);
    } else {
        $('.therapist_info_next_btn').prop('disabled', true);
    }
});

$('#mobile_number').on('input',function(e){
    var mobile_number = $('#mobile_number').val();

    if (mobile_number.length > 0) {
        $('.therapist_contact_next_btn').prop('disabled', false);
    } else {
        $('.therapist_contact_next_btn').prop('disabled', true);
    }
});

let offer_value;

$('#therapist-form #offer_type').on('change', function() {
    offer_value = $(this).find(":selected").val();

    $('.offers').hide();
    $('.offers input').val('');
    $('.therapist_offer_submit_btn').attr('disabled',true);
    if (offer_value === 'percentage_only') {
        $('.commission_percentage').show();
    } else if (offer_value === 'percentage_plus_allowance') {
        $('.commission_percentage, .allowance').show();
    } else if (offer_value === 'amount_only') {
        $('.commission_flat').show();
    } else if (offer_value === 'amount_plus_allowance') {
        $('.commission_flat, .allowance').show();
    }
});

$('#offer-part input').bind('keyup', function() {
    let btn_disabled = true;
    let targetElement = allFilled('#offer-part  input');
    if(
        (offer_value === 'percentage_only' && targetElement['commission_percentage'] > 0) ||
        (offer_value === 'percentage_plus_allowance' && targetElement['commission_percentage'] > 0 && targetElement['allowance'] > 0) ||
        (offer_value === 'amount_only' && targetElement['commission_flat'] > 0) ||
        (offer_value === 'amount_plus_allowance' && targetElement['commission_flat'] > 0 && targetElement['allowance'] > 0)
    ){
        btn_disabled = false;
    }

    $('.therapist_offer_submit_btn').attr('disabled',btn_disabled);
});



function allFilled(element) {
    let data = [];
    $(element).each(function() {
        data[this.id] = $(this).val().length;
    });
    return data;
}

$(document).on('click','.therapist_info_next_btn',function(){
    $('.therapist_info_next_btn, .therapist_info_previous_btn').addClass('hiddenBtn');
    $('.therapist_contact_next_btn, .therapist_contact_previous_btn').removeClass('hiddenBtn');
});

$(document).on('click','.therapist_info_previous_btn',function(){
    $('.therapist_name_next_btn, .therapist_closeModal').removeClass('hiddenBtn');
    $('.therapist_info_previous_btn, .therapist_info_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.therapist_contact_next_btn',function(){
    $('.therapist_contact_next_btn, .therapist_contact_previous_btn').addClass('hiddenBtn');
    $('.therapist_offer_previous_btn, .therapist_offer_submit_btn').removeClass('hiddenBtn');
});

$(document).on('click','.therapist_contact_previous_btn',function(){
    $('.therapist_info_next_btn, .therapist_info_previous_btn').removeClass('hiddenBtn');
    $('.therapist_contact_previous_btn, .therapist_contact_next_btn').addClass('hiddenBtn');
});

$(document).on('click','.therapist_offer_previous_btn',function(){
    $('.therapist_offer_previous_btn, .therapist_offer_submit_btn').addClass('hiddenBtn');
    $('.therapist_contact_previous_btn, .therapist_contact_next_btn').removeClass('hiddenBtn');
});

$('#add-new-therapist-modal').on('hidden.bs.modal', function () {
    addTherapistStepper.to(0);
    $('.therapist_name_next_btn').removeClass('hiddenBtn');
    $('.therapist_closeModal').removeClass('hiddenBtn').prop('disabled', true);
    $('.therapist_info_next_btn').addClass('hiddenBtn').prop('disabled', true);
    $('.therapist_info_previous_btn, .therapist_contact_previous_btn, .therapist_contact_next_btn, .therapist_offer_previous_btn').addClass('hiddenBtn')
    $('.therapist_offer_submit_btn').addClass('hiddenBtn').prop('disabled', true);
    $('.commission, .allowance').addClass('hiddenBtn');
});

$(document).on('click','.edit-therapist-btn',function(){
    let id = this.id;
    let user_id = $(this).data("user_id");
    let therapistForm = $('#update-therapist-form');

    $('.edit_commission').remove();
    $.ajax({
        'url' : '/therapist/'+id,
        'type' : 'GET',
        success: function(result){
            console.log(result);
            offer_value = result.therapist.offer_type;
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


                // $('#update-therapist-form').find('.edit_offer_type').append(percentage_input);
                therapistForm.find('.edit_commission_percentage').show()
                    .find('#edit_commission_percentage').val(result.therapist.commission_percentage);

            } else if (result.therapist.offer_type === 'percentage_plus_allowance') {
                $('#edit_offer_type').append('<option value="percentage_plus_allowance" selected="selected">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                therapistForm.find('.edit_commission_percentage').show()
                    .find('#edit_commission_percentage').val(result.therapist.commission_percentage);
                therapistForm.find('.edit_allowance').show()
                    .find('#edit_allowance').val(result.therapist.allowance);
                // $('#update-therapist-form').find('.edit_offer_type').append(percentage_input).append(allowance_input);

            } else if (result.therapist.offer_type === 'amount_only') {
                $('#edit_offer_type').append('<option value="amount_only" selected="selected">Amount Only</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                therapistForm.find('.edit_commission_flat').show()
                    .find('#edit_commission_flat').val(result.therapist.commission_flat);

                // $('.edit_commission').removeClass('hidden');
                // $('.edit_allowance').addClass('hidden');
                // $('.edit_commission_name').text('Commission Amount');
            } else if (result.therapist.offer_type === 'amount_plus_allowance') {
                $('#edit_offer_type').append('<option value="amount_plus_allowance" selected="selected">Amount + Allowance</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');

                therapistForm.find('.edit_commission_flat').show()
                    .find('#edit_commission_flat').val(result.therapist.commission_flat);
                therapistForm.find('.edit_allowance').show()
                    .find('#edit_allowance').val(result.therapist.allowance);
                // $('.edit_commission').removeClass('hidden');
                // $('.edit_allowance').removeClass('hidden');
                // $('.edit_commission_name').text('Commission Amount');
            } else {
                $('#edit_certificate').append('<option value="" selected="selected">Select here</option>');
                $('#edit_offer_type').append('<option value="percentage_only">Percentage Only</option>');
                $('#edit_offer_type').append('<option value="percentage_plus_allowance">Percentage + Allowance</option>');
                $('#edit_offer_type').append('<option value="amount_only">Amount Only</option>');
                $('#edit_offer_type').append('<option value="amount_plus_allowance">Amount + Allowance</option>');

                $('.edit_commission').addClass('hidden');
                $('.edit_allowance').addClass('hidden');
            }

            // $('#edit_commission').val(result.therapist.commission);
            $('#edit_allowance').val(result.therapist.allowance);
        }
    });
});

$('#update-therapist-form #edit_offer_type').on('change', function() {
    offer_value = $(this).find(":selected").val();
    $('.edit-offers').hide();
    $('.update-therapist-btn').attr('disabled',true);
    if (offer_value === 'percentage_only') {
        $('.edit_commission_percentage').show();
    } else if (offer_value === 'percentage_plus_allowance') {
        $('.edit_commission_percentage, .edit_allowance').show();
    } else if (offer_value === 'amount_only') {
        $('.edit_commission_flat').show();
    } else if (offer_value === 'amount_plus_allowance') {
        $('.edit_commission_flat, .edit_allowance').show();
    }
});

$('#edit-offer-part input').bind('keyup', function() {
    let btn_disabled = true;
    let targetElement = allFilled('#edit-offer-part input');

    if(
        (offer_value === 'percentage_only' && targetElement['edit_commission_percentage'] > 0) ||
        (offer_value === 'percentage_plus_allowance' && targetElement['edit_commission_percentage'] > 0 && targetElement['edit_allowance'] > 0) ||
        (offer_value === 'amount_only' && targetElement['edit_commission_flat'] > 0) ||
        (offer_value === 'amount_plus_allowance' && targetElement['edit_commission_flat'] > 0 && targetElement['edit_allowance'] > 0)
    ){
        btn_disabled = false;
    }
    $('.update-therapist-btn').attr('disabled',btn_disabled);
});

$(document).on('click','.update-therapist-btn',function(){
    let data = {
        id: $('#edit_id').val(),
        user_id: $('#edit_user_id').val(),
        firstname : $('#edit_firstname').val(),
        middlename : $('#edit_middlename').val(),
        lastname : $('#edit_lastname').val(),
        date_of_birth : $('#edit_date_of_birth').val(),
        mobile_number : $('#edit_mobile_number').val(),
        email : $('#edit_email').val(),
        gender : $('#edit_gender').val(),
        certificate : $('#edit_certificate').val(),
        commission_percentage : $('#edit_commission_percentage').val(),
        commission_flat : $('#edit_commission_flat').val(),
        allowance : $('#edit_allowance').val(),
        offer_type : $('#edit_offer_type').val()
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
                'url' : '/therapist/'+data['id'],
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-therapist-form').find('.update-therapist-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    console.log(result);
                    if(result.status) {
                        reloadTherapistTable();

                        swal.fire("Done!", result.message, "success");
                        $('#update-therapist-modal').modal('toggle');
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
