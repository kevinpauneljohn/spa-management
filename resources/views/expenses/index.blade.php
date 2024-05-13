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
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                <li class="breadcrumb-item active">{{ucwords($owner->spas->first()->name)}} </li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <span class="float-left">
                   <x-expenses.expenses-date-range :spaId="$spaId"/>
                </span>
                <span class="float-right">
                    <x-expenses.add-expenses-button :spaId="$spaId"/>
                </span>
            </div>
            <div class="card-body">
                <x-expenses.expenses :spaId='$spaId'/>
            </div>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')


@stop
