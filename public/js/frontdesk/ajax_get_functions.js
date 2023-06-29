//Set DateTimePicker
function setDateTimePicker(fields, id)
{
    $('#'+fields+id).datetimepicker({
        footer: true, modal: true,
        format: 'dd mmmm yyyy hh:MM TT',
        use24hours: false 
    });
}
// Get Functions //
function getServicesAppointment(spa_id, id, fields)
{
    $.ajax({
        'url' : '/receptionist-service/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#'+fields+id).html('');
            $('#'+fields+id).append('<option></option>');
            $('#'+fields+id).select2({
                placeholder: "Choose Services",
                allowClear: true
            });
            $.each(result , function(index, val) { 
                $('#'+fields+id).append('<option value="'+val+'">'+index+'</option>');
            });
        }
    });
}

function getPlusTime(id, field)
{
    $.ajax({
        'url' : '/receptionist-plus-range/',
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(result){
            $('#'+field+id).html('');
            $('#'+field+id).append('<option></option>');
            $('#'+field+id).select2({
                placeholder: "Choose Plus Time",
                allowClear: true
            }); 
            $.each(result , function(index, val) { 
                $('#'+field+id).append('<option value="'+index+'">'+val+'</option>');
            });
        }
    });
}


function getRoomList(id, fields)
{
    // var numberOfRooms = $('#numberOfRooms').val();
    // $.ajax({
    //     'url' : '/receptionist-room-range/'+numberOfRooms,
    //     'type' : 'GET',
    //     'data' : {},
    //     'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     success: function(result){
    //         $('#'+fields+id).html('');
    //         $('#'+fields+id).append('<option></option>');
    //         $('#'+fields+id).select2({
    //             placeholder: "Choose Room",
    //             allowClear: true
    //         }); 

    //         $.each(result , function(appointment_index, appointment_val) { 
    //             $('#'+fields+id).append('<option value="'+appointment_val+'">'+appointment_val+'</option>');
    //         });

    //         $.each(UnAvailableRoom, function (key, value) {
    //             $('#'+fields+id).children('option[value="' + value + '"]').attr('disabled', true);
    //         });
    //     }
    // });
}

function loadRoom()
{
    var spa_id = $('#spa_id_val').val();

    UnAvailableRoom = [];
    $.ajax({
        'url' : '/receptionist-lists/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.displayRoomList').html('');
        },
        success: function(result){
            $('.countSelected').text(0);
            if (result.length > 3) {
                $('#room-availability').addClass('overflow');
            } else {
                $('#room-availability').removeClass('overflow');
            }

            getTotalSales($('#spa_id_val').val());
            getMasseurAvailability($('#spa_id_val').val());
            loadSales($('#spa_id_val').val());
            loadData($('#spa_id_val').val());
            $('.displayRoomList').html('');
            $.each(result , function(index, val) { 
                clearInterval(interValCountDown[val.room_id])
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
                    var interValCountDowns = setInterval(function() {
                        countdownInterval(val.room_id, val.data.start_time, val.data.end_time)
                    }, 1000)

                    interValCountDown[val.room_id] = interValCountDowns;

                }
            });
        }
    });
}

function getMasseurAvailability(spa_id)
{
    UnAvailableTherapist = [];
    $.ajax({
        'url' : '/transaction-masseur-availability/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('.availableMasseur').html('');
            clearInterval(interValTherpist)
            $.each(result, function (key, value) {
                var names;
                clearInterval(interValTherpist[value.id])
                if (value.data != '') {
                    UnAvailableTherapist.push(value.id);                   
                    var interValTherpists = setInterval(function() {
                        countdownTherapistInterval(value.id, value.data.start_time, value.data.end_time, value.data.total_seconds)
                    }, 1000)

                    interValTherpist[value.id] = interValTherpists;

                    names = value.firstname+' '+value.lastname+' <small class="font-weight-bold text-danger">[ Room # '+value.data.room_id+' ]</small>';
                } else {
                    names = value.firstname+' '+value.lastname;
                }

                var availableMasseur = '<span class="masseurName">'+names+'</span>';
                availableMasseur += '<div class="progress progress-xl">';
                    availableMasseur += '<div id="progressBarCalc'+value.id+'" class="progress-bar bg-info progress-bar-striped progress-bar-animated rounded-pill progressBarCalc" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>';
                    availableMasseur += '<span class="countdownTherapistPercentage" id="countdownTherapistPercentage'+value.id+'">Available</span>';
                availableMasseur += '</div>';

                $( availableMasseur ).appendTo(".availableMasseur");
            });
        }
    });
}

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

