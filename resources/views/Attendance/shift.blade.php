@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Shift Management</h1>
@stop
@section('content')
<x-schedmodal></x-schedmodal>
<div class="container-fluid">
    <div class="card p-4">
        <div>
     <x-schedmodal></x-schedmodal>
<table id="shift-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                <thead>
                     <tr role="row">
                        <th>Name</th>
                        <th>Position</th>
                        <th>Schedule</th>
                        <th>Shift</th>
                        <th>Action</th>
                     </tr>
                </thead>
             </table>
        </div>
    </div>
</div>
@stop
<!-- @section('plugins.Moment',true) -->

@section('css')
<link rel="stylesheet" href="{{asset('AllStyle/style.css')}}">


@stop

@section('js')
 <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> 
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script> 
<script>
$(document).ready(function(){

    $('#shift-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route("shift.list") !!}',
                columns: [
                    { data: 'names', name: 'names', className: 'text-center' },
                    { data: 'position', name: 'position'},
                    { data: 'schedule', name: 'schedule'},
                    { data: 'time', name: 'time'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 100
            });
});
$('#savesched').click(function() {
    var schedslist = [];
    var edit_sched_id = $('.edit-shift-btn').attr('data-id');
    var edit_time = $('#timesched').val();
    
    $('.checkbox:checked').each(function() {
        schedslist.push($(this).val());
    });
    var listofsched = {
        list : schedslist,
        edit_time : edit_time,
    }
    if(listofsched.list.length > 0){
          swal({
        title: "Are you sure you want to update Schedule of ID: "+edit_sched_id,
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((isUpdate)=>{
                if(isUpdate){
                    $.ajax({
                    'url' : '/update/' + edit_sched_id,
                    'type' : 'PUT',
                    'data' : listofsched,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    success : (res)=>{
                        swal({
                        title: res.message,
                        text: 'Operation completed successfully.',
                        icon: 'success',
                        buttons: {
                            confirm: {
                            text: 'OK',
                            className: 'btn-success'
                            }
                        },
                            }).then(function(){
                                location.reload();
                            });
                       }
                  });
                }
            });
        }
        else{
            alert('Schedule is Empty');
        }

  
  
  });
</script>

@stop