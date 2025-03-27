@extends('adminlte::page')

@section('title', 'Payroll')

@section('content_header')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payslip</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">HR</li>
                        <li class="breadcrumb-item"><a href="{{route('employees-payroll')}}">Payroll</a></li>
                        <li class="breadcrumb-item active">Payslip</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop
<style>

</style>
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-olive card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                {{--                                <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">--}}
                                <img class="profile-user-img img-fluid img-circle" src="{{asset('vendor/adminlte/dist/img/user2-160x160.jpg')}}" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ucwords($employee->user->fullname)}}</h3>

                            <p class="text-muted text-center">
                                @foreach($employee->user->getRoleNames() as $role)
                                    <span class="badge badge-info mr-1">{{$role}}</span>
                                @endforeach
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Pay Period:</b> <a class="float-right">{{\Carbon\Carbon::parse($payroll->date_start)->format('M d, Y')}} to {{\Carbon\Carbon::parse($payroll->date_end)->format('M d, Y')}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Branch:</b> <a class="float-right">{{!is_null($employee->user->spa_id) ? $employee->user->spa->name : ''}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Biometrics ID:</b> <a class="float-right">{{!is_null($employee->biometric) ? $employee->biometric->userid : ''}}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#payslip" data-toggle="tab">Payslip</a></li>
                                <li class="nav-item"><a class="nav-link" href="#additional-pay" data-toggle="tab">Additional Pay</a></li>
                                <li class="nav-item"><a class="nav-link" href="#deductions" data-toggle="tab">Deductions</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="payslip">
                                    <x-hr.payroll.payslip
                                        :employee="$employee"
                                        :payroll="$payroll"
                                        daysWorked="{{$number_of_days_worked}}"
                                        grossBasicPay="{{$gross_basic_pay}}"
                                        :attendance="$attendance"
                                        :deductions="$deductions"
                                        :additionalPays="$additionalPay"
                                        netPay="{{$net_pay}}"
                                    />
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="additional-pay">
                                    <button class="btn btn-primary mb-4" id="add-additional-pay-button" data-toggle="modal" data-target="#add-additional-pay-modal">Add</button>
                                    <table id="additional-pay-list" class="table table-hover table-bordered" role="grid" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Amount</td>
                                            <td>Remarks</td>
                                            <td></td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="deductions">
                                    <button class="btn btn-primary mb-4" id="add-deduction-button" data-toggle="modal" data-target="#add-deduction-modal">Add</button>
                                    <table id="deduction-list" class="table table-hover table-bordered" role="grid" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <td>Title</td>
                                                <td>Amount</td>
                                                <td>Remarks</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                    </table>

                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <form class="deduction-modal-form" id="add-deduction-form">
        @csrf
        <div class="modal fade" id="add-deduction-modal" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Add Deduction</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>×</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <div class="form-group name">
                            <label for="title">Title</label><span class="required">*</span>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group amount">
                            <label for="amount">Amount</label><span class="required">*</span>
                            <input type="number" name="amount" step="any" class="form-control" id="amount">
                        </div>
                        <div class="form-group remarks">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" class="form-control" id="remarks"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="payroll_id" value="{{$payroll->id}}">
                    <input type="hidden" name="employee_id" value="{{$employee->id}}">
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-primary save-deduction-button">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form class="additional-pay-modal-form" id="add-additional-pay-form">
        @csrf
        <div class="modal fade" id="add-additional-pay-modal" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Add Additional Pay</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>×</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <div class="form-group name">
                            <label for="title">Name</label><span class="required">*</span>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group amount">
                            <label for="amount">Amount Per Day</label><span class="required">*</span>
                            <input type="number" name="amount" step="any" class="form-control" id="amount">
                        </div>
                        <div class="form-group remarks">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" class="form-control" id="remarks"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="payroll_id" value="{{$payroll->id}}">
                    <input type="hidden" name="employee_id" value="{{$employee->id}}">
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-primary save-additional-pay-button">Save</button>
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
            $('#deduction-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-deduction-datatable',['owner_id' => $employee->owner_id, 'payroll_id' => $payroll->id]) !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'amount', name: 'amount'},
                    { data: 'remarks', name: 'remarks'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                pageLength: 50
            })
        });

        $(document).ready(function(){
            $('#additional-pay-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get-additional-pay-datatable',['payroll_id' => $payroll->id]) !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'amount', name: 'amount'},
                    { data: 'remarks', name: 'remarks'},
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

        let deductionModalForm = $('.deduction-modal-form');

        $(document).on('submit','#add-deduction-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                url: '{{route('payslips.store')}}',
                method: 'post',
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    deductionModalForm.find('.is-invalid').removeClass('is-invalid');
                    deductionModalForm.find('.text-danger').remove();
                    deductionModalForm.find('.save-deduction-button').attr('disabled',true).text('Saving ...');
                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    $('#deduction-list').DataTable().ajax.reload(null, false);
                    deductionModalForm.trigger('reset');
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    })
                }else{
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    })
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    deductionModalForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
                })
            }).always(function(){
                deductionModalForm.find('.save-deduction-button').attr('disabled',false).text('Save');
            });
        });

        $(document).on('click','#add-deduction-button', function(){
            deductionModalForm.attr('id','add-deduction-form');
            deductionModalForm.find('.modal-title').text('Add Deduction')
            deductionModalForm.trigger('reset')
            deductionModalForm.find('.is-invalid').removeClass('is-invalid');
            deductionModalForm.find('.text-danger').remove();
        })

        let editDeductionId;
        $(document).on('click','.edit-deduction-button', function(){
            editDeductionId = this.id;
            deductionModalForm.attr('id','edit-deduction-form');
            deductionModalForm.find('.modal-title').text('Edit Deduction')

            $.ajax({
                url: `/deductions/${editDeductionId}/edit`,
                method: 'get',
                beforeSend: function (){
                    deductionModalForm.find('input, textarea, .save-deduction-button').attr('disabled',true);
                }
            }).done(function(response){
                $.each(response, function(key, value){
                    deductionModalForm.find(`#${key}`).val(value)
                })
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                deductionModalForm.find('input, textarea, .save-deduction-button').attr('disabled',false);
            });
        })

        $(document).on('submit','#edit-deduction-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                url: '/deductions/'+editDeductionId,
                type: 'put',
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {
                console.log(response)
                if(response.success === true)
                {
                    $('#deduction-list').DataTable().ajax.reload(null, false);
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    })
                }
                else if(response.success === false){
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    })
                }
            }).fail(function(xhr, data, status){
                console.log(xhr)
            });
        })


        $(document).on('click','.delete-deduction-button', function(){
            let $tr = $(this).closest('tr');
            let data = $tr.children('td').map(function () {
                return $(this).text();
            }).get();

            let id = this.id;

            swal.fire({
                title: `Delete ${data[0]}?`,
                html:
                    '<p>This will delete the created deduction</p>Click <b class="text-info">YES</b>, to confirm',
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajax({
                        url: '/deductions/'+id,
                        type: 'delete',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    }).done((response) => {
                        console.log(response)
                        if(response.success === true)
                        {
                            $('#deduction-list').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                response.message,
                                '',
                                'success'
                            )
                        }
                        else if(response.success === false){
                            Swal.fire(
                                response.message,
                                '',
                                'warning'
                            )
                        }
                    }).fail(function(xhr, data, status){
                        console.log(xhr)
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        });


        // Additional Pay
        let additionalPayForm = $('.additional-pay-modal-form');

        $(document).on('click','#add-additional-pay-button', function(){
            additionalPayForm.attr('id','add-additional-pay-form');
            additionalPayForm.find('.modal-title').text('Add Additional Pay');
            additionalPayForm.trigger('reset');
            additionalPayForm.find('.is-invalid').removeClass('is-invalid');
            additionalPayForm.find('.text-danger').remove();
        })

        $(document).on('submit','#add-additional-pay-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/additional-pay/',
                type: 'post',
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    additionalPayForm.find('.is-invalid').removeClass('is-invalid');
                    additionalPayForm.find('.text-danger').remove();
                    additionalPayForm.find('.save-additional-pay-button').attr('disabled',true).text('Saving ...');
                }
            }).done((response) => {
                console.log(response)
                if(response.success === true)
                {
                    $('#additional-pay-list').DataTable().ajax.reload(null, false);
                    additionalPayForm.trigger('reset');
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    })
                }
                else if(response.success === false){
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    })
                }
            }).fail(function(xhr, data, status){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    additionalPayForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
                })
            }).always(function(){
                additionalPayForm.find('.save-additional-pay-button').attr('disabled',false).text('Save');
            });
        })

        let editAdditionalPayId;
        $(document).on('click','.edit-additional-pay-button', function(){
            editAdditionalPayId = this.id;
            additionalPayForm.attr('id','edit-additional-pay-form');
            additionalPayForm.find('.modal-title').text('Edit Additional Pay')

            $.ajax({
                url: `/additional-pay/${editAdditionalPayId}/edit`,
                method: 'get',
                beforeSend: function (){
                    additionalPayForm.find('input, textarea, .save-additional-pay-button').attr('disabled',true);
                }
            }).done(function(response){
                console.log(response)
                $.each(response, function(key, value){
                    additionalPayForm.find(`#${key}`).val(value)
                })
            }).fail(function(xhr, status, error){
                console.log(xhr)
            }).always(function(){
                additionalPayForm.find('input, textarea, .save-additional-pay-button').attr('disabled',false);
            });
        })

        $(document).on('submit','#edit-additional-pay-form', function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                url: '/additional-pay/'+editAdditionalPayId,
                type: 'put',
                data: data,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){
                    additionalPayForm.find('.is-invalid').removeClass('is-invalid');
                    additionalPayForm.find('.text-danger').remove();
                    additionalPayForm.find('.save-additional-pay-button').attr('disabled',true).text('Saving ...');
                }
            }).done((response) => {
                console.log(response)
                if(response.success === true)
                {
                    $('#additional-pay-list').DataTable().ajax.reload(null, false);
                    Toast.fire({
                        type: 'success',
                        title: response.message
                    })
                }
                else if(response.success === false){
                    Toast.fire({
                        type: 'warning',
                        title: response.message
                    })
                }
            }).fail(function(xhr, data, status){
                console.log(xhr)
                $.each(xhr.responseJSON.errors, function(key, value){
                    additionalPayForm.find('#'+key).addClass('is-invalid').after('<p class="text-danger">'+value+'</p>');
                })
            }).always(function(){
                additionalPayForm.find('.save-additional-pay-button').attr('disabled',false).text('Save');
            });
        })

        $(document).on('click','.delete-additional-pay-button', function(){
            let $tr = $(this).closest('tr');
            let data = $tr.children('td').map(function () {
                return $(this).text();
            }).get();

            let id = this.id;

            swal.fire({
                title: `Delete ${data[0]}?`,
                html:
                    '<p>This will delete the created additional pay</p>Click <b class="text-info">YES</b>, to confirm',
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajax({
                        url: '/additional-pay/'+id,
                        type: 'delete',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    }).done((response) => {

                        if(response.success === true)
                        {
                            $('#additional-pay-list').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                response.message,
                                '',
                                'success'
                            )
                        }
                        else if(response.success === false){
                            Swal.fire(
                                response.message,
                                '',
                                'warning'
                            )
                        }
                    }).fail(function(xhr, data, status){
                        console.log(xhr)
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        });
    </script>
@stop
