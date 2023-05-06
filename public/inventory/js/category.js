let tableName = $('#category-list');
let categoryModalBtn = $('#category-modal-btn');
let categoryForm = $('#category-form');
let categoryModal = $('#category-modal');
let overlay = '<div class="overlay dark"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
let categoryId;

categoryModalBtn.click(function(){
    categoryForm.removeClass().addClass('category-form');
    categoryForm.find('.text-danger').remove();
    categoryModal.find('.modal-title').text('Add New Category');
    categoryForm.trigger('reset');
});


$(document).on('submit','.category-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/inventory-categories',
        method: 'POST',
        data: data,
        beforeSend: function (){
            categoryForm.find('.modal-content').append(overlay)
                .find('#category-modal-btn').attr('disabled',true).text('Saving...');
        }
    }).done( (result) => {
        if(result.success === true)
        {
            categoryForm.trigger('reset');

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
        categoryForm.find('.overlay').remove();
        categoryForm.find('#category-modal-btn').attr('disabled',false).text('Save');
    });
    clear_errors('name');
});

$(document).on('click','.edit-category-btn',function(){
    categoryId = this.id;

    categoryForm.removeClass().addClass('edit-category-form').find('.text-danger').remove();
    categoryModal.find('.modal-title').text('Edit Category');
    categoryModal.modal('toggle');

    $.ajax({
        url: '/inventory-categories/'+categoryId+'/edit',
        method: 'GET',
        beforeSend: function(){
            categoryModal.find('.modal-content').append(overlay)
                .find('#category-modal-btn').attr('disabled',true);
        }
    }).done( (result) => {
        $.each(result,function (key, value){
            categoryForm.find('#'+key).val(value).change();
        })
    }).fail( (xhr, status, error) => {
        console.log(xhr);
    }).always( () => {
        categoryForm.find('.overlay').remove();
        categoryForm.find('#category-modal-btn').attr('disabled',false);
    });
})

$(document).on('submit','.edit-category-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/inventory-categories/'+categoryId,
        method: 'PATCH',
        data: data.concat({'name':'id','value' : categoryId}),
        dataType: 'json',
        beforeSend: function (){
            categoryForm.find('.modal-content').append(overlay)
                .find('#category-modal-btn').attr('disabled',true).text('Saving...');
        }
    }).done( (result) => {
        console.log(result)
        if(result.success === true)
        {
            Toast.fire({
                type: 'success',
                title: result.message
            });
            tableName.DataTable().ajax.reload(null, false);
        }
        else if(result.success === false)
        {
            Toast.fire({
                type: 'warning',
                title: result.message
            });
        }
    }).fail( (xhr, status, error) => {
        $.each(xhr.responseJSON.errors, function (key, value) {
            let element = $('.'+key);

            element.find('.error-'+key).remove();
            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            toastr.error(value)
        });
    }).always( () => {
        categoryForm.find('.overlay').remove();
        categoryForm.find('#category-modal-btn').attr('disabled',false).text('Save');
    });
    clear_errors('name')
});
