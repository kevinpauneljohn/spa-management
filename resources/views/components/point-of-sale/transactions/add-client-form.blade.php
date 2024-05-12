<div class="modal fade" id="add-client-modal" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h4 class="modal-title">Add Transaction</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 bg-gray-light">
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
                            <form id="add-client-form" class="sales-client-form">
                                @csrf
                                <input type="hidden" name="sales_id" value="{{$sale->id}}">
                                <input type="hidden" name="spa_id" value="{{$spa->id}}">
                                <input type="hidden" name="service_id">
                                <input type="hidden" name="appointment_type" value="Walk-in">
                                <!--start personal info -->
                                <fieldset class="border p-4">
                                    <legend class="text-info" style="width: unset !important;">Personal Information</legend>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 firstname">
                                            <label for="firstname">First Name</label><span class="required">*</span>
                                            <input type="text" name="firstname" class="form-control" id="firstname" />
                                        </div><div class="col-lg-4 middlename">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" name="middlename" class="form-control" id="middlename" />
                                        </div>
                                        <div class="col-lg-4 lastname">
                                            <label for="lastname">Last Name</label><span class="required">*</span>
                                            <input type="text" name="lastname" class="form-control" id="lastname" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 date_of_birth">
                                            <label for="date_of_birth">Date Of Birth</label>
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
                                        <div class="col-lg-12 address">
                                            <label for="address">Address</label>
                                            <textarea name="address" class="form-control" id="address"></textarea>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- end personal info -->
                                <div class="row mt-4">
                                    <div class="col-lg-3 preparation_time">
                                        <label for="preparation_time">Prep Time</label>
                                        <select name="preparation_time" class="form-control" id="preparation_time">
                                            <option value=""> -- Select -- </option>
                                            @for($minutes = 0; $minutes <= 180; $minutes++)
                                                <option value="{{$minutes}}">{{$minutes}} @if($minutes === 1) min @else mins @endif</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-lg-9 service">
                                        <label for="service">Services</label>
                                        <select name="service" class="form-control select2" id="service" style="width: 100%;">
                                            <option value=""> -- Select -- </option>
                                            @foreach($spa->services as $service)
                                                <option value="{{$service->id}}" title="{{number_format($service->price,2)}}">{{$service->name}} (Price: {{number_format($service->price,2)}})</option>
                                            @endforeach
                                        </select>
                                    </div>
{{--                                    <div class="col-lg-4 plus_time">--}}
{{--                                        <label for="plus_time">Plus Time</label>--}}
{{--                                        <select name="plus_time" class="form-control" id="plus_time" disabled>--}}
{{--                                            <option value="0"> 0 </option>--}}
{{--                                            <option value="3"> 3 mins </option>--}}
{{--                                            @for($minutes = 15; $minutes <= 120; $minutes = $minutes + 5)--}}
{{--                                                <option value="{{$minutes}}">{{$minutes}} @if($minutes === 1) min @else mins @endif</option>--}}
{{--                                            @endfor--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-4 room">
                                        <label for="room">Room</label>
                                        <select name="room" class="form-control" id="room">
                                            <option value=""> -- Select -- </option>
                                            @for($room = 1; $room <= $spa->number_of_rooms; $room++)
                                                <option value="{{$room}}">#{{$room}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-lg-4 therapist_1">
                                        <label for="therapist_1">Therapist #1</label>
                                        <select name="therapist_1" class="form-control" id="therapist_1">
                                            <option value=""> -- Select -- </option>
                                            @foreach($spa->therapists()->where('is_excluded',false)->get() as $therapist)
                                                <option value="{{$therapist->id}}"> {{$therapist->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 therapist_2">
                                        <label for="therapist_2">Therapist #2</label>
                                        <select name="therapist_2" class="form-control" id="therapist_2">
                                            <option value=""> -- Select -- </option>
                                            @foreach($spa->therapists()->where('is_excluded',false)->get() as $therapist)
                                                <option value="{{$therapist->id}}"> {{$therapist->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <div class="btn-group float-left">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-modal-btn" data-toggle="tooltip" data-placement="left" title="Close modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-default" id="reset-personal-info-btn"
                                                data-toggle="tooltip" data-placement="top" title="Clear Personal Info"><i class="fa fa-user-times" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-default" id="sales-transaction-reset-btn"
                                                data-toggle="tooltip" data-placement="right" title="Clear Whole Form"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                    <span class="float-right">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                            <div class="col-lg-7 second-row table-responsive">
                                <x-sales-transaction-table :spaId="$spa->id" :saleId="$sale->id" :displayAllColumns="false" tableId="display-sales-client-1"/>
                            </div>
                        </div>
                    </div>

                </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@section('plugins.Select2',true)
@section('css')
<style>
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
            let addClientForm = $('.sales-client-form');
            let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
            let addClientModal = $('#add-client-modal');
            // let therapists = [];
            let multipleMasseur = false;

            $('.select2').select2()

            $(document).ready(function(){
                $('#clear-client-search, #appointment-info, #sales-transaction-reset-btn, #reset-personal-info-btn, #close-modal-btn').tooltip();
                $('#therapist_1, #therapist_2').attr('disabled',true);
                // addClientForm.find('#therapist_1').html('').attr('disabled',true).append('<option value="">--Select--</option>')
                services({disabled: true});
                room({disabled: true});
                // roomAvailability();
            });

            $(document).on('submit','#client-search-form', function(form){
                form.preventDefault();

                let search = $('#clients').val();
                addClientModal.find('#client-lists .list-group-item').remove();
                if(search.length >= 3)
                {
                    $.ajax({
                        url: '/search/clients/{{$spa->id}}',
                        type: 'POST',
                        data: {'search' : search },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){
                            addClientModal.find('#client-lists li').remove();
                            addClientModal.find('#client-lists').html('<li class="list-group-item loading"><div class="text-center">' +
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
                            addClientModal.find('#client-lists').html('<li class="list-group-item text-secondary">No Existing Client (Create New)</li>');
                        }
                        $.each(clients, function(key, value){
                            addClientModal.find('#client-lists').append('<a href="#" id="'+value.id+'" class="select-client" onclick=getClient("'+value.id+'","{{$spa->id}}")><li class="list-group-item" title="Click to populate form">'+value.firstname+' '+value.lastname+' - <span class="text-primary">'+value.mobile_number+'</span></li></a>')
                        });
                    }).always(function(){
                        addClientModal.find('#client-lists .loading').remove();
                        $('#clear-client-search').attr('disabled',false)
                    });
                }else{
                    alert('Type at least 3 characters');
                }
            });


            function getClient(clientId, spaId)
            {
                $.ajax({
                    url: '/get-spa/'+spaId+'/client/'+clientId,
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        addClientForm.find('input[name=client_id]').remove();
                        addClientModal.find('.modal-content').append(overlay);
                        addClientForm.find('.text-danger').remove();
                        addClientForm.find('.is-invalid').removeClass('is-invalid');
                    }
                }).done(function(client){
                    addClientForm.prepend('<input type="hidden" name="client_id" value="'+client.id+'">');
                    addClientForm.find('input[name=firstname]').val(client.firstname).attr('disabled',true)
                    addClientForm.find('input[name=middlename]').val(client.middlename).attr('disabled',true)
                    addClientForm.find('input[name=lastname]').val(client.lastname).attr('disabled',true)
                    addClientForm.find('input[name=date_of_birth]').val(client.date_of_birth).attr('disabled',true)
                    addClientForm.find('input[name=mobile_number]').val(client.mobile_number).attr('disabled',true)
                    addClientForm.find('input[name=email]').val(client.email).attr('disabled',true)
                    addClientForm.find('textarea[name=address]').val(client.address).attr('disabled',true)

                    addClientModal.find('#client-lists a').remove()
                    addClientModal.find('#clients').val('')
                }).always(() => {
                    addClientModal.find('.overlay').remove();
                });
                return false;
            }

            $(document).on('submit','#add-client-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                addClientToTransaction(data);
            })

            function addClientToTransaction(data)
            {
                $.ajax({
                    url: '/pos-transaction',
                    type: 'POST',
                    data: data,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        addClientModal.find('.modal-content').append(overlay);
                        addClientForm.find('.text-danger').remove();
                        addClientForm.find('.is-invalid').removeClass('is-invalid');
                    }
                }).done(function(transaction){
                    if(transaction.success === true)
                    {
                        $('#button-container').load('{{url()->current()}} #button-container');
                        $('#print-invoice-section').load('{{url()->current()}} #print-invoice-section');

                        let salesTransaction = transaction.transaction;

                        $('.display-sales-client').DataTable().ajax.reload(null, false);

                        addClientForm.find('input[name=client_id]').remove();
                        addClientForm.find('select[name=service]').val('').change();
                        addClientForm.trigger('reset');
                        $('#therapist_2').attr('disabled',true);

                        addClientForm.find('input[name=firstname], ' +
                            'input[name=middlename], ' +
                            'input[name=lastname],' +
                            'input[name=date_of_birth],' +
                            'input[name=mobile_number],' +
                            'input[name=email],' +
                            'textarea[name=address]').attr('disabled',false)

                        addClientForm.find('#service, #room, #therapist_1, #therapist_2').attr('disabled',true)


                        // masseurAvailability(salesTransaction.spa_id, 'therapist_1')
                        // roomAvailability()

                        @if(isset($_GET['booking']))
                        @php
                            $bookingId = $_GET['booking'];
                                $appointment = \App\Models\Appointment::findOrFail($bookingId);
                        @endphp
                        $('#client-search-form').find('input, button').attr('disabled',false);
                        addClientModal.find('#close-modal-btn, #reset-personal-info-btn, #sales-transaction-reset-btn, .close').attr('disabled',false);
                        history.pushState({}, null, '/point-of-sale/add-transaction/{{$spa->id}}/{{$sale->id}}');

                        removeBooking('{{$bookingId}}')
                        @endif
                    }
                    else if(transaction.success === false)
                    {
                        if(confirm('Client already exists in the sales transaction. Do you want to proceed?'))
                        {
                            addClientToTransaction(data.concat({name: 'confirm', value:true}))
                        }else{
                            alert('cancelled')
                        }
                    }
                }).fail(function(xhr){
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value){
                        addClientForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
                    })
                }).always(function(){
                    addClientModal.find('.overlay').remove();
                });
            }

            $(document).on('change','#preparation_time', function(){
                let prepTimeValue = $(this).val();

                if(prepTimeValue !== "")
                {
                    services({disabled: false});
                }else {
                    services({disabled: true});
                }
            });

            $(document).on('change','#service', function(){
                let value = $(this).val()
                addClientForm.find('input[name=service_id]').val('')
                if(value.length > 0)
                {
                    room({disabled: false});
                    $('#therapist_1').attr('disabled',false);
                    {{--masseurAvailability('{{$spa->id}}', 'therapist_1');--}}
                    $.ajax({
                        {{--url: '/spa/{{$spa->id}}/retrieve-by-name/'+value,--}}
                        url: '/spa/{{$spa->id}}/retrieve-by-id/'+value,
                        async: false,
                        beforeSend: function(){
                        }
                    }).done(function(service){
                        addClientForm.find('input[name=service_id]').val(service.id)
                        if(service.multiple_masseur === 1 )
                        {
                            multipleMasseur = true;
                            $('#therapist_2').val('').attr('disabled',false);
                        }
                        else{
                            multipleMasseur = false;
                            $('#therapist_2').val('').attr('disabled',true);
                        }
                    });
                }else{
                    // $('#therapist_2').attr('disabled',true);
                    room({disabled: true});
                    // therapists = [];
                    // $('#therapist_1, #therapist_2').html('').attr('disabled',true).append('<option value="">--Select--</option>');
                    $('#therapist_1, #therapist_2').val('').attr('disabled',true);
                }
            })

            function services({disabled = true})
            {
                if(disabled === true)
                {
                    addClientForm.find('#service').val('').change();
                }
                addClientForm.find('#service').attr('disabled', disabled);
            }

            $(document).on('click','#sales-transaction-reset-btn',function(){
                Swal.fire({
                    title: 'Do you want to reset the form?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.value === true) {
                        addClientForm.find('#firstname, #middlename, #lastname, #date_of_birth, #mobile_number, #email, #address').attr('disabled',false)
                        addClientForm.find('input[name=client_id]').remove();
                        addClientForm.find('input[name=service_id]').val('');
                        addClientForm.find('.is-invalid').removeClass('is-invalid');
                        addClientForm.find('.text-danger').remove();
                        clientModal.find('.modal-title').text('Add Transaction');
                        $('#therapist_1, #therapist_2').attr('disabled',true);
                        addClientForm.trigger('reset');
                        services({disabled: true});
                        room({disabled: true});

                        Swal.fire('Form Cleared!', '', 'success')
                    }
                })
            });


            $(document).on('click','#reset-personal-info-btn',function(){
                Swal.fire({
                    title: 'Clear Personal Info Only?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {

                    if (result.value === true) {

                        addClientForm.find('#firstname, #middlename, #lastname, #date_of_birth, #mobile_number, #email, #address').val('').attr('disabled',false)
                        addClientForm.find('input[name=client_id]').remove();
                        addClientForm.find('.is-invalid').removeClass('is-invalid');
                        addClientForm.find('.text-danger').remove();
                        clientModal.find('.modal-title').text('Add Transaction');
                        Swal.fire('Form Cleared!', '', 'success')
                    }
                })
            });

            // function masseurAvailability(spaId, therapist)
            // {
            //     addClientForm.find('#'+therapist).html('');
            //     addClientForm.find('#'+therapist).append('<option value=""> --Select-- </option>').val('').change();
            //
            //     $.get('/masseur-availability/'+spaId, function (masseurs){
            //         therapists = masseurs;
            //         $.each(masseurs, function (key, value){
            //             addClientForm.find('#'+therapist).append('<option value="'+value.id+'">'+value.user.firstname+' '+value.user.lastname+'</option>');
            //         })
            //     },'json')
            // }

            // $('.sales-client-form #therapist_1').on('change', function(){
            //     let selectedValue = $(this).val();
            //
            //     console.log(therapists)
            //
            //     addClientForm.find('#therapist_2').html('').append('<option value=""> --Select-- </option>').val('');
            //     if(multipleMasseur === true)
            //     {
            //         $.each(therapists, function (key, value){
            //             if(selectedValue !== value.id)
            //             {
            //                 addClientForm.find('#therapist_2').append('<option value="'+value.id+'">'+value.user.firstname+' '+value.user.lastname+'</option>');
            //             }
            //         })
            //     }else{
            //     }
            // });


            const room = ({disabled: disabled = true}) => {
                if(disabled === true)
                {
                    addClientForm.find('#room').val('').change();
                }
                addClientForm.find('#room').attr('disabled', disabled);
            };

            function roomAvailability()
            {
                $.get('/room-availability/{{$spa->id}}', function(key, value){
                    addClientForm.find('#room').html('').append('<option value="">--Select--</option>');
                    $.each(key, function(roomKey, roomValue){
                        addClientForm.find('#room').append('<option value="'+roomValue+'">'+roomValue+'</option>');
                    })
                },'json')
            }

            function voidTransaction(spaId, transactionId)
            {
                $.ajax({
                    url: '/void-transaction/'+spaId+'/'+transactionId,
                    type: 'patch',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'test':'data'},
                    dataType: 'json',
                    beforeSend: function(){

                    }
                }).done(function(transaction){
                    console.log(transaction);
                });
            }

            $(document).on('click','.void-transaction', function(){
                let id = this.id;

                Swal.fire({
                    title: 'Void Transaction',
                    html:
                        'Type your <b>Reason</b>',
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: (reason) => {
                        return $.ajax({
                            url: '/void-transaction/'+id,
                            type: 'patch',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {'reason':reason},
                            beforeSend: function(){

                            }
                        }).then(response => {
                            $('.display-sales-client').DataTable().ajax.reload(null, false);
                            addClientForm.find('#preparation_time').val('').change();
                            return response;
                        }).catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error.responseJSON.message}`
                                )
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value.success === true) {
                        $('#button-container').load('{{url()->current()}} #button-container');
                        $('#print-invoice-section').load('{{url()->current()}} #print-invoice-section');
                        Swal.fire(
                            result.value.message,
                            '',
                            'success'
                        )
                    }
                })
            });

            @if(isset($_GET['booking']))
                @php
                $bookingId = $_GET['booking'];
                    $appointment = \App\Models\Appointment::findOrFail($bookingId);
                @endphp
                addClientModal.modal('toggle')
                getClient('{{$appointment->client_id}}','{{$appointment->spa_id}}');
                $('#client-search-form').find('input, button').attr('disabled',true);
                addClientModal.find('#close-modal-btn, #reset-personal-info-btn, #sales-transaction-reset-btn, .close').attr('disabled',true);

                const removeBooking = (bookingId) => {
                    $.ajax({
                        url: '/appointments/'+bookingId,
                        type: 'delete',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    }).done((booking) => {
                        console.log(booking)
                    });

                }
            @endif
        </script>
    @endpush
@endonce
