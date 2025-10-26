@can('add service')
    <button type="button" class="btn bg-gradient-info btn-sm float-right" id="addNewService">Add Service</button>
@endcan

@once
    @push('js')
        <script>
            $(document).on('click','#addNewService', function(){
                $('#service-form').trigger('reset');
                $('#duration').val('').trigger('change');
                $('#add-new-service-modal').modal('toggle');
                $('#add-new-service-modal').find('.modal-title').text('New Service Form')
                $('#service-form').removeClass('edit-service').addClass('add-service')
            });
        </script>
    @endpush
@endonce
