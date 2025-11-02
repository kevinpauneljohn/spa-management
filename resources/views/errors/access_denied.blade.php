@extends('adminlte::page')

@section('title', 'Access Denied')

@section('content_header')
    <div class="row mb-5"></div>
@stop

@section('content')
    <div class="error-page">
        <h2 class="headline text-warning"> 403</h2>

        <div class="error-content">
            <h3 class="text-danger text-bold"><i class="fas fa-exclamation-triangle text-warning"></i> Oops! You're access to the POS was denied.</h3>

            <p>
                Someone was already logged in. If you want to access it, you may request the owner to
                <span class="text-bold text-red">end shift</span> the current logged in front desk officer.
            </p>
        </div>
        <!-- /.error-content -->
    </div>
@stop

@section('css')

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
