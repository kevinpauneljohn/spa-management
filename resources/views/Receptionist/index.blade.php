
@extends('adminlte::page')

@section('title', 'Receptionist')

@section('content_header')
    <h1></h1>
@stop

@section('content')
@section('css')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
<style>
    .hidden {
        display: none;
    }
    .pointer {cursor: pointer;}
    .error-border {
        border: 2px solid red;
    }
    .isRequired {
        color: red;
    }
    .select2-results {
        color: #000 !important;
    }
    .select2-container--default .select2-selection--single {
        height: 40px;
    }

    .modal-body{
        max-height: calc(400vh - 200px) !important;
        overflow-y: auto;
    }
    .progress span {
        position: absolute;
        text-align:center;
        display: block;
        width: 100%;
        font-weight: 600;
        margin-top: 8px;
    }
    .closeTabs {
        float: right;
        font-size: .9rem;
        font-weight: 700;
        line-height: 1;
        color: red;
        text-shadow: 0 1px 0 #fff;
        opacity: .3;
        margin-top: -40px;
        margin-right: 1px;
        border-radius: 75px;
    }
    .modal-body{
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .popup-btn{
        top:180px;
        position:fixed;
        right:-50px;
        z-index: 1000;
        transform: rotate(90deg);
        background-color: red;
        padding:10px 35px 35px;
        height: 0px;
        background-color: #17a2b8;
        color:#fff;
    }
    .popup-btn a:hover{
        text-decoration: none;
    }
</style>
@stop
    @if(auth()->user()->hasRole('front desk') || auth()->user()->can('add sales'))
        <div class="popup-btn">
            <x-pos.front-desk-shifts.end_shift spaId="{{$spa_id}}" />
        </div>
    @endif
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan">{{ucwords($title)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('spa.show',['spa' => $spa_id])}}">Spa</a> </li>
                <li class="breadcrumb-item active">{{ucwords($title)}} </li>
            </ol>
        </div>
    </div>
    <x-pos.appointments.board spaId="{{$spa_id}}" />
    <div class="container-fluid">

        <div class="row">
            <section class="col-lg-6">
                <x-pos.availability.therapist spaId="{{$spa_id}}" />
            </section>
            <section class="col-lg-6">
                <x-pos.appointments.upcoming_appointment spaId="{{$spa_id}}" />
            </section>
        </div>
        <div class="row">
            <section class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <x-pos.appointments.book-appointment.create spaId="{{$spa_id}}" />
                        </h3>

                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link roomView active" href="#room-availability" data-toggle="tab">Rooms</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link guestView" href="#guests-data" data-toggle="tab">
                                        Guest
                                        <span class="badge badge-danger text-default viewBadgeCount"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link transactionView" href="#transactions-data" data-toggle="tab">
                                        Transactions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link appointmentView" href="#appointment-data" data-toggle="tab">
                                        Upcoming
                                        <span class="badge badge-danger text-default countSelectedAppoitment"></span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link appointmentView" href="#calendar-data" data-toggle="tab">
                                        Calendar
                                        <!-- <span class="badge badge-danger text-default countSelectedAppoitment"></span> -->
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content p-0">
                            <div class="tab-pane active" id="room-availability" style="position: relative;">
                                <input type="hidden" class="form-control" id="spa_id_val" value="{{$spa_id}}">
                                <input type="hidden" class="form-control" id="room_ids_val">
                                <input type="hidden" class="form-control" id="isValid">
                                <input type="hidden" class="form-control" id="numberOfRooms" value="{{$total_rooms}}">
                                <input type="hidden" class="form-control" id="start_shit_id">
                                <input type="hidden" class="form-control" id="owner_id_val" value="{{$owner_id}}">
                                <x-pos.availability.room spaId="{{$spa_id}}" />
                            </div>
                            <div class="tab-pane" id="guests-data" style="position: relative; height: auto;">
                                <x-pos.appointments.guest-tabs.table-list spaId="{{$spa_id}}" />
                            </div>
                            <div class="tab-pane" id="transactions-data" style="position: relative; height: auto;">
                                <x-pos.appointments.transactions-tab.table-list spaId="{{$spa_id}}" />
                            </div>
                            <div class="tab-pane" id="appointment-data" style="position: relative;height: auto;">
                                <x-pos.appointments.upcoming-tab.table-list spaId="{{$spa_id}}" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @if(auth()->user()->hasRole('front desk') || auth()->user()->can('add sales'))
        <!-- <div class="modal fade" id="start-shift-modal"  data-keyboard="false" data-backdrop="static">
            <form role="form" id="start-shift-form" class="form-submit modal-dialog-centered">
                @csrf
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Good Day {{ucfirst(auth()->user()->firstname)}}!</h4>
                        </div>
                        <div class="modal-body">
                            <h5 class="text-center shiftMessage"></h5>
                            <span class="badge badge-info text-default pointer viewEndShiftReport">View Report</span>
                        </div>
                        <div class="modal-footer">
                            <button id="btnStartShift" class="btn btn-primary btnStartShift mx-auto">Click here to start your shift</button>
                            <button id="btnEndShift" class="btn btn-info btnEndShift mx-auto hidden">Click here to end your shift</button>
                        </div>
                    </div>
                </div>
            </form>
        </div> -->

        <div class="modal fade" id="money-on-hand-modal"  data-keyboard="false" data-backdrop="static">
            <form role="form" id="money-on-hand-form" class="form-submit modal-dialog-centered">
                @csrf
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Good Day {{ucfirst(auth()->user()->firstname)}}!</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="money_on_hand">Money on Hand</label>
                                <input type="number" class="form-control" id="money_on_hand" name="money_on_hand" placeholder="Enter money on hand" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btnMoneyOnHand" class="btn btn-primary btnMoneyOnHand mx-auto">Click here to confirm</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="view-shift-report"  data-keyboard="false" data-backdrop="static">
            <form role="form" id="view-shift-report-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">{{ucfirst(auth()->user()->firstname)}} End of Shift Report</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="invoice p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> <span>{{$title}}</span>
                                        <small class="float-right">Date: {{date('F d, Y')}}</small>
                                    </h4>
                                    </div>
                                </div>
                                <div class="row invoice-info">
                                    <div class="col-sm-12 invoice-col">
                                        Reported By: <span class="reporter">{{ucfirst(auth()->user()->fullname)}}</span><br>
                                        Reported Date: (From) <span class="shift_date_start"></span> (To) <span class="shift_date_end"></span><br>
                                        Shift Time: <span class="shift_start"></span> - <span class="shift_end"></span><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped table-hover responsive" id="endShiftReport" styke="width:100% !important;">
                                            <thead>
                                                <tr>
                                                    <th>Invoice #</th>
                                                    <th>Payment Method</th>
                                                    <th>Reference #</th>
                                                    <th>Payment Date</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <div class="table-responsive">
                                            <table id="summaryTotalEndReport" class="table">

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row no-print">
                                    <div class="col-12">
                                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                                        Payment
                                    </button>
                                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                        <i class="fas fa-download"></i> Generate PDF
                                    </button>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('add sales'))

    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('edit sales'))
        <x-pos.appointments.guest-tabs.edit id="{{$spa_id}}" />
        <x-pos.appointments.upcoming-tab.edit id="{{$spa_id}}" />
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('view sales'))
        <x-pos.appointments.upcoming-tab.view id="{{$spa_id}}" />
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('move sales'))
        <x-pos.appointments.upcoming-tab.move id="{{$spa_id}}" />
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('view invoices'))
        <x-pos.appointments.transactions-tab.invoice id="{{$spa_id}}" />

        <div class="modal fade" id="update-invoice-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <form role="form" id="invoice-update-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title updateInvoiceTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label><span class="isRequired">*</span>
                                <select class="form-control" name="payment_method" id="payment_method">
                                    <option value="" selected disabled>-- Choose Here --</option>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">Gcash</option>
                                    <option value="paymaya">Paymaya</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                                <input type="hidden" class="form-control" id="sales_invoice_id">
                                <input type="hidden" class="form-control" id="sales_batch_id">
                            </div>
                            <div class="form-group account_number_div hidden">
                                <label for="payment_account_number">Reference Number</label><span class="isRequired">*</span>
                                <input type="text" class="form-control" name="payment_account_number" id="payment_account_number">
                            </div>
                            <div class="form-group payment_bank_name hidden">
                                <label for="payment_bank_name">Bank Name</label><span class="isBankName isRequired">*</span>
                                <input type="text" class="form-control" name="payment_bank_name" id="payment_bank_name">
                            </div>
                            <div class="form-group">
                                <label for="payment_status">Payment Status</label><span class="isRequired">*</span>
                                <select class="form-control" name="payment_status" id="payment_status">
                                    <option value="" selected disabled>-- Choose Here --</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary update-invoice-btn">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
