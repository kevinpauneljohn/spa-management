
@extends('adminlte::page')

@section('title', 'Receptionist')

@section('content_header')
    <h1>{{$title}}</h1>
@stop

@section('content')
    <style>
        /* span {
            color: #fff;
        } */
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

        /* .gj-modal .gj-picker-bootstrap {
            padding: 15px !important;
        } */
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
        /* .bootstrap-datetimepicker-widget table td {
        color: red;
        } */
        /*  */
    </style>

    <div class="card">
        <div class="card-header">
            <div class="card-body pb-0">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Daily Appointment</span>
                                        <span class="info-box-number dailyAppointment"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Monthly Appointment</span>
                                        <span class="info-box-number monthlyAppointment"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix hidden-md-up"></div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Monthly New Client</span>
                                        <span class="info-box-number newClients"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Daily Sales</span>
                                        <span class="info-box-number dailySales"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <section class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <!-- <button class="btn btn-block btn-outline-info btn" id="addNewSales">
                                                <i class="fas fa-shopping-cart"></i> 
                                                <span class="badge badge-danger text-default countSelected"></span>
                                            </button> -->
                                            <button class="btn btn-block btn-outline-info btn" id="addNewAppointment">
                                                <i class="fas fa-shopping-cart"></i> 
                                            </button>
                                        </h3>
                                       
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#room-availability" data-toggle="tab">Rooms</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link salesView" href="#sales-data" data-toggle="tab">
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
                                                <div class="alert alert-primary alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <h5><i class="icon fas fa-info"></i> Note:</h5>
                                                    Blue color means available, Gray color means occupied.
                                                </div>
                                                <div class="row displayRoomList">

                                                </div>
                                            </div>
                                            <div class="tab-pane" id="sales-data" style="position: relative; height: auto;">
                                                <div class="alert alert-primary alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <h5><i class="icon fas fa-info"></i> Note:</h5>
                                                    List of clients that currently occupy rooms.
                                                </div>
                                                <table id="sales-data-lists" class="table table-striped table-valign-middle" style="width:100%;">
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
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="transactions-data" style="position: relative; height: auto;">
                                                <div class="alert alert-primary alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <h5><i class="icon fas fa-info"></i> Note:</h5>
                                                    List of all sales. Please update the payment status once the client has paid.
                                                </div>
                                                <table id="transaction-data-lists" class="table table-striped table-valign-middle" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Spa</th>
                                                            <th>Status</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="tab-pane" id="appointment-data" style="position: relative;height: auto;">
                                                <div class="alert alert-primary alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    <h5><i class="icon fas fa-info"></i> Note:</h5>
                                                    List of upcoming clients. Please move and update the start time of the appointment once the client has arrived.
                                                </div>
                                                <table id="appointment-data-lists" class="table table-striped table-valign-middle" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Client Name</th>
                                                            <th>Service</th>
                                                            <th>Batch #</th>
                                                            <th>Amount</th>
                                                            <th>Type</th>
                                                            <th>Status</th>
                                                            <th>Date Added</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">

                                </div>
                            </section>
                            <section class="col-lg-4">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">
                                            <i class="fas fa-users"></i>
                                            Masseur Availability
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <div class="progress-group availableMasseur">
                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header bg-warning">
                                        <h3 class="card-title">
                                            <i class="fas fa-users"></i>
                                            Upcoming Appointments
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content p-0">
                                            <div class="progress-group upcomingGuest">
                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="card bg-gradient-success">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">
                                            <i class="far fa-calendar-alt"></i>
                                            Calendar
                                        </h3>
                                        <div class="card-tools">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a href="#" class="dropdown-item">Add new event</a>
                                                    <a href="#" class="dropdown-item">Clear events</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="#" class="dropdown-item">View calendar</a>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div id="calendar" style="width: 100%"></div>
                                    </div>
                                </div> -->
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('add sales'))
        <div class="modal" id="add-new-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="appointment-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Set New Appointment</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="tabList">
                                <input type="hidden" class="form-control" id="guest_ids_val" value="1">
                                <ul class="nav nav-pills dataTabsAppointment">

                                </ul>
                            </div>
                            <br />

                            <div class="tab-content tabFormAppointment">
                                <div class="tab-pane" id="summaryTab">
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                                        The total amount can change depending on the selected services.
                                    </div>
                                    <div class="tableSummaryAppointment"></div>
                                    <div class="py-2 px-3 mt-4">
                                        <div class="col-md-4 border border-danger float-right">
                                            <h2 class="mb-0 total_amount_appointment text-center"></h2>
                                            <h4 class="mt-0 text-center">TOTAL</h4>
                                            <input type="hidden" class="form-control" id="totalAmountToPayAppointment">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary process-appointment-btn">Process</button>
                            <button type="button" class="btn btn-primary add-appointment-btn hidden">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('edit sales'))
        <div class="modal fade" id="update-sales-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="sales-update-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Update Sales</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <input type="hidden" class="form-control" id="edit_transaction_id">
                                    <input type="hidden" class="form-control" id="edit_client_id">
                                    <input type="hidden" class="form-control" id="edit_sales_id">
                                    <input type="hidden" class="form-control" id="edit_client_type">
                                    <div class="col-md-4">
                                        <label for="edit_first_name">First Name</label><span class="isRequired">*</span>
                                        <input type="text" name="edit_first_name" id="edit_first_name" class="form-control" disabled>
                                        <p class="text-danger hidden" id="error-edit_first_name"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_middle_name">Middle Name</label>
                                        <input type="text" name="edit_middle_name" id="edit_middle_name" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_last_name">Last Name</label><span class="isRequired">*</span>
                                        <input type="text" name="edit_last_name" id="edit_last_name" class="form-control" disabled>
                                        <p class="text-danger hidden" id="error-edit_last_name"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="edit_date_of_birth">Date of Birth</label><span class="isRequired">*</span>
                                        <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control" disabled>
                                        <p class="text-danger hidden" id="error-edit_date_of_birth"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_mobile_number">Mobile Number</label><span class="isRequired">*</span>
                                        <input type="text" name="edit_mobile_number" id="edit_mobile_number" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_mobile_number"><p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_email">Email</label>
                                        <input type="email" name="edit_email" id="edit_email" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="edit_address">Address</label>
                                        <input type="text" name="edit_address" id="edit_address" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="edit_services">Services</label><span class="isRequired">*</span>
                                        <select data-select="edit" name="edit_services" id="edit_services" class="form-control select-edit-services" style="width:100%;"></select>
                                        <input type="hidden" name="edit_price" id="edit_price" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_services"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_masseur1">Masseur 1</label><span class="isRequired">*</span>
                                        <select data-select="edit" name="edit_masseur1" id="edit_masseur1" class="form-control select-edit-masseur1" style="width:100%;"></select>
                                        <input type="hidden" name="edit_masseur1_id" id="edit_masseur1_id" class="form-control">
                                        <input type="hidden" name="edit_masseur1_id_prev" id="edit_masseur1_id_prev" class="form-control">

                                        <div class="custom-control custom-checkbox">
                                            <input data-select="edit" disabled class="custom-control-input isEditMultipleMasseur" type="checkbox" id="editCustomCheckbox" value="1">
                                            <label for="editCustomCheckbox" class="custom-control-label">Is multiple Masseur ?</label>
                                        </div>
                                        <p class="text-danger hidden" id="error-edit_masseur1"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_masseur2">Masseur 2</label>
                                        <select data-select="edit" name="edit_masseur2" id="edit_masseur2" class="form-control select-edit-masseur2" style="width:100%;"></select>
                                        <input type="hidden" name="edit_masseur2_id" id="edit_masseur2_id" class="form-control">
                                        <input type="hidden" name="edit_masseur2_id_prev" id="edit_masseur2_id_prev" class="form-control">
                                        <input type="hidden" name="edit_masseur2_id_val" id="edit_masseur2_id_val" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="edit_start_time">Start Time</label><span class="isRequired">*</span>
                                        <input type="datetime-local" id="edit_start_time" name="edit_start_time" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_start_time"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_plus_time">Plus Time</label>
                                        <select data-select="edit" name="edit_plus_time" id="edit_plus_time" class="form-control select-edit-plus_time" style="width:100%;"></select>
                                        <input type="hidden" name="edit_plus_time_price" id="edit_plus_time_price" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_services"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_room">Room #</label>
                                        <select data-select="edit" name="edit_room" id="edit_room" class="form-control select-edit-room" style="width:100%;"></select>
                                        <input type="hidden" name="edit_room_val" id="edit_room_val" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="py-2 px-3 mt-4">
                                            <div class="col-md-4 border border-danger float-right">
                                                <h2 class="mb-0 text-center totalAmountFormatted"></h2>
                                                <h4 class="mt-0 text-center">TOTAL</h4>
                                                <input type="hidden" class="form-control" id="totalAmountEditToPay">
                                                <input type="hidden" class="form-control" id="totalAmountEditToPayOld">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-sales-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal" id="update-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="update-appointment-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title viewAppointmentUpdateTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                                The total amount can change depending on the selected services.
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="edit_app_firstname">First Name : </label>
                                        <input type="text" class="form-control edit_app_firstname" id="edit_app_firstname" disabled>
                                        <input type="hidden" class="form-control edit_app_client_id" id="edit_app_client_id">
                                        <input type="hidden" class="form-control edit_app_id" id="edit_app_id">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_app_middlename">Middle Name : </label>
                                        <input type="text" class="form-control edit_app_middlename" id="edit_app_middlename" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_app_lastname">Last Name : </label>
                                        <input type="text" class="form-control edit_app_lastname" id="edit_app_lastname" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="edit_app_date_of_birth">Date of Birth : </label>
                                        <input type="date" class="form-control edit_app_date_of_birth" id="edit_app_date_of_birth">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_app_mobile_number">Mobile Number : </label>
                                        <input type="text" class="form-control edit_app_mobile_number" id="edit_app_mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="edit_app_email">Email Address : </label>
                                        <input type="email" class="form-control edit_app_email" id="edit_app_email">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_app_address">Address : </label>
                                        <input type="email" class="form-control edit_app_address" id="edit_app_address">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="edit_app_appointment_type">Appointment Type : </label>
                                        <select data-id="_up" name="edit_app_appointment_type" id="appointment_name_appointmentup" class="form-control appointment_name_appointmentup" style="width:100%;"></select>
                                    </div>
                                    <div class="col-md-6 socialMedialUpdate">
                                        <label for="edit_app_social_media_appointment">Social Media Type : </label>
                                        <select data-id="_up" name="edit_app_social_media_appointment" id="social_media_appointmentup" class="form-control social_media_appointmentup" style="width:100%;"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="edit_app_services">Services : </label>
                                        <select data-id="_up" name="edit_app_services" id="edit_app_servicesup" class="form-control select-services-appointment" style="width:100%;"></select>
                                        <input type="hidden" name="price_appointment_up" id="price_appointment_up" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_app_start_time">Start Time : </label>
                                        <input type="datetime-local" id="start_time_appointment_up" name="start_time_appointment_up" class="form-control">
                                    </div>
                                    <div class="col-md-4 border border-danger pull-right">
                                        <h2 class="mb-0 text-center totalAmountUpdateAppointmentFormatted"></h2>
                                        <h4 class="mt-0 text-center">TOTAL</h4>
                                        <input type="hidden" class="form-control" id="totalAmountAppointmentToPay" value="0">
                                        <input type="hidden" class="form-control" id="plusTimeAppointment" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary update-appointment-btn">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('view sales'))
        <div class="modal fade" id="view-sales-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="sales-view-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title viewRoomNumber"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_full_name">Full Name : </label>
                                        <p class="viewFullname"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_date_of_birth">Date of Birth : </label>
                                        <p class="viewDateOfBirth"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_mobile_number">Mobile Number : </label>
                                        <p class="viewMobileNumber"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="view_email">Email Address : </label>
                                        <p class="viewEmail"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="view_email">Address : </label>
                                        <p class="viewAddress"></p>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_service">Services : </label>
                                        <p class="viewService"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_therapist_1">Masseur 1 : </label>
                                        <p class="viewTherapist1"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_therapist_2">Masseur 2 : </label>
                                        <p class="viewTherapist2"></p>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_start_time">Start Time : </label>
                                        <p class="viewStartTime"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_end_time">End Time : </label>
                                        <p class="viewEndTime"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_remaining_time">Remaining : </label>
                                        <p class="viewRemainingTime"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="view_plus_time">Plus Time : </label>
                                        <p class="viewPlusTime"></p>
                                    </div>
                                    <div class="col-md-6 border border-danger float-right">
                                        <h2 class="mb-0 text-center totalAmountViewFormatted"></h2>
                                        <h4 class="mt-0 text-center">TOTAL</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal" id="view-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="view-appointment-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title viewAppointmentTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                                The total amount can change depending on the selected services.
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_full_name">Full Name : </label>
                                        <p class="viewAppointmentFullname"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_date_of_birth">Date of Birth : </label>
                                        <p class="viewAppointmentDateOfBirth"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_mobile_number">Mobile Number : </label>
                                        <p class="viewAppointmentMobileNumber"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="view_email">Email Address : </label>
                                        <p class="viewAppointmentEmail"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="view_email">Address : </label>
                                        <p class="viewAppointmentAddress"></p>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_batch">Batch # : </label>
                                        <p class="viewAppointmentBatch"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_type">Type : </label>
                                        <p class="viewAppointmentType"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_status">Status : </label>
                                        <p class="viewAppointmentStatus"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="view_service">Services : </label>
                                        <p class="viewAppointmentService"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="view_start_time">Start Time : </label>
                                        <p class="viewAppointmentStartTime"></p>
                                    </div>
                                    <div class="col-md-4 border border-danger pull-right">
                                        <h2 class="mb-0 text-center totalAmountViewAppointmentFormatted"></h2>
                                        <h4 class="mt-0 text-center">TOTAL</h4>
                                    </div>
                                </div>
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

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('view invoices'))
        <div class="modal fade" id="view-invoice-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="invoice-view-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title viewNameInvoice"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- <div class="callout callout-info">
                                        <h5><i class="fas fa-info"></i> Note:</h5>
                                        This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                                        </div> -->

                                        <div class="invoice p-3 mb-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4>
                                                        <i class="fas fa-globe"></i> <span class="spaName"></span>
                                                        <small class="float-right"><b>Date : </b>{{date('F d, Y')}}</small>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="row invoice-info">
                                                <div class="col-sm-6 invoice-col">
                                                    From
                                                    <address>
                                                        <strong><span class="spaName"></span></strong><br>
                                                        <span class="spaAddress"></span><br>
                                                        <span class="spaMobile"></span><br>
                                                        <span class="spaEmail"></span>
                                                    </address>
                                                </div>
                                                <!-- <div class="col-sm-4 invoice-col">
                                                    To
                                                    <address>
                                                        <strong><span class="clientName"></span></strong><br>
                                                        <span class="clientAddress"></span><br>
                                                        <span class="clientMobile"></span><br>
                                                        <span class="clientEmail"></span>
                                                    </address>
                                                </div> -->
                                                <div class="col-sm-6 invoice-col">
                                                    <span class="salesInvoiceNumber float-right"></span>
                                                    <!-- <br><br>
                                                    <b>Order ID:</b> <span class="salesId"></span><br>
                                                    <b>Payment Due:</b> <span class="transactionEndDate"></span><br>
                                                    <b>Account:</b> -->
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 table-responsive">
                                                    <table id="invoiceTable" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Client</th>
                                                                <th>Service</th>
                                                                <th>Room #</th>
                                                                <th>Start Time</th>
                                                                <th>End Time #</th>
                                                                <th>Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="lead">Payment Methods:</p>
                                                    <span class="paymentMethod"></span>

                                                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                                        <!-- Sample Notes Here..... -->
                                                    </p>
                                                </div>
                                                <div class="col-6">
                                                    <!-- <p class="lead">Amount Due <span class="transactionEndDate"></span></p> -->

                                                    <div class="table-responsive">
                                                        <table id="summaryTotal" class="table">
                             
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
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal" id="update-invoice-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                <p class="text-danger hidden" id="error-payment_method"></p>
                            </div>
                            <div class="form-group account_number_div hidden">
                                <label for="payment_account_number">Account Number</label><span class="isRequired">*</span>
                                <input type="text" class="form-control" name="payment_account_number" id="payment_account_number">
                                <p class="text-danger hidden" id="error-payment_account_number"></p>
                            </div>
                            <div class="form-group payment_bank_name hidden">
                                <label for="payment_bank_name">Bank Name</label><span class="isBankName isRequired">*</span>
                                <input type="text" class="form-control" name="payment_bank_name" id="payment_bank_name">
                                <p class="text-danger hidden" id="error-payment_bank_name"></p>
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

    @if(auth()->user()->hasRole('owner') || auth()->user()->can('move sales'))
    <div class="modal" id="move-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="move-appointment-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title viewAppointmentMoveTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                                The total amount can change depending on the selected services and plus time.
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="move_app_firstname">First Name : </label>
                                        <input type="text" class="form-control move_app_firstname" id="move_app_firstname" disabled>
                                        <input type="hidden" class="form-control move_app_client_id" id="move_app_client_id">
                                        <input type="hidden" class="form-control move_app_id" id="move_app_id">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_app_middlename">Middle Name : </label>
                                        <input type="text" class="form-control move_app_middlename" id="move_app_middlename" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_app_lastname">Last Name : </label>
                                        <input type="text" class="form-control move_app_lastname" id="move_app_lastname" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="move_app_date_of_birth">Date of Birth : </label>
                                        <input type="date" class="form-control move_app_date_of_birth" id="move_app_date_of_birth">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="move_app_mobile_number">Mobile Number : </label>
                                        <input type="text" class="form-control move_app_mobile_number" id="move_app_mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="move_app_email">Email Address : </label>
                                        <input type="email" class="form-control move_app_email" id="move_app_email">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="move_app_address">Address : </label>
                                        <input type="email" class="form-control move_app_address" id="move_app_address">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="move_app_appointment_type">Appointment Type : </label><span class="isRequired">*</span>
                                        <select data-id="_move" name="move_app_appointment_type" id="appointment_name_appointmentmove" class="form-control appointment_name_appointmentmove" style="width:100%;"></select>
                                        <p class="text-danger hidden" id="error-move_app_appointment_type"></p>
                                    </div>
                                    <div class="col-md-6 socialMedialMove">
                                        <label for="move_app_social_media_appointment">Social Media Type : </label>
                                        <select data-id="_move" name="move_app_social_media_appointment" id="social_media_appointmentmove" class="form-control social_media_appointmentmove" style="width:100%;"></select>
                                        <p class="text-danger hidden" id="error-move_app_social_media_appointment"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="move_app_services">Services : </label><span class="isRequired">*</span>
                                        <select data-select="move" data-id="_up" name="move_app_services" id="move_app_servicesmove" class="form-control select-services-move-appointment" style="width:100%;"></select>
                                        <input type="hidden" name="price_appointment_move" id="price_appointment_move" class="form-control" value="0">
                                        <input type="hidden" name="move_app_services_id" id="move_app_services_id" class="form-control">
                                        <p class="text-danger hidden" id="error-move_app_servicesmove"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_plus_time">Plus Time : </label>
                                        <select data-select="move" name="move_plus_time" id="move_plus_time" class="form-control select-move-plus_time" style="width:100%;"></select>
                                        <input type="hidden" name="move_plus_time_price" id="move_plus_time_price" class="form-control" value="0">
                                        <input type="hidden" name="move_plus_time_id" id="move_plus_time_id" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_app_start_time">Start Time : </label><span class="isRequired">*</span>
                                        <input type="datetime-local" id="start_time_appointment_move" name="start_time_appointment_move" class="form-control">
                                        <p class="text-danger hidden" id="error-start_time_appointment_move"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="move_masseur1">Masseur 1</label><span class="isRequired">*</span>
                                        <select data-select="move" name="move_masseur1" id="move_masseur1" class="form-control select-move-masseur1" style="width:100%;"></select>
                                        <input type="hidden" name="move_masseur1_id" id="move_masseur1_id" class="form-control">
                                        <input type="hidden" name="move_masseur1_id_prev" id="move_masseur1_id_prev" class="form-control">

                                        <div class="custom-control custom-checkbox">
                                            <input data-select="move" disabled class="custom-control-input isMoveMultipleMasseur" type="checkbox" id="moveCustomCheckbox" value="1">
                                            <label for="moveCustomCheckbox" class="custom-control-label">Is multiple Masseur ?</label>
                                        </div>
                                        <p class="text-danger hidden" id="error-move_masseur1_id"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_masseur2">Masseur 2</label>
                                        <select data-select="move" name="move_masseur2" id="move_masseur2" class="form-control select-move-masseur2" style="width:100%;" disabled></select>
                                        <input type="hidden" name="move_masseur2_id" id="move_masseur2_id" class="form-control">
                                        <input type="hidden" name="move_masseur2_id_prev" id="move_masseur2_id_prev" class="form-control">
                                        <input type="hidden" name="move_masseur2_id_val" id="move_masseur2_id_val" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="move_room">Room #</label><span class="isRequired">*</span>
                                        <select data-select="move" name="move_room" id="move_room" class="form-control select-move-room" style="width:100%;"></select>
                                        <input type="hidden" class="form-control" id="move_room_id">
                                        <p class="text-danger hidden" id="error-move_room"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="mb-0 text-center totalAmountMoveAppointmentFormatted float-right"></h2>
                                        <input type="hidden" class="form-control" id="totalAmountMoveToPay">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary move-sales-appointment-btn">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
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
<script>
    $(function() {
        // $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, { icons: { time: 'fas fa-clock', date: 'fas fa-calendar', up: 'fas fa-arrow-up', down: 'fas fa-arrow-down', previous: 'far fa-chevron-left', next: 'far fa-chevron-right', today: 'far fa-calendar-check-o', clear: 'far fa-trash', close: 'far fa-times' } });
        // $('#datetimepicker1').datetimepicker();

        // $('#calendar').datepicker({

        // });
        getResponses($('#spa_id_val').val());

        getAppointmentCount();
        getAppointmentType('up');
        getAppointmentType('move');
        getServicesAppointment($('#spa_id_val').val(), 'move', 'move_app_services');
        getServicesAppointment($('#spa_id_val').val(), 'up', 'edit_app_services');

        // setDateTimePicker('start_time_appointment_up', '');
        // setDateTimePicker('start_time_appointment_move', '');
        setDateTimePicker('edit_start_time', '');



        loadRoom();
        getTotalSales($('#spa_id_val').val());
        getMasseurAvailability($('#spa_id_val').val());
        getUpcomingGuest($('#spa_id_val').val());
        loadData($('#spa_id_val').val());
        $('.select-client-type').select2();         
    });
</script>
@stop
