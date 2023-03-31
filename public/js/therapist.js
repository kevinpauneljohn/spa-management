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
                        $.each(result, function (key, value) {
                            var element = $('#'+key);
            
                            element.closest('div.'+key)
                                .addClass(value.length > 0 ? 'has-error' : 'has-success')
                                .find('.text-danger')
                                .remove();
                            
                            element.after('<p class="text-danger">'+value+'</p>');
                        });
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

$(document).on('click','.edit-therapist-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/therapist/'+id,
        'type' : 'GET',
        success: function(result){
            $('#edit_id').val(result.therapist.id);
            $('#edit_firstname').val(result.therapist.firstname);
            $('#edit_middlename').val(result.therapist.middlename);
            $('#edit_lastname').val(result.therapist.lastname);
            $('#edit_date_of_birth').val(result.therapist.date_of_birth);
            $('#edit_mobile_number').val(result.therapist.mobile_number);
            $('#edit_email').val(result.therapist.email);
            $('#edit_gender').val(result.therapist.gender);
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
            $('#edit_certificate').val(result.therapist.certificate);
            $('#edit_commission').val(result.therapist.commission);
            $('#edit_allowance').val(result.therapist.allowance);
            $('#edit_offer_type').val(result.therapist.offer_type);
        }
    });
});

$(document).on('click','.update-therapist-btn',function(){
    var id = $('#edit_id').val();
    var firstname = $('#edit_firstname').val();
    var middlename = $('#edit_middlename').val();
    var lastname = $('#edit_lastname').val();
    var date_of_birth = $('#dedit_ate_of_birth').val();
    var mobile_number = $('#edit_mobile_number').val();
    var email = $('#edit_email').val();
    var gender = $('#edit_gender').val();
    var certificate = $('#edit_certificate').val();
    var commission = $('#edit_commission').val();
    var allowance = $('#edit_allowance').val();
    var offer_type = $('#edit_offer_type').val();
    
    var data = {
        id: id,
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