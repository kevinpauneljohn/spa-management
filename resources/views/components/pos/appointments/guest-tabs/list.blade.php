<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    List of clients and transactions.
</div>
<div class="table-responsive">
    <table id="sales-data-lists" class="table table-striped" style="width: 100%;">
        <thead>
            <tr>
                <th>Client</th>
                <th>Service</th>
                <th>Masseur</th>
                <th>Start Time</th>
                <th>Plus Time</th>
                <th>End Time</th>
                <th>Room #</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
@section('css')

@endsection

@push('js')
    @if(auth()->check())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="{{asset('js/reusable.js')}}"></script>
        <script>
            $(document).ready(function(){
                $('#sales-data-lists').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/transaction-list/{{$spaId}}'
                    },
                    columns: [
                        { data: 'client', name: 'client', className: 'text-center'},
                        { data: 'service', name: 'service'},
                        { data: 'masseur', name: 'masseur'},
                        { data: 'start_time', name: 'start_time'},
                        { data: 'plus_time', name: 'plus_time', className: 'text-center'},
                        { data: 'end_time', name: 'end_time', className: 'text-center'},
                        { data: 'room', name: 'room', className: 'text-center'},
                        { data: 'amount', name: 'amount', className: 'text-center'},
                        { data: 'status', name: 'status', className: 'text-center'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    language: {
                        "processing": '<div class="dataTables_processing text-primary text-bold">Loading Guest Data...</div>'
                    },
                    "bDestroy": true,
                    responsive:true,
                    order:[8,'asc'],
                    pageLength: 10
                });

                $(document).on('click', '.edit-sales-btn', function () {
                    var id = this.id;
                    getSalesInfo(id);
                });

                function getSalesInfo(id)
                {
                    $.ajax({
                        'url' : '/transaction/'+id,
                        'type' : 'GET',
                        'data' : {},
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function () {

                        },
                        success: function(result){
                            $('#edit_first_name').val(result.data.transaction.client.firstname);
                            $('#edit_middle_name').val(result.data.transaction.client.middlename);
                            $('#edit_last_name').val(result.data.transaction.client.lastname);
                            $('#edit_date_of_birth').val(result.data.transaction.client.date_of_birth);
                            $('#edit_mobile_number').val(result.data.transaction.client.mobile_number);
                            $('#edit_email').val(result.data.transaction.client.email);
                            $('#edit_address').val(result.data.transaction.client.address);
                            $('#edit_client_type').val(result.data.transaction.client.client_type);
                            $('#edit_start_time').val(result.data.transaction.start_time);
                            $('#edit_masseur1_id').val(result.data.transaction.therapist_1);
                            $('#edit_masseur1_id_prev').val(result.data.transaction.therapist_1);

                            if (result.data.transaction.service.multiple_masseur) {
                                $('#edit_masseur2_id').val(result.data.transaction.therapist_2);
                                $('#edit_masseur2_id_prev').val(result.data.transaction.therapist_2);
                                $('#edit_masseur2_id_val').val(result.data.transaction.therapist_2_id);

                                if ($('.edit_masseur2_div').hasClass('hidden')) {
                                    $('.edit_masseur2_div').removeClass('hidden'); 
                                }

                                if ($('.edit_masseur1_div').hasClass('col-md-6')) {
                                    $('.edit_masseur1_div').removeClass('col-md-6');
                                    $('.edit_masseur1_div').addClass('col-md-4');
                                }

                                if ($('.edit_services_div').hasClass('col-md-6')) {
                                    $('.edit_services_div').removeClass('col-md-6');
                                    $('.edit_services_div').addClass('col-md-4');
                                }

                                if (result.data.therapist_2) {
                                    $('.select-edit-masseur2').html('');
                                    $('.select-edit-masseur2').append('<option></option>');
                                    $('.select-edit-masseur2').select2({
                                        placeholder: "Choose Here",
                                        allowClear: true
                                    });

                                    $.each(result.data.therapist_2 , function(index, therapist_2) { 
                                        if (therapist_2.therapist_id == result.data.transaction.therapist_2) {
                                            $('.select-edit-masseur2').append('<option value="'+therapist_2.therapist_id+'" selected>'+therapist_2.fullname+' Minutes</option>');
                                        } else {
                                            $('.select-edit-masseur2').append('<option value="'+therapist_2.therapist_id+'">'+therapist_2.fullname+'</option>');
                                        }
                                    });
                                }
                            } else {
                                $('#edit_masseur2_id').val('');
                                $('#edit_masseur2_id_val').val('');
                                $('.edit_masseur2_div').addClass('hidden');
                                
                                $('.edit_masseur1_div').removeClass('col-md-4');
                                $('.edit_masseur1_div').addClass('col-md-6');

                                $('.edit_services_div').removeClass('col-md-4');
                                $('.edit_services_div').addClass('col-md-6');
                            }

                            $('#multiple_masseur').val(result.data.transaction.service.multiple_masseur);
                            $('.totalAmountFormatted').html('&#8369; '+result.data.transaction.amount_formatted);
                            $('#totalAmountEditToPay').val(result.data.transaction.amount);
                            $('#totalAmountEditToPayOld').val(result.data.transaction.amount);
                            $('#edit_transaction_id').val(result.data.transaction.id);
                            $('#edit_client_id').val(result.data.transaction.client_id);
                            $('#edit_sales_id').val(result.data.transaction.sales_id);
                            
                            if (result.data.services) {
                                $('.select-edit-services').html('');
                                $('.select-edit-services').append('<option></option>');
                                $('.select-edit-services').select2({
                                    placeholder: "Choose Services",
                                    allowClear: true
                                });

                                $.each(result.data.services , function(index, services) { 
                                    if (services.id == result.data.transaction.service_id) {
                                        $('.select-edit-services').append('<option value="'+services.id+'" selected>'+services.name+'</option>');
                                    } else {
                                        $('.select-edit-services').append('<option value="'+services.id+'">'+services.name+'</option>');
                                    }
                                });
                            }

                            if (result.data.room)
                            {
                                $('#edit_room_val').val(result.data.transaction.room_id);
                                $('.select-edit-room').html('');
                                $('.select-edit-room').append('<option></option>');
                                $('.select-edit-room').select2({
                                    placeholder: "Choose Room",
                                    allowClear: true
                                });

                                $.each(result.data.room , function(index, rooms) { 
                                    if (rooms.room_id == result.data.transaction.room_id) {
                                        $('.select-edit-room').append('<option value="'+rooms.room_id+'" selected>Room # '+rooms.room_id+'</option>');
                                    } else {
                                        $('.select-edit-room').append('<option value="'+rooms.room_id+'">Room # '+rooms.room_id+'</option>');
                                    }
                                });
                            }
                            
                            if (result.data.plus_time)
                            {
                                $('.select-edit-plus_time').html('');
                                $('.select-edit-plus_time').append('<option></option>');
                                $('.select-edit-plus_time').select2({
                                    placeholder: "Choose Plus Time",
                                    allowClear: true
                                });

                                $.each(result.data.plus_time , function(index, plus) { 
                                    if (plus == result.data.transaction.plus_time) {
                                        $('.select-edit-plus_time').append('<option value="'+index+'" selected>'+plus+' Minutes</option>');
                                    } else {
                                        $('.select-edit-plus_time').append('<option value="'+index+'">'+plus+'</option>');
                                    }
                                });
                            }

                            if (result.data.therapist_1) {
                                $('.select-edit-masseur1').html('');
                                $('.select-edit-masseur1').append('<option></option>');
                                $('.select-edit-masseur1').select2({
                                    placeholder: "Choose Here",
                                    allowClear: true
                                });

                                $.each(result.data.therapist_1 , function(index, therapist_1) { 
                                    if (therapist_1.therapist_id == result.data.transaction.therapist_1) {
                                        $('.select-edit-masseur1').append('<option value="'+therapist_1.therapist_id+'" selected>'+therapist_1.fullname+' Minutes</option>');
                                    } else {
                                        $('.select-edit-masseur1').append('<option value="'+therapist_1.therapist_id+'">'+therapist_1.fullname+'</option>');
                                    }
                                });
                            }                            
                            // $.each(UnAvailableRoom, function (key, value) {
                            //     $('.select-edit-room').children('option[value="' + value + '"]').attr('disabled', true);
                            // });
                        }
                    });

                    $('#update-sales-modal').modal('show');
                }

                $(document).on('click', '.update-sales-btn', function () {
                    var transaction_id = $('#edit_transaction_id').val();
                    var client_id = $('#edit_client_id').val();
                    var sales_id = $('#edit_sales_id').val();
                    var mobile_number = $('#edit_mobile_number').val();
                    var email = $('#edit_email').val();
                    var address = $('#edit_address').val();
                    var client_type = $('#edit_client_type').val();
                    var amount = $('#totalAmountEditToPay').val();
                    var prevAmount = $('#totalAmountEditToPayOld').val();
                    var services = $('#edit_services').select2('data');
                    var value_services = services[0].id;
                    var value_services_name = services[0].text;
                    var multiple_masseur = $('#multiple_masseur').val();  
                    var plus_time = $('#edit_plus_time').select2('data');
                    var value_plus_time = plus_time[0].id;
                    var room_id = $('#edit_room').select2('data');
                    var value_room_id = room_id[0].id;
                    var masseur1_id = $('#edit_masseur1_id').val();

                    var masseur2_id = '';
                    if (multiple_masseur == 1) {
                        var masseur2_id = $('#edit_masseur2_id').val();

                        if (masseur2_id.length < 1) {
                            toastr.error('The masseur 2 field is required.');
                        }
                    }

                    var validateMobile = mobileValidation(mobile_number);

                    var date_sub = new Date();
                    var date_add = new Date();
                    var subDate = subtractHours(date_sub, 1);
                    var addDate = addHours(date_add, 1);

                    if (mobile_number.length < 1) {
                        toastr.error('The mobile number field is required.');
                    } else {
                        if (!validateMobile) {
                            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
                        }
                    }

                    if (value_services.length < 1) {
                        toastr.error('The services field is required.');
                    }

                    if (masseur1_id.length < 1) {
                        toastr.error('The masseur 1 field is required.');
                    }

                    if (value_room_id.length < 1) {
                        toastr.error('The room # field is required.');
                    }

                    var valid = false;
                    if (
                        mobile_number.length > 0 &&
                        value_services.length > 0 &&
                        masseur1_id.length > 0 &&
                        value_room_id.length > 0 && 
                        validateMobile
                    ) {
                        if (!validateMobile) {
                            toastr.error('The mobile number must be a number, have 10 characters, and not start with zero.');
                            valid = false;
                        } else if (multiple_masseur == 1) {
                            if (masseur2_id.length < 1) {
                                toastr.error('The masseur 2 field is required.');
                                valid = false;
                            } else {
                                valid = true;
                            }
                        } else {
                            valid = true;
                        }
                    }

                    if (valid) {
                        swal.fire({
                            title: "Are you sure you want to update the transaction?",
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
                                    'url' : '/transaction-update/'+transaction_id,
                                    'type' : 'PUT',
                                    'data': {
                                        id: transaction_id,
                                        client_id: client_id,
                                        sales_id: sales_id,
                                        mobile_number: mobile_number,
                                        email: email,
                                        address: address,
                                        client_type: client_type,
                                        amount: amount,
                                        prevAmount: prevAmount,
                                        service_id: value_services,
                                        service_name: value_services_name,
                                        therapist_1: masseur1_id,
                                        therapist_2: masseur2_id,
                                        plus_time: value_plus_time,
                                        room_id: value_room_id,
                                    },
                                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    beforeSend: function () {
                                        $('#sales-update-form').find('.update-sales-btn').attr('disabled',true);
                                        $('#sales-update-form').find('.text-update-btn').text('Saving ...');
                                        $('#sales-update-form').find('.spinner-update-btn').removeClass('hidden');
                                    },success: function (result) {
                                        setTimeout(function() { 
                                            if(result.status) {
                                                $('#sales-update-form').trigger('reset');

                                                // loadRoom();
                                                // loadSales(spa_id);
                                                // getTotalSales(spa_id);
                                                // getMasseurAvailability(spa_id);
                                                // loadData(spa_id);
                                                // getUpcomingGuest($('#spa_id_val').val());
                                                $('#sales-data-lists').DataTable().ajax.reload(null, false);
                                                swal.fire("Done!", result.message, "success");
                                                $('#update-sales-modal').modal('hide');
                                            } else {
                                                swal.fire("Warning!", result.message, "warning");
                                            }

                                            $('#sales-update-form').find('.update-sales-btn').attr('disabled',false);
                                            $('#sales-update-form').find('.text-update-btn').text('Save');
                                            $('#sales-update-form').find('.spinner-update-btn').addClass('hidden');
                                        }, 1000);
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
                    }
                });
            });
        </script>
    @endif
@endpush