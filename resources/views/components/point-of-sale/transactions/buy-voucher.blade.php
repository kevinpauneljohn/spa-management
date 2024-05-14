<button type="button" class="btn bg-purple" id="buy-voucher-btn">Buy Voucher</button>
<form id="buy-voucher-form">
    @csrf
    <x-adminlte-modal id="buy-voucher-modal" title="Account Policy" theme="olive"
                      icon="fas fa-bell" v-centered static-backdrop scrollable>
        <div>
            <div class="form-group voucher">
                <label for="voucher"></label>
                <input type="text" name="voucher" class="form-control" id="voucher" onkeypress="return (event.key!=='Enter')">
{{--                <x-adminlte-select2 name="voucher" id="voucher">--}}
{{--                    <option value="">-- Select Voucher</option>--}}
{{--                    @foreach($vouchers as $voucher)--}}
{{--                        <option value="{{$voucher->id}}">{{$voucher->is_amount ? $voucher->amount  :$voucher->percent.'%'}} - {{$voucher->title}}</option>--}}
{{--                    @endforeach--}}
{{--                </x-adminlte-select2>--}}
            </div>
            <span class="text-success" id="code-availability"></span>
        </div>
        <input type="hidden" name="sales_id" value="{{$salesId}}" />
        <input type="hidden" name="voucher_id" value=""/>
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

            $(document).on('input','#voucher',function(){
                let code = $(this).val();
                if(code.length === 8)
                {
                    $.ajax({
                        'url' : '/check-voucher-availability/'+code,
                        type: 'get',
                        beforeSend: function(){
                            $('input[name=voucher_id]').val('').change();
                            buyVoucherModal.find('.modal-content').append(overlay);
                        }
                    }).done(function(response){
                        console.log(response)
                        if(response === '')
                        {
                            Toast.fire({
                                type: 'warning',
                                title: 'Voucher already bought/claimed'
                            });
                            buyVoucherModal.find('#voucher').val('').change();
                            $('#code-availability').html('')
                        }else{
                            $('#code-availability').html('<i class="fa fa-check"></i> Available')
                            $('input[name=voucher_id]').val(response.id).change();
                        }
                        // if(response.is_amount === 1)
                        // {
                        //     $('.voucher-row').find('.voucher-code#'+id).attr('readonly',true)
                        // }


                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    }).always(function(){
                        buyVoucherModal.find('.overlay').remove();
                    });
                    // if(voucherScannedCode.indexOf(code) === -1)
                    // {
                    //
                    // }else{
                    //     Toast.fire({
                    //         type: 'warning',
                    //         title: 'Voucher already scanned'
                    //     });
                    //     $('.voucher-row').find('.voucher-code#'+id).val('').change();
                    // }
                }

            })

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
                        $('input[name=voucher_id]').val('').change();
                        $('#code-availability').html('')
                        let url = window.location.href;
                        swal.fire(response.message, '', "success");
                        $('#{{$tableId}}').DataTable().ajax.reload(null, false);
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
