@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="text-cyan">{{$title}}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-adminlte-button label="Add" data-toggle="modal" data-target="#createOwner" class="bg-teal" id="add-owner-btn"/>
            </div>
            <div class="card-body">
                <x-admin.owners.owner />
            </div>
        </div>
    </div>

    <form id="register-owner">
        @csrf
    <x-adminlte-modal id="createOwner" title="Register New Owner" size="lg" theme="teal"
                      icon="fas fa-bell" v-centered static-backdrop scrollable>

                    <div class="row mb-2">
                        <div class="col-lg-4 firstname">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" class="form-control" id="firstname">
                        </div>
                        <div class="col-lg-4 middlename">
                            <label for="middlename">Middle Name</label>
                            <input type="text" name="middlename" class="form-control" id="middlename">
                        </div>
                        <div class="col-lg-4 lastname">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="lastname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 email">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" id="email">
                        </div>
                        <div class="col-lg-6 username">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 password">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="col-lg-6 password_confirmation">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div>
                    </div>

        <x-slot name="footerSlot">
            <x-adminlte-button type="submit" class="mr-auto submit-create-btn" theme="success" label="Save"/>
        </x-slot>
    </x-adminlte-modal>
    </form>


@stop
@section('plugins.CustomCSS',true)
@section('plugins.Sweetalert2',true)
@section('css')

@stop

@section('js')
    @if(auth()->check())
        <script>
            let registerForm = $('#register-owner');
            $(document).on('click','#add-owner-btn',function(){
                registerForm.find('.modal-title').text('Register New Owner');
            });

            $(document).on('submit','#register-owner', function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                $.ajax({
                    url: '{{route('owners.index')}}',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        registerForm.find('.text-danger').remove();
                        registerForm.find('.submit-create-btn').attr('disabled',true).text('Saving..');
                    }
                }).done(function(result){
                    console.log(result);
                    if(result.success === true)
                    {
                        Toast.fire({
                            type: 'success',
                            title: data
                        });

                        $('#owners-list').DataTable().ajax.reload(null, false);
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                    $.each(xhr.responseJSON.errors, function (key, value){
                        registerForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                    });
                }).always(function(){
                    registerForm.find('.submit-create-btn').attr('disabled',false).text('Save');
                });
            });
        </script>
    @endif
@stop
