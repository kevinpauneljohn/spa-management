@extends('adminlte::page')

@section('title', 'Spa')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
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
            <div class="card-body">
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
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
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
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });
        });
    </script>
@stop
