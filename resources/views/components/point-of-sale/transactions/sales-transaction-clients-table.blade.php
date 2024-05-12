<table class="table table-bordered table-hover {{$tableClass}}" id="{{$tableId}}">
    <thead>
    <tr>
        @if($displayAllColumns === false)
            <th>Client</th>
            <th>Service</th>
            <th>Service Amount</th>
            <th>Room</th>
            <th>Masseur</th>
        @else
            <th>Client</th>
            <th>Service</th>
            <th>Service Amount</th>
            <th>Payable Amount</th>
            <th>Commission Reference Amount</th>
            <th>Status</th>
            <th>Service Duration</th>
            <th>Total Time</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Room</th>
            <th>Masseur</th>
            <th>Sales Type</th>
            <th>Isolate</th>
            <th>Under Time</th>
        @endif
            <th>Void Transaction</th>


    </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@section('css')
    <style>
        .client-payment{
            background-color: #cdffd5!important;
        }
    </style>
@endsection

    @push('js')
        <script>
            $(function(){


                $('#{{$tableId}}').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('pos-sales-client-transactions',['spaId' => $spaId, 'saleId' => $saleId]) !!}',
                    columns: [
                        @if($displayAllColumns === false)
                            { data: 'client_name', name: 'client_name'},
                            { data: 'service_name', name: 'service_name'},
                            { data: 'amount', name: 'amount'},
                            { data: 'room_id', name: 'room_id'},
                            { data: 'therapists', name: 'therapists'},

                        @else
                            { data: 'client_name', name: 'client_name'},
                            { data: 'service_name', name: 'service_name'},
                            { data: 'amount', name: 'amount'},
                            { data: 'payable_amount', name: 'payable_amount'},
                            { data: 'commission_reference_amount', name: 'commission_reference_amount'},
                            { data: 'status', name: 'status'},
                            { data: 'duration', name: 'duration'},
                            { data: 'total_time', name: 'total_time'},
                            { data: 'start_date', name: 'start_date'},
                            { data: 'end_date', name: 'end_date'},
                            { data: 'room_id', name: 'room_id'},
                            { data: 'therapists', name: 'therapists'},
                            { data: 'sales_type', name: 'sales_type'},
                            // { data: 'extend_time', name: 'extend_time'},
                            { data: 'isolate', name: 'isolate',className: 'text-center'},
                            { data: 'under_time', name: 'under_time',className: 'text-center'},
                        @endif
                            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                    ],
                    autoFill:'off',
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 10,
                    "autoWidth": false,
                    drawCallback: function(row){
                        let transaction = row.json;
                        let color = 'text-primary';
                        if(transaction.payment_status === 'completed')
                        {
                            color = 'text-success';
                        }

                        $.each(transaction.vouchers,function(key, value){
                            let voidButton = transaction.sale_status !== 'completed' ? '<button type="button" class="btn btn-sm btn-outline-danger m-1 void-transaction" id="'+value.id+'" title="Void Transaction"><i class="fas fa-times"></i></button>' : '';
                            $('#{{$tableId}}').find('tbody').append('<tr><td colspan="2" class="text-primary">Voucher</td><td class="text-primary">'+value.amount+'</td>' +
                                '<td class="text-danger text-bold">'+value.price+'</td><td colspan="11"></td><td class="text-center">'+voidButton+'</td></tr>')
                        })
                        $('#{{$tableId}}').find('tbody')
                            .append('<tr><td class="text-bold" colspan="@if($displayAllColumns === false) 2 @else 7 @endif">Total Amount: <span class="text-primary">'+transaction.total_amount+'</span></td>' +
                                '<td colspan="3" class="text-bold">Total Clients: <span class="text-primary">'+transaction.total_clients+'</span></td>' +
                                '<td colspan="8" class="text-bold">Payment Status: <span class="'+color+'">'+transaction.payment_status+'</span></td></tr>' +
                                '<tr class="text-bold client-payment" style="background-color: #f3fdf5!important;">' +
                                '<td colspan="3">Amount Paid: <span class="text-success">'+transaction.amount_paid+'</span></td>' +
                                '<td colspan="4">Change: <span class="text-success">'+transaction.change+'</span></td>' +
                                @if($displayAllColumns === true)'<td colspan="3">Payment Method: <span class="text-success">'+transaction.payment_method+'</span></td>' +
                                '<td colspan="3">Non-cash amount: <span class="text-success">'+transaction.non_cash_amount+'</span></td>' +
                                '<td colspan="3">Cash amount: <span class="text-success">'+transaction.cash_amount+'</span></td>' +
                                @endif'</tr>')
                    }
                });

            });
        </script>
    @endpush

