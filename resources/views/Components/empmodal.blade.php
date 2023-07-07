<div class="modal fade" id="empModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employee Summary</h5>
      </div>
      <div class="modal-body">
           <table class="table-striped w-100" id="modal-viewsummaryemp">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Hours</th>
                        <th scope="col">Daily Pay</th>
                    </tr>
                </thead>
                <tbody>
        
                </tbody>
            </table>
            <div class="container-fluid mt-3">
              <p style="font-size: 25px">Total Net Pay:
              <span style="color: green; font-size: 25px" id="totalnet"></span>
              </p>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

