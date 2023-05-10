var myVals = [];
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
            $('.countSelected').text(0);
            if (result.length > 3) {
                $('#room-availability').addClass('overflow');
            } else {
                $('#room-availability').removeClass('overflow');
            }

            $.each(result , function(index, val) { 
                var roomLink = '<a href="#" data-transaction_id="'+val.data.id+'" data-id="'+val.room_id+'" class="small-box-footer reservedInfo">More info <i class="fas fa-arrow-circle-right"></i></a>';
                var divAvailable = '';
                var divPointer = '';
                var isAvailable = 'no';
                var backgroundIcon = '<i class="fas fa-ban"></i>';
                if (val.data == '') {
                    // divAvailable = 'divClickable';
                    divAvailable = '';
                    divPointer = 'pointer_ ';
                    isAvailable = 'yes';
                    backgroundIcon = '<i class="fas fa-hot-tub"></i>';
                    roomLink = '<div class="small-box-footer isFooterAvailable'+val.room_id+'">Available <i class="fas fa-plus-circle"></i></div>';
                }

                var fullName = '';
                var startTime = '0';
                var endTime = '0';
                var roomTime = 0;
                if (val.data != '') {
                    fullName = val.data.client.firstname+' '+val.data.client.lastname;
                    startTime = val.data.start_time;
                    endTime = val.data.end_time;
                    roomTime = val.data.start_and_end_time;
                    UnAvailableRoom.push(val.room_id);
                }

                var displayRoomList = '<div data-id="'+val.room_id+'" class="col-md-4 '+divAvailable+' '+divPointer+'" id="'+val.room_id+'">';
                    displayRoomList += '<input type="hidden" id="isAvailable'+val.room_id+'" value="'+isAvailable+'">';
                    displayRoomList += '<div class="parentAvailDiv'+val.room_id+' small-box '+val.is_color_set+'">';
                        displayRoomList += '<div class="inner">';
                            displayRoomList += '<h4>Room #: '+val.room_id+'</h4>';
                            displayRoomList += '<h6>Name: <b>'+fullName+'</b></h6>';
                            displayRoomList += '<h6>Time: <b>'+roomTime+'</b></h6>';
                            displayRoomList += '<h6>Remaining Time: <b><span id="countdown'+val.room_id+'"></span></b></h6>';
                        displayRoomList += '</div>';
                        displayRoomList += '<div class="icon">';
                            displayRoomList += backgroundIcon;
                        displayRoomList += '</div>';
                        displayRoomList += roomLink;
                        
                    displayRoomList += '</div>';
                displayRoomList += '</div>';
                $( displayRoomList ).appendTo(".displayRoomList");

                if (endTime != 0) {
                    countdown(val.room_id, val.data.start_time, val.data.end_time);
                }
            });
        }
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
                var names;
                if (value.data != '') {
                    UnAvailableTherapist.push(value.id);
                    countdownTherapist(value.id, value.data.start_time, value.data.end_time, value.data.total_seconds);

                    names = value.firstname+' '+value.lastname+' <small class="font-weight-bold text-danger">[ Room # '+value.data.room_id+' ]</small>';
                } else {
                    names = value.firstname+' '+value.lastname;
                }

                var availableMasseur = '<span class="masseurName">'+names+'</span>';
                availableMasseur += '<div class="progress progress-xl">';
                    availableMasseur += '<div id="progressBarCalc'+value.id+'" class="progress-bar bg-info progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>';
                    availableMasseur += '<span id="countdownTherapistPercentage'+value.id+'">Available</span>';
                availableMasseur += '</div>';

                $( availableMasseur ).appendTo(".availableMasseur");
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
        $('#addNewSales').addClass('pointer');
        myVals.sort(function(a, b) {
            return a - b;
        });
        $('.countSelected').text(myVals.length);
    } else {
        $('#addNewSales').removeClass('pointer');
        $('.countSelected').text(0);
    }

    
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

var array = [];
$('.process-sales-btn').on('click', function() {
    var cur_val = $('#room_ids_val').val();
    const data = cur_val.split(',');
    if (data.length > 0) {
        array = [];
        var total_amount = 0;
        $.each(data, function (key, value) {
            var value_client_type = $('#client_type'+value).val();

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
                        $('.countSelected').text(0);
                        $('#room_ids_val').val('');
                        $('#isValid').val(false);
                        $('.process-sales-btn').removeClass('hidden');
                        $('.add-sales-btn').addClass('hidden');
                        $('.summaryTabLink').addClass('hidden');
                        myVals = [];

                        loadRoom();
                        getTotalSales(spa_id);
                        getMasseurAvailability(spa_id);

                        loadData(spa_id);

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
    getTherapists(spa_id, 'update', 0);
    getPlusTime('update', 0);
    getRoomList('update', 0);

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

            $(".select-edit-services").select2({
                placeholder: "Choose Services",
                allowClear: true
            }).val(result.service_id).trigger("change");
            $(".select-edit-masseur1").select2({
                placeholder: "Choose Masseur 1",
                allowClear: true
            }).val(result.therapist_1).trigger("change");
            $(".select-edit-masseur2").select2({
                placeholder: "Choose Masseur 2",
                allowClear: true
            }).val(result.therapist_2).trigger("change");
            if (result.plus_time != '') {
                $(".select-edit-plus_time").select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }).val(result.plus_time).trigger("change");
                $('#edit_plus_time_price').val(0);
            }

            $(".select-edit-room").select2({
                placeholder: "Choose Room",
                allowClear: true
            }).val(result.room_id).trigger("change");

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
                    if(result.status) {
                        $('#sales-update-form').trigger('reset');
                        loadRoom();
                        loadSales(spa_id);
                        getTotalSales(spa_id);
                        getMasseurAvailability(spa_id);
                        loadData(spa_id);
        
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
    var spa_id = $('#spa_id_val').val();
    if (myVals.length > 0) {
        $('#add-new-sales-modal').modal('show');
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
                            content += '<label for="filter_client'+value+'">Client:</label><span class="isRequired">*</span>';
                            content += '<input type="text" class="form-control filterClient clientFilter'+value+'" id="'+value+'">';
                            content += '<div id="suggesstion-box'+value+'" class="list-group suggesstion-box hidden"></div>';
                            // content += '<select data-id="'+value+'" name="client_type'+value+'" id="client_type'+value+'" class="form-control select-client-type" style="width:100%;">';
                            
                            //     content += '<option value="" disabled selected>-- Choose Client type --</option>';
                            //     content += '<option value="new">New</option>';
                            //     content += '<option value="recurring">Recurring</option>';
                            // content += '</select>';
                            content += '<input type="hidden" class="form-control" id="formId" value="'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurDataCurSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurDataPrevSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurMultipleDataCurSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="masseurMultipleDataPrevSelected'+value+'">';
                            content += '<input type="hidden" class="form-control" id="existing_user_id_'+value+'">';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';

                // content += '<div class="form-group hidden clientSearch'+value+'">';
                //     content += '<div class="row">';
                //         content += '<div class="col-md-12">';
                //             content += '<label for="client_type'+value+'">Search Client Name</label><span class="isRequired">*</span>';
                //             content += '<select data-search-id="'+value+'" name="search'+value+'" id="search'+value+'" class="form-control select-client-name" style="width:100%;"></select>';
                //             content += '<input type="hidden" class="form-control" id="existing_user_id_'+value+'">';
                //         content += '</div>';
                //     content += '</div>';
                // content += '</div>';

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
                        content += '<div class="col-md-4">';
                            content += '<label for="client_type'+value+'">Client Type</label><span class="isRequired">*</span>';
                            content += '<input type="text" data-id="'+value+'" name="client_type'+value+'" id="client_type'+value+'" class="form-control select-client-type">';
                        content += '</div>';
                        content += '<div class="col-md-8">';
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
                    format: 'dd mmmm yyyy hh:MM TT',
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
    } else {
        alert('Please select at least 1 available room.');
    }

    getTherapists(spa_id, 'new', 0);
    getServices(spa_id, 'new');
    getPlusTime('new', 0);
    getRoomList('new', 0);
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

        $('.select-therapist').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-multiple-therapist').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
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
    $('.select-therapist').select2({
        placeholder: "Choose Masseur 1",
        allowClear: true
    });
    $('.select-multiple-therapist').select2({
        placeholder: "Choose Masseur 2",
        allowClear: true
    });
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
        $('.select-therapist').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-multiple-therapist').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });

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

function getTherapists(spa_id, status, id)
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
            } else if (status == 'update') {
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
            } else if (status == 'move') {
                $('.select-move-masseur1').html('');
                $('.select-move-masseur1').append('<option></option>');
                $('.select-move-masseur1').select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: true
                }); 
    
                $('.select-move-masseur2').html('');
                $('.select-move-masseur2').append('<option></option>');
                $('.select-move-masseur2').select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: true
                }); 
    
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-move-masseur1').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                    $('.select-move-masseur2').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            } else if (status == 'appointment') {
                $('#appointment_masseur1'+id).html('');
                $('#appointment_masseur1'+id).append('<option></option>');
                $('#appointment_masseur1'+id).select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: true
                }); 
    
                $('#appointment_masseur2'+id).html('');
                $('#appointment_masseur2'+id).append('<option></option>');
                $('#appointment_masseur2'+id).select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: true
                }); 
    
                $.each(result , function(edit_index, edit_val) { 
                    $('#appointment_masseur1'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
                    $('#appointment_masseur2'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            }

            if (UnAvailableTherapist.length > 0) {
                $.each(UnAvailableTherapist , function(un_index, un_val) { 
                    $('.select-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-therapist').select2({
                        placeholder: "Choose Masseur 1",
                        allowClear: true
                    });
                    $('.select-multiple-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-multiple-therapist').select2({
                        placeholder: "Choose Masseur 2",
                        allowClear: true
                    });

                    $('.select-edit-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-edit-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

                    $('.select-edit-masseur1').select2({
                        placeholder: "Choose Masseur 1",
                        allowClear: true
                    });
                    $('.select-edit-masseur2').select2({
                        placeholder: "Choose Masseur 2",
                        allowClear: true
                    });

                    $('.select-move-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('.select-move-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

                    $('.select-move-masseur1').select2({
                        placeholder: "Choose Masseur 1",
                        allowClear: true
                    });
                    $('.select-move-masseur2').select2({
                        placeholder: "Choose Masseur 2",
                        allowClear: true
                    });

                    $('#appointment_masseur1'+id).children('option[value="' + un_val + '"]').attr('disabled', true);
                    $('#appointment_masseur2'+id).children('option[value="' + un_val + '"]').attr('disabled', true);

                    $('#appointment_masseur1'+id).select2({
                        placeholder: "Choose Masseur 1",
                        allowClear: true
                    });
                    $('#appointment_masseur2'+id).select2({
                        placeholder: "Choose Masseur 2",
                        allowClear: true
                    });
                });
            }
        }
    });
}

$('#add-new-sales-modal').on('hidden.bs.modal', function () {
    // $('.changeModalSize').addClass('modal-md');
    // $('.changeModalSize').removeClass('modal-xl');
});

// $(document).on('click', '.select-client-type', function () {
//     var id = $(this).data("id");
//     var val = $(this).find(":selected").val();
    
//     $('#first_name'+id).val('');
//     $('#middle_name'+id).val('');
//     $('#last_name'+id).val('');
//     $('#date_of_birth'+id).val('');
//     $('#mobile_number'+id).val('');
//     $('#email'+id).val('');
//     $('#address'+id).val('');
//     $('#start_time'+id).val('');
//     $('#existing_user_id_'+id).val('');
    
//     if (val === 'new') { 
//         $('.clientInfo'+id).removeClass('hidden');
//         $('.clientContact'+id).removeClass('hidden');
//         $('.clientAddress'+id).removeClass('hidden');
//         $('.clientSearch'+id).addClass('hidden');
        
//         $('#first_name'+id).prop( "disabled", false );
//         $('#middle_name'+id).prop( "disabled", false );
//         $('#last_name'+id).prop( "disabled", false );
//     } else if (val === 'recurring') {
//         $('.clientSearch'+id).removeClass('hidden');
//         $('.clientInfo'+id).removeClass('hidden');
//         $('.clientContact'+id).removeClass('hidden');
//         $('.clientAddress'+id).removeClass('hidden');
//         clientList(id);
//     }

//     if (val.length > 0) {
//         $('.clientService'+id).removeClass('hidden');
//         $('.clientTime'+id).removeClass('hidden');
//     }

//     // $('.changeModalSize').removeClass('modal-md');
//     // $('.changeModalSize').addClass('modal-xl');
// });

// function clientList(id)
// {
//     var room_id = id;
//     $.ajax({
//         'url' : '/client-list',
//         'type' : 'GET',
//         'data' : {},
//         'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         success: function(result){
//             if (result != '') {
//                 $('#search'+id).html('');
//                 $('#search'+id).append('<option></option>');
//                 $('#search'+id).select2({
//                     placeholder: "Choose Client Name",
//                     allowClear: true
//                 }); 

//                 $.each(result , function(index, val) { 
//                     $('#search'+id).append('<option value="'+val+'">'+index+'</option>');
//                 });
//             } else {
//                 $('#search'+id).append('<option value="" disabled selected>-- No data found --</option>');
//             }
//         }
//     });
// }

// $(document).on('change', '.select-client-name', function () {
//     var id = $(this).data("search-id");
//     var selected = $(this).select2('data');
//     var value = selected[0].id;

//     $.ajax({
//         'url' : '/client/'+value,
//         'type' : 'GET',
//         'data' : {},
//         'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         success: function(result){
//             if (result.client != '') {
//                 $('#existing_user_id_'+id).val(result.client.id);
//                 $('#first_name'+id).val(result.client.firstname);
//                 $('#first_name'+id).prop( "disabled", true );
//                 $('#middle_name'+id).val(result.client.middlename);
//                 $('#middle_name'+id).prop( "disabled", true );
//                 $('#last_name'+id).val(result.client.lastname);
//                 $('#last_name'+id).prop( "disabled", true );
//                 $('#date_of_birth'+id).val(result.client.date_of_birth);
//                 $('#mobile_number'+id).val(result.client.mobile_number);
//                 $('#email'+id).val(result.client.email);
//                 $('#address'+id).val(result.client.address);
//             } else {
//                 $('#search'+id).append('<option value="" disabled selected>-- No data found --</option>');
//             }
//         }
//     });
// });

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

function getPlusTime(status, id)
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
            } else if (status == 'update') {
                $('.select-edit-plus_time').html('');
                $('.select-edit-plus_time').append('<option></option>');
                $('.select-edit-plus_time').select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }); 
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-edit-plus_time').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            } else if (status == 'move') {
                $('.select-move-plus_time').html('');
                $('.select-move-plus_time').append('<option></option>');
                $('.select-move-plus_time').select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }); 
                $.each(result , function(edit_index, edit_val) { 
                    $('.select-move-plus_time').append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            } else if (status == 'appointment') {
                $('#plus_time_appointment'+id).html('');
                $('#plus_time_appointment'+id).append('<option></option>');
                $('#plus_time_appointment'+id).select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }); 
                $.each(result , function(edit_index, edit_val) { 
                    $('#plus_time_appointment'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            }
        }
    });
}

function getRoomList(status, id)
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
            } else if (status == 'move') {
                $('.select-move-room').html('');
                $('.select-move-room').append('<option></option>');
                $('.select-move-room').select2({
                    placeholder: "Choose Room",
                    allowClear: true
                }); 


                $.each(result , function(move_index, move_val) { 
                    $('.select-move-room').append('<option value="'+move_val+'">'+move_val+'</option>');
                });

                $.each(UnAvailableRoom, function (key, value) {
                    $('.select-move-room').children('option[value="' + value + '"]').attr('disabled', true);
                });
            } else if (status == 'appointment') {
                $('#appointment_room'+id).html('');
                $('#appointment_room'+id).append('<option></option>');
                $('#appointment_room'+id).select2({
                    placeholder: "Choose Room",
                    allowClear: true
                }); 

                $.each(result , function(appointment_index, appointment_val) { 
                    $('#appointment_room'+id).append('<option value="'+appointment_val+'">'+appointment_val+'</option>');
                });

                $.each(UnAvailableRoom, function (key, value) {
                    $('#appointment_room'+id).children('option[value="' + value + '"]').attr('disabled', true);
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
        $('.select-edit-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });

        $('.select-edit-masseur2').attr('disabled',true);
        $('.select-edit-masseur2').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $(".select-edit-masseur2").select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        }).val('').trigger("change");
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
    
        $('.select-edit-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-edit-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
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
    
        $('.select-edit-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-edit-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    }
});

$(document).on('click', '.reservedInfo', function () {
    var id = $(this).data("transaction_id");

    $.ajax({
        'url' : '/transaction/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){

            $('.viewRoomNumber').text('Room # ' +result.room_id+' ['+result.firstname+' '+result.lastname+']');
            $('.viewFullname').text(result.firstname+' '+result.lastname);
            $('.viewDateOfBirth').text(result.date_of_birth_formatted);
            $('.viewMobileNumber').text(result.mobile_number);
            $('.viewEmail').text(result.email);
            $('.viewAddress').text(result.address);
            $('.viewService').text(result.service_name);
            $('.viewTherapist1').text(result.therapist_1_name);
            $('.viewTherapist2').text(result.therapist_2_name);
            $('.viewStartTime').text(result.start_date_formatted);
            $('.viewEndTime').text(result.end_date_formatted);

            if (result.end_date_formatted != '') {
                countdownModal(result.start_time_formatted, result.end_time);
            }

            $('.viewPlusTime').text(result.plus_time_formatted);
            $('.totalAmountViewFormatted').html('&#8369; '+result.amount);
        }
    });
    $('#view-sales-modal').modal('show');
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

$(document).on('change keyup input', '.filterClient', function () {
    var id = this.id;
    var val = $(this).val();

    var value;
    if (val.length > 0) {
        value = val;
    } else {
        value = 'NoData';
    }

    $.ajax({
        'url' : '/client-filter/'+value,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $("#suggesstion-box"+id).html('');
            $('#existing_user_id_'+id).val('');
            $('#first_name'+id).val('');
            $('#first_name'+id).prop( "disabled", false );
            $('#middle_name'+id).val('');
            $('#middle_name'+id).prop( "disabled", false );
            $('#last_name'+id).val('');
            $('#last_name'+id).prop( "disabled", false );
            $('#date_of_birth'+id).val('');
            $('#mobile_number'+id).val('');
            $('#email'+id).val('');
            $('#address'+id).val('');
            $('#client_type'+id).val('');
            $('#client_type'+id).prop( "disabled", false );

            $('.clientInfo'+id).addClass('hidden');
            $('.clientContact'+id).addClass('hidden');
            $('.clientAddress'+id).addClass('hidden');
            // $('.clientSearch'+id).addClass('hidden');
            $('.clientService'+id).addClass('hidden');
            $('.clientTime'+id).addClass('hidden');
            
            $('#first_name'+id).prop( "disabled", true );
            $('#middle_name'+id).prop( "disabled", true );
            $('#last_name'+id).prop( "disabled", true );
        },
        success: function(result){
            if (result.count > 0) {
                $("#suggesstion-box"+id).removeClass('hidden');
                $("#suggesstion-box"+id).html('');
                if (result.status) {
                    $.each(result.data , function(index, val) { 
                        $("#suggesstion-box"+id).append('<a class="list-group-item pointer filterValue" data-room="'+id+'" data-index="'+index+'" id="'+val+'">'+index+'</a>');
                    });
                }
            } else {
                alert('test')
                $('#client_type'+id).val('new');
                $('#client_type'+id).prop( "disabled", true );

                $("#suggesstion-box"+id).html('');
                $("#suggesstion-box"+id).addClass('hidden');

                $('.clientInfo'+id).removeClass('hidden');
                $('.clientContact'+id).removeClass('hidden');
                $('.clientAddress'+id).removeClass('hidden');
                $('.clientService'+id).removeClass('hidden');
                $('.clientTime'+id).removeClass('hidden');

                $('#first_name'+id).prop( "disabled", false );
                $('#middle_name'+id).prop( "disabled", false );
                $('#last_name'+id).prop( "disabled", false );
            }           
        }
    });
});

// $(document).on('click', '.filterValue', function () {
//     var id = this.id;
//     var index = $(this).data("index");
//     var room_id = $(this).data("room");

//     $('.clientFilter'+room_id).val(index);
//     $("#suggesstion-box"+room_id).html('');
//     $("#suggesstion-box"+room_id).addClass('hidden');

//     $.ajax({
//         'url' : '/client/'+id,
//         'type' : 'GET',
//         'data' : {},
//         'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         success: function(result){
//             if (result.client != '') {
//                 $('#existing_user_id_'+room_id).val(result.client.id);
//                 $('#first_name'+room_id).val(result.client.firstname);
//                 $('#first_name'+room_id).prop( "disabled", true );
//                 $('#middle_name'+room_id).val(result.client.middlename);
//                 $('#middle_name'+room_id).prop( "disabled", true );
//                 $('#last_name'+room_id).val(result.client.lastname);
//                 $('#last_name'+room_id).prop( "disabled", true );
//                 $('#date_of_birth'+room_id).val(result.client.date_of_birth);
//                 $('#mobile_number'+room_id).val(result.client.mobile_number);
//                 $('#email'+room_id).val(result.client.email);
//                 $('#address'+room_id).val(result.client.address);
//                 $('#client_type'+room_id).val('recurring');
//                 $('#client_type'+room_id).prop( "disabled", true );
//                 $('.clientInfo'+room_id).removeClass('hidden');
//                 $('.clientContact'+room_id).removeClass('hidden');
//                 $('.clientAddress'+room_id).removeClass('hidden');
//                 $('.clientService'+room_id).removeClass('hidden');
//                 $('.clientTime'+room_id).removeClass('hidden');
//             } else {
//                 // $('#search'+id).append('<option value="" disabled selected>-- No data found --</option>');
//             }
//         }
//     });
// });

function countdown(id, start_time, end_time)
{
    var countDownStartDate = new Date(start_time).getTime();
    var countDownEndDate = new Date(end_time).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        if (now >= countDownStartDate) {
            $("#countdown"+id).text('');
            var distance = countDownEndDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
            $("#countdown"+id).text(days + "d " + hours + "h "+ minutes + "m " + seconds + "s ");
    
            if (distance < 0) {
                clearInterval(x);
                loadRoom();
                getTotalSales($('#spa_id_val').val());
                getMasseurAvailability($('#spa_id_val').val());
                loadSales($('#spa_id_val').val());
                loadData($('#spa_id_val').val());
            }
        } else {
            $("#countdown"+id).text('Waiting...');
        }
    }, 1000);
}

