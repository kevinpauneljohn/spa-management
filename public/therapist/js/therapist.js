let tableName = $('#therapist-list');
let overlay = '<div class="overlay dark"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
let therapistForm = $('.therapist-form');
let therapistModal = $('#therapist-modal');
let therapistId = $('input[name="therapistId"]').val();
let formSubmitBtn = $('.add-therapist-btn')
let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
document.addEventListener('DOMContentLoaded', function () {
    window.addTherapistStepper = new Stepper(document.querySelector('#bs-stepper-add-therapist'))
});

$(document).on('change','#offer_type',function(){
    let offerType = $(this).val();

    $('.text-danger').remove();

    $('.commission_percentage, .commission_flat, .allowance').hide();
    if(offerType === 'percentage_only')
    {
        $('.commission_percentage').show();
    }
    else if(offerType === 'percentage_plus_allowance')
    {
        $('.commission_percentage, .allowance').show();
    }
    else if(offerType === 'amount_only')
    {
        $('.commission_flat').show();
    }
    else if(offerType === 'amount_plus_allowance')
    {
        $('.commission_flat,.allowance').show();
    }
});

$('#therapist-modal-btn').click(function(){
    therapistModal.find('form').removeClass().addClass('therapist-form');
    therapistModal.find('.modal-title').text('New Masseur/Masseuse Form')
});

$('#therapist-modal-btn').click(function(){
    addTherapistStepper.to(0);
    therapistForm.trigger('reset');
    $('#offer-part').find('.commission_percentage, .commission_flat, .allowance').hide();
});

$(document).on('submit','.therapist-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/therapists',
        type: 'POST',
        data: data,
        headers: csrf_token,
        beforeSend: function(){
            $(overlay).appendTo('#therapist-modal .modal-content')

        }
    }).done(function(data){
            if(data.success === true)
            {
                therapistForm.trigger('reset');

                Toast.fire({
                    type: 'success',
                    title: data.message
                });
                addTherapistStepper.to(0);
                $('#offer-part').find('.commission_percentage, .commission_flat, .allowance').hide();
                tableName.DataTable().ajax.reload(null, false);
            }
    }).fail(function(xhr){
            $.each(xhr.responseJSON.errors, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
                toastr.error(value)
            });
    }).always(function(){
        $('.overlay').remove();
    });
    clear_errors('firstname','lastname','gender','mobile_number','email','offer_type','commission_percentage','commission_flat','allowance');
});

$(document).on('click','.edit-therapist-btn',function(){
    let id = this.id;
    therapistId = id;
    therapistModal.find('form').removeClass().addClass('edit-therapist-form');

    therapistModal.find('.modal-title').text('Edit Masseur/Masseuse Information')

    therapistModal.modal('toggle');
    addTherapistStepper.to(0);

    $.ajax({
        url: '/therapists/'+id+'/edit',
        method: 'GET',
        beforeSend: function(){
            $(overlay).appendTo('#therapist-modal .modal-content');
        }
    }).done(function (data){
        $.each(data, function (key, value) {
            therapistForm.find('#'+key).val(value).change();
        });
    }).fail(function(xhr){
        $.each(xhr.responseJSON.errors, function (key, value) {
            let element = $('.'+key);

            element.find('.error-'+key).remove();
            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            toastr.error(value)
        });
    }).always(function(){
        $('.overlay').remove();
    });
});

$(document).on('submit','.edit-therapist-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/therapists/'+therapistId,
        method: 'PATCH',
        data: data.concat({
            'name' : 'therapistId',
            'value' : therapistId
        }),
        beforeSend:function(){
            $(overlay).appendTo('#therapist-modal .modal-content');
            $('.main-therapist-content .card-body').after(overlay);
            formSubmitBtn.attr('disabled',true).text('Saving...');
        }
    }).done(function(data){
        if(data.success === false)
        {
            Toast.fire({
                type: 'warning',
                title: data.message
            });
        }
        else if(data.success === true)
        {
            Toast.fire({
                type: 'success',
                title: data.message
            });
            tableName.DataTable().ajax.reload(null, false);
        }
    }).fail(function(xhr){
        $.each(xhr.responseJSON.errors, function (key, value) {
            let element = $('.'+key);

            element.find('.error-'+key).remove();
            element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            toastr.error(value)
        });
    }).always(function(){
        $('.overlay').remove();
        formSubmitBtn.attr('disabled',false).text('Save');
    });

    clear_errors('firstname','lastname','gender','mobile_number','email','offer_type','commission_percentage','commission_flat','allowance');
});


$(document).on('click','.delete-therapist-btn',function(){
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
                'url' : '/therapists/'+id,
                'type' : 'DELETE',
                'headers': csrf_token,
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
