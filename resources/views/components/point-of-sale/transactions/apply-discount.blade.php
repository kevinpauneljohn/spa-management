<form id="apply-discount-form">
    @csrf
    <x-adminlte-modal id="apply-discount-modal" title="Apply Discount" theme="olive"
                      icon="fas fa-tags" v-centered static-backdrop scrollable>
        <div>
            <div class="form-group discount_code">
                <label for="discount_code">Discount Code</label>
                <x-adminlte-input name="discount_code" type="text" id="discount_code" onkeypress="return (event.key!=='Enter')"/>
            </div>
            <p id="discount-details"></p>
        </div>
        <input type="hidden" name="discount_id">
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button type="submit" theme="success" label="Apply"/>
        </x-slot>
    </x-adminlte-modal>
</form>

@push('js')
    <script>
        let applyDiscountModal = $('#apply-discount-modal');
        let transactionId;
        let transactionData;
        $(document).on('click','.apply-discount',function(){
            $tr = $(this).closest('tr');
            id = this.id;
            transactionData = $tr.children('td').map(function () {
                return $(this).text();
            }).get();

            applyDiscountModal.find('input[name=discount_code]').val('').change();
            applyDiscountModal.find('input[name=discount_id]').val('').change();
            applyDiscountModal.find('#discount-details').html('');

            transactionId = this.id;
            applyDiscountModal.modal('toggle')
        });

        $(document).on('input','input[name=discount_code]',function(){
            let code = $(this).val();
            if(code.length === 8)
            {
                $.ajax({
                    url: '/get-coupon/'+code,
                    type: 'get',
                    beforeSend: function(){
                        applyDiscountModal.find('.modal-content').append(overlay);
                    }
                }).done(function(response){
                    console.log(response.amount)
                    if(response.amount !== undefined)
                    {
                        let discountAmount = response.amount;
                        discountAmount = parseFloat(discountAmount.replace(',',''));
                        let serviceAmount = parseFloat(transactionData[2].replace(',',''));
                        let discountedServiceAmount = serviceAmount - discountAmount;
                        applyDiscountModal.find('input[name=discount_id]').val(response.id).change();
                        applyDiscountModal.find('#discount-details')
                            .html('Discount Amount: <span class="text-primary">'+response.amount+'</span><br/>' +
                                'Service Amount: <span class="text-primary">'+transactionData[2]+'</span><br/>' +
                                'Discounted Amount: <span class="text-success text-bold">'+discountedServiceAmount.toFixed(2).toLocaleString()+'</span>');
                    }else{
                        applyDiscountModal.find('input[name=discount_code]').val('').change();
                        Swal.fire('Invalid Code', '', 'error')
                    }
                }).fail(function(xhr, status, error){
                    console.log(xhr)
                }).always(function(){
                    applyDiscountModal.find('.overlay').remove();
                });
            }
        })

        $(document).on('submit','#apply-discount-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            Swal.fire({
                title: 'Apply Discount?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.value === true) {

                    $.ajax({
                        url: '/claim-coupon/'+transactionId,
                        type: 'patch',
                        data: data,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){
                            applyDiscountModal.find('.modal-content').append(overlay);
                        }
                    }).done(function(response){
                        console.log(response);
                        if(response.success === true)
                        {
                            $('.display-sales-client').DataTable().ajax.reload(null, false);
                            applyDiscountModal.find('input[name=discount_code]').val('').change();
                            applyDiscountModal.find('input[name=discount_id]').val('').change();
                            applyDiscountModal.find('#discount-details').html('');
                            applyDiscountModal.modal('toggle');

                            Swal.fire(response.message, '', 'success')
                        }else{
                            Swal.fire(response.message, '', 'warning')
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                        $.each(xhr.responseJSON.errors, function(key, value){
                            applyDiscountModal.find('.'+key).append('<p class="text-danger">'+value+'</p>')
                        })

                        if(xhr.responseJSON.message === 'Attempt to read property "amount" on null')
                        {
                            applyDiscountModal.find('input[name=discount_code]').val('').change();
                            Swal.fire('Invalid Code', '', 'error')
                        }

                    }).always(function(){
                        applyDiscountModal.find('.overlay').remove();
                    });

                }
            })
        });


        $(document).on('click','.remove-discount',function(){
            let id = this.id;
            swal.fire({
                title: "Remove Discount?",
                html:
                    'Click <b class="text-info">YES</b>, to confirm',
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajax({
                        url: '/void-transaction-coupon/'+id,
                        type: 'patch',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        }
                    }).done(function(response){
                        console.log(response);
                        if(response.success === true)
                        {
                            $('.display-sales-client').DataTable().ajax.reload(null, false);
                            Swal.fire(response.message, '', 'success')
                        }else{
                            Swal.fire(response.message, '', 'warning')
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    }).always(function(){

                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        });
    </script>
@endpush
