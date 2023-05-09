let tableName = $('#inventory-list');
let inventoryModal = $('#inventory-modal');
let inventoryForm = $('#inventory-form');
let overlay = '<div class="overlay dark"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
let editItemId;

$(document).on('submit','.inventory-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/inventories',
        method: 'POST',
        data: data,
        beforeSend: function(){
            inventoryForm.find('.modal-content').append(overlay)
                .find('#inventory-modal-btn').attr('disabled',true).text('Saving...');
        }
    }).done( (result) => {
        console.log(result)

        if(result.success === true)
        {
            inventoryForm.trigger('reset');

            Toast.fire({
                type: 'success',
                title: result.message
            });
            tableName.DataTable().ajax.reload(null, false);
        }
    }).fail( (xhr, status, error) => {
        $.each(xhr.responseJSON.errors, function (key, value) {
            let element = $('.'+key);

            element.find('.error-'+key).remove();
            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            toastr.error(value)
        });
    }).always( () => {
        $('.overlay').remove();
        inventoryForm.find('#inventory-modal-btn').attr('disabled',false).text('Save');
    });
    clear_errors('spa_id','name','quantity','unit','category');
});

$('#inventory-modal-btn').on('click',function(){
    inventoryForm.find('.text-danger').remove();
    inventoryForm.find('.modal-title').text('Add New Item');
    inventoryForm.trigger('reset');
    inventoryForm.removeClass().addClass('inventory-form');
})

$(document).on('click','.edit-inventory-btn',function(){
    editItemId = this.id;
    inventoryForm.find('.modal-title').text('Edit Item');
    inventoryForm.removeClass().addClass('edit-inventory-form');
    inventoryForm.find('.text-danger').remove();
    inventoryModal.modal('toggle');

    $.ajax({
        url: '/inventories/'+editItemId+'/edit',
        method: 'GET',
        beforeSend: function(){
            inventoryForm.find('.modal-content').append(overlay)
                .find('#inventory-modal-btn').attr('disabled',true);
        }
    }).done( (result) => {
        $.each(result,function (key, value){
            inventoryForm.find('#'+key).val(value).change();
        })
    }).fail( (xhr, status, error) => {
        console.log(xhr);
    }).always( () => {
        inventoryForm.find('.overlay').remove();
        inventoryForm.find('#inventory-modal-btn').attr('disabled',false);
    });
})

$(document).on('submit','.edit-inventory-form', async function (form) {
    form.preventDefault();
    let data = $(this).serializeArray();

    const {value: password} = await Swal.fire({
        title: 'Enter your password',
        input: 'password',
        inputLabel: 'Password',
        inputPlaceholder: 'Enter your password',
        inputAttributes: {
            maxlength: 10,
            autocapitalize: 'off',
            autocorrect: 'off'
        }
    })

    if (password) {
        $.post('/check-user-password',{password:password, '_token' : $('meta[name="csrf-token"]').attr('content')},function(result){
            console.log(result);

            if(result.success === true)
            {
                $.ajax({
                    url: '/inventories/' + editItemId,
                    method: 'PATCH',
                    data: data,
                    dataType: 'json',
                    beforeSend: function () {
                        inventoryForm.find('.modal-content').append(overlay)
                            .find('#inventory-modal-btn').attr('disabled', true).text('Saving...');
                    }
                }).done((inventoryResult) => {

                    if (inventoryResult.success === true) {
                        Toast.fire({
                            type: 'success',
                            title: inventoryResult.message
                        });
                        tableName.DataTable().ajax.reload(null, false);
                    }
                    else if(inventoryResult.success === false)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: inventoryResult.message
                        });
                    }
                }).fail((xhr, status, error) => {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        let element = $('.' + key);

                        element.find('.error-' + key).remove();
                        element.append('<p class="text-danger error-' + key + '">' + value + '</p>');
                        toastr.error(value)
                    });
                }).always(() => {
                    $('.overlay').remove();
                    inventoryForm.find('#inventory-modal-btn').attr('disabled', false).text('Save');
                });
                clear_errors('spa_id', 'name', 'quantity', 'unit', 'category');
            }
            else if(result.success === false)
            {
                Toast.fire({
                    type: 'warning',
                    title: result.message
                });
            }
        })
    }
});


$(document).on('click','.delete-inventory-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete New Masseur/Masseuse: "+data[1]+"?",
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                url : '/inventories/'+id,
                method : 'DELETE',
                headers: csrf_token,
            }).done(function(data){
                if(data.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: data.message
                    });
                    tableName.DataTable().ajax.reload(null, false);
                }else if(data.success === false)
                {
                    Toast.fire({
                        type: 'warning',
                        title: data.message
                    });
                }
            });
        }
    });
});

