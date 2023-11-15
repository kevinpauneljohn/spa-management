@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <h1></h1>
@stop
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan">{{ucwords($pageTitle)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
            </ol>
        </div>
    </div>

    <div class="card">
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
            </table>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

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
                    ajax: '{!! route('access-payroll-by-spa') !!}',
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
            });
        </script>
    @endif

@stop
