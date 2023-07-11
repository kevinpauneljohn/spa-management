@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan"><i class="fas fa-spa"></i> {{ucwords($spa->name)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('spa.show',['spa' => $spa->id])}}">Spa Profile</a> </li>
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <x-clients.client-calendar :spaId="$spa->id" />
        </div>
    </div>

@stop
@section('plugins.CustomCSS',true)

@section('css')
@stop

@section('js')


@stop
