@extends('adminlte::page')

@section('title', 'Inventory Management')

@section('content_header')

@stop

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-4">
            <h3 class="text-info">{{$spa->name}} - Inventories</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                @if(auth()->user()->hasRole(['admin','owner']))
                    <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                @else
                    <li class="breadcrumb-item"><a href="{{route('spa.show',['spa' => $spa->id])}}">{{$spa->name}}</a> </li>
                @endif

                <li class="breadcrumb-item active">Inventories </li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <x-inventory.inventory-management :spaId="$spa->id"/>
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
