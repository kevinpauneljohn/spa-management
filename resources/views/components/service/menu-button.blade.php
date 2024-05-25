<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#service-menu">{{$title}}</button>

<x-adminlte-modal id="service-menu" title="Service Menu" size="xl" theme="olive"
                  icon="fas fa-bell" v-centered static-backdrop scrollable>
    <div class="table-responsive">
        <table id="service-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                <th>Date Added</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Category</th>
                <th>Multiple Masseur</th>
            </tr>
            </thead>
        </table>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

@push('js')
    <script>
        $(document).ready(function(){
            $('#service-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('service.lists', ['id' => $spa->id]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'price', name: 'price'},
                    { data: 'duration', name: 'duration'},
                    { data: 'category', name: 'category'},
                    { data: 'multiple_masseur', name: 'multiple_masseur'},
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });
        });
    </script>
@endpush


