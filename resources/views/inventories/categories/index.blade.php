{{--@extends('adminlte::page', ['iFrameEnabled' => true])--}}
@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')

@stop
@section('content')
    <div class="row mb-2">
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

    <div class="card">
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
