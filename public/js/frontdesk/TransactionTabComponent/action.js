function viewInvoice(id)
{
    $.ajax({
        'url' : '/transaction-invoice/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#invoiceTable tbody').html('');
            $('#summaryTotal').html('');
        },
        success: function(result){
            // var date = moment(result.info.end_time).format('DD-MMMM-YYYY');
            // var start_time = moment(result.info.start_time).format('HH:mm:ss');
            // var end_time = moment(result.info.end_time).format('HH:mm:ss');

            $('.viewNameInvoice').text('[ Invoice # '+result.invoice +' ]');
            $('.spaName').text(result.sales.spa.name);
            // $('.transactionEndDate').text(date);

            $('.spaAddress').text(result.sales.spa.address);
            $('.spaMobile').text(result.owner.mobile_number);
            $('.spaEmail').text(result.owner.email);
            $('.salesInvoiceNumber').html('<b>Invoice #</b> '+result.invoice);

            var paymenthMethod = 'None';
            if (result.sales.payment_method != null) {
                paymenthMethod = result.sales.payment_method
            }
            $('.paymentMethod').html('<b>'+paymenthMethod+'</b>');
            // $('.clientName').text(result.info.client.firstname+' '+result.info.client.lastname);
            // $('.clientAddress').text(result.info.client.address);
            // $('.clientMobile').text(result.info.client.mobile_number);
            // $('.clientEmail').text(result.info.client.email);

            // // $('.result.info.client.').text();
            // $('.salesId').text(result.sales);

            // $('#invoiceTable tbody tr').append("<td>"+result.service.name+"</td>");
            // var totalAmount = result.info.amount + result.info.tip;

            $.each(result.transactions , function(index, val) {
                var start_time = moment(val.start_time).format('HH:mm:ss');
                var end_time = moment(val.end_time).format('HH:mm:ss');

                var displayInvoiceTable = '<tr>';
                displayInvoiceTable += '<td>'+val.client.firstname+' '+val.client.lastname+'</td>';
                displayInvoiceTable += '<td>'+val.service.name+'</td>';
                displayInvoiceTable += '<td>'+val.room_id+'</td>';
                displayInvoiceTable += '<td>'+start_time+'</td>';
                displayInvoiceTable += '<td>'+end_time+'</td>';
                displayInvoiceTable += '<td>&#8369; '+val.amount+'</td>';
                displayInvoiceTable += '</tr>';

                $( displayInvoiceTable ).appendTo("#invoiceTable");
            });


            var summaryInvoiceTable = '<tr>';
                summaryInvoiceTable += '<th style="width:50%">Subtotal:</th>';
                summaryInvoiceTable += '<td>&#8369; '+result.sales.amount_paid+'</td>';
            summaryInvoiceTable += '</tr>';
            summaryInvoiceTable += '<tr>';
                summaryInvoiceTable += '<th style="width:50%">Tip:</th>';
                summaryInvoiceTable += '<td>&#8369; 0</td>';
            summaryInvoiceTable += '</tr>';
            summaryInvoiceTable += '<tr>';
                summaryInvoiceTable += '<th style="width:50%">Total:</th>';
                summaryInvoiceTable += '<td>&#8369;  '+result.sales.amount_paid+'</td>';
            summaryInvoiceTable += '</tr>';

            $( summaryInvoiceTable ).appendTo("#summaryTotal");
        }
    });

    $('#view-invoice-modal').modal('show');
}