<div>
    <table id="owners-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
        <thead>
        <tr role="row">
            <th>Date Added</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Mobile Number</th>
            <th>Date Of Birth</th>
            <th>Number of Spa</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>

@once
    @push('js')
        <script>
            $(document).ready(function(){
                $('#owners-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('owner.lists') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at', className: 'text-center' },
                        { data: 'fullname', name: 'fullname'},
                        { data: 'email', name: 'email'},
                        { data: 'username', name: 'username'},
                        { data: 'mobile_number', name: 'mobile_number'},
                        { data: 'date_of_birth', name: 'date_of_birth'},
                        { data: 'qty_of_spa', name: 'qty_of_spa'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });
        </script>
    @endpush
@endonce


