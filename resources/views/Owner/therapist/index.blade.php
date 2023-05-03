@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1></h1>
@stop
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-gray-dark"><i class="fas fa-spa"></i> {{ucwords($therapist->user->firstname.' '.$therapist->user->lastname)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('spa.show',['spa' => $spa->id])}}">{{ucwords($spa->name)}}</a> </li>
                <li class="breadcrumb-item active">@if($therapist->gender === 'male') Masseur @else Masseuse @endif Profile </li>
            </ol>
        </div>
    </div>

    <x-therapist-profile :therapist="$therapist"/>
@stop

@section('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@stop

@section('js')

@stop
