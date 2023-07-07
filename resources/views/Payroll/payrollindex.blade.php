@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Payroll</h1>
@stop
@section('content')

<div class="container">
  <!-- The Modal -->
    <x-modal/>
    <x-empmodal/>
  <!-- Modal -->
</div>
    <div class="container-fluid">
        <div class="card p-4">
            <div class="container-fluid">
              <x-form/>
            </div>
            <div class="table table-responsive" id="table-wrapper">
                <x-table :columnNames="['Name','Total Sales','Total Commission','Allowance','View Summary']"/>
            </div>
        </div>
    </div>
    <!-- Modal -->
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{asset('AllStyle/style.css')}}">
<style>
    th{
        text-align: center;
    }
</style>
@stop

@section('js')
 {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
var selectedOption;
$('#generate')
$('#department').on('change', function() {
  selectedOption = $(this).val();
  var emptyheaders='';
    if(selectedOption === 'employee'){
        emptyheaders = '<th>Name</th><th>Total Hours</th><th>Net Pay</th><th>View Summary</th></tr>';
    }
    if(selectedOption === 'therapist'){
        emptyheaders = '<th>Name</th><th>Total Sales</th><th>Total Commission</th><th>Allowance</th><th>View Summary</th>';
    }
    $('#table-id thead').html(emptyheaders);
    $('#table-id tbody').empty();
    $("#modal-viewsummary tbody").empty();
    $('#no_data').text("");
});
//DATE RANGE PICKER LIMIT
$(function() {
    var dateRangePicker = $('input[name="daterange"]');
    var defaultOptions = {
        alwaysShowCalendars: true,
        minDate: new Date() // Set a default minDate to prevent selecting past dates before AJAX request completes
    };
    dateRangePicker.daterangepicker(defaultOptions);
    $.ajax({
        'url': '/dateRangechecker',
        'type': 'GET',
        'data': {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            // Destroy the existing daterangepicker
            dateRangePicker.data('daterangepicker').remove();

            var updatedOptions = Object.assign({}, defaultOptions, { minDate: new Date(res.minDate) });
            dateRangePicker.daterangepicker(updatedOptions);

            if (dateRangePicker.data('daterangepicker').startDate < res.formattedDate) {
                dateRangePicker.data('daterangepicker').setStartDate(res.formattedDate);
            }
        }
    });
});
//GENERATE PAYSLIP

$(document).on('click', '#printslip', function (form) {
  $(this).prop('disabled', true).text('Loading...');

  setTimeout(function () {
    $('#printslip').prop('disabled', false).text('PRINT SLIP');
    getDate(function (alldate) {
      var selectedOption = $('#department').val();
      var strTherapist = 'Therapist';
      var strEmployee = 'Employee';

      if (selectedOption === 'therapist') {
        $.get('/payslip/' + strTherapist, alldate, function (data, status) {
          data = parseInt(data);
          if (data === 404) {
            swal({
              title: "No payrolls found for the given date.",
              icon: "warning",
              dangerMode: true
            });
          } else {
            window.location.href = '/payslip/' + strTherapist;
          }
        });
      } else if (selectedOption === 'employee') {
        $.get('/payslip/' + strEmployee, alldate, function (data, status) {
          data = parseInt(data);
          if (data === 404) {
            swal({
              title: "No payrolls found for the given date.",
              icon: "warning",
              dangerMode: true
            });
          } else {
            window.location.href = '/payslip/' + strEmployee;
          }
        });
      }
    });
  }, 500);
});



//GENERATE PAYROLL 
$(document).on('click','#generate',function(form){
    form.preventDefault();
    $(this).prop('disabled', true).text('Loading...');

    setTimeout(function() {

      $('#generate').prop('disabled', false).text('GENERATE');

        getDate(function(alldate){
            $("#table-id tbody").empty();
            $('#no_data').text("");
            selectedOption = $('#department').val();

            if(selectedOption === 'therapist')
            {
                    $('#no_data').text("");
                    $.get('/show-date', alldate, function(data, status) {
                            var html = "";
                            $.each(data, function(key, value) {
                                if (value.amount) {
                                html += "<tr class='text-center'>";
                                html += "<td>" + value.fullname + "</td>";
                                html += "<td>" + value.amount + "</td>";
                                html += "<td>" + value.TotalCommission + "</td>";
                                html += "<td>" + value.Allowance + "</td>";
                                html += '<td> <button type="button" value="'+value.id+'" class="btn btn-primary viewsummary" data-toggle="modal" data-target="#exampleModal">View Summary </button> </td>';
                                html += "</tr>";
                                }
                            });

                            if (html !== "") {
                                $("#table-id").append(html);
                            } else {
                                $('#no_data').text("No Existing Data");
                            }
                            });
            }
            else if(selectedOption === 'employee')
                    {
                        $('#no_data').text("");
                        $.get('/employee-salary',alldate, function(data, status){
                          if(data == 404)
                          {
                            $('#no_data').text("No Existing Data");
                          }
                          else{
                            var htmlEmployee = "";
                            $.each(data, (key, value) => {

                                        htmlEmployee += "<tr class='text-center'>";
                                        htmlEmployee += "<td>" + value.Name + "</td>";
                                        htmlEmployee += "<td>" + value.Total_working_hours + "</td>";
                                        htmlEmployee += "<td>" + value.Net_Pay + "</td>";
                                        htmlEmployee += '<td> <button type="button" data-pay="'+value.Net_Pay+'" value="'+value.id+'" class="btn btn-primary empsummary" data-toggle="modal" data-target="#empModal">View Summary </button> </td>';
                                        htmlEmployee += "</tr>";  
                              
                            });
                            $('#table-id').append(htmlEmployee); // Append the HTML to the table
                                $('#no_data').text("")
                          }
                          
                  
                          // data = parseInt(data)
                          // if(data === 404)
                          // {
                          //   $('#no_data').text("No Existing Data");
                          // }
                          // else{
                          //       // var htmlEmployee = "";
               
                          //       $.each(data, (key, value) => {
                          //         console.table(value);
                          //               // htmlEmployee += "<tr class='text-center'>";
                          //               // htmlEmployee += "<td>" + value.Name + "</td>";
                          //               // htmlEmployee += "<td>" + value.Total_working_hours + "</td>";
                          //               // htmlEmployee += "<td>" + value.Net_Pay + "</td>";
                          //               // htmlEmployee += '<td> <button type="button" value="'+value.id+'" class="btn btn-primary empsummary" data-toggle="modal" data-target="#empModal">View Summary </button> </td>';
                          //               // htmlEmployee += "</tr>";        
                          //       });
                          //       // $('#table-id').append(htmlEmployee); // Append the HTML to the table
                          //       // $('#no_data').text("");
                          // }

                        });
                    }
            });
    }, 500);
});
// Employee View SUmmary

$(document).on('click', '.empsummary', function(){
    let id = $(this).val();    
    let net = $(this).data('pay');
    $("#modal-viewsummaryemp tbody").empty();
    getDate(function(alldate){
        $.ajax({
            'url' : '/employee-summary/' + id,
            'type' : 'GET',
            'data' : alldate,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success : (res)=>{
                console.table(res);
                var html = "";
                $.each(res, function(key, value){
                // const formats = "MMMM DD, YYYY";
                // var date = moment(value.time_in).format(formats);
                html += "<tr class='text-center'>";
                html += "<td>" + value.Date + "</td>";
                html += "<td>" + value.Total_Hours + "</td>";
                html += "<td>" + value.Pay + "</td>";
                html += "</tr>";
                });

                $("#modal-viewsummaryemp").append(html);
                $('#totalnet').text(net);
            }
        })
    })
});
// Therapist View SUmmary
$(document).on('click', '.viewsummary', function(){
    let id = $(this).val();

    getDate(function(alldate){
    $("#modal-viewsummary tbody").empty();
        $.ajax({
            'url' : '/info/' + id,
            'type' : 'GET',
            'data' : alldate,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : (res)=>{
                var html = "";
                $.each(res, function(key, value){
                    $('#exampleModalLabel').text(value.fullname);
                    const formats = "MMMM DD, YYYY";
                    var date = moment(value.date).format(formats);
                    html += "<tr class='text-center'>";
                    html += "<td>" + value.service + "</td>";
                    html += "<td>" + value.amount + "</td>";
                    html += "<td>" + date + "</td>";
                    html += "</tr>";
                })
            $("#modal-viewsummary").append(html);
            }
        })
    });
});

function getDate(callback){
    let dateStart = $('#daterange').data('daterangepicker').startDate.format('MMMM DD, YYYY');
    let dateEnd = $('#daterange').data('daterangepicker').endDate.format('MMMM DD, YYYY');

    let alldate= {
        datestart : dateStart,
        dateEnd : dateEnd
    }
    callback(alldate);
}

</script>
@stop