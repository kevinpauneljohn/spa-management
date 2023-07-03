@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Attendancess</h1>
@stop
@section('content')
<div class="container-fluid">
    <div class="card p-4">
                <div class="table-responsive mt-3" style="height: 720px;">
                            <input style="width: 200px;padding-bottom: 6px; padding-top: 6px; text-align: center" type="text" name="daterange"/>
                            <button id="savedatess" class="pl-4 pr-4 btn btn-outline-success mb-2 ml-2">Filter</button>
                    <table id="tbl_attendance" class="table attendanceTable table-bordered display">
                        <thead>
                            <tr role="row" class="text-center">
                                <th>Employee Name</th>
                                <th>Time-In</th>
                                <th>Break-In</th>
                                <th>Break-Out</th>
                                <th>Time-Out</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
          
                    </table>
                    <div class="text-center">
                      <p id="noresult" style="font-size: 25px; color: red"></p>
                    </div>
               
                 </div>
      
    </div>
</div>
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" href="{{asset('AllStyle/style.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@stop

@section('js')

 {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
      
$(function() {
  var currentDate = moment();

  $('input[name="daterange"]').daterangepicker({
    opens: 'left',
    startDate: currentDate,
    endDate: currentDate
  });
});

  $(document).ready(function(){   
    let dateStart = $('input[name="daterange"]').data('daterangepicker').startDate.format('MMMM DD, YYYY');
    let dateEnd = $('input[name="daterange"]').data('daterangepicker').endDate.format('MMMM DD, YYYY');
    $.ajax({
      'url' : '/show',
      'type' : 'GET',
      'data' : {start:dateStart, end:dateEnd},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: (res) => {
        if(res==0)
        {
          $('#noresult').text("No result");
        }
        var html = "";
        $.each(res, function(key, value) {
          html += "<tr class='text-center'>";
          html += "<td>" + value.employee.user.firstname + "</td>";
          html += "<td>" + value.time_in + "</td>";
          html += "<td>" + value.break_in + "</td>";
          html += "<td>" + value.break_out + "</td>";
          html += "<td>" + value.time_out + "</td>";
          html += "</tr>";
        });
        $('#tbl_attendance').append(html);
        $('#noresult').text("");
      }
    });
  })

  $(document).on('click','#savedatess', function(e){
    e.preventDefault();
    let dateStart = $('input[name="daterange"]').data('daterangepicker').startDate.format('MMMM DD, YYYY');
    let dateEnd = $('input[name="daterange"]').data('daterangepicker').endDate.format('MMMM DD, YYYY');
    $('#tbl_attendance tbody').empty();
    $.ajax({
      'url' : '/show',
      'type' : 'GET',
      'data' : {start:dateStart, end:dateEnd},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: (res) => {
        if(res==0)
        {
          $('#noresult').text("No result");
        }
        var htmls = "";
        $.each(res, function(key, value) {
          htmls += "<tr class='text-center'>";
          htmls += "<td>" + value.employee.user.firstname + "</td>";
          htmls += "<td>" + value.time_in + "</td>";
          htmls += "<td>" + value.break_in + "</td>";
          htmls += "<td>" + value.break_out + "</td>";
          htmls += "<td>" + value.time_out + "</td>";
          htmls += "</tr>";
        });
        $('#tbl_attendance').append(htmls);
        $('#noresult').text("");
      }
    });
  })



// $(document).ready(function(){
//     $('#tbl_attendance').DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: '{!! route("attendance.display") !!}',
//     columns: [
//       { data: 'name', name: 'name', className: 'text-center' },
//       { data: 'timein', name: 'timein' },
//       { data: 'breakin', name: 'breakin' },
//       { data: 'breakout', name: 'breakout' },
//       { data: 'timeout', name: 'timeout' }
//     ],
//     responsive: true,
//     order: [0, 'desc'],
//     pageLength: 100
//   });
// })




</script>
@stop