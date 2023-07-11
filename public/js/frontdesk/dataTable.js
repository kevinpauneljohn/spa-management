// function loadSales(spa_id)
// {
//     $('#sales-data-lists').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '/transaction-list/'+spa_id
//         },
//         columns: [
//             { data: 'client', name: 'client', className: 'text-center'},
//             { data: 'service', name: 'service'},
//             { data: 'masseur', name: 'masseur'},
//             { data: 'start_time', name: 'start_time'},
//             { data: 'plus_time', name: 'plus_time', className: 'text-center'},
//             { data: 'end_time', name: 'end_time', className: 'text-center'},
//             { data: 'room', name: 'room', className: 'text-center'},
//             { data: 'amount', name: 'amount', className: 'text-center'},
//             { data: 'status', name: 'status', className: 'text-center'},
//             { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
//         ],
//         "bDestroy": true,
//         scrollX: true,
//         scrollY: true,
//         responsive:true,
//         order:[8,'asc'],
//         pageLength: 10
//     });
// }

// function loadTransactions(spa_id)
// {
//     $('#transaction-data-lists').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '/sales-list/'+spa_id
//         },
//         columns: [
//             { data: 'spa', name: 'spa', className: 'text-center'},
//             { data: 'client', name: 'client', className: 'text-center'},
//             { data: 'payment_status', name: 'payment_status'},
//             { data: 'amount', name: 'amount', className: 'text-center'},
//             { data: 'date', name: 'date', className: 'text-center'},
//             { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
//         ],
//         "bDestroy": true,
//         scrollX: true,
//         scrollY: true,
//         responsive:true,
//         order:[2,'asc'],
//         pageLength: 10
//     });
// }

// function loadAppointments(spa_id)
// {
//     $('#appointment-data-lists').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '/appointment-lists/'+spa_id
//         },
//         columns: [
//             { data: 'client', name: 'client'},
//             { data: 'service', name: 'service'},
//             { data: 'batch', name: 'batch'},
//             { data: 'amount', name: 'amount', className: 'text-center'},
//             { data: 'type', name: 'type', className: 'text-center'},
//             { data: 'status', name: 'status', className: 'text-center'},
//             { data: 'date', name: 'date', className: 'text-center'},
//             { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
//         ],
//         "bDestroy": true,
//         scrollX: true,
//         scrollY: true,
//         responsive:true,
//         order:[3,'desc'],
//         pageLength: 10
//     });
// }

function appointmentSummary(id)
{
    if (id == 'summary') {
        $('.divCloseTab').addClass('hidden');
        $('.add-appointment-btn').removeClass('hidden');
        $('.process-appointment-btn').addClass('hidden');

        $('.tableSummaryAppointment').html('');
        var summaryContent = '<div class="table-responsive p-0">';
            summaryContent +='<table class="table table-hover datatable" id="summary-list">';
                summaryContent += '<thead>';
                    summaryContent += '<tr>';
                        summaryContent += '<th>Client</th>';
                        summaryContent += '<th>Service</th>';
                        summaryContent += '<th>Date</th>';
                        summaryContent += '<th>Start Time</th>';
                        summaryContent += '<th>Type</th>';
                        summaryContent += '<th>Amount</th>';
                    summaryContent += '</tr>';
                summaryContent += '</thead>';
                summaryContent += '<tbody class="summaryBody">';
                summaryContent += '</tbody>';
            summaryContent += '</table>';
        summaryContent += '</div>';

        $( summaryContent ).appendTo(".tableSummaryAppointment");
        const dataSet = appointment.map(({
            firstname, 
            lastname,
            service_name,
            start_time_date_format,
            start_time_format,
            appointment_socials,
            price
        }) => [
            firstname+' '+lastname,
            service_name,
            start_time_date_format,
            start_time_format,
            appointment_socials,
            '&#8369;'+ ReplaceNumberWithCommas(price)
        ]);

        $('#summary-list').DataTable({
            data: dataSet,
            columns: [
              { title: 'Client' },
              { title: 'Service' },
              { title: 'Date' },
              { title: 'Start Time' },
              { title: 'Type' },
              { title: 'Amount' },
            ],
            paging: false,
            searching: false,
            info: false
        });
    } else {
        $('.divCloseTab').removeClass('hidden');
        $('.add-appointment-btn').addClass('hidden');
        $('.process-appointment-btn').removeClass('hidden');
        $('.summaryTabAppointmentLink').addClass('hidden');
        $('#summaryTab').removeClass('active');
        $('#summaryTab').addClass('hidden');
    }
}

function loadEndOfShiftReport()
{
    var spa_id = $('#spa_id_val').val();
    var shift_id = $('#start_shit_id').val();
    // $('#endShiftReport').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //         url: '/sales-end-of-shift-report/'+spa_id+'/'+shift_id
    //     },
    //     columns: [
    //         { data: 'invoice', name: 'invoice', className: 'text-center'},
    //         { data: 'payment_method', name: 'payment_method', className: 'text-center'},
    //         { data: 'reference_number', name: 'reference_number', className: 'text-center'},
    //         { data: 'payment_date', name: 'payment_date', className: 'text-center'},
    //         { data: 'subtotal', name: 'subtotal', orderable: false, searchable: false, className: 'text-center' }
    //     ],
    //     "bDestroy": true,
    //     scrollX: true,
    //     scrollY: true,
    //     responsive:true,
    //     order:[1,'desc'],
    //     pageLength: 10,
    // }).columns.adjust();

    $.ajax({
        'url' : '/sales-end-of-shift-report/'+spa_id+'/'+shift_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#endShiftReport tbody').html('');
            $('#summaryTotalEndReport').html('');
        },
        success: function(result){
            $('.shift_date_start').text(result.shift_data.date_start);
            $('.shift_date_end').text(result.shift_data.date_end);
            $('.shift_start').text(result.shift_data.start_shift);
            $('.shift_end').text(result.shift_data.end_shift);
            $.each(result.data , function(index, val) { 
                var displayInvoiceTable = '<tr>';
                displayInvoiceTable += '<td>'+val.invoice+'</td>';
                    displayInvoiceTable += '<td>'+val.payment_method+'</td>';
                    displayInvoiceTable += '<td>'+val.reference_number+'</td>';
                    displayInvoiceTable += '<td>'+val.payment_date+'</td>';
                    displayInvoiceTable += '<td>'+val.subtotal+'</td>';
                displayInvoiceTable += '</tr>';

                $( displayInvoiceTable ).appendTo("#endShiftReport tbody");
            });

            var summaryInvoiceTable = '<tr>';
                summaryInvoiceTable += '<th style="width:50%">Subtotal:</th>';
                summaryInvoiceTable += '<td>&#8369; '+result.total_sales+'</td>';
            summaryInvoiceTable += '</tr>';
            summaryInvoiceTable += '<tr>';
                summaryInvoiceTable += '<th style="width:50%">On hand Money:</th>';
                summaryInvoiceTable += '<td>&#8369; '+result.shift_data.start_money+'</td>';
            summaryInvoiceTable += '</tr>';
            summaryInvoiceTable += '<tr>';
                summaryInvoiceTable += '<th style="width:50%">Total:</th>';
                summaryInvoiceTable += '<td>&#8369;  '+result.total_sales_plus_start_money+'</td>';
            summaryInvoiceTable += '</tr>';
    
            $( summaryInvoiceTable ).appendTo("#summaryTotalEndReport");
        }
    });
}