@stop

@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="{{asset('js/frontdesk/main.js')}}"></script>
<script src="{{asset('js/frontdesk/ajax_get_functions.js')}}"></script>
<script src="{{asset('js/frontdesk/ajax_post_functions.js')}}"></script>
<script src="{{asset('js/frontdesk/ajax_put_functions.js')}}"></script>
<script src="{{asset('js/frontdesk/ajax_delete_functions.js')}}"></script>
<script src="{{asset('js/frontdesk/countdown_timer.js')}}"></script>
<script src="{{asset('js/frontdesk/appointment_form.js')}}"></script>
<script src="{{asset('js/frontdesk/filter_client.js')}}"></script>
<script src="{{asset('js/frontdesk/dataTable.js')}}"></script>
<script src="{{asset('js/frontdesk/onClickEvents.js')}}"></script>
<script src="{{asset('js/frontdesk/onChangeEvents.js')}}"></script>
<script src="{{asset('js/reusableJs.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="{{asset('js/frontdesk/BookAppointmentComponent/app.js')}}"></script>
<script src="{{asset('js/frontdesk/BookAppointmentComponent/forms.js')}}"></script>
<script src="{{asset('js/frontdesk/BookAppointmentComponent/filter-client.js')}}"></script>
<script src="{{asset('js/frontdesk/BookAppointmentComponent/actions.js')}}"></script>

