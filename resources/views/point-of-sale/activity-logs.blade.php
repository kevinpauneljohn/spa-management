@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')

@stop
<style>

</style>
@section('content')
<div class="row mb-2">
    <div class="col-sm-6 mt-3">
        <h3 class="text-cyan">{{ucwords($pageTitle)}}</h3>
    </div>
    <div class="col-sm-6 mt-3">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
            <li class="breadcrumb-item">{{ucwords($spa->name)}} </li>
            <li class="breadcrumb-item"><a href="{{route('point-of-sale.show',['point_of_sale' => $spa->id])}}">Dashboard</a> </li>
            <li class="breadcrumb-item active">{{ucwords($pageTitle)}} </li>
        </ol>
    </div>
</div>

    <div>
        <a class="btn btn-primary btn-sm" href="{{route('point-of-sale.show',['point_of_sale' => $spa->id])}}">
            <i class="fa fa-arrow-left ml-2" aria-hidden="true"></i>
            Back to Dashboard
        </a>
        <x-point-of-sale.logs.transaction-log :spaId="$spa->id"/>
    </div>

@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')

@stop
