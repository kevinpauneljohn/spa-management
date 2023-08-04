
<div class="modal fade" id="book-client">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h4 class="modal-title">Set New Appointment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group check">
                        <label for="clients">Check Existing Client</label><span class="required">*</span> <span class="text-muted font-italic">Type at least 3 characters to show result</span>
                        <form id="client-search-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" id="clients" />
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text" data-toggle="tooltip" data-placement="right" title="Click to search clients" id="clear-client-search"><i class="fa fa-search-plus"></i></button>
                                </div>
                            </div>
                        </form>
                        <ul class="list-group" id="client-lists"></ul>
                    </div>



                        <form id="appointment-client-form">
                            <input type="hidden" name="spa_id" value="{{$spa->id}}">
                            <fieldset class="border p-4">
                                <legend class="text-info" style="width: unset !important;">
                                    <span class="float-left">
                                        Personal Information
                                    </span>
                                    <span class="float-right ml-4 mt-2 mr-1">
                                        <button type="button" class="btn btn-default btn-xs" id="reset-personal-info-btn">Reset</button>
                                    </span>
                                </legend>

                            @csrf
                                <div class="row">
                                    <div class="col-lg-4 firstname">
                                        <label for="firstname">First Name</label>
                                        <input type="text" name="firstname" class="form-control" id="firstname">
                                    </div>
                                    <div class="col-lg-4 middlename">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" name="middlename" class="form-control" id="middlename">
                                    </div>
                                    <div class="col-lg-4 lastname">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-4 date_of_birth">
                                        <label for="date_of_birth">Date Of Birth</label><span class="required">*</span>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" />
                                    </div><div class="col-lg-4 mobile_number">
                                        <label for="mobile_number">Mobile Number</label>
                                        <input type="tel" name="mobile_number" class="form-control" id="mobile_number" />
                                    </div>
                                    <div class="col-lg-4 email">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" />
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 address">
                                        <label for="address">Address</label>
                                        <textarea name="address" class="form-control" id="address"></textarea>
                                    </div>
                                    <div class="col-lg-6 appointment_date">
                                        <label for="reservation_date">Appointment Date</label>
                                        <div class="input-group date" id="appointment_date" data-target-input="nearest">
                                            <input name="appointment_date" type="text" class="form-control datetimepicker-input" id="reservation_date"  data-target="#appointment_date"/>
                                            <div class="input-group-append" data-target="#appointment_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 address">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" class="form-control" id="remarks" style="min-height: 200px;"></textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <button type="submit" class="btn btn-success px-5 mt-3 w-100">Save</button>
                        </form>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
</div>
<!-- /.modal -->
@section('css')
    <style>
        #client-search-form{
            margin-bottom: 0;
        }
        .list-group-item{
            cursor: pointer;
        }

        @media screen and (max-width: 991.9px) {
            .second-row {
                margin-top: 30px;
            }
        }
    </style>
@endsection

