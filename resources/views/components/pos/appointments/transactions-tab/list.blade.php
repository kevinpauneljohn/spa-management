<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    List of all sales. Please update the payment status once the client has paid.
</div>
<div class="table-responsive">
    <table id="transaction-data-lists" class="table table-striped table-valign-middle" style="width:100%;">
        <thead>
            <tr>
                <th>Spa</th>
                <th>Client</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Paid At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
@section('css')

@endsection

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/alerts.js')}}"></script>
        <script>
            $(document).ready(function(){
                $('#transaction-data-lists').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/sales-list/{{$spaId}}'
                    },
                    columns: [
                        { data: 'spa', name: 'spa', className: 'text-center'},
                        { data: 'client', name: 'client', className: 'text-center'},
                        { data: 'payment_status', name: 'payment_status'},
                        { data: 'amount', name: 'amount', className: 'text-center'},
                        { data: 'date', name: 'date', className: 'text-center'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    language: {
                        "processing": '<div class="dataTables_processing text-primary text-bold">Loading Transactions Data...</div>'
                    },
                    "bDestroy": true,
                    responsive:true,
                    order:[2,'asc'],
                    pageLength: 10
                });
            });
        </script>
    @endif
@endpush