<script src="{{asset('js/frontdesk/TherapistAvailabilityComponent/therapistAvailabiltyFunction.js')}}"></script>
<script src="{{asset('js/frontdesk/UpcomingAppointmentComponent/upComingGuestFunction.js')}}"></script>

<!-- <script src="{{asset('js/frontdesk/GuestTabComponent/app.js')}}"></script>
<script src="{{asset('js/frontdesk/GuestTabComponent/action.js')}}"></script> -->
<script>
    // $(window).on('load',function(){
    //     // $('#start-shift-modal').modal('show');
    //     $('#start-shift-modal').modal('show');
    // });

    $(function() {
        getPosApi($('#spa_id_val').val());
        // getPosShift($('#spa_id_val').val());
        // $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, { icons: { time: 'fas fa-clock', date: 'fas fa-calendar', up: 'fas fa-arrow-up', down: 'fas fa-arrow-down', previous: 'far fa-chevron-left', next: 'far fa-chevron-right', today: 'far fa-calendar-check-o', clear: 'far fa-trash', close: 'far fa-times' } });
        // $('#datetimepicker1').datetimepicker();

        // $('#calendar').datepicker({

        // });
        // therapistTransactionCount($('#spa_id_val').val(), '2023-06-13 21:30:00');
        getResponses($('#spa_id_val').val());

        getAppointmentCount();
        // getAppointmentType('up');
        // getAppointmentType('move');
        // getServicesAppointment($('#spa_id_val').val(), 'move', 'move_app_services');
        // getServicesAppointment($('#spa_id_val').val(), 'up', 'edit_app_services');

        // setDateTimePicker('start_time_appointment_up', '');
        // setDateTimePicker('start_time_appointment_move', '');
        setDateTimePicker('edit_start_time', '');



        // loadRoom();
        getTotalSales($('#spa_id_val').val());
        getMasseurAvailability($('#spa_id_val').val());
        // getUpcomingGuest($('#spa_id_val').val());

        $('.select-client-type').select2();
    });
</script>
@stop
