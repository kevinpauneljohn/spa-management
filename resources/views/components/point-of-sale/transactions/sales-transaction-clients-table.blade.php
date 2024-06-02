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
            <th>Discount</th>
            <th>Status</th>
            <th>Service Duration</th>
{{--            <th>Total Time</th>--}}
            <th>Start Date</th>
            <th>End Date</th>
            <th>Room</th>
            <th>Masseur</th>
            <th>Sales Type</th>
            <th>Apply/Remove Discount</th>
            <th>Isolate</th>
            <th>Under Time</th>
        @endif
            <th>Void Transaction</th>
            @if(auth()->user()->hasRole('owner'))
                <th>
                    Owner Action
                </th>
            @endif


    </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@if(auth()->user()->hasRole('owner'))
    <form id="edit-transaction-form">
        @csrf
        <x-adminlte-modal id="edit-transaction-modal" title="Edit Transaction" theme="olive"
                          icon="fas fa-bell" v-centered static-backdrop scrollable>
            <div>
                <div class="form-group client">
                    <label>Client</label>
                    <x-adminlte-select2 name="client" id="client">
                        <option value=""> -- Select client -- </option>
                        @foreach($clients as $client)
                            <option value="{{$client->id}}">{{ucwords($client->full_name)}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="form-group service">
                    <label>Service</label>
                    <x-adminlte-select2 name="service" id="service">
                        <option value=""> -- Select service -- </option>
                        @foreach($services as $service)
                            <option value="{{$service->id}}">{{ucwords($service->name)}} - {{number_format($service->price,2)}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="form-group edit_therapist_1">
                    <label>Therapist 1</label>
                    <x-adminlte-select2 name="edit_therapist_1" id="therapist_1">
                        <option value=""> -- Select therapist -- </option>
                        @foreach($therapists as $therapist)
                            <option value="{{$therapist->id}}">{{ucwords($therapist->full_name)}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="form-group edit_therapist_2">
                    <label>Therapist 2</label>
                    <x-adminlte-select2 name="edit_therapist_2">
                        <option value=""> -- Select therapist -- </option>
                        @foreach($therapists as $therapist)
                            <option value="{{$therapist->id}}">{{ucwords($therapist->full_name)}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <x-slot name="footerSlot">

                <x-adminlte-button class="mr-auto" theme="danger" label="Close" data-dismiss="modal"/>
                <x-adminlte-button type="submit" theme="success" label="Save"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

@endif

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
                            { data: 'client_id', name: 'client_id'},
                            { data: 'service_name', name: 'service_name'},
                            { data: 'amount', name: 'amount'},
                            { data: 'room_id', name: 'room_id'},
                            { data: 'therapists', name: 'therapists'},

                        @else
                            { data: 'client_id', name: 'client_id'},
                            { data: 'service_id', name: 'service_id'},
                            { data: 'amount', name: 'amount'},
                            { data: 'payable_amount', name: 'payable_amount'},
                            { data: 'commission_reference_amount', name: 'commission_reference_amount'},
                            { data: 'discount_amount', name: 'discount_amount'},
                            { data: 'status', name: 'status'},
                            { data: 'duration', name: 'duration'},
                            // { data: 'total_time', name: 'total_time'},
                            { data: 'start_date', name: 'start_date'},
                            { data: 'end_date', name: 'end_date'},
                            { data: 'room_id', name: 'room_id'},
                            { data: 'therapists', name: 'therapists'},
                            { data: 'sales_type', name: 'sales_type'},
                            // { data: 'extend_time', name: 'extend_time'},
                            { data: 'apply_discount', name: 'apply_discount', className: 'text-center'},
                            { data: 'isolate', name: 'isolate',className: 'text-center'},
                            { data: 'under_time', name: 'under_time',className: 'text-center'},
                        @endif
                            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                        @if(auth()->user()->hasRole('owner'))
                            { data: 'edit', name: 'edit', orderable: false, searchable: false, className: 'text-center'},
                        @endif
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
                            let voidButton = transaction.sale_status !== 'completed' ? '<button type="button" class="btn btn-sm btn-outline-danger m-1 remove-voucher" id="'+value.id+'" title="Void Transaction"><i class="fas fa-times"></i></button>' : '';
                            $('#{{$tableId}}').find('tbody').append('<tr><td colspan="2" class="text-primary">Voucher</td><td class="text-primary">'+value.amount+'</td>' +
                                '<td class="text-danger text-bold">'+value.price+'</td><td colspan="9">'+value.title+'</td><td colspan="2"></td><td class="text-center">'+voidButton+'</td></tr>')
                        })
                        $('#{{$tableId}}').find('tbody')
                            .append('<tr><td class="text-bold" colspan="@if($displayAllColumns === false) 2 @else 7 @endif">Total Amount: <span class="text-primary">'+transaction.total_amount+'</span></td>' +
                                '<td colspan="3" class="text-bold">Total Clients: <span class="text-primary">'+transaction.total_clients+'</span></td>' +
                                '<td colspan="10" class="text-bold">Payment Status: <span class="'+color+'">'+transaction.payment_status+'</span></td></tr>' +
                                '<tr class="text-bold client-payment" style="background-color: #f3fdf5!important;">' +
                                '<td colspan="3">Amount Paid: <span class="text-success">'+transaction.amount_paid+'</span></td>' +
                                '<td colspan="4">Change: <span class="text-success">'+transaction.change+'</span></td>' +
                                @if($displayAllColumns === true)'<td colspan="3">Payment Method: <span class="text-success">'+transaction.payment_method+'</span></td>' +
                                '<td colspan="3">Non-cash amount: <span class="text-success">'+transaction.non_cash_amount+'</span></td>' +
                                '<td colspan="5">Cash amount: <span class="text-success">'+transaction.cash_amount+'</span></td>' +
                                @endif'</tr>')
                    }
                });

            });

            $(document).on('click','.remove-voucher',function(){
                let id = this.id;
                $.ajax({
                    url: '/discounts/'+id,
                    type: 'delete',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function(){

                    }
                }).done(function(response){
                    console.log(response)
                    $('#button-container').load('{{url()->current()}} #button-container');
                    $('.display-sales-client').DataTable().ajax.reload(null, false);

                    let url = window.location.href;
                    $('#voucher').load(url+' #voucher option');
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                }).always(function(){

                });
            })
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

            @if(auth()->user()->hasRole('owner'))
                let editTransactionModal = $('#edit-transaction-modal');
                let editTransactionId;
                $(document).on('click','.edit-transaction',function(){
                    editTransactionId = this.id;
                    editTransactionModal.modal('toggle');

                    $.ajax({
                        url: '/transaction/'+editTransactionId,
                        type: 'get',
                        beforeSend: function(){
                            editTransactionModal.find('.modal-content').append(overlay);
                        }
                    }).done(function(response){
                        // console.log(response)
                        editTransactionModal.find('select[name=client]').val(response.data.transaction.client_id).change();
                        editTransactionModal.find('select[name=service]').val(response.data.transaction.service_id).change();
                        editTransactionModal.find('select[name=edit_therapist_1]').val(response.data.transaction.therapist_1).change();
                        editTransactionModal.find('select[name=edit_therapist_2]').val(response.data.transaction.therapist_2).change();
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    }).always(function(){
                        editTransactionModal.find('.overlay').remove();
                    });
                });

                $(document).on('submit','#edit-transaction-form', function(form){
                    form.preventDefault();
                    let data = $(this).serializeArray();
                    $.ajax({
                        url: '/transaction-updated-by-owner/'+editTransactionId,
                        type: 'patch',
                        data: data,
                        beforeSend: function(){
                            editTransactionModal.find('.modal-content').append(overlay);
                        }
                    }).done(function(response){
                        console.log(response)
                        if(response.success === true)
                        {
                            Swal.fire(response.message, '', 'success')
                            $('#{{$tableId}}').DataTable().ajax.reload(null, false);
                            editTransactionModal.modal('toggle');
                        }else{
                            Swal.fire(response.message, '', 'warning')
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                        $.each(xhr.responseJSON.errors, function(key, value){
                            editTransactionModal.find('.'+key).append('<p class="text-danger">'+value+'</p>')
                        })
                    }).always(function(){
                        editTransactionModal.find('.overlay').remove();
                    });
                });
            @endif
        </script>
    @endpush
@endonce
