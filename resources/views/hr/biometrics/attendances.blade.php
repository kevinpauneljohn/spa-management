@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Employees</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">HR</li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@stop
<style>

</style>
@section('content')
<div class="card">
    <div class="card-header">
        <x-adminlte-button label="Add" theme="primary" id="add-employee-btn" data-toggle="modal" data-target="#add-employee"/>
        <div class="card-tools">
            <button type="button" class="btn btn-info test-biometrics-connection-button">Test Biometrics Connection</button>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table id="attendance-list" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                <th>UID</th>
                <th>Name</th>
                <th>Timestamp</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<form class="employee-form" id="add-employee-form">
    @csrf
    <div class="modal fade" id="add-employee" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h4 class="modal-title">Add Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group firstname">
                                <label>First Name</label><span class="required">*</span>
                                <input type="text" name="firstname" class="form-control" id="firstname">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group middlename">
                                <label>Middle Name</label>
                                <input type="text" name="middlename" class="form-control" id="middlename">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group lastname">
                                <label>last Name</label><span class="required">*</span>
                                <input type="text" name="lastname" class="form-control" id="lastname">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group email">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group username">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" id="username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mobile_number">
                                <label>Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" id="mobile_number">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group date_of_birth">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" id="date_of_birth">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-primary save-employee">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>


@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#attendance-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('get-employee-biometrics-attendance') !!}',
            columns: [
                { data: 'uid', name: 'uid'},
                { data: 'name', name: 'name'},
                { data: 'timestamp', name: 'timestamp'},
                { data: 'type', name: 'type'},
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            responsive:true,
            pageLength: 50
        })
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


</script>
@stop
