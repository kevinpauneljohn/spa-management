@extends('adminlte::page')

@section('title', 'Services')

@section('content_header')
    <h1></h1>
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-spa"></i> {{ucwords($spa->name)}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('owners.index')}}">Owners</a></li>
                        <li class="breadcrumb-item"><a href="{{route('spa.overview',['id' => $owner->user_id])}}">Spa</a> </li>
                        <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="row">
        <div class="col-md-3">

            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-fw fa-user profile-user-img img-fluid img-circle" style="width:80px;height:80px;font-size:60px;"></i>
                    </div>


                    <h3 class="profile-username text-center">{{ucwords($owner->user->fullname)}}</h3>
                    <input type="hidden" class="form-control user-id" value="{{$owner->id}}">
                    <input type="hidden" class="form-control spa-id" value="{{$spa->id}}" />
                    <p class="text-muted text-center">Owner</p>
                    <hr>
                    <strong><i class="fas fa-user mr-1"></i> Username</strong>

                    <p class="text-muted"><a href="#">{{ucfirst($owner->user->username)}}</a> </p>
                    <hr>
                    <strong><i class="fas fa-phone mr-1"></i> Contact Number</strong>

                    <p class="text-muted"><a href="tel:{{$owner->user->mobile_number}}">{{$owner->user->mobile_number}}</a> </p>
                    <hr>
                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>

                    <p class="text-muted"><a href="mailto:{{$owner->user->email}}">{{$owner->user->email}}</a> </p>
                    <hr>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Spa Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                    <p class="text-muted">{{ucwords($spa->address)}}</p>

                    <hr>

                    <strong><i class="fas fa-bed mr-1"></i> Rooms</strong>

                    <p class="text-muted">
                        <span class="tag tag-primary">{{$spa->number_of_rooms}} available rooms</span>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#therapists" data-toggle="tab">Massage Therapists</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="data">
                            <div class="alert alert-default-info">
                                <h5><i class="fas fa-info"></i> Note:</h5>
                                Create services you offer to your customers.
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @can('add service')
                                        <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewService"><i class="fa fa-plus-circle"></i> Add Service</button>
                                    @endcan
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="service-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                        <thead>
                                        <tr role="row">
                                            <th>Date Added</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Duration</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="therapists">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-default-info">
                                        <h5><i class="fas fa-info"></i> Note:</h5>
                                        Add masseur/masseuse to your spa who will serve your valued customers
                                    </div>
                                    @can('add therapist')
                                        <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewTherapist"><i class="fa fa-plus-circle"></i> Add Therapist</button>
                                    @endcan
                                </div>
                            </div><br />
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="therapist-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                        <thead>
                                        <tr role="row">
                                            <th>Date Added</th>
                                            <th>Full Name</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('add service')
        <div class="modal fade" id="add-new-service-modal">
            <form role="form" id="service-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Services Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-add">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="info-part" id="info-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#price-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="price-part" id="price-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Pricing</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="info-part" class="content" role="tabpanel" aria-labelledby="info-part-trigger">
                                        <div class="form-group name">
                                            <label for="name">Name</label><span class="required">*</span>
                                            <input type="text" name="name" class="form-control" id="name">
                                        </div>
                                        <div class="form-group description">
                                            <label for="description">Description</label><span class="required">*</span>
                                            <textarea name="description" class="form-control" id="description"></textarea>
                                        </div>
                                    </div>
                                    <div id="price-part" class="content" role="tabpanel" aria-labelledby="price-part-trigger">
                                        <div class="form-group duration">
                                            <label for="duration">Duration</label> <i>(minutes)</i><span class="required">*</span>
                                            <br />
                                            <select class="form-control duration-select" name="duration" id="duration" style="width:100%;">
                                                <option value="">Select here</option>
                                                @foreach($range as $key => $data)
                                                    <option value="{{$data}}">{{$data}} minutes</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group price">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" id="price" name="price">
                                        </div>
                                        <div class="form-group category">
                                            <label for="category">Category</label>
                                            <select name="category" class="form-control" id="category">
                                                <option value="">Select here</option>
                                                <option value="regular">Regular</option>
                                                <option value="promo">Promo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary info_next_btn" onclick="addServiceStepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default price_previous_btn hiddenBtn" onclick="addServiceStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary price_submit_btn add-service-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit service')
        <div class="modal fade" id="update-service-modal">
            <form role="form" id="update-service-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Services Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-update">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#edit-info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-info-part" id="edit-info-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-price-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-price-part" id="edit-price-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Pricing</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="edit-info-part" class="content" role="tabpanel" aria-labelledby="edit-info-part-trigger">
                                        <div class="form-group edit_name">
                                            <label for="edit_name">Name</label><span class="required">*</span>
                                            <input type="text" name="edit_name" class="form-control" id="edit_name">
                                            <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                        </div>
                                        <div class="form-group edit_description">
                                            <label for="edit_description">Description</label><span class="required">*</span>
                                            <input type="text" name="edit_description" class="form-control" id="edit_description">
                                        </div>
                                    </div>
                                    <div id="edit-price-part" class="content" role="tabpanel" aria-labelledby="edit-price-part-trigger">
                                        <div class="form-group edit_duration">
                                            <label for="edit_duration">Duration</label><span class="required">*</span>
                                            <br />
                                            <select class="form-control edit-duration-select" name="edit_duration" id="edit_duration" style="width:100%;">

                                            </select>
                                        </div>
                                        <div class="form-group edit_price">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" id="edit_price" name="edit_price">
                                        </div>
                                        <div class="form-group edit_category">
                                            <label for="edit_category">Category</label>
                                            <select name="edit_category" class="form-control" id="edit_category">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary edit_info_next_btn" onclick="editServiceStepper.next()">Next</button>
                            <button type="button" class="btn btn-default edit_price_previous_btn hiddenBtn" onclick="editServiceStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_price_submit_btn update-service-btn hiddenBtn">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

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
                            <div class="bs-stepper" id="bs-stepper-add-therapist">
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
                                        <div class="form-group commission_percentage offers hidden">
                                            <label for="commission_percentage" class="commission_percentage_name">Commission %</label><span class="required">*</span>
                                            <input type="number" name="commission_percentage" id="commission_percentage" class="form-control" >
                                        </div>
                                        <div class="form-group commission_flat offers hidden">
                                            <label for="commission_flat" class="commission_flat_name">Commission Amount</label><span class="required">*</span>
                                            <input type="number" name="commission_flat" id="commission_flat" class="form-control">
                                        </div>
                                        <div class="form-group allowance offers hidden">
                                            <label for="allowance" class="allowance_name">Allowance Amount</label>
                                            <input type="number" name="allowance" id="allowance" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default therapist_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary therapist_name_next_btn" onclick="addTherapistStepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default therapist_info_previous_btn hiddenBtn" onclick="addTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary therapist_info_next_btn hiddenBtn" onclick="addTherapistStepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default therapist_contact_previous_btn hiddenBtn" onclick="addTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary therapist_contact_next_btn hiddenBtn" onclick="addTherapistStepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default therapist_offer_previous_btn hiddenBtn" onclick="addTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary therapist_offer_submit_btn add-therapist-btn hiddenBtn" disabled>Submit</button>
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
                            <div class="bs-stepper" id="bs-stepper-update-therapist">
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
                                            <select name="edit_certificate" class="form-control" id="edit_certificate">
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
                                        <div class="form-group edit_commission_percentage hidden edit-offers">
                                            <label for="edit_commission_percentage" class="edit_commission_name">Commission Percentage</label><span class="required">*</span>
                                            <input type="number" name="edit_commission_percentage" id="edit_commission_percentage" class="form-control">
                                        </div>
                                        <div class="form-group edit_commission_flat hidden edit-offers">
                                            <label for="edit_commission_flat" class="edit_commission_name">Commission Amount</label><span class="required">*</span>
                                            <input type="number" name="edit_commission_flat" id="edit_commission_flat" class="form-control">
                                        </div>
                                        <div class="form-group edit_allowance hidden edit-offers">
                                            <label for="edit_allowance" class="edit_allowance_name">Allowance Amount</label>
                                            <input type="number" name="edit_allowance" id="edit_allowance" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary edit_name_next_btn" onclick="editTherapistStepper.next()">Next</button>
                            <button type="button" class="btn btn-default edit_info_previous_btn hiddenBtn" onclick="editTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_info_next_btn hiddenBtn" onclick="editTherapistStepper.next()">Next</button>
                            <button type="button" class="btn btn-default edit_contact_previous_btn hiddenBtn" onclick="editTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_contact_next_btn hiddenBtn" onclick="editTherapistStepper.next()">Next</button>
                            <button type="button" class="btn btn-default edit_offer_previous_btn hiddenBtn" onclick="editTherapistStepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_offer_submit_btn update-therapist-btn hiddenBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

@stop

@section('css')
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@stop

@section('js')
    <script src="{{asset('js/service.js')}}"></script>
    <script src="{{asset('js/therapist.js')}}"></script>
    <script>
        $(document).ready(function() {
            var spa_id = $('.spa-id').val();
            $('.duration-select').select2();
            $('.edit-duration-select').select2();

            $('#service-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('service.lists', ['id' => $spa["id"]]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'duration', name: 'duration'},
                    { data: 'category', name: 'category'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewService').on('click', function() {
                $('#name').val('');
                $('#description').val('');
                $('#duration').val('');
                $('#price').val('');
                $('#category').val('');
                $('#add-new-service-modal').modal('show');
            });

            $(document).on('click','.edit-service-btn',function() {
                $('#edit_id').val('');
                $('#edit_name').val('');
                $('#edit_description').val('');
                $('#edit_price').val('');

                $('#update-service-modal').modal('show');
            });

        });

        function reloadServiceTable ()
        {
            var table = $('#service-list').DataTable();
            table.ajax.reload();
        }

        $(document).ready(function() {
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

    </script>
@stop
