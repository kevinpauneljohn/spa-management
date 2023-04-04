@extends('adminlte::page')

@section('title', 'Owner')

@section('content_header')
    <h1></h1>
@stop
<style>
    .required {
        color: red;
    }

    .errorForm {
        border: 2px solid red !important;
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
                            <h1>Owner Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Owners</a></li>
                            <li class="breadcrumb-item active">List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @can('add owner')
                        <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewOwner"><i class="fa fa-plus-circle"></i> Add New</button>
                    @endcan
                </div>
            </div><br />
            <div class="row">
                <div class="col-md-12">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <table id="owners-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                            <thead>
                            <tr role="row">
                                <th>Date Added</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Mobile Number</th>
                                <th>Qty of SPA</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('add owner')
        <div class="modal fade" id="add-new-owner-modal">
            <form role="form" id="owner-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Owner Registration Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-add">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#basic-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="basic-part" id="basic-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Basic Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="contact-part" id="contact-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Contact Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#credential-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="credential-part" id="credential-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Credentials</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="basic-part" class="content" role="tabpanel" aria-labelledby="basic-part-trigger">
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
                                    <div id="contact-part" class="content" role="tabpanel" aria-labelledby="contact-part-trigger">
                                        <div class="form-group mobile_number">
                                            <label for="mobile_number">Mobile Number</label><span class="required">*</span>
                                            <input type="text" name="mobile_number" class="form-control" id="mobile_number">
                                        </div>
                                        <div class="form-group email">
                                            <label for="email">Email</label><span class="required">*</span>
                                            <input type="email" name="email" class="form-control" id="email">
                                            <p class="emailValidation text-danger hiddenBtn"></p>
                                        </div>
                                    </div>
                                    <div id="credential-part" class="content" role="tabpanel" aria-labelledby="credential-part-trigger">
                                        <div class="form-group username">
                                            <label for="username">Username</label><span class="required">*</span>
                                            <input type="text" name="username" class="form-control" id="username">
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 password">
                                                    <label for="password">Password</label><span class="required">*</span>
                                                    <div class="input-group mb-3" id="show_hide_password">
                                                        <input type="password" name="password" id="password" class="form-control">
                                                        <button type="button" class="input-group-text password_icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                                    </div>
                                                    <p class="passwordValidation text-danger hiddenBtn"></p>
                                                </div>
                                                <div class="col-lg-6 password_confirmation">
                                                    <label for="password_confirmation">Confirm Password</label><span class="required">*</span>
                                                    <div class="input-group mb-3" id="show_hide_confirm_password">
                                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                                        <button type="button" class="input-group-text confirm_password_icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary basic_info_next_btn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default contact_info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary contact_info_next_btn hiddenBtn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default credential_info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary credential_info_submit_btn add-owner-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit owner')
        <div class="modal fade" id="update-owner-modal">
            <form role="form" id="update-owner-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Owner Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-update">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#edit-basic-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-basic-part" id="edit-basic-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Basic Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-contact-part" id="edit-contact-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Contact Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-credential-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-credential-part" id="edit-credential-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Credentials</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="edit-basic-part" class="content" role="tabpanel" aria-labelledby="edit-basic-part-trigger">
                                        <div class="form-group edit_firstname">
                                            <label for="edit_firstname">First Name</label><span class="required">*</span>
                                            <input type="text" name="edit_firstname" class="form-control" id="edit_firstname">
                                            <input type="hidden" name="edit_id" id="edit_id" class="form-control">
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
                                    <div id="edit-contact-part" class="content" role="tabpanel" aria-labelledby="edit-contact-part-trigger">
                                        <div class="form-group edit_mobile_number">
                                            <label for="edit_mobile_number">Mobile Number</label><span class="required">*</span>
                                            <input type="text" name="edit_mobile_number" class="form-control" id="edit_mobile_number">
                                        </div>
                                        <div class="form-group edit_email">
                                            <label for="edit_email">Email</label><span class="required">*</span>
                                            <input type="edit_email" name="edit_email" class="form-control" id="edit_email">
                                            <p class="emailValidationEdit text-danger hiddenBtn"></p>
                                        </div>
                                    </div>
                                    <div id="edit-credential-part" class="content" role="tabpanel" aria-labelledby="edit-credential-part-trigger">
                                        <div class="form-group edit_username">
                                            <label for="edit_username">Username</label><span class="required">*</span>
                                            <input type="text" name="edit_username" class="form-control" id="edit_username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary edit_basic_info_next_btn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_contact_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_contact_info_next_btn hiddenBtn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_credential_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_credential_info_submit_btn update-owner-btn hiddenBtn">Submit</button>
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
<script src="{{asset('js/user.js')}}"></script>
    <script>
        $(function() {
            $('#owners-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('owner.lists') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at', className: 'text-center'},
                    { data: 'fullname', name: 'fullname'},
                    { data: 'username', name: 'username'},
                    { data: 'email', name: 'email'},
                    { data: 'mobile_number', name: 'mobile_number', className: 'text-center'},
                    { data: 'qty_of_spa', name: 'qty_of_spa', className: 'text-center'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewOwner').on('click', function() {
                $('#firstname').val('');
                $('#middlename').val('');
                $('#lastname').val('');
                $('#mobile_number').val('');
                $('#email').val('');
                $('#username').val('');
                $('#password').val('');
                $('#password_confirmation').val();
                
                $('.password_icon').prop("disabled", true);
                $('.confirm_password_icon').prop("disabled", true);
                $('#add-new-owner-modal').modal('show');
            });

            $(document).on('click','.edit-owner-btn',function() {
                $('#edit_id').val('');
                $('#edit_firstname').val('');
                $('#edit_middlename').val('');
                $('#edit_lastname').val('');
                $('#edit_mobileNo').val('');
                $('#edit_email').val('');
                $('#edit_username').val('');
                $('#edit_password').val('');
                $('#edit_password_confirmation').val();

                $('#update-owner-modal').modal('show');
            });
        });

        function reloadOwnerTable ()
        {
            var table = $('#owners-list').DataTable();
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
