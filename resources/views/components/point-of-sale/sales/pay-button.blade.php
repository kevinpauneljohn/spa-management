<span id="button-container">
    @if($display)
        <button class="btn btn-success mr-2 pay-button" id="{{$salesId}}">Pay</button>
    @endif
</span>


    <div class="modal fade" id="pay-form-modal">
        <div class="modal-dialog">
            <form class="pay-form">
                @csrf
                <input type="hidden" name="sales_id">
                <div class="modal-content">
                    <div class="modal-header bg-olive">
                        <h4 class="modal-title">Payment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="total_amount" class="col-lg-4">Total Amount: </label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="total_amount" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_type" class="col-lg-4">Payment Type: </label>
                            <div class="col-lg-8">
                                <select name="payment_type" class="form-control" id="payment_type">
                                    <option value="">--Select--</option>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Maya">Maya</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row cash-section">
                            <label for="cash" class="col-lg-4">Cash: </label>
                            <div class="col-lg-8 cash">
                                <input type="text" name="cash" class="form-control" id="cash" >
                            </div>
                        </div>
                        <div class="form-group row nonCashField">
                            <label for="reference_no" class="col-lg-4">Reference No.: </label>
                            <div class="col-lg-8 reference_no">
                                <input type="text" name="reference_no" class="form-control" id="reference_no" >
                            </div>
                        </div>
                        <div class="form-group row change-section">
                            <label for="change" class="col-lg-4">Change: </label>
                            <div class="col-lg-8">
                                <input type="text" name="change" class="form-control" id="change" disabled>
                            </div>
                        </div>
                        <div class="result">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="float-left">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @once
        @push('js')
            <script>
                let payForm = $('.pay-form');
                let payFormModal = $('#pay-form-modal');
                let totalAmount;
                let salesId;
                // let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

                $(document).ready(function(){
                    cashField({show:false, disabled: true})
                    nonCashField({show:false, disabled: true})
                    changeField({show:false, disabled: true})
                });
                function replaceNumberWithCommas(yourNumber) {
                    //Seperates the components of the number
                    let n= yourNumber.toString().split(".");
                    //Comma-fies the first part
                    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    //Combines the two sections
                    return n.join(".");
                }

                $('input[name=cash]').on('keypress',function(key){
                    let cash = $(this).val();
                    if(key.charCode < 48 || key.charCode > 57) {
                        return false;
                    }

                });

                $(document).on('input','input[name=cash]',function(key){
                    let cash = $(this).val();
                    let change = cash - totalAmount;

                    if(cash >= totalAmount)
                    {
                        payForm.find('#cash').removeClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(change).toFixed(2)));
                    }else{

                        payForm.find('#cash').addClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                    }
                });



                const cashField = ({show = true, disabled = false}) => {
                    if(show === true)
                    {
                        payForm.find('input[name=cash], .cash-section').show().attr('disabled',false);
                    }else{
                        payForm.find('input[name=cash], .cash-section').hide().attr('disabled',true);
                    }
                }

                const nonCashField = ({show = true, disabled = false}) => {
                    if(show === true)
                    {
                        payForm.find('input[name=reference_no], .nonCashField').show().attr('disabled',false);
                    }else{
                        payForm.find('input[name=reference_no], .nonCashField').hide().attr('disabled',true);
                    }
                }

                const changeField = ({show = true, disabled = false}) => {
                    if(show === true)
                    {
                        payForm.find('input[name=change], .change-section').show();
                    }else{
                        payForm.find('input[name=change], .change-section').hide();
                    }
                }

                $(document).on('change','#payment_type', function(){
                    let paymentType = $(this).val();

                    if(paymentType === "Cash")
                    {
                        nonCashField({show: false, disabled: true})
                        cashField({show: true, disabled: false})
                        changeField({show: true, disabled: false})
                        payForm.find('input[name=reference_no]').val('')
                    }
                    else if(paymentType === "GCash" || paymentType === "Maya" || paymentType === "Bank Transfer")
                    {
                        payForm.find('#cash').removeClass('is-invalid')
                        cashField({show: false, disabled: true})
                        nonCashField({show: true, disabled: false})
                        changeField({show:false, disabled: true})
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                        payForm.find('input[name=cash]').val('')
                    }else{
                        payForm.find('#cash').removeClass('is-invalid')
                        cashField({show: false, disabled: true})
                        nonCashField({show: false, disabled: true})
                        payForm.find('input[name=cash], input[name=reference_no]').val('')
                        changeField({show:false, disabled: true})
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                    }
                })

                $(document).on('click','.pay-button',function(){
                    salesId = this.id;
                    payForm.find('#cash').removeClass('is-invalid')

                    payForm.find('input[name=sales_id]').val(salesId);
                    payFormModal.modal('toggle')
                    $.ajax({
                        url: '/total-amount-to-be-paid-in-sales/'+salesId,
                        beforeSend: function(){
                            payFormModal.find('.modal-content').append(overlay);
                        }
                    }).done(function(sales){
                        // console.log(sales)
                        totalAmount = sales.total_amount
                        payForm.find('#total_amount').val(replaceNumberWithCommas(Number.parseFloat(totalAmount).toFixed(2)))
                    }).always(function(){
                        payFormModal.find('.overlay').remove();
                    });
                })

                $(document).on('submit','.pay-form',function(form){
                    form.preventDefault();
                    let data = $(this).serializeArray();

                    Swal.fire({
                        title: 'Complete Payment?',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.value === true) {

                            $.ajax({
                                url: '/pay/'+salesId,
                                type: 'patch',
                                data: data,
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                beforeSend: function(){
                                    payFormModal.find('.modal-content').append(overlay);
                                }
                            }).done(function(payment){
                                console.log(payment);
                                if(payment.success === true)
                                {
                                    $('#print-invoice-section').load('{{url()->current()}} #print-invoice-section');
                                    $('.pay-button, #add-client-btn').remove();
                                    $('.display-sales-client').DataTable().ajax.reload(null, false);
                                    payFormModal.modal('toggle');
                                    setTimeout(function(){
                                        payFormModal.remove();
                                    },600)
                                    Swal.fire(payment.message, '', 'success')
                                }else{
                                    Swal.fire(payment.message, '', 'warning')
                                }
                            }).always(function(){
                                payFormModal.find('.overlay').remove();
                            });

                        }
                    })


                });
            </script>
        @endpush
    @endonce


