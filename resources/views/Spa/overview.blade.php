@extends('adminlte::page')

@section('title', 'Therapist')

@section('content_header')
    <h1>{{$spa['name']}}</h1>
    <input type="hidden" class="form-control spa-id" value="{{$spa['id']}}" />
@stop
<style>
    .required {
        color: red;
    }
</style>
@section('content')
    <div class="card">
        <div class="card-header">
            @can('add therapist')
                <button type="button" class="btn bg-gradient-primary btn-sm" id="addNewTherapist"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan
        </div>
        <div class="card-body">
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

                <tfoot>
                <tr>
                    <th>Date Added</th>
                    <th>Fullname</th>
                    <th>Birth Date</th>
                    <th>Mobile Number</th>
                    <th>Email Address</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @can('add therapist')
        <div class="modal fade" id="add-new-therapist-modal">
            <form role="form" id="spa-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Therapist Form</h4>
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
                                <div class="col-lg-4 date_of_birth">
                                    <label for="date_of_birth">Birth Date</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                                </div>
                                <div class="col-lg-4 mobile_number">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control">
                                </div>
                                <div class="col-lg-4 email">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-4 gender">
                                    <label for="gender">Gender</label><span class="required">*</span>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Select here</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 certificate">
                                    <label for="certificate">Certificate</label>
                                    <input type="text" name="certificate" id="certificate" class="form-control">
                                </div>
                                <div class="col-lg-4 commission">
                                    <label for="commission">Commission</label><span class="required">*</span>
                                    <input type="number" name="commission" id="commission" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-6 allowance">
                                    <label for="allowance">Allowance</label>
                                    <input type="number" name="allowance" id="allowance" class="form-control">
                                </div>
                                <div class="col-lg-6 offer_type">
                                    <label for="offer_type">Offer Type</label><span class="required">*</span>
                                    <input type="text" name="offer_type" id="offer_type" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-therapist-btn" value="Save">
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Therapist Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 edit_firstname">
                                    <label for="edit_firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="edit_firstname" id="edit_firstname" class="form-control">
                                    <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_middlename">
                                    <label for="edit_middlename">Middle Name</label>
                                    <input type="text" name="edit_middlename" id="edit_middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_lastname">
                                    <label for="edit_lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="edit_lastname" id="edit_lastname" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-4 edit_date_of_birth">
                                    <label for="edit_date_of_birth">Birth Date</label>
                                    <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_mobile_number">
                                    <label for="edit_mobile_number">Mobile Number</label>
                                    <input type="text" name="edit_mobile_number" id="edit_mobile_number" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_email">
                                    <label for="edit_email">Email</label>
                                    <input type="email" name="edit_email" id="edit_email" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-4 edit_gender">
                                    <label for="edit_gender">Gender</label><span class="required">*</span>
                                    <select class="form-control" name="edit_gender" id="edit_gender">

                                    </select>
                                </div>
                                <div class="col-lg-4 edit_certificate">
                                    <label for="edit_certificate">Certificate</label>
                                    <input type="text" name="edit_certificate" id="edit_certificate" class="form-control">
                                </div>
                                <div class="col-lg-4 edit_commission">
                                    <label for="edit_commission">Commission</label><span class="required">*</span>
                                    <input type="number" name="edit_commission" id="edit_commission" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-6 edit_allowance">
                                    <label for="edit_allowance">Allowance</label>
                                    <input type="number" name="edit_allowance" id="edit_allowance" class="form-control">
                                </div>
                                <div class="col-lg-6 edit_offer_type">
                                    <label for="edit_offer_type">Offer Type</label><span class="required">*</span>
                                    <input type="text" name="offer_type" id="edit_offer_type" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-therapist-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('delete therapist')
        <div class="modal fade" id="delete-spa-modal">
            <form role="form" id="delete-spa-form" class="form-submit">
                @csrf
                @method('DELETE')
                <input type="hidden" name="deleteSpaId" id="deleteSpaId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">Delete Spa</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="delete_owner">Delete Spa: <span class="delete-spa-name"></span></p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-outline-light delete-spa-modal-btn" value="Delete">
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
                    { data: 'action', name: 'action', orderable: false, searchable: false}
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
        });

        function reloadTherapistTable ()
        {
            var table = $('#therapist-list').DataTable();
            table.ajax.reload();
        }
    </script>
@stop
