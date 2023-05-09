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
    <div class="col-md-12">
        <table id="inventory-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                <th>Spa</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Category</th>
                <th>SKU</th>
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

                    <x-inventory-form :formDefault="false"/>

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button type="submit" class="bg-olive" id="inventory-modal-btn" label="Save"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
    @endif
@once
    @push('js')
        <script>
            $(document).ready(function() {
                $('#inventory-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('inventory.lists') !!}',
                    columns: [
                        { data: 'spa_id', name: 'spa_id'},
                        { data: 'name', name: 'name'},
                        { data: 'description', name: 'description'},
                        { data: 'quantity', name: 'quantity'},
                        { data: 'unit', name: 'unit'},
                        { data: 'category', name: 'category'},
                        { data: 'sku', name: 'sku'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });
        </script>
    @endpush
@endonce


