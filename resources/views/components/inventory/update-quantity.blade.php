
<form id="update-inventory-quantity-form">
@csrf
    <input type="hidden" name="id" value="{{$inventory->id}}">
    <div class="form-group quantity">
        <label>Current Quantity</label>
        <input type="number" min="0" id="current-inventory" class="form-control" disabled value="{{$inventory->quantity}}">
    </div>
    <div class="form-group quantity">
        <label>Additional Quantity</label> <span class="required">*</span>
        <input type="number" name="quantity" id="quantity" min="0" class="form-control" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

@section('plugins.ClearErrors',true)
@section('plugins.Toastr',true)
@section('plugins.CustomAlert',true)
@once
    @push('js')
        <script>
            $(document).on('submit','#update-inventory-quantity-form',function(form){
                form.preventDefault();

                let data = $(this).serializeArray()
                swal.fire({
                    title: "You are about to add "+data[2].value+" {{$inventory->unit}} of {{$inventory->name}}?",
                    text: "Please ensure and then confirm!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Yes!",
                    cancelButtonText: "No!",
                    reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {

                        $.ajax({
                            url: '{{route('update.inventory.quantity',['inventory' => $inventory->id])}}',
                            type: 'PUT',
                            data: data,
                            dataType: 'json',
                            beforeSend: function(){
                                $('#update-inventory-quantity-form').find('button[type=submit]').attr('disabled',true).text('Updating...');
                            }
                        }).done( (result) => {
                            if(result.success === true)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: result.message
                                });
                                $('#current-inventory').attr('value',result.inventory);
                                $('#update-inventory-quantity-form').trigger('reset');
                            }else if(result.success === false)
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: result.message
                                });
                            }
                        }).fail( (xhr, status, error) => {
                            $.each(xhr.responseJSON, function (key, value) {
                                let element = $('.' + key);

                                element.find('.error-' + key).remove();
                                element.append('<p class="text-danger error-' + key + '">' + value + '</p>');
                                toastr.error(value)
                            });
                        }).always( () => {
                            $('#update-inventory-quantity-form').find('button[type=submit]').attr('disabled',false).text('Update');
                        });

                    }
                });
                clear_errors('quantity');
            })
        </script>
    @endpush
@endonce
