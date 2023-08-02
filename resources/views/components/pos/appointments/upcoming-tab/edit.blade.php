@if(auth()->user()->hasRole('owner') || auth()->user()->can('edit sales'))
    <div class="modal fade" id="update-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="update-appointment-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-md modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title viewAppointmentUpdateTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="edit_app_firstname">First Name : </label><span class="isRequired">*</span>
                                    <input type="text" class="form-control edit_app_firstname" id="edit_app_firstname" disabled>
                                    <input type="hidden" class="form-control edit_app_client_id" id="edit_app_client_id">
                                    <input type="hidden" class="form-control edit_app_id" id="edit_app_id">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_app_middlename">Middle Name : </label>
                                    <input type="text" class="form-control edit_app_middlename" id="edit_app_middlename" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_app_lastname">Last Name : </label><span class="isRequired">*</span>
                                    <input type="text" class="form-control edit_app_lastname" id="edit_app_lastname" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="edit_app_date_of_birth">Date of Birth : </label>
                                    <input type="date" class="form-control edit_app_date_of_birth" id="edit_app_date_of_birth">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_app_mobile_number">Mobile Number : </label><span class="isRequired">*</span>
                                    <input type="text" class="form-control edit_app_mobile_number" id="edit_app_mobile_number" maxlength="10">
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_app_mobile_number">Client type : </label>
                                    <input type="text" class="form-control edit_app_client_type" id="edit_app_client_type" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_app_email">Email Address : </label>
                                    <input type="email" class="form-control edit_app_email" id="edit_app_email">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_app_address">Address : </label>
                                    <input type="email" class="form-control edit_app_address" id="edit_app_address">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_app_appointment_type">Appointment Type : </label>
                                    <select data-id="_up" name="edit_app_appointment_type" id="appointment_name_appointmentup" class="form-control appointment_name_appointmentup" style="width:100%;"></select>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_app_start_time">Appointment Time : </label>
                                    <input type="datetime-local" id="start_time_appointment_up" name="start_time_appointment_up" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 socialMedialUpdate">
                                    <label for="edit_app_social_media_appointment">Social Media Type : </label>
                                    <select data-id="_up" name="edit_app_social_media_appointment" id="social_media_appointmentup" class="form-control social_media_appointmentup" style="width:100%;"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary update-appointment-btn">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif