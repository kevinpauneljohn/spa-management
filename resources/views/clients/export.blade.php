<table>
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Mobile Number</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->firstname }}</td>
            <td>{{ $client->lastname }}</td>
            <td>{{ $client->mobile_number }}</td>
            <td>{{ $client->address }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
