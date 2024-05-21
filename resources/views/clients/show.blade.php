@extends('adminlte::page')

@section('title', 'Client Profile')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')

    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan">{{ucwords($client->full_name)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                <li class="breadcrumb-item active"><a href="{{route('clients.index')}}">Clients</a> </li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Service Amount</th>
                        <th>Payable Amount</th>
                        <th>Commission Reference Amount</th>
                        <th>Status</th>
                        <th>Service Duration</th>
                        <th>Total Time</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Room</th>
                        <th>Masseur</th>
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

@stop
