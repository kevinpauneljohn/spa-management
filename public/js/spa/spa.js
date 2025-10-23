let tableName = $('#spa-list');
let formModal = $('.modal');
let spaForm = $('.spa-form');
let editSpaId;

$(document).on('submit','.spa-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();


    $.ajax({
        url: '/spa',
        type: 'POST',
        data: data,
        beforeSend: function(){
            spaForm.find('button[type="submit"]').attr('disabled',true).text('submitting...');
        },success: function(data, textStatus, xhr){

                Toast.fire({
                    type: 'success',
                    title: data
                });
            spaForm.find('button[type="submit"]').attr('disabled',false).text('submit');
            spaForm.trigger('reset');
            formModal.modal('toggle');
            tableName.DataTable().ajax.reload(null, false);

        },error: function(xhr, status, error){
            $.each(xhr.responseJSON.errors, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            spaForm.find('button[type="submit"]').attr('disabled',false).text('submit');
        }
    });
    clear_errors('name','address','number_of_rooms','category');
});

$(document).on('click','#add-spa-btn',function(){
    $('.edit-spa-form').removeClass().addClass('spa-form').find('.modal-title').text('Add New Spa')
    spaForm.trigger('reset');
    $('.text-danger').remove();
});

$(document).on('click','.edit-spa-btn',function (){
    let id = this.id;
    editSpaId = id;
    $('.text-danger').remove();
    spaForm.removeClass().addClass('edit-spa-form').find('.modal-title').text('Edit Spa');

    let editSpaForm = $('.edit-spa-form');
    let loadingInterval;
    formModal.modal('toggle')

    $.ajax({
        url: '/spa/'+id+'/edit',
        type: 'GET',
        dataType: 'json',
        beforeSend: function(){
            editSpaForm.find('input, textarea, button').attr('disabled',true);

            editSpaForm.find('.modal-title').text('Fetching Data')
            let counter = 0;
            let loading = '.';
            loadingInterval = setInterval(function(){

                console.log(loading);
                if(counter < 4){
                    counter++;
                    loading ='.';
                    editSpaForm.find('.modal-title').append(loading);
                }
                if(counter === 4){
                    counter = 0;
                    loading = '';
                    editSpaForm.find('.modal-title').text('Fetching Data');
                }
            },1000)
        },success: function(data){
            console.log(data)
            $.each(data,function (key, value){
                editSpaForm.find('#'+key).val(value).change();
            });
            clearInterval(loadingInterval);
            editSpaForm.find('.modal-title').text('Edit Spa');
            editSpaForm.find('input, textarea, button').attr('disabled',false);
        }
    });
});

$(document).on('submit','.edit-spa-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();
    let editSpaForm = $('.edit-spa-form');
    $.ajax({
        url: '/spa/'+editSpaId,
        type: 'PUT',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            editSpaForm.find('button[type="submit"]').attr('disabled',true).text('submitting...');
        },success: function(data, textStatus, xhr){
            if(data.status === false){
                Toast.fire({
                    type: 'warning',
                    title: data.message
                });
            }else{
                Toast.fire({
                    type: 'success',
                    title: data.message
                });
            }
            tableName.DataTable().ajax.reload(null, false);
            editSpaForm.find('button[type="submit"]').attr('disabled',false).text('submit');

        },error: function(xhr, status, error){
            console.log(xhr);
            $.each(xhr.responseJSON.errors, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            editSpaForm.find('button[type="submit"]').attr('disabled',false).text('submit');
        }
    });
    clear_errors('name','address','number_of_rooms');
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

                },success: function (data, textStatus, xhr) {
                    if(data.status) {
                        Toast.fire({
                            type: 'success',
                            title: data.message
                        });
                        tableName.DataTable().ajax.reload(null, false);
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: data.message
                        });
                    }

                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});