@once
    @push('js')
        <script>
            $(document).on('click','.isolate',function(){
                let transactionId = this.id;

                $tr = $(this).closest('tr');
                id = this.id;
                let data = $tr.children('td').map(function () {
                    return $(this).text();
                }).get();


                Swal.fire({
                    title: 'Isolate '+data[0]+'?',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.value === true) {

                        $.ajax({
                            url: '/isolate-transaction/{{$spaId}}/'+transactionId,
                            type: 'patch',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },
                        }).done(function(transaction){

                            if(transaction.success === true)
                            {
                                $('#button-container').load('{{url()->current()}} #button-container');
                                $('.display-sales-client').DataTable().ajax.reload(null, false);
                                Swal.fire(transaction.message, '', 'success')
                                setTimeout(function (){

                                },2500)
                                window.location.replace('/point-of-sale/add-transaction/{{$spaId}}/'+transaction.sales_id)
                            }else{
                                Swal.fire(transaction.message, '', 'warning')
                            }
                        }).fail( (xhr, data, error) => {
                            console.log(xhr)
                            if(xhr.status === 403 || xhr.status === 404)
                            {
                                let errorMessage = xhr.responseJSON.message !== '' ? xhr.responseJSON.message : 'An error occurred'
                                Swal.fire('Warning!', errorMessage, 'warning')
                            }
                        });

                    }
                })

            })

            $(document).on('change','.extend_time',function(){
                let id = this.id;
                let plus_time = $(this).val();

                $tr = $(this).closest('tr');
                id = this.id;
                let data = $tr.children('td').map(function () {
                    return $(this).text();
                }).get();

                Swal.fire({
                    title: 'Extend Time?',
                    html:
                         'Additional <strong class="text-info">'+plus_time+' minutes</strong> extension for <br/><strong class="text-primary">'+data[0]+'</strong>',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {

                    if (result.value === true) {

                        $.ajax({
                            url: '/extend-time/'+id,
                            type: 'patch',
                            data: {time:plus_time},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },
                        }).done(function(transaction){
                            console.log(transaction)
                            if(transaction.success === true)
                            {
                                $('#{{$tableId}}').DataTable().ajax.reload(null, false);
                                Swal.fire(transaction.message, '', 'success')

                            }else{
                                Swal.fire(transaction.message, '', 'warning')
                            }
                        });

                    }else{
                        $('#{{$tableId}}').DataTable().ajax.reload(null, false);
                    }
                })
            })

            $(document).on('click','.under-time',function(){

                $tr = $(this).closest('tr');
                let id = this.id;
                let data = $tr.children('td').map(function () {
                    return $(this).text();
                }).get();


                Swal.fire({
                    type: 'warning',
                    title: 'Under Time?',
                    html:'<strong class="text-primary" style="font-size: 20pt">'+data[0]+'</strong>',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {

                    if (result.value === true) {

                        $.ajax({
                            url: '/under-time/transaction/'+id,
                            type: 'patch',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },
                        }).done(function(transaction){
                            console.log(transaction)
                            if(transaction.success === true)
                            {
                                {{--$('.sales-client-form').trigger('reset')--}}
                                {{--$('#{{$tableId}}').DataTable().ajax.reload(null, false);--}}
                                // Swal.fire(transaction.message, '', 'success')
                                Swal.fire(transaction.message, 'Page Reloading...', 'success')
                                setTimeout(function(){
                                    window.location.reload()
                                },2000)

                            }else{
                                Swal.fire(transaction.message, '', 'warning')
                            }
                        });

                    }else{
                        $('#{{$tableId}}').DataTable().ajax.reload(null, false);
                    }
                })
            })
        </script>
    @endpush
@endonce
