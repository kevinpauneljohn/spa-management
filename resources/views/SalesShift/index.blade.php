@extends('adminlte::page')

@section('title','Sales Shift')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan">Sales Shift</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active"><a href="{{route('payroll.index')}}">Sales Shift</a> </li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive">
                <table id="sales-shit-list" class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Updated At</th>
                            <th>Spa</th>
                            <th>User</th>
                            <th>Start Shift</th>
                            <th>End Shift</th>
                            <th>Start Shift</th>
                            <th>Completed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function(){
            $('#sales-shit-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('sales.shift.lists') !!}',
                columns: [
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'spa_id', name: 'spa_id'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'start_shift', name: 'start_shift'},
                    { data: 'end_shift', name: 'end_shift'},
                    { data: 'start_money', name: 'start_money'},
                    { data: 'completed', name: 'completed'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[3,'desc'],
                pageLength: 10,
            });
        });

        @if(auth()->user()->can('edit sales shift'))
        $(document).on('click','.end-shift',function(){
            $tr = $(this).closest('tr');
            let id = this.id;
            let data = $tr.children('td').map(function () {
                return $(this).text();
            }).get();


            Swal.fire({
                title: 'End Shift?',
                html: 'are you sure you want to end your shift?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.value === true) {

                    $.ajax({
                        url: '/end-shift-by-owner/'+id,
                        type: 'post',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        }
                    }).done(function(response){
                        console.log(response);
                        if(response.success === true)
                        {
                            Swal.fire(response.message, '', 'warning')
                            $('#sales-shit-list').DataTable().ajax.reload(null, false);
                        }
                    }).always(function(){
                    });

                }
            })
        })
        @endif

        @if(auth()->user()->can('delete sales shift'))
        $(document).on('click','.delete-shift',function(){
            $tr = $(this).closest('tr');
            let id = this.id;
            let data = $tr.children('td').map(function () {
                return $(this).text();
            }).get();


            Swal.fire({
                title: 'Delete Shift?',
                html: 'are you sure you want to delete shift for ['+data[2]+']?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.value === true) {

                    $.ajax({
                        url: '/sales-shift/'+id,
                        type: 'delete',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        }
                    }).done(function(response){
                        console.log(response);
                        if(response.success === true)
                        {
                            Swal.fire(response.message, '', 'warning')
                            $('#sales-shit-list').DataTable().ajax.reload(null, false);
                        }
                    }).always(function(){
                    });

                }
            })
        })
        @endif
    </script>
@stop
