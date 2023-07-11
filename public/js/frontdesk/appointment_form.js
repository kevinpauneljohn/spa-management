// function createAppointmentForm(id, isActive, isTabFirst, isNewTab)
// {
//     $('.appointmentTabNav').removeClass('active');
//     $('.appointmentContent').removeClass('active');
//     $('.divCloseTab').removeClass('hidden');
//     $('#summaryTab').removeClass('active');
//     $('.summaryTabAppointmentLink').addClass('hidden');
//     if ($('.process-appointment-btn').hasClass('hidden')) {
//         $('.process-appointment-btn').removeClass('hidden');
//         $('.add-appointment-btn').addClass('hidden');
//     }

//     var spa_id = $('#spa_id_val').val();

//     var liCount = $('.appointmentTab').last().attr('id');
//     if (liCount != '') {
//         $(".isCloseTab"+liCount).append('<button type="button" class="closeTabs pointer" id="'+liCount+'">×</button>');
//     }

//     var disabled = '';
//     if (isTabFirst == 'no') {
//         disabled = 'disabled';
//     }

//     var active = '';
//     if (isActive == 'active') {
//         active = isActive;
//     } else if (isNewTab == 'yes') {
//         active = 'active';
//         $('.appointmentNav'+liCount).removeClass('active');
//         $('.tabAppointmentContent'+liCount).removeClass('active');
//     }

//     var tabs = '<li class="nav-item appointmentTab tabAppointmentTitle'+id+'" id="'+id+'">';
//         tabs += '<a id="'+id+'" class="nav-link appointmentTabNav appointmentNav'+id+' '+active+'" href="#appointment'+id+'" data-id="'+id+'" data-toggle="tab">Guest # '+id+'</a>';
//         tabs += '<div class="divCloseTab isCloseTab'+id+'">';
//         if (isNewTab == 'yes') {
//             tabs +='<button type="button" class="closeTabs pointer" id="'+id+'">×</button>';
//         }
//         tabs += '</div>';
//     tabs += '</li>';
//     if (isTabFirst == 'yes') {
//         tabs += '<li class="nav-item summaryTabAppointmentLink hidden"><a class="nav-link appointmentTabNav appointmentTabNavSummary" href="#summaryTab" data-id="summary" data-toggle="tab">Summary</a></li>';
//         tabs += '<li class="nav-item addNewTabs">';
//             tabs += '<button type="button" class="btn btn-default">';
//                 tabs += '<i class="fas fa-plus-circle bg-success"></i>';
//             tabs += '</button>';
//         tabs += '</li>';

//         $( tabs ).appendTo(".dataTabsAppointment");
//     } else {
//         $( tabs ).insertAfter('.appointmentTab:last');
//     }
    
//     var content = '<div class="tab-pane '+active+' appointmentContent tabAppointmentContent'+id+'" id="appointment'+id+'">';
//         content +='<div class="form-group">';
//             content +='<div class="row">';
//                 content +='<div class="col-md-12">';
//                     content +='<label for="filter_client'+id+'">Client:</label><span class="isRequired">*</span>';
//                     content +='<input type="text" class="form-control filterClientAppointment clientFilterAppointent'+id+'" id="'+id+'" autoComplete="none" placeholder="Search existing client here">';
//                     content +='<div id="suggesstion-box-appointment'+id+'" class="list-group suggesstion-box-appointment hidden"></div>';
//                     content +='<input type="hidden" class="form-control" id="existing_user_id_appointment_'+id+'">';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';

//         content +='<div class="form-group hidden clientInfoApp clientInfo_appointment'+id+'">';
//             content +='<div class="row">';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="first_name_appointment'+id+'">First Name</label><span class="isRequired">*</span>';
//                     content +='<input type="text" name="first_name_appointment'+id+'" id="first_name_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="middle_name_appointment'+id+'">Middle Name</label>';
//                     content +='<input type="text" name="middle_name_appointment'+id+'" id="middle_name_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="last_name_appointment'+id+'">Last Name</label><span class="isRequired">*</span>';
//                     content +='<input type="text" name="last_name_appointment'+id+'" id="last_name_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';

