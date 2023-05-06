@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Attendance</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="did-floating-label-content">
            <input class="did-floating-input" type="text" placeholder=" ">
            <label class="did-floating-label">Sale Price</label>
        </div>
    </div>
</div>
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" href="{{asset('AttendanceStyle/style.css')}}">
@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

@stop