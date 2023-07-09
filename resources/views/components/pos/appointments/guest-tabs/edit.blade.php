@if(auth()->user()->hasRole('owner') || auth()->user()->can('edit sales'))
    <div class="modal fade" id="update-sales-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="sales-update-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-md modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Update Transaction</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <input type="hidden" class="form-control" id="edit_transaction_id">
                                <input type="hidden" class="form-control" id="edit_client_id">
                                <input type="hidden" class="form-control" id="edit_sales_id">
                                <div class="col-md-4">
                                    <label for="edit_first_name">First Name</label><span class="isRequired">*</span>
                                    <input type="text" name="edit_first_name" id="edit_first_name" class="form-control" disabled>
                                    <p class="text-danger hidden" id="error-edit_first_name"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_middle_name">Middle Name</label>
                                    <input type="text" name="edit_middle_name" id="edit_middle_name" class="form-control" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_last_name">Last Name</label><span class="isRequired">*</span>
                                    <input type="text" name="edit_last_name" id="edit_last_name" class="form-control" disabled>
                                    <p class="text-danger hidden" id="error-edit_last_name"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="edit_date_of_birth">Date of Birth</label><span class="isRequired">*</span>
                                    <input type="date" name="edit_date_of_birth" id="edit_date_of_birth" class="form-control" disabled>
                                    <p class="text-danger hidden" id="error-edit_date_of_birth"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_mobile_number">Mobile Number</label><span class="isRequired">*</span>
                                    <input type="text" name="edit_mobile_number" id="edit_mobile_number" class="form-control" maxlength="10">
                                    <p class="text-danger hidden" id="error-edit_mobile_number"><p>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_email">Email</label>
                                    <input type="email" name="edit_email" id="edit_email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_client_type">Client Type</label>
                                    <input type="text" name="edit_client_type" id="edit_client_type" class="form-control" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_address">Address</label>
                                    <input type="text" name="edit_address" id="edit_address" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 edit_services_div">
                                    <label for="edit_services">Services</label><span class="isRequired">*</span>
                                    <select data-select="edit" name="edit_services" id="edit_services" class="form-control select-edit-services" style="width:100%;"></select>
                                    <input type="text" name="edit_price" id="edit_price" class="form-control">
                                    <input type="text" id="multiple_masseur" class="form-control">
                                    <p class="text-danger hidden" id="error-edit_services"></p>
                                </div>
                                <div class="col-md-4 edit_masseur1_div">
                                    <label for="edit_masseur1">Masseur 1</label><span class="isRequired">*</span>
                                    <select data-select="edit" name="edit_masseur1" id="edit_masseur1" class="form-control select-edit-masseur1" style="width:100%;"></select>
                                    <input type="text" name="edit_masseur1_id" id="edit_masseur1_id" class="form-control">
                                    <input type="text" name="edit_masseur1_id_prev" id="edit_masseur1_id_prev" class="form-control">
                                    <p class="text-danger hidden" id="error-edit_masseur1"></p>
                                </div>
                                <div class="col-md-4 edit_masseur2_div">
                                    <label for="edit_masseur2">Masseur 2</label>
                                    <select data-select="edit" name="edit_masseur2" id="edit_masseur2" class="form-control select-edit-masseur2" style="width:100%;"></select>
                                    <input type="text" name="edit_masseur2_id" id="edit_masseur2_id" class="form-control">
                                    <input type="text" name="edit_masseur2_id_prev" id="edit_masseur2_id_prev" class="form-control">
                                    <input type="text" name="edit_masseur2_id_val" id="edit_masseur2_id_val" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="edit_start_time">Start Time</label>
                                    <input type="datetime-local" id="edit_start_time" name="edit_start_time" class="form-control" disabled>
                                    <p class="text-danger hidden" id="error-edit_start_time"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_plus_time">Plus Time</label>
                                    <select data-select="edit" name="edit_plus_time" id="edit_plus_time" class="form-control select-edit-plus_time" style="width:100%;"></select>
                                    <input type="text" name="edit_plus_time_price" id="edit_plus_time_price" class="form-control">
                                    <p class="text-danger hidden" id="error-edit_services"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="edit_room">Room #</label>
                                    <select data-select="edit" name="edit_room" id="edit_room" class="form-control select-edit-room" style="width:100%;"></select>
                                    <input type="text" name="edit_room_val" id="edit_room_val" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="py-2 px-3 mt-4">
                                        <div class="col-md-4 border border-danger float-right">
                                            <h2 class="mb-0 text-center totalAmountFormatted"></h2>
                                            <h4 class="mt-0 text-center">TOTAL</h4>
                                            <input type="hidden" class="form-control" id="totalAmountEditToPay">
                                            <input type="hidden" class="form-control" id="totalAmountEditToPayOld">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary update-sales-btn">
                            <span class="spinner-border spinner-border-sm spinner-update-btn hidden"></span>
                            <span class="text-update-btn">Save</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif
@section('css')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection