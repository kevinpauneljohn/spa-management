@extends('adminlte::page')

@section('title', 'Spa')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-cyan">Spa Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Spas</li>
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-outline card-info">
                <x-adminlte-button label="Add" data-toggle="modal" data-target="#add-spa" class="bg-gradient-orange text-white" id="add-spa-btn"/>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table id="spa-list" class="table table-striped table-hover border border-2" role="grid" style="width:100%;">
                            <thead>
                            <tr role="row">
                                <th>Date Added</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-add-spa-form-modal/>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop
<style>


</style>
@section('js')
    @if(auth()->check())
        <script src="{{asset('js/clear_errors.js')}}"></script>
        <script src="{{asset('js/alerts.js')}}"></script>
        <script src="{{asset('js/spa/spa.js')}}"></script>
        <script>
            $(document).ready(function(){

                $('#spa-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('owner.list.spas') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at', className: 'text-center' },
                        { data: 'name', name: 'name'},
                        { data: 'address', name: 'address'},
                        { data: 'category', name: 'category'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });
        </script>
    @endif

@stop
