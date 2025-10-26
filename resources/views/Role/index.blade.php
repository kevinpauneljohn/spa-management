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
            <h3 class="text-info">User Role Management</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Role</a></li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </div>
    </div>
    <div class="card card-info card-outline">
        <div class="card-header">
            @can('add role')
                <button type="button" class="btn bg-gradient-orange text-white btn-sm" id="addNewRole">Add</button>
            @endcan
        </div>
        <div class="card-body table-responsive">
            <table id="roles-list" class="table table-striped table-hover border border-2" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @can('add role')
        <div class="modal fade" id="add-new-role-modal">
            <form role="form" id="role-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-info">
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
                            <button type="button" class="btn bg-gradient-orange text-white" data-dismiss="modal">Close</button>
                            <input type="button" class="btn bg-gradient-info add-role-btn" value="Save">
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
                        <div class="modal-header bg-gradient-info">
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
                            <button type="button" class="btn bg-gradient-orange text-white" data-dismiss="modal">Close</button>
                            <input type="button" class="btn bg-gradient-info update-role-btn" value="Save">
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
