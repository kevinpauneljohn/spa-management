let attendanceForm = $('#attendance-form');
let attendanceSavingForm = $('.attendance-saving-form');
let attendance_id;


$(document).on('click','.edit-attendance',function(){
    attendance_id = this.id;

    attendanceSavingForm.attr('id','attendance-form')
        .find('.modal-title').text('Update Attendance');
    attendanceSavingForm.find('.select-employee').hide();
    attendanceSavingForm.find('#biometrics_user').attr('disabled',true);

    $.ajax({
        url: `/attendances/${attendance_id}/edit`,
        type: 'GET',
        beforeSend: function () {
            attendanceForm.find('input[type=time], button[type=submit]').attr('disabled',true);
        }
    }).done(function(response){
        attendanceSavingForm.find('input[name="is_overtime_allowed"]').prop('checked',response.is_overtime_allowed);

        $.each(response, function(index, value){
            attendanceForm.find(`input[name=${index}]`).val(value);
        })
    }).fail(function(xhr, status, error){

    }).always(function(){
        attendanceForm.find('input[type=time], button[type=submit]').attr('disabled',false);
    });
})

$(document).on('submit','#attendance-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();
    $.ajax({
        url: `/attendances/${attendance_id}`,
        type: 'PATCH',
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            attendanceForm.find('input[type=time], button[type=submit]').attr('disabled',true);
            attendanceForm.find('button[type=submit]').text('Saving...')
        }
    }).done(function(response){
        if(response.success === true)
        {
            $('#attendance-list').DataTable().ajax.reload(null, false);
            Toast.fire({
                type: 'success',
                title: response.message
            })
        }
        else{
            Toast.fire({
                type: 'warning',
                title: response.message
            })
        }
    }).fail(function(xhr, status, error){
        console.log(xhr)
    }).always(function(){
        attendanceForm.find('input[type=time], button[type=submit]').attr('disabled',false);
        attendanceForm.find('button[type=submit]').text('Save')
    });
})



$(document).on('click','#add-attendance-btn', function(){
    attendance_id = "";

    $('#attendance-form').attr('id','add-new-attendance-form');
    attendanceSavingForm.find('.modal-title').text('Add New Attendance');
    attendanceSavingForm.find('#biometrics_user').attr('disabled',false);
    attendanceSavingForm.trigger('reset');
    attendanceSavingForm.find('.select-employee').show();
})

$(document).on('submit','#add-new-attendance-form', function(form){
    form.preventDefault();
    let data = $(this).serializeArray();
    console.log(data)
    $.ajax({
        url: `/add-new-employee-attendance`,
        method: 'POST',
        data: data,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            attendanceSavingForm.find('.is-invalid').removeClass('is-invalid');
            attendanceSavingForm.find('.text-danger').remove();
        }
    }).done(function(response){
        console.log(response)
        if(response.success === true)
        {
            $('#attendance-list').DataTable().ajax.reload(null, false);
            Toast.fire({
                type: 'success',
                title: response.message
            })
        }
        else{
            Toast.fire({
                type: 'warning',
                title: response.message
            })
        }

    }).fail(function(xhr, status, error){
        console.log(xhr)
        $.each(xhr.responseJSON.errors, function(key, value){
            attendanceSavingForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
        })
    }).always(function(){

    });
});


$(document).on('click','.delete-attendance', function(){

    let id = this.id;
    console.log(id);
    swal.fire({
        title: "Delete Attendance?",
        html:
            '<p>This will delete the created attendance</p>Click <b class="text-info">YES</b>, to confirm',
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                url: '/attendances/'+id,
                type: 'delete',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {
                if(response.success === true)
                {
                    $('#attendance-list').DataTable().ajax.reload(null, false);
                    Swal.fire(
                        response.message,
                        '',
                        'success'
                    )
                }
                else if(response.success === false){
                    Swal.fire(
                        response.message,
                        '',
                        'warning'
                    )
                }
            }).fail(function(xhr, data, status){

            });

        } else {
            e.dismiss;
        }

    }, function (dismiss) {
        return false;
    })
});
