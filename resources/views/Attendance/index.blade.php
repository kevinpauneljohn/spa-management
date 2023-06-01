@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Attendance</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card p-4">
        <div class="row">
            <div class="col-md-4 col-lg-4 col-sm-12">
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
            </div>
            <div class="col-md-8 col-lg-8 col-sm-12">
                <div class="table-responsive" style="height: 720px;">
                    <table class="table attendanceTable table-bordered">
                        <thead>
                        <tr scope="row">
                            <x-table :columnNames="['Employee','Time-in','Break-in','Break-out','Time-out']"/>
                        </tr>
                        </thead>
                        <tbody>
                    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" href="{{asset('AllStyle/style.css')}}">
@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
    
    //TIME-IN
    $(document).on('click', '#time_in' , ()=>{
     let empID = $("#empID").val();
        swal({
            title: "Are you sure you want to time in employee ID: "+empID,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willTimeIn)=>{
            if(willTimeIn){     
                $.ajax({
                    'url' : '/attendanceID/' + empID,
                    'type' : 'POST',
                    'data' : {},
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success : (res)=>{
                        if(res == 0){
                           isExist();
                        }
                        else if(res == 1){
                            isSuccessful();
                        }
                        else if(res == 2){
                            NotMatch();
                        }
                       
                    },
                    error: function(Error) {
                        alert("Error:", textStatus);
                    }

                });
            }
        })
         
    });
    //TIME-OUT
    $(document).on('click', '#time_out' , ()=>{
        let empID = $("#empID").val();

         swal({
            title: "Are you sure you want to time out employee ID: "+empID,
            icon: "warning",
            buttons: true,
            dangerMode: true,
         }).then((willTimeOut)=>{
            if(willTimeOut){
                $.ajax({
                    'url' : '/time-out/' + empID,
                    'type' : 'PUT',
                    'data' : {},
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success : (res)=>{    
                        if(res == 0){
                             isSuccessful();
                        }
                        else if(res == 1){
                           isExist();
                        }
                        else if(res == 2){
                            NotMatch();
                        }
                        else if(res == 3)
                        {
                            NotTimeIn();
                        }
                    }
                });
             }
         });  
    });
    //BREAK-IN
    $(document).on('click', '#break_in' , ()=>{
        let empID = $("#empID").val();
        $.ajax({
            'url' : '/break-in/' + empID,
            'type' : 'GET',
            'data' : {},
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : (res)=>{
                if(res == 0){
                         isSuccessful();
                        }
                        else if(res == 1){
                           isExist();
                        }
                        else if(res == 2){
                            NotMatch();
                }
            }

        });
    });
    //BREAK-OUT
    $(document).on('click', '#break_out' , ()=>{
        let empID = $("#empID").val();
        $.ajax({
            'url' : '/break-out/' + empID,
            'type' : 'GET',
            'data' : {},
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : (res)=>{
                if(res == 0){
                         isSuccessful();
                        }
                        else if(res == 1){
                           isExist();
                        }
                        else if(res == 2){
                            NotMatch();
                         }
                         else if(res == 3){
                            NotBreakIn();
                         }
            }

        });
    });

    //Display
    $.ajax({
            'url' : '/show',
            'type' : 'GET',
            'data' : {},
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : (AttendanceTbl)=>{
                var html = "";
                $.each(AttendanceTbl, function(item,data){

                    html += "<tr class='text-center' style='font-size: 25px'>";
                    html += "<td>" + data.name + "</td>";
                    html += "<td>" + data.time_in + "</td>";
                    html += "<td>" + data.break_in + "</td>";
                    html += "<td>" + data.break_out + "</td>";
                    html += "<td>" + data.time_out + "</td>";
             
                    html += "</tr>";
                });
                $("#table-id").append(html);
            }
        })
        
        $(document).ready(function() {
            setInterval(function() {
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();
                var month = currentTime.getMonth() + 1;
                var day = currentTime.getDate();
                var year = currentTime.getFullYear();

            
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;


                var suffix = (hours >= 12) ? "PM" : "AM";

                hours = (hours > 12) ? hours - 12 : hours;

                hours = (hours == 0) ? 12 : hours;

            
                var currentTimeString = month + "/" + day + "/" + year + " " + hours + ":" + minutes + ":" + seconds + " " + suffix;

            
                $("#clock").html(currentTimeString);
            }, 1000); 
    });
    function isSuccessful(){
        swal({
         title: "Successful",
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
    }
    function isExist(){
        swal({
            title: "Attendance record already exists for this employee for today.",
            icon: "warning",
            dangerMode: true,
            });
    }
    function NotMatch(){
        swal({
            title: 'ID Not Found!',
            icon: "warning",
            dangerMode: true,
            });
         }
    function NotTimeIn(){
        swal({
            title: 'Not Yet Time in',
            icon: "warning",
            dangerMode: true,
            });
    }
    function NotBreakIn(){
        swal({
            title: 'Not Yet Break in',
            icon: "warning",
            dangerMode: true,
            });
    }

</script>

@stop