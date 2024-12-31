@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Employees</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item active">Employees</li>
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
            <x-adminlte-button label="Add" theme="primary" id="add-employee-btn" data-toggle="modal" data-target="#add-employee"/>
        </div>
        <div class="card-body table-responsive">
            <table id="employee-list" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Mobile Number</th>
                    <th>Date of birth</th>
                    <th>Spa</th>
                    <th>Role</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <form class="employee-form" id="add-employee-form">
        @csrf
        <div class="modal fade" id="add-employee" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Add Employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group firstname">
                                    <label>First Name</label><span class="required">*</span>
                                    <input type="text" name="firstname" class="form-control" id="firstname">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group middlename">
                                    <label>Middle Name</label>
                                    <input type="text" name="middlename" class="form-control" id="middlename">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group lastname">
                                    <label>last Name</label><span class="required">*</span>
                                    <input type="text" name="lastname" class="form-control" id="lastname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group email">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" id="email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group username">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" id="username">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mobile_number">
                                    <label>Mobile Number</label>
                                    <input type="text" name="mobile_number" class="form-control" id="mobile_number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group date_of_birth">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" id="date_of_birth">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group spa_id">
                                    <label>Spa</label>
                                    <select class="form-control" name="spa_id" id="spa_id">
                                        <option value=""> -- Select Spa -- </option>
                                        @foreach($spas as $spa)
                                            <option value="{{$spa->id}}">{{ucwords($spa->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group role">
                                    <label>Role</label><span class="required">*</span>
                                    <select class="form-control" name="role" id="role">
                                        <option value=""> -- Select Role -- </option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}">{{ucwords($role->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-primary save-employee">Save</button>
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
            $('#employee-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-employees') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'username', name: 'username'},
                    { data: 'mobile_number', name: 'mobile_number'},
                    { data: 'date_of_birth', name: 'date_of_birth'},
                    { data: 'spa', name: 'spa'},
                    { data: 'role', name: 'role'},
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

        let employeeForm = $('.employee-form');
        let addEmployeeForm = $('#add-employee-form');
        let csrf_token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        $(document).on('click','#add-employee-btn', function(){
            employeeForm.attr('id','add-employee-form')
            employeeForm.find('.modal-title').text('Add Employee');
            employeeForm.find('input[name=employee_id]').remove();
            employeeForm.trigger('reset');
        })
        $(document).on('submit','#add-employee-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '{{route('employees.store')}}',
                type: 'POST',
                data: data,
                headers: csrf_token,
                beforeSend: function(){
                    addEmployeeForm.find('.text-danger').remove();
                    addEmployeeForm.find('.save-employee').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){

                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#employee-list').DataTable().ajax.reload(null, false);
                    addEmployeeForm.trigger('reset');
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    addEmployeeForm.find('.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>')
                })
            }).always(function(){
                addEmployeeForm.find('.save-employee').attr('disabled',false).text('Save');
            });
        })


        $(document).on('click','.edit-employee', function(){
            employeeForm.attr('id','edit-employee-form');
            addEmployeeForm.find('.text-danger').remove();
            $('#add-employee').modal('toggle');
            employeeForm.find('.modal-title').text('Edit Employee');

            let id = this.id;

            $.ajax({
                url: '/employees/'+id+'/edit',
                type: 'get',
                headers: csrf_token,
                beforeSend: function(){
                    employeeForm.find('input[name=employee_id]').remove();
                    employeeForm.find('input').attr('disabled',true);
                    employeeForm.find('.save-employee').attr('disabled',true).text('Loading data');
                }
            }).done(function(response){

                $.each(response.user, function(key, value){
                    employeeForm.find('#'+key).val(value);
                })

                employeeForm.find('#role').val(response.role[0]).change();

                employeeForm.find('#name').val(response.name);
                employeeForm.append('<input type="hidden" name="employee_id" value="'+id+'">')
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                employeeForm.find('input').attr('disabled',false);
                employeeForm.find('.save-employee').attr('disabled',false).text('Save');
            })
        })

        let editEmployeeForm = $('#edit-employee-form');
        $(document).on('submit','#edit-employee-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            console.log(data)
            $.ajax({
                url: '/employees/'+data[10].value,
                type: 'put',
                headers: csrf_token,
                data: data,
                beforeSend: function(){
                    addEmployeeForm.find('.text-danger').remove();
                    editEmployeeForm.find('.save-employee').attr('disabled',true).text('Saving...');
                }
            }).done(function(response){
                // console.log(response)
                if(response.success === true)
                {
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    });
                    $('#employee-list').DataTable().ajax.reload(null, false);
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
                $.each(xhr.responseJSON.errors, function(key, value){
                    console.log(key)
                    employeeForm.find('.'+key).append('<p class="text-danger error-'+key+'">'+value+'</p>')
                })
            }).always(function(){
                editEmployeeForm.find('.save-employee').attr('disabled',false).text('Save');
            });
        })

        $(document).on('click','.delete-employee',function(){
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
                        url : '/employees/'+id,
                        type : 'DELETE',
                        headers: csrf_token,
                    }).done(function(response){
                        if(response.success === true)
                        {
                            Toast.fire({
                                type: 'success',
                                title: response.message
                            });
                            $('#employee-list').DataTable().ajax.reload(null, false);
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
