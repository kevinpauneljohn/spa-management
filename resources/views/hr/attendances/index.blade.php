@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Attendances</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item active">Attendances</li>
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
            <x-hr.add-attendance-button />
            <div class="card-tools">

            </div>
        </div>
        <div class="card-body table-responsive">
            <x-hr.attendance ownerId="{{$owner_id}}"/>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')

@stop
