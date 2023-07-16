@if(auth()->user()->hasRole('owner') || auth()->user()->can('move sales'))
    <div class="modal fade" id="move-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="move-appointment-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-md modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title viewAppointmentMoveTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible">
                            <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                            The total amount can change depending on the selected services and plus time.
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="move_app_firstname">First Name : </label>
                                    <input type="text" class="form-control move_app_firstname" id="move_app_firstname" disabled>
                                    <input type="hidden" class="form-control move_app_client_id" id="move_app_client_id">
                                    <input type="hidden" class="form-control move_app_id" id="move_app_id">
                                </div>
                                <div class="col-md-4">
                                    <label for="move_app_middlename">Middle Name : </label>
                                    <input type="text" class="form-control move_app_middlename" id="move_app_middlename" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="move_app_lastname">Last Name : </label>
                                    <input type="text" class="form-control move_app_lastname" id="move_app_lastname" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="move_app_date_of_birth">Date of Birth : </label>
                                    <input type="date" class="form-control move_app_date_of_birth" id="move_app_date_of_birth">
                                </div>
                                <div class="col-md-6">
                                    <label for="move_app_mobile_number">Mobile Number : </label>
                                    <input type="text" class="form-control move_app_mobile_number" id="move_app_mobile_number" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="move_app_email">Email Address : </label>
                                    <input type="email" class="form-control move_app_email" id="move_app_email">
                                </div>
                                <div class="col-md-6">
                                    <label for="move_app_address">Address : </label>
                                    <input type="email" class="form-control move_app_address" id="move_app_address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="move_app_appointment_type">Appointment Type : </label><span class="isRequired">*</span>
                                    <select data-id="_move" name="move_app_appointment_type" id="appointment_name_appointmentmove" class="form-control appointment_name_appointmentmove" style="width:100%;" disabled></select>
                                </div>
                                <div class="col-md-6 socialMedialMove">
                                    <label for="move_app_social_media_appointment">Social Media Type : </label>
                                    <select data-id="_move" name="move_app_social_media_appointment" id="social_media_appointmentmove" class="form-control social_media_appointmentmove" style="width:100%;" disabled></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 moveStartTimeDiv">
                                    <label for="move_app_start_time">Start Time : </label><span class="isRequired">*</span>
                                    <input type="datetime-local" id="start_time_appointment_move" name="start_time_appointment_move" class="form-control">
                                </div>
                                <div class="col-md-4 moveServicesDiv">
                                    <label for="move_app_services">Services : </label><span class="isRequired">*</span>
                                    <select data-select="move" data-id="_up" name="move_app_services" id="move_app_servicesmove" class="form-control select-services-move-appointment" style="width:100%;"></select>
                                    <input type="hidden" name="price_appointment_move" id="price_appointment_move" class="form-control" value="0">
                                    <input type="hidden" name="move_app_services_id" id="move_app_services_id" class="form-control">
                                    <input type="hidden" name="move_app_services_name" id="move_app_services_name" class="form-control">
                                    <input type="hidden" name="move_app_services_multiple" id="move_app_services_multiple" class="form-control">
                                </div>
                                <div class="col-md-4 movePlusTimeDiv">
                                    <label for="move_plus_time">Plus Time : </label>
                                    <select data-select="move" name="move_plus_time" id="move_plus_time" class="form-control select-move-plus_time" style="width:100%;" disabled></select>
                                    <input type="hidden" name="move_plus_time_price" id="move_plus_time_price" class="form-control" value="0">
                                    <input type="hidden" name="move_plus_time_id" id="move_plus_time_id" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 moveRoomDiv hidden">
                                    <label for="move_room">Room #</label><span class="isRequired">*</span>
                                    <select data-select="move" name="move_room" id="move_room" class="form-control select-move-room" style="width:100%;"></select>
                                    <input type="hidden" class="form-control" id="move_room_id">
                                </div>
                                <div class="col-md-4 moveMasseur1Div hidden">
                                    <label for="move_masseur1">Masseur 1</label><span class="isRequired">*</span>
                                    <select data-select="move" name="move_masseur1" id="move_masseur1" class="form-control select-move-masseur1" style="width:100%;"></select>
                                    <input type="hidden" name="move_masseur1_id" id="move_masseur1_id" class="form-control">
                                </div>
                                <div class="col-md-4 moveMasseur2Div hidden">
                                    <label for="move_masseur2">Masseur 2</label><span class="isRequired">*</span>
                                    <select data-select="move" name="move_masseur2" id="move_masseur2" class="form-control select-move-masseur2" style="width:100%;"></select>
                                    <input type="hidden" name="move_masseur2_id" id="move_masseur2_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="mb-0 text-center totalAmountMoveAppointmentFormatted float-right"></h2>
                                    <input type="hidden" class="form-control" id="totalAmountMoveToPay">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary move-sales-appointment-btn">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif