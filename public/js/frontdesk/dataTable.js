function loadSales(spa_id)
{
    $('#sales-data-lists').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/transaction-list/'+spa_id
        },
        columns: [
            { data: 'client', name: 'client', className: 'text-center'},
            { data: 'service', name: 'service'},
            { data: 'masseur', name: 'masseur'},
            { data: 'start_time', name: 'start_time'},
            { data: 'plus_time', name: 'plus_time', className: 'text-center'},
            { data: 'end_time', name: 'end_time', className: 'text-center'},
            { data: 'room', name: 'room', className: 'text-center'},
            { data: 'amount', name: 'amount', className: 'text-center'},
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        "bDestroy": true,
        scrollX: true,
        scrollY: true,
        responsive:true,
        order:[3,'desc'],
        pageLength: 10
    });
}

function loadTransactions(spa_id)
{
    $('#transaction-data-lists').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/sales-list/'+spa_id
        },
        columns: [
            { data: 'spa', name: 'spa', className: 'text-center'},
            { data: 'payment_status', name: 'payment_status'},
            { data: 'amount', name: 'amount', className: 'text-center'},
            { data: 'date', name: 'date', className: 'text-center'},
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        "bDestroy": true,
        scrollX: true,
        scrollY: true,
        responsive:true,
        order:[3,'desc'],
        pageLength: 10
    });
}

function loadAppointments(spa_id)
{
    $('#appointment-data-lists').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/appointment-lists/'+spa_id
        },
        columns: [
            { data: 'client', name: 'client'},
            { data: 'service', name: 'service'},
            { data: 'batch', name: 'batch'},
            { data: 'amount', name: 'amount', className: 'text-center'},
            { data: 'type', name: 'type', className: 'text-center'},
            { data: 'status', name: 'status', className: 'text-center'},
            { data: 'date', name: 'date', className: 'text-center'},
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        "bDestroy": true,
        scrollX: true,
        scrollY: true,
        responsive:true,
        order:[3,'desc'],
        pageLength: 10
    });
}

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
            value_first_name, 
            value_last_name,
            value_services_name,
            value_start_time_date_format,
            value_start_time_format,
            value_appointment_socials,
            price
        }) => [
            value_first_name+' '+value_last_name,
            value_services_name,
            value_start_time_date_format,
            value_start_time_format,
            value_appointment_socials,
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