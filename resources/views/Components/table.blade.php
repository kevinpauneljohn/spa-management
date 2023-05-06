<table class="table-striped w-100" id="table-id">
    <thead>
       <tr>
        @foreach ($columnNames as $columnName)
            <th scope="col">{{$columnName}}</th>
        @endforeach
      </tr>
    </thead>
     <tbody>
        <!--results -->
     </tbody>
</table>