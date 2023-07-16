@if(auth()->user()->hasRole('owner') || auth()->user()->can('view invoices'))
    <div class="modal fade" id="view-invoice-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form role="form" id="invoice-view-form" class="form-submit">
            @csrf
            <div class="modal-dialog modal-md modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title viewNameInvoice"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    <!-- <div class="callout callout-info">
                                    <h5><i class="fas fa-info"></i> Note:</h5>
                                    This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                                    </div> -->

                                    <div class="invoice p-3 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    <i class="fas fa-globe"></i> <span class="spaName"></span>
                                                    <small class="float-right"><b>Date : </b>{{date('F d, Y')}}</small>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row invoice-info">
                                            <div class="col-sm-6 invoice-col">
                                                From
                                                <address>
                                                    <strong><span class="spaName"></span></strong><br>
                                                    <span class="spaAddress"></span><br>
                                                    <span class="spaMobile"></span><br>
                                                    <span class="spaEmail"></span>
                                                </address>
                                            </div>
                                            <!-- <div class="col-sm-4 invoice-col">
                                                To
                                                <address>
                                                    <strong><span class="clientName"></span></strong><br>
                                                    <span class="clientAddress"></span><br>
                                                    <span class="clientMobile"></span><br>
                                                    <span class="clientEmail"></span>
                                                </address>
                                            </div> -->
                                            <div class="col-sm-6 invoice-col">
                                                <span class="salesInvoiceNumber float-right"></span>
                                                <!-- <br><br>
                                                <b>Order ID:</b> <span class="salesId"></span><br>
                                                <b>Payment Due:</b> <span class="transactionEndDate"></span><br>
                                                <b>Account:</b> -->
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table id="invoiceTable" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Client</th>
                                                            <th>Service</th>
                                                            <th>Room #</th>
                                                            <th>Start Time</th>
                                                            <th>End Time #</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <p class="lead">Payment Methods:</p>
                                                <span class="paymentMethod"></span>

                                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                                    <!-- Sample Notes Here..... -->
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <!-- <p class="lead">Amount Due <span class="transactionEndDate"></span></p> -->

                                                <div class="table-responsive">
                                                    <table id="summaryTotal" class="table">

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row no-print">
                                            <div class="col-12">
                                                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                                <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                                                    Payment
                                                </button>
                                                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                                    <i class="fas fa-download"></i> Generate PDF
                                                </button>
                                            </div>
                                        </div> -->
                                    </div>
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
@endif