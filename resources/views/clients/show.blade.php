@extends('adminlte::page')

@section('title', 'Client Profile')

@section('content_header')
    <h1></h1>
@stop
<style>

</style>
@section('content')

    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan">Client Profile</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                <li class="breadcrumb-item"><a href="{{route('clients.index')}}">Clients</a> </li>
                <li class="breadcrumb-item active">{{ucwords($client->full_name)}} </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header bg-gradient-info">
                    <h3 class="card-title">{{ucwords($client->full_name)}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <strong><i class="fa fa-mobile-alt mr-1"></i> Mobile #</strong>

                    <p class="text-muted">
                        {{$client->mobile_number}}
                    </p>

                    <hr>

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                    <p class="text-muted">{{ucwords($client->address)}}</p>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="transaction-list" class="table table-striped table-hover w-100 border border-2">
                        <thead>
                        <tr>
                            <th>Spa/Salon</th>
                            <th>Service</th>
                            <th>Payable Amount</th>
                            <th>Service Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Room</th>
                            <th>Masseur</th>
                            <th>Invoice #</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)


@section('css')
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#transaction-list').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('client.transactions',['client' => $client->id]) !!}',
            columns: [
                { data: 'spa_id', name: 'spa_id'},
                { data: 'service_name', name: 'service_name'},
                { data: 'payable_amount', name: 'payable_amount'},
                { data: 'duration', name: 'duration'},
                { data: 'start_date', name: 'start_date'},
                { data: 'end_date', name: 'end_date'},
                { data: 'room_id', name: 'room_id'},
                { data: 'therapists', name: 'therapists'},
                { data: 'invoice_number', name: 'invoice_number'},
                // { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            responsive:true,
            order:[4,'desc'],
            pageLength: 25,
            drawCallback: function(row){
                let transactions = row.json;

                $('#transaction-list').find('tbody')
                    .append('<tr class="sales-info-bg"><td colspan="9">Total Transactions: <span class="text-primary" ">'+transactions.total_transactions+'</span></td></tr>')
            }
        });
    });
</script>
@stop
