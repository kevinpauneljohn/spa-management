@extends('adminlte::page')

@section('title', 'Coupons & Vouchers')

@section('content_header')

@stop

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-4">
            <h3 class="text-info">Coupons & Vouchers</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a> </li>
                <li class="breadcrumb-item active">Coupons & Vouchers </li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <x-adminlte-button label="Add" theme="primary" id="add-discount-btn" data-toggle="modal" data-target="#add-discount"/>
        </div>
        <div class="card-body table-responsive">
            <table id="discount-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                <thead>
                <tr role="row">
                    <th>Date Added</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Amount/Percent</th>
                    <th>Price</th>
                    <th>Client</th>
                    <th>Sales Invoice</th>
                    <th>Date Claimed</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-adminlte-modal id="code-modal" title="Code Generator" theme="teal" size="sm">
        <div id="code-content" style="display: flex; align-items: center; justify-content: center"></div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" class="w-100"/>
        </x-slot>
    </x-adminlte-modal>


    <form id="discount-form">
        @csrf
        <x-adminlte-modal id="add-discount" title="Add Voucher/Coupon" theme="teal">
            <div class="form-group type">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value=""> -- Select type -- </option>
                    <option value="voucher">Voucher</option>
                    <option value="coupon">Coupon</option>
                </select>
            </div>
            <div class="form-group value_type">
                <label for="value_type">Value Type</label>
                <select name="value_type" id="value_type" class="form-control">
                    <option value=""> -- Select type -- </option>
                    <option value="amount">Amount</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            <div class="form-group amount">
                <label for="amount">Amount / Percentage</label>
                <input type="number" step="any" name="amount" class="form-control" id="amount" min="0">
            </div>
            <div class="form-group price">
                <label for="price">Price</label>
                <input type="number" step="any" name="price" class="form-control" id="price" min="0">
            </div>
            <div class="form-group client">
                <label for="client">Assign Client</label>
                <select name="client" class="form-control select2" id="client" style="width: 100%;">
                    <option value=""> -- Select Client -- </option>
                    @foreach($clients as $client)
                        <option value="{{$client->id}}">{{ucwords(strtolower($client->full_name))}} - {{$client->mobile_number}}</option>
                    @endforeach
                </select>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="success" label="Save" type="submit"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
@stop
@section('plugins.Toastr',true)
@section('footer')
    <strong>Copyright © 2023 <a href="https://adminlte.io">DHG IT Solutions</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
    </div>
@stop
@section('css')
@stop

@section('js')
    <script src="{{asset('/js/Discounts/discounts.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#discount-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('discount.table') !!}',
                columns: [
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'code', name: 'code'},
                    { data: 'type', name: 'type'},
                    { data: 'discount_amount', name: 'discount_amount'},
                    { data: 'price', name: 'price'},
                    { data: 'client_id', name: 'client_id'},
                    { data: 'sales_invoice', name: 'sales_invoice'},
                    { data: 'date_claimed', name: 'date_claimed'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 10
            });
        });
    </script>
@stop
