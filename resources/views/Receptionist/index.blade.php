
@extends('adminlte::page')

@section('title', 'Receptionist')

@section('content_header')
    <h1>Dashboard</h1>
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
        .modal {
            overflow-y:auto;
        }

        .gj-modal .gj-picker-bootstrap {
            padding: 15px !important;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <div class="card-body pb-0">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">CPU Traffic</span>
                                        <span class="info-box-number">
                                        10
                                        <small>%</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Likes</span>
                                        <span class="info-box-number">41,410</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix hidden-md-up"></div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sales</span>
                                        <span class="info-box-number">760</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">New Members</span>
                                        <span class="info-box-number">2,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <section class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <button class="btn btn-block hidden" id="addNewSales">
                                                <i class="fas fa-shopping-cart"></i> 
                                                <span class="badge badge-danger text-default countSelected"></span>
                                            </button>
                                        </h3>
                                       
                                        <div class="card-tools">
                                            <ul class="nav nav-pills ml-auto">
                                                <li class="nav-item">
                                                    <a class="nav-link active" href="#room-availability" data-toggle="tab">List</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link salesView" href="#sales-data" data-toggle="tab">
                                                        View
                                                        <span class="badge badge-danger text-default viewBadgeCount"></span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#reservation-data" data-toggle="tab">
                                                        Reservations
                                                        <span class="badge badge-danger text-default"></span>
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
                                                <div class="row displayRoomList">

                                                </div>
                                            </div>
                                            <div class="tab-pane" id="sales-data" style="position: relative; height: auto;">
                                                <table id="transaction-lists" class="table table-striped table-valign-middle" style="width:100%;">
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
                                            <div class="tab-pane" id="reservation-data" style="position: relative;height: auto;">
                                                <div class="card-header border-transparent">
                                                    <h3 class="card-title">{{date('F, Y')}} Reservations</h3>
                                                </div>
                                                <table id="latest-reservation" class="table table-striped table-valign-middle" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Client Name</th>
                                                            <th>Service</th>
                                                            <th>Room #</th>
                                                            <th>Amount</th>
                                                            <th>Date Added</th>
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
                                    <div class="card-header">
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
                                <div class="card bg-gradient-success">
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
                                </div>
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @can('add sales')
        <div class="modal fade" id="add-new-sales-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form role="form" id="sales-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md changeModalSize">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Create new Sales</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="tabList">
                            <ul class="nav nav-pills dataTabs"></ul>
                            </div>
                            <br />
                            <div class="tab-content tabFormReservation">
                                
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-sales-btn hidden" value="Save">
                            <input type="button" class="btn btn-primary process-sales-btn" value="Process">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit sales')
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
                                        <select name="edit_services" id="edit_services" class="form-control select-edit-services" style="width:100%;"></select>
                                        <input type="hidden" name="edit_price" id="edit_price" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_services"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_masseur1">Masseur 1</label><span class="isRequired">*</span>
                                        <select name="edit_masseur1" id="edit_masseur1" class="form-control select-edit-masseur1" style="width:100%;"></select>
                                        <input type="hidden" name="edit_masseur1_id" id="edit_masseur1_id" class="form-control">
                                        <input type="hidden" name="edit_masseur1_id_prev" id="edit_masseur1_id_prev" class="form-control">

                                        <div class="custom-control custom-checkbox">
                                            <input disabled class="custom-control-input isEditMultipleMasseur" type="checkbox" id="editCustomCheckbox" value="1">
                                            <label for="editCustomCheckbox" class="custom-control-label">Is multiple Masseur ?</label>
                                        </div>
                                        <p class="text-danger hidden" id="error-edit_masseur1"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_masseur2">Masseur 2</label>
                                        <select name="edit_masseur2" id="edit_masseur2" class="form-control select-edit-masseur2" style="width:100%;"></select>
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
                                        <input name="edit_start_time" id="edit_start_time" class="form-control dateTimePicker" />
                                        <p class="text-danger hidden" id="error-edit_start_time"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_plus_time">Plus Time</label>
                                        <select name="edit_plus_time" id="edit_plus_time" class="form-control select-edit-plus_time" style="width:100%;"></select>
                                        <input type="hidden" name="edit_plus_time_price" id="edit_plus_time_price" class="form-control">
                                        <p class="text-danger hidden" id="error-edit_services"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="edit_room">Room #</label>
                                        <select name="edit_room" id="edit_room" class="form-control select-edit-room" style="width:100%;"></select>
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
    @endcan
@stop

@section('css')

@stop

@section('js')
<script src="{{asset('js/receptionist.js')}}"></script>
<script>
    $(function() {
        $('.dateTimePicker').datetimepicker({
            footer: true, modal: true,
            // datepicker: {
            //     disableDates:  function (date) {
            //         const currentDate = new Date();
            //     return date > currentDate ? true : false;
            //     }
            // }
        });
        
        loadRoom();
        getTotalSales($('#spa_id_val').val());
        getMasseurAvailability($('#spa_id_val').val());
        getLatestReservation($('#spa_id_val').val());
        getReservedTherapist($('#spa_id_val').val());

        $('.select-client-type').select2();   
        
    });
</script>
@stop