@once
    @push('js')
        <script>
            let bookClient = $('#book-client');
            let appointmentClientForm = $('#appointment-client-form');
            let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

            $(document).ready(function(){
                $('#appointment_date').datetimepicker({ icons: { time: 'far fa-clock' } });
            });

            $(document).on('submit','#client-search-form', function(form){
                form.preventDefault();

                let search = $('#clients').val();
                bookClient.find('#client-lists .list-group-item').remove();
                if(search.length >= 3)
                {
                    $.ajax({
                        url: '/search/clients/{{$spa->id}}',
                        type: 'POST',
                        data: {'search' : search },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){
                            bookClient.find('#client-lists li').remove();
                            bookClient.find('#client-lists').html('<li class="list-group-item loading"><div class="text-center">' +
                                '<div class="spinner-grow text-success" role="status"></div>' +
                                '<div class="spinner-grow text-success ml-2" role="status"></div>' +
                                '<div class="spinner-grow text-success ml-2" role="status"></div>' +
                                '<div class="spinner-grow text-success ml-2" role="status"></div>' +
                                '</div></li>');
                            $('#clear-client-search').attr('disabled',true)
                        }
                    }).done(function(clients){
                        if(clients.length === 0)
                        {
                            bookClient.find('#client-lists').html('<li class="list-group-item text-secondary">No Existing Client (Create New)</li>');
                        }
                        $.each(clients, function(key, value){
                            bookClient.find('#client-lists').append('<a href="#" id="'+value.id+'" class="select-client" onclick=getClient("'+value.id+'","{{$spa->id}}")><li class="list-group-item" title="Click to populate form">'+value.firstname+' '+value.lastname+' - <span class="text-primary">'+value.mobile_number+'</span></li></a>')
                        });

                        bookClient.find('#client-lists').append('<li class="list-group-item text-secondary clear-lists text-danger">' +
                            '<i class="fa fa-trash mr-1" aria-hidden="true"></i>Clear list</li>');

                    }).always(function(){
                        bookClient.find('#client-lists .loading').remove();
                        $('#clear-client-search').attr('disabled',false)
                    });
                }else{
                    alert('Type at least 3 characters');
                }
            });

            const getClient = (clientId, spaId) =>
            {
                $.ajax({
                    url: '/get-spa/'+spaId+'/client/'+clientId,
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        appointmentClientForm.find('input[name=client_id]').remove();
                        bookClient.find('.modal-content').append(overlay);
                        appointmentClientForm.find('.text-danger').remove();
                        appointmentClientForm.find('.is-invalid').removeClass('is-invalid');
                    }
                }).done(function(client){

                    appointmentClientForm.prepend('<input type="hidden" name="client_id" value="'+client.id+'">');
                    appointmentClientForm.find('input[name=firstname]').val(client.firstname).attr('disabled',true)
                    appointmentClientForm.find('input[name=middlename]').val(client.middlename).attr('disabled',true)
                    appointmentClientForm.find('input[name=lastname]').val(client.lastname).attr('disabled',true)
                    appointmentClientForm.find('input[name=date_of_birth]').val(client.date_of_birth).attr('disabled',true)
                    appointmentClientForm.find('input[name=mobile_number]').val(client.mobile_number).attr('disabled',true)
                    appointmentClientForm.find('input[name=email]').val(client.email).attr('disabled',true)
                    appointmentClientForm.find('textarea[name=address]').val(client.address).attr('disabled',true)

                    bookClient.find('.clear-lists, #client-lists a').remove()
                    bookClient.find('#clients').val('')
                }).always(() => {
                    bookClient.find('.overlay').remove();
                });
                return false;
            }

            $(document).on('click','.clear-lists',function(){
                bookClient.find('#client-lists .list-group-item').remove();
            });

            $(document).on('submit','#appointment-client-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/appointments',
                    type: 'post',
                    data: data,
                    beforeSend: function(){
                        bookClient.find('.modal-content').append(overlay);
                        appointmentClientForm.find('.text-danger').remove();
                        appointmentClientForm.find('.is-invalid').removeClass('is-invalid');
                    }
                }).done((appointment) => {
                    console.log(appointment)
                    if(appointment.success === true)
                    {
                        $('#button-container').load('{{url()->current()}} #button-container');
                        $('#print-invoice-section').load('{{url()->current()}} #print-invoice-section');


                        // $('.display-sales-client').DataTable().ajax.reload(null, false);

                        appointmentClientForm.find('input[name=client_id]').remove();
                        appointmentClientForm.trigger('reset');
                        // bookClient.modal('toggle')

                        appointmentClientForm.find('input[name=firstname], ' +
                            'input[name=middlename], ' +
                            'input[name=lastname],' +
                            'input[name=date_of_birth],' +
                            'input[name=mobile_number],' +
                            'input[name=email],' +
                            'textarea[name=address]').attr('disabled',false)

                        Swal.fire(appointment.message, '', 'success')

                        bookingCalendar()
                    }
                    else
                    {
                        Swal.fire('An error occurred!', '', 'danger')
                    }
                }).fail((xhr, data, error) => {
                    let errors = xhr.responseJSON.errors;
                    // console.log(errors)

                    $.each(errors, function(key, value){

                        appointmentClientForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
                    });
                }).always((appointment) => {
                    bookClient.find('.overlay').remove();
                });
            });

            $(document).on('click','#reset-personal-info-btn',function(){
                Swal.fire({
                    title: 'Clear Form?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {

                    if (result.value === true) {

                        appointmentClientForm.find('#firstname, #middlename, #lastname, #date_of_birth, #mobile_number, #email, #address, #remarks, input[name=appointment_date]').val('').attr('disabled',false)
                        appointmentClientForm.find('input[name=client_id]').remove();
                        appointmentClientForm.find('.is-invalid').removeClass('is-invalid');
                        appointmentClientForm.find('.text-danger').remove();
                        Swal.fire('Form Cleared!', '', 'success')
                    }
                })
            });
        </script>
    @endpush
@endonce
