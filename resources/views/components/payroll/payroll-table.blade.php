<table id="therapist-sales-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
    <thead>
    <tr role="row">
        <th>Therapist</th>
        <th>Total Clients</th>
        <th>Gross Sales</th>
        <th>Offer Type</th>
        <th>Commission Rate</th>
        <th>Commission Amount</th>
        <th>Gross Sales Commission</th>
        <th>View Summary</th>
    </tr>
    </thead>
</table>


@once
    @push('js')
        <script>
            $(document).ready(function(){
                $('#therapist-sales-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('get.therapists.sales',['spa' => $spaId]) !!}',
                    columns: [
                        { data: 'therapist', name: 'therapist'},
                        { data: 'total_clients', name: 'total_clients'},
                        { data: 'gross_sales', name: 'gross_sales'},
                        { data: 'offer_type', name: 'offer_type'},
                        { data: 'commission_percentage', name: 'commission_percentage'},
                        { data: 'commission_flat', name: 'commission_flat'},
                        { data: 'gross_commission', name: 'gross_commission'},
                        { data: 'summary', name: 'summary', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 10
                });
            });
        </script>
    @endpush
@endonce
