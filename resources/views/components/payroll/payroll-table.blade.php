<table id="therapist-sales-list" class="table table-striped table-hover border border-2" role="grid" style="width:100%;">
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

<div class="modal fade" id="view-therapist-sales-summary">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-bordered" id="view-summary-details-table"></table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<button type="button" class="btn btn-primary test-lang">click</button>
@once
    @push('js')
        <script>
            let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
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
                    buttons: [
                        {
                            text: 'My button',
                            action: function ( e, dt, node, config ) {
                                alert( 'Button activated' );
                            }
                        }
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 10,
                    drawCallback: function(row){
                        let therapist = row.json;

                        $('#therapist-sales-list').find('tbody')
                            .append('<tr class="text-bold"><td>Total</td>' +
                                '<td class="text-success">'+therapist.total_clients+'</td><td class="text-success">'+therapist.total_gross_sales+'</td>' +
                                '<td colspan="2"></td><td></td><td class="text-success">'+therapist.total_gross_sales_commissions_formatted+'</td>' +
                                '<td>Net Sales: <span class="text-success">'+therapist.net_sales+'</span></td></tr>')
                    }
                });
            });

            let therapistModal = $('#view-therapist-sales-summary');
            $(document).on('click','.view-summary',function(){
                let id = this.id;

                therapistModal.modal('toggle')
                therapistModal.find('.modal-title').text('View Sales Summary');
                therapistModal.find('#view-summary-details-table').html('');
                $.ajax({
                    url: '/therapist/transactions/'+id,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function(){
                        therapistModal.find('.modal-content').append(overlay);
                    }
                }).done((result) => {
                    // console.log(result)
                    therapistModal.find('#view-summary-details-table').append('<tr>' +
                        '<th colspan="8"><h2>'+result.therapist.full_name+'</h2></th><th style="width: 5%!important;">'+result.therapist.offer_type.replace('_',' ')+'</th><th class="text-info">'+result.therapist.commission+'</th></tr>');
                    therapistModal.find('#view-summary-details-table').append('<tr>' +
                        '<th></th><th>invoice</th><th>Start Date</th><th>End Date</th><th>Client</th><th>Room Number</th><th>Service</th><th>Service Amount</th><th>Reference Amount</th><th>Base Amount</th><th>Receivables</th></tr>');
                    var number = 1;


                    $.each(result.data, function(key, value){
                        console.log(value);
                        therapistModal.find('#view-summary-details-table')
                            .append('<tr><td>#'+number+++'</td>' +
                                '<td><a href="/point-of-sale/add-transaction/'+value.spa_id+'/'+value.sales_id+'" target="_blank">View Invoice</a></td>' +
                                '<td>'+value.start_date+'</td>' +
                                '<td>'+value.end_date+'</td>' +
                                '<td>'+value.client_name+'</td>' +
                                '<td class="text-primary">#'+value.room_id+'</td>' +
                                '<td>'+value.service_name+'</td>' +
                                '<td>'+value.amount+'</td>' +
                                '<td>'+value.commission_reference_amount+'</td>' +
                                '<td>'+value.gross_sale+'</td>' +
                                '<td>'+(parseFloat(value.gross_sale) * (result.commission_rate)).toFixed(2)+'</td></tr>');
                    });
                    therapistModal.find('#view-summary-details-table').append('<tr>' +
                        '<th colspan="7">Gross Sales<span class="text-primary ml-2">'+result.gross_sales+'</span></th><th colspan="3"><span class="float-right">Gross Commissions: <span class="text-primary ml-2">'+result.gross_sales_commission+'</span></span></th></tr>');

                })
                    .always(() => therapistModal.find('.overlay').remove());
            })

            $(document).on('click','.test-lang',function(){
                $('#therapist-sales-list').DataTable().ajax.reload(null, false);
            });
        </script>
    @endpush
@endonce

@section('css')
    <style>
        #view-summary-details-table tr:hover{
            background-color: #ececec;
            cursor: pointer;
        }
    </style>
@stop


