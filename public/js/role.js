$(document).on('click','.add-role-btn',function(){
    var name = $('#name').val();

    swal.fire({
        title: "Are you sure you want to create Role?",
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
                'url' : '/role',
                'type' : 'POST',
                'data': {
                    name: name
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#role-form').find('.add-role-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#role-form').trigger('reset');
                        reloadRoleTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-role-modal').modal('hide');
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
            
                    $('#role-form').find('.add-role-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.edit-role-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/role/'+id,
        'type' : 'GET',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#edit_name').val(result.role.name);
            $('#edit_id').val(id);

            $('.role-title').text('Update '+result.role.name+' Details')
        }
    });
});

$(document).on('click','.update-role-btn',function(){
    var id = $('#edit_id').val();
    var name = $('#edit_name').val();

    swal.fire({
        title: "Are you sure you want to update role?",
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
                'url' : '/role/'+id,
                'type' : 'PUT',
                'data': {
                    name: name
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-role-form').find('.update-role-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#role-form').trigger('reset');
                        reloadRoleTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-role-modal').modal('hide');
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
            
                    $('#update-role-form').find('.update-role-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.delete-role-btn',function(){
    $tr = $(this).closest('tr');
    var id = this.id;
    var name = $(this).data("name")
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete role: "+data[0]+"?",
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
                'url' : '/role/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-role-form').find('.delete-role-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        reloadRoleTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#delete-role-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#delete-role-form').find('.delete-role-modal-btn').val('Delete').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});