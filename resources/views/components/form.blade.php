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
                    <option value="therapist" selected>Therapist</option>
                    <option value="employee">Employee</option>

                </select>
            </div>
        </div>
        <div class="third">
            <button type="submit" id="generate" class="text-center btn btn-success mb-4 pl-3 pr-3">GENERATE</button> 
            <button type="submit" id="printslip" class="text-center btn btn-success mb-4 pl-3 pr-3">PRINT SLIP</button> 
        </div>
     </div>
 </form>
