@extends('adminlte::page')

@section('title', 'Departments')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Departments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item active">Departments</li>
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
            <x-adminlte-button label="Add" theme="primary" id="add-department-btn" data-toggle="modal" data-target="#add-department"/>
        </div>
        <div class="card-body table-responsive">
            <table id="department-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Name</th>
                    <th>Updated By</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <form class="departments-form" id="add-department-form">
        @csrf
        <div class="modal fade" id="add-department" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">
                        <div class="modal-header bg-olive">
                            <h4 class="modal-title">Add Department</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label>Name</label><span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                            <button type="submit" class="btn btn-primary save-department">Save</button>
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
            $('#department-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('departments.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'user_id', name: 'user_id'},
                    { data: 'updated_at', name: 'updated_at'},
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

        let departmentForm = $('.departments-form');
        let addDepartmentForm = $('#add-department-form');
        let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        $(document).on('click','#add-department-btn', function(){
            departmentForm.attr('id','add-department-form')
            departmentForm.find('.modal-title').text('Add Department');
            departmentForm.find('input[name=department_id]').remove();
            departmentForm.trigger('reset');
        })
        $(document).on('submit','#add-department-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            // console.log(data);
            $.ajax({
                url: '{{route('departments.store')}}',
                type: 'POST',
                data: data,
                headers: csrf_token,
                beforeSend: function(){
                    addDepartmentForm.find('.text-danger').remove();
                    addDepartmentForm.find('.save-department').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#department-list').DataTable().ajax.reload(null, false);
                    addDepartmentForm.trigger('reset');
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    addDepartmentForm.find('.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>')
                })
            }).always(function(){
                addDepartmentForm.find('.save-department').attr('disabled',false).text('Save');
            });
        })


        $(document).on('click','.edit-department', function(){
            departmentForm.attr('id','edit-department-form');
            addDepartmentForm.find('.text-danger').remove();
            $('#add-department').modal('toggle');
            departmentForm.find('.modal-title').text('Edit Department');

            let id = this.id;

            $.ajax({
                url: '/departments/'+id+'/edit',
                type: 'get',
                headers: csrf_token,
                beforeSend: function(){
                    departmentForm.find('input[name=department_id]').remove();
                    departmentForm.find('input').attr('disabled',true);
                    departmentForm.find('.save-department').attr('disabled',true).text('Loading data');
                }
            }).done(function(response){
                departmentForm.find('#name').val(response.name);
                departmentForm.append('<input type="hidden" name="department_id" value="'+id+'">')
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                departmentForm.find('input').attr('disabled',false);
                departmentForm.find('.save-department').attr('disabled',false).text('Save');
            })
        })

        let editDepartmentForm = $('#edit-department-form');
        $(document).on('submit','#edit-department-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/departments/'+data[2].value,
                type: 'put',
                headers: csrf_token,
                data: data,
                beforeSend: function(){
                    addDepartmentForm.find('.text-danger').remove();
                    editDepartmentForm.find('.save-department').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#department-list').DataTable().ajax.reload(null, false);
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
                editDepartmentForm.find('.save-department').attr('disabled',false).text('Save');
            });
        })

        $(document).on('click','.delete-department',function(){
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
                        url : '/departments/'+id,
                        type : 'DELETE',
                        headers: csrf_token,
                    }).done(function(response){
                        if(response.success === true)
                        {
                            Toast.fire({
                                type: 'success',
                                title: response.message
                            });
                            $('#department-list').DataTable().ajax.reload(null, false);
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
