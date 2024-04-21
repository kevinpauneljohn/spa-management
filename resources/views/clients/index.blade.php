@extends('adminlte::page')

@section('title', 'Client Management')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-3">
            <h3 class="text-cyan">Client Management</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Spa</a> </li>
                <li class="breadcrumb-item active">Client Management</li>
            </ol>
        </div>
    </div>
<div class="card clients-card">
    <div class="card-header">
        <div class="float-right">
            {{--            <label for="search"></label><input type="search" name="search" class="form-control" id="search">--}}
            <form>
                @csrf
                <div class="input-group">
                    <input type="search" name="search_client" class="form-control" id="search_client" placeholder="search here..">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Date of birth</th>
                <th>Address</th>
                <th style="width: 10%"></th>
            </tr>
            <tbody>
                @foreach($clients as $key => $client)
                    <tr>
                        <td>{{ucwords(strtolower($client->full_name))}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->mobile_number}}</td>
                        <td>{{$client->date_of_birth}}</td>
                        <td>{{$client->address}}</td>
                        <td>
                            @can('edit client') <button class="btn btn-sm btn-primary edit-client" id="{{$client->id}}">Edit</button> @endcan
                            @can('delete client') <button class="btn btn-sm btn-danger delete-client" id="{{$client->id}}">Delete</button> @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3 float-left">
            Showing {{$clients->firstItem()}} to {{$clients->lastItem()}} of {{$clients->total()}}
        </div>
        <div class="mt-3 float-right">
            {{$clients->onEachSide(1)->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>
</div>

@can('edit client')
    <!-- Modal -->
    <div class="modal fade client-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <form id="client-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h5 class="modal-title">Edit Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-3">
                            <div class="col-lg-4 firstname">
                                <label for="firstname">First Name</label><span class="required">*</span>
                                <input type="text" name="firstname" class="form-control" id="firstname" />
                            </div><div class="col-lg-4 middlename">
                                <label for="middlename">Middle Name</label>
                                <input type="text" name="middlename" class="form-control" id="middlename" />
                            </div>
                            <div class="col-lg-4 lastname">
                                <label for="lastname">Last Name</label><span class="required">*</span>
                                <input type="text" name="lastname" class="form-control" id="lastname" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4 date_of_birth">
                                <label for="date_of_birth">Date Of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" id="date_of_birth" />
                            </div><div class="col-lg-4 mobile_number">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="tel" name="mobile_number" class="form-control" id="mobile_number" />
                            </div>
                            <div class="col-lg-4 email">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" />
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12 address">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control" id="address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endcan

@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')
    <script>
        $(document).on('click','.pagination a', function(link){
            link.preventDefault();
            let url = $(this).attr('href')

            $('.page-link').attr('disabled',true)
            $('.clients-card').append(`<div class="overlay">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            </div>`)
            window.location.replace(url)
        })

        @can('edit client')
            let clientModal = $('.client-modal')
            let clientId
            $(document).on('click','.edit-client',function(){
                clientId = this.id;
                clientModal.modal('toggle')
                $.ajax({
                    url: '/client/'+clientId,
                    type: 'get',
                    beforeSend: function(){
                        clientModal.find('.modal-header').append(`<div class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>`)
                    }
                }).done(function(response){
                    clientModal.find('#firstname').val(response.client.firstname)
                    clientModal.find('#middlename').val(response.client.middlename)
                    clientModal.find('#lastname').val(response.client.lastname)
                    clientModal.find('#date_of_birth').val(response.client.date_of_birth)
                    clientModal.find('#mobile_number').val(response.client.mobile_number)
                    clientModal.find('#email').val(response.client.email)
                    clientModal.find('#address').val(response.client.address)
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                }).always(function(){
                    clientModal.find('.overlay').remove()
                })
            })

            $(document).on('submit','#client-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray()

                clientModal.find('.is-invalid').removeClass('is-invalid')
                clientModal.find('.text-danger').remove()
                $.ajax({
                    url: '/clients/'+clientId,
                    type: 'patch',
                    data: data,
                    beforeSend: function(){
                        clientModal.find('.modal-header').append(`<div class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>`)
                    }
                }).done(function(response){
                    console.log(response)
                    if(response.success === true)
                    {
                        Swal.fire(response.message, '', 'success')
                        setTimeout(function(){
                            window.location.reload()
                        },1500)
                    }else{
                        Swal.fire(response.message, '', 'warning')
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                    $.each(xhr.responseJSON.errors, function(key, value){
                        clientModal.find('#'+key).addClass('is-invalid').closest('.'+key).append('<p class="text-danger">'+value+'</p>')
                    })

                }).always(function(){
                    clientModal.find('.overlay').remove()
                })
            })
        @endcan
    </script>
@stop
