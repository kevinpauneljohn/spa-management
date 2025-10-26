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
    <div class="row">
        <div class="col-sm-6">
            <h3 class="text-info">User Permission Management</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Permission</a></li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </div>
    </div>
    <div class="card card-info card-outline">
        <div class="card-header">
            @can('add permission')
                <button type="button" class="btn bg-gradient-orange text-white btn-sm" id="addNewPermission">Add</button>
            @endcan
        </div>
        <div class="card-body table-responsive">
            <table id="permissions-list" class="table table-striped table-hover border border-2" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Name</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    @can('add permission')
        <div class="modal fade" id="add-new-permission-modal">
            <form role="form" id="permission-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Permission Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group permission">
                                <label for="permission">Permission Name</label><span class="required">*</span>
                                <input type="text" name="permission" id="permission" class="form-control">
                            </div>
                            <div class="form-group roles">
                                <label for="roles">Assign Role</label><span class="required">*</span>
                                <select class="form-control role-select" name="roles[]" id="roles" style="width:100%;" multiple="multiple" data-placeholder="Select a role">
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary add-permission-btn" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan

    @can('edit permission')
        <div class="modal fade" id="update-permission-modal">
            <form role="form" id="update-permission-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title permission-title"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_permission">
                                <label for="edit_permission">Permission Name</label><span class="required">*</span>
                                <input type="text" name="edit_permission" id="edit_permission" class="form-control">
                                <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                            </div>
                            <div class="form-group edit_roles">
                                <label for="edit_roles">Assign Role</label><span class="required">*</span>
                                <select class="form-control role-select-edit" name="edit_roles[]" id="edit_roles" style="width:100%;" multiple="multiple" data-placeholder="Select a role">
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary update-permission-btn" value="Save">
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
<script src="{{asset('js/permission.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.role-select').select2();
            $('.role-select-edit').select2();

            $('#permissions-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('permission.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'role', name: 'role'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center', width: '20%'}
                ],
                responsive:true,
                order:[0,'desc']
            });

            $('#addNewPermission').on('click', function() {
                $('#name').val('');

                $('.text-danger').remove();
                $('#add-new-permission-modal').modal('show');
            });

            $(document).on('click','.edit-permission-btn',function() {
                $('.text-danger').remove();
                $('#update-permission-modal').modal('show');
            });
        });

        function reloadPermissionTable ()
        {
            var table = $('#permissions-list').DataTable();
            table.ajax.reload();
        }
    </script>
@stop
