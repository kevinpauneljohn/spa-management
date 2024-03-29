<div class="row mb-1">
    <div class="col-md-12">
                <div class="alert alert-default-info">
                    <h5><i class="fas fa-info"></i> Note:</h5>
                    Add Categories to your inventory
                </div>
        @can('add inventory')
            <x-adminlte-button label="Add Category" data-toggle="modal" data-target="#category-modal" id="category-modal-btn" class="bg-olive float-right mb-3"/>
        @endcan
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="category-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                <th>Category</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@if(auth()->user()->can('add category') || auth()->user()->can('edit category'))
    <form class="category-form" id="category-form">
        @csrf
        <x-adminlte-modal class="category-modal" id="category-modal" title="Add New Category" size="md" theme="olive"
                          v-centered static-backdrop scrollable>

            <x-inventory.inventory-category-form :formDefault="false"/>

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button type="submit" class="bg-olive" id="category-modal-btn" label="Save"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
@endif
@once
    @push('js')
        <script>
            $(document).ready(function() {
                $('#category-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('inventory.category.lists') !!}',
                    columns: [
                        { data: 'name', name: 'name'},
                        { data: 'description', name: 'description'},
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

