@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Employee List</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card p-4">
            <x-rateModal></x-rateModal>
                <div class="table-responsive">
                    <table id="tbl_employee" class="table attendanceTable table-bordered" style="width:100%;">
                        <thead>
                            <tr role="row">
                                <th>Name</th>
                                <th>ID</th>
                                <th>Position</th>
                                <th>Daily Rate</th>
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
                    { data: 'id', name: 'id'},
                    { data: 'position', name: 'position'},
                    { data: 'rate', name: 'rate'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            responsive:true,
            order:[0,'desc'],
            pageLength: 10
        });
    });

 $(document).on('click', '#edit-rate-btn', function() {
     $('#rateModal').find('.error').remove();
     $('#rateModal').find('.is-invalid').removeClass('is-invalid');
    var employeeId = $(this).data('id');
    $('#hiddenID').val(employeeId);
    $.ajax({
        url: '/getEmployeeRate/' + employeeId,
        type: 'GET',
        success: function(response) {
            $('#Daily_Rate').val(response.rate);
            $('#ratename').text(response.name);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});




</script>

@stop
