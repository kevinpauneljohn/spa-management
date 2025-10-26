{{--@extends('adminlte::page', ['iFrameEnabled' => true])--}}
@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')

@stop
@section('content')
    <div class="row">
        <div class="col-sm-6 mt-4">
            <h3 class="text-info">Inventory Categories</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('inventories.index')}}">Inventory Management</a> </li>
                <li class="breadcrumb-item active">Categories </li>
            </ol>
        </div>
    </div>
    <div class="alert alert-default-info">
        <h5><i class="fas fa-info"></i> Note:</h5>
        Add Categories to your inventory
    </div>
    <div class="card card-info card-outline">
        <div class="card-header">
            @can('add inventory')
                <x-adminlte-button label="Add Category" data-toggle="modal" data-target="#category-modal" id="category-modal-btn" class="bg-gradient-orange text-white float-right"/>
            @endcan
        </div>
        <div class="card-body">
            <x-inventory.inventory-category/>
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
