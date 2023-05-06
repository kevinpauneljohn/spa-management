@extends('adminlte::page')

@section('title', 'Therapist')

@section('content_header')
    <h1></h1>
@stop
<style>
    .required {
        color: red;
    }
    .hidden{
        display:none;
    }
    .hiddenBtn {
        display: none !important;
    }
</style>
@section('content')
    <div class="card">
        <div class="card-header">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Therapist Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('owners')}}">Owners</a></li>
                            <li class="breadcrumb-item"><a href="{{url('spa/overview', [ 'id' => $owners['id'] ])}}">Spa</a></li>
                            <li class="breadcrumb-item active">Therapist</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="card-body">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <i class="fas fa-fw fa-user profile-user-img img-fluid img-circle" style="width:80px;height:80px;font-size:60px;"></i>
                                    </div>

                                    <h3 class="profile-username text-center">{{$owners['firstname']}} {{$owners['lastname']}}</h3>
                                    <input type="hidden" class="form-control user-id" value="{{$owners['id']}}" />
                                    <input type="hidden" class="form-control spa-id" value="{{$spa['id']}}" />
                                    <p class="text-muted text-center">{{ucfirst($roles)}}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Username</b> <a class="float-right">{{$owners['username']}}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Mobile Number: </b> <a class="float-right" href="tel:{{$owners['mobile_number']}}">{{$owners['mobile_number']}}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Email: </b> <a class="float-right" href="mailto:{{$owners['email']}}">{{$owners['email']}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Spa Information</h3>
                                </div>
                                <div class="card-body">
                                    <strong><i class="fas fa-spa mr-1"></i> {{ucfirst($spa['name'])}}</strong>
                                    <hr>
                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                    <p class="text-muted">{{ucfirst($spa['address'])}}</p>
                                    <hr>
                                    <strong><i class="fas fa-person-booth mr-1"></i> Room</strong>
                                    <p class="text-muted">{{$spa['number_of_rooms']}}</p>
                                    <hr>
                                    <strong><i class="fas fa-id-badge mr-1"></i> License</strong>
                                    <p class="text-muted">
                                    <span class="tag tag-danger">UI Design</span>
                                    <span class="tag tag-success">Coding</span>
                                    <span class="tag tag-info">Javascript</span>
                                    <span class="tag tag-warning">PHP</span>
                                    <span class="tag tag-primary">Node.js</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">List</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                    </ul>
                                </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @can('add therapist')
                                                    <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewTherapist"><i class="fa fa-plus-circle"></i> Add New</button>
                                                @endcan
                                            </div>
                                        </div><br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="therapist-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Date Added</th>
                                                            <th>Fullname</th>
                                                            <th>Birth Date</th>
                                                            <th>Mobile Number</th>
                                                            <th>Email Address</th>
                                                            <th>Gender</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="settings">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @can('add therapist')
        <div class="modal fade" id="add-new-therapist-modal">
            <form role="form" id="therapist-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Therapist Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-add">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#name-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="name-part" id="name-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Name</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="info-part" id="info-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="contact-part" id="contact-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Contact</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#offer-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="offer-part" id="offer-part-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Offer</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="name-part" class="content" role="tabpanel" aria-labelledby="name-part-trigger">
                                        <div class="form-group firstname">
                                            <label for="firstname">First Name</label><span class="required">*</span>
                                            <input type="text" name="firstname" class="form-control" id="firstname">
                                        </div>
                                        <div class="form-group middlename">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" name="middlename" class="form-control" id="middlename">
                                        </div>
                                        <div class="form-group lastname">
                                            <label for="lastname">Last Name</label><span class="required">*</span>
                                            <input type="text" name="lastname" class="form-control" id="lastname">
                                        </div>
                                    </div>
                                    <div id="info-part" class="content" role="tabpanel" aria-labelledby="info-part-trigger">
                                        <div class="form-group date_of_birth">
                                            <label for="date_of_birth">Birth Date</label>
                                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                                        </div>
                                        <div class="form-group gender">
                                            <label for="gender">Gender</label><span class="required">*</span>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Select here</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group certificate">
                                            <label for="certificate">Certificate</label>
                                            <select name="ceritificate" class="form-control" id="certificate">
                                                <option value="">Select here</option>
                                                <option value="DOH">DOH</option>
                                                <option value="NC2">NC2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="contact-part" class="content" role="tabpanel" aria-labelledby="contact-part-trigger">
                                        <div class="form-group mobile_number"><span class="required">*</span>
                                            <label for="mobile_number">Mobile Number</label>
                                            <input type="text" name="mobile_number" id="mobile_number" class="form-control">
                                        </div>
                                        <div class="form-group email">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>
                                    </div>
                                    <div id="offer-part" class="content" role="tabpanel" aria-labelledby="offer-part-trigger">
                                        <div class="form-group offer_type">
                                            <label for="offer_type">Offer Type</label><span class="required">*</span>
                                            <select class="form-control" name="offer_type" id="offer_type">
                                                <option value="">Select here</option>
                                                <option value="percentage_only">Percentage Only</option>
                                                <option value="percentage_plus_allowance">Percentage + Allowance</option>
                                                <option value="amount_only">Amount Only</option>
                                                <option value="amount_plus_allowance">Amount + Allowance</option>
                                            </select>
                                        </div>
                                        <div class="form-group commission hidden">
                                            <label for="commission" class="commission_name">Commission</label><span class="required">*</span>
                                            <input type="number" name="commission" id="commission" class="form-control">
                                        </div>
                                        <div class="form-group allowance hidden">
                                            <label for="allowance" class="allowance_name">Allowance Amount</label>
                                            <input type="number" name="allowance" id="allowance" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary name_next_btn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary info_next_btn hiddenBtn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default contact_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary contact_next_btn hiddenBtn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default offer_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary offer_submit_btn add-therapist-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit therapist')
        <div class="modal fade" id="update-therapist-modal">
            <form role="form" id="update-therapist-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Therapist Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-update">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#edit-name-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-name-part" id="edit-name-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Name</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-info-part" id="edit-info-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-contact-part" id="edit-contact-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Contact</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-offer-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-offer-part" id="edit-offer-part-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Offer</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="edit-name-part" class="content" role="tabpanel" aria-labelledby="edit-name-part-trigger">
                                        <div class="form-group edit_firstname">
                                            <label for="edit_firstname">First Name</label><span class="required">*</span>
                                            <input type="text" name="edit_firstname" class="form-control" id="edit_firstname">
                                            <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                            <input type="hidden" name="edit_user_id" id="edit_user_id" class="form-control">
                                        </div>
                                        <div class="form-group edit_middlename">
                                            <label for="edit_middlename">Middle Name</label>
                                            <input type="text" name="edit_middlename" class="form-control" id="edit_middlename">
                                        </div>
                                        <div class="form-group edit_lastname">
                                            <label for="edit_lastname">Last Name</label><span class="required">*</span>
                                            <input type="text" name="edit_lastname" class="form-control" id="edit_lastname">
                                        </div>
                                    </div>
                                    <div id="edit-info-part" class="content" role="tabpanel" aria-labelledby="edit-info-part-trigger">
                                        <div class="form-group edit_date_of_birth">
                                            <label for="edit_date_of_birth">Birth Date</label>
                                            <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control">
                                        </div>
                                        <div class="form-group edit_gender">
                                            <label for="edit_gender">Gender</label><span class="required">*</span>
                                            <select class="form-control" name="edit_gender" id="edit_gender">
                                                <option value="">Select here</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group edit_certificate">
                                            <label for="edit_certificate">Certificate</label>
                                            <select name="edit_ceritificate" class="form-control" id="edit_certificate">
                                                <option value="">Select here</option>
                                                <option value="DOH">DOH</option>
                                                <option value="NC2">NC2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="edit-contact-part" class="content" role="tabpanel" aria-labelledby="edit-contact-part-trigger">
                                        <div class="form-group edit_mobile_number">
                                            <label for="edit_mobile_number">Mobile Number</label><span class="required">*</span>
                                            <input type="text" name="edit_mobile_number" id="edit_mobile_number" class="form-control">
                                        </div>
                                        <div class="form-group edit_email">
                                            <label for="edit_email">Email</label>
                                            <input type="edit_email" name="edit_email" id="edit_email" class="form-control">
                                        </div>
                                    </div>
                                    <div id="edit-offer-part" class="content" role="tabpanel" aria-labelledby="edit-offer-part-trigger">
                                        <div class="form-group edit_offer_type">
                                            <label for="edit_offer_type">Offer Type</label><span class="required">*</span>
                                            <select class="form-control" name="edit_offer_type" id="edit_offer_type">
                                                <option value="">Select here</option>
                                                <option value="percentage_only">Percentage Only</option>
                                                <option value="percentage_plus_allowance">Percentage + Allowance</option>
                                                <option value="amount_only">Amount Only</option>
                                                <option value="amount_plus_allowance">Amount + Allowance</option>
                                            </select>
                                        </div>
                                        <div class="form-group edit_commission hidden">
                                            <label for="edit_commission" class="edit_commission_name">Commission</label><span class="required">*</span>
                                            <input type="number" name="edit_commission" id="edit_commission" class="form-control">
                                        </div>
                                        <div class="form-group edit_allowance hidden">
                                            <label for="edit_allowance" class="edit_allowance_name">Allowance Amount</label>
                                            <input type="number" name="edit_allowance" id="edit_allowance" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary edit_name_next_btn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_info_next_btn hiddenBtn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_contact_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_contact_next_btn hiddenBtn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_offer_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_offer_submit_btn update-therapist-btn hiddenBtn">Submit</button>
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
<script src="{{asset('js/therapist.js')}}"></script>
    <script>
        $(document).ready(function() {
            var spa_id = $('.spa-id').val();
            $('#therapist-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('therapist.lists', ['id' => $spa["id"]]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'fullname', name: 'fullname'},
                    { data: 'date_of_birth', name: 'date_of_birth'},
                    { data: 'mobile_number', name: 'mobile_number'},
                    { data: 'email', name: 'email'},
                    { data: 'gender', name: 'gender'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewTherapist').on('click', function() {
                $('#firstname').val('');
                $('#middlename').val('');
                $('#lastname').val('');
                $('#date_of_birth').val('');
                $('#mobile_number').val('');
                $('#email').val('');
                $('#gender').val('');
                $('#certificate').val('');
                $('#commission').val('');
                $('#allowance').val('');
                $('#offer_type').val('');
                $('#add-new-therapist-modal').modal('show');
            });

            $(document).on('click','.edit-therapist-btn',function() {
                $('#edit_id').val('');
                $('#edit_user_id').val('');
                $('#edit_firstname').val('');
                $('#edit_middlename').val('');
                $('#edit_lastname').val('');
                $('#edit_date_of_birth').val('');
                $('#edit_mobile_number').val('');
                $('#edit_email').val('');
                $('#edit_gender').val('');
                $('#edit_certificate').val('');
                $('#edit_commission').val('');
                $('#edit_allowance').val('');
                $('#edit_offer_type').val('');

                $('#update-therapist-modal').modal('show');
            });

            $('#offer_type').on('change', function() {
                var val = $(this).find(":selected").val();
                if (val === 'percentage_only') {
                    $('.commission').removeClass('hidden');
                    $('.commission_name').text('Commission Rate');

                    $('.allowance').addClass('hidden');
                    $('#allowance').val(0);
                } else if (val === 'percentage_plus_allowance') {
                    $('.commission').removeClass('hidden');
                    $('.commission_name').text('Commission Rate');

                    $('.allowance').removeClass('hidden');
                    $('.allowance_name').text('Allowance');
                } else if (val === 'amount_only') {
                    $('.commission').removeClass('hidden');
                    $('.commission_name').text('Commission Amount');

                    $('.allowance').addClass('hidden');
                    $('#allowance').val(0);
                } else if (val === 'amount_plus_allowance') {
                    $('.commission').removeClass('hidden');
                    $('.commission_name').text('Commission Amount');
                    $('.allowance').removeClass('hidden');
                    $('.allowance_name').text('Allowance');
                }
            });

            $('#edit_offer_type').on('change', function() {
                var val = $(this).find(":selected").val();
                if (val === 'percentage_only') {
                    $('.edit_commission').removeClass('hidden');
                    $('.edit_commission_name').text('Commission Rate');

                    $('.edit_allowance').addClass('hidden');
                    $('#edit_allowance').val(0);
                } else if (val === 'percentage_plus_allowance') {
                    $('.edit_commission').removeClass('hidden');
                    $('.edit_commission_name').text('Commission Rate');
                    $('.edit_allowance').removeClass('hidden');
                    $('.edit_allowance_name').text('Allowance');
                } else if (val === 'amount_only') {
                    $('.edit_commission').removeClass('hidden');
                    $('.edit_commission_name').text('Commission Amount');

                    $('.edit_allowance').addClass('hidden');
                    $('#edit_allowance').val(0);
                } else if (val === 'amount_plus_allowance') {
                    $('.edit_commission').removeClass('hidden');
                    $('.edit_commission_name').text('Commission Amount');
                    $('.edit_allowance').removeClass('hidden');
                    $('.edit_allowance_name').text('Allowance');
                }
            });
        });

        function reloadTherapistTable ()
        {
            var table = $('#therapist-list').DataTable();
            table.ajax.reload();
        }

        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('#bs-stepper-add'))
        });

        document.addEventListener('DOMContentLoaded', function () {
            window.steppers = new Stepper(document.querySelector('#bs-stepper-update'))
        });
    </script>
@stop
