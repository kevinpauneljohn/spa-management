let serviceTable = $('#service-list');
document.addEventListener('DOMContentLoaded', function () {
    window.addServiceStepper = new Stepper(document.querySelector('#bs-stepper-add'))
});

document.addEventListener('DOMContentLoaded', function () {
    window.editServiceStepper = new Stepper(document.querySelector('#bs-stepper-update'))
});
let container = $('#add-new-service-modal');

$(document).on('click','.add-service-btn',function(){
    var name = $('#name').val();
    var description = $('#description').val();
    var duration = $('#duration').val();
    var price = $('#price').val();
    var category = $('#category').val();
    var spa_id = $('.spa-id').val()
    var price_per_plus_time = $('#price_per_plus_time').val();

    var data = {
        name : name,
        description : description,
        duration : duration,
        price : price,
        category : category,
        spa_id : spa_id,
        price_per_plus_time: price_per_plus_time
    };

    swal.fire({
        title: "Are you sure you want to create Services?",
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
                'url' : '/service',
                'type' : 'POST',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#service-form').find('.add-service-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#service-form').trigger('reset');
                        $('#duration').val('').trigger('change');
                        serviceTable.DataTable().ajax.reload(null, false);

                        swal.fire("Done!", result.message, "success");
                        $('#add-new-service-modal').modal('toggle');
                        addServiceStepper.reset();

                        container.find('.info_next_btn').removeClass('hiddenBtn');
                        container.find('.closeModal').removeClass('hiddenBtn');
                        container.find('.price_previous_btn').addClass('hiddenBtn');
                        container.find('.price_submit_btn').addClass('hiddenBtn');
                        container.find('.price_submit_btn').prop('disabled', true);
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

                    $('#service-form').find('.add-service-btn').val('Save').attr('disabled',false);
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


$('#name, #description').on('#add-new-service-modal input',function(e){

    let name = container.find('#name').val();
    let description = container.find('#description').val();

    if (name.length > 0 && description.length > 0) {
        container.find('.info_next_btn').prop('disabled', false);
    } else {
        container.find('.info_next_btn').prop('disabled', true);
    }
});

$(document).on('click','#add-new-service-modal .info_next_btn',function(){
    container.find('.info_next_btn').addClass('hiddenBtn');
    container.find('.closeModal').addClass('hiddenBtn');
    container.find('.price_previous_btn').removeClass('hiddenBtn');
    container.find('.price_submit_btn').removeClass('hiddenBtn');
});

$('#add-new-service-modal #duration, #add-new-service-modal #price, #add-new-service-modal #category').on('#add-new-service-modal input',function(e){
    var duration = container.find('#duration').val();
    var price = container.find('#price').val();
    var category = container.find('#category').val();

    if (duration.length > 0 && price.length > 0 && category.length > 0) {
        container.find('.price_submit_btn').prop('disabled', false);
    } else {
        container.find('.price_submit_btn').prop('disabled', true);
    }
});

$(document).on('click','#add-new-service-modal .price_previous_btn',function(){
    container.find('.price_previous_btn').addClass('hiddenBtn');
    container.find('.price_submit_btn').addClass('hiddenBtn');
    container.find('.info_next_btn').removeClass('hiddenBtn');
    container.find('.closeModal').removeClass('hiddenBtn');
});

$('#add-new-service-modal').on('hidden.bs.modal', function () {
    addServiceStepper.to(0);
    container.find('.info_next_btn').removeClass('hiddenBtn');
    container.find('.closeModal').removeClass('hiddenBtn');
    container.find('.price_previous_btn').addClass('hiddenBtn');
    container.find('.price_submit_btn').addClass('hiddenBtn');
    container.find('.price_submit_btn').prop('disabled', true);
});

$(document).on('click','.edit-service-btn',function(){
    let id = this.id;
    $.ajax({
        'url' : '/service/'+id,
        'type' : 'GET',
        success: function(result){
            $('#edit_id').val(result.service.id);
            $('#edit_name').val(result.service.name);
            $('#edit_description').val(result.service.description);
            $('#edit_price').val(result.service.price);
            $('#edit_category').val(result.service.category);
            serviceTable.DataTable().ajax.reload(null, false);
            $('#edit_price_per_plus_time').val(result.service.price_per_plus_time);

            $.each(result.range , function(index, val) {
                if (result.service.duration == val) {
                    $('#edit_duration').append('<option value="'+index+'" selected="selected">'+val+'</option>');
                } else {
                    $('#edit_duration').append('<option value="'+index+'">'+val+'</option>');
                }
            });

            if (result.service.category === 'regular') {
                $('#edit_category').append('<option value="regular" selected="selected">Regular</option>');
                $('#edit_category').append('<option value="promo">Promo</option>');
            } else if (result.service.category === 'promo') {
                $('#edit_category').append('<option value="promo" selected="selected">Promo</option>');
                $('#edit_category').append('<option value="regular">Regular</option>');
            } else {
                $('#edit_category').append('<option value="" selected="selected">Select here</option>');
                $('#edit_category').append('<option value="regular">Regular</option>');
                $('#edit_category').append('<option value="promo">Promo</option>');
            }
        }
    });
});

$(document).on('click','.update-service-btn',function(){
    var id = $('#edit_id').val();
    var name = $('#edit_name').val();
    var description = $('#edit_description').val();
    var duration = $('#edit_duration').val();
    var price = $('#edit_price').val();
    var category = $('#edit_category').val();
    var price_per_plus_time = $('#edit_price_per_plus_time').val();

    var data = {
        id: id,
        name : name,
        description : description,
        duration : duration,
        price : price,
        category : category,
        price_per_plus_time: price_per_plus_time
    };

    swal.fire({
        title: "Are you sure you want to update Services?",
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
                'url' : '/service/'+id,
                'type' : 'PUT',
                'data': data,
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#update-service-form').find('.update-service-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        // $('#service-form').trigger('reset');
                        serviceTable.DataTable().ajax.reload(null, false);

                        swal.fire("Done!", result.message, "success");
                        $('#update-service-modal').modal('toggle');
                        editServiceStepper.reset();

                        $('.edit_info_next_btn').removeClass('hiddenBtn');
                        $('.edit_closeModal').removeClass('hiddenBtn');
                        $('.edit_price_previous_btn').addClass('hiddenBtn');
                        $('.edit_price_submit_btn').addClass('hiddenBtn');
                        $('.edit_price_submit_btn').prop('disabled', false);
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

                    $('#update-service-form').find('.update-service-btn').val('Save').attr('disabled',false);
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

$('#edit_name, #edit_description').on('input',function(e){
    var name = $('#edit_name').val();
    var description = $('#edit_description').val();

    if (name.length > 0 && description.length > 0) {
        $('.edit_info_next_btn').prop('disabled', false);
    } else {
        $('.edit_info_next_btn').prop('disabled', true);
    }
});

$(document).on('click','.edit_info_next_btn',function(){
    $('.edit_info_next_btn').addClass('hiddenBtn');
    $('.edit_closeModal').addClass('hiddenBtn');
    $('.edit_price_previous_btn').removeClass('hiddenBtn');
    $('.edit_price_submit_btn').removeClass('hiddenBtn');
});

$('#edit_duration, #edit_price, #edit_category').on('input',function(e){
    var duration = $('#edit_duration').val();
    var price = $('#edit_price').val();
    var category = $('#edit_category').val();

    if (duration.length > 0 && price.length > 0 && category.length > 0) {
        $('.edit_price_submit_btn').prop('disabled', false);
    } else {
        $('.edit_price_submit_btn').prop('disabled', true);
    }
});

$(document).on('click','.edit_price_previous_btn',function(){
    $('.edit_info_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_price_previous_btn').addClass('hiddenBtn');
    $('.edit_price_submit_btn').addClass('hiddenBtn');
});

$('#update-service-modal').on('hidden.bs.modal', function () {
    editServiceStepper.to(0);
    $('.edit_info_next_btn').removeClass('hiddenBtn');
    $('.edit_closeModal').removeClass('hiddenBtn');
    $('.edit_price_previous_btn').addClass('hiddenBtn');
    $('.edit_price_submit_btn').addClass('hiddenBtn');
    $('.edit_price_submit_btn').prop('disabled', false);
});

$(document).on('click','.delete-service-btn',function(){
    $tr = $(this).closest('tr');
    id = this.id;
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete Services: "+data[1]+"?",
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
                'url' : '/service/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#delete-service-form').find('.delete-service-modal-btn').val('Deleting ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        serviceTable.DataTable().ajax.reload(null, false);

                        swal.fire("Done!", result.message, "success");
                        $('#delete-service-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }

                    $('#delete-service-form').find('.delete-service-modal-btn').val('Delete').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});
