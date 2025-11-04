let serviceModal = $('#service-modal');
const uriSegment = window.location.pathname.split('/').filter(Boolean);

$(document).on('click','#add-service-category-btn',function(){
    serviceModal.find('.text-danger').remove();
    serviceModal.find('input[name=category]').val('');
    serviceModal.find('.is-invalid').removeClass('is-invalid');
    serviceModal.modal('show');
    serviceModal.find('.modal-title').text('Add Service Category');
    serviceModal.find('form').attr('id', 'add-service-category-form');
});

$(document).on('submit','#add-service-category-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray().concat({'name':'spa_id','value':uriSegment[1]});

    $.ajax({
        url: '/service-category',
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            serviceModal.find('input').attr('disabled',true);
            serviceModal.find('.save-category-btn').attr('disabled',true).text('Saving...');
        }
    }).done(function(response){
        console.log(response);

        if(response.success === true)
        {
            Toast.fire({
                type: 'success',
                title: response.message
            });
            $('#service-category-list').DataTable().ajax.reload(null, false);
        }
    }).fail(function(xhr, status, error) {
        $.each(xhr.responseJSON.errors, function(key, value) {
            serviceModal.find('#'+key).addClass('is-invalid');
            serviceModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
        })

    }).always(function(){
        serviceModal.find('input').attr('disabled',false);
        serviceModal.find('.save-category-btn').attr('disabled',false).text('Save');
    })
})

let service_category_id = '';
$(document).on('click','.edit-service-category-btn',function(){
    service_category_id = this.id;
    $tr = $(this).closest('tr');
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    serviceModal.find('.text-danger').remove();
    serviceModal.find('.is-invalid').removeClass('is-invalid');
    serviceModal.modal('show');
    serviceModal.find('.modal-title').text('Edit Service Category');
    serviceModal.find('form').attr('id', 'edit-service-category-form');
    serviceModal.find('input[name=category]').val(data[0]);
});

$(document).on('submit','#edit-service-category-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray().concat({'name':'spa_id','value':uriSegment[1]});

    $.ajax({
        url: '/service-category/'+service_category_id,
        type: 'PUT',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            serviceModal.find('input').attr('disabled',true);
            serviceModal.find('.save-category-btn').attr('disabled',true).text('Saving...');
        }
    }).done(function(response){
        if(response.success === true)
        {
            Toast.fire({
                type: 'success',
                title: response.message
            });
            $('#service-category-list').DataTable().ajax.reload(null, false);
        }else if(response.success === false){
            Toast.fire({
                type: 'warning',
                title: response.message
            });
        }
    }).fail(function(xhr, status, error) {
        $.each(xhr.responseJSON.errors, function(key, value) {
            serviceModal.find('#'+key).addClass('is-invalid');
            serviceModal.find('.'+key).append('<p class="text-danger">'+value+'</p>');
        })

    }).always(function(){
        serviceModal.find('input').attr('disabled',false);
        serviceModal.find('.save-category-btn').attr('disabled',false).text('Save');
    })
})

$(document).on('click','.delete-service-category-btn',function(){
    service_category_id = this.id;
    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: data[0],
        text: 'Delete Category?',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.value === true) {

            $.ajax({
                url: '/service-category/'+service_category_id,
                type: 'delete',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                }
            }).done(function(response){
                console.log(response);
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#service-category-list').DataTable().ajax.reload(null, false);
                }else if(response.success === false){
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    });
                    Swal.fire(response.message, '', 'warning')
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){

            });

        }
    })
});