function getTherapists(spa_id, status, id)
{
    // $.ajax({
    //     'url' : '/receptionist-therapist/'+spa_id,
    //     'type' : 'GET',
    //     'data' : {},
    //     'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     success: function(result){
    //         if (status == 'new') {
    //             $('.select-therapist').html('');
    //             $('.select-therapist').append('<option></option>');
    //             $('.select-therapist').select2({
    //                 placeholder: "Choose Masseur 1",
    //                 allowClear: true
    //             }); 
    
    //             $('.select-multiple-therapist').html('');
    //             $('.select-multiple-therapist').append('<option></option>');
    //             $('.select-multiple-therapist').select2({
    //                 placeholder: "Choose Masseur 2",
    //                 allowClear: true
    //             }); 
    
    //             $.each(result , function(index, val) { 
    //                 $('.select-therapist').append('<option value="'+val+'">'+index+'</option>');
    //                 $('.select-multiple-therapist').append('<option value="'+val+'">'+index+'</option>');
    //             });
    //         } else if (status == 'update') {
    //             $('.select-edit-masseur1').html('');
    //             $('.select-edit-masseur1').append('<option></option>');
    //             $('.select-edit-masseur1').select2({
    //                 placeholder: "Choose Masseur 1",
    //                 allowClear: true
    //             }); 
    
    //             $('.select-edit-masseur2').html('');
    //             $('.select-edit-masseur2').append('<option></option>');
    //             $('.select-edit-masseur2').select2({
    //                 placeholder: "Choose Masseur 2",
    //                 allowClear: true
    //             }); 
    
    //             $.each(result , function(edit_index, edit_val) { 
    //                 $('.select-edit-masseur1').append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //                 $('.select-edit-masseur2').append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //             });
    //         } else if (status == 'move') {
    //             $('.select-move-masseur1').html('');
    //             $('.select-move-masseur1').append('<option></option>');
    //             $('.select-move-masseur1').select2({
    //                 placeholder: "Choose Masseur 1",
    //                 allowClear: true
    //             }); 
    
    //             $('.select-move-masseur2').html('');
    //             $('.select-move-masseur2').append('<option></option>');
    //             $('.select-move-masseur2').select2({
    //                 placeholder: "Choose Masseur 2",
    //                 allowClear: true
    //             }); 
    
    //             $.each(result , function(edit_index, edit_val) { 
    //                 $('.select-move-masseur1').append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //                 $('.select-move-masseur2').append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //             });
    //         } else if (status == 'appointment') {
    //             // $('#appointment_masseur1'+id).html('');
    //             // $('#appointment_masseur1'+id).append('<option></option>');
    //             // $('#appointment_masseur1'+id).select2({
    //             //     placeholder: "Choose Masseur 1",
    //             //     allowClear: true
    //             // }); 
    
    //             // $('#appointment_masseur2'+id).html('');
    //             // $('#appointment_masseur2'+id).append('<option></option>');
    //             // $('#appointment_masseur2'+id).select2({
    //             //     placeholder: "Choose Masseur 2",
    //             //     allowClear: true
    //             // }); 
    
    //             // $.each(result , function(edit_index, edit_val) { 
    //             //     $('#appointment_masseur1'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //             //     $('#appointment_masseur2'+id).append('<option value="'+edit_val+'">'+edit_index+'</option>');
    //             // });
    //         }

    //         if (UnAvailableTherapist.length > 0) {
    //             $.each(UnAvailableTherapist , function(un_index, un_val) { 
    //                 $('.select-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
    //                 $('.select-therapist').select2({
    //                     placeholder: "Choose Masseur 1",
    //                     allowClear: true
    //                 });
    //                 $('.select-multiple-therapist').children('option[value="' + un_val + '"]').attr('disabled', true);
    //                 $('.select-multiple-therapist').select2({
    //                     placeholder: "Choose Masseur 2",
    //                     allowClear: true
    //                 });

    //                 $('.select-edit-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
    //                 $('.select-edit-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

    //                 $('.select-edit-masseur1').select2({
    //                     placeholder: "Choose Masseur 1",
    //                     allowClear: true
    //                 });
    //                 $('.select-edit-masseur2').select2({
    //                     placeholder: "Choose Masseur 2",
    //                     allowClear: true
    //                 });

    //                 $('.select-move-masseur1').children('option[value="' + un_val + '"]').attr('disabled', true);
    //                 $('.select-move-masseur2').children('option[value="' + un_val + '"]').attr('disabled', true);

    //                 $('.select-move-masseur1').select2({
    //                     placeholder: "Choose Masseur 1",
    //                     allowClear: true
    //                 });
    //                 $('.select-move-masseur2').select2({
    //                     placeholder: "Choose Masseur 2",
    //                     allowClear: true
    //                 });

    //                 $('#appointment_masseur1'+id).children('option[value="' + un_val + '"]').attr('disabled', true);
    //                 $('#appointment_masseur2'+id).children('option[value="' + un_val + '"]').attr('disabled', true);

    //                 $('#appointment_masseur1'+id).select2({
    //                     placeholder: "Choose Masseur 1",
    //                     allowClear: true
    //                 });
    //                 $('#appointment_masseur2'+id).select2({
    //                     placeholder: "Choose Masseur 2",
    //                     allowClear: true
    //                 });
    //             });
    //         }
    //     }
    // });
}

