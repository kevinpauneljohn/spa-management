var myVals = [];
// $('.divAvailable').on('click', function() {
//     console.log('test')
//     var id = this.id;
//     var is_available = $('#isAvailable'+id).val();
//     var cur_val = $('#room_ids_val').val();
//     if (is_available == 'yes') {
//         $('#isAvailable'+id).val('no');
//         $('.isAvailableRemark'+id).text('Selected')
//         myVals.push(id);
//         if (cur_val == '') {
//             $('#room_ids_val').val(cur_val + id);
//         } else {
//             $('#room_ids_val').val(cur_val + "," + id);
//         }
        
//         $('#header'+id).removeClass('bg-success');
//         $('#header'+id).addClass('bg-secondary');

//         $('#footer'+id).removeClass('bg-success');
//         $('#footer'+id).addClass('bg-secondary');
//     } else {
//         var index = myVals.indexOf(id);
//         if (index !== -1) {
//             myVals.splice(index, 1);
//         }

//         var remove = removeValue(cur_val, id);
//         remove.split(",").sort().join(",")
//         $('#room_ids_val').val(remove);

//         $('#isAvailable'+id).val('yes');
//         $('.isAvailableRemark'+id).text('Available')
//         $('#header'+id).addClass('bg-success');
//         $('#header'+id).removeClass('bg-secondary');

//         $('#footer'+id).addClass('bg-success');
//         $('#footer'+id).removeClass('bg-secondary');
//     }            

//     if (myVals.length > 0) {
//         $('#addNewSales').removeClass('hidden');
//         myVals.sort(function(a, b) {
//             return a - b;
//         });
//     } else {
//         $('#addNewSales').addClass('hidden');
//     }
// });

var UnAvailableRoom = [];
function loadRoom()
{
    UnAvailableRoom = [];
    $('.displayRoomList').html('');
    $.ajax({
        'url' : '/receptionist-lists',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (result.length > 3) {
                $('#room-availability').addClass('overflow');
            } else {
                $('#room-availability').removeClass('overflow');
            }

            $.each(result , function(index, val) { 
                var roomLink = '<a href="#" data-id="'+val.room_id+'" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>';
                var divAvailable = '';
                var divPointer = '';
                var isAvailable = 'no';
                var backgroundIcon = '<i class="fas fa-ban"></i>';
                if (val.data == '') {
                    divAvailable = 'divClickable';
                    divPointer = 'pointer';
                    isAvailable = 'yes';
                    backgroundIcon = '<i class="fas fa-hot-tub"></i>';
                    roomLink = '<div class="small-box-footer isFooterAvailable'+val.room_id+'">Available <i class="fas fa-plus-circle"></i></div>';
                }

                var fullName = '';
                var startTime = '0';
                var endTime = '0';
                if (val.data != '') {
                    fullName = val.data.client.firstname+' '+val.data.client.lastname;
                    startTime = val.data.start_time;
                    endTime = val.data.end_time;

                    UnAvailableRoom.push(val.room_id);
                }

                var displayRoomList = '<div data-id="'+val.room_id+'" class="col-md-4 '+divAvailable+' '+divPointer+'" id="'+val.room_id+'">';
                    displayRoomList += '<input type="hidden" id="isAvailable'+val.room_id+'" value="'+isAvailable+'">';
                    displayRoomList += '<div class="parentAvailDiv'+val.room_id+' small-box '+val.is_color_set+'">';
                        displayRoomList += '<div class="inner">';
                            displayRoomList += '<h4>Room #: '+val.room_id+'</h4>';
                            displayRoomList += '<h5>Name: '+fullName+'</h5>';
                            displayRoomList += '<p>Start Time: <b>'+startTime+'</b></p>';
                            displayRoomList += '<p>End TIme: <b>'+endTime+'</b></p>';
                        displayRoomList += '</div>';
                        displayRoomList += '<div class="icon">';
                            displayRoomList += backgroundIcon;
                        displayRoomList += '</div>';
                        displayRoomList += roomLink;
                        
                    displayRoomList += '</div>';
                displayRoomList += '</div>';
                $( displayRoomList ).appendTo(".displayRoomList");
            });
        }
    });
}

function getReservedTherapist(spa_id)
{
    $.ajax({
        'url' : '/receptionist-reserved/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            console.log(result);
        }
    });
}

function getLatestReservation(spa_id)
{
    $('#latest-reservation').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/transaction-latest-reservation/'+spa_id
        },
        columns: [
            { data: 'client', name: 'client', className: 'text-center'},
            { data: 'service', name: 'service'},
            { data: 'room', name: 'room', className: 'text-center'},
            { data: 'amount', name: 'amount', className: 'text-center'},
            { data: 'date', name: 'date', className: 'text-center' }
        ],
        "bDestroy": true,
        scrollX: true,
        scrollY: true,
        responsive:true,
        order:[4,'desc'],
        pageLength: 10
    });
}

var UnAvailableTherapist = [];
function getMasseurAvailability(spa_id)
{
    UnAvailableTherapist = [];
    $.ajax({
        'url' : '/transaction-masseur-availability/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('.availableMasseur').html('');
            $.each(result, function (key, value) {
                var availableMasseur = '<span class="masseurName">'+value.firstname+' '+value.lastname+'</span>';
                availableMasseur += '<span class="float-right"><b>160</b>/200</span>';
                availableMasseur += '<div class="progress progress-sm">';
                    availableMasseur += '<div class="progress-bar bg-info progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>';
                availableMasseur += '</div>';

                $( availableMasseur ).appendTo(".availableMasseur");

                if (value.data != '') {
                    UnAvailableTherapist.push(value.id);
                }
            });
        }
    });
}

