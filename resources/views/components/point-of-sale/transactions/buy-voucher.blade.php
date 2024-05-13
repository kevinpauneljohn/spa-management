<button type="button" class="btn bg-purple" id="buy-voucher-btn">Buy Voucher</button>
<form id="buy-voucher-form">
    @csrf
    <x-adminlte-modal id="buy-voucher-modal" title="Account Policy" theme="olive"
                      icon="fas fa-bell" size="lg" v-centered static-backdrop scrollable>
        <div>
            <div class="form-group voucher">
                <x-adminlte-select2 name="voucher" id="voucher">
                    <option value="">-- Select Voucher</option>
                    @foreach($vouchers as $voucher)
                        <option value="{{$voucher->id}}">{{$voucher->is_amount ? $voucher->amount  :$voucher->percent.'%'}} - {{$voucher->title}}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <input type="hidden" name="sales_id" value="{{$salesId}}" />
        <x-slot name="footerSlot">
            <x-adminlte-button type="submit" theme="success" label="Add voucher"/>
        </x-slot>
    </x-adminlte-modal>
</form>
@once
    @push('js')
        <script>
            let buyVoucherModal = $('#buy-voucher-modal');
            $(document).ready(function (){

            });

            $(document).on('click','#buy-voucher-btn', function(){
                buyVoucherModal.modal('toggle');
                buyVoucherModal.find('.modal-title').text('Buy Voucher');
            });

            $(document).on('submit','#buy-voucher-form',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();

                $.ajax({
                    url: '/buy-voucher',
                    type: 'patch',
                    data: data,
                    beforeSend: function(){
                        buyVoucherModal.find('.text-danger').remove()
                        buyVoucherModal.find('.is-invalid').removeClass('is-invalid')
                    }
                }).done(function(response){
                    console.log(response)

                    if(response.success === true)
                    {
                        let url = window.location.href;
                        $('#voucher').load(url+' #voucher option');
                        swal.fire(response.message, '', "success");
                        $('#{{$tableId}}').DataTable().ajax.reload(null, false);
                        // buyVoucherModal.find('select[name=voucher]').val('').change();
                        $('#button-container').load('{{url()->current()}} #button-container');

                    }else{
                        swal.fire(response.message, '', "warning");
                    }

                }).fail(function(xhr, status, error){
                    console.log(xhr)
                    $.each(xhr.responseJSON.errors,function(key, value){
                        buyVoucherModal.find('#'+key).addClass('is-invalid').closest('.'+key).append('<p class="text-danger">'+value+'</p>');
                    });
                }).always(function(){
                    $('#buy-voucher-form').trigger('reset');
                });
            });
        </script>
    @endpush
@endonce
