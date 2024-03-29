
@if(auth()->user()->hasRole('owner') || auth()->user()->can('add sales'))
    <button class="btn btn-block btn-outline-info btn" id="addNewAppointment">
        <i class="fas fa-calendar-plus"></i>
    </button>

    <div class="modal fade" id="add-new-appointment-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="appointment-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Set New Appointment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tabList">
                            <input type="hidden" class="form-control" id="guest_ids_val" value="1">
                            <ul class="nav nav-pills dataTabsAppointment">

                            </ul>
                        </div>
                        <br />

                        <div class="tab-content tabFormAppointment">
                            <div class="tab-pane" id="summaryTab">
                                <div class="alert alert-danger alert-dismissible">
                                    <h5><i class="icon fas fa-info"></i> Reminder!!!</h5>
                                    The total amount can change depending on the selected services.
                                </div>
                                <div class="tableSummaryAppointment"></div>
                                <div class="py-2 px-3 mt-4">
                                    <div class="col-md-4 border border-danger float-right">
                                        <h2 class="mb-0 total_amount_appointment text-center"></h2>
                                        <h4 class="mt-0 text-center">TOTAL</h4>
                                        <input type="hidden" class="form-control" id="totalAmountToPayAppointment">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary process-appointment-btn">Process</button>
                        <button type="button" class="btn btn-primary add-appointment-btn hidden">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif

@push('js')
    @if(auth()->check())
        <!-- <script src="{{asset('js/frontdesk/BookAppointmentComponent/app.js')}}"></script>
        <script src="{{asset('js/frontdesk/BookAppointmentComponent/forms.js')}}"></script>
        <script src="{{asset('js/frontdesk/BookAppointmentComponent/filter-client.js')}}"></script>
        <script src="{{asset('js/frontdesk/BookAppointmentComponent/actions.js')}}"></script>
        <script src="{{asset('js/frontdesk/TherapistAvailabilityComponent/therapistAvailabiltyFunction.js')}}"></script>
        <script src="{{asset('js/frontdesk/UpcomingAppointmentComponent/upComingGuestFunction.js')}}"></script> -->
        <script>

        </script>
    @endif
@endpush
