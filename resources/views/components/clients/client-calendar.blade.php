<div id='appointment-calendar'></div>

<x-clients.client-details-modal/>
<x-clients.book-client-modal :spaId="$spaId"/>

@section('plugins.Fullcalendar',true)
@section('plugins.CustomCSS',true)

@once
    @push('js')
        <script>
            $(document).ready(function(){

                bookingCalendar();

                $('.fc-bookAppointment-button').attr('data-bs-toggle','tooltip').attr('data-bs-placement','top').attr('title','Book Appointment')
                    .html('<span class="fa fa-calendar"></span>');

                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            const bookingCalendar = () => {
                let calendarEl = document.getElementById('appointment-calendar');
                let clientCalendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
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
                    events: '{!! route('appointment.events',['spa' => $spaId]) !!}',
                    dateClick: function (info){
                        console.log(info)
                        $('#book-client').modal('toggle');
                    },
                    eventClick: function(info){
                        $.ajax({
                            url: '/appointment-show/'+info.event.id,
                            type: 'GET',
                            beforeSend: function(){

                            }
                        }).done( (appointment) => {
                            console.log(appointment)
                            let fullName = appointment.firstname+' '+appointment.middlename+' '+appointment.lastname;
                            $('#client-info').find('.modal-title').text(fullName);
                            $('#client-info').find('#date').text(appointment.start_time_formatted);
                            $('#client-info').find('#appointment_type').text(appointment.appointment_type);
                            $('#client-info').find('#client_type').text(appointment.client_type);
                            $('#client-info').find('#mobile_number').html('<a href="tel:+63'+appointment.mobile_number+'">+63'+appointment.mobile_number+'</a>');
                            $('#client-info').find('#email').html('<a href="mailto:'+appointment.email+'">'+appointment.email != null ? appointment.email : ''+'</a>');
                        });

                        $('#client-info').modal('toggle');
                    }

                });
                clientCalendar.render();
            }
        </script>
    @endpush
@endonce
