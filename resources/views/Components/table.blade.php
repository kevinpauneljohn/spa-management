<table class="table-striped w-100" id="table-id">
    <thead>
       <tr class="text-center">
       @foreach ($columnNames as $columnName)
            @php
                // Define an array of background color classes to match the column name
                $bgClasses = [
                    'Employee' => 'bg-primary',
                    'Time-in' => 'bg-success',
                    'Break-in' => 'bg-warning',
                    'Break-out' => 'bg-info',
                    'Time-out' => 'bg-danger'
                ];
                // Check if the column name exists in the array of background color classes
                $bgClass = isset($bgClasses[$columnName]) ? $bgClasses[$columnName] : '';
            @endphp
            <!-- Add the background color class to the th element -->
            <th scope="col" class="p-3 {{$bgClass}}">{{$columnName}}</th>
        @endforeach
      </tr>
    </thead>
     <tbody>
        <!--if no data -->
   
     </tbody>
  
</table>

   <div class="container text-center">
        <p id="no_data"></p>
    </div>