<form id="update-inventory-form">
    @csrf
    <x-adminlte-modal class="update-inventory-modal" id="update-inventory-modal" title="Update Inventory" size="md" theme="olive"
                      v-centered static-backdrop scrollable>
        <div class="form-group">
            <label for="current-qty">Current Quantity</label>
            <input type="text" class="form-control" id="current-qty" disabled>
        </div>
        <div class="form-group action">
            <label for="action">Action</label>
            <select id="action" name="action" class="form-control">
                <option value="">-- Select Action -- </option>
                <option value="increase">Increase</option>
                <option value="decrease">Decrease</option>
            </select>
        </div>
        <div class="form-group update_quantity">
            <label for="update_quantity">Update Quantity</label>
            <input type="number" class="form-control" id="update_quantity" name="update_quantity" min="0" value="0" disabled>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button type="submit" class="bg-olive" id="update-inventory-modal-btn" label="Save"/>
        </x-slot>
    </x-adminlte-modal>
</form>

@once
    @push('js')
        <script>
            let updateInventoryModal = $('#update-inventory-modal');
            let updateInventoryForm = $('#update-inventory-form');
            let inventoryId;
            let tableData;
            $(document).on('click','.update-inventory',function(){
                inventoryId = this.id;

                $tr = $(this).closest('tr');

                tableData = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                updateInventoryModal.find('.modal-title').text(tableData[1]);
                updateInventoryModal.find('#current-qty').val(tableData[3]).change();

                updateInventoryModal.modal('toggle')
            })

            $(document).on('submit','#update-inventory-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                Swal.fire({
                    title: 'Update '+tableData[1]+'?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '/inventory-quantity-update/'+inventoryId,
                            type: 'patch',
                            data: data,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){
                                updateInventoryForm.find('button[type=submit]').attr('disabled',true).text('Saving...');
                                updateInventoryForm.find('.is-invalid').removeClass('is-invalid');
                                updateInventoryForm.find('.text-danger').remove();
                            }
                        }).done(function(response){
                            console.log(response)
                            if(response.success === true){
                                Swal.fire(
                                    response.message,
                                    '',
                                    'success'
                                );

                                let table = $('#inventory-list').DataTable();
                                table.ajax.reload();
                                updateInventoryForm.trigger('reset');
                                updateInventoryForm.find('#update_quantity').attr('disabled',true);
                                updateInventoryModal.modal('toggle');
                            }else{
                                toastr.error(response.message);
                            }
                        }).fail(function(xhr, status, error){
                            console.log(xhr)
                            $.each(xhr.responseJSON.errors, function(key, value){
                                updateInventoryForm.find('#'+key).addClass('is-invalid')
                                updateInventoryForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
                            });
                        }).always(function(){
                            updateInventoryForm.find('button[type=submit]').attr('disabled',false).text('Save');
                        })
                    }
                });
            })

            $(document).on('change','#action',function(){
                let action = $(this).val();

                if(action === '')
                {
                    $('#update_quantity').attr('disabled',true);
                }else{
                    $('#update_quantity').attr('disabled',false);
                }
            })
        </script>
    @endpush
@endonce
