let benefitsForm = $('#benefits-form');
$(document).on('submit','#benefits-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    console.log(data);

    $.ajax({
        url: '/benefits',
        method: 'POST',
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function(){
            benefitsForm.find('.save-benefits-button').attr('disabled',true).text('Saving...');
        }
    }).done(function(response){
        // console.log(response)
        if(response.success === true)
        {
            Toast.fire({
                type: 'success',
                title: response.message
            })
        }else{
            Toast.fire({
                type: 'warning',
                title: response.message
            })
        }
    }).fail(function(xhr, status, error){

    }).always(function(data){
        benefitsForm.find('.save-benefits-button').attr('disabled',false).text('Save');
    });
});