//         content +='<div class="form-group hidden clientContactApp clientContact_appointment'+id+'">';
//             content +='<div class="row">';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="date_of_birth_appointment'+id+'">Date of Birth</label>';
//                     content +='<input type="date" name="date_of_birth_appointment'+id+'" id="date_of_birth_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="mobile_number_appointment'+id+'">Mobile Number</label><span class="isRequired">*</span>';
//                     content +='<input type="text" name="mobile_number_appointment'+id+'" id="mobile_number_appointment'+id+'" class="form-control" maxlength="10">';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="email_appointment'+id+'">Email</label>';
//                     content +='<input type="email" name="email_appointment'+id+'" id="email_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';

//         content +='<div class="form-group hidden clientAddressApp clientAddress_appointment'+id+'">';
//             content +='<div class="row">';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="client_type_appointment'+id+'">Client Type</label><span class="isRequired">*</span>';
//                     content +='<input type="text" data-id="'+id+'" name="client_type_appointment'+id+'" id="client_type_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-8">';
//                     content +='<label for="address_appointment1">Address</label>';
//                     content +='<input type="text" name="address_appointment'+id+'" id="address_appointment'+id+'" class="form-control">';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';

//         content +='<div class="form-group hidden clientAppointmentApp clientAppointment_appointment'+id+'">';
//             content +='<div class="row">';
//                 content +='<div class="col-md-6">';
//                     content +='<label for="appointment_appointment'+id+'">Appointment Type</label><span class="isRequired">*</span>';
//                     content +='<select '+disabled+' data-id="'+id+'" name="appointment_name_appointment'+id+'" id="appointment_name_appointment'+id+'" class="form-control appointment_name_appointment" style="width:100%;"></select>';
//                 content +='</div>';
//                 content +='<div class="col-md-6 hidden socialMediaType socialMediaType'+id+'">';
//                     content +='<label for="social_media_appointment'+id+'">Social Media Type</label>';
//                     content +='<select '+disabled+' data-id="'+id+'" name="social_media_appointment'+id+'" id="social_media_appointment'+id+'" class="form-control social_media_appointment" style="width:100%;"></select>';
//                 content +='</div>';
//             content +='</div>';
//             content +='<div class="row hidden walkInOptions">';
//                 content +='<div class="col-md-6">';
//                     content +='<div class="form-check form-check-inline">';
//                         content +='<input data-id="'+id+'" data-value="reserved_now" class="form-check-input reserveNow reserveOption" type="checkbox" id="reservenow'+id+'" value="reserved_now" '+disabled+'>';
//                         content +='<label class="form-check-label" for="reservenow">Reserve now</label>';
//                     content +='</div>';
//                     content +='<div class="form-check form-check-inline">';
//                         content +='<input data-id="'+id+'" data-value="reserved_later" class="form-check-input reserveLater reserveOption" type="checkbox" id="reservelater'+id+'" value="reserved_later" '+disabled+'>';
//                         content +='<label class="form-check-label" for="reservelater">Reserve later</label>';
//                     content +='</div>';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';