$(document).on('click', '.divClickable', function () {
    var id = this.id;
    var is_available = $('#isAvailable'+id).val();
    var cur_val = $('#room_ids_val').val();

    if (is_available == 'yes') {
        $('#isAvailable'+id).val('no');
        $('.isFooterAvailable'+id).html('Selected <i class="fas fa-minus-circle"></i>')
        myVals.push(id);
        if (cur_val == '') {
            $('#room_ids_val').val(cur_val + id);
        } else {
            $('#room_ids_val').val(cur_val + "," + id);
        }
        
        $('.parentAvailDiv'+id).removeClass('bg-info');
        $('.parentAvailDiv'+id).addClass('bg-success');
    } else {
        var index = myVals.indexOf(id);
        if (index !== -1) {
            myVals.splice(index, 1);
        }

        var remove = removeValue(cur_val, id);
        remove.split(",").sort().join(",")
        $('#room_ids_val').val(remove);

        $('#isAvailable'+id).val('yes');
        $('.isFooterAvailable'+id).html('Available <i class="fas fa-plus-circle"></i>');
        $('.parentAvailDiv'+id).addClass('bg-info');
        $('.parentAvailDiv'+id).removeClass('bg-success');
    }            

    if (myVals.length > 0) {
        $('#addNewSales').removeClass('hidden');
        $('#addNewSales').addClass('btn-outline-info');
        $('#addNewSales').addClass('pointer');
        myVals.sort(function(a, b) {
            return a - b;
        });
    } else {
        $('#addNewSales').addClass('hidden');
        $('#addNewSales').removeClass('btn-outline-info');
        $('#addNewSales').removeClass('pointer');
    }

    $('.countSelected').text(myVals.length);
});

$(document).on('click', '.salesView', function () {
    var spa_id = $('#spa_id_val').val();
    loadSales(spa_id);
    getTotalSales(spa_id);
});

function getTotalSales(spa_id)
{
    $('.viewBadgeCount').text('');
    $.ajax({
        'url' : '/transaction-total-sales/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('.viewBadgeCount').text(result);
        }
    });
}

function loadSales(spa_id)
{
    $('#transaction-lists').DataTable({
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

var array = [];
$('.process-sales-btn').on('click', function() {
    var cur_val = $('#room_ids_val').val();
    const data = cur_val.split(',');
    if (data.length > 0) {
        array = [];
        var total_amount = 0;
        $.each(data, function (key, value) {
            var value_client_type = $('#client_type'+value).find(":selected").val();
            if (value_client_type.length < 1) {
                $('#error-client_type'+value).removeClass('hidden');
                $('#error-client_type'+value).text('Client Type field is required!');
            } else {
                $('#error-client_type'+value).addClass('hidden');
                $('#error-client_type'+value).text('');
            }

            var value_first_name = $('#first_name'+value).val();
            if (value_first_name.length < 1) {
                $('#error-first_name'+value).removeClass('hidden');
                $('#error-first_name'+value).text('First Name field is required!');
            } else {
                $('#error-first_name'+value).addClass('hidden');
                $('#error-first_name'+value).text('');
            }

            var value_middle_name = $('#middle_name'+value).val();

            var value_last_name = $('#last_name'+value).val();
            if (value_last_name.length < 1) {
                $('#error-last_name'+value).removeClass('hidden');
                $('#error-last_name'+value).text('Last Name field is required!');
            } else {
                $('#error-last_name'+value).addClass('hidden');
                $('#error-last_name'+value).text('');
            }

            var value_date_of_birth = $('#date_of_birth'+value).val();
            if (value_date_of_birth.length < 1) {
                $('#error-date_of_birth'+value).removeClass('hidden');
                $('#error-date_of_birth'+value).text('Date of Birth field is required!');
            } else {
                $('#error-date_of_birth'+value).addClass('hidden');
                $('#error-date_of_birth'+value).text('');
            }

            var value_mobile_number = $('#mobile_number'+value).val();
            if (value_mobile_number.length < 1) {
                $('#error-mobile_number'+value).removeClass('hidden');
                $('#error-mobile_number'+value).text('Mobile Number field is required!');
            } else {
                $('#error-mobile_number'+value).addClass('hidden');
                $('#error-mobile_number'+value).text('');
            }

            var value_email = $('#email'+value).val();
            var value_address = $('#address'+value).val();

            var services = $('#service_name'+value).select2('data');
            var value_services = services[0].id;
            var value_services_name = services[0].text;
            if (value_services.length < 1) {
                $('#error-service_name'+value).removeClass('hidden');
                $('#error-service_name'+value).text('Services field is required!');
            } else {
                $('#error-service_name'+value).addClass('hidden');
                $('#error-service_name'+value).text('');
            }

            var therapist_1 = $('#masseur'+value).select2('data');
            var value_therapist_1 = therapist_1[0].id;
            var value_therapist_1_name = therapist_1[0].text;

            if (value_therapist_1.length < 1) {
                $('#error-masseur'+value).removeClass('hidden');
                $('#error-masseur'+value).text('Masseur 1 field is required!');
            } else {
                $('#error-masseur'+value).addClass('hidden');
                $('#error-masseur'+value).text('');
            }

            var therapist_2 = $('#masseur_2'+value).select2('data');
            var value_therapist_2 = therapist_2[0].id;
            var value_therapist_2_name = therapist_2[0].text;

            var value_start_time = $('#start_time'+value).val();
            if (value_start_time.length < 1) {
                $('#error-start_time'+value).removeClass('hidden');
                $('#error-start_time'+value).text('Start Time field is required!');
            } else {
                $('#error-start_time'+value).addClass('hidden');
                $('#error-start_time'+value).text('');
            }

            var plus_time = $('#plus_time'+value).select2('data');
            var value_plus_time = plus_time[0].id;

            var value_room_id = $('#room_number'+value).val();
            if (value_room_id.length < 1) {
                $('#error-room_number'+value).removeClass('hidden');
                $('#error-room_number'+value).text('Room Number field is required!');
            } else {
                $('#error-room_number'+value).addClass('hidden');
                $('#error-room_number'+value).text('');
            }

            var plus_time_price = 0;
            if ($('#plus_time_price'+value).val() > 0) {
                plus_time_price = $('#plus_time_price'+value).val();
            }

            var existing_user_id = $('#existing_user_id_'+value).val();
            var price = parseInt($('#price'+value).val()) + parseInt(plus_time_price);
            var price_converted = ReplaceNumberWithCommas(price);
            var price_formatted ='&#8369;'+price_converted;

            total_amount += parseInt($('#price'+value).val()) + parseInt(plus_time_price);

            if (
                value_client_type.length < 1 ||
                value_first_name.length < 1 ||
                value_last_name.length < 1 ||
                value_date_of_birth.length < 1 ||
                value_mobile_number.length < 1 ||
                value_services.length < 1 ||
                value_therapist_1.length < 1 ||
                value_start_time.length < 1 ||
                value_room_id.length < 1
            ) {
                $('.tabTitle'+value).addClass('error-border');
            } else {
                $('.tabTitle'+value).removeClass('error-border');
            }

            if (
                value_client_type.length > 0 &&
                value_first_name.length > 0 &&
                value_last_name.length > 0 &&
                value_date_of_birth.length > 0 &&
                value_mobile_number.length > 0 &&
                value_services.length > 0 &&
                value_therapist_1.length > 0 &&
                value_start_time.length > 0 &&
                value_room_id.length > 0
            ) {
                $('#isValid').val(true);
                $('.summaryTabLink').removeClass('hidden');
            } else {
                $('#isValid').val(false);
                $('.summaryTabLink').addClass('hidden');
            }

            array.push({
                key: value,
                value_client_type: value_client_type,
                value_first_name: value_first_name,
                value_middle_name: value_middle_name,
                value_last_name: value_last_name,
                value_date_of_birth: value_date_of_birth,
                value_mobile_number: value_mobile_number,
                value_email: value_email,
                value_address: value_address,
                value_services: value_services,
                value_services_name: value_services_name,
                value_therapist_1: value_therapist_1,
                value_therapist_1_name: value_therapist_1_name,
                value_therapist_2: value_therapist_2,
                value_therapist_2_name: value_therapist_2_name,
                value_start_time: value_start_time,
                value_plus_time: value_plus_time,
                value_room_id: value_room_id,
                price: price,
                price_formatted: price_formatted,
                existing_user_id: existing_user_id
            })
        });

        var converted_amount = ReplaceNumberWithCommas(total_amount);
        $('#totalAmountToPay').val(total_amount);
        $('.total_amount').html('&#8369;'+converted_amount);
    }
});

$(document).on('click', '.roomNav', function () {
    var id = $(this).data("id");
    
    if (id == 'summary') {
        $('.add-sales-btn').removeClass('hidden');
        $('.process-sales-btn').addClass('hidden');

        $('.tableSummary').html('');
        var summaryContent = '<div class="table-responsive p-0">';
            summaryContent +='<table class="table table-hover datatable" id="summary-list">';
                summaryContent += '<thead>';
                    summaryContent += '<tr>';
                        summaryContent += '<th>Client</th>';
                        summaryContent += '<th>Service</th>';
                        summaryContent += '<th>Masseur</th>';
                        summaryContent += '<th>Start Time</th>';
                        summaryContent += '<th>Plus Time</th>';
                        summaryContent += '<th>Room</th>';
                        summaryContent += '<th>Amount</th>';
                    summaryContent += '</tr>';
                summaryContent += '</thead>';
                summaryContent += '<tbody class="summaryBody">';
                summaryContent += '</tbody>';
            summaryContent += '</table>';
        summaryContent += '</div>';

        $( summaryContent ).appendTo(".tableSummary");
        const dataSet = array.map(({
            value_first_name, 
            value_last_name,
            value_services_name,
            value_therapist_1_name,
            value_therapist_2_name,
            value_start_time,
            value_plus_time,
            value_room_id,
            price_formatted
        }) => [
            value_first_name+' '+value_last_name,
            value_services_name,
            value_therapist_1_name+'<br />'+value_therapist_2_name,
            value_start_time,
            value_plus_time,
            value_room_id,
            price_formatted
        ]);

        $('#summary-list').DataTable({
            data: dataSet,
            columns: [
              { title: 'Client' },
              { title: 'Service' },
              { title: 'Masseur' },
              { title: 'Start Time' },
              { title: 'Plus Time' },
              { title: 'Room' },
              { title: 'Amount' },
            ],
            paging: false,
            searching: false,
            info: false
        });
    } else {
        $('.add-sales-btn').addClass('hidden');
        $('.process-sales-btn').removeClass('hidden');
        $('.summaryTabLink').addClass('hidden');
    }
});

$('.add-sales-btn').on('click', function() {
    var data = array;
    var spa_id = $('#spa_id_val').val();
    var amount = $('#totalAmountToPay').val();

    swal.fire({
        title: "Are you sure you want to save the reservation?",
        icon: 'question',
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                'url' : '/create/'+spa_id+'/'+amount,
                'type' : 'POST',
                'data': {value: data},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#sales-form').find('.add-sales-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#sales-form').trigger('reset');
                        $('.countSelected').text('');
                        $('#addNewSales').addClass('hidden');
                        $('#room_ids_val').val('');
                        $('#isValid').val(false);
                        $('.process-sales-btn').removeClass('hidden');
                        $('.add-sales-btn').addClass('hidden');
                        myVals = [];
                        $('.countSelected').text('');

                        loadRoom();
                        getTotalSales(spa_id);
                        getMasseurAvailability(spa_id);
                        getLatestReservation(spa_id);
        
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-sales-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#sales-form').find('.add-sales-btn').val('Save').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
});

$(document).on('click', '.edit-sales-btn', function () {
    var id = this.id;
    var start_time = $(this).data("start_date");
    var spa_id = $('#spa_id_val').val();
    getServices(spa_id, 'update');
    getTherapists(spa_id, 'update');
    getPlusTime('update');
    getRoomList('update');

    $.ajax({
        'url' : '/transaction/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#edit_first_name').val('');
            $('#edit_middle_name').val('');
            $('#edit_last_name').val('');
            $('#edit_date_of_birth').val('');
            $('#edit_mobile_number').val('');
            $('#edit_email').val('');
            $('#edit_address').val('');
            $('#edit_client_type').val('');
            $('#edit_start_time').val('');
            $('.totalAmountFormatted').html('');
            $('#totalAmountEditToPay').val('');
            $('#totalAmountEditToPayOld').val('');
            $('#editCustomCheckbox').prop('checked', false); 
            $('#edit_masseur1_id_prev').val('');
            $('#edit_masseur2_id_prev').val('');
            $('#edit_masseur2_id_val').val('');
            $('#edit_transaction_id').val('');
            $('#edit_client_id').val('');
            $('#edit_sales_id').val('');
            $('#edit_masseur2').attr('disabled', false);
        },
        success: function(result){
            $('#edit_first_name').val(result.firstname);
            $('#edit_middle_name').val(result.middlename);
            $('#edit_last_name').val(result.lastname);
            $('#edit_date_of_birth').val(result.date_of_birth);
            $('#edit_mobile_number').val(result.mobile_number);
            $('#edit_email').val(result.email);
            $('#edit_address').val(result.address);
            $('#edit_client_type').val(result.client_type);
            $('#edit_start_time').val(start_time);
            $('#edit_masseur1_id').val(result.therapist_1);
            $('#edit_masseur1_id_prev').val(result.therapist_1);
            $('#edit_masseur2_id').val(result.therapist_2);
            $('#edit_masseur1_id_prev').val(result.therapist_2);
            $('#edit_masseur2_id_val').val(result.therapist_2_id);
            $('.totalAmountFormatted').html('&#8369; '+result.amount_formatted);
            $('#totalAmountEditToPay').val(result.amount);
            $('#totalAmountEditToPayOld').val(result.amount);
            $('#edit_transaction_id').val(result.id);
            $('#edit_client_id').val(result.client_id);
            $('#edit_sales_id').val(result.sales_id);
            
            $('#editCustomCheckbox').attr('disabled', false);
            $('#edit_masseur2').attr('disabled', true);
            if (result.therapist_2 != '') {
                $('#editCustomCheckbox').prop('checked', true); 
                $('#edit_masseur2').attr('disabled', false);
            }

            $(".select-edit-services").select2().val(result.service_id).trigger("change");
            $(".select-edit-masseur1").select2().val(result.therapist_1).trigger("change");
            $(".select-edit-masseur2").select2().val(result.therapist_2).trigger("change");
            if (result.plus_time != '') {
                $(".select-edit-plus_time").select2().val(result.plus_time).trigger("change");
                $('#edit_plus_time_price').val(0);
            }
            $(".select-edit-room").select2().val(result.room_id).trigger("change");

            $.each(UnAvailableRoom, function (key, value) {
                $('.select-edit-room').children('option[value="' + value + '"]').attr('disabled', true);
            });
        }
    });

    $('#update-sales-modal').modal('show');
});

$('.update-sales-btn').on('click', function() {
    var spa_id = $('#spa_id_val').val();
    var amount = $('#totalAmountEditToPay').val();
    var old_amount = $('#totalAmountEditToPayOld').val();

    var services = $('#edit_services').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var masseur2_id = $('#edit_masseur2_id_val').val();
    var plus_time = $('#edit_plus_time').select2('data');
    var value_plus_time = plus_time[0].id;
    var room_id = $('#edit_room').select2('data');
    var value_room_id = room_id[0].id;

    swal.fire({
        title: "Are you sure you want to update the reservation?",
        icon: 'question',
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Yes!",
        cancelButtonText: "No!",
        reverseButtons: !0
    }).then(function (e) {
        if (e.value === true) {
            $.ajax({
                'url' : '/update/'+spa_id+'/'+amount,
                'type' : 'PUT',
                'data': {
                    id: $('#edit_transaction_id').val(),
                    client_id: $('#edit_client_id').val(),
                    sales_id: $('#edit_sales_id').val(),
                    firstname: $('#edit_first_name').val(),
                    middlename: $('#edit_middle_name').val(),
                    lastname: $('#edit_last_name').val(),
                    date_of_birth: $('#edit_date_of_birth').val(),
                    mobile_number: $('#edit_mobile_number').val(),
                    email: $('#edit_email').val(),
                    address: $('#edit_address').val(),
                    client_type: $('#edit_client_type').val(),
                    service_id: value_services,
                    service_name: value_services_name,
                    therapist_1: $('#edit_masseur1_id').val(),
                    therapist_2: $('#edit_masseur2_id').val(),
                    therapist_2_id: masseur2_id,
                    start_time: $('#edit_start_time').val(),
                    plus_time: value_plus_time,
                    room_id: value_room_id,
                    old_amount: old_amount,
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#sales-update-form').find('.update-sales-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    console.log(result);
                    if(result.status) {
                        $('#sales-update-form').trigger('reset');
                        loadRoom();
                        loadSales(spa_id);
                        getTotalSales(spa_id);
                        getMasseurAvailability(spa_id);
                        getLatestReservation(spa_id);
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-sales-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#sales-update-form').find('.update-sales-btn').val('Save').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    })
});

$('#addNewSales').on('click', function() {
    $('#add-new-sales-modal').modal('show');
    var spa_id = $('#spa_id_val').val();
    if (myVals.length > 0) {
        $('.dataTabs').html('');
        $('.tabFormReservation').html('');
        $.each(myVals, function (key, value) {
            let isActive = '';
            let isDisabled = '';
            if ( key === 0) {
                isActive = 'active';
                isDisabled = 'disabled';
            }

            var tabs = '<li class="nav-item roomTab tabTitle'+value+'" id="'+value+'"><a id="'+value+'" class="nav-link roomNav '+isActive+'" href="#room'+value+'" data-id="'+value+'" data-toggle="tab">Room # '+value+'</a></li>';
            $( tabs ).appendTo(".dataTabs");
    
            var content = '<div class="tab-pane '+isActive+'" id="room'+value+'">';
                content += '<div class="form-group">';
                    content += '<div class="row">';
                        content += '<div class="col-md-12">';
                            content += '<label for="client_type'+value+'">Client Type</label><span class="isRequired">*</span>';
                            content += '<select data-id="'+value+'" name="client_type'+value+'" id="client_type'+value+'" class="form-control select-client-type" style="width:100%;">';
                                content += '<option value="" disabled selected>-- Choose Client type --</option>';
                                content += '<option value="new">New</option>';
                                content += '<option value="recurring">Recurring</option>';
                            content += '</select>';
                            content += '<p class="text-danger hidden" id="error-client_type'+value+'"></p>';
                            content += '<input type="hidden" class="form-control" id="formId" value="'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurDataCurSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurDataPrevSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurMultipleDataCurSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurMultipleDataPrevSelected'+value+'">';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group hidden clientSearch'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-12">';
                            content += '<label for="client_type'+value+'">Search Client Name</label><span class="isRequired">*</span>';
                            content += '<select data-search-id="'+value+'" name="search'+value+'" id="search'+value+'" class="form-control select-client-name" style="width:100%;"></select>';
                            content += '<input type="hidden" class="form-control" id="existing_user_id_'+value+'">';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group hidden clientInfo'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-4">';
                            content += '<label for="first_name'+value+'">First Name</label><span class="isRequired">*</span>';
                            content += '<input type="text" name="first_name'+value+'" id="first_name'+value+'" class="form-control">';
                            content += '<p class="text-danger hidden" id="error-first_name'+value+'"></p>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="middle_name'+value+'">Middle Name</label>';
                            content += '<input type="text" name="middle_name'+value+'" id="middle_name'+value+'" class="form-control">';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="last_name'+value+'">Last Name</label><span class="isRequired">*</span>';
                            content += '<input type="text" name="last_name'+value+'" id="last_name'+value+'" class="form-control">';
                            content += '<p class="text-danger hidden" id="error-last_name'+value+'"></p>';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group hidden clientContact'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-4">';
                            content += '<label for="date_of_birth'+value+'">Date of Birth</label><span class="isRequired">*</span>';
                            content += '<input type="date" name="date_of_birth'+value+'" id="date_of_birth'+value+'" class="form-control">';
                            content += '<p class="text-danger hidden" id="error-date_of_birth'+value+'"></p>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="mobile_number'+value+'">Mobile Number</label><span class="isRequired">*</span>';
                            content += '<input type="text" name="mobile_number'+value+'" id="mobile_number'+value+'" class="form-control">';
                            content += '<p class="text-danger hidden" id="error-mobile_number'+value+'"></p>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="email'+value+'">Email</label>';
                            content += '<input type="email" name="email'+value+'" id="email'+value+'" class="form-control">';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group hidden clientAddress'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-12">';
                            content += '<label for="address'+value+'">Address</label>';
                            content += '<input type="text" name="address'+value+'" id="address'+value+'" class="form-control">';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                content += '<div class="form-group hidden clientService'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-4">';
                            content += '<label for="services'+value+'">Services</label><span class="isRequired">*</span>';
                            content += '<select data-service_id="'+value+'" name="service_name'+value+'" id="service_name'+value+'" class="form-control select-services" style="width:100%;"></select>';
                            content += '<input type="hidden" name="price'+value+'" id="price'+value+'" class="form-control">';
                            content += '<p class="text-danger hidden" id="error-service_name'+value+'"></p>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="masseur'+value+'">Masseur 1</label><span class="isRequired">*</span>';
                            content += '<select data-id="'+value+'" name="masseur'+value+'" id="masseur'+value+'" class="form-control select-therapist" style="width:100%;"></select>';
                            content += '<p class="text-danger hidden" id="error-masseur'+value+'"></p>';
                            content += '<div class="custom-control custom-checkbox">';
                                content += '<input disabled class="custom-control-input isMultipleMasseur" type="checkbox" data-id="'+value+'" id="customCheckbox'+value+'" value="1">';
                                content += '<label for="customCheckbox'+value+'" class="custom-control-label">Is multiple Masseur ?</label>';
                            content += '</div>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="masseur_2'+value+'">Masseur 2</label>';
                            content += '<select data-id="'+value+'" name="masseur_2'+value+'" id="masseur_2'+value+'" class="form-control select-multiple-therapist" style="width:100%;" disabled></select>';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';
        
                content += '<div class="form-group hidden clientTime'+value+'">';
                    content += '<div class="row">';
                        content += '<div class="col-md-4">';
                            content += '<label for="start_time'+value+'">Start Time</label><span class="isRequired">*</span>';
                            content += '<input name="start_time'+value+'" id="start_time'+value+'" class="form-control dateTimePickerNew">';
                            content += '<p class="text-danger hidden" id="error-start_time'+value+'"></p>';
                        content += '</div>';
                        content += '<div class="col-md-4">';
                            content += '<label for="plus_time'+value+'">Plus Time</label>';
                            content += '<select data-plus-id="'+value+'" name="plus_time'+value+'" id="plus_time'+value+'" class="form-control select-plus-time" style="width:100%;"></select>';
                            content += '<input type="hidden" name="plus_time_price'+value+'" id="plus_time_price'+value+'" class="form-control">';
                        content += '</div>';
                            content += '<div class="col-md-4">';
                            content += '<label for="room_number'+value+'">Assigned Room</label><span class="isRequired">*</span>';
                            content += '<input type="text" name="room_number'+value+'" id="room_number'+value+'" class="form-control" value="'+value+'" disabled>';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';
            content += '</div>';
            $( content ).appendTo(".tabFormReservation");

            $('#start_time'+value).datetimepicker({
                footer: true, modal: true,
                datepicker: {
                    // disableDates:  function (date) {
                    //     const currentDate = new Date();
                    // return date > currentDate ? true : false;
                    // }
                }
            });
        });

        var summaryTabs = '<li class="nav-item roomTab summaryTabLink hidden"><a class="nav-link roomNav summaryTab" href="#summaryTab" data-id="summary" data-toggle="tab">Summary</a></li>';
        $( summaryTabs ).appendTo(".dataTabs");

        var summaryContent = '<div class="tab-pane" id="summaryTab">';
            summaryContent += '<div class="tableSummary"></div>';
            summaryContent += '<div class="py-2 px-3 mt-4">';
                summaryContent += '<div class="col-md-4 border border-danger float-right">';
                    summaryContent += '<h2 class="mb-0 total_amount text-center"></h2>';
                    summaryContent += '<h4 class="mt-0 text-center">TOTAL</h4>';
                    summaryContent += '<input type="hidden" class="form-control" id="totalAmountToPay">';
                summaryContent += '</div>';
            summaryContent += '</div>';
        summaryContent += '</div>';
        $( summaryContent ).appendTo(".tabFormReservation");
    }

    getTherapists(spa_id, 'new');
    getServices(spa_id, 'new');
    getPlusTime('new');
    getRoomList('new');
});

$(document).on('change', '.select-plus-time', function () {
    var room_id = $(this).data("plus-id")
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    var services = $('#service_name'+room_id).select2('data');
    var value_services = services[0].id;

    $('#plus_time_price'+room_id).val(0);
    $.ajax({
        'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#plus_time_price'+room_id).val(result);
        }
    });
});

$(document).on('change', '.select-services', function () {
    var room_id = $(this).data("service_id")
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    $('#price'+room_id).val('');
    $('#plus_time_price'+room_id).val(0);

    if (selected_id.length > 0) {
        $('#plus_time'+room_id).attr('disabled', false);
    } else {
        $('#plus_time'+room_id).attr('disabled', true);
    }

    $.ajax({
        'url' : '/service-price/'+selected_id+'/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#price'+room_id).val(result);
        }
    });
});

$(document).on('change', '.select-therapist', function () {
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    var formId = $('#formId').val();
    var room_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var cur_val = $('#masseurDataCurSelected'+room_id).val();
    var prev_val = $('#masseurDataPrevSelected'+room_id).val();

    if (selected_id.length > 0) {
        $('#customCheckbox'+room_id).attr('disabled',false);

        if (cur_val == '') {
            if (prev_val == '') {
                $('#masseurDataCurSelected'+room_id).val(selected_id);
                $('#masseurDataPrevSelected'+room_id).val(selected_id);
            } else {
                $('#masseurDataCurSelected').val(selected_id);
            }

            $('#masseurData').val(selected_id);
            $('.select-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
            $('.select-multiple-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
        } else {
            if (formId == room_id) {
                if (cur_val == prev_val) {
                    $('#masseurDataCurSelected'+room_id).val(selected_id);
                    $('.select-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);

                    $('.select-multiple-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);
                } else {
                    $('.select-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);

                    $('.select-multiple-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);

                    $('#masseurDataPrevSelected'+room_id).val(cur_val);
                    $('#masseurDataCurSelected'+room_id).val(selected_id);
                }

                $('#masseurData').val(selected_id);
            } else {
                if (cur_val == prev_val) {
                    $('.select-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);

                    $('.select-multiple-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);
                } else {
                    $('.select-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);

                    $('.select-multiple-therapist').children('option[value="' + selected_id + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);
                }
            }
        }

        $('.select-therapist').select2();
        $('.select-multiple-therapist').select2();
    } else {
        $('#customCheckbox'+room_id).attr('disabled',true);
        $("#customCheckbox"+room_id).prop('checked', false); 
        $('#masseur_2'+room_id).attr('disabled',true);
    }
});

$(document).on('change', '.select-multiple-therapist', function () {
    var selected = $(this).select2('data');
    var id = selected[0].id;
    var formId = $('#formId').val();
    var room_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();

    var cur_val = $('#masseurMultipleDataCurSelected'+room_id).val();
    var prev_val = $('#masseurMultipleDataPrevSelected'+room_id).val();

    if (cur_val == '') {
        if (prev_val == '') {
            $('#masseurMultipleDataCurSelected'+room_id).val(id);
            $('#masseurMultipleDataPrevSelected'+room_id).val(id);
        } else {
            $('#masseurMultipleDataCurSelected').val(id);
        }
        $('.select-therapist').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-multiple-therapist').children('option[value="' + id + '"]').attr('disabled', true);
    } else {
        if (formId == room_id) {
            if (cur_val == prev_val) {
                $('#masseurMultipleDataCurSelected'+room_id).val(id);
                $('.select-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);

                $('.select-multiple-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-multiple-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);
            } else {
                $('.select-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);

                $('.select-multiple-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-multiple-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);

                $('#masseurMultipleDataPrevSelected'+room_id).val(cur_val);
                $('#masseurMultipleDataCurSelected'+room_id).val(id);
            }
        } else {
            if (cur_val == prev_val) {
                $('.select-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);

                $('.select-multiple-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-multiple-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);
            } else {
                $('.select-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-therapist').children('option[value="' + cur_val + '"]').attr('disabled', false);

                $('.select-multiple-therapist').children('option[value="' + id + '"]').attr('disabled', true);
                $('.select-multiple-therapist').children('option[value="' + prev_val + '"]').attr('disabled', false);
            }
        }
    }
    $('.select-therapist').select2();
    $('.select-multiple-therapist').select2();
});

$(document).on('click', '.isMultipleMasseur', function () {
    var id = $(this).data("id")
    var value = $('#masseurMultipleDataCurSelected'+id).val();

    if ($('#customCheckbox'+id).is(':checked') == true) {
        $('#masseur_2'+id).attr('disabled',false);
        $('#masseur_2'+id).append('<option></option>');
        $('#masseur_2'+id).select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    } else {
        $('.select-therapist').children('option[value="' + value + '"]').attr('disabled', false);
        $('.select-multiple-therapist').children('option[value="' + value + '"]').attr('disabled', false);
        $('.select-therapist').select2();
        $('.select-multiple-therapist').select2();

        $('#masseur_2'+id).append('<option selected></option>');
        $('#masseur_2'+id).select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });

        $('#masseurMultipleDataCurSelected'+id).val('');
        $('#masseurMultipleDataPrevSelected'+id).val('');

        $('#masseur_2'+id).attr('disabled',true);
    }
});

function getTherapists(spa_id, status)
{
    $.ajax({
        'url' : '/receptionist-therapist/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (status == 'new') {
                $('.select-therapist').html('');
                $('.select-therapist').append('<option></option>');
                $('.select-therapist').select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: true
                }); 
    
                $('.select-multiple-therapist').html('');
                $('.select-multiple-therapist').append('<option></option>');
                $('.select-multiple-therapist').select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: true
                }); 
    
                $.each(result , function(index, val) { 
                    $('.select-therapist').append('<option value="'+val+'">'+index+'</option>');
                    $('.select-multiple-therapist').append('<option value="'+val+'">'+index+'</option>');
                });
            } else {
                $('.select-edit-masseur1').html('');
                $('.select-edit-masseur1').append('<option></option>');
                $('.select-edit-masseur1').select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: true
                }); 
    
                $('.select-edit-masseur2').html('');
                $('.select-edit-masseur2').append('<option></option>');
                $('.select-edit-masseur2').select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: true
                }); 
    
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-edit-masseur1').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                    $('.select-edit-masseur2').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            }

            if (UnAvailableTherapist.length > 0) {
                $.each(UnAvailableTherapist , function(un_index, un_val) { 
                    $('.select-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-therapist').select2();
                    $('.select-multiple-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').select2();

                    $('.select-edit-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-edit-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

                    $('.select-edit-masseur1').select2();
                    $('.select-edit-masseur2').select2();
                });
            }
        }
    });
}

$('#add-new-sales-modal').on('hidden.bs.modal', function () {
    $('.changeModalSize').addClass('modal-md');
    $('.changeModalSize').removeClass('modal-xl');
});

$(document).on('click', '.select-client-type', function () {
    var id = $(this).data("id");
    var val = $(this).find(":selected").val();
    
    $('#first_name'+id).val('');
    $('#middle_name'+id).val('');
    $('#last_name'+id).val('');
    $('#date_of_birth'+id).val('');
    $('#mobile_number'+id).val('');
    $('#email'+id).val('');
    $('#address'+id).val('');
    $('#start_time'+id).val('');
    $('#existing_user_id_'+id).val('');
    
    if (val === 'new') { 
        $('.clientInfo'+id).removeClass('hidden');
        $('.clientContact'+id).removeClass('hidden');
        $('.clientAddress'+id).removeClass('hidden');
        $('.clientSearch'+id).addClass('hidden');
    } else if (val === 'recurring') {
        $('.clientSearch'+id).removeClass('hidden');
        $('.clientInfo'+id).removeClass('hidden');
        $('.clientContact'+id).removeClass('hidden');
        $('.clientAddress'+id).removeClass('hidden');
        clientList(id);
    }

    if (val.length > 0) {
        $('.clientService'+id).removeClass('hidden');
        $('.clientTime'+id).removeClass('hidden');
    }

    $('.changeModalSize').removeClass('modal-md');
    $('.changeModalSize').addClass('modal-xl');
});

function clientList(id)
{
    var room_id = id;
    $.ajax({
        'url' : '/client-list',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (result != '') {
                $('#search'+id).html('');
                $('#search'+id).append('<option></option>');
                $('#search'+id).select2({
                    placeholder: "Choose Client Name",
                    allowClear: true
                }); 

                $.each(result , function(index, val) { 
                    $('#search'+id).append('<option value="'+val+'">'+index+'</option>');
                });
            } else {
                $('#search'+id).append('<option value="" disabled selected>-- No data found --</option>');
            }
        }
    });
}

$(document).on('change', '.select-client-name', function () {
    var id = $(this).data("search-id");
    var selected = $(this).select2('data');
    var value = selected[0].id;

    $.ajax({
        'url' : '/client/'+value,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (result.client != '') {
                $('#existing_user_id_'+id).val(result.client.id);
                $('#first_name'+id).val(result.client.firstname);
                $('#middle_name'+id).val(result.client.firstname);
                $('#last_name'+id).val(result.client.lastname);
                $('#date_of_birth'+id).val(result.client.date_of_birth);
                $('#mobile_number'+id).val(result.client.mobile_number);
                $('#email'+id).val(result.client.email);
                $('#address'+id).val(result.client.address);
            } else {
                $('#search'+id).append('<option value="" disabled selected>-- No data found --</option>');
            }
        }
    });
});

function getServices(spa_id, status)
{
    $.ajax({
        'url' : '/receptionist-service/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (status == 'new') {
                $('.select-services').html('');
                $('.select-services').append('<option></option>');
                $('.select-services').select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
                $.each(result , function(index, val) { 
                    $('.select-services').append('<option value="'+val+'">'+index+'</option>');
                });
            } else {
                $('.select-edit-services').html('');
                $('.select-edit-services').append('<option></option>');
                $('.select-edit-services').select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
    
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-edit-services').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            }
        }
    });
}

function getPlusTime(status)
{
    $.ajax({
        'url' : '/receptionist-plus-range/',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (status == 'new') {
                $('.select-plus-time').html('');
                $('.select-plus-time').append('<option></option>');
                $('.select-plus-time').select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }); 
                $.each(result , function(index, val) { 
                    $('.select-plus-time').append('<option value="'+val+'">'+index+'</option>');
                });
            } else {
                $('.select-edit-plus_time').html('');
                $('.select-edit-plus_time').append('<option></option>');
                $('.select-edit-plus_time').select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }); 
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-edit-plus_time').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            }
        }
    });
}

function getRoomList(status)
{
    var numberOfRooms = $('#numberOfRooms').val();
    $.ajax({
        'url' : '/receptionist-room-range/'+numberOfRooms,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (status == 'update') {
                $('.select-edit-room').html('');
                $('.select-edit-room').append('<option></option>');
                $('.select-edit-room').select2({
                    placeholder: "Choose Room",
                    allowClear: true
                }); 
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-edit-room').append('<option value="'+edit_val+'">'+edit_val+'</option>');
                });
            }
        }
    });
}

$(document).on('change', '.select-edit-services', function () {
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;

    $('#edit_price').val(0);
    var plus_time_val = $('#edit_plus_time_price').val();
    if (selected_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_id+'/'+spa_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#edit_price').val(result);
                var price = parseInt(result) + parseInt($('#edit_plus_time_price').val());
    
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountFormatted').html(price_formatted);
                $('#totalAmountEditToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#edit_price').val()) + parseInt($('#edit_plus_time_price').val());
    
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountFormatted').html(price_formatted);
        $('#totalAmountEditToPay').val(price);
    }

    triggerPlusTime();
});

$(document).on('change', '.select-edit-plus_time', function () {
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    var services = $('.select-edit-services').select2('data');
    var value_services = services[0].id;

    $('#edit_plus_time_price').val(0);
    if (selected_id != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#edit_plus_time_price').val(result);
                var price = parseInt(result) + parseInt($('#edit_price').val());
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountFormatted').html(price_formatted);
                $('#totalAmountEditToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#edit_plus_time_price').val()) + parseInt($('#edit_price').val());
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountFormatted').html(price_formatted);
        $('#totalAmountEditToPay').val(price);
    }
});

function triggerPlusTime()
{
    var spa_id = $('#spa_id_val').val();
    var plusTime = $('.select-edit-plus_time').select2('data');
    var value_plusTime = plusTime[0].id;

    var services = $('.select-edit-services').select2('data');
    var value_services = services[0].id;

    if (value_plusTime != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+value_plusTime,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#edit_plus_time_price').val(result);
                var price = parseInt(result) + parseInt($('#edit_price').val());
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369;'+price_converted;
    
                $('.totalAmountFormatted').html(price_formatted);
                $('#totalAmountEditToPay').val(price);
            }
        });
    } else {
        $('#edit_plus_time_price').val(0);
        var price = 0;
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369;'+price_converted;

        $('.totalAmountFormatted').html(price_formatted);
        $('#totalAmountEditToPay').val(price);
    }
}

