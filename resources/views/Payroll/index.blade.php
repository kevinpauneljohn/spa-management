@extends('adminlte::page')

@section('title', 'Payroll')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')

    <div class="container-fluid">
        <x-payroll.payroll-table spaId="{{\App\Models\Spa::where('name','Thai Khun Lounge & Spa')->first()->id}}" />
    </div>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')


@stop
