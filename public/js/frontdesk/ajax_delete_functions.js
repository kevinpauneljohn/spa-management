function deleteAppointment(id)
{
    var spa_id = $('#spa_id_val').val();
    $tr = $(this).closest('tr');
    var name = $(this).data("name")
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete appointment of "+data[0]+"?",
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
                'url' : '/appointment-delete/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if(result.status) {
                        getAppointmentCount();
                        loadAppointments(spa_id);
                        getUpcomingGuest($('#spa_id_val').val());
        
                        swal.fire("Done!", result.message, "success");
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
}