function countdownModal(start_time, end_time)
{
    var countDownStartDate = new Date(start_time).getTime();
    var countDownEndDate = new Date(end_time).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        if (now >= countDownStartDate) {
            $(".viewRemainingTime").text('');
            var distance = countDownEndDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            $(".viewRemainingTime").text(days + "d " + hours + "h "+ minutes + "m " + seconds + "s ");
    
            if (distance < 0) {
                clearInterval(x);
                $(".viewRemainingTime").text('00:00');
            }
        } else {
            $(".viewRemainingTime").text('Waiting...');
        }
    }, 1000);
}

function countdownTherapist(id, start_time, end_time, total_seconds)
{
    var x = setInterval(function() {
        var progress_end_time = new Date(end_time);
        var progress_new_time = new Date();
        var progress_remaining_seconds = Math.floor(progress_end_time.getTime() - progress_new_time.getTime())/1000;
        var progress_seconds_parse = parseInt(progress_remaining_seconds);
        var progress_percentage = progress_seconds_parse / total_seconds * 100;
        var width_percentage = progress_percentage.toFixed(2);
        var percentage = 100 - width_percentage;
        var percentage_text = percentage.toFixed(2);

        if (percentage <= 100 && percentage > 0) {
            $('#countdownTherapistPercentage'+id).text('');
            $('#progressBarCalc'+id).css('width', percentage+'%');
            $('#countdownTherapistPercentage'+id).text(percentage_text+'%');
        } else {
            $('#countdownTherapistPercentage'+id).text('Waiting...');
        }
    }, 1000);
}

function loadData(id)
{
    $.ajax({
        'url' : '/transaction-data/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('.dailyAppointment').text(result.daily_appointment);
            $('.monthlyAppointment').text(result.monthly_appointment);
            $('.newClients').text(result.new_clients);
            $('.dailySales').html(result.total_sales);
        }
    });
}

$(document).on('click', '.transactionView', function () {
    var spa_id = $('#spa_id_val').val();
    loadTransactions(spa_id);
});

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

$(document).on('click', '.view-invoice', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();

    $.ajax({
        'url' : '/transaction-invoice/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                summaryInvoiceTable += '<th style="width:50%">Tax(1%):</th>';
                summaryInvoiceTable += '<td>&#8369; 50.00</td>';
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
});

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////


$('#addNewAppointment').on('click', function() {
    $('#add-new-appointment-modal').modal('show');

    $('.dataTabsAppointment').html('');
    $('.appointmentContent').remove();
    $('#summaryTab').removeClass('active');
    $('.tableSummaryAppointment').html('');
    $('.total_amount_appointment').html('&#8369;0');
    $('#totalAmountToPayAppointment').val(0);

    if (!$('.add-appointment-btn').hasClass('hidden')) {
        $('.add-appointment-btn').addClass('hidden');
        $('.process-appointment-btn').removeClass('hidden');
    }

    addAppointmentForm(1, 'active', 'yes', 'no');
});

$(document).on('click', '.addNewTabs', function () {
    var liCount = $('.appointmentTab').last().attr('id');
    if (liCount == 1) {
        $(".isCloseTab"+liCount).append('<button type="button" class="closeTabs pointer" id="'+liCount+'">×</button></div>');
    }

    var id = parseInt(liCount) + 1;
    var cur_val = $('#guest_ids_val').val();
    $('#guest_ids_val').val(cur_val + "," + id);

    addAppointmentForm(id, 'inactive', 'no', 'yes');
});

function addAppointmentForm(id, isActive, isTabFirst, isNewTab)
{
    $('.appointmentTabNav').removeClass('active');
    $('.appointmentContent').removeClass('active');
    $('.divCloseTab').removeClass('hidden');
    $('#summaryTab').removeClass('active');
    $('.summaryTabAppointmentLink').addClass('hidden');
    if ($('.process-appointment-btn').hasClass('hidden')) {
        $('.process-appointment-btn').removeClass('hidden');
        $('.add-appointment-btn').addClass('hidden');
    }

    var spa_id = $('#spa_id_val').val();

    var liCount = $('.appointmentTab').last().attr('id');
    if (liCount != '') {
        $(".isCloseTab"+liCount).append('<button type="button" class="closeTabs pointer" id="'+liCount+'">×</button>');
    }

    var disabled = '';
    if (isTabFirst == 'no') {
        disabled = 'disabled';
    }

    var active = '';
    if (isActive == 'active') {
        active = isActive;
    } else if (isNewTab == 'yes') {
        active = 'active';
        $('.appointmentNav'+liCount).removeClass('active');
        $('.tabAppointmentContent'+liCount).removeClass('active');
    }

    var tabs = '<li class="nav-item appointmentTab tabAppointmentTitle'+id+'" id="'+id+'">';
        tabs += '<a id="'+id+'" class="nav-link appointmentTabNav appointmentNav'+id+' '+active+'" href="#appointment'+id+'" data-id="'+id+'" data-toggle="tab">Guest # '+id+'</a>';
        tabs += '<div class="divCloseTab isCloseTab'+id+'">';
        if (isNewTab == 'yes') {
            tabs +='<button type="button" class="closeTabs pointer" id="'+id+'">×</button>';
        }
        tabs += '</div>';
    tabs += '</li>';
    if (isTabFirst == 'yes') {
        tabs += '<li class="nav-item summaryTabAppointmentLink hidden"><a class="nav-link appointmentTabNav appointmentTabNavSummary" href="#summaryTab" data-id="summary" data-toggle="tab">Summary</a></li>';
        tabs += '<li class="nav-item addNewTabs">';
            tabs += '<button type="button" class="btn btn-default">';
                tabs += '<i class="fas fa-plus-circle bg-success"></i>';
            tabs += '</button>';
        tabs += '</li>';

        $( tabs ).appendTo(".dataTabsAppointment");
    } else {
        $( tabs ).insertAfter('.appointmentTab:last');
    }
    
    var content = '<div class="tab-pane '+active+' appointmentContent tabAppointmentContent'+id+'" id="appointment'+id+'">';
        content +='<div class="form-group">';
            content +='<div class="row">';
                content +='<div class="col-md-12">';
                    content +='<label for="filter_client'+id+'">Client:</label><span class="isRequired">*</span>';
                    content +='<input type="text" class="form-control filterClientAppointment clientFilterAppointent'+id+'" id="'+id+'">';
                    content +='<div id="suggesstion-box-appointment'+id+'" class="list-group suggesstion-box-appointment hidden"></div>';
                    content +='<input type="hidden" class="form-control" id="existing_user_id_appointment_'+id+'">';
                content +='</div>';
            content +='</div>';
        content +='</div>';

        content +='<div class="form-group hidden clientInfoApp clientInfo_appointment'+id+'">';
            content +='<div class="row">';
                content +='<div class="col-md-4">';
                    content +='<label for="first_name_appointment'+id+'">First Name</label><span class="isRequired">*</span>';
                    content +='<input type="text" name="first_name_appointment'+id+'" id="first_name_appointment'+id+'" class="form-control">';
                    content +='<p class="text-danger hidden" id="error-first_name_appointment'+id+'"></p>';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="middle_name_appointment'+id+'">Middle Name</label>';
                    content +='<input type="text" name="middle_name_appointment'+id+'" id="middle_name_appointment'+id+'" class="form-control">';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="last_name_appointment'+id+'">Last Name</label><span class="isRequired">*</span>';
                    content +='<input type="text" name="last_name_appointment'+id+'" id="last_name_appointment'+id+'" class="form-control">';
                    content +='<p class="text-danger hidden" id="error-last_name_appointment'+id+'"></p>';
                content +='</div>';
            content +='</div>';
        content +='</div>';

        content +='<div class="form-group hidden clientContactApp clientContact_appointment'+id+'">';
            content +='<div class="row">';
                content +='<div class="col-md-4">';
                    content +='<label for="date_of_birth_appointment'+id+'">Date of Birth</label><span class="isRequired">*</span>';
                    content +='<input type="date" name="date_of_birth_appointment'+id+'" id="date_of_birth_appointment'+id+'" class="form-control">';
                    content +='<p class="text-danger hidden" id="error-date_of_birth_appointment'+id+'"></p>';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="mobile_number_appointment'+id+'">Mobile Number</label><span class="isRequired">*</span>';
                    content +='<input type="text" name="mobile_number_appointment'+id+'" id="mobile_number_appointment'+id+'" class="form-control">';
                    content +='<p class="text-danger hidden" id="error-mobile_number_appointment'+id+'"></p>';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="email_appointment'+id+'">Email</label>';
                    content +='<input type="email" name="email_appointment'+id+'" id="email_appointment'+id+'" class="form-control">';
                content +='</div>';
            content +='</div>';
        content +='</div>';

        content +='<div class="form-group hidden clientAddressApp clientAddress_appointment'+id+'">';
            content +='<div class="row">';
                content +='<div class="col-md-4">';
                    content +='<label for="client_type_appointment'+id+'">Client Type</label><span class="isRequired">*</span>';
                    content +='<input type="text" data-id="'+id+'" name="client_type_appointment'+id+'" id="client_type_appointment'+id+'" class="form-control">';
                content +='</div>';
                content +='<div class="col-md-8">';
                    content +='<label for="address_appointment1">Address</label>';
                    content +='<input type="text" name="address_appointment'+id+'" id="address_appointment'+id+'" class="form-control">';
                content +='</div>';
            content +='</div>';
        content +='</div>';

        content +='<div class="form-group hidden clientAppointmentApp clientAppointment_appointment'+id+'">';
            content +='<div class="row">';
                content +='<div class="col-md-6">';
                    content +='<label for="appointment_appointment'+id+'">Appointment Type</label><span class="isRequired">*</span>';
                    content +='<select '+disabled+' data-id="'+id+'" name="appointment_name_appointment'+id+'" id="appointment_name_appointment'+id+'" class="form-control appointment_name_appointment" style="width:100%;"></select>';
                    content +='<p class="text-danger hidden" id="error-appointment_name_appointment'+id+'"></p>';
                content +='</div>';
                content +='<div class="col-md-6 hidden socialMediaType socialMediaType'+id+'">';
                    content +='<label for="social_media_appointment'+id+'">Social Media Type</label>';
                    content +='<select '+disabled+' data-id="'+id+'" name="social_media_appointment'+id+'" id="social_media_appointment'+id+'" class="form-control social_media_appointment" style="width:100%;"></select>';
                    content +='<p class="text-danger hidden" id="error-social_media_appointment'+id+'"></p>';
                content +='</div>';
            content +='</div>';
        content +='</div>';

        content +='<div class="form-group hidden clientServiceApp clientService_appointment'+id+'">';
            content +='<div class="row defaultOptionalService">';
                content +='<div class="col-md-6">';
                    content +='<label for="service_name_appointment'+id+'">Services (Optional)</label>';
                    content +='<select data-id="'+id+'" name="service_name_appointment'+id+'" id="service_name_appointment'+id+'" class="form-control select-services-appointment" style="width:100%;"></select>';
                    content +='<input type="hidden" name="price_appointment'+id+'" id="price_appointment'+id+'" class="form-control" value="0">';
                    content +='<input type="hidden" name="service_name_appointment_id'+id+'" id="service_name_appointment_id'+id+'" class="form-control">';
                content +='</div>';
                content +='<div class="col-md-6">';
                    content +='<label for="start_time_appointment'+id+'">Start Time (Optional)</label>';
                    content +='<input name="start_time_appointment'+id+'" id="start_time_appointment'+id+'" class="form-control">';
                content +='</div>';
            content +='</div>';
            content +='<div class="row hidden requiredService requiredService'+id+'">';
                content +='<div class="col-md-4">';
                    content +='<label for="service_name_appointment_walkin'+id+'">Services</label><span class="isRequired">*</span>';
                    content +='<select data-id="'+id+'" name="service_name_appointment_walkin'+id+'" id="service_name_appointment_walkin'+id+'" class="form-control select-services-walkin-appointment" style="width:100%;"></select>';
                    content +='<input type="hidden" name="price_appointment_walkin'+id+'" id="price_appointment_walkin'+id+'" class="form-control" value="0">';
                    content +='<input type="hidden" name="appointment_app_services_id'+id+'" id="appointment_app_services_id'+id+'" class="form-control">';
                    content +='<p class="text-danger hidden" id="error-service_name_appointment_walkin'+id+'"></p>';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="plus_time_appointment'+id+'">Plus Time</label>';
                    content +='<select data-id="'+id+'" name="plus_time_appointment'+id+'" id="plus_time_appointment'+id+'" class="form-control select-appointment-plus_time" style="width:100%;"></select>';
                    content +='<input type="hidden" name="appointment_plus_time_price'+id+'" id="appointment_plus_time_price'+id+'" class="form-control" value="0">';
                    content +='<input type="hidden" name="appointment_plus_time_id'+id+'" id="appointment_plus_time_id'+id+'" class="form-control" value="0">';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="start_time_appointment_walkin'+id+'">Start Time</label><span class="isRequired">*</span>';
                    content +='<input name="start_time_appointment_walkin'+id+'" id="start_time_appointment_walkin'+id+'" class="form-control">';
                    content +='<input type="hidden" name="appointment_total_service_price'+id+'" id="appointment_total_service_price'+id+'" class="form-control" value="0">';
                    content +='<p class="text-danger hidden" id="error-start_time_appointment_walkin'+id+'"></p>';
                content +='</div>';
            content +='</div>';
            content +='<div class="row hidden requiredTherapist requiredTherapist'+id+'">';
                content +='<div class="col-md-4">';
                    content +='<label for="appointment_masseur1'+id+'">Masseur 1</label><span class="isRequired">*</span>';
                    content +='<select data-id="'+id+'" name="appointment_masseur1'+id+'" id="appointment_masseur1'+id+'" class="form-control select-appointment-masseur1" style="width:100%;"></select>';
                    content +='<input type="hidden" name="appointment_masseur1'+id+'_id" id="appointment_masseur1'+id+'_id" class="form-control">';
                    content +='<input type="hidden" name="appointment_masseur1'+id+'_id_prev" id="appointment_masseur1'+id+'_id_prev" class="form-control">';
                    content +='<div class="custom-control custom-checkbox">';
                        content +='<input disabled data-id="'+id+'" class="custom-control-input isAppointmentMultipleMasseur" type="checkbox" id="appointmentCustomCheckbox'+id+'" value="1">';
                        content +='<label for="appointmentCustomCheckbox'+id+'" class="custom-control-label">Is multiple Masseur ?</label>';
                    content +='</div>';
                    content +='<p class="text-danger hidden" id="error-appointment_masseur1'+id+'_id"></p>';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="appointment_masseur2'+id+'">Masseur 2</label>';
                    content +='<select data-id="'+id+'" name="appointment_masseur2'+id+'" id="appointment_masseur2'+id+'" class="form-control select-appointment-masseur2" style="width:100%;" disabled></select>';
                    content +='<input type="hidden" name="appointment_masseur2'+id+'_id" id="appointment_masseur2'+id+'_id" class="form-control">';
                    content +='<input type="hidden" name="appointment_masseur2'+id+'_id_prev" id="appointment_masseur2'+id+'_id_prev" class="form-control">';
                    content +='<input type="hidden" name=appointment_masseur2'+id+'_id_val" id="appointment_masseur2'+id+'_id_val" class="form-control">';
                content +='</div>';
                content +='<div class="col-md-4">';
                    content +='<label for="appointment_room'+id+'">Room #</label><span class="isRequired">*</span>';
                    content +='<select data-id="'+id+'" name="appointment_room'+id+'" id="appointment_room'+id+'" class="form-control select-appointment-room" style="width:100%;"></select>';
                    content +='<input type="hidden" class="form-control" id="appointment_room_id'+id+'">';
                    content +='<p class="text-danger hidden" id="error-appointment_room'+id+'"></p>';
                content +='</div>';
            content +='</div>';
        content +='</div>';
    content +='</div>';
    $( content ).appendTo(".tabFormAppointment");

    $('#start_time_appointment'+id).datetimepicker({
        footer: true, modal: true,
        format: 'dd mmmm yyyy hh:MM TT',
    });

    getAppointmentType(id);
    getServicesAppointment(spa_id, 'new', id);
    getServicesAppointment(spa_id, 'appointment', id);
    $('#start_time_appointment_walkin'+id).datetimepicker({
        footer: true, modal: true,
        format: 'dd mmmm yyyy hh:MM TT',
    });
}

$(document).on('click', '.appointmentTabNav', function () {
    var id = this.id;
    $('.appointmentContent').removeClass('active');
    $('.tabAppointmentContent'+id).addClass('active');
});

$(document).on('click', '.closeTabs', function () {
    var id = this.id;
    var count = $('ul.dataTabsAppointment li').length;

    var li = $(this).closest('li').prev('li');
    if (id == 1) {
        var li = $(this).closest('li').next('li');
    }
    
    var cur_val = $('#guest_ids_val').val();

    if ($('.appointmentNav'+id).hasClass('active')) {     
        if (count == 3) {
            alert('Unable to remove last Guest Tab.')
            return false;
        } else {
            $('a.appointmentNav'+li[0].id).addClass('active');
            $('div#appointment'+li[0].id).addClass('active');
        }
    }

    var remove = removeValue(cur_val, id);
    remove.split(",").sort().join(",")
    $('#guest_ids_val').val(remove);
    
    $('.tabAppointmentTitle'+id).remove();
    $('.tabAppointmentContent'+id).remove();
    checkTabs();
});

function checkTabs()
{
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;
    $('#appointment_name_appointment'+firstLiId).prop('disabled', false);
    $('#social_media_appointment'+firstLiId).prop('disabled', false);
    
    var count = $('ul.dataTabsAppointment li').length;
    if (count == 3) {
        $(".divCloseTab").html('');
    }

    $('.appointmentTabNavSummary').removeClass('active');
    $('#summaryTab').removeClass('active');

    $('.summaryTabAppointmentLink').addClass('hidden');
    if ($('.process-appointment-btn').hasClass('hidden')) {
        $('.process-appointment-btn').removeClass('hidden');
        $('.add-appointment-btn').addClass('hidden');
    }
}

function getServicesAppointment(spa_id, status, id)
{
    $.ajax({
        'url' : '/receptionist-service/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (status == 'new') {
                $('#service_name_appointment'+id).html('');
                $('#service_name_appointment'+id).append('<option></option>');
                $('#service_name_appointment'+id).select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
                $.each(result , function(index, val) { 
                    $('#service_name_appointment'+id).append('<option value="'+val+'">'+index+'</option>');
                });
            } else if (status == 'update') {
                $('#edit_app_services'+id).html('');
                $('#edit_app_services'+id).append('<option></option>');
                $('#edit_app_services'+id).select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
    
                $.each(result , function(edit_index, edit_val) { 
                    $('#edit_app_services'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
                });
            } else if (status == 'move') {
                $('#move_app_services'+id).html('');
                $('#move_app_services'+id).append('<option></option>');
                $('#move_app_services'+id).select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
    
                $.each(result , function(move_index, move_val) { 
                    $('#move_app_services'+id).append('<option value="'+move_val+'">'+move_index+'</option>');
                });
            } else if (status == 'appointment') {
                $('#service_name_appointment_walkin'+id).html('');
                $('#service_name_appointment_walkin'+id).append('<option></option>');
                $('#service_name_appointment_walkin'+id).select2({
                    placeholder: "Choose Services",
                    allowClear: true
                });
    
                $.each(result , function(move_index, move_val) { 
                    $('#service_name_appointment_walkin'+id).append('<option value="'+move_val+'">'+move_index+'</option>');
                });
            }
        }
    });
}

$(document).on('change keyup input', '.filterClientAppointment', function () {
    var id = this.id;
    var val = $(this).val();

    var value;
    if (val.length > 0) {
        value = val;
    } else {
        value = 'NoData';
    }

    $.ajax({
        'url' : '/client-filter/'+value,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $("#suggesstion-box-appointment"+id).html('');
            $('#existing_user_id_appointment_'+id).val('');
            $('#first_name_appointment'+id).val('');
            $('#first_name_appointment'+id).prop( "disabled", false );
            $('#middle_name_appointment'+id).val('');
            $('#middle_name_appointment'+id).prop( "disabled", false );
            $('#last_name_appointment'+id).val('');
            $('#last_name_appointment'+id).prop( "disabled", false );
            $('#date_of_birth_appointment'+id).val('');
            $('#mobile_number_appointment'+id).val('');
            $('#email_appointment'+id).val('');
            $('#address_appointment'+id).val('');
            $('#client_type_appointment'+id).val('');
            $('#client_type_appointment'+id).prop( "disabled", false );

            $('.clientInfo_appointment'+id).addClass('hidden');
            $('.clientContact_appointment'+id).addClass('hidden');
            $('.clientAddress_appointment'+id).addClass('hidden');
            $('.clientService_appointment'+id).addClass('hidden');
            $('.clientAppointment_appointment'+id).addClass('hidden');
            
            $('#first_name_appointment'+id).prop( "disabled", true );
            $('#middle_name_appointment'+id).prop( "disabled", true );
            $('#last_name_appointment'+id).prop( "disabled", true );
        },
        success: function(result){
            if (result.count > 0) {
                $("#suggesstion-box-appointment"+id).removeClass('hidden');
                if (result.status) {
                    $("#suggesstion-box-appointment"+id).html('');
                    $.each(result.data , function(index, val) { 
                        $("#suggesstion-box-appointment"+id).append('<a class="list-group-item pointer filterValue" data-id="'+id+'" data-index="'+index+'" id="'+val+'">'+index+'</a>');
                    });
                }
            } else {
                $('#client_type_appointment'+id).val('new');
                $('#client_type_appointment'+id).prop( "disabled", true );

                $("#suggesstion-box-appointment"+id).html('');
                $("#suggesstion-box-appointment"+id).addClass('hidden');

                $('.clientInfo_appointment'+id).removeClass('hidden');
                $('.clientContact_appointment'+id).removeClass('hidden');
                $('.clientAddress_appointment'+id).removeClass('hidden');
                $('.clientService_appointment'+id).removeClass('hidden');
                $('.clientAppointment_appointment'+id).removeClass('hidden');

                $('#first_name_appointment'+id).prop( "disabled", false );
                $('#middle_name_appointment'+id).prop( "disabled", false );
                $('#last_name_appointment'+id).prop( "disabled", false );

                getAppointmentTypeforNewGuest(id);
            }           
        }
    });
});

$(document).on('click', '.filterValue', function () {
    var id = this.id;
    var index = $(this).data("index");
    var data_id = $(this).data("id");

    $('.clientFilterAppointent'+data_id).val(index);
    $("#suggesstion-box-appointment"+data_id).html('');
    $("#suggesstion-box-appointment"+data_id).addClass('hidden');

    $.ajax({
        'url' : '/client/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            if (result.client != '') {
                $('#existing_user_id_appointment_'+data_id).val(result.client.id);
                $('#first_name_appointment'+data_id).val(result.client.firstname);
                $('#first_name_appointment'+data_id).prop( "disabled", true );
                $('#middle_name_appointment'+data_id).val(result.client.middlename);
                $('#middle_name_appointment'+data_id).prop( "disabled", true );
                $('#last_name_appointment'+data_id).val(result.client.lastname);
                $('#last_name_appointment'+data_id).prop( "disabled", true );
                $('#date_of_birth_appointment'+data_id).val(result.client.date_of_birth);
                $('#mobile_number_appointment'+data_id).val(result.client.mobile_number);
                $('#email_appointment'+data_id).val(result.client.email);
                $('#address_appointment'+data_id).val(result.client.address);
                $('#client_type_appointment'+data_id).val('recurring');
                $('#client_type_appointment'+data_id).prop( "disabled", true );

                $('.clientInfo_appointment'+data_id).removeClass('hidden');
                $('.clientContact_appointment'+data_id).removeClass('hidden');
                $('.clientAddress_appointment'+data_id).removeClass('hidden');
                $('.clientService_appointment'+data_id).removeClass('hidden');
                $('.clientAppointment_appointment'+data_id).removeClass('hidden');

                getAppointmentTypeforNewGuest(id);
            }
        }
    });
});

function getAppointmentTypeforNewGuest(id)
{
    var firstLi = $('ul.dataTabsAppointment li:first');
    var firstLiId = firstLi[0].id;

    var appointmentVal = '';
    if ($('#appointment_name_appointment'+firstLiId).val() != '') {
        appointmentVal = $('#appointment_name_appointment'+firstLiId).val();
    }

    var socialMediaVal = '';
    if ($('#social_media_appointment'+firstLiId).val() != '') {
        socialMediaVal = $('#social_media_appointment'+firstLiId).val();
    }

    $('#social_media_appointment'+id).val(socialMediaVal).change()
    $('#appointment_name_appointment'+id).val(appointmentVal).change()
}

$(document).on('change', '.select-services-appointment', function () {
    var data_id = $(this).data("id")
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;

    $('#price_appointment'+data_id).val('');
    $('#service_name_appointment_id'+data_id).val(selected_id);
    $.ajax({
        'url' : '/service-price/'+selected_id+'/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#price_appointment'+data_id).val(result);
            $('#price_appointment_up').val(result);
            $('.totalAmountUpdateAppointmentFormatted').html('&#8369; '+result);
        },
        error: function(xhr, status, error) {
            $('#price_appointment_up').val(0);
            $('.totalAmountUpdateAppointmentFormatted').html('&#8369; 0');
        }
    });
});

function getAppointmentType(id)
{
    $.ajax({
        'url' : '/appointment-type',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $("#appointment_name_appointment"+id).html('');
            $("#social_media_appointment"+id).html('');
        },
        success: function(result){
            $("#appointment_name_appointment"+id).append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.appointment_type , function(index_appointment, val_appointment) { 
                $("#appointment_name_appointment"+id).append('<option value="'+val_appointment+'">'+val_appointment+'</option>');
            });

            $("#social_media_appointment"+id).append('<option value="" disabled selected> -- Choose Here --</option>');
            $.each(result.social_media , function(index_social, val_social) { 
                $("#social_media_appointment"+id).append('<option value="'+val_social+'">'+val_social+'</option>');
            });
        }
    });
}

$(document).on('change', '.appointment_name_appointment', function () {
    var id = $(this).data("id");
    var val = $(this).val();
    var spa_id = $('#spa_id_val').val();

    $('.appointment_name_appointment').val(val);
    if (val == 'Social Media') {
        $('.socialMediaType').removeClass('hidden');

        if (!$('.requiredService').hasClass('hidden')) {
            $('.requiredService').addClass('hidden');
            $('.requiredTherapist').addClass('hidden');
            $('.defaultOptionalService').removeClass('hidden');
        }
    } else if (val == 'Walk-in') {
        $('.defaultOptionalService').addClass('hidden');
        $('.requiredService').removeClass('hidden');
        $('.requiredTherapist').removeClass('hidden');

        if (!$('.socialMediaType').hasClass('hidden')) {
            $('.socialMediaType').addClass('hidden');
            $('.social_media_appointment').val('');
        }

        getPlusTime('appointment', id);
        getRoomList('appointment', id);
        getTherapists(spa_id, 'appointment', id);
    } else {
        if (!$('.socialMediaType').hasClass('hidden')) {
            $('.socialMediaType').addClass('hidden');
            $('.social_media_appointment').val('');
        }

        if (!$('.requiredService').hasClass('hidden')) {
            $('.requiredService').addClass('hidden');
            $('.requiredTherapist').addClass('hidden');
            $('.defaultOptionalService').removeClass('hidden');
        }
    }
});

$(document).on('change', '.social_media_appointment', function () {
    var id = $(this).data("id");
    var val = $(this).val();
    
    $('.social_media_appointment').val(val);
});

$(document).on('change', '.select-services-walkin-appointment', function () {
    var id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;

    $('#appointment_app_services_id'+id).val(selected_id);
    $('#price_appointment_walkin'+id).val(0);
    var plus_time_val = $('#appointment_plus_time_price'+id).val();
    if (selected_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_id+'/'+spa_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#price_appointment_walkin'+id).val(result);
                var price = parseInt(result) + parseInt($('#appointment_plus_time_price'+id).val());
                $('#appointment_total_service_price'+id).val(price);
            }
        });
    } else {
        var price = parseInt($('#price_appointment_walkin'+id).val()) + parseInt($('#appointment_plus_time_price'+id).val());
        $('#appointment_total_service_price'+id).val(price);
    }

    triggerPlusAppointmentTime(id);
});

$(document).on('change', '.select-appointment-plus_time', function () {
    var id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    var value_services = $('#appointment_app_services_id'+id).val();

    $('#appointment_plus_time_id'+id).val(selected_id);
    $('#appointment_plus_time_price'+id).val(0);
    var price;
    var price_converted;
    var price_formatted;
    if (selected_id != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#appointment_plus_time_price'+id).val(result);
                price = parseInt(result) + parseInt($('#price_appointment_walkin'+id).val());
                $('#appointment_total_service_price'+id).val(price);
            }
        });
    } else {
        price = parseInt($('#appointment_plus_time_price'+id).val()) + parseInt($('#price_appointment_walkin'+id).val());
        $('#appointment_total_service_price'+id).val(price);
    }

});

function triggerPlusAppointmentTime(id)
{
    var spa_id = $('#spa_id_val').val();
    var value_plusTime = $('#appointment_plus_time_id'+id).val();
    var value_services = $('#appointment_app_services_id'+id).val();

    var price;
    if (value_plusTime != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+value_plusTime,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#appointment_plus_time_price'+id).val(result);
                price = parseInt(result) + parseInt($('#price_appointment_walkin'+id).val());
                $('#appointment_total_service_price'+id).val(price);
            }
        });
    } else {
        $('#appointment_plus_time_price'+id).val(0);
    }
}

$(document).on('click', '.isAppointmentMultipleMasseur', function () {
    var id = $(this).data("id");
    var therapist_2_val = $('#appointment_masseur2'+id+'_id').val();

    if ($('#appointmentCustomCheckbox'+id).is(':checked') == false) {
        const index = UnAvailableTherapist.indexOf(therapist_2_val);
        if (index > -1) {
            UnAvailableTherapist.splice(index, 1);
        }

        $('.select-appointment-masseur1').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $('.select-appointment-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });

        $('#appointment_masseur2'+id).attr('disabled',true);
        $('.select-appointment-masseur2').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $(".select-appointment-masseur2").select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        }).val('').trigger("change");
        $('#appointment_masseur2'+id+'_id').val('');
    } else {
        $('#appointment_masseur2'+id).attr('disabled',false);
    }
});