//         content +='<div class="form-group hidden clientServiceApp clientService_appointment'+id+'">';
//             content +='<div class="row defaultOptionalService">';
//                 content +='<div class="col-md-6">';
//                     content +='<label for="start_time_appointment'+id+'">Start Time</label><span class="isRequired">*</span>';
//                     content +='<input '+disabled+' class="form-control start_time_appointment" type="datetime-local" name="start_time_appointment'+id+'" id="start_time_appointment'+id+'" min="">';
//                 content +='</div>';
//             content +='</div>';
//             content +='<div class="row hidden requiredService requiredService'+id+'">';
//                 content +='<div class="col-md-4 walkInStartTimeDiv">';
//                     content +='<label for="start_time_appointment_walkin'+id+'">Start Time</label><span class="isRequired">*</span>';
//                     content += '<input '+disabled+' data-id="'+id+'" type="datetime-local" name="start_time_appointment_walkin'+id+'" id="start_time_appointment_walkin'+id+'" class="form-control start_time_appointment_walkin" min="">';
//                     content +='<input type="hidden" name="price_appointment_walkin'+id+'" id="price_appointment_walkin'+id+'" class="form-control" value="0">';
//                     content +='<input type="hidden" name="appointment_app_services_id'+id+'" id="appointment_app_services_id'+id+'" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-4 hidden walkInHiddenDiv">';
//                     content +='<label for="service_name_appointment_walkin'+id+'">Services</label><span class="isRequired">*</span>';
//                     content +='<select data-select="appointment" data-id="'+id+'" name="service_name_appointment_walkin'+id+'" id="service_name_appointment_walkin'+id+'" class="form-control select-services-walkin-appointment" style="width:100%;"></select>';
//                     content +='<input type="hidden" name="appointment_plus_time_price'+id+'" id="appointment_plus_time_price'+id+'" class="form-control" value="0">';
//                     content +='<input type="hidden" name="appointment_plus_time_id'+id+'" id="appointment_plus_time_id'+id+'" class="form-control" value="0">';
//                 content +='</div>';
//                 content +='<div class="col-md-4 hidden walkInHiddenDiv">';
//                     content +='<label for="plus_time_appointment'+id+'">Plus Time</label>';
//                     content +='<select data-select="appointment" data-id="'+id+'" name="plus_time_appointment'+id+'" id="plus_time_appointment'+id+'" class="form-control select-appointment-plus_time" style="width:100%;"></select>';
//                     content +='<input type="hidden" name="appointment_total_service_price'+id+'" id="appointment_total_service_price'+id+'" class="form-control appointment_total_service_price'+id+'" value="0">';
//                 content +='</div>';
//             content +='</div>';
//             content +='<div class="row hidden requiredTherapist requiredTherapist'+id+' walkInHiddenDiv">';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="appointment_masseur1'+id+'">Masseur 1</label><span class="isRequired">*</span>';
//                     content +='<select data-select="appointment" data-id="'+id+'" name="appointment_masseur1'+id+'" id="appointment_masseur1'+id+'" class="form-control select-appointment-masseur1" style="width:100%;"></select>';
//                     content +='<input type="hidden" name="appointment_masseur1'+id+'_id" id="appointment_masseur1'+id+'_id" class="form-control">';
//                     content +='<input type="hidden" name="appointment_masseur1'+id+'_id_prev" id="appointment_masseur1'+id+'_id_prev" class="form-control">';
//                     content +='<div class="custom-control custom-checkbox">';
//                         content +='<input data-select="appointment" disabled data-id="'+id+'" class="custom-control-input isAppointmentMultipleMasseur" type="checkbox" id="appointmentCustomCheckbox'+id+'" value="1">';
//                         content +='<label for="appointmentCustomCheckbox'+id+'" class="custom-control-label">Is multiple Masseur ?</label>';
//                     content +='</div>';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="appointment_masseur2'+id+'">Masseur 2</label>';
//                     content +='<select data-select="appointment" data-id="'+id+'" name="appointment_masseur2'+id+'" id="appointment_masseur2'+id+'" class="form-control select-appointment-masseur2" style="width:100%;" disabled></select>';
//                     content +='<input type="hidden" name="appointment_masseur2'+id+'_id" id="appointment_masseur2'+id+'_id" class="form-control">';
//                     content +='<input type="hidden" name="appointment_masseur2'+id+'_id_prev" id="appointment_masseur2'+id+'_id_prev" class="form-control">';
//                     content +='<input type="hidden" name=appointment_masseur2'+id+'_id_val" id="appointment_masseur2'+id+'_id_val" class="form-control">';
//                 content +='</div>';
//                 content +='<div class="col-md-4">';
//                     content +='<label for="appointment_room'+id+'">Room #</label><span class="isRequired">*</span>';
//                     content +='<select data-select="appointment" data-id="'+id+'" name="appointment_room'+id+'" id="appointment_room'+id+'" class="form-control select-appointment-room" style="width:100%;"></select>';
//                     content +='<input type="hidden" class="form-control" id="appointment_room_id'+id+'">';
//                 content +='</div>';
//             content +='</div>';
//         content +='</div>';
//     content +='</div>';
//     $( content ).appendTo(".tabFormAppointment");

//     getAppointmentType(id);

//     getServicesAppointment(spa_id, id, 'service_name_appointment');
//     getServicesAppointment(spa_id, id, 'service_name_appointment_walkin');

//     setDateTimePicker('start_time_appointment', id);
//     setDateTimePicker('start_time_appointment_walkin', id);

//     var currentDate = new Date();
//     var currentDateTime = currentDate.toISOString().slice(0, 16);
//     $(".start_time_appointment").attr("min", currentDateTime);
//     $(".start_time_appointment_walkin").attr("min", currentDateTime);
// }