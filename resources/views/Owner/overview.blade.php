@extends('adminlte::page')

@section('title', 'Spa')

@section('content_header')
    <h1>{{$owners['firstname']}} {{$owners['lastname']}}</h1>
    <input type="hidden" class="form-control user-id" value="{{$owners['id']}}" />
@stop
<style>
    .required {
        color: red;
    }
</style>
@section('content')
    <div class="card">
        <div class="card-header">
            @can('add spa')
                <button type="button" class="btn bg-gradient-primary btn-sm" id="addNewSpa"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan
        </div>
        <div class="card-body">
            <table id="spa-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Date Added</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>Date Added</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @can('add spa')
        <div class="modal fade" id="add-new-spa-modal">
            <form role="form" id="spa-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Spa Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6 name">
                                    <label for="name">Name</label><span class="required">*</span>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="col-lg-6 address">
                                    <label for="address">Address</label><span class="required">*</span>
                                    <input type="text" name="address" id="address" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-6 number_of_rooms">
                                    <label for="number_of_rooms">Number of Rooms</label><span class="required">*</span>
                                    <input type="number" min="1" name="number_of_rooms" id="number_of_rooms" class="form-control">
                                </div>
                                <!-- <div class="col-lg-6 email">
                                    <label for="email">License</label>
                                    <input type="text" name="license" id="license" class="form-control">
                                </div> -->
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-spa-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit spa')
        <div class="modal fade" id="update-spa-modal">
            <form role="form" id="update-spa-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Spa Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6 edit_name">
                                    <label for="edit_name">Name</label><span class="required">*</span>
                                    <input type="text" name="edit_name" id="edit_name" class="form-control">
                                    <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                </div>
                                <div class="col-lg-6 edit_address">
                                    <label for="edit_address">Address</label>
                                    <input type="text" name="edit_address" id="edit_address" class="form-control">
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-lg-6 edit_number_of_rooms">
                                    <label for="edit_number_of_rooms">Mobile No.</label><span class="required">*</span>
                                    <input type="number" min="1" name="edit_number_of_rooms" id="edit_number_of_rooms" class="form-control">
                                </div>
                                <!-- <div class="col-lg-4 edit_license">
                                    <label for="edit_license">License</label><span class="required">*</span>
                                    <input type="text" name="edit_license" id="edit_license" class="form-control">
                                </div> -->
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-spa-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('delete spa')
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
<script src="{{asset('js/spa.js')}}"></script>
    <script>
        $(document).ready(function() {
            var user_id = $('.user-id').val();
            $('#spa-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('spa.lists', ['id' => $owners["id"]]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewSpa').on('click', function() {
                $('#name').val('');
                $('#address').val('');
                $('#number_of_rooms').val('');
                $('#license').val('');
                $('#add-new-spa-modal').modal('show');
            });

            $(document).on('click','.edit-spa-btn',function() {
                $('#edit_id').val('');
                $('#edit_name').val('');
                $('#edit_address').val('');
                $('#edit_number_of_rooms').val('');
                $('#edit_license').val('');

                $('#update-spa-modal').modal('show');
            });
        });

        function reloadSpaTable ()
        {
            var table = $('#spa-list').DataTable();
            table.ajax.reload();
        }
    </script>
@stop
