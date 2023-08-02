<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    List of clients and transactions.
</div>
<div class="table-responsive">
    <table id="sales-data-lists" class="table table-striped" style="width: 100%;">
        <thead>
            <tr>
                <th>Client</th>
                <th>Service</th>
                <th>Masseur</th>
                <th>Start Time</th>
                <th>Plus Time</th>
                <th>End Time</th>
                <th>Room #</th>
                <th>Amount</th>
                <th>Status</th>
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
        <script src="{{asset('js/frontdesk/GuestTabComponent/app.js')}}"></script>
        <script src="{{asset('js/frontdesk/GuestTabComponent/action.js')}}"></script>
        <script>
            $(document).ready(function(){
                $('#sales-data-lists').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/transaction-list/{{$spaId}}'
                    },
                    columns: [
                        { data: 'client', name: 'client', className: 'text-center'},
                        { data: 'service', name: 'service'},
                        { data: 'masseur', name: 'masseur'},
                        { data: 'start_time', name: 'start_time'},
                        { data: 'plus_time', name: 'plus_time', className: 'text-center'},
                        { data: 'end_time', name: 'end_time', className: 'text-center'},
                        { data: 'room', name: 'room', className: 'text-center'},
                        { data: 'amount', name: 'amount', className: 'text-center'},
                        { data: 'status', name: 'status', className: 'text-center'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    language: {
                        "processing": '<div class="dataTables_processing text-primary text-bold">Loading Guest Data...</div>'
                    },
                    "bDestroy": true,
                    responsive:true,
                    order:[8,'asc'],
                    pageLength: 10
                });
            });
        </script>
    @endif
@endpush