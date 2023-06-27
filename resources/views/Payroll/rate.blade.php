@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Employee List</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card p-4">

            {{-- <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="row text-center">
                    <div class="col-md-6 col-lg-6">
                      <p style="font-size: 30px;">Spa ASD</p>
                    </div>
                    <div class="col-md-6 col-lg-6">
                      <p style="font-size: 30px; color: green;" id="clock"></p>
                    </div>
                </div>
                </br>
                <div class="row">
                    <div class="col-md-12 col-lg-12 ml-4">
                        <div class="did-floating-label-content">
                            <input id="empID" class="did-floating-input w-100" type="text" placeholder=" ">
                            <label class="did-floating-label">Enter Employee ID</label>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <button class="btn pt-4 pb-4 btn-success w-100" id="time_in">Time-In <i class="fas fa-hourglass-start ml-2"></i></button>
                     </div>
                     <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <button class="btn pt-4 pb-4 btn-danger w-100" id="time_out">Time-Out <i class="fas fa-hourglass-end ml-2"></i></button>
                    </div>
                </div>
                </br>
                <div class="row text-center">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <button class="btn btn-primary pt-4 pb-4 w-100" id="break_in">Break-In</button>
                     </div>
                     <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <button class="btn btn-warning pt-4 pb-4 w-100" id="break_out">Break-Out</button>
                    </div>
                </div>
            </div> --}}
            <x-rateModal></x-rateModal>
                <div class="table-responsive" style="height: 720px;">
                    <table id="tbl_employee" class="table attendanceTable table-bordered">
                        <thead>
                            <tr role="row">
                                <th>Name</th>
                                <th>Position</th>
                                <th>Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                 </div>
</div>
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" href="{{asset('AllStyle/style.css')}}">
@stop

@section('js')
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function(){   
        $('#tbl_employee').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route("employee-rate") !!}',
            columns: [
                    { data: 'name', name: 'name', className: 'text-center' },
                    { data: 'position', name: 'position'},
                    { data: 'rate', name: 'rate'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            responsive:true,
            order:[0,'desc'],
            pageLength: 100
        });
    });

 $(document).on('click', '#edit-rate-btn', function() {
    var employeeId = $(this).data('id');
    $('#hiddenID').val(employeeId);
    $.ajax({
        url: '/getEmployeeRate/' + employeeId, 
        type: 'GET',
        success: function(response) {
            $('#emp_rate').val(response.rate);
            $('#ratename').text(response.name);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});

// Update MonthlyRate
$(document).on('click', '#saverate', function() {
    var employeeId = $('#hiddenID').val();
    var newRate = $('#emp_rate').val();
    
    $.ajax({
        url: '/updateEmployeeRate/' + employeeId,
        type: 'PUT',
        data: {
            newRate: newRate,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
                  swal({
         title: "Update Successful",
         text: 'Operation completed successfully.',
          icon: "success",
            buttons: {
                confirm: {
                  text: 'OK',
                  className: 'btn-success'
                   }
               },
             }).then(function(){
                 location.reload();
         });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});

    
</script>

@stop