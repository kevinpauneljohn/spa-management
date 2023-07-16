<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    Green color means available, Gray color means occupied.
</div>
<div class="row displayRoomData">

</div>

<div class="modal fade" id="view-sales-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <form role="form" id="sales-view-form" class="form-submit">
        @csrf
        <div class="modal-dialog modal-md modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title viewRoomNumber"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="view_full_name">Full Name : </label>
                                <p class="viewFullname"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_date_of_birth">Date of Birth : </label>
                                <p class="viewDateOfBirth"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_mobile_number">Mobile Number : </label>
                                <p class="viewMobileNumber"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="view_email">Email Address : </label>
                                <p class="viewEmail"></p>
                            </div>
                            <div class="col-md-6">
                                <label for="view_email">Address : </label>
                                <p class="viewAddress"></p>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="view_service">Services : </label>
                                <p class="viewService"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_therapist_1">Masseur 1 : </label>
                                <p class="viewTherapist1"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_therapist_2">Masseur 2 : </label>
                                <p class="viewTherapist2"></p>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="view_start_time">Start Time : </label>
                                <p class="viewStartTime"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_end_time">End Time : </label>
                                <p class="viewEndTime"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="view_remaining_time">Remaining : </label>
                                <p class="viewRemainingTime"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="view_plus_time">Plus Time : </label>
                                <p class="viewPlusTime"></p>
                            </div>
                            <div class="col-md-6 border border-danger float-right">
                                <h2 class="mb-0 text-center totalAmountViewFormatted"></h2>
                                <h4 class="mt-0 text-center">TOTAL</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
@push('css')
    <style>

    </style>
@endpush

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/frontdesk/BookAppointmentComponent/roomAvailabilityFunction.js')}}"></script>
        <script>
            loadRoomAvailability($('.spaId').val());
        </script>
    @endif
@endpush
