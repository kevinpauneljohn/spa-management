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
                                    <option value="Voucher">Voucher</option>
                                </select>
                            </div>
                        </div>
                        <section class="voucher-section mb-2">
                            <div class="icheck-primary d-inline mr-5">
                                <input type="checkbox" id="add-cash">
                                <label for="add-cash">
                                    Add Cash
                                </label>
                            </div>
                            <button type="button" class="btn btn-default btn-xs" id="reset-voucher">Reset Voucher</button>
                            <p class="mt-2">Total Voucher Amount = <span class="text-primary text-bold" id="total-voucher-amount">0</span></p>
                        </section>
                        <div class="row voucher-row mb-1">
                            <div class="col-6">
                                <input type="text" name="voucher[]" id="1" class="form-control voucher-code" onkeypress="return (event.key!=='Enter')" placeholder="Input Voucher Code here..." disabled>
                            </div>
                            <div class="col-3">
                                <input type="text" name="amount[]" id="1" class="form-control voucher-amount" readonly>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-success btn-xs plus-voucher"><i class="fa fa-plus"></i></button>
{{--                                <button type="button" class="btn btn-danger btn-xs minus-voucher"><i class="fa fa-minus"></i></button>--}}
                            </div>
                        </div>
                        <div class="form-group row nonCashField mt-2">
                            <label for="non_cash_amount" class="col-lg-4">Non Cash Amount: </label>
                            <div class="col-lg-8 non_cash_amount">
                                <input type="text" name="non_cash_amount" class="form-control" id="non_cash_amount" min="0">
                            </div>
                        </div>
                        <div class="form-group row nonCashField">
                            <label for="reference_no" class="col-lg-4">Reference No.: </label>
                            <div class="col-lg-8 reference_no">
                                <input type="text" name="reference_no" class="form-control" id="reference_no" required>
                            </div>
                        </div>
                        <div class="form-group row cash-section mt-2">
                            <label for="cash" class="col-lg-4">Cash: </label>
                            <div class="col-lg-8 cash">
                                <input type="text" name="cash" class="form-control" id="cash" min="0" required>
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
                    <input type="hidden" name="total_service_amount">
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
@section('plugins.IcheckBootstrap', true)
    @once
        @push('js')
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                let payForm = $('.pay-form');
                let payFormModal = $('#pay-form-modal');
                let totalAmount;
                let salesId;
                let voucherRow = $('.voucher-row');
                // let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';

                let voucherScannedCode = [];


                $(document).ready(function(){
                    cashField({show:false, disabled: true})
                    nonCashField({show:false, disabled: true})
                    changeField({show:false, disabled: true})
                    voucherField({show: false, disabled: true})
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

                const cashPayment = (cash, totalAmount, change) => {
                    if(cash >= totalAmount)
                    {
                        payForm.find('#cash').removeClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(change).toFixed(2)));
                    }else{

                        payForm.find('#cash').addClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                    }
                }

                const nonCashPayment = (cash, required_cash, change) => {
                    if(cash >= required_cash)
                    {
                        payForm.find('#cash').removeClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(change).toFixed(2)));
                    }
                    else{

                        payForm.find('#cash').addClass('is-invalid')
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                    }
                }

                $(document).on('input','input[name=cash]',function(key){
                    let cash = $(this).val();

                    if(paymentType === "Cash")
                    {
                        let change = cash - totalAmount;
                        cashPayment(cash, totalAmount, change)
                    }
                    else{
                        let total_payment = parseFloat($('#non_cash_amount').val()) + parseFloat(cash);
                        let change = total_payment - totalAmount
                        nonCashPayment(cash, required_amount, change)
                    }
                });



                const voucherField = ({show = true, disabled = true}) => {
                    if(show === true)
                    {
                        $('.voucher-section').show();
                        voucherRow.show();
                        voucherRow.find('.voucher-amount').attr('disabled',false).attr('readonly',true)
                    }else{
                        $('.voucher-section').hide();
                        voucherRow.hide();
                        voucherRow.find('.voucher-amount').attr('disabled',true).attr('readonly',false)
                        $('.appended-voucher').remove();
                    }
                    voucherRow.find('.voucher-code').attr('disabled',disabled)

                }

                const cashField = ({show = true, disabled = false}) => {
                    if(show === true)
                    {
                        payForm.find('input[name=cash], .cash-section').show().attr('disabled',disabled);
                    }else{
                        payForm.find('input[name=cash], .cash-section').hide().attr('disabled',disabled);
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

                const maxNonCashAmount = (amount) => {
                    $('#non_cash_amount').attr('max',amount)
                }

                $(document).on('click','.plus-voucher',function(){
                    let id = $('.voucher-row').length + 1;
                    let voucherAppendSection = `<div class="row voucher-row mb-1 appended-voucher">

                            <div class="col-6">
                                <input type="text" name="voucher[]" id="${id}" class="form-control voucher-code" onkeypress="return (event.key!=='Enter')" placeholder="Input Voucher Code here...">
                            </div>
                            <div class="col-3">
                                <input type="text" name="amount[]" id="${id}" class="form-control voucher-amount" readonly>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-success btn-xs plus-voucher"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-danger btn-xs minus-voucher"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>`;
                    voucherRow.after(voucherAppendSection)
                })

                function disable_minus_button(disable = true)
                {
                    $('.minus-voucher').attr('disabled',disable)
                }

                $(document).on('click','.minus-voucher',function(){
                    let rows = $('.voucher-row').length;
                    if(rows >= 2)
                    {
                        $(this).closest('.voucher-row').remove();
                        console.log(rows)
                        disable_minus_button(false);
                    }else{
                        disable_minus_button();
                    }
                    let total_voucher = parseFloat($('#total-voucher-amount').text().replace(/,/g, ""))
                    let deducted_value = parseFloat($(this).closest('.voucher-row').find('.voucher-amount').val());
                    let latest_value = total_voucher - deducted_value;
                    // console.log(total_voucher )
                    $('#total-voucher-amount').text(latest_value.toLocaleString())
                    display_cash_field_if_voucher_value_is_lower(latest_value)
                });

                function display_cash_field_if_voucher_value_is_lower(total_voucher_amount)
                {
                    if(total_voucher_amount < totalAmount)
                    {
                        $('#add-cash').prop('checked',true).change()
                    }else{
                        $('#add-cash').prop('checked',false).change()
                    }
                }

                function display_total_voucher_amount(total_voucher_amount = 0)
                {
                    $.each($('.voucher-amount').serializeArray(), function(key, value){
                        let voucher_amount = value.value !== '' ? parseFloat(value.value) : 0;
                        total_voucher_amount = parseFloat(total_voucher_amount) + voucher_amount;
                    });
                    $('#total-voucher-amount').text(total_voucher_amount.toLocaleString())
                    return total_voucher_amount;
                }

                $(document).on('input','.voucher-code',function(){
                    let code = $(this).val();
                    let id = this.id;
                    if(code.length === 8)
                    {
                        if(voucherScannedCode.indexOf(code) == -1)
                        {
                            $.ajax({
                                'url' : '/get-discount/'+code,
                                type: 'get',
                                beforeSend: function(){
                                    payFormModal.find('.modal-content').append(overlay);
                                }
                            }).done(function(response){
                                console.log(response)
                                let value = "";
                                if(response === '')
                                {
                                    Toast.fire({
                                        type: 'warning',
                                        title: 'Voucher already claimed'
                                    });
                                    $('.voucher-row').find('.voucher-code#'+id).val('').change();
                                }
                                if(response.is_amount === 1)
                                {
                                    value = response.amount;
                                    $('.voucher-row').find('.voucher-code#'+id).attr('readonly',true)
                                }else{
                                    value = response.percent;
                                }
                                voucherScannedCode.push(code)
                                // console.log(voucherScannedCode)
                                $('.voucher-row').find('.voucher-amount#'+id).val(value).change();

                                let total_voucher_amount = display_total_voucher_amount();

                                display_cash_field_if_voucher_value_is_lower(total_voucher_amount);

                            }).fail(function(xhr, status, error){
                                console.log(xhr)
                            }).always(function(){
                                payFormModal.find('.overlay').remove();
                            });
                        }else{
                            Toast.fire({
                                type: 'warning',
                                title: 'Voucher already scanned'
                            });
                            $('.voucher-row').find('.voucher-code#'+id).val('').change();
                        }
                    }

                })

                $(document).on('click','#reset-voucher', function(){
                    let total_amount = $('#total_amount').val();
                    payForm.trigger('reset');
                    voucherScannedCode = [];
                    $('#total_amount').val(total_amount);
                    $('#payment_type').val('Voucher').change();
                    $('.voucher-row').find('.voucher-code').attr('readonly',false)
                    cashField({show: false, disabled: true});
                    changeField({show: false, disabled: true});
                    display_total_voucher_amount();
                })


                $(document).on('change','#add-cash',function(){
                    if($('#add-cash').is(':checked'))
                    {
                        cashField({show: true, disabled: false})
                        changeField({show: true, disabled: false})
                    }else{
                        cashField({show: false, disabled: true})
                        changeField({show: false, disabled: true})
                    }
                })

                let paymentType = '';
                $(document).on('change','#payment_type', function(){
                    paymentType = $(this).val();

                    payForm.find('input[name=cash], #change, #non_cash_amount', '#reference_no').val('')
                    if(paymentType === "Cash")
                    {
                        nonCashField({show: false, disabled: true})
                        cashField({show: true, disabled: false})
                        changeField({show: true, disabled: false})
                        voucherField({show: false, disabled: true})
                        payForm.find('input[name=reference_no]').val('')
                    }
                    else if(paymentType === "GCash" || paymentType === "Maya" || paymentType === "Bank Transfer")
                    {
                        payForm.find('#cash').removeClass('is-invalid')
                        cashField({show: false, disabled: true})
                        nonCashField({show: true, disabled: false})
                        changeField({show:false, disabled: true})
                        voucherField({show: false, disabled: true})
                        payForm.find('#change').val(replaceNumberWithCommas(Number.parseFloat(0).toFixed(2)));
                        payForm.find('input[name=cash]').val('')
                    }
                    else if(paymentType === "Voucher")
                    {
                        voucherField({show: true, disabled: false})
                        nonCashField({show: false, disabled: true})
                        cashField({show: false, disabled: true})
                        changeField({show: false, disabled: true})
                        payForm.find('input[name=reference_no]').val('')
                    }
                    else{
                        voucherField({show: false, disabled: true})
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
                        totalAmount = sales.total_amount
                        maxNonCashAmount(totalAmount)

                        $('input[name=total_service_amount]').val(totalAmount)

                        payForm.find('#total_amount').val(replaceNumberWithCommas(Number.parseFloat(totalAmount).toFixed(2)))
                    }).always(function(){
                        payFormModal.find('.overlay').remove();
                    });
                })

                const requiredCashAmount = (serviceAmount, nonCashPaymentAmount) => {
                    return parseFloat(serviceAmount) - parseFloat(nonCashPaymentAmount)
                }

                let nonCashAmountField = $('.non_cash_amount')
                const nonCashExactAmountOnlyWarning = (non_cash_amount) => {
                    nonCashAmountField.find('.text-danger').remove()
                    nonCashAmountField.find('.is-invalid').removeClass('is-invalid')
                    if(non_cash_amount > totalAmount)
                    {
                        $('#non_cash_amount').addClass('is-invalid')
                        nonCashAmountField.append('<p class="text-danger">Amount Exceeding</p>')
                    }
                }

                let required_amount = 0;
                $(document).on('input','#non_cash_amount', function(){
                    let non_cash_amount = $(this).val();
                    required_amount = requiredCashAmount(totalAmount, non_cash_amount)

                    nonCashExactAmountOnlyWarning(non_cash_amount)
                    if(non_cash_amount < totalAmount && !isNaN(required_amount))
                    {
                        cashField({show: true, disabled: false})
                        changeField({show:true, disabled: true})
                        $('#cash').val(Number.parseFloat(required_amount).toFixed(2))
                    }else{
                        cashField({show: false, disabled: true})
                        changeField({show:false, disabled: true})
                    }
                })

                $(document).on('submit','.pay-form',function(form){
                    form.preventDefault();
                    let data = $(this).serializeArray();

                    payFormModal.find('.text-danger').remove()
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
                            }).fail(function(xhr, status, error){
                                console.log(xhr)
                                $.each(xhr.responseJSON.errors, function(key, value){
                                    payFormModal.find('.'+key).append('<p class="text-danger">'+value+'</p>')
                                })
                            }).always(function(){
                                payFormModal.find('.overlay').remove();
                            });

                        }
                    })


                });

                //voucher//

            </script>
        @endpush
    @endonce


