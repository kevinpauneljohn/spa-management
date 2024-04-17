<div id='appointment-calendar'></div>

<x-clients.client-details-modal/>
<x-clients.book-client-modal :spaId="$spaId"/>

@section('plugins.Fullcalendar',true)
@section('plugins.CustomCSS',true)

@once
    @push('js')
        <script>
            let clientInfoModal = $('#client-info');
            $(document).ready(function(){

                bookingCalendar();

                $('.fc-bookAppointment-button').attr('data-bs-toggle','tooltip').attr('data-bs-placement','top').attr('title','Book Appointment')
                    .html('<span class="fa fa-calendar"></span>');

                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            const bookingCalendar = () => {
                let calendarEl = document.getElementById('appointment-calendar');
                let clientCalendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'listWeek',
                    headerToolbar: {
                        left  : 'prev,next today bookAppointment',
                        center: 'title',
                        right : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    },
                    displayEventTime: true,
                    slotEventOverlap: true,
                    dayMaxEventRows: true,
                    customButtons: {
                        bookAppointment: {
                            icon: "- glyphicon glyphicon-calendar",
                            // text: "Create",
                            click: function() {
                                $('#book-client').modal('toggle');
                            }
                        }
                    },
                    themeSystem: 'bootstrap',
                    editable: true,
                    selectable: true,
                    selectConstraint:{
                        start: '00:00',
                        end: '24:00'
                    },
                    select: function(info){
                        let selectedDate = info.startStr;

                        if(moment(selectedDate).isBefore(moment('{{now()}}')))
                        {
                        }
                    },
                    events: '{!! route('appointment.events',['spa' => $spaId]) !!}',
                    dateClick: function (info){
                        let selectedDate = info.dateStr;

                        if(moment(selectedDate).isBefore(moment('{{now()}}')))
                        {
                            Swal.fire('Warning!', 'The date selected cannot be processed', 'warning')
                        }else{
                            $('#book-client').find('input[name=appointment_date]').val(moment(info.date).format('MM/DD/YYYY hh:mm A'));
                            $('#book-client').modal('toggle');
                        }
                    },
                    eventClick: function(info){
                        // console.log(info.event.id)
                        $.ajax({
                            url: '/appointment-show/'+info.event.id,
                            type: 'GET',
                            beforeSend: function(){
                                $('#client-info').find('.modal-content').append(overlay);
                            }
                        }).done( (appointment) => {
                            console.log(appointment)
                            let fullName = appointment.firstname+' '+appointment.middlename+' '+appointment.lastname;
                            clientInfoModal.find('.modal-title').text(fullName);

                            let details = '<tr><td>Category</td><td class=text-info>Upcoming Appointment</td></tr>' +
                                '<tr><td>Appointment Date:</td><td>'+appointment.start_time_formatted+'</td></tr>' +
                                '<tr><td>Mobile Number:</td><td><a href="tel:+63'+appointment.mobile_number+'">+63'+appointment.mobile_number+'</a></td></tr>' +
                                '<tr><td>Email:</td><td><a href="mailto:'+appointment.email+'">'+(appointment.email != null ? appointment.email : '')+'</a></td></tr>' +
                                '<tr><td>Client Type:</td><td>'+appointment.client_type+'</td></tr>' +
                                '<tr><td>Remarks:</td><td>'+appointment.remarks+'</td></tr>';

                            clientInfoModal.find('#client-booking-info').html(details)
                                .append('<tr><td>' +
                                    '<button type="button" class="btn btn-primary btn-sm convert-booking-to-sales" id="'+appointment.id+'">Convert to sales</button>' +
                                    '</td><td><form id="reschedule-form">@csrf<div class="row"><div class="col-lg-9 col-md-6 mb-2"><input type="datetime-local" name="reschedule" class="form-control" id="'+appointment.id+'"/></div><div class="col-lg-3 col-md-6"><button type="submit" class="btn btn-primary">Reschedule</button></div></div></form></td></tr>');
                            return appointment;

                        }).fail( (xhr, data, error) => {

                            if(error === 'Not Found')
                            {
                                clientInfoModal.find('.modal-title').text('');
                                clientInfoModal.find('#client-booking-info tr').remove()
                                transactions(info.event.id)
                            }
                        }).always(() => {

                            clientInfoModal.find('.overlay').remove();
                        });

                        clientInfoModal.modal('toggle');
                    }

                });
                clientCalendar.render();
            }

            const transactions = (transactionId) => {
                $.ajax({
                    url: '/pos-transaction/'+transactionId,
                    beforeSend: function(){
                        $('#client-info').find('.modal-content').append(overlay);
                    }
                }).done((transaction) => {
                    // console.log(transaction)
                    clientInfoModal.find('.modal-title').text(transaction.client_name);

                        clientInfoModal.find('#client-booking-info').html('<tr><td>Category</td><td class="text-success">Completed Sales</td></tr>' +
                            '<tr><td>Invoice #</td><td><a href="/point-of-sale/add-transaction/'+transaction.spa_id+'/'+transaction.sales_id+'" style="cursor:pointer;">#'+(truncateString(transaction.sales_id,8))+'</a></td></tr>' +
                            '<tr><td>Start Date:</td><td>'+transaction.start_date+'</td></tr>' +
                            '<tr><td>End Date:</td><td>'+transaction.end_date+'</td></tr>' +
                            '<tr><td>Service:</td><td class="text-fuchsia">'+transaction.service.name+'</td></tr>' +
                            '<tr><td>Service Amount</td><td>'+parseFloat(transaction.service.price).toFixed(2)+'</td></tr>' +
                            '<tr><td>Plus Time</td><td>'+transaction.plus_time+' minutes</td></tr>' +
                            '<tr><td>Plus Time Amount</td><td>'+parseFloat(transaction.price_per_plus_time_total).toFixed(2)+'</td></tr>' +
                            '<tr><td>Total Amount</td><td>'+transaction.total_amount.toFixed(2)+'</td></tr>');
                }).always(() => {

                    clientInfoModal.find('.overlay').remove();
                });
            }

            $(document).on('submit','#reschedule-form',function(form){
                form.preventDefault();
                let dateValue = $('input[name="reschedule"]').val();
                let bookingId = $('input[name="reschedule"]').attr('id');

                swal.fire({
                    title: "Reschedule Appointment?",
                    html:
                        '<h4 class="text-fuchsia">'+moment( dateValue ).format( "dddd h:mm a DD MMM YYYY" )+'</h4>Click <b class="text-info">YES</b>, to confirm',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    reverseButtons: !0
                }).then(function (e) {
                    // console.log(e.dismiss)
                    if (e.value === true) {
                       rescheduleAppointment(bookingId, dateValue)

                    }
                    else if(e.dismiss === 'cancel') {

                    }

                })

                // console.log(dateValue+' '+bookingId)
            });

            const rescheduleAppointment = (bookingId, date) => {
                $.ajax({
                    url: '/appointments/'+bookingId,
                    type: 'patch',
                    data: {booking:bookingId, appointmentDate: date},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){
                        $('#reschedule-form').find('button').attr('disabled',true).text('Processing...')
                    }
                }).done((appointment) => {
                    console.log(appointment)

                    if(appointment.success === true)
                    {
                        Swal.fire('Hooray!', appointment.message, 'success')
                        bookingCalendar()
                        $('input[name=reschedule]').val('')
                    }else{
                        Swal.fire('Warning!', appointment.message, 'warning')
                    }
                }).fail((xhr, data, error) => {
                    if(error === 'Not Found')
                    {
                        Swal.fire('Warning!', 'Please select a date!', 'warning')
                    }
                }).always(() => {
                    $('#reschedule-form').find('button').attr('disabled',false).text('Reschedule')
                });
            }

            const truncateString = (string = '', maxLength = 50) =>
                string.length > maxLength
                    ? `${string.substring(0, maxLength)}`
                    : string

            $(document).on('click','.convert-booking-to-sales',function(){
                let bookingId = this.id;

                swal.fire({
                    title: "Convert Booking To Sales?",
                    html:
                        'Click <b class="text-info">YES</b>, to confirm',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '/point-of-sale',
                            type: 'post',
                            data: {
                                'spa_id' : '{{$spaId}}',
                                'amount_paid' : 0,
                                'payment_status' : 'pending',
                                'user_id' : '{{auth()->user()->id}}'
                            },
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        }).done((sales) => {
                            console.log(sales.sales.id)
                            if(sales.success === true)
                            {
                                Swal.fire('Hooray!', 'Sales Instance Successfully created!', 'success')
                                setTimeout(function(){
                                    window.location.replace('/point-of-sale/add-transaction/{{$spaId}}/'+sales.sales.id+'?booking='+bookingId);
                                },1000)

                            }

                        });

                    } else {
                        e.dismiss;
                    }

                })
            })
        </script>
    @endpush
@endonce
