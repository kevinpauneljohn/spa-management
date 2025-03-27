@extends('adminlte::page')

@section('title', 'Payroll')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payroll</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item active">Payroll</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop
<style>

</style>
@section('content')
    <form id="generate-payroll-form">
        @csrf
        <div class="card">
            <div class="card-header">
                <x-hr.payroll.date-range />
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="generate-payroll-btn">Generate Payroll</button>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-body table-responsive">
            <table id="payroll-list" class="table table-bordered table-hover table-striped" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Start</th>
                    <th>End</th>
{{--                    <th style="width: 10%;">Employee ID</th>--}}
                    <th style="width: 15%;">Name</th>
                    <th style="width: 20%;">Branch</th>
                    <th>Role</th>
                    <th style="width: 10%;">Biometrics User Id</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="view-payroll" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h4 class="modal-title">Pay Slip</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-primary save-employee-to-biometrics-button">Save</button>
                </div>
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
            $('#payroll-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-employees-payroll') !!}',
                columns: [
                    { data: 'date_start', name: 'date_start'},
                    { data: 'date_end', name: 'date_end'},
                    // { data: 'employee_id', name: 'employee_id'},
                    { data: 'name', name: 'name'},
                    { data: 'branch', name: 'branch'},
                    { data: 'role', name: 'role'},
                    { data: 'biometric_user', name: 'biometric_user'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                pageLength: 50
            })
        });

        let generateForm = $('#generate-payroll-form');
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

        $(document).on('submit','#generate-payroll-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            // console.log(data);

            $.ajax({
                url: '/save-payroll',
                method: 'post',
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    generateForm.find('#generate-payroll-btn').attr('disabled',true).text('Generating ...');
                }
            }).done(function(response){
                console.log(response)

                if(response.success === true)
                {
                    $('#payroll-list').DataTable().ajax.reload(null, false);
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    })
                }
                else{
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    })
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                generateForm.find('#generate-payroll-btn').attr('disabled',false).text('Generate Payroll');
            });
        })
    </script>
@stop
