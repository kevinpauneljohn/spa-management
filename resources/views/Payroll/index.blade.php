@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
 
    <h1>Payroll</h1>
@stop
@section('content')

<div class="container">

  <!-- The Modal -->
  <!-- Button trigger modal -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" style="color: green;" id="exampleModalLabel"></h1>
      </div>
      <div class="modal-body">
            <table class="table-striped w-100" id="modal-viewsummary">
                <thead>
                    <tr>
                        <th scope="col">Service Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
        
                </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</div>

    <div class="container-fluid">
        <div class="card p-4">
            <div class="container-fluid">
                <form class="generate-payroll-form">
                    @csrf
                <div class="row d-flex justify-content-start p-3 selectionTop">
                   <div id="first">
                        <div>
                            <label style="font-size: 20px" for="daterange" class="mr-2">Select Date Range:</label>
                        </div>
                        <div>
                            <input type="text" class="mr-4 rounded border border-dark p-2 text-center" id="daterange" name="daterange"  />
                        </div>
                   </div>
                   <div id="second">
                        <div>
                            <label style="font-size: 20px" for="exampleDataList" class="form-label mr-2">Select Department: </label>
                        </div>
                        <div>
                            <select id="department" class="form-select form-select-lg mr-4 rounded border border-dark p-2 text-center" aria-label=".form-select-lg example">
                                <option selected>Therapist</option>
                                <option>Sample Department</option>
                                <option>Sample Department</option>
                                <option>Sample Department</option>
                            </select>
                        </div>
                   </div>
                    <div class="third">
                        <button type="submit" id="generate" class="text-center btn btn-success mb-4 pl-5 pr-5">GENERATE</button> 
                    </div>
                </div>
                </form>
            </div>
            <div class="table table-responsive" id="table-wrapper">
                <table class="table-striped w-100" id="table-id">
                    <thead>
                      <tr>
                        <th id="changeabol" scope="col">Therapist</th>
                        <th scope="col">Total Sales</th>
                        <th scope="col">Total Commision</th>
                        <th scope="col">View Summary</th>
                      </tr>
                    </thead>
                    <tbody>
  
                    </tbody>
                  </table>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script>
$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        // console.log("A new date selection was made: " + start.format('MMMM DD, YYYY') + ' to ' + end.format('MMMM DD, YYYY'));
    });
});
//        let department = $('#department').val();
//        let newText;
//        let dateStart = $('#daterange').data('daterangepicker').startDate.format('MMMM DD, YYYY');
//        let dateEnd = $('#daterange').data('daterangepicker').endDate.format('MMMM DD, YYYY');
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
            $('#exampleModalLabel').text(res.therapist.firstname +' '+ res.therapist.lastname);
            var html = "";
            $.each(res.transaction, function(key, value){
                const formats = "YYYY-MM-DD";
                var date = moment(value.created_at).format(formats);
                html += "<tr>";
                html += "<td>" + value.service_name + "</td>";
                html += "<td>" + value.amount + "</td>";
                html += "<td>" + date + "</td>";
                html += "</tr>";
                
                // $('#showName').text(value.firstname + ' ' + value.lastname);

              
           });
          $("#modal-viewsummary").append(html);
          
        }
    })
});

</script>
@stop