function viewReservedRoom(id)
{
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
            $('#daily_sales_amount').val(result.sales);
        }
    });
}

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

function getSalesInfo(id, spa_id)
{
    getServicesAppointment(spa_id, '', 'edit_services')
    // getServices(spa_id, 'update');
    getTherapists(spa_id, 'update', 0);
    getPlusTime('', 'edit_plus_time');
    getRoomList('', 'edit_room');

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
            $('#edit_start_time').val(result.start_time);
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

            if (result.plus_time > 0) {
                $(".select-edit-plus_time").select2({
                    placeholder: "Choose Plus Time",
                    allowClear: true
                }).val(result.plus_time).trigger("change");
            }
            $('#edit_plus_time_price').val(0);

            $(".select-edit-room").select2({
                placeholder: "Choose Room",
                allowClear: true
            }).val(result.room_id).trigger("change");

            $('#edit_room_val').val(result.room_id);
            
            $.each(UnAvailableRoom, function (key, value) {
                $('.select-edit-room').children('option[value="' + value + '"]').attr('disabled', true);
            });
        }
    });

    $('#update-sales-modal').modal('show');
}

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
            $('#edit_app_client_type').val('');
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
            $(".viewAppointmentFullname").html(result.firstname+' '+result.lastname);
            $(".viewAppointmentDateOfBirth").html(result.date_of_birth);
            $(".viewAppointmentMobileNumber").html(result.mobile_number);
            $(".viewAppointmentEmail").html(result.email);
            $(".viewAppointmentAddress").html(result.address);
            $(".viewAppointmentService").html(result.service_name);
            $(".viewAppointmentStartTime").html(result.start_time_formatted);
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
            if (result.client_id != '') {
                $('#edit_app_firstname').prop('disabled', true);
                $('#edit_app_middlename').prop('disabled', true);
                $('#edit_app_lastname').prop('disabled', true);
            } else {
                $('#edit_app_firstname').prop("disabled", false);
                $('#edit_app_middlename').prop('disabled', false);
                $('#edit_app_lastname').prop('disabled', false);
            }
            
            $('#edit_app_id').val(result.id);
            $('#edit_app_client_id').val(result.client_id);
            $('#edit_app_firstname').val(result.firstname);
            $('#edit_app_middlename').val(result.middlename);
            $('#edit_app_lastname').val(result.lastname);
            $('#edit_app_date_of_birth').val(result.date_of_birth);
            $('#edit_app_mobile_number').val(result.mobile_number);
            $('#edit_app_email').val(result.email);
            $('#edit_app_address').val(result.address);
            $('#edit_app_client_type').val(result.client_type);

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
            $('#move_app_client_id').val(result.client_id);
            $('#move_app_firstname').val(result.firstname);
            $('#move_app_middlename').val(result.middlename);
            $('#move_app_lastname').val(result.lastname);
            $('#move_app_date_of_birth').val(result.date_of_birth);
            $('#move_app_mobile_number').val(result.mobile_number);
            $('#move_app_email').val(result.email);
            $('#move_app_address').val(result.address);

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

function getUpcomingGuest(spa_id)
{
    UnAvailableGuest = [];
    $.ajax({
        'url' : '/appointment-upcoming/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('.upcomingGuest').html('');
        },
        success: function(result){
            $.each(result, function (key, value) {
                clearInterval(interValUpcoming[value.id])
                UnAvailableGuest.push(value.id);
                var interValUpcomings = setInterval(function() {
                    countdownUpcomingInterval(value.id, value.created_at, value.start_time, value.total_seconds)
                }, 1000)

                interValUpcoming[value.id] = interValUpcomings;
                var names = value.fullname+' <small class="font-weight-bold text-danger">[ Mobile # '+value.mobile_number+' ]</small>';
                var upcomingGuest = '<span class="masseurName">'+names+'</span>';

                upcomingGuest += '<div class="progress progress-xl">';
                    upcomingGuest += '<div id="progressBarCalcUpcoming'+value.id+'" class="progress-bar bg-danger progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>';
                    upcomingGuest += '<span id="countdownUpcomingPercentage'+value.id+'">Upcoming</span>';
                upcomingGuest += '</div>';

                $( upcomingGuest ).appendTo(".upcomingGuest");
            });
        }
    });
}

function therapistTransactionCount(spa_id, date)
{
    $.ajax({
        'url' : '/therapists-transaction-count/'+spa_id+'/'+date,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            // console.log(result);
        }
    });
}

function getPosShift(spa_id)
{
    $.ajax({
        'url' : '/pos-get-shift/'+spa_id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {
            $('#start_shit_id').val('');
            $('.shiftMessage').html('');
        },
        success: function(result){
            if (result.status) {
                if (result.shift_today) {
                    if (result.end_shift) {
                        $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                        $('#start-shift-modal').modal('show');
                        $('#start-shift-form').find('.btnStartShift').text('Click here to start your shift again');
                    } else {
                        // Confirm money on hand
                        if (!result.money_confirm) {
                            $('#money-on-hand-modal').modal('show');
                        }
                    }

                    if (!$('.btnEndShift').hasClass('hidden')) {
                        $('.btnEndShift').addClass('hidden');
                    }
                } else {
                    if (!result.end_shift) {
                        $('.shiftMessage').html('Warning!! System noticed that you forgot to End your shift. Please click the button below to end your shift.');
                        $('.viewEndShiftReport').addClass('hidden');
                        $('#start-shift-modal').modal('show');
                        $('.btnStartShift').addClass('hidden');
                        $('.btnEndShift').removeClass('hidden');
                        $('#start-shift-form').find('.btnEndShift').text('Click here to End your shift');
                    } else {
                        // New Shift
                        $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                        $('.btnEndShift').addClass('hidden');

                        if ($('.btnStartShift').hasClass('hidden')) {
                            $('.btnStartShift').removeClass('hidden');
                        }
                        
                        $('.viewEndShiftReport').removeClass('hidden');
                        $('#start_shit_id').val('');
                        $('#start-shift-modal').modal('show');
                        $('#start-shift-form').find('.btnStartShift').text('Click here to start your new shift');
                    }
                }

                $('#start_shit_id').val(result.data.id);
            } else {
                //First Time Log Shift
                $('.shiftMessage').html('To proceed with the POS, kindly click the button below.');
                $('#start_shit_id').val('');
                $('.viewEndShiftReport').addClass('hidden');
                $('#start-shift-form').find('.btnStartShift').text('Click here to start your new shift');
                $('#start-shift-modal').modal('show');
            }
        }
    });
}

