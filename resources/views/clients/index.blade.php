@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
{{--    {{$spa->transactions}}--}}
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
