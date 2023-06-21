@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Attendance</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card p-4">
                <div class="table-responsive" style="height: 720px;">
                    <table id="tbl_attendance" class="table attendanceTable table-bordered">
                        <thead>
                            <tr role="row">
                                <th>Employee Name</th>
                                <th>Time-In</th>
                                <th>Break-In</th>
                                <th>Break-Out</th>
                                <th>Time-Out</th>
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
        $('#tbl_attendance').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route("attendance.display") !!}',
            columns: [
                    { data: 'name', name: 'name', className: 'text-center' },
                    { data: 'timein', name: 'timein'},
                    { data: 'breakin', name: 'breakin'},
                    { data: 'breakout', name: 'breakout'},
                    { data: 'timeout', name: 'timeout'}
            ],
            responsive:true,
            order:[0,'desc'],
            pageLength: 100
        });
    });

</script>

@stop