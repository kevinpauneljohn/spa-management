{{--<table class="table table-bordered table-hover" id="activity-logs-table">--}}

{{--</table>--}}
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="float-right mr-4">{{$transactionLogs->links()}}</div>
    </div>
    <div class="col-md-12">
        <div class="timeline">


            @foreach($transactionLogs as $transactionLog)
                <div class="time-label">
                    <span class="bg-secondary">{{$transactionLog->created_at->format('d M Y')}}</span>
                </div>

                <div>
                    @if($transactionLog->description === 'created transaction')
                        <i class="fas fa-cart-plus bg-success"></i>
                    @elseif($transactionLog->description === 'voided a transaction')
                        <i class="fas fa-trash bg-danger"></i>
                    @endif
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i> {{$transactionLog->created_at->format('h:s a')}}</span>
                        <h3 class="timeline-header"><a href="#">{{ucwords($transactionLog->properties['causer_name'])}}</a>
                            @if($transactionLog->description === "Client Isolated")
                                isolated a client
                            @elseif($transactionLog->description === "Sales Payment")
                                processed a payment
                            @else
                                {{$transactionLog->description}}
                            @endif
                            </h3>
                        <div class="timeline-body">
                            @if($transactionLog->description === 'created transaction')
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Plus</th>
                                        <th>Sales Invoice</th>
                                        <th>Therapist 1</th>
                                        <th>Therapist 2</th>
                                    </tr>
                                    <tr>
                                        <td>{{$transactionLog->properties['client_name']}}</td>
                                        <td>{{$transactionLog->properties['service_name']}}</td>
                                        <td class="text-info">{{number_format($transactionLog->properties['amount'],2)}}</td>
                                        <td>{{\Carbon\Carbon::parse($transactionLog->properties['start_time'])->setTimezone('Asia/Manila')->format('d-M-Y h:s a')}}</td>
                                        <td>{{\Carbon\Carbon::parse($transactionLog->properties['end_time'])->setTimezone('Asia/Manila')->format('d-M-Y h:s a')}}</td>
                                        <td>{{$transactionLog->properties['plus_time']}}</td>
                                        <td class="text-info">#{{substr($transactionLog->properties['sales_id'],0,8)}}</td>
                                        <td><a href="{{route('therapists.profile',['id' => $transactionLog->properties['therapist_1']])}}" target="_blank">{{$transactionLog->properties['therapist_1_name']}}</a></td>
                                        <td><a href="@if($transactionLog->properties['therapist_2'] !== null) {{route('therapists.profile',['id' => $transactionLog->properties['therapist_2']])}} @endif" target="_blank">{{$transactionLog->properties['therapist_2_name']}}</a></td>
                                    </tr>
                                </table>

                            @elseif($transactionLog->description === 'voided a transaction')
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Sales Id</th>
                                        <th>Transaction Id</th>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                    <tr>
                                        <td>{{$transactionLog->properties['sales_id']}}</td>
                                        <td>{{$transactionLog->properties['transactionId']}}</td>
                                        <td>{{$transactionLog->properties['client_name']}}</td>
                                        <td>{{$transactionLog->properties['service_name']}}</td>
                                        <td class="text-info">{{number_format($transactionLog->properties['amount'],2)}}</td>
                                        <td>{{\Carbon\Carbon::parse($transactionLog->properties['start_time'])->format('d-M-Y h:s a')}}</td>
                                        <td>{{\Carbon\Carbon::parse($transactionLog->properties['end_time'])->format('d-M-Y h:s a')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="9">
                                            <h4 class="text-info">Reason</h4>
                                            <p>
                                                {{$transactionLog->properties['void_reason']}}
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                            @elseif($transactionLog->description === 'Client Isolated')
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>From Sales Invoice #</th>
                                            <th>To Sales Invoice #</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{$transactionLog->properties['client_name']}}
                                            </td>
                                            <td>
                                                @if(\App\Models\Sale::where('id',$transactionLog->properties['previous_sales_id'])->count() > 0)
                                                    <a href="{{route('pos.add.transaction',[
                                                        'spa' => $transactionLog->properties['spa_id'],
                                                        'sale' => $transactionLog->properties['previous_sales_id']
                                                    ])}}" target="_blank">#{{substr($transactionLog->properties['previous_sales_id'],0,8)}}</a>
                                                @else
                                                    #{{substr($transactionLog->properties['previous_sales_id'],0,8)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(\App\Models\Sale::where('id',$transactionLog->properties['sales_id'])->count() > 0)
                                                    <a href="{{route('pos.add.transaction',[
                                                        'spa' => $transactionLog->properties['spa_id'],
                                                        'sale' => $transactionLog->properties['sales_id']
                                                    ])}}" target="_blank">#{{substr($transactionLog->properties['sales_id'],0,8)}}</a>
                                                @else
                                                    #{{substr($transactionLog->properties['sales_id'],0,8)}}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @elseif($transactionLog->description === 'Sales Payment')
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Sales Invoice #</th>
                                        <th>Total Amount</th>
                                        <th>Amount Paid</th>
                                        <th>Client Cash</th>
                                        <th>Payment Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-info">
                                            <a href="{{route('pos.add.transaction',[
                                                        'spa' => $transactionLog->properties['spa_id'],
                                                        'sale' => $transactionLog->properties['id']
                                                    ])}}" target="_blank">
                                                #{{substr($transactionLog->properties['id'],0,8)}}
                                            </a>
                                        </td>
                                        <td>{{number_format($transactionLog->properties['total_amount'],2)}}</td>
                                        <td>{{number_format($transactionLog->properties['amount_paid'],2)}}</td>
                                        <td>{{number_format($transactionLog->properties['client_cash'],2)}}</td>
                                        <td>{{$transactionLog->properties['payment_method']}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

                <!-- END timeline item -->
                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="float-right mr-4">{{$transactionLogs->links()}}</div>
    </div>
</div>

@once
    @push('js')
        <script>
            $(document).ready(function(){
                {{--$('#activity-logs-table').DataTable({--}}
                {{--    processing: true,--}}
                {{--    serverSide: true,--}}
                {{--    ajax: '{!! route('point-of-sale-lists',['spa' => $spaId]) !!}',--}}
                {{--    columns: [--}}
                {{--        { data: 'created_at', name: 'created_at'},--}}
                {{--        { data: 'invoice_number', name: 'invoice_number'},--}}
                {{--        { data: 'rooms', name: 'rooms', className: 'text-center'},--}}
                {{--        { data: 'completed', name: 'completed', className: 'text-center'},--}}
                {{--        { data: 'total_amount', name: 'total_amount'},--}}
                {{--        { data: 'payment_status', name: 'payment_status'},--}}
                {{--        { data: 'payment_required', name: 'payment_required'},--}}
                {{--        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}--}}
                {{--    ],--}}
                {{--    autoFill:'off',--}}
                {{--    responsive:true,--}}
                {{--    order:[0,'desc'],--}}
                {{--    pageLength: 10,--}}
                {{--    "autoWidth": false,--}}
                {{--    drawCallback: function(row){--}}
                {{--        let sale = row.json;--}}

                {{--        $('#dashboard-sales-table-list').find('tbody')--}}
                {{--            .append('<tr class="sales-info-bg">' +--}}
                {{--                '<td colspan="2">Total No. Clients: <span class="text-info text-bold">'+sale.total_clients+'</span></td>' +--}}
                {{--                '<td colspan="3">Total Amount: <span class="text-info text-bold">'+sale.total_expected_amount+'</span></td>' +--}}
                {{--                '<td colspan="3">Total Amount Paid: <span class="text-success text-bold">'+sale.total_amount_paid+'</span></td></tr>' +--}}
                {{--                '<tr class="sales-info-bg"><td colspan="2">Completed Sales: <span class="text-success text-bold">'+sale.completed_sales+'</span></td>' +--}}
                {{--                '<td colspan="3">Pending Sales: <span class="text-danger text-bold">'+sale.pending_sales+'</span></td>' +--}}
                {{--                '<td colspan="3">Total No. of Sales: <span class="text-info text-bold">'+sale.total_sales+'</span></td></tr>')--}}
                {{--    }--}}
                {{--});--}}
            });
        </script>
    @endpush
@endonce
