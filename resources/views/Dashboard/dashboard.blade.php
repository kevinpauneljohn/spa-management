
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@stop

@section('footer')
    <strong>Copyright © 2023 <a href="https://adminlte.io">DHG IT Solutions</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
