@extends('adminlte::page')

@section('title', 'Inventory Management')

@section('content_header')

@stop

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-4">
            <h3 class="text-info">Inventory Management - {{ucwords($spa)}}</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('inventories.index')}}">Inventory Management</a> </li>
                <li class="breadcrumb-item active">Inventories </li>
            </ol>
        </div>
    </div>

    <div class="card card-info card-outline">
        <div class="card-header">
            @can('add inventory')
                <x-adminlte-button label="Add Item" data-toggle="modal" data-target="#inventory-modal" id="inventory-modal-btn" class="bg-gradient-info float-right"/>
            @endcan
        </div>
        <div class="card-body">
            <x-inventory.inventory-management/>
        </div>
    </div>

@stop

@section('footer')
    <strong>Copyright © 2023 <a href="https://adminlte.io">DHG IT Solutions</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
@stop
@section('css')
@stop

@section('js')

@stop
