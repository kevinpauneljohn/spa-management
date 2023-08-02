@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="text-cyan">Dashboard</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Spa</li>
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($spa as $data)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-muted border-bottom-0">
                                <!-- Digital Strategist -->
                            </div>
                            <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="lead"><b>{{$data->name}}</b></h2>
                                    <br />
                                    <p class="text-muted text-sm"><i class="fas fa-lg fa-building"></i> <b>Address: </b> {{$data->address}} </p>
                                    <!-- <p class="text-muted text-sm"><i class="fas fa-lg fa-phone"></i> <b>Phone #: </b>+ 800 - 12 12 23 52 </p> -->
                                </div>
                                <!-- <div class="col-5 text-center">
                                    <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar" class="img-circle img-fluid">
                                </div> -->
                            </div>
                            </div>
                            <div class="card-footer">
                            <div class="text-right">
                                <a href="{{route('point-of-sale.show', ['point_of_sale' => $data->id])}}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-spa"></i> View Spa
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
