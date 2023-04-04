@extends('adminlte::page')

@section('title', 'Spa')

@section('content_header')
    <h1></h1>
@stop
<style>
    .required {
        color: red;
    }
</style>
@section('content')
    <div class="card">
        <div class="card-header">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Spa Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('owners')}}">Owners</a></li>
                            <li class="breadcrumb-item active">Spa</li>
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
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">List</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#licenses" data-toggle="tab">Licenses</a></li>
                                    </ul>
                                </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @can('add spa')
                                                    <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewSpa"><i class="fa fa-plus-circle"></i> Add New</button>
                                                @endcan
                                            </div>
                                        </div><br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="spa-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Date Added</th>
                                                            <th>Name</th>
                                                            <th>Address</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="settings">

                                    </div>

                                    <div class="tab-pane" id="licenses">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @can('add spa')
        <div class="modal fade" id="add-new-spa-modal">
            <form role="form" id="spa-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Spa Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label><span class="required">*</span>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label><span class="required">*</span>
                                <input type="text" name="address" id="address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="number_of_rooms">Number of Rooms</label><span class="required">*</span>
                                <input type="number" min="1" name="number_of_rooms" id="number_of_rooms" class="form-control">
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
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title spa-title"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_name">Name</label><span class="required">*</span>
                                <input type="text" name="edit_name" id="edit_name" class="form-control">
                                <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="edit_address">Address</label><span class="required">*</span>
                                <input type="text" name="edit_address" id="edit_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="edit_number_of_rooms">Number of Rooms</label><span class="required">*</span>
                                <input type="number" min="1" name="edit_number_of_rooms" id="edit_number_of_rooms" class="form-control">
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
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
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

                $('.text-danger').remove();
                $('#add-new-spa-modal').modal('show');
            });

            $(document).on('click','.edit-spa-btn',function() {
                $('#edit_id').val('');
                $('#edit_name').val('');
                $('#edit_address').val('');
                $('#edit_number_of_rooms').val('');
                $('#edit_license').val('');

                $('.text-danger').remove();
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