$(document).on('click', '.isEditMultipleMasseur', function () {
    var therapist_2_val = $('#edit_masseur2_id').val();

    if ($('#editCustomCheckbox').is(':checked') == false) {
        $('.select-edit-masseur1').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $('.select-edit-masseur1').select2();

        $('.select-edit-masseur2').attr('disabled',true);
        $('.select-edit-masseur2').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $(".select-edit-masseur2").select2().val('').trigger("change");
        $('#edit_masseur2_id').val('');
    } else {
        $('.select-edit-masseur2').attr('disabled',false);
    }
});

$('.select-edit-masseur1').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    var cur_val = $('#edit_masseur1_id').val();

    if (cur_val !== id) {
        $('.select-edit-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-edit-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-edit-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-edit-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#edit_masseur1_id').val(id);
    
        $('.select-edit-masseur1').select2();
        $('.select-edit-masseur2').select2();
    }
});

$('.select-edit-masseur2').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    var cur_val = $('#edit_masseur2_id').val();

    if (cur_val !== id) {
        $('.select-edit-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-edit-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-edit-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-edit-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#edit_masseur2_id').val(id);
    
        $('.select-edit-masseur1').select2();
        $('.select-edit-masseur2').select2();
    }
});

function ReplaceNumberWithCommas(value) {
    var n= value.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    return n.join(".");
}

function removeValue(list, value) {
    list = list.split(',');
    list.splice(list.indexOf(value), 1);
    return list.join(',');
}