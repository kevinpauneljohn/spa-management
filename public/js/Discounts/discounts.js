let codeModal = $('#code-modal');
let addDiscountModal = $('#add-discount');
let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

$(document).ready(function(){
    addDiscountModal.find('input[name=amount]').attr('disabled',true);
    $('.select2').select2({
        theme: 'bootstrap4'
    })
});

$(document).on('click','.view-bar-code',function(){
    let id = this.id;

    $.ajax({
        url: '/generate-bar-code/'+id,
        type: 'get',
        beforeSend: function(){
            codeModal.find('.text-code').remove();
            codeModal.find('.modal-content').append(overlay)
        }
    }).done(function(response){
        codeModal.find('#code-content').html(response.code).after(`<h2 class="text-center text-code mt-3">${response.discount.code}</h2>`)
    }).fail(function(xhr, status, error){
        console.log(xhr)
    }).always(function(){
        codeModal.find('.overlay').remove()
    })

    codeModal.modal('toggle')
})

$(document).on('change','#value_type',function(){
    let value = $(this).val();

    if(value === "")
    {
        addDiscountModal.find('input[name=amount]').attr('disabled',true);
    }
    else if(value === "amount")
    {
        addDiscountModal.find('input[name=amount]')
            .attr('disabled',false)
    }else{
        addDiscountModal.find('input[name=amount]')
            .attr('disabled',false)
    }
})

$(document).on('submit','#discount-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/discounts',
        type: 'post',
        data: data,
        beforeSend: function(){
            addDiscountModal.find('.is-invalid').removeClass('is-invalid')
            $('.text-danger').remove();

            addDiscountModal.find('button[type=submit]').attr('disabled',true).text('Saving...')
        }
    }).done(function(response){
        console.log(response)
        if(response.success === true)
        {
            Toast.fire({
                type: 'success',
                title: response.message
            });
            $('#discount-list').DataTable().ajax.reload(null, false);
            $('#discount-form').trigger('reset');
            addDiscountModal.find('#client').val('').change()
            addDiscountModal.modal('toggle')
        }else{
            Toast.fire({
                type: 'danger',
                title: response.message
            });
        }
    }).fail(function(xhr, status, error){
        console.log(xhr)
        $.each(xhr.responseJSON.errors, function(key, value){
            addDiscountModal.find('#'+key).addClass('is-invalid')
                .closest('.'+key).append('<p class="text-danger">'+value+'</p>');
        })
    }).always(function(){
        addDiscountModal.find('button[type=submit]').attr('disabled',false).text('Save')
    });
})

$(document).on('click','.delete-discount',function(){
    let id = this.id;
    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();


    Swal.fire({
        title: '#'+data[2],
        text: 'Delete Coupon/Voucher?',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.value === true) {

            $.ajax({
                url: '/delete-discount/'+id,
                type: 'delete',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    addDiscountModal.find('.modal-content').append(overlay);
                }
            }).done(function(response){
                console.log(response);
                if(response.success === true)
                {
                    Swal.fire(response.message, '', 'success')
                    $('#discount-list').DataTable().ajax.reload(null, false);
                }else{
                    Swal.fire(response.message, '', 'warning')
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                addDiscountModal.find('.overlay').remove();
            });

        }
    })
});