$(document).on('change', '.select-appointment-masseur1', function () {
    var data_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var id = selected[0].id;
    UnAvailableTherapist.push(id);

    var cur_val = $('#appointment_masseur1'+data_id+'_id').val();

    if (cur_val !== id) {
        $('.select-appointment-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-appointment-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-appointment-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-appointment-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#appointment_masseur1'+data_id+'_id').val(id);
    
        $('.select-appointment-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-appointment-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    }

    $('#appointmentCustomCheckbox'+data_id).prop('disabled', false);
});

$(document).on('change', '.select-appointment-masseur2', function () {
    var data_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var id = selected[0].id;

    UnAvailableTherapist.push(id);
    var cur_val = $('#appointment_masseur2'+data_id+'_id').val();

    if (cur_val !== id) {
        $('.select-appointment-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-appointment-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-appointment-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-appointment-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#appointment_masseur2'+data_id+'_id').val(id);
    
        $('.select-appointment-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-appointment-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    }
});

$(document).on('change', '.select-appointment-room', function () {
    var data_id = $(this).data("id");
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var id = selected[0].id;

    var cur_val = $('#appointment_room_id'+data_id).val();
    UnAvailableRoom.push(id);
    if (cur_val !== id) {
        $('.select-appointment-room').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-appointment-room').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#appointment_room_id'+data_id).val(id);
    
        $('.select-appointment-room').select2({
            placeholder: "Choose Room",
            allowClear: true
        });
    }
});

var appointment = [];
$('.process-appointment-btn').on('click', function() {
    $('.divCloseTab').addClass('hidden');
    var cur_val = $('#guest_ids_val').val();
    const data = cur_val.split(',');

    if (data.length > 0) {
        appointment = [];
        var total_amount = 0;
        $.each(data, function (key, value) {
            var value_client_type = $('#client_type_appointment'+value).val();

            var value_first_name = $('#first_name_appointment'+value).val();
            if (value_first_name.length < 1) {
                $('#error-first_name_appointment'+value).removeClass('hidden');
                $('#error-first_name_appointment'+value).text('First Name field is required!');
            } else {
                $('#error-first_name_appointment'+value).addClass('hidden');
                $('#error-first_name_appointment'+value).text('');
            }

            var value_middle_name = $('#middle_name_appointment'+value).val();

            var value_last_name = $('#last_name_appointment'+value).val();
            if (value_last_name.length < 1) {
                $('#error-last_name_appointment'+value).removeClass('hidden');
                $('#error-last_name_appointment'+value).text('Last Name field is required!');
            } else {
                $('#error-last_name_appointment'+value).addClass('hidden');
                $('#error-last_name_appointment'+value).text('');
            }

            var value_date_of_birth = $('#date_of_birth_appointment'+value).val();
            if (value_date_of_birth.length < 1) {
                $('#error-date_of_birth_appointment'+value).removeClass('hidden');
                $('#error-date_of_birth_appointment'+value).text('Date of Birth field is required!');
            } else {
                $('#error-date_of_birth_appointment'+value).addClass('hidden');
                $('#error-date_of_birth_appointment'+value).text('');
            }

            var value_mobile_number = $('#mobile_number_appointment'+value).val();
            if (value_mobile_number.length < 1) {
                $('#error-mobile_number_appointment'+value).removeClass('hidden');
                $('#error-mobile_number_appointment'+value).text('Mobile Number field is required!');
            } else {
                $('#error-mobile_number_appointment'+value).addClass('hidden');
                $('#error-mobile_number_appointment'+value).text('');
            }

            var value_email = $('#email_appointment'+value).val();
            var value_address = $('#address_appointment'+value).val();

            var existing_user_id = $('#existing_user_id_appointment_'+value).val();
  
            var value_appointment_type = '';
            if ($('#appointment_name_appointment'+value).val() != null) {
                value_appointment_type = $('#appointment_name_appointment'+value).val();

                if (!$('#error-appointment_name_appointment'+value).hasClass('hidden')) {
                    $('#error-appointment_name_appointment'+value).addClass('hidden');
                }

                $('#error-appointment_name_appointment'+value).text('');
            } else {
                $('#error-appointment_name_appointment'+value).removeClass('hidden');
                $('#error-appointment_name_appointment'+value).text('Appointment Type field is required!');
            }

            var value_social_type = '';
            var value_appointment_socials = value_appointment_type;
            if ($('#social_media_appointment'+value).val() != null) {
                value_social_type = $('#social_media_appointment'+value).val();
                value_appointment_socials = value_appointment_type+'<br />('+value_social_type+')';

                if (!$('#error-social_media_appointment'+value).hasClass('hidden')) {
                    $('#error-social_media_appointment'+value).addClass('hidden');
                }

                $('#error-social_media_appointment'+value).text('');
            } else {
                $('#error-social_media_appointment'+value).removeClass('hidden');
                $('#error-social_media_appointment'+value).text('Social Media Type field is required!');
            }

            var services = $('#service_name_appointment'+value).select2('data');
            var value_services = $('#service_name_appointment_id'+value).val();
            var value_start_time = $('#start_time_appointment'+value).val();
            var price = parseInt($('#price_appointment'+value).val());
            total_amount += parseInt($('#price_appointment'+value).val());
            var plus_time = 0;
            var plus_time_price = 0;
            var therapist_1 = '';
            var therapist_2 = '';
            var room = '';
            var price_converted = ReplaceNumberWithCommas(price);

            if ($('#appointment_name_appointment'+value).val() == 'Walk-in') {
                services = $('#service_name_appointment_walkin'+value).select2('data');
                value_services = $('#appointment_app_services_id'+value).val();
                value_start_time = $('#start_time_appointment_walkin'+value).val();
                price = parseInt($('#appointment_total_service_price'+value).val());
                plus_time = $('#appointment_plus_time_id'+value).val();
                plus_time_price = $('#appointment_plus_time_price'+value).val();
                therapist_1 = $('#appointment_masseur1'+value+'_id').val();
                therapist_2 = $('#appointment_masseur2'+value+'_id').val();
                room = $('#appointment_room_id'+value).val();
                total_amount += parseInt($('#appointment_total_service_price'+value).val());
                price_converted = ReplaceNumberWithCommas(price);
            }
            
            var price_formatted = '&#8369;'+0;
            if (price_converted > 0) {
                price_formatted ='&#8369;'+price_converted;
            }

            var value_services_name = services[0].text;

            if ($('#appointment_name_appointment'+value).val() == 'Walk-in') {
                if (value_services.length < 1) {
                    $('#error-service_name_appointment_walkin'+value).removeClass('hidden');
                    $('#error-service_name_appointment_walkin'+value).text('Services Type field is required!');
                }

                if (value_start_time.length < 1) {
                    $('#error-start_time_appointment_walkin'+value).removeClass('hidden');
                    $('#error-start_time_appointment_walkin'+value).text('Start time field is required!');
                }

                if (therapist_1.length < 1) {
                    $('#error-appointment_masseur1'+value+'_id').removeClass('hidden');
                    $('#error-appointment_masseur1'+value+'_id').text('Masseur 1 field is required!');
                }

                if (room.length < 1) {
                    $('#error-appointment_room'+value).removeClass('hidden');
                    $('#error-appointment_room'+value).text('Room number field is required!');
                }
            }

            if (
                value_client_type.length < 1 ||
                value_first_name.length < 1 ||
                value_last_name.length < 1 ||
                value_date_of_birth.length < 1 ||
                value_mobile_number.length < 1 ||
                value_appointment_type.length < 1
            ) {
                $('.tabAppointmentTitle'+value).addClass('error-border');
            } else {
                if (value_appointment_type == 'Social Media') {
                    if (value_social_type.length < 1) {
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                    } else {
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                } else if (value_appointment_type == 'Walk-in') {
                    if (
                        value_services.length < 1 &&
                        value_start_time.length < 1 &&
                        therapist_1.length < 1 &&
                        room.length < 1
                    ) {
                        $('.tabAppointmentTitle'+value).addClass('error-border');
                    } else {
                        $('.tabAppointmentTitle'+value).removeClass('error-border');
                    }
                } else {
                    $('.tabAppointmentTitle'+value).removeClass('error-border');
                }
            }

            if (
                value_client_type.length > 0 &&
                value_first_name.length > 0 &&
                value_last_name.length > 0 &&
                value_date_of_birth.length > 0 &&
                value_mobile_number.length > 0 &&
                value_appointment_type.length > 0
            ) {
                if (value_appointment_type == 'Social Media') {
                    if (value_social_type.length > 0) {
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    } else {
                        $('.summaryTabAppointmentLink').addClass('hidden');
                    }
                } else if (value_appointment_type == 'Walk-in') {
                    if (
                        value_services.length > 0 &&
                        value_start_time.length > 0 &&
                        therapist_1.length > 0 &&
                        room.length > 0
                    ) {
                        $('.summaryTabAppointmentLink').removeClass('hidden');
                    } else {
                        $('.summaryTabAppointmentLink').addClass('hidden');
                    }
                } else {
                    $('.summaryTabAppointmentLink').removeClass('hidden');
                }
            } else {
                $('.summaryTabAppointmentLink').addClass('hidden');
            }
            

            appointment.push({
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
                value_start_time: value_start_time,
                price: price,
                price_formatted: price_formatted,
                existing_user_id: existing_user_id,
                value_appointment_type: value_appointment_type,
                value_social_type: value_social_type,
                value_appointment_socials: value_appointment_socials,
                total_price: total_amount,
                plus_time: plus_time,
                therapist_1: therapist_1,
                therapist_2: therapist_2,
                room_id: room
            })
        });

        var converted_amount = 0;
        var getTotal_amount = 0;
        if (total_amount > 0) {
            converted_amount = ReplaceNumberWithCommas(total_amount);
            getTotal_amount = total_amount;
        }

        $('#totalAmountToPayAppointment').val(getTotal_amount);
        $('.total_amount_appointment').html('&#8369;'+converted_amount);
    }
});

$(document).on('click', '.appointmentTabNav', function () {
    var id = $(this).data("id");
    console.log(appointment)
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
            value_start_time,
            value_appointment_socials,
            price
        }) => [
            value_first_name+' '+value_last_name,
            value_services_name,
            value_start_time,
            value_appointment_socials,
            '&#8369;'+ ReplaceNumberWithCommas(price)
        ]);

        $('#summary-list').DataTable({
            data: dataSet,
            columns: [
              { title: 'Client' },
              { title: 'Service' },
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
});

$('.add-appointment-btn').on('click', function() {
    var data = appointment;
    var appointment_type = $('.appointment_name_appointment').val();
    console.log(appointment_type);
    var amount = $('#totalAmountToPayAppointment').val();
    var spa_id = $('#spa_id_val').val();

    var message = 'Are you sure you want to save the appointment?';
    var url = '/appointment-store/'+spa_id;
    if (appointment_type == 'Walk-in') {
        message = 'Are you sure you want to save the appointment as sales?';
        url = '/appointment-create-sales/'+spa_id+'/'+amount
    }

    swal.fire({
        title: message,
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
                'url' : url,
                'type' : 'POST',
                'data': {value: data},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#appointment-form').find('.add-appointment-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#appointment-form').trigger('reset');
                        $('.process-appointment-btn').removeClass('hidden');
                        $('.add-appointment-btn').addClass('hidden');

                        if (appointment_type == 'Walk-in') {
                            loadRoom();
                            getTotalSales(spa_id);
                        } else {
                            getAppointmentCount();
                            loadAppointments(spa_id);
                        }
                        getMasseurAvailability(spa_id);

                        loadData(spa_id);
                        swal.fire("Done!", result.message, "success");
                        $('#add-new-appointment-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#appointment-form').find('.add-appointment-btn').val('Save').attr('disabled',false);
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

function getAppointmentCount()
{
    var spa_id = $('#spa_id_val').val();
    $.ajax({
        'url' : '/appointment-count/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.countSelectedAppoitment').html('0');
        },
        success: function(result){
            $('.countSelectedAppoitment').html(result);
        }
    });
}

$(document).on('click', '.appointmentView', function () {
    var spa_id = $('#spa_id_val').val();
    loadAppointments(spa_id);
});

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

$(document).on('click', '.view-appointment-btn', function () {
    var id = this.id;

    $('#view-appointment-modal').modal('show');
    viewAppointment(id);
});

$(document).on('click', '.edit-appointment-btn', function () {
    var id = this.id;

    $('#update-appointment-modal').modal('show');
    viewAppointment(id);
});


function viewAppointment(id)
{
    $.ajax({
        'url' : '/appointment-show/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            //clear view fields
            $(".viewAppointmentTitle").html('');
            $(".viewAppointmentFullname").html('');
            $(".viewAppointmentDateOfBirth").html('');
            $(".viewAppointmentMobileNumber").html('');
            $(".viewAppointmentEmail").html('');
            $(".viewAppointmentAddress").html('');
            $(".viewAppointmentService").html('');
            $(".viewAppointmentStartTime").html('');
            $(".totalAmountViewAppointmentFormatted").html('');
            $(".viewAppointmentBatch").html('');
            $(".viewAppointmentType").html('');
            $(".viewAppointmentStatus").html('');

            //clear update fields
            $('.viewAppointmentUpdateTitle').html('');
            $('#edit_app_id').val('');
            $('#edit_app_client_id').val('');
            $('#edit_app_firstname').val('');
            $('#edit_app_middlename').val('');
            $('#edit_app_lastname').val('');
            $('#edit_app_date_of_birth').val('');
            $('#edit_app_mobile_number').val('');
            $('#edit_app_email').val('');
            $('#edit_app_address').val('');
            $('#appointment_name_appointmentup').val('');
            $("#social_media_appointmentup").val('');
            $('#edit_app_servicesup').val('');
            $('#totalAmountUpdateAppointmentFormatted').html('');
            $('#price_appointment_up').val(0);
            $('#start_time_appointment_up').val('');

            //clear move sales field
            $('.viewAppointmentMoveTitle').html('');
            $('#move_app_id').val('');
            $('#move_app_client_id').val('');
            $('#move_app_firstname').val('');
            $('#move_app_middlename').val('');
            $('#move_app_lastname').val('');
            $('#move_app_date_of_birth').val('');
            $('#move_app_mobile_number').val('');
            $('#move_app_email').val('');
            $('#move_app_address').val('');
            $('#move_app_appointment_type').val('');
            $("#social_media_appointmentmove").val('');
            $('#move_app_servicesmove').val('');
            $('#totalAmountMoveAppointmentFormatted').html('');
            $('#price_appointment_move').val(0);
            $('#start_time_appointment_move').val('');
            $('#move_app_services_id').val('');
            $('#move_plus_time_id').val('');
            $('#move_room_id').val('');
            $('#error-move_app_appointment_type').addClass('hidden');
            $('#error-move_app_appointment_type').html('');
            $('#error-move_app_social_media_appointment').addClass('hidden');
            $('#error-move_app_social_media_appointment').html('');
            $('#error-move_app_servicesmove').addClass('hidden');
            $('#error-move_app_servicesmove').html('');
            $('#error-start_time_appointment_move').addClass('hidden');
            $('#error-start_time_appointment_move').html('');
            $('#error-move_masseur1_id').addClass('hidden');
            $('#error-move_masseur1_id').html('');
            $('#error-move_room').addClass('hidden');
            $('#error-move_room').html('');
        },
        success: function(result){
            // view values
            $(".viewAppointmentTitle").html('View Appointment');
            $(".viewAppointmentFullname").html(result.client.firstname+' '+result.client.lastname);
            $(".viewAppointmentDateOfBirth").html(result.client.date_of_birth);
            $(".viewAppointmentMobileNumber").html(result.client.mobile_number);
            $(".viewAppointmentEmail").html(result.client.email);
            $(".viewAppointmentAddress").html(result.client.address);
            $(".viewAppointmentService").html(result.service_name);
            $(".viewAppointmentStartTime").html(result.start_time);
            $(".totalAmountViewAppointmentFormatted").html('&#8369; '+result.amount);
            $(".viewAppointmentBatch").html('Batch # '+result.batch);

            if (result.appointment_type == 'Social Media') {
                $(".viewAppointmentType").html(result.appointment_type+'<br />('+result.social_media_type+')');
            } else {
                $(".viewAppointmentType").html(result.appointment_type);
            }
            $(".viewAppointmentStatus").html(result.appointment_status);

            //update values
            $('.viewAppointmentUpdateTitle').html('Update Appointment');
            $('#edit_app_id').val(result.id);
            $('#edit_app_client_id').val(result.client.id);
            $('#edit_app_firstname').val(result.client.firstname);
            $('#edit_app_middlename').val(result.client.middlename);
            $('#edit_app_lastname').val(result.client.lastname);
            $('#edit_app_date_of_birth').val(result.client.date_of_birth);
            $('#edit_app_mobile_number').val(result.client.mobile_number);
            $('#edit_app_email').val(result.client.email);
            $('#edit_app_address').val(result.client.address);

            $("#appointment_name_appointmentup").val(result.appointment_type).change();
            if (result.appointment_type == 'Social Media') {
                $("#social_media_appointmentup").val(result.social_media_type).change();
            } else {
                $('.socialMedialUpdate').addClass('hidden');
                $("#social_media_appointmentup").val('').change();
            }

            $("#edit_app_servicesup").select2({
                placeholder: 'Choose Services',
                allowClear: true
            }).val(result.service_id).trigger("change");

            $('#price_appointment_up').val(result.amount);
            $('#start_time_appointment_up').val(result.start_time);
            $('.totalAmountUpdateAppointmentFormatted').html('&#8369; '+result.amount);

            //move sales values
            $('.viewAppointmentMoveTitle').html('Move Appointment to Sales');
            $('#move_app_id').val(result.id);
            $('#move_app_client_id').val(result.client.id);
            $('#move_app_firstname').val(result.client.firstname);
            $('#move_app_middlename').val(result.client.middlename);
            $('#move_app_lastname').val(result.client.lastname);
            $('#move_app_date_of_birth').val(result.client.date_of_birth);
            $('#move_app_mobile_number').val(result.client.mobile_number);
            $('#move_app_email').val(result.client.email);
            $('#move_app_address').val(result.client.address);

            if (result.appointment_type == 'Social Media') {
                $("#appointment_name_appointmentmove").val(result.appointment_type).change();
                $("#social_media_appointmentmove").val(result.social_media_type).change();
            } else {
                $("#appointment_name_appointmentmove").val(result.appointment_type).change();
                $('.socialMedialMove').addClass('hidden');
                $("#social_media_appointmentmove").val('').change();
            }

            $("#move_app_servicesmove").select2({
                placeholder: 'Choose Services',
                allowClear: true
            }).val(result.service_id).trigger("change");

            $('#move_app_services_id').val(result.service_id);
            $('#move_plus_time_id').val('');
            $('#price_appointment_move').val(result.amount);
            $('#start_time_appointment_move').val(result.start_time);
            $('.totalAmountMoveAppointmentFormatted').html('&#8369; '+result.amount);
        }
    });
}

$(document).on('change', '#appointment_name_appointmentup', function () {
    var val = $(this).val();
    
    if (val == 'Social Media') {
        $('.socialMedialUpdate').removeClass('hidden');
    } else {
        if (!$('.socialMedialUpdate').hasClass('hidden')) {
            $('.socialMedialUpdate').addClass('hidden');
            $('#social_media_appointmentup').val('');
        }
    }
});

$('.update-appointment-btn').on('click', function() {
    var spa_id = $('#spa_id_val').val();
    var id = $('#edit_app_id').val();
    var client_id = $('#edit_app_client_id').val();
    var firstname = $('#edit_app_firstname').val();
    var middlename = $('#edit_app_middlename').val();
    var lastname = $('#edit_app_lastname').val();
    var date_of_birth = $('#edit_app_date_of_birth').val();
    var mobile_number = $('#edit_app_mobile_number').val();
    var email = $('#edit_app_email').val();
    var address = $('#edit_app_address').val();
    var appointment_type = $('#appointment_name_appointmentup').val();
    var appointment_social = $('#social_media_appointmentup').val();
    var services = $('#edit_app_servicesup').select2('data');
    var value_services = services[0].id;
    var value_services_name = services[0].text;
    var price = $('#price_appointment_up').val();
    var start_time = $('#start_time_appointment_up').val();

    swal.fire({
        title: "Are you sure you want to update the appointment?",
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
                'url' : '/appointment-update/'+id,
                'type' : 'PUT',
                'data': {
                    id: id,
                    client_id: client_id,
                    firstname: firstname,
                    middlename: middlename,
                    lastname: lastname,
                    date_of_birth: date_of_birth,
                    mobile_number: mobile_number,
                    email: email,
                    address: address,
                    appointment_type: appointment_type,
                    appointment_social: appointment_social,
                    value_services: value_services,
                    value_services_name: value_services_name,
                    price: price,
                    start_time: start_time
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                    $('#update-appointment-form').find('.update-appointment-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        $('#update-appointment-form').trigger('reset');
                        getAppointmentCount();
                        loadAppointments(spa_id);
                        // loadRoom();
                        // loadSales(spa_id);
                        // getTotalSales(spa_id);
                        // getMasseurAvailability(spa_id);
                        // loadData(spa_id);
        
                        swal.fire("Done!", result.message, "success");
                        $('#update-appointment-modal').modal('hide');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
            
                    $('#update-appointment-form').find('.update-appointment-btn').val('Save').attr('disabled',false);
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

$(document).on('click', '.move-appointment-btn', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();

    $('#move-appointment-modal').modal('show');
    viewAppointment(id);
    getPlusTime('move', 0);
    getRoomList('move', 0);
    getTherapists(spa_id, 'move', 0);
});

$(document).on('change', '.select-services-move-appointment', function () {
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;

    $('#move_app_services_id').val(selected_id);
    $('#price_appointment_move').val(0);
    var plus_time_val = $('#move_plus_time_price').val();
    if (selected_id != '') {
        $.ajax({
            'url' : '/service-price/'+selected_id+'/'+spa_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#price_appointment_move').val(result);
                var price = parseInt(result) + parseInt($('#move_plus_time_price').val());
    
                var price_converted = ReplaceNumberWithCommas(price);
                var price_formatted ='&#8369; '+price_converted;
    
                $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
                $('#totalAmountMoveToPay').val(price);
            }
        });
    } else {
        var price = parseInt($('#price_appointment_move').val()) + parseInt($('#move_plus_time_price').val());
    
        var price_converted = ReplaceNumberWithCommas(price);
        var price_formatted ='&#8369; '+price_converted;

        $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
        $('#totalAmountMoveToPay').val(price);
    }

    triggerPlusMoveTime();
});

$(document).on('change', '.select-move-plus_time', function () {
    var spa_id = $('#spa_id_val').val();
    var selected = $(this).select2('data');
    var selected_id = selected[0].id;
    
    var value_services = $('#move_app_services_id').val();

    $('#move_plus_time_id').val(selected_id);
    $('#move_plus_time_price').val(0);
    var price;
    var price_converted;
    var price_formatted;
    if (selected_id != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+selected_id,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#move_plus_time_price').val(result);
                price = parseInt(result) + parseInt($('#price_appointment_move').val());
                price_converted = ReplaceNumberWithCommas(price);
                price_formatted ='&#8369; '+price_converted;

                $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
                $('#totalAmountMoveToPay').val(price);
            }
        });
    } else {
        price = parseInt($('#move_plus_time_price').val()) + parseInt($('#price_appointment_move').val());
        price_converted = ReplaceNumberWithCommas(price);
        price_formatted ='&#8369; '+price_converted;

        $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
        $('#totalAmountMoveToPay').val(price);
    }

});

function triggerPlusMoveTime()
{
    var spa_id = $('#spa_id_val').val();
    var value_plusTime = $('#move_plus_time_id').val();
    var value_services = $('#move_app_services_id').val();

    var price;
    var price_converted;
    var price_formatted;
    if (value_plusTime != '' && value_services != '') {
        $.ajax({
            'url' : '/service-plus-time-price/'+value_services+'/'+spa_id+'/'+value_plusTime,
            'type' : 'GET',
            'data' : {},
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result){
                $('#move_plus_time_price').val(result);
                price = parseInt(result) + parseInt($('#price_appointment_move').val());
                price_converted = ReplaceNumberWithCommas(price);
                price_formatted ='&#8369; '+price_converted;

                $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
                $('#totalAmountMoveToPay').val(price);
            }
        });
    } else {
        $('#move_plus_time_price').val(0);
        price = 0;
        price_converted = ReplaceNumberWithCommas(price);
        price_formatted ='&#8369; '+price_converted;
        
        $('.totalAmountMoveAppointmentFormatted').html(price_formatted);
        $('#totalAmountMoveToPay').val(price);
    }
}

$(document).on('click', '.isMoveMultipleMasseur', function () {
    var therapist_2_val = $('#move_masseur2_id').val();

    if ($('#moveCustomCheckbox').is(':checked') == false) {
        $('.select-move-masseur1').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $('.select-move-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });

        $('.select-move-masseur2').attr('disabled',true);
        $('.select-move-masseur2').children('option[value="'+therapist_2_val+'"]').prop('disabled', false);
        $(".select-move-masseur2").select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        }).val('').trigger("change");
        $('#move_masseur2_id').val('');
    } else {
        $('.select-move-masseur2').attr('disabled',false);
    }
});

$('.select-move-masseur1').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    var cur_val = $('#move_masseur1_id').val();

    if (cur_val !== id) {
        $('.select-move-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-move-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-move-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-move-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#move_masseur1_id').val(id);
    
        $('.select-move-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-move-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    }

    $('#moveCustomCheckbox').prop('disabled', false);
});

$('.select-move-masseur2').on("select2:selecting", function(e) {
    var id = e.params.args.data.id;
    var cur_val = $('#move_masseur2_id').val();

    if (cur_val !== id) {
        $('.select-move-masseur1').children('option[value="' + id + '"]').attr('disabled', true);
        $('.select-move-masseur2').children('option[value="' + id + '"]').attr('disabled', true);
    
        $('.select-move-masseur1').children('option[value="' + cur_val + '"]').attr('disabled', false);
        $('.select-move-masseur2').children('option[value="' + cur_val + '"]').attr('disabled', false);
    
        $('#move_masseur2_id').val(id);
    
        $('.select-move-masseur1').select2({
            placeholder: "Choose Masseur 1",
            allowClear: true
        });
        $('.select-move-masseur2').select2({
            placeholder: "Choose Masseur 2",
            allowClear: true
        });
    }
});

$(document).on('change', '.select-move-room', function () {
    var selected = $(this).select2('data');
    var selected_id = selected[0].text;

    $('#move_room_id').val(selected_id);
});

$('.move-sales-appointment-btn').on('click', function() {
    var spa_id = $('#spa_id_val').val();
    var id = $('#move_app_id').val();
    var client_id = $('#move_app_client_id').val();
    var firstname = $('#move_app_firstname').val();
    var middlename = $('#move_app_middlename').val();
    var lastname = $('#move_app_lastname').val();
    var date_of_birth = $('#move_app_date_of_birth').val();
    var mobile_number = $('#move_app_mobile_number').val();
    var email = $('#move_app_email').val();
    var address = $('#move_app_address').val();
    var appointment_type = $('#appointment_name_appointmentmove').val();

    var appointment_social = '';
    if (appointment_type == 'Social Media') {
        appointment_social = $('#move_app_social_media_appointment').val();
    }
    
    var value_services = '';
    var value_services_name = '';
    var services = '';
    if ($('#move_app_services_id').val().length > 0) {
        services = $('#edit_app_servicesup').select2('data');
        value_services = services[0].id;
        value_services_name = services[0].text;
    }
    
    var value_plus_time = '';
    var plus_time = '';
    if ($('#move_plus_time_id').val().length > 0) {
        plus_time = $('#edit_app_servicesup').select2('data');
        value_plus_time = services[0].id;
    }
    
    var therapist_1 = $('#move_masseur1').select2('data');
    var value_therapist_1_id = therapist_1[0].id;
    var therapist_2 = $('#move_masseur2').select2('data');
    var value_therapist_2_id = therapist_2[0].id;
    var price = $('#price_appointment_move').val();
    var total_price = $('#totalAmountMoveToPay').val();
    var start_time = $('#start_time_appointment_up').val();
    var value_room_id = $('#move_room_id').val();
 
    var valid = false;
    if (
        appointment_type.length > 0 &&
        value_services.length > 0 &&
        start_time.length > 0 &&
        value_therapist_1_id.length > 0 &&
        value_room_id.length > 0
    ) {
        if (appointment_type == 'Social Media') {
            if (appointment_social != '') {
                valid = true;
            } else {
                valid = false;
            }
        } else {
            valid = true;
        }
    } else {
        if (appointment_type.length < 1) {
            $('#error-move_app_appointment_type').removeClass('hidden');
            $('#error-move_app_appointment_type').text('Appointment Type field is required!');
        } else {
            if (appointment_social ==  '') {
                $('#error-move_app_social_media_appointment').removeClass('hidden');
                $('#error-move_app_social_media_appointment').text('Social Media Type field is required!');
            }
        }
    
        if (value_services.length < 1) {
            $('#error-move_app_servicesmove').removeClass('hidden');
            $('#error-move_app_servicesmove').text('Services field is required!');
        }
    
        if (start_time.length < 1) {
            $('#error-start_time_appointment_move').removeClass('hidden');
            $('#error-start_time_appointment_move').text('Start time field is required!');
        }
    
        if (value_therapist_1_id.length < 1) {
            $('#error-move_masseur1_id').removeClass('hidden');
            $('#error-move_masseur1_id').text('Masseur 1 field is required!');
        }
    
        if (value_room_id.length < 1) {
            $('#error-move_room').removeClass('hidden');
            $('#error-move_room').text('Room field is required!');
        }
    }
    
    if (valid) {
        swal.fire({
            title: "Are you sure you want to move the appointment to sales?",
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
                    'url' : '/appointment-sales',
                    'type' : 'POST',
                    'data': {
                        spa_id: spa_id,
                        appointment_id: id,
                        client_id: client_id,
                        firstname: firstname,
                        middlename: middlename,
                        lastname: lastname,
                        date_of_birth: date_of_birth,
                        mobile_number: mobile_number,
                        email: email,
                        address: address,
                        appointment_type: appointment_type,
                        appointment_social: appointment_social,
                        value_services: value_services,
                        value_services_name: value_services_name,
                        value_plus_time: value_plus_time,
                        therapist_1: value_therapist_1_id,
                        therapist_2: value_therapist_2_id,
                        price: price,
                        total_price: total_price,
                        start_time: start_time,
                        room_id: value_room_id
                    },
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: function () {
                        $('#move-appointment-form').find('.move-sales-appointment-btn').val('Saving ... ').attr('disabled',true);
                    },success: function (result) {
                        if(result.status) {
                            $('#move-appointment-form').trigger('reset');
                            getAppointmentCount();
                            loadAppointments(spa_id);
                            loadSales(spa_id);
                            loadRoom();
                            getTotalSales(spa_id);
                            getMasseurAvailability(spa_id);
                            // loadData(spa_id);
            
                            swal.fire("Done!", result.message, "success");
                            $('#move-appointment-modal').modal('hide');
                        } else {
                            swal.fire("Warning!", result.message, "warning");
                        }
                
                        $('#move-appointment-form').find('.move-sales-appointment-btn').val('Save').attr('disabled',false);
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
    }
});

$(document).on('click','.delete-appointment-btn',function(){
    var spa_id = $('#spa_id_val').val();
    $tr = $(this).closest('tr');
    var id = this.id;
    var name = $(this).data("name")
    let data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();

    swal.fire({
        title: "Are you sure you want to delete appointment of "+data[0]+"?",
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
                'url' : '/appointment-delete/'+id,
                'type' : 'DELETE',
                'data': {},
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (result) {
                    if(result.status) {
                        getAppointmentCount();
                        loadAppointments(spa_id);
        
                        swal.fire("Done!", result.message, "success");
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        } else {
            e.dismiss;
        }
    });
});

$(document).on('click', '.update-invoice', function () {
    var id = this.id;
    var spa_id = $('#spa_id_val').val();
    var invoice_no = $(this).data("invoice");
    var payment_method = $(this).data("payment");
    var account_number = $(this).data("account");
    var bank_name = $(this).data("bank");
    var status = $(this).data("status");

    $('.updateInvoiceTitle').html('Update '+invoice_no);
    $('#sales_invoice_id').val(id);
    $('#payment_method').val(payment_method).change();
    if (payment_method == 'bank') {
        if(!$('.payment_bank_name').hasClass('hidden')) {
            $('.payment_bank_name').removeClass('hidden');
        }

        $('#payment_bank_name').val(bank_name);
    } else {
        $('.payment_bank_name').addClass('hidden');
    }
    $('#payment_account_number').val(account_number);
    
    $('#payment_status').val(status).change();
    $('#update-invoice-modal').modal('show');
});

$(document).on('change', '#payment_method', function () {
    var val = $(this).val();

    if (val == 'bank') {
        $('.payment_bank_name').removeClass('hidden');
    } else {
        if(!$('.payment_bank_name').hasClass('hidden')) {
            $('.payment_bank_name').addClass('hidden');
        }
    }
});

$(document).on('click','.update-invoice-btn',function(){
    var spa_id = $('#spa_id_val').val();
    var id =  $('#sales_invoice_id').val();
    var payment_method = $('#payment_method').val();
    var payment_status = $('#payment_status').val();
    var payment_account_number = $('#payment_account_number').val();
    var  payment_bank_name = $('#payment_bank_name').val();

    swal.fire({
        title: "Are you sure you want to update Therapist?",
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
                'url' : '/sales-update/'+id,
                'type' : 'PUT',
                'data': {
                    payment_method: payment_method,
                    payment_status: payment_status,
                    payment_account_number: payment_account_number,
                    payment_bank_name: payment_bank_name
                },
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function () {
                  $('#invoice-update-form').find('.update-invoice-btn').val('Saving ... ').attr('disabled',true);
                },success: function (result) {
                    if(result.status) {
                        loadTransactions(spa_id);

                        swal.fire("Done!", result.message, "success");
                        $('#update-invoice-modal').modal('toggle');
                    } else {
                        swal.fire("Warning!", result.message, "warning");
                    }

                    $('#invoice-update-form').find('.update-invoice-btn').val('Save').attr('disabled',false);
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