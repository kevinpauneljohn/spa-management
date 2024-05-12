@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')

@stop
@if(isset($_GET['view']))
    <style>
        #view-{{$_GET['view']}}{
            background-color: #ffebeb;
        }
    </style>
@endif
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-3">
            <h3>Sales Invoice: <span class="text-info">#{{$sale->invoice_number}}</span></h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('point-of-sale.show',['point_of_sale' => $spa->id])}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>

    <x-point-of-sale.sales.sales-tab :spa="$spa->id" :salesInvoice="$sale->invoice_number"/>

    <div class="card">
        <div class="card-header">
            <span class="float-left">
                <x-point-of-sale.transactions.delete-sales-instance-button :sale="$sale">
                    Cancel
                </x-point-of-sale.transactions.delete-sales-instance-button>
            </span>
            <span class="float-right">
                <x-point-of-sale.sales.print-invoice :salesId="$sale->id"/>

                @if(auth()->user()->hasRole('front desk'))
                    <x-point-of-sale.sales.pay-button :spaId="$spa->id" :salesId="$sale->id"/>
                    <x-point-of-sale.transactions.add-transaction :salesId="$sale->id"/>
                    @if($sale->payment_status !== 'completed')
                            <x-point-of-sale.transactions.buy-voucher :salesId="$sale->id" tableId="display-sales-client-2"/>
                    @endif
                @endif

            </span>
        </div>
        <div class="card-body table-wrapper">
            <x-sales-transaction-table :spaId="$spa->id" :saleId="$sale->id" tableId="display-sales-client-2"/>
        </div>
    </div>
    <x-point-of-sale.transactions.add-client-form :spa="$spa" :sale="$sale"/>


@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@section('js')
    @if(auth()->check())
        <script>
            $(document).ready(function(){

            });
        </script>
    @endif
@stop
