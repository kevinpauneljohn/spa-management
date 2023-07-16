<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    List of upcoming clients. Please move and update the start time of the appointment once the client has arrived.
</div>
<div class="table-responsive">
    <table id="appointment-data-lists" class="table table-striped table-valign-middle" style="width:100%">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Service</th>
                <th>Batch #</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date Added</th>
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
        <script src="{{asset('js/frontdesk/UpcomingTabComponent/app.js')}}"></script>
        <script src="{{asset('js/frontdesk/UpcomingTabComponent/action.js')}}"></script>
        <script>
            $(document).ready(function(){
                $('#appointment-data-lists').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/appointment-lists/{{$spaId}}'
                    },
                    columns: [
                        { data: 'client', name: 'client'},
                        { data: 'service', name: 'service'},
                        { data: 'batch', name: 'batch'},
                        { data: 'amount', name: 'amount', className: 'text-center'},
                        { data: 'type', name: 'type', className: 'text-center'},
                        { data: 'status', name: 'status', className: 'text-center'},
                        { data: 'date', name: 'date', className: 'text-center'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    language: {
                        "processing": '<div class="dataTables_processing text-primary text-bold">Loading Upcoming Data...</div>'
                    },
                    "bDestroy": true,
                    responsive:true,
                    order:[3,'desc'],
                    pageLength: 10
                });

                getUpcomingAppointmentType();
            });
        </script>
    @endif
@endpush