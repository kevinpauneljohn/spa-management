@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="container-fluid">
        <div class="row">
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
        <div class="row">
            @foreach($spa as $data)
                <div class="col-xl-4 col-lg-6 col-sm-6 col-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h4>{{$data->name}}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted text-sm"><i class="fas fa-lg fa-globe mr-2"></i> {{$data->address}} </p>
                        </div>
                        <div class="card-footer">
                            <div>
                                <a href="{{route('point-of-sale.show', ['point_of_sale' => $data->id])}}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-spa"></i> View @if(!is_null($data->category)) {{ucwords($data->category)}} @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
                $(document).on('click','#api-btn', function(){

                    $.ajax({
                        url: '/test-kevin',
                    }).done( (posts) => {
                        console.log(posts)
                    });
                });
            });
        </script>
    @endif
@stop
