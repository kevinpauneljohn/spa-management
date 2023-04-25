@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Payroll</h1>
@stop
@section('content')
    <div class="container-fluid">
        <div class="card p-4">
            <div class="container-fluid">
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
                        <button type="button" id="generate" class="text-center btn btn-success mb-4 pl-5 pr-5">GENERATE</button> 
                    </div>
                </div>
            </div>
            <div class="table table-responsive" id="table-wrapper">
                <table class="table-striped w-100">
                    <thead>
                      <tr>
                        <th id="changeabol" scope="col">Therapist</th>
                        <th scope="col">Total Sales</th>
                        <th scope="col">Total Commision</th>
                        <th scope="col">View Summary</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td><input type="button" class="btn btn-success pl-4 pr-4" value="View"></td>
                      </tr>
                      <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td><input type="button" class="btn btn-success pl-4 pr-4" value="View"></td>
                      </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
@stop

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
        console.log("A new date selection was made: " + start.format('MMMM DD, YYYY') + ' to ' + end.format('MMMM DD, YYYY'));
    });
});
$("#generate").on('click', function(){

       let department = $('#department').val();
       let newText;
       let dateStart = $('#daterange').data('daterangepicker').startDate.format('MMMM DD, YYYY');
       let dateEnd = $('#daterange').data('daterangepicker').endDate.format('MMMM DD, YYYY');
       $('#changeabol').text(department);
})
</script>
@stop