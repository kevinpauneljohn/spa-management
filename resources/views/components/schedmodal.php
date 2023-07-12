<div class="modal fade "  id="schedModal" tabindex="-1" aria-labelledby="schedModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
    <div class="modal-content" style="border: 2px;border-radius: 10px;">
      <div class="modal-header">
        <h5 class="modal-title" id="schedModal">Edit Schedule</h5>
      </div>
      <div class="modal-body">
       <div class="container-fluid weekly-schedule-container">
            <h2>Weekly Schedule</h2>
            <button value="Mon" id="daysched" class="m-2 btn btn-outline-primary">Monday</button>
            <button value="Tue" id="daysched" class="m-2 btn btn-outline-success">Tuesday</button>
            <button value="Wed" id="daysched" class="m-2 btn btn-outline-warning">Wednesday</button>
            <button value="Thu" id="daysched" class="m-2 btn btn-outline-danger">Thursday</button>
            <button value="Fri" id="daysched" class="m-2 btn btn-outline-dark">Friday</button>
            <button value="Sat" id="daysched" class="m-2 btn btn-outline-info">Saturday</button>
            <button value="Sun" id="daysched" class="m-2 btn btn-outline-secondary">Sunday</button>
        </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 col-lg-6 text-center">
              <h3>Time In</h3>
              <input type="time" id="timein" value="08:00">
              <h3 class="bottom-text">Time Out</h3>
              <input type="time" id="timeout" value="17:00">
              <input type="hidden" id="hidden">  
            </div>
            <div class="col-md-6 col-lg-6 text-center overtime-container">
              <h3>Over-time Hours</h3>
              <select name="option" id="overTime">
                    <option>SELECT TIME</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
              </select> 
              <h3 class="bottom-text">Over-time</h3>  
              <input type='checkbox' class='switch' id='first-toggle-btn'>
              <label for='first-toggle-btn' class='label-switch'>
            </div>
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