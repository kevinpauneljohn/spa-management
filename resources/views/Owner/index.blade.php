@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Owner</h1>
@stop
<style>
    .required {
        color: red;
    }

    .errorForm {
        border: 2px solid red !important;
    }

</style>
@section('content')
    <div class="card">
        <div class="card-header">
            @can('add owner')
                <button type="button" class="btn bg-gradient-primary btn-sm" id="addNewOwner"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan
        </div>
        <div class="card-body">
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

                    <tfoot>
                    <tr>
                        <th>Date Added</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Qty of SPA</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add owner')
        <div class="modal fade" id="add-new-owner-modal">
            <form role="form" id="owner-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Owner Registration Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 firstname">
                                    <label for="firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="firstname" id="firstname" class="form-control">
                                </div>
                                <div class="col-lg-4 middlename">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" name="middlename" id="middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 lastname">
                                    <label for="lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-4 mobile_number">
                                    <label for="mobileNo">Mobile No.</label><span class="required">*</span>
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control">
                                </div>
                                <div class="col-lg-4 email">
                                    <label for="email">Email</label><span class="required">*</span>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="col-lg-4 username">
                                    <label for="username">Username</label><span class="required">*</span>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-6 password">
                                    <label for="password">Password</label><span class="required">*</span>
                                    <div class="input-group mb-3" id="show_hide_password">
                                        <input type="password" name="password" id="password" class="form-control">
                                        <button type="button" class="input-group-text password_icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="col-lg-6 password_confirmation">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <div class="input-group mb-3" id="show_hide_confirm_password">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                        <button type="button" class="input-group-text confirm_password_icon"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-owner-btn" value="Save">
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Owner Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 edit_firstname">
                                    <label for="firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="edit_firstname" id="edit_firstname" class="form-control">
                                    <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_middlename">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" name="edit_middlename" id="edit_middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_lastname">
                                    <label for="lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="edit_lastname" id="edit_lastname" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-4 edit_mobile_number">
                                    <label for="mobileNo">Mobile No.</label><span class="required">*</span>
                                    <input type="text" name="edit_mobile_number" id="edit_mobile_number" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_email">
                                    <label for="email">Email</label><span class="required">*</span>
                                    <input type="email" name="edit_email" id="edit_email" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_username">
                                    <label for="username">Username</label><span class="required">*</span>
                                    <input type="text" name="edit_username" id="edit_username" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-owner-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('delete owner')
        <div class="modal fade" id="delete-owner-modal">
            <form role="form" id="delete-owner-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteOwnerId" id="deleteOwnerId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">Delete Owner</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="delete_owner">Delete Owner: <span class="delete-owner-name"></span></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-outline-light delete-owner-modal-btn" value="Delete">
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
                    { data: 'created_at', name: 'created_at'},
                    { data: 'fullname', name: 'fullname'},
                    { data: 'username', name: 'username'},
                    { data: 'email', name: 'email'},
                    { data: 'mobile_number', name: 'mobile_number'},
                    { data: 'qty_of_spa', name: 'qty_of_spa'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewOwner').on('click', function() {
                $('#firstname').val('');
                $('#middlename').val('');
                $('#lastname').val('');
                $('#mobileNo').val('');
                $('#email').val('');
                $('#username').val('');
                $('#password').val('');
                $('#password_confirmation').val();
                
                $('.password_icon').prop("disabled", true);
                $('.confirm_password_icon').prop("disabled", true);
                $('#add-new-owner-modal').modal('show');
            });

            $(document).on('click','.edit-owner-btn',function() {
                $('#update-firstname').val('');
                $('#update-middlename').val('');
                $('#update-lastname').val('');
                $('#update-mobileNo').val('');
                $('#update-email').val('');
                $('#update-username').val('');
                $('#update-password').val('');
                $('#update-password_confirmation').val();

                $('#update-owner-modal').modal('show');
            });

            $(document).on('click','.delete-owner-btn',function() {
                $('#delete-owner-modal').modal('show');
            });
        });

        function reloadOwnerTable ()
        {
            var table = $('#owners-list').DataTable();
            table.ajax.reload();
        }
    </script>
@stop
