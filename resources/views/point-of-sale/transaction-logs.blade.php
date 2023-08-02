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
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>

    <x-point-of-sale.logs.transaction-log :spaId="$spa->id"/>


@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')
    @if(auth()->check())
        <script>
            $(document).ready(function(){

            });
        </script>
    @endif
@stop
