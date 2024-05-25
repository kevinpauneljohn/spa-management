<div class="row mb-1">
    <div class="col-md-12">

{{--        <div class="alert alert-default-info">--}}
{{--            <h5><i class="fas fa-info"></i> Note:</h5>--}}
{{--            Add masseur/masseuse to your spa who will serve your valued customers--}}
{{--        </div>--}}
        @can('add inventory')
            <x-adminlte-button label="Add Item" data-toggle="modal" data-target="#inventory-modal" id="inventory-modal-btn" class="bg-olive float-right mb-3"/>
        @endcan
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table id="inventory-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                @if(auth()->user()->hasRole('owner'))
                    <th>Spa</th>
                @endif
                <th>Item Name</th>
                <th style="width:30%">Description</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Re-stock Limit</th>
                <th>Category</th>
                <th>Updated By</th>
                <th>Updated At <br/><span class="text-muted font-italic">(Y-M-D h:m:s)</span></th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@if(auth()->user()->can('add inventory') || auth()->user()->can('edit inventory'))
    <form class="inventory-form" id="inventory-form">
        @csrf
        <x-adminlte-modal class="inventory-modal" id="inventory-modal" title="Add New Item" size="md" theme="olive"
                          v-centered static-backdrop scrollable>

                    <x-inventory.inventory-form :formDefault="false" :spaId="$spaId"/>

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button type="submit" class="bg-olive" id="inventory-modal-btn" label="Save"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
    @endif

@if(auth()->user()->can('manage inventory'))
    <x-inventory.inventory-update />
@endif
@section('plugins.CustomCSS',true)
@once
    @push('js')
        <script>
            $(document).ready(function() {
                $('#inventory-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '@if($spaId){!! route('spa.inventory.lists',['spa' => $spaId]) !!}@else{!! route('inventory.lists') !!}@endif',
                    columns: [
                        @if(auth()->user()->hasRole('owner'))
                        { data: 'spa_id', name: 'spa_id'},
                        @endif

                        { data: 'name', name: 'name'},
                        { data: 'description', name: 'description'},
                        { data: 'quantity', name: 'quantity'},
                        { data: 'unit', name: 'unit'},
                        { data: 'restock_limit', name: 'restock_limit'},
                        { data: 'category', name: 'category'},
                        { data: 'user_id', name: 'user_id'},
                        { data: 'updated_at', name: 'updated_at'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                    ],
                    autoFill:'off',
                    responsive:true,
                    order:[@if(auth()->user()->hasRole('owner')) 8 @else 7 @endif,'desc'],

                    pageLength: 50
                });
            });
        </script>
    @endpush
@endonce


