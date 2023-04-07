$(document).on('click','.add-spa-btn',function(){
    var name = $('#name').val();
    var address = $('#address').val();
    var number_of_rooms = $('#number_of_rooms').val();
    var license = $('#license').val();
    var owner_id = $('.user-id').val()

    var data = {
        name : name,
        address : address,
        number_of_rooms : number_of_rooms,
        license : license,
        owner_id : owner_id
    };

    swal.fire({
        title: "Are you sure you want to create Spa?",
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
                'url' : '/spa',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#spa-form').find('.add-spa-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#spa-form').trigger('reset');
                        reloadSpaTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-spa-modal').modal('hide');
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
            
                    $('#spa-form').find('.add-spa-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.edit-spa-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/spa/'+id,
        'type' : 'GET',
        success: function(result){
            $('#edit_id').val(result.spa.id);
            $('#edit_name').val(result.spa.name);
            $('#edit_address').val(result.spa.address);
            $('#edit_number_of_rooms').val(result.spa.number_of_rooms);
            $('#edit_license').val(result.spa.license);
            $('.spa-title').text('Update '+result.spa.name+' Details')
        }
    });
});

$(document).on('click','.update-spa-btn',function(){
    var id = $('#edit_id').val();
    var name = $('#edit_name').val();
    var address = $('#edit_address').val();
    var number_of_rooms = $('#edit_number_of_rooms').val();
    var license = $('#edit_license').val();
    
    var data = {
        id: id,
        name : name,
        address : address,
        number_of_rooms : number_of_rooms,
        license : license
    };

    swal.fire({
        title: "Are you sure you want to update Spa?",
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
                'url' : '/spa/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-spa-form').find('.update-spa-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#spa-form').trigger('reset');
                        reloadSpaTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-spa-modal').modal('hide');
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
            
                    $('#update-spa-form').find('.update-spa-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.delete-spa-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete Spa: "+data[1]+"?",
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
                'url' : '/spa/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-spa-form').find('.delete-spa-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        reloadSpaTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#delete-spa-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#delete-spa-form').find('.delete-spa-modal-btn').val('Delete').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});