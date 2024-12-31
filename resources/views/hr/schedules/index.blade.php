@extends('adminlte::page')

@section('title', 'Schedules')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Schedules</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item active">Schedules</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop
<style>

</style>
@section('content')
    <div class="card">
        <div class="card-header">
            <x-adminlte-button label="Add" theme="primary" id="add-schedule-btn" data-toggle="modal" data-target="#add-schedule"/>
        </div>
        <div class="card-body table-responsive">
            <table id="schedule-list" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time out</th>
                    <th>Total Hours</th>
                    <th>Break In</th>
                    <th>Break Out</th>
                    <th>Total Break Minutes</th>
                    <th>Total Hours Less Break</th>
                    <th>Date Added</th>
                    <th>Updated by</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <form class="schedule-form" id="add-schedule-form">
        @csrf
        <div class="modal fade" id="add-schedule" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Add Schedule</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group name">
                            <label>Name</label><span class="required">*</span>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group time_in">
                                    <label>Time In</label><span class="required">*</span>
                                    <input type="time" name="time_in" class="form-control" id="time_in">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group time_out">
                                    <label>Time Out</label><span class="required">*</span>
                                    <input type="time" name="time_out" class="form-control" id="time_out">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group break_in">
                                    <label>Break In</label>
                                    <input type="time" name="break_in" class="form-control" id="break_in">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group break_out">
                                    <label>Break Out</label>
                                    <input type="time" name="break_out" class="form-control" id="break_out">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-primary save-schedule">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function(){
            $('#schedule-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-schedules') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'time_in', name: 'time_in'},
                    { data: 'time_out', name: 'time_out'},
                    { data: 'total_hours', name: 'total_hours'},
                    { data: 'break_in', name: 'break_in'},
                    { data: 'break_out', name: 'break_out'},
                    { data: 'total_break_in_minutes', name: 'total_break_in_minutes'},
                    { data: 'total_hours_less_break', name: 'total_hours_less_break'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                pageLength: 50
            })
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        let scheduleForm = $('.schedule-form');
        let addScheduleForm = $('#add-schedule-form');
        let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        $(document).on('click','#add-schedule-btn', function(){
            scheduleForm.attr('id','add-schedule-form')
            scheduleForm.find('.modal-title').text('Add Schedule');
            scheduleForm.find('input[name=schedule_id]').remove();
            scheduleForm.trigger('reset');
        })
        $(document).on('submit','#add-schedule-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            // console.log(data);
            $.ajax({
                url: '{{route('schedules.store')}}',
                type: 'POST',
                data: data,
                headers: csrf_token,
                beforeSend: function(){
                    addScheduleForm.find('.text-danger').remove();
                    addScheduleForm.find('.save-schedule').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#schedule-list').DataTable().ajax.reload(null, false);
                    addScheduleForm.trigger('reset');
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    addScheduleForm.find('.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>')
                })
            }).always(function(){
                addScheduleForm.find('.save-schedule').attr('disabled',false).text('Save');
            });
        })


        $(document).on('click','.edit-schedule', function(){
            scheduleForm.attr('id','edit-schedule-form');
            addScheduleForm.find('.text-danger').remove();
            $('#add-schedule').modal('toggle');
            scheduleForm.find('.modal-title').text('Edit Schedule');

            let id = this.id;

            $.ajax({
                url: '/schedules/'+id+'/edit',
                type: 'get',
                headers: csrf_token,
                beforeSend: function(){
                    scheduleForm.find('input[name=schedule_id]').remove();
                    scheduleForm.find('input').attr('disabled',true);
                    scheduleForm.find('.save-schedule').attr('disabled',true).text('Loading data');
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    scheduleForm.find('#'+key).val(value);
                })

                scheduleForm.find('#name').val(response.name);
                scheduleForm.append('<input type="hidden" name="schedule_id" value="'+id+'">')
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                scheduleForm.find('input').attr('disabled',false);
                scheduleForm.find('.save-schedule').attr('disabled',false).text('Save');
            })
        })

        let editScheduleForm = $('#edit-schedule-form');
        $(document).on('submit','#edit-schedule-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/schedules/'+data[6].value,
                type: 'put',
                headers: csrf_token,
                data: data,
                beforeSend: function(){
                    addScheduleForm.find('.text-danger').remove();
                    editScheduleForm.find('.save-schedule').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#schedule-list').DataTable().ajax.reload(null, false);
                }
                else if(response.success === false)
                {
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    });
                }
            }).fail(function(xhr, status,error){
                console.log(xhr)
            }).always(function(){
                editScheduleForm.find('.save-schedule').attr('disabled',false).text('Save');
            });
        })

        $(document).on('click','.delete-schedule',function(){
            $tr = $(this).closest('tr');
            let id = this.id;
            console.log(id)
            let data = $tr.children('td').map(function () {
                return $(this).text();
            }).get();

            swal.fire({
                title: "Are you sure you want to delete: "+data[0]+"?",
                text: "Please ensure and then confirm!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes!",
                cancelButtonText: "No!",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajax({
                        url : '/schedules/'+id,
                        type : 'DELETE',
                        headers: csrf_token,
                    }).done(function(response){
                        if(response.success === true)
                        {
                            Toast.fire({
                                type: 'success',
                                title: response.message
                            });
                            $('#schedule-list').DataTable().ajax.reload(null, false);
                        }else if(response.success === false)
                        {
                            Toast.fire({
                                type: 'warning',
                                title: response.message
                            });
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    });
                }
            });
        });
    </script>
@stop
