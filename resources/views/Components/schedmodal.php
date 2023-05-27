<div class="modal fade "  id="schedModal" tabindex="-1" aria-labelledby="schedModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
    <div class="modal-content" style="border: 2px;border-radius: 10px;">
      <div class="modal-header">
        <h5 class="modal-title" id="schedModal">Edit Schedule</h5>
      </div>
      <div class="modal-body">
        <div class="container-fluid text-center">
            <h3 class="text-center">Name of the Employee</h3>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched1" class="checkbox" value="M" type="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Monday</h4>
                    </div>
                
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched2" class="checkbox" value="T" type="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Tuesday</h4>
                    </div>
                 
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched3" class="checkbox" value="W" type="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Wednesday</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched3" type="checkbox" value="TH" class="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Thursday</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched3" type="checkbox" value="F" class="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Friday</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched3" type="checkbox" value="SA" class="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Saturday</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sched3" type="checkbox" value="S" class="checkbox" style="width: 20px; height: 20px">
                    </div>
                    <div class="col-md-2">
                        <h4>Sunday</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="time" id="timesched" value="08:30">
                    </div>
                </div>
          
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary active">
                        <input type="radio"  name="options" id="option1" autocomplete="off"> Morning Shift
                    </label>
                    <label class="btn btn-outline-success">
                        <input type="radio"  name="options" id="option2" autocomplete="off"> Mid Shift
                    </label>
                    <label class="btn btn-outline-danger">
                        <input type="radio" name="options" id="option3" autocomplete="off"> Night Shift
                    </label>
                </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="savesched" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
