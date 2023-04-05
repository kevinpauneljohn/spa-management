$(document).on('click','.add-permission-btn',function(){
    var permission = $('#permission').val();
    var roles = $('#roles').val();

    swal.fire({
        title: "Are you sure you want to create Permission?",
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
                'url' : '/permission',
                'type' : 'POST',
                'data': {
                    permission: permission,
                    roles: roles
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#permission-form').find('.add-permission-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#permission-form').trigger('reset');
                        reloadPermissionTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-permission-modal').modal('hide');
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
            
                    $('#permission-form').find('.add-permission-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.edit-permission-btn',function(){
    $tr = $(this).closest('tr');
    var id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    $('#edit_permission').val(data[0]);
    $('#edit_id').val(id);

    $.ajax({
        'url' : '/permission-roles',
        'type' : 'POST',
        'data' : {'name': data[0]},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#edit_roles').val(result).change();

            $('.permission-title').text('Update '+data[0]+' Details')
        }
    });
});

$(document).on('click','.update-permission-btn',function(){
    var id = $('#edit_id').val();
    var permission = $('#edit_permission').val();
    var roles = $('#edit_roles').val();

    swal.fire({
        title: "Are you sure you want to update permission?",
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
                'url' : '/permission/'+id,
                'type' : 'PUT',
                'data': {
                    edit_permission: permission,
                    edit_roles: roles
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#update-permission-form').find('.update-permission-btn').val('Saving ... ').attr('disabled',true);
                  $('.text-danger').remove();
                },success: function (result) {
                    if(result.status) {
                        $('#permission-form').trigger('reset');
                        reloadPermissionTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-permission-modal').modal('hide');
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
            
                    $('#update-permission-form').find('.update-permission-btn').val('Save').attr('disabled',false);
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

$(document).on('click','.delete-permission-btn',function(){
    $tr = $(this).closest('tr');
    var id = this.id;
    var name = $(this).data("name")
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete permission: "+data[1]+"?",
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
                'url' : '/permission/'+id+'/'+name,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-permission-form').find('.delete-permission-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        reloadPermissionTable();
        
                        swal.fire("Done!", result.message, "success");
                        $('#delete-permission-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#delete-permission-form').find('.delete-permission-modal-btn').val('Delete').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});