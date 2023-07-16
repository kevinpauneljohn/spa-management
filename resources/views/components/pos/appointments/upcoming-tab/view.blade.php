@if(auth()->user()->hasRole('owner') || auth()->user()->can('view sales'))
    <div class="modal fade" id="view-appointment-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="view-appointment-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title viewAppointmentTitle"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="view_full_name">Full Name : </label>
                                    <p class="viewAppointmentFullname"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="view_date_of_birth">Date of Birth : </label>
                                    <p class="viewAppointmentDateOfBirth"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="view_mobile_number">Mobile Number : </label>
                                    <p class="viewAppointmentMobileNumber"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="view_email">Email Address : </label>
                                    <p class="viewAppointmentEmail"></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="view_email">Address : </label>
                                    <p class="viewAppointmentAddress"></p>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="view_batch">Batch # : </label>
                                    <p class="viewAppointmentBatch"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="view_type">Type : </label>
                                    <p class="viewAppointmentType"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="view_status">Status : </label>
                                    <p class="viewAppointmentStatus"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="view_service">Client Type : </label>
                                    <p class="viewAppointmentClientType"></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="view_start_time">Appointment Time : </label>
                                    <p class="viewAppointmentStartTime"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif