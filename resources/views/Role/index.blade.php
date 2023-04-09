@extends('adminlte::page')

@section('title', 'Permission')

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
                            <h1>User Role Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Role</a></li>
                            <li class="breadcrumb-item active">List</li>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @can('add role')
                                                    <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewRole"><i class="fa fa-plus-circle"></i> Add New</button>
                                                @endcan
                                            </div>
                                        </div><br />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="roles-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Name</th>
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
            </section>
        </div>
    </div>

    @can('add role')
        <div class="modal fade" id="add-new-role-modal">
            <form role="form" id="role-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Role Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label for="name">Role Name</label><span class="required">*</span>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-role-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit role')
        <div class="modal fade" id="update-role-modal">
            <form role="form" id="update-role-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title role-title"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_name">
                                <label for="edit_name">Role Name</label><span class="required">*</span>
                                <input type="text" name="edit_name" id="edit_name" class="form-control">
                                <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-role-btn" value="Save">
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
<script src="{{asset('js/role.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.role-select').select2();
            $('.role-select-edit').select2();

            $('#roles-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('role.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center', width: '20%'}
                ],
                responsive:true,
                order:[0,'desc']
            });

            $('#addNewRole').on('click', function() {
                $('#name').val('');

                $('.text-danger').remove();
                $('#add-new-role-modal').modal('show');
            });

            $(document).on('click','.edit-role-btn',function() {
                $('.text-danger').remove();
                $('#update-role-modal').modal('show');
            });
        });

        function reloadRoleTable ()
        {
            var table = $('#roles-list').DataTable();
            table.ajax.reload();
        }
    </script>
@stop
