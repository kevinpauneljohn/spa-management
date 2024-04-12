@extends('adminlte::page')

@section('title', 'Staff')

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
    .select2-results {
        color: #000 !important;
    }
    .select2-container--default .select2-selection--single {
        height: 40px !important;
    }
    .select2-selection__clear{
        margin-right: -10px;
        margin-top: -7px;
    }
    /* .select2-container .select2-selection--single */
</style>
@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="text-cyan">Staff Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Staffs</li>
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @can('add staff')
                            <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewStaff"><i class="fa fa-plus-circle"></i> Add New</button>
                        @endcan
                    </div>
                </div><br />
                <div class="row">
                    <div class="col-md-12">
                        <table id="staff-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                            <thead>
                            <tr role="row">
                                <th>Date Added</th>
                                <th>Spa</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Mobile Number</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('add staff')
        <div class="modal fade" id="add-new-staff-modal">
            <form role="form" id="staff-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Staff Registration Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-add">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#role-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="role-part" id="role-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Role & Spa</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#basic-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="basic-part" id="basic-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Basic Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="contact-part" id="contact-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Contact Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#credential-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="credential-part" id="credential-part-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Credentials</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="role-part" class="content" role="tabpanel" aria-labelledby="role-part-trigger">
                                        <div class="form-group role">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="role">Role</label><span class="required">*</span>
                                                    <select name="role" id="role" class="form-control select-role" style="width:100%;">

                                                    </select>
                                                    <input type="hidden" id="selected-role" class="form-control selected-role">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="spa">Spa</label><span class="required">*</span>
                                                    <select name="spa" id="spa" class="form-control select-spa" style="width:100%;">

                                                    </select>
                                                    <input type="hidden" id="selected-spa" class="form-control selected-spa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                        <div class="form-group gender hiddenBtn">
                                            <label for="gender">Gender</label><span class="required">*</span>
                                            <select name="gender" class="form-control gender" id="gender">
                                                <option value="">-- Choose Gender --</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group certificate hiddenBtn">
                                            <label for="certificate">Certificate</label>
                                            <select name="certificate" class="form-control certificate" id="certificate">
                                                <option value="">-- Choose Certificate --</option>
                                                @foreach($certificate_type as $certificate)
                                                    <option value="{{$certificate}}">{{strtoupper($certificate)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group offer_type_div hiddenBtn">
                                            <label for="offer_type">Offer Type</label><span class="required">*</span>
                                            <select name="offer_type" class="form-control offer_type" id="offer_type">
                                                <option value="">-- Choose Offer Type --</option>
                                                @foreach($offer_type as $offer)
                                                    <option value="{{$offer}}">{{ucfirst(str_replace('_', ' ', $offer))}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group offer_type_field hiddenBtn">
                                            <div class="row">
                                                <div class="col-md-6 commissionDiv hiddenBtn">
                                                    <label for="commission">Commission</label>
                                                    <input type="number" class="form-control commission" id="commission">
                                                </div>
                                                <div class="col-md-6 allowanceDiv hiddenBtn">
                                                    <label for="allowance">Allowance</label>
                                                    <input type="number" class="form-control allowance" id="allowance">
                                                </div>
                                            </div>
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
                                            <input type="text" name="username" class="form-control" id="username" autocomplete="false">
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 password">
                                                    <label for="password">Password</label><span class="required">*</span>
                                                    <div class="input-group mb-3" id="show_hide_password">
                                                        <input type="password" name="password" id="password" class="form-control" autocomplete="false">
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
                            <button type="button" class="btn btn-primary role_info_next_btn">Next</button>
                            <button type="button" class="btn btn-default basic_info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary basic_info_next_btn hiddenBtn" disabled>Next</button>
                            <button type="button" class="btn btn-default contact_info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary contact_info_next_btn hiddenBtn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default credential_info_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary credential_info_submit_btn add-staff-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit staff')
        <div class="modal fade" id="update-staff-modal">
            <form role="form" id="update-staff-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title modalUpdateTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-update">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#edit-role-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-role-part" id="edit-role-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Role & Spa</span>
                                        </button>
                                    </div>
                                    <div class="step" data-target="#edit-basic-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-basic-part" id="edit-basic-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Basic Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-contact-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-contact-part" id="edit-contact-part-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Contact Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-credential-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-credential-part" id="edit-credential-part-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Credentials</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="edit-role-part" class="content" role="tabpanel" aria-labelledby="edit-role-part-trigger">
                                        <div class="form-group edit_role">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="edit_role">Role</label><span class="required">*</span>
                                                    <select name="edit_role" id="edit_role" class="form-control select-edit-role" style="width:100%;">

                                                    </select>
                                                    <input type="hidden" id="selected-edit-role" class="form-control selected-edit-role">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="edit_spa">Spa</label><span class="required">*</span>
                                                    <select name="edit_spa" id="edit_spa" class="form-control select-edit-spa" style="width:100%;">

                                                    </select>
                                                    <input type="hidden" id="selected-edit-spa" class="form-control selected-edit-spa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                        <div class="form-group edit_gender hiddenBtn">
                                            <label for="edit_gender">Gender</label><span class="required">*</span>
                                            <select name="edit_gender" class="form-control edit_gender" id="edit_gender">
                                                <option value="">-- Choose Gender --</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <input type="hidden" name="edit_gender_data" class="form-control edit_gender_data" id="edit_gender_data">
                                        </div>
                                        <div class="form-group edit_certificate hiddenBtn">
                                            <label for="edit_certificate">Certificate</label>
                                            <select name="edit_certificate" class="form-control edit_certificate" id="edit_certificate">
                                                <option value="">-- Choose Certificate --</option>
                                                @foreach($certificate_type as $certificate)
                                                    <option value="{{$certificate}}">{{strtoupper($certificate)}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="edit_certificate_data" class="form-control edit_certificate_data" id="edit_certificate_data">
                                        </div>
                                        <div class="form-group edit_offer_type_div hiddenBtn">
                                            <label for="edit_offer_type">Offer Type</label><span class="required">*</span>
                                            <select name="edit_offer_type" class="form-control edit_offer_type" id="edit_offer_type">
                                                <option value="">-- Choose Offer Type --</option>
                                                @foreach($offer_type as $offer)
                                                    <option value="{{$offer}}">{{ucfirst(str_replace('_', ' ', $offer))}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="edit_offer_type_data" class="form-control edit_offer_type_data" id="edit_offer_type_data">
                                            <input type="hidden" name="edit_therapist_id" class="form-control edit_therapist_id" id="edit_therapist_id">
                                        </div>
                                        <div class="form-group edit_offer_type_field hiddenBtn">
                                            <div class="row">
                                                <div class="col-md-6 edit_commissionDiv hiddenBtn">
                                                    <label for="edit_commission">Commission</label>
                                                    <input type="number" class="form-control edit_commission" id="edit_commission">
                                                    <input type="hidden" name="edit_commission_data" class="form-control edit_commission_data" id="edit_commission_data">
                                                </div>
                                                <div class="col-md-6 edit_allowanceDiv hiddenBtn">
                                                    <label for="edit_allowance">Allowance</label>
                                                    <input type="number" class="form-control edit_allowance" id="edit_allowance">
                                                    <input type="hidden" name="edit_allowance_data" class="form-control edit_allowance_data" id="edit_allowance_data">
                                                </div>
                                            </div>
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
                            <button type="button" class="btn btn-primary edit_role_info_next_btn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_basic_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_basic_info_next_btn hiddenBtn">Next</button>
                            <button type="button" class="btn btn-default edit_contact_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_contact_info_next_btn hiddenBtn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_credential_info_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_credential_info_submit_btn update-staff-btn hiddenBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('change staff password')
        <div class="modal fade" id="change-password-modal">
            <form role="form" id="change-password-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Change Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-responsive-lg mb-4 table-bordered">
                                <tr>
                                    <td style="width: 10%">Staff</td>
                                    <td id="staff-name" style="width: 40%">Staff name here</td>
                                    <td style="width: 10%">Role</td>
                                    <td id="role" style="width: 40%">Role here</td>
                                </tr>
                            </table>
                            <div class="form-group new_password">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" id="new_password">
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation">Re-type Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary change-password-btn">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@stop
@section('plugins.BsStepper',true)
@section('css')
@stop

@section('js')
    <script src="{{asset('js/staff.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('.select-role').select2();

            $('#staff-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('owner.list.staffs') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'spa', name: 'spa'},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'mobile', name: 'mobile'},
                    { data: 'position', name: 'position'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewStaff').on('click', function() {
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
                $('#add-new-staff-modal').modal('show');

                loadRole('new', 0);
                loadSpa('new', 0);
            });

            $(document).on('click','.edit-staff-btn',function() {
                $('#edit_id').val('');
                $('#edit_firstname').val('');
                $('#edit_middlename').val('');
                $('#edit_lastname').val('');
                $('#edit_mobileNo').val('');
                $('#edit_email').val('');
                $('#edit_username').val('');
                $('#edit_password').val('');
                $('#edit_password_confirmation').val();

                $('#update-staff-modal').modal('show');
            });
        });

        function reloadStaffTable ()
        {
            var table = $('#staff-list').DataTable();
            table.ajax.reload();
        }

        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('#bs-stepper-add'))
        });

        document.addEventListener('DOMContentLoaded', function () {
            window.steppers = new Stepper(document.querySelector('#bs-stepper-update'))
        });


        @can('change staff password')
            let changePasswordModal = $('#change-password-modal')
            let staffId;
            $(document).on('click','.change-staff-password-btn',function(){
            staffId = this.id;
            let $tr = $(this).closest('tr');

            let data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

                changePasswordModal.find('#staff-name').text(data[2])
                changePasswordModal.find('#role').text(data[5])
                changePasswordModal.modal('show')
            })

            $(document).on('submit','#change-password-form', function(form){
                form.preventDefault();
                let data = $(this).serializeArray()

                $.ajax({
                    url: '/staff/'+staffId+'/change-password',
                    type: 'put',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    beforeSend: function(){
                        changePasswordModal.find('.is-invalid').removeClass('is-invalid')
                        changePasswordModal.find('.text-danger').remove()

                        changePasswordModal.find('.change-password-btn').attr('disabled',true).text('Updating...')
                    }
                }).done(function(response){
                    console.log(response)
                    if(response.success === true)
                    {
                        swal.fire(response.message, "", "success");
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                    if(xhr.status === 403)
                    {
                        swal.fire(xhr.responseJSON.message, xhr.statusText, "warning");
                    }

                    $.each(xhr.responseJSON.errors,function(key, value){
                        console.log(key)
                        changePasswordModal.find('#'+key).addClass('is-invalid')
                        changePasswordModal.find('.'+key).append('<p class="text-danger">'+value+'</p>')
                    })
                }).always(function(){
                    changePasswordModal.find('.change-password-btn').attr('disabled',false).text('Update')
                })
            })
        @endcan
    </script>
@stop
