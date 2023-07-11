<div class="modal fade" id="client-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><td>Date:</td><td id="date"></td></tr>
                    <tr><td>Mobile Number:</td><td id="mobile_number"></td></tr>
                    <tr><td>Email:</td><td id="email"></td></tr>
                    <tr><td>Appointment Type:</td><td id="appointment_type"></td></tr>
                    <tr><td>Client Type:</td><td id="client_type"></td></tr>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@once
    @push('js')

    @endpush
@endonce
