@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>asdf</h1>
@stop
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan"><i class="fas fa-spa"></i> {{ucwords($therapist->firstname.' '.$therapist->lastname)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('spa.show',['id' => $spa->id])}}">Spa</a> </li>
                <li class="breadcrumb-item active">asf </li>
            </ol>
        </div>
    </div>


@stop

@section('css')
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@stop

@section('js')

@stop
