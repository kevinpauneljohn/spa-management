@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Employee Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item"><a href="{{route('employees.index')}}">Employees</a></li>
                        <li class="breadcrumb-item active">{{ucwords($employee->user->fullname)}}</li>

                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop
<style>

</style>
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-olive card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
{{--                                <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">--}}
                                <img class="profile-user-img img-fluid img-circle" src="{{asset('vendor/adminlte/dist/img/user2-160x160.jpg')}}" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ucwords($employee->user->fullname)}}</h3>

                            <p class="text-muted text-center">
                                @foreach($employee->user->getRoleNames() as $role)
                                    <span class="badge badge-info mr-1">{{$role}}</span>
                                @endforeach
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Date Onboarded:</b> <a class="float-right">{{$employee->created_at->format('Y-m-d h:i:s a')}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Branch:</b> <a class="float-right">{{!is_null($employee->user->spa_id) ? $employee->user->spa->name : ''}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Biometrics ID:</b> <a class="float-right">{{!is_null($employee->biometric) ? $employee->biometric->userid : ''}}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-olive">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Date Of Birth</strong>

                            <p class="text-muted">
                                {{!is_null($employee->user->date_of_birth) ? \Carbon\Carbon::parse($employee->user->date_of_birth)->format('Y-M-d'): ''}}
                            </p>

                            <hr>

                            <strong><i class="fas fa-envelope mr-1"></i> Email</strong>

                            <p class="text-muted">{{!is_null($employee->user->email) ? $employee->user->email : ''}}</p>

                            <hr>

                            <strong><i class="fas fa-envelope mr-1"></i> Username</strong>

                            <p class="text-muted">{{!is_null($employee->user->username) ? $employee->user->username : ''}}</p>

                            <hr>

                            <strong><i class="fas fa-mobile-alt mr-1"></i> Mobile Number</strong>

                            <p class="text-muted">{{!is_null($employee->user->mobile_number) ? $employee->user->mobile_number : ''}}</p>

                            <hr>



                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#attendance" data-toggle="tab">Attendance</a></li>
                                <li class="nav-item"><a class="nav-link" href="#schedule" data-toggle="tab">Schedule</a></li>
                                <li class="nav-item"><a class="nav-link" href="#benefits" data-toggle="tab">Benefits</a></li>
                                <li class="nav-item"><a class="nav-link" href="#job-description" data-toggle="tab">Job Description</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="attendance">
                                    <x-hr.add-attendance-button />
                                    <x-hr.attendance.attendance-date-range />
                                    <x-hr.attendance :employee="$employee" :ownerId="$employee->owner_id"/>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="benefits">
                                    <x-hr.benefits :employee="$employee"/>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="schedule">
                                    <x-hr.schedule-settings-form :employee="$employee"/>
                                </div>
                                <div class="tab-pane" id="job-description">
                                    Job Description
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>



@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')

@stop
