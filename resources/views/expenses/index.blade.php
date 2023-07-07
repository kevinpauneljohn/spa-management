@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="container-fluid">
        <x-expenses.expenses-date-range :spaId="$spaId"/>
        <x-expenses.add-expenses-button :spaId="$spaId"/>
        <x-expenses.expenses spaId='4ac9aef5-36eb-4606-828b-4f00efa7f3fc'/>
    </div>

    <x-add-spa-form-modal/>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')


@stop
