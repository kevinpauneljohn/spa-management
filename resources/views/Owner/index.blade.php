
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <table id="leads-list" class="table table-bordered table-hover" role="grid">
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

@stop

@section('css')
@stop

@section('js')
    <script>
        $(function() {
            $('#leads-list').DataTable({
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
        });
    </script>
@stop
