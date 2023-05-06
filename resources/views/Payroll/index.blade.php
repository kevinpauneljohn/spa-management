@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Payroll</h1>
@stop
@section('content')

<div class="container">
  <!-- The Modal -->
    <x-modal/>
  <!-- Modal -->
</div>
    <div class="container-fluid">
        <div class="card p-4">
            <div class="container-fluid">
              <x-form/>
            </div>
            <div class="table table-responsive" id="table-wrapper">
                <!--GENERATED TABLE -->
                <x-table :columnNames="['Name','Total Sales','Total Commision','View Summary']"/>
            </div>
        </div>
    </div>

    <!-- Modal -->
@stop
@section('plugins.Moment',true)

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{asset('AttendanceStyle/style.css')}}">
@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        // console.log("A new date selection was made: " + start.format('MMMM DD, YYYY') + ' to ' + end.format('MMMM DD, YYYY'));
    });
});
    $(document).on('submit','.generate-payroll-form',function(form){
    form.preventDefault();
    let values = $(this).serializeArray();
    $("#table-id tbody").empty();

    $.get('/show-date',values, function(data, status){
        var html = "";
        $.each(data, function(key, value){

            html += "<tr>";
            html += "<td>" + value.fullname + "</td>";
            html += "<td>" + value.amount + "</td>";
            html += "<td>" + value.TotalCommission + "</td>";
            html += '<td> <button type="button" value="'+value.id+'" class="btn btn-primary viewsummary" data-bs-toggle="modal" data-bs-target="#exampleModal">View Summary </button> </td>';
            html += "</tr>";
            
        });
        $("#table-id").append(html);
        console.log($("#viewsummary").val());
    }); 
});

$(document).on('click', '.viewsummary', function(){
    let id = $(this).val();

    let dateStart = $('#daterange').data('daterangepicker').startDate.format('MMMM DD, YYYY');
    let dateEnd = $('#daterange').data('daterangepicker').endDate.format('MMMM DD, YYYY');

    let alldate= {
        datestart : dateStart,
        dateEnd : dateEnd
    }

    $("#modal-viewsummary tbody").empty();
        $.ajax({
            'url' : '/info/' + id,
            'type' : 'GET',
            'data' : alldate,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : (res)=>{

                $('#exampleModalLabel').text(res.therapist.firstname+' '+res.therapist.lastname);
                var html = "";
                $.each(res.transaction, function(key, value){

                    const formats = "MMMM DD, YYYY";
                    var date = moment(value.created_at).format(formats);
                    html += "<tr>";
                    html += "<td>" + value.service_name + "</td>";
                    html += "<td>" + value.amount + "</td>";
                    html += "<td>" + date + "</td>";
                    html += "</tr>";

            });
            $("#modal-viewsummary").append(html);
            
            }
        })
});

</script>
@stop