<table class="table table-bordered table-hover" id="dashboard-sales-table-list">
    <thead>
        <tr>
            <th>Created At</th>
            <th>Invoice</th>
            <th>Rooms</th>
            <th>Clients</th>
            <th>Completed</th>
            <th>Total Amount</th>
            <th>Action</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@section('plugins.CustomCSS',true)
@once
    @push('js')
        <script>
            $('#dashboard-sales-table-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('point-of-sale-lists',['spa' => $spaId]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'invoice_number', name: 'invoice_number'},
                    { data: 'rooms', name: 'rooms', className: 'text-center'},
                    { data: 'clients', name: 'clients', className: 'client-width'},
                    { data: 'completed', name: 'completed', className: 'text-center'},
                    { data: 'total_amount', name: 'total_amount'},
                    { data: 'payment_status', name: 'payment_status'},
                    { data: 'payment_required', name: 'payment_required'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                autoFill:'off',
                responsive:true,
                order:[0,'desc'],
                pageLength: 10,
                "autoWidth": false,
                drawCallback: function(row){
                    let sale = row.json;

                    $('#dashboard-sales-table-list').find('tbody')
                        .append('<tr class="sales-info-bg">' +
                            '<td colspan="2">Total No. Clients: <span class="text-info text-bold">'+sale.total_clients+'</span></td>' +
                            '<td colspan="3">Total Amount: <span class="text-info text-bold">'+sale.total_expected_amount+'</span></td>' +
                            '<td colspan="4">Total Amount Paid: <span class="text-success text-bold">'+sale.total_amount_paid+'</span></td></tr>' +
                            '<tr class="sales-info-bg"><td colspan="2">Completed Sales: <span class="text-success text-bold">'+sale.completed_sales+'</span></td>' +
                            '<td colspan="3">Pending Sales: <span class="text-danger text-bold">'+sale.pending_sales+'</span></td>' +
                            '<td colspan="4">Total No. of Sales: <span class="text-info text-bold">'+sale.total_sales+'</span></td></tr>')
                }
            });
        </script>
    @endpush
@endonce