function getResponses(id)
{
    $('.viewBadgeCount').text('');
    $.ajax({
        'url' : '/appointment-response/'+id,
        'type' : 'GET',
        'data' : {},
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){

        }
    });
}

function getPosApi(spa_id)
{
    $.ajax({
        'url' : '/pos-api/'+spa_id,
        'type' : 'GET',
        'data' : {
            'date': ''
        },
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            console.log(result);
        }
    });
}

function getPosTherapistApi(spa_id, dateTime)
{
    $.ajax({
        'url' : '/pos-api-therapist-list/'+spa_id,
        'type' : 'GET',
        'data' : {
            'date': dateTime
        },
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('.select-appointment-masseur1').html('');
            $('.select-appointment-masseur1').append('<option></option>');
            $('.select-appointment-masseur1').select2({
                placeholder: "Choose Masseur 1",
                allowClear: true
            }); 

            $('.select-appointment-masseur2').html('');
            $('.select-appointment-masseur2').append('<option></option>');
            $('.select-appointment-masseur2').select2({
                placeholder: "Choose Masseur 2",
                allowClear: true
            }); 

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

            $.each(result , function(index, val) {                
                $('.select-appointment-masseur1').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                $('.select-appointment-masseur2').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');

                $('.select-edit-masseur1').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                $('.select-edit-masseur2').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');

                $('.select-move-masseur1').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');
                $('.select-move-masseur2').append('<option value="'+val.therapist_id+'">'+val.fullname+'</option>');

                if (val.availability == 'yes') {
                    $('.select-appointment-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                    $('.select-appointment-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);

                    $('.select-edit-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                    $('.select-edit-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);

                    $('.select-move-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                    $('.select-move-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', false);
                } else {
                    $('.select-appointment-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('.select-appointment-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);

                    $('.select-edit-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('.select-edit-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);

                    $('.select-move-masseur1').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                    $('.select-move-masseur2').children('option[value="' + val.therapist_id + '"]').attr('disabled', true);
                }
                
                $('.select-appointment-masseur1').select2({
                    placeholder: "Choose Masseur 1",
                    allowClear: true
                });

                $('.select-appointment-masseur2').select2({
                    placeholder: "Choose Masseur 2",
                    allowClear: true
                });
            });
        }
    });
}

function getPosRoomApi(spa_id, dateTime)
{
    $.ajax({
        'url' : '/pos-api-room-list/'+spa_id,
        'type' : 'GET',
        'data' : {
            'date': dateTime
        },
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend: function () {

        },
        success: function(result){
            $('.select-appointment-room').html('');
            $('.select-appointment-room').append('<option></option>');
            $('.select-appointment-room').select2({
                placeholder: "Choose Room",
                allowClear: true
            }); 

            $('.select-edit-room').html('');
            $('.select-edit-room').append('<option></option>');
            $('.select-edit-room').select2({
                placeholder: "Choose Room",
                allowClear: true
            }); 

            $('.select-move-room').html('');
            $('.select-move-room').append('<option></option>');
            $('.select-move-room').select2({
                placeholder: "Choose Room",
                allowClear: true
            }); 

            $.each(result , function(index, val) { 
                $('.select-appointment-room').append('<option value="'+val.room_id+'">'+val.room_id+'</option>');

                $('.select-edit-room').append('<option value="'+val.room_id+'">'+val.room_id+'</option>');

                $('.select-move-room').append('<option value="'+val.room_id+'">'+val.room_id+'</option>');

                if (val.is_available == 'yes') {
                    $('.select-appointment-room').children('option[value="' + val.room_id + '"]').attr('disabled', false);

                    $('.select-edit-room').children('option[value="' + val.room_id + '"]').attr('disabled', false);

                    $('.select-move-room').children('option[value="' + val.room_id + '"]').attr('disabled', false);
                } else {
                    $('.select-appointment-room').children('option[value="' + val.room_id + '"]').attr('disabled', true);

                    $('.select-edit-room').children('option[value="' + val.room_id + '"]').attr('disabled', true);

                    $('.select-move-room').children('option[value="' + val.room_id + '"]').attr('disabled', true);
                }
                
                $('.select-appointment-room').select2({
                    placeholder: "Choose Room",
                    allowClear: true
                });
            });
        }
